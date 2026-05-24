const VESC_SERVICE_UUID =
    '0000ffe0-0000-1000-8000-00805f9b34fb';

const VESC_CHAR_UUID =
    '0000ffe1-0000-1000-8000-00805f9b34fb';

let bleDevice;

let bleCharacteristic;

export async function connectVESC() {

    bleDevice =
        await navigator.bluetooth.requestDevice({

            filters: [
                {
                    namePrefix: 'VESC'
                }
            ],

            optionalServices: [
                VESC_SERVICE_UUID
            ]
        });

    console.log(
        'Connecting to',
        bleDevice.name
    );

    const server =
        await bleDevice.gatt.connect();

    console.log(
        'Connected'
    );

    const service =
        await server.getPrimaryService(
            VESC_SERVICE_UUID
        );

    console.log(
        'Got service'
    );

    bleCharacteristic =
        await service.getCharacteristic(
            VESC_CHAR_UUID
        );

    console.log(
        'Got characteristic'
    );

    await bleCharacteristic
        .startNotifications();

    console.log(
        'Notifications started'
    );

    return bleCharacteristic;
}

export async function writePacket(packet) {

    if (!bleCharacteristic) {

        throw new Error(
            'BLE not connected'
        );
    }

    console.log(
        'TX:',
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

        await bleCharacteristic
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
            'Disconnected'
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