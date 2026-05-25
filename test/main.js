import { crc16 } from './crc_utils.js';

let packetBuffer = new Uint8Array(0);

export function onDataReceived(newData, displayCallback) {
    // Accumulate into packet buffer
	const rawHex = Array.from(newData).map(b => b.toString(16).padStart(2, '0')).join(' ');
    	displayCallback(`RX: ${rawHex}`);

    packetBuffer = new Uint8Array([...packetBuffer, ...newData]);

    // Need at least start + len + crc + stop (5 bytes minimum)
    while (packetBuffer.length >= 5) {
        // 1. Find start byte 0x02
        if (packetBuffer[0] !== 0x02) {
            const idx = packetBuffer.indexOf(0x02);
            if (idx === -1) {
                packetBuffer = new Uint8Array(0);
                return;
            }
            packetBuffer = packetBuffer.slice(idx);
            if (packetBuffer.length < 5) return;
        }

        const len = packetBuffer[1];
        const fullLen = len + 5; // 0x02, LEN, PAYLOAD, CRC16, 0x03

        if (packetBuffer.length < fullLen) return;

        const packet = packetBuffer.slice(0, fullLen);
        packetBuffer = packetBuffer.slice(fullLen);

        // 2. Validate Stop Byte
        if (packet[fullLen - 1] !== 0x03) {
            displayCallback('Bad stop byte');
            continue;
        }

const payload = packetBuffer.slice(2, packetBuffer.length - 3); 
// TO THIS (Exclude the ID byte from the CRC calculation):
const payloadData = packetBuffer.slice(3, packetBuffer.length - 3);
const checksum = (packetBuffer[packetBuffer.length - 3] << 8) | packetBuffer[packetBuffer.length - 2];

// Check CRC against data only
if (crc16(payloadData) === checksum) 	{ displayCallback("crc16(payloadData) === checksum)"); }
else 					{displayCallback('CRC ERROR'); }


        const cmd = payload[0];

        if (cmd === 4) { // COMM_GET_VALUES
            const parsed = parseVescData(payload);
            if (parsed) {
                displayCallback(
                    `PARSED -> RPM: ${parsed.rpm} | I_in: ${parsed.currentIn.toFixed(2)}A | V_in: ${parsed.voltage.toFixed(2)}V | T_FET: ${parsed.tempMosfet.toFixed(1)}°C | T_MOT: ${parsed.tempMotor.toFixed(1)}°C`
                );
            } else {
                displayCallback('PARSE ERROR');
            }
        } else {
            // 5. Text Handling: Skip ID byte (payload[0]) and decode the text payload
            try {
                const textPayload = payload.slice(1);
                const txt = new TextDecoder().decode(textPayload);
                // Sanitize non-printable characters
                displayCallback(`TXT: ${txt.replace(/[^\x20-\x7E]/g, '')}`);
            } catch {
                displayCallback('Non-text payload');
            }
        }
    }
}

export function parseVescData(data) {
    const v = new DataView(data.buffer);
    let i = 1; // skip command byte (0x04)

    try {
        const tempMosfet = v.getInt16(i) / 10; i += 2;
        const tempMotor  = v.getInt16(i) / 10; i += 2;
        const currentMotor = v.getFloat32(i); i += 4;
        const currentIn    = v.getFloat32(i); i += 4;
        const duty = v.getInt16(i) / 1000; i += 2;
        const rpm = v.getInt32(i); i += 4;
        const voltage = v.getInt16(i) / 10; i += 2;

        return { tempMosfet, tempMotor, currentMotor, currentIn, duty, rpm, voltage };
    } catch (e) {
        return null;
    }
}