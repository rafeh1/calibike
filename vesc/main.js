import { crc16 } from './crc_utils.js';

let packetBuffer = new Uint8Array(0);

export function onDataReceived(newData) {
    packetBuffer = new Uint8Array([...packetBuffer, ...newData]);

    // Check for standard VESC end-byte 0x03
    if (packetBuffer.length > 5 && packetBuffer[packetBuffer.length - 1] === 0x03) {
        const payload = packetBuffer.slice(1, -3);
        const checksum = (packetBuffer[packetBuffer.length - 3] << 8) | packetBuffer[packetBuffer.length - 2];
        
        if (crc16(payload) === checksum) {
            const parsed = parseVescData(payload);
            const log = document.getElementById('log');
            if (log && parsed) log.innerText = `RPM: ${parsed.rpm} | Amp: ${parsed.amp} | Volt: ${parsed.volt}`;
        }
        packetBuffer = new Uint8Array(0);
    }
}

export function parseVescData(data) {
    const view = new DataView(data.buffer);
    try {
        return { 
            rpm: view.getInt32(4), 
            amp: view.getInt32(12) / 100, 
            volt: view.getInt16(24) / 10 
        };
    } catch(e) { return null; }
}