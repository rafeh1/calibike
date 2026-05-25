import { crc16 } from './crc_utils.js';

let writeChar;

export async function connectVESC(onDataCallback) {
    alert('DEBUG: connectVESC started!'); // This will stop execution and pop up a window
    console.log('calibike');
    console.log('REQUESTING DEVICE…');

    const bleDevice = await navigator.bluetooth.requestDevice({
        acceptAllDevices: true,
        optionalServices: [
            '6e400001-b5a3-f393-e0a9-e50e24dcca9e',
            '0000ffe0-0000-1000-8000-00805f9b34fb'
        ]
    });

    console.log('DEVICE SELECTED:', bleDevice.name);

    const server = await bleDevice.gatt.connect();
    console.log('GATT CONNECTED');

    const services = await server.getPrimaryServices();
    console.log('SERVICES:', services.map(s => s.uuid));

    for (const s of services) {
        const chars = await s.getCharacteristics();
        console.log('SERVICE', s.uuid, 'CHARS:', chars.map(c => ({
            uuid: c.uuid,
            props: c.properties
        })));
    }

    let notifyChar = null;
    let writeCandidate = null;

    for (const s of services) {
        const chars = await s.getCharacteristics();
        for (const c of chars) {
            if (c.properties.notify) notifyChar = c;
            if (c.properties.write || c.properties.writeWithoutResponse) writeCandidate = c;
        }
    }

    writeChar = writeCandidate;

    console.log('notifyChar:', notifyChar && notifyChar.uuid);
    console.log('writeChar:', writeChar && writeChar.uuid);

    if (!notifyChar) {
        console.log('NO NOTIFY CHARACTERISTIC FOUND — cannot receive data');
    } else {
        await notifyChar.startNotifications();
        notifyChar.addEventListener('characteristicvaluechanged', (event) => {
            const arr = new Uint8Array(event.target.value.buffer);
            console.log('RX EVENT:', arr);
            onDataCallback(arr);
        });
    }

    console.log('Connected to device.');
}

export async function sendCommand(payload) {
    if (!writeChar) {
        console.log('NO WRITE CHARACTERISTIC — cannot send');
        return;
    }

    const len = payload.length;
    const packet = new Uint8Array(len + 5);

    packet[0] = 0x02; // Start
    packet[1] = len;  // Length
    packet.set(payload, 2);

    const crc = crc16(payload);
    packet[len + 2] = (crc >> 8) & 0xFF;
    packet[len + 3] = crc & 0xFF;

    packet[len + 4] = 0x03; // Stop
    console.log('TX SENT:', Array.from(packet).map(b => b.toString(16).padStart(2, '0')).join(' '));

    console.log('TX PACKET:', packet);
    await writeChar.writeValue(packet);
}
