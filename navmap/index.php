<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>CaliBike 33C3 Dashboard</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { width: 100%; height: 100%; overflow: hidden; font-family: 'Courier New', monospace; background: #1a1a1a; }
        #wrapper { display: flex; flex-direction: row; width: 100vw; height: 100vh; }
        #sidebar { width: 400px; height: 100vh; background: #1e1e1e; padding: 20px; border-right: 1px solid #444; display: flex; flex-direction: column; overflow: hidden; color: #0f0; }
        #top-controls { flex: 0 0 auto; }
        #telemetry-panel { flex: 0 0 auto; margin-top: 10px; padding: 10px; background: #111; border: 1px solid #333; font-size: 14px; line-height: 1.6em; }
        #bottom-terminal { flex: 1; display: flex; flex-direction: column; min-height: 0; margin-top: 10px; }
        #terminal { flex: 1; background: #000; color: #0f0; overflow-y: auto; padding: 12px; border: 1px solid #333; white-space: pre-wrap; font-size: 13px; }
        .input-line { flex: 0 0 auto; margin-top: 6px; display: flex; gap: 10px; align-items: center; }
        #cmd-input { flex: 1; background: #111; color: #0f0; border: 1px solid #444; padding: 10px; font-family: 'Courier New', monospace; font-size: 13px; }
        button { background: #222; color: #0f0; border: 1px solid #0f0; padding: 10px 20px; cursor: pointer; font-family: 'Courier New', monospace; }
        .debug-check { margin-left: 10px; display: inline-flex; align-items: center; gap: 5px; background: #222; padding: 5px 10px; border-radius: 4px; }
        #map { flex: 1; height: 100vh; }
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
            <div>GPS Speed: <span id="t_gps_speed">--</span> km/h</div>
            <div>GPS Location: <span id="t_gps_loc">--</span></div>
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

/* ==== ALL DOM REFS FIRST — nothing runs before this ==== */
var terminal     = document.getElementById('terminal');
var input        = document.getElementById('cmd-input');
var statusEl     = document.getElementById('status');
var teleRaw      = document.getElementById('telemetry-raw');
var t_volt       = document.getElementById('t_volt');
var t_temp       = document.getElementById('t_temp');
var t_motor_temp = document.getElementById('t_motor_temp');
var t_motor      = document.getElementById('t_motor');
var t_input      = document.getElementById('t_input');
var t_duty       = document.getElementById('t_duty');
var t_rpm        = document.getElementById('t_rpm');
var t_gps_speed  = document.getElementById('t_gps_speed');
var t_gps_loc    = document.getElementById('t_gps_loc');

/* ==== BLE STATE ==== */
var writeChar      = null;
var rxBuffer       = [];
var debugEnabled   = false;
var telemetryTimer = null;

var SERVICE_UUID = '6e400001-b5a3-f393-e0a9-e50e24dcca9e';
var RX_UUID      = '6e400002-b5a3-f393-e0a9-e50e24dcca9e';
var TX_UUID      = '6e400003-b5a3-f393-e0a9-e50e24dcca9e';

/* ==== GPS STATE ==== */
var gpsMarker  = null;
var gpsCircle  = null;
var gpsCentered = false;

/* ==== MAP ==== */
var leafletMap = L.map('map').setView([33.8752, -117.5664], 16);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(leafletMap);

/* ==== LOG ==== */
function log(t) {
    var d = document.createElement('div');
    d.textContent = t;
    terminal.appendChild(d);
    terminal.scrollTop = terminal.scrollHeight;
}

function hexLine(label, data) {
    var hex = Array.from(data).map(function(b){ return b.toString(16).padStart(2,'0'); }).join(' ');
    log(label + hex);
}

/* ==== GPS ==== */
function startGPS() {
    if (!navigator.geolocation) { log("GPS: not supported"); return; }
    log("GPS: starting...");
    navigator.geolocation.watchPosition(
        function(pos) {
            var lat = pos.coords.latitude;
            var lng = pos.coords.longitude;
            var acc = pos.coords.accuracy;
            var spd = pos.coords.speed;

            t_gps_speed.textContent = (spd !== null && spd >= 0) ? (spd * 3.6).toFixed(1) : "0.0";
            t_gps_loc.textContent   = lat.toFixed(5) + ', ' + lng.toFixed(5);

            if (!gpsMarker) {
                var icon = L.divIcon({
                    className: '',
                    html: '<div style="width:16px;height:16px;background:#00ff00;border:3px solid #fff;border-radius:50%;box-shadow:0 0 8px #00ff00;"></div>',
                    iconSize: [16,16], iconAnchor: [8,8]
                });
                gpsMarker = L.marker([lat, lng], { icon: icon }).addTo(leafletMap);
            } else {
                gpsMarker.setLatLng([lat, lng]);
            }

            if (!gpsCircle) {
                gpsCircle = L.circle([lat, lng], { radius: acc, color:'#00ff00', fillColor:'#00ff0033', fillOpacity:0.3, weight:1 }).addTo(leafletMap);
            } else {
                gpsCircle.setLatLng([lat, lng]);
                gpsCircle.setRadius(acc);
            }

            if (!gpsCentered) { leafletMap.setView([lat, lng], 17); gpsCentered = true; }
            else { leafletMap.panTo([lat, lng]); }

            log("GPS: " + lat.toFixed(5) + ", " + lng.toFixed(5) + " ±" + acc.toFixed(0) + "m");
        },
        function(err) { log("GPS ERROR: " + err.message); t_gps_speed.textContent = "ERR"; },
        { enableHighAccuracy: true, maximumAge: 2000, timeout: 15000 }
    );
}

/* ==== CRC + PACKETS ==== */
function crc16(buf) {
    var crc = 0;
    for (var i = 0; i < buf.length; i++) {
        crc ^= (buf[i] << 8);
        for (var j = 0; j < 8; j++) crc = (crc & 0x8000) ? (crc << 1) ^ 0x1021 : (crc << 1);
    }
    return crc & 0xFFFF;
}

function buildPacket(payload) {
    var len = payload.length;
    var pkt = new Uint8Array(len + 5);
    pkt[0] = 0x02; pkt[1] = len;
    pkt.set(payload, 2);
    var crc = crc16(payload);
    pkt[len+2] = (crc >> 8) & 0xFF; pkt[len+3] = crc & 0xFF; pkt[len+4] = 0x03;
    return pkt;
}

function buildTerminalPacket(text) {
    var tb = new TextEncoder().encode(text);
    var payload = new Uint8Array(1 + tb.length);
    payload[0] = 0x14; payload.set(tb, 1);
    return buildPacket(payload);
}

function buildTelemetryPacket() { return buildPacket(new Uint8Array([0x04])); }

/* ==== BLE ==== */
async function connect() {
    try {
        log("Requesting VESC...");
        var device = await navigator.bluetooth.requestDevice({ filters: [{ services: [SERVICE_UUID] }] });
        device.addEventListener('gattserverdisconnected', function() {
            statusEl.textContent = "Disconnected"; log("Disconnected");
            if (telemetryTimer) { clearInterval(telemetryTimer); telemetryTimer = null; }
        });
        var server  = await device.gatt.connect();
        var service = await server.getPrimaryService(SERVICE_UUID);
        writeChar   = await service.getCharacteristic(RX_UUID);
        var notifyChar = await service.getCharacteristic(TX_UUID);
        await notifyChar.startNotifications();
        notifyChar.addEventListener('characteristicvaluechanged', handleNotification);
        statusEl.textContent = "Connected VESC+GPS";
        log("Connected to VESC");
        startTelemetry();
    } catch(e) { log("CONNECT ERROR: " + e.message); }
}

function startTelemetry() {
    if (telemetryTimer) return;
    telemetryTimer = setInterval(async function() {
        if (!writeChar) return;
        try { await writeChar.writeValueWithoutResponse(buildTelemetryPacket()); }
        catch(e) { log("TELE ERR: " + e.message); }
    }, 500);
}

function handleNotification(event) {
    var chunk = new Uint8Array(event.target.value.buffer);
    if (debugEnabled) hexLine("RECV: ", chunk);
    for (var b of chunk) rxBuffer.push(b);
    parsePackets();
}

function parsePackets() {
    while (true) {
        if (rxBuffer.length < 5) return;
        while (rxBuffer.length > 0 && rxBuffer[0] !== 0x02) rxBuffer.shift();
        if (rxBuffer.length < 5) return;
        var len = rxBuffer[1], total = len + 5;
        if (rxBuffer.length < total) return;
        var packet = rxBuffer.slice(0, total);
        rxBuffer = rxBuffer.slice(total);
        if (packet[total-1] !== 0x03) continue;
        var payload = packet.slice(2, 2+len);
        if (!payload.length) continue;
        var cmd = payload[0], data = payload.slice(1);
        if (debugEnabled) hexLine("PKT 0x"+cmd.toString(16)+": ", data);
        if (cmd === 0x04) {
            teleRaw.textContent = Array.from(data).map(function(b){ return b.toString(16).padStart(2,'0'); }).join(' ');
            t_temp.textContent = ((data[0]<<8|data[1])/10).toFixed(1);
            var mtr = data[2]<<8|data[3]; if(mtr&0x8000) mtr-=0x10000; t_motor_temp.textContent=(mtr/10).toFixed(1);
            var mc  = data[4]<<8|data[5]; if(mc &0x8000) mc -=0x10000; t_motor.textContent      =(mc /10).toFixed(1);
            var ic  = data[6]<<8|data[7]; if(ic &0x8000) ic -=0x10000; t_input.textContent      =(ic /10).toFixed(1);
            var du  = data[8]<<8|data[9]; if(du &0x8000) du -=0x10000; t_duty.textContent       =(du /1000).toFixed(3);
            var rpm = (data[10]<<24)|(data[11]<<16)|(data[12]<<8)|data[13];
            if(rpm&0x80000000) rpm-=0x100000000; t_rpm.textContent=rpm;
            t_volt.textContent = ((data[26]<<8|data[27])/10).toFixed(2);
            continue;
        }
        try { var txt=new TextDecoder().decode(new Uint8Array(data)).trim(); if(txt) log("RCV: "+txt); } catch(e){}
    }
}

async function send() {
    if (!writeChar) { log("Not connected"); return; }
    var txt = input.value.trim(); if (!txt) return;
    var pkt = buildTerminalPacket(txt);
    if (debugEnabled) hexLine("TX: ", pkt);
    for (var i=0; i<pkt.length; i+=20) {
        await writeChar.writeValueWithoutResponse(pkt.slice(i,i+20));
        await new Promise(function(r){ setTimeout(r,10); });
    }
    log("> "+txt); input.value="";
}

/* ==== WIRE BUTTONS ==== */
document.getElementById('connectBtn').onclick = connect;
document.getElementById('sendBtn').onclick    = send;
document.getElementById('debugCheckbox').onchange = function(e) {
    debugEnabled = e.target.checked; log("Debug " + (debugEnabled ? "ON" : "OFF"));
};
input.addEventListener('keypress', function(e){ if(e.key==='Enter') send(); });

/* ==== START GPS LAST — everything above is ready ==== */
startGPS();

</script>
</body>
</html>