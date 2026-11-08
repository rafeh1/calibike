import { crc16 } from './crc_utils.js';

let packetBuffer = new Uint8Array(0);

export function onDataReceived(newData) {
    // Concatenate new data to existing buffer
    let temp = new Uint8Array(packetBuffer.length + newData.length);
    temp.set(packetBuffer);
    temp.set(newData, packetBuffer.length);
    packetBuffer = temp;

    // Process all complete packets in the buffer
    while (packetBuffer.length > 0) {
        // Find start byte (0x02 for short packets)
        const startIdx = packetBuffer.indexOf(0x02);
        if (startIdx === -1) {
            packetBuffer = new Uint8Array(0);
            break;
        }
        
        // Remove junk before start byte
        if (startIdx > 0) packetBuffer = packetBuffer.slice(startIdx);

        // Check if we have enough bytes for length (packet[1])
        if (packetBuffer.length < 2) break;
        const payloadLength = packetBuffer[1];
        const totalSize = payloadLength + 5; // 0x02, len, payload, crcH, crcL, 0x03

        if (packetBuffer.length < totalSize) break; // Incomplete packet, wait for more data

        // Extract and process
        const packet = packetBuffer.slice(0, totalSize);
        if (packet[totalSize - 1] === 0x03) {
            const payload = packet.slice(2, 2 + payloadLength);
            const checksum = (packet[totalSize - 3] << 8) | packet[totalSize - 2];
            
            if (crc16(payload) === checksum) {
                const parsed = parseVescData(payload);
                if (parsed) sendToPHP(parsed); // Trigger the save logic
            }
        }
        // Slice off processed packet
        packetBuffer = packetBuffer.slice(totalSize);
    }
}

export function onDataReceived_old(newData, displayCallback) {
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
    const view = new DataView(data.buffer);
    try {
        return { 
            rpm: view.getInt32(4), 
            amp: view.getInt32(12) / 100, 
            volt: view.getInt16(24) / 10 
        };
    } catch(e) { return null; }
}