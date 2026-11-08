<!DOCTYPE html>
<html>
<head>
    <title>VESC Terminal</title>
    <style>
        #terminal { width: 400px; height: 300px; background: #000; color: #0f0; overflow-y: scroll; padding: 10px; font-family: monospace; }
    </style>
</head>
<body>
    <button id="connectBtn">Connect VESC</button>
    <div id="terminal"></div>
    <input type="text" id="input" placeholder="Command (e.g., 04)">

    <script type="module">
        import { connectVESC, sendCommand } from './vesc_ble.js';
        import { onDataReceived } from './main.js';
        
        const terminal = document.getElementById('terminal');
        const input = document.getElementById('input');

const dataHandler = (buffer) => {
    // 1. buffer[1] is the VESC Length byte. 
    // It defines the size of the payload (ID + Text).
    const payloadLen = buffer[1];
    
    // 2. We start at index 3 to skip:
    // [0] Start Byte
    // [1] Length Byte
    // [2] Command ID
    // We stop at (2 + payloadLen) to exclude the 2 bytes of ECC + 1 byte of Stop.
    const textPayload = buffer.slice(3, 2 + payloadLen);
    
    // 3. Decode only that slice
    const text = new TextDecoder().decode(textPayload);
    
    // 4. Print clean text
    if (text.length > 0) {
        terminal.innerHTML += `<div>RX: ${text}</div>`;
        terminal.scrollTop = terminal.scrollHeight;
    }
    
    // 5. Pass original buffer to your existing parser
    onDataReceived(buffer);
};
        document.getElementById('connectBtn').addEventListener('click', async () => {
            try {
                await connectVESC(dataHandler);
                terminal.innerHTML += "<div>Connected.</div>";
            } catch (err) { terminal.innerHTML += `<div>Error: ${err}</div>`; }
        });

        input.addEventListener('keypress', async (e) => {
            if (e.key === 'Enter') {
                const val = parseInt(input.value, 16);
                if (!isNaN(val)) {
                    await sendCommand(new Uint8Array([val]));
                    terminal.innerHTML += `<div>TX: 0x${val.toString(16)}</div>`;
                }
                input.value = '';
            }
        });
    </script>
</body>
</html>