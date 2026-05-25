import { crc16 } from './crc_utils.js';

let packetBuffer = new Uint8Array(0);

export function onDataReceived(newData, displayCallback) {
    // 1. Accumulate new data into the buffer
    packetBuffer = new Uint8Array([...packetBuffer, ...newData]);

    // 2. Check if we have a full packet
    // A VESC packet must have a start byte (0x02 or 0x03) and end with 0x03
    if (packetBuffer.length > 0 && packetBuffer[packetBuffer.length - 1] === 0x03) {
        
        // 3. Extract the payload
        // Payload starts after the Start and Length bytes (indices 0 and 1)
        // Payload ends before the CRC (last 2 bytes) and Stop byte (last 1 byte)
        if (packetBuffer.length > 5) {
            const payload = packetBuffer.slice(2, packetBuffer.length - 3);
            const checksum = (packetBuffer[packetBuffer.length - 3] << 8) | packetBuffer[packetBuffer.length - 2];
            
            // 4. Verify Integrity
            if (crc16(payload) === checksum) {
                const parsed = parseVescData(payload);
                if (parsed) {
                    displayCallback(`RPM: ${parsed.rpm} | Amp: ${parsed.amp} | Volt: ${parsed.volt}`);
                } else {
                    // Only decode if it's not a standard telemetry packet
                    const decoded = new TextDecoder().decode(payload);
                    displayCallback(decoded);
                }
            } else {
                // Fallback for text messages if CRC check fails on non-telemetry
                displayCallback(new TextDecoder().decode(payload));
            }
        }
        
        // 5. Clear buffer after full packet is processed
        packetBuffer = new Uint8Array(0);
    }
}

export function parseVescData(data) {
    const v = new DataView(data.buffer);
    let i = 1; // skip command byte (0x04)

    const tempMosfet = v.getInt16(i) / 10; i += 2;
    const tempMotor  = v.getInt16(i) / 10; i += 2;

    const currentMotor = v.getFloat32(i); i += 4;
    const currentIn    = v.getFloat32(i); i += 4;

    const duty = v.getInt16(i) / 1000; i += 2;

    const rpm = v.getInt32(i); i += 4;

    const voltage = v.getInt16(i) / 10; i += 2;

    return {
        tempMosfet,
        tempMotor,
        currentMotor,
        currentIn,
        duty,
        rpm,
        voltage
    };
}

export function parseVescData_del(data) {
    const view = new DataView(data.buffer);
    try {
        return { 
            rpm: view.getInt32(4), 
            amp: view.getInt32(12) / 100, 
            volt: view.getInt16(24) / 10 
        };
    } catch(e) { return null; }
}