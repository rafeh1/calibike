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
    <input type="text" id="input" placeholder="Command...">

    <script type="module">
        import { connectVESC, sendCommand } from './vesc_ble.js';
        import { onDataReceived } from './main.js';
        
        const terminal = document.getElementById('terminal');
        const input = document.getElementById('input');

        // Wrapper to update terminal and keep original parsing logic
        const dataHandler = (buffer) => {
            // Log binary data to terminal in hex for visibility
            const hex = Array.from(buffer).map(b => b.toString(16).padStart(2, '0')).join(' ');
            terminal.innerHTML += `<div>RX: ${hex}</div>`;
            terminal.scrollTop = terminal.scrollHeight;
            
            // Pass to the logic that handles parsing and UI updates
            onDataReceived(buffer);
        };

        document.getElementById('connectBtn').addEventListener('click', async () => {
            try {
                await connectVESC(dataHandler);
                terminal.innerHTML += "<div>Connected.</div>";
            } catch (err) {
                terminal.innerHTML += `<div>Error: ${err}</div>`;
            }
        });

        input.addEventListener('keypress', async (e) => {
            if (e.key === 'Enter') {
                await sendCommand(input.value);
                terminal.innerHTML += `<div>TX: ${input.value}</div>`;
                input.value = '';
            }
        });
    </script>
</body>
</html>