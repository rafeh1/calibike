<!DOCTYPE html>
<html>
<head>
    <title>VESC Terminal</title>
    <style>
        #terminal { 
            width: 400px; 
            height: 300px; 
            background: #000; 
            color: #0f0; 
            overflow-y: scroll; 
            padding: 10px; 
            font-family: monospace; 
        }
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

        const display = (msg) => {
            terminal.innerHTML += `<div>${msg}</div>`;
            terminal.scrollTop = terminal.scrollHeight;
        };


// index.php - Update dataHandler
const dataHandler = (buffer) => {
    // 1. RAW DUMP: See EVERYTHING, even if main.js discards it
    const raw = Array.from(buffer).map(b => b.toString(16).padStart(2, '0')).join(' ');
    terminal.innerHTML += `<div style="color: #666; font-size: 10px;">DEBUG: ${raw}</div>`;
    
    // 2. PARSED PATH: Still run your main logic
    onDataReceived(buffer, display);
};

        document.getElementById('connectBtn').addEventListener('click', async () => {
            display('Calibike Connecting…');
            try {
                await connectVESC(dataHandler);
                display('Calibike Connected.');
            } catch (err) {
                display(`ERROR: ${err}`);
            }
        });

        input.addEventListener('keypress', async (e) => {
            if (e.key === 'Enter') {
                const val = parseInt(input.value, 16);
                if (!isNaN(val)) {
                    await sendCommand(new Uint8Array([val]));
                    display(`TX CMD: 0x${val.toString(16)}`);
                }
                input.value = '';
            }
        });
    </script>
</body>
</html>