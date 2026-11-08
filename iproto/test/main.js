import { crc16 } from './crc_utils.js';

let packetBuffer = new Uint8Array(0);
let dataBuffer = [];

export function onDataReceived_del(event) {
    console.log("Raw event data:", event.target.value.buffer);
    const newData = new Uint8Array(event.target.value.buffer);
    packetBuffer = new Uint8Array([...packetBuffer, ...newData]);

    if (packetBuffer.length > 5 && packetBuffer[packetBuffer.length - 1] === 0x03) {
        const payload = packetBuffer.slice(1, -3);
        const checksum = (packetBuffer[packetBuffer.length - 3] << 8) | packetBuffer[packetBuffer.length - 2];
        
        if (crc16(payload) === checksum) {
            const parsed = parseVescData(payload);
            if (parsed) {
                dataBuffer.push(parsed);
                const log = document.getElementById('log');
                if (log) log.innerText = `RPM: ${parsed.rpm} | Amp: ${parsed.amp} | Volt: ${parsed.volt}`;
            }
        }
        packetBuffer = new Uint8Array(0);
    }
}

// main.js - Temporarily replace your onDataReceived to reveal the data
export function onDataReceived(event) {
    const rawData = new Uint8Array(event.target.value.buffer);
    
    // Convert to Hex string to see what we are dealing with
    const hex = Array.from(rawData).map(b => b.toString(16).padStart(2, '0')).join(' ');
    console.log("Hex Packet:", hex);
    
    // Log the lengths so we know which packet is which
    console.log("Packet Length:", rawData.length);
    
    // Print the raw data to the log element so you can see it on screen
    const log = document.getElementById('log');
    if (log) log.innerText = "Received: " + hex;
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

setInterval(async () => {
    if (dataBuffer.length > 0) {
        let toSend = dataBuffer.splice(0, dataBuffer.length);
        try {
            await fetch('./log_vesc.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(toSend)
            });
        } catch(e) { console.error("Log error:", e); }
    }
}, 5000);