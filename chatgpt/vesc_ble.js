const NUS_SERVICE_UUID =  '6e400001-b5a3-f393-e0a9-e50e24dcca9e';

const NUS_RX_UUID =    '6e400002-b5a3-f393-e0a9-e50e24dcca9e';

const NUS_TX_UUID =     '6e400003-b5a3-f393-e0a9-e50e24dcca9e';

let bleDevice;

let notifyCharacteristic;

let writeCharacteristic;

export async function connectVESC() {

    bleDevice =
        await navigator.bluetooth.requestDevice({

            acceptAllDevices: true,

            optionalServices: [
                NUS_SERVICE_UUID
            ]
        });

    console.log(
        'Connecting to',
        bleDevice.name
    );

    const server =
        await bleDevice.gatt.connect();

    console.log(
        'Connected to GATT server'
    );

    const service =
        await server.getPrimaryService(
            NUS_SERVICE_UUID
        );

    console.log(
        'Got NUS service'
    );

    writeCharacteristic =
        await service.getCharacteristic(
            NUS_RX_UUID
        );

    console.log(
        'Got RX write characteristic'
    );

    notifyCharacteristic =
        await service.getCharacteristic(
            NUS_TX_UUID
        );

    console.log(
        'Got TX notify characteristic'
    );

    await notifyCharacteristic
        .startNotifications();

    console.log(
        'Notifications started'
    );

    notifyCharacteristic
        .addEventListener(
            'characteristicvaluechanged',
            handleNotifications
        );

    return {
        device: bleDevice,
        notifyCharacteristic,
        writeCharacteristic
    };
}

function handleNotifications(event) {

    const value =
        new Uint8Array(
            event.target.value.buffer
        );

    console.log(
        'BLE RX:',
        bytesToHex(value)
    );

    window.dispatchEvent(
        new CustomEvent(
            'vescData',
            {
                detail: value
            }
        )
    );
}

export async function writePacket(packet) {

    if (!writeCharacteristic) {

        throw new Error(
            'Write characteristic not connected'
        );
    }

    console.log(
        'BLE TX:',
        bytesToHex(packet)
    );

    const MTU = 20;

    for (
        let i = 0;
        i < packet.length;
        i += MTU
    ) {

        const chunk =
            packet.slice(
                i,
                i + MTU
            );

        await writeCharacteristic
            .writeValue(chunk);

        await delay(10);
    }
}

export async function disconnectVESC() {

    if (
        bleDevice &&
        bleDevice.gatt.connected
    ) {

        bleDevice.gatt.disconnect();

        console.log(
            'BLE disconnected'
        );
    }
}

function delay(ms) {

    return new Promise(
        resolve =>
            setTimeout(resolve, ms)
    );
}

function bytesToHex(bytes) {

    return Array
        .from(bytes)
        .map(
            b =>
                b
                .toString(16)
                .padStart(2, '0')
        )
        .join(' ');
}