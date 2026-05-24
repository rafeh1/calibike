import { PacketParser } from './packet.js';
import { requestValues } from './vesc_protocol.js';

let bleCharacteristic = null;

const parser = new PacketParser(onPacketReceived);

export function setCharacteristic(c) {
    bleCharacteristic = c;
}

export function onDataReceived(event) {

    const data =
        new Uint8Array(event.target.value.buffer);

    parser.process(data);
}

function onPacketReceived(payload) {

    const command = payload[0];

    // COMM_GET_VALUES response
    if (command === 4) {

        const parsed =
            parseValues(payload);

        if (!parsed) return;

        updateUI(parsed);

        console.log(parsed);
    }
}

function parseValues(data) {

    try {

        const view =
            new DataView(data.buffer);

        let i = 1;

        const mosTemp =
            view.getInt16(i) / 10;
        i += 2;

        const motorTemp =
            view.getInt16(i) / 10;
        i += 2;

        const motorCurrent =
            view.getInt32(i) / 100;
        i += 4;

        const inputCurrent =
            view.getInt32(i) / 100;
        i += 4;

        i += 4;

        i += 4;

        const duty =
            view.getInt16(i) / 1000;
        i += 2;

        const rpm =
            view.getInt32(i);
        i += 4;

        const voltage =
            view.getInt16(i) / 10;
        i += 2;

        return {
            rpm,
            voltage,
            motorCurrent,
            inputCurrent,
            duty,
            mosTemp,
            motorTemp
        };

    } catch (e) {

        console.log(e);

        return null;
    }
}

function updateUI(v) {

    document.getElementById('rpm').innerText =
        v.rpm;

    document.getElementById('volt').innerText =
        v.voltage.toFixed(1);

    document.getElementById('amp').innerText =
        v.motorCurrent.toFixed(1);

    document.getElementById('duty').innerText =
        v.duty.toFixed(3);

    document.getElementById('temp').innerText =
        v.mosTemp.toFixed(1);
}

setInterval(async () => {

    if (!bleCharacteristic) return;

    try {

        await requestValues(
            bleCharacteristic
        );

    } catch (e) {

        console.log(e);
    }

}, 200);