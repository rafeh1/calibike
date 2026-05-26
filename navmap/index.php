<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>CaliBike 33C3 Dashboard</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            width: 100%;
            height: 100%;
            overflow: hidden;
            font-family: 'Courier New', monospace;
            background: #1a1a1a;
        }

        #wrapper {
            display: flex;
            flex-direction: row;
            width: 100vw;
            height: 100vh;
        }

        #sidebar {
            width: 400px;
            height: 100vh;
            background: #1e1e1e;
            padding: 20px;
            border-right: 1px solid #444;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            color: #0f0;
        }

        #top-controls {
            flex: 0 0 auto;
        }

        #telemetry-panel {
            flex: 0 0 auto;
            margin-top: 10px;
            padding: 10px;
            background: #111;
            border: 1px solid #333;
            font-size: 14px;
            line-height: 1.4em;
        }

        #bottom-terminal {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 0;
            margin-top: 10px;
        }

        #terminal {
            flex: 1;
            background: #000;
            color: #0f0;
            overflow-y: auto;
            padding: 12px;
            border: 1px solid #333;
            white-space: pre-wrap;
            font-size: 13px;
        }

        .input-line {
            flex: 0 0 auto;
            margin-top: 6px;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        #cmd-input {
            flex: 1;
            background: #111;
            color: #0f0;
            border: 1px solid #444;
            padding: 10px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
        }

        button {
            background: #222;
            color: #0f0;
            border: 1px solid #0f0;
            padding: 10px 20px;
            cursor: pointer;
            font-family: 'Courier New', monospace;
        }

        .debug-check {
            margin-left: 10px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #222;
            padding: 5px 10px;
            border-radius: 4px;
        }

        #map {
            flex: 1;
            height: 100vh;
        }

        #status { margin-left: 8px; }
    </style>
</head>
<body>

<div id="wrapper">

    <div id="sidebar">

        <div id="top-controls">
            <button id="connectBtn">Connect to VESC</button>
            <span id="status">Disconnected</span>
            <label class="debug-check">
                <input type="checkbox" id="debugCheckbox"> Debug (show bytes)
            </label>
        </div>

        <div id="telemetry-panel">
            <div>Voltage: <span id="t_volt">--</span> V</div>
            <div>MOSFET Temp: <span id="t_temp">--</span></div>
            <div>Motor Temp: <span id="t_motor_temp">--</span></div>
            <div>Motor Current: <span id="t_motor">--</span> A</div>
            <div>Input Current: <span id="t_input">--</span> A</div>
            <div>Duty: <span id="t_duty">--</span></div>
            <div>RPM: <span id="t_rpm">--</span></div>
            <div>Raw: <span id="telemetry-raw">--</span></div>
        </div>

        <div id="bottom-terminal">
            <div id="terminal"></div>

            <div class="input-line">
                <input type="text" id="cmd-input" placeholder="Type command" autocomplete="off">
                <button id="sendBtn">Send</button>
            </div>
        </div>

    </div>

    <div id="map"></div>

</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
/* ---------- MAP ---------- */
var leafletMap = L.map('map').setView([33.238, 72.714], 16);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(leafletMap);

/* ---------- TERMINAL + BLE ---------- */
let writeChar = null;
let rxBuffer = [];
let debugEnabled = false;
let telemetryTimer = null;

const SERVICE_UUID = '6e400001-b5a3-f393-e0a9-e50e24dcca9e';
const RX_UUID      = '6e400002-b5a3-f393-e0a9-e50e24dcca9e';
const TX_UUID      = '6e400003-b5a3-f393-e0a9-e50e24dcca9e';

const terminal = document.getElementById('terminal');
const input    = document.getElementById('cmd-input');
const statusEl = document.getElementById('status');

const teleRaw  = document.getElementById('telemetry-raw');
const t_volt   = document.getElementById('t_volt');
const t_temp   = document.getElementById('t_temp');
const t_motor_temp = document.getElementById('t_motor_temp');
const t_motor  = document.getElementById('t_motor');
const t_input  = document.getElementById('t_input');
const t_duty   = document.getElementById('t_duty');
const t_rpm    = document.getElementById('t_rpm');

function log(t) {
    const d = document.createElement('div');
    d.textContent = t;
    terminal.appendChild(d);
    terminal.scrollTop = terminal.scrollHeight;
}

function hexLine(label, data) {
    const hex = Array.from(data)
        .map(b => b.toString(16).padStart(2,'0'))
        .join(' ');
    log(label + hex);
}

document.getElementById('debugCheckbox').onchange = e => {
    debugEnabled = e.target.checked;
    log("Debug " + (debugEnabled ? "ON" : "OFF"));
};

/* CRC16 */
function crc16(buf) {
    let crc = 0;
    for (let i=0;i<buf.length;i++) {
        crc ^= (buf[i] << 8);
        for (let j=0;j<8;j++)
            crc = (crc & 0x8000) ? (crc << 1) ^ 0x1021 : (crc << 1);
    }
    return crc & 0xFFFF;
}

/* Build terminal packet */
function buildTerminalPacket(text) {
    const textBytes = new TextEncoder().encode(text);
    const payload   = new Uint8Array([0x14, ...textBytes]);
    const len       = payload.length;

    const packet = new Uint8Array(len + 5);
    packet[0] = 0x02;
    packet[1] = len;
    packet.set(payload, 2);

    const crc = crc16(payload);
    packet[len+2] = (crc >> 8) & 0xFF;
    packet[len+3] = crc & 0xFF;
    packet[len+4] = 0x03;

    return packet;
}

/* Build telemetry packet */
function buildTelemetryPacket() {
    const payload = new Uint8Array([0x04]); // COMM_GET_VALUES
    const len     = payload.length;

    const packet = new Uint8Array(len + 5);
    packet[0] = 0x02;
    packet[1] = len;
    packet.set(payload, 2);

    const crc = crc16(payload);
    packet[len+2] = (crc >> 8) & 0xFF;
    packet[len+3] = crc & 0xFF;
    packet[len+4] = 0x03;

    return packet;
}

/* Connect */
async function connect() {
    try {
        log("Requesting VESC...");
        const device = await navigator.bluetooth.requestDevice({
            filters: [{ services: [SERVICE_UUID] }]
        });

        device.addEventListener('gattserverdisconnected', () => {
            statusEl.textContent = "Disconnected";
            log("Disconnected");
            if (telemetryTimer) {
                clearInterval(telemetryTimer);
                telemetryTimer = null;
            }
        });

        const server = await device.gatt.connect();
        const service = await server.getPrimaryService(SERVICE_UUID);

        writeChar = await service.getCharacteristic(RX_UUID);
        const notifyChar = await service.getCharacteristic(TX_UUID);

        await notifyChar.startNotifications();
        notifyChar.addEventListener('characteristicvaluechanged', handleNotification);

        statusEl.textContent = "Connected VESC";
        log("Connected to VESC");

        startTelemetry();
    } catch (e) {
        log("CONNECT ERROR: " + e.message);
    }
}

/* Start telemetry polling */
function startTelemetry() {
    if (telemetryTimer) return;
    telemetryTimer = setInterval(async () => {
        if (!writeChar) return;
        const pkt = buildTelemetryPacket();
        if (debugEnabled) hexLine("TELE SEND BYTES: ", pkt);
        try {
            await writeChar.writeValueWithoutResponse(pkt);
        } catch (e) {
            log("TELE SEND ERROR: " + e.message);
        }
    }, 500);
}

/* Incoming BLE chunks */
function handleNotification(event) {
    const chunk = new Uint8Array(event.target.value.buffer);

    if (debugEnabled)
        hexLine("RECV BYTES: ", chunk);

    for (let b of chunk) rxBuffer.push(b);
    parsePackets();
}

/* Packet parser */
function parsePackets() {
    while (true) {
        if (rxBuffer.length < 5) return;

        while (rxBuffer.length > 0 && rxBuffer[0] !== 0x02)
            rxBuffer.shift();

        if (rxBuffer.length < 5) return;

        const len   = rxBuffer[1];
        const total = len + 5;
        if (rxBuffer.length < total) return;

        const packet = rxBuffer.slice(0, total);
        rxBuffer = rxBuffer.slice(total);

        if (packet[total - 1] !== 0x03) continue;

        const payload = packet.slice(2, 2 + len);
        if (payload.length === 0) continue;

        const cmd  = payload[0];
        const data = payload.slice(1);

        if (debugEnabled) {
            hexLine("PKT CMD 0x" + cmd.toString(16) + " DATA: ", data);
        }

        /* Telemetry decode: cmd 0x04 (COMM_GET_VALUES) */
        if (cmd === 0x04) {

            /* RAW */
            const hex = Array.from(data)
                .map(b => b.toString(16).padStart(2,'0'))
                .join(' ');
            teleRaw.textContent = hex;

            // MOSFET temp (big-endian, /10)
            const tempMosfetRaw = (data[0] << 8) | data[1];
            t_temp.textContent = (tempMosfetRaw / 10).toFixed(1);

            // motor temp (big-endian signed, /10) — raw, no °C
            let tempMotorRaw = (data[2] << 8) | data[3];
            if (tempMotorRaw & 0x8000) tempMotorRaw -= 0x10000;
            t_motor_temp.textContent = (tempMotorRaw / 10).toFixed(1);

            // motor current (big-endian signed, /10)
            let motorCurrentRaw = (data[4] << 8) | data[5];
            if (motorCurrentRaw & 0x8000) motorCurrentRaw -= 0x10000;
            t_motor.textContent = (motorCurrentRaw / 10).toFixed(1);

            // input current (big-endian signed, /10)
            let inputCurrentRaw = (data[6] << 8) | data[7];
            if (inputCurrentRaw & 0x8000) inputCurrentRaw -= 0x10000;
            t_input.textContent = (inputCurrentRaw / 10).toFixed(1);

            // duty (big-endian signed, /1000)
            let dutyRaw = (data[8] << 8) | data[9];
            if (dutyRaw & 0x8000) dutyRaw -= 0x10000;
            t_duty.textContent = (dutyRaw / 1000).toFixed(3);

            // rpm (big-endian signed 32-bit)
            let rpmRaw =
                (data[10] << 24) |
                (data[11] << 16) |
                (data[12] << 8)  |
                 data[13];
            if (rpmRaw & 0x80000000) rpmRaw -= 0x100000000;
            t_rpm.textContent = rpmRaw;

            // voltage (big-endian, correct offset)
            const voltRaw = (data[26] << 8) | data[27];
            t_volt.textContent = (voltRaw / 10).toFixed(2);

            continue;
        }

        /* Text responses */
        try {
            const txt = new TextDecoder().decode(new Uint8Array(data)).trim();
            if (txt.length > 0)
                log("RCV: " + txt);
        } catch {}
    }
}

/* Send terminal command */
async function send() {
    if (!writeChar) { log("Not connected"); return; }

    const txt = input.value.trim();
    if (!txt) return;

    const packet = buildTerminalPacket(txt);

    if (debugEnabled)
        hexLine("TERM SEND BYTES: ", packet);

    for (let i=0;i<packet.length;i+=20) {
        await writeChar.writeValueWithoutResponse(packet.slice(i,i+20));
        await new Promise(r=>setTimeout(r,10));
    }

    log("> " + txt);
    input.value="";
}

document.getElementById("connectBtn").onclick = connect;
document.getElementById("sendBtn").onclick = send;
input.addEventListener("keypress", e => { if (e.key==="Enter") send(); });

</script>
</body>
</html>
