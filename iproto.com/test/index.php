<!DOCTYPE html>
<html>
<head>
    <title>VESC Terminal</title>
    <style>
        #terminal { 
            width: 800px; 
            height: 500px; 
            background: #000; 
            color: #0f0; 
            overflow-y: scroll; 
            padding: 10px; 
            font-family: monospace; 
            white-space: pre-wrap;
        }
        .hex { color: #888; font-size: 12px; }
        .telemetry { color: #0af; }
        .command { color: #ff0; }
    </style>
</head>
<body>
    <button id="connectBtn">Connect VESC</button>
    <span id="status">Disconnected</span>
    <div id="terminal"></div>
    <input type="text" id="input" placeholder="Type command (e.g., volt, version, status)" style="width: 80%;">
    <button id="sendBtn">Send</button>

    <script type="module">
        import { connectVESC, sendCommand } from './vesc_ble.js';
        import { crc16 } from './crc_utils.js';

        const terminal = document.getElementById('terminal');
        const input = document.getElementById('input');
        const statusSpan = document.getElementById('status');
        let writeChar = null; // will be set by connectVESC

        // We need to hijack the writeChar from vesc_ble.js, so we'll store it.
        // Better: re-export writeChar from vesc_ble.js, but for simplicity we'll
        // create our own send function that uses the same packet building.
        // Actually we can use sendCommand from vesc_ble.js – it already builds packets.
        // But we want to send text commands with command ID 0x14.
        // Let's keep using sendCommand from vesc_ble.js, but we'll build our payload.

        // Override sendCommand to accept payload array directly (already does)
        // We'll import sendCommand and use it.

        // Helper to log
        function log(msg, className = '') {
            const div = document.createElement('div');
            div.textContent = msg;
            if (className) div.className = className;
            terminal.appendChild(div);
            terminal.scrollTop = terminal.scrollHeight;
        }

        function logHex(data, prefix) {
            const hex = Array.from(data).map(b => b.toString(16).padStart(2,'0')).join(' ');
            log(`${prefix} ${hex}`, 'hex');
        }

        // Build a VESC packet from payload (used for telemetry command)
        function buildPacket(payload) {
            const len = payload.length;
            const packet = new Uint8Array(len + 5);
            packet[0] = 0x02;
            packet[1] = len;
            packet.set(payload, 2);
            const crc = crc16(payload);
            packet[len + 2] = (crc >> 8) & 0xFF;
            packet[len + 3] = crc & 0xFF;
            packet[len + 4] = 0x03;
            return packet;
        }

        // Send a text command (COMM_TERMINAL_CMD = 0x14)
        async function sendTextCommand(text) {
            const encoder = new TextEncoder();
            const textBytes = encoder.encode(text);
            const payload = new Uint8Array([0x14, ...textBytes]);
            await sendCommand(payload);
            log(`> ${text}`, 'command');
        }

        // Send telemetry request (COMM_GET_VALUES = 0x04)
        async function sendTelemetry() {
            const payload = new Uint8Array([0x04]);
            await sendCommand(payload);
        }

        // Decode telemetry response
        function decodeTelemetry(payload) {
            if (payload.length < 33) {
                log("Telemetry packet too short", 'hex');
                return;
            }
            const dv = new DataView(payload.buffer);
            let offset = 1; // skip command byte

            const tempMosfet = dv.getInt16(offset) / 10;   offset += 2;
            const tempMotor  = dv.getInt16(offset) / 10;   offset += 2;
            const currentMotor = dv.getFloat32(offset);    offset += 4;
            const currentIn    = dv.getFloat32(offset);    offset += 4;
            const dutyNow      = dv.getInt16(offset) / 1000; offset += 2;
            const rpm          = dv.getInt32(offset);      offset += 4;
            const inputVoltage = dv.getInt16(offset) / 10; offset += 2;
            const ampHours     = dv.getFloat32(offset);    offset += 4;
            const ampHoursCharged = dv.getFloat32(offset); offset += 4;
            const wattHours    = dv.getFloat32(offset);    offset += 4;
            const wattHoursCharged = dv.getFloat32(offset); offset += 4;
            const tachometer   = dv.getInt32(offset);      offset += 4;
            const tachometerAbs= dv.getInt32(offset);      offset += 4;
            const faultCode    = dv.getInt16(offset);

            log('--- Real-time Telemetry ---', 'telemetry');
            log(`? Input Voltage:   ${inputVoltage.toFixed(2)} V`, 'telemetry');
            log(`?? Motor RPM:       ${rpm}`, 'telemetry');
            log(`?? Duty Cycle:      ${(dutyNow * 100).toFixed(1)}%`, 'telemetry');
            log(`?? Motor Current:   ${currentMotor.toFixed(2)} A`, 'telemetry');
            log(`?? Input Current:   ${currentIn.toFixed(2)} A`, 'telemetry');
            log(`??? MOSFET Temp:     ${tempMosfet.toFixed(1)} °C`, 'telemetry');
            log(`?? Motor Temp:      ${tempMotor.toFixed(1)} °C`, 'telemetry');
            log(`?? Amp Hours:       ${ampHours.toFixed(2)} Ah`, 'telemetry');
            log(`?? Amp Hours Charged: ${ampHoursCharged.toFixed(2)} Ah`, 'telemetry');
            log(`? Watt Hours:      ${wattHours.toFixed(2)} Wh`, 'telemetry');
            log(`?? Watt Hours Charged: ${wattHoursCharged.toFixed(2)} Wh`, 'telemetry');
            log(`?? Tachometer:      ${tachometer}`, 'telemetry');
            log(`?? Tachometer Abs:  ${tachometerAbs}`, 'telemetry');
            log(`?? Fault Code:      ${faultCode} (0 = no fault)`, 'telemetry');
            log('------------------------', 'telemetry');
        }

        // Packet parser
        let rxBuffer = new Uint8Array(0);

        function handleReceivedData(newData) {
            logHex(newData, 'RX');
            // Append
            let combined = new Uint8Array(rxBuffer.length + newData.length);
            combined.set(rxBuffer);
            combined.set(newData, rxBuffer.length);
            rxBuffer = combined;

            while (rxBuffer.length >= 5) {
                // Find start byte 0x02
                let idx = 0;
                while (idx < rxBuffer.length && rxBuffer[idx] !== 0x02) idx++;
                if (idx === rxBuffer.length) {
                    rxBuffer = new Uint8Array(0);
                    return;
                }
                if (idx > 0) rxBuffer = rxBuffer.slice(idx);

                if (rxBuffer.length < 5) return;
                const len = rxBuffer[1];
                const totalLen = len + 5;
                if (rxBuffer.length < totalLen) return;

                const packet = rxBuffer.slice(0, totalLen);
                rxBuffer = rxBuffer.slice(totalLen);

                if (packet[totalLen - 1] !== 0x03) {
                    log("Bad stop byte", 'hex');
                    continue;
                }

                const payload = packet.slice(2, 2 + len);
                const receivedCrc = (packet[len+2] << 8) | packet[len+3];
                const computedCrc = crc16(payload);
                if (computedCrc !== receivedCrc) {
                    log(`CRC error (got 0x${receivedCrc.toString(16)}, expected 0x${computedCrc.toString(16)})`, 'hex');
                    continue;
                }

                const cmd = payload[0];
                const data = payload.slice(1);
                log(`CMD 0x${cmd.toString(16)}`, 'hex');

                if (cmd === 0x04) {
                    decodeTelemetry(payload);
                } else {
                    // Try to decode as text
                    try {
                        const txt = new TextDecoder().decode(data);
                        const trimmed = txt.trim();
                        if (trimmed) log(`RCV: ${trimmed}`);
                    } catch(e) {
                        log(`(binary data, ${data.length} bytes)`, 'hex');
                    }
                }
            }
        }

        // Override the connectVESC to inject our handler
        // Since connectVESC expects a callback, we'll call the original but replace its onDataCallback.
        // However, the original connectVESC from vesc_ble.js uses its own onDataCallback.
        // We'll re-implement connect here to have full control (but keep using the same BLE logic).
        // To avoid duplication, we can modify vesc_ble.js to export writeChar, but simplest is to write our own connect.
        // But the user already has vesc_ble.js, so let's use it but we need to capture the writeChar.
        // Actually vesc_ble.js exports connectVESC and sendCommand. sendCommand uses the internal writeChar.
        // That's fine; we just need to provide a data callback that uses our parser.
        
        import { connectVESC as originalConnect, sendCommand as originalSend } from './vesc_ble.js';
        // Override sendCommand? No, we'll use originalSend.

        // We'll create a wrapper that calls originalConnect with our handler
        async function myConnect() {
            try {
                await originalConnect(handleReceivedData);
                statusSpan.textContent = "Connected";
                log("Connected to VESC. Type commands (e.g., volt, status).");
            } catch (err) {
                log(`Connection error: ${err.message}`);
                statusSpan.textContent = "Error";
            }
        }

        // Re-export sendCommand as is
        // But we need to send text commands via originalSend with payload built as 0x14 + text
        // So we'll define our own send function that uses originalSend.
        window.sendCommand = originalSend; // expose for our use

        document.getElementById('connectBtn').addEventListener('click', myConnect);
        document.getElementById('sendBtn').addEventListener('click', async () => {
            const cmd = input.value.trim();
            if (!cmd) return;
            if (cmd.toLowerCase() === 'status') {
                const payload = new Uint8Array([0x04]);
                await originalSend(payload);
                log("> status (telemetry request)", 'command');
            } else {
                const encoder = new TextEncoder();
                const textBytes = encoder.encode(cmd);
                const payload = new Uint8Array([0x14, ...textBytes]);
                await originalSend(payload);
                log(`> ${cmd}`, 'command');
            }
            input.value = '';
        });
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') document.getElementById('sendBtn').click();
        });
    </script>
</body>
</html>