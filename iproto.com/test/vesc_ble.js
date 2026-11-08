export let bleCharacteristic;

export async function connectVESC() {
    const bleDevice = await navigator.bluetooth.requestDevice({
        acceptAllDevices: true,
        optionalServices: ['6e400001-b5a3-f393-e0a9-e50e24dcca9e', '0000ffe0-0000-1000-8000-00805f9b34fb']
    });

    const server = await bleDevice.gatt.connect();
    const services = await server.getPrimaryServices();
    const service = services[0];
    const chars = await service.getCharacteristics();

    const notifyChar = chars.find(c => c.properties.notify);
    const writeChar = chars.find(c => c.properties.write || c.properties.writeWithoutResponse);

    if (notifyChar) {
        await notifyChar.startNotifications();
        bleCharacteristic = notifyChar;
    }

    // SAFE POLLING: Use a flag to prevent overlapping GATT operations
    if (writeChar) {
        const GET_VALUES = new Uint8Array([0x02, 0x01, 0x04, 0x4D, 0xAB, 0x03]);
        let isWriting = false;
        
        setInterval(async () => {
            if (isWriting) return; // Wait until previous operation finishes
            isWriting = true;
            try {
                await writeChar.writeValue(GET_VALUES);
            } catch (e) {
                console.warn("Polling skipped:", e);
            } finally {
                isWriting = false;
            }
        }, 200); 
    }
    
    return notifyChar;
}