<!DOCTYPE html>
<html>

<head>

    <title>VESC Web Tool</title>

    <style>

        body {
            font-family: Arial;
            background: #111;
            color: #0f0;
            padding: 20px;
        }

        button {
            font-size: 20px;
            padding: 10px;
        }

        .row {
            margin-top: 10px;
            font-size: 24px;
        }

    </style>

</head>

<body>

    <button id="connectBtn">
        Connect VESC
    </button>

    <div class="row">
        RPM:
        <span id="rpm">0</span>
    </div>

    <div class="row">
        Voltage:
        <span id="volt">0</span>
    </div>

    <div class="row">
        Current:
        <span id="amp">0</span>
    </div>

    <div class="row">
        Duty:
        <span id="duty">0</span>
    </div>

    <div class="row">
        MOS Temp:
        <span id="temp">0</span>
    </div>

    <script type="module">

        import { connectVESC }
            from './vesc_ble.js';

        import {
            onDataReceived,
            setCharacteristic
        }
            from './main.js';

        let characteristic;

        document
            .getElementById('connectBtn')
            .addEventListener(
                'click',
                async () => {

                    try {

                        characteristic =
                            await connectVESC();

                        setCharacteristic(
                            characteristic
                        );

                        characteristic
                            .addEventListener(
                                'characteristicvaluechanged',
                                onDataReceived
                            );

                        alert('Connected');

                    } catch (e) {

                        alert(e);
                    }
                }
            );

    </script>

</body>

</html>