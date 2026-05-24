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
            // Pass raw Uint8Array, not a decoded string
            onDataCallback(new Uint8Array(event.target.value.buffer));
        });
    }
}

export async function sendCommand(text) {
    if (!writeChar) return;
    const payload = new TextEncoder().encode(text);
    const len = payload.length;
    
    // Construct VESC Packet: [Start (0x02)] [Len] [Payload...] [CRC High] [CRC Low] [Stop (0x03)]
    const packet = new Uint8Array(len + 5);
    packet[0] = 0x02;
    packet[1] = len;
    packet.set(payload, 2);
    const crc = crc16(payload);
    packet[len + 2] = (crc >> 8) & 0xFF;
    packet[len + 3] = crc & 0xFF;
    packet[len + 4] = 0x03;
    
    await writeChar.writeValue(packet);
}