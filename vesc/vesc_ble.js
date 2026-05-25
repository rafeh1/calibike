import { crc16 } from './crc_utils.js';

let writeChar;

export async function connectVESC(onDataCallback) {
    const bleDevice = await navigator.bluetooth.requestDevice({
        acceptAllDevices: true,
        optionalServices: ['6e400001-b5a3-f393-e0a9-e50e24dcca9e', '0000ffe0-0000-1000-8000-00805f9b34fb']
    });

    const server = await bleDevice.gatt.connect();
    const service = await server.getPrimaryService('6e400001-b5a3-f393-e0a9-e50e24dcca9e');
    const chars = await service.getCharacteristics();

    const notifyChar = chars.find(c => c.properties.notify);
    writeChar = chars.find(c => c.properties.write || c.properties.writeWithoutResponse);

    if (notifyChar) {
        await notifyChar.startNotifications();
        notifyChar.addEventListener('characteristicvaluechanged', (event) => {
            onDataCallback(new Uint8Array(event.target.value.buffer));
        });
    }
}

export async function sendCommand(payload) {
    if (!writeChar) return;

    const len = payload.length;
    // VESC packets for length < 256 bytes use one byte for length
    const packet = new Uint8Array(len + 5);
    
    packet[0] = 0x02;            // Start Byte
    packet[1] = len;             // Payload Length
    packet.set(payload, 2);      // Payload
    
    const crc = crc16(payload);
    packet[len + 2] = (crc >> 8) & 0xFF; // CRC High
    packet[len + 3] = crc & 0xFF;        // CRC Low
    packet[len + 4] = 0x03;              // Stop Byte
    
    await writeChar.writeValue(packet);
}