<!DOCTYPE html>
<html>
<head><title>VESC Logger</title></head>
<body>
    <button id="connectBtn">Connect VESC</button>
    <p id="status">Status: Ready</p>
    <pre id="log"></pre>

    <script type="module">
        import { connectVESC } from './vesc_ble.js';
        import { onDataReceived } from './main.js';

        document.getElementById('connectBtn').addEventListener('click', async () => {
            try {
                const char = await connectVESC();
                document.getElementById('status').innerText = "Connected & Logging...";
                char.addEventListener('characteristicvaluechanged', onDataReceived);
            } catch (err) {
                document.getElementById('status').innerText = "Error: " + err;
                console.error("Connection failed:", err);
            }
        });
    </script>
</body>
</html>