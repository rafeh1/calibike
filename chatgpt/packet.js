import { crc16 } from './crc_utils.js';

export function buildPacket(payload) {
    const len = payload.length;
    let packet = [];

    // Short packet
    if (len <= 255) {
        packet.push(0x02);
        packet.push(len);
    }
    // Long packet
    else {
        packet.push(0x03);
        packet.push((len >> 8) & 0xFF);
        packet.push(len & 0xFF);
    }

    // Payload
    packet.push(...payload);

    // CRC
    const crc = crc16(payload);
    packet.push((crc >> 8) & 0xFF);
    packet.push(crc & 0xFF);

    // Stop byte
    packet.push(0x03);

    return new Uint8Array(packet);
}

export class PacketParser {
    constructor(onPacket) {
        this.onPacket = onPacket;
        this.reset();
    }

    reset() {
        this.state = 'WAIT_START';
        this.buffer = [];
        this.payloadLength = 0;
        this.payload = [];
        this.crcHigh = 0;
        this.crcLow = 0;
        this.isLongPacket = false;
    }

    process(data) {
        for (let byte of data) {

            switch (this.state) {

                case 'WAIT_START':

                    if (byte === 0x02) {
                        this.isLongPacket = false;
                        this.state = 'READ_LEN_SHORT';
                    }

                    else if (byte === 0x03) {
                        this.isLongPacket = true;
                        this.state = 'READ_LEN_LONG_1';
                    }

                    break;

                case 'READ_LEN_SHORT':

                    this.payloadLength = byte;
                    this.payload = [];
                    this.state = 'READ_PAYLOAD';
                    break;

                case 'READ_LEN_LONG_1':

                    this.payloadLength = byte << 8;
                    this.state = 'READ_LEN_LONG_2';
                    break;

                case 'READ_LEN_LONG_2':

                    this.payloadLength |= byte;
                    this.payload = [];
                    this.state = 'READ_PAYLOAD';
                    break;

                case 'READ_PAYLOAD':

                    this.payload.push(byte);

                    if (this.payload.length >= this.payloadLength) {
                        this.state = 'READ_CRC_1';
                    }

                    break;

                case 'READ_CRC_1':

                    this.crcHigh = byte;
                    this.state = 'READ_CRC_2';
                    break;

                case 'READ_CRC_2':

                    this.crcLow = byte;
                    this.state = 'READ_STOP';
                    break;

                case 'READ_STOP':

                    if (byte === 0x03) {

                        const receivedCrc =
                            (this.crcHigh << 8) | this.crcLow;

                        const payloadArray =
                            new Uint8Array(this.payload);

                        const calculatedCrc =
                            crc16(payloadArray);

                        if (receivedCrc === calculatedCrc) {
                            this.onPacket(payloadArray);
                        }
                    }

                    this.reset();
                    break;
            }
        }
    }
}