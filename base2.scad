<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>CaliBike 33C3 Dashboard</title>

<link rel="stylesheet"
href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<style>

*{
    box-sizing:border-box;
    margin:0;
    padding:0;
}

html,body{
    width:100%;
    height:100%;
    overflow:hidden;
    font-family:Arial,sans-serif;
    background:#111;
}

#wrapper{
    width:100vw;
    height:100vh;
    position:relative;
}

/* =========================
   SIDEBAR
========================= */

#sidebar{

    position:absolute;

    left:10px;
    top:10px;

    width:320px;

    z-index:9000;

    display:flex;
    flex-direction:column;

    gap:10px;
}

#top-controls{

    background:rgba(0,0,0,0.65);

    padding:10px;

    border-radius:12px;

    backdrop-filter:blur(5px);

    color:#0f0;
}

button{

    background:#222;

    color:#0f0;

    border:1px solid #0f0;

    padding:10px 18px;

    cursor:pointer;

    border-radius:8px;
}

.debug-check{

    margin-left:10px;
}

/* =========================
   TERMINAL
========================= */

#bottom-terminal{

    height:260px;

    display:flex;

    flex-direction:column;

    background:rgba(0,0,0,0.65);

    border-radius:14px;

    overflow:hidden;

    backdrop-filter:blur(5px);
}

#terminal{

    flex:1;

    overflow-y:auto;

    padding:10px;

    color:#0f0;

    font-family:monospace;

    font-size:13px;

    background:#000;
}

.input-line{

    display:flex;

    padding:8px;

    gap:8px;

    background:#111;
}

#cmd-input{

    flex:1;

    background:#000;

    color:#0f0;

    border:1px solid #444;

    padding:10px;

    border-radius:8px;
}

/* =========================
   MAP
========================= */

#map{
    width:100vw;
    height:100vh;
}

/* =========================
   HUD DISPLAY
========================= */

#hud-overlay{

    position:absolute;

    left:50%;
    bottom:max(18px, env(safe-area-inset-bottom, 18px));

    transform:translateX(-50%);

    width:92%;

    height:185px;

    z-index:9999;

    display:flex;

    align-items:center;

    background:rgba(0,0,0,0.58);

    border-radius:26px;

    backdrop-filter:blur(8px);

    border:1px solid rgba(255,255,255,0.18);

    padding:18px;

    color:white;

    box-shadow:
        0 0 20px rgba(0,0,0,0.45);
}

/* SPEED WRAP */

#hud-speed-wrap{
    display:flex;
    flex-direction:row;
    align-items:center;
    gap:10px;
    margin-right:20px;
    flex-shrink:0;
}

#connect-col, #lkul-col{
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:5px;
}

/* CONNECT CIRCLE */

#connectBtn{
    width:44px;
    height:44px;
    border-radius:50%;
    border:3px solid #888;
    background:#333;
    cursor:pointer;
    transition: background 0.3s, border-color 0.3s, box-shadow 0.3s;
    outline:none;
    padding:0;
}

#connectBtn:hover{
    border-color:#aaa;
    background:#444;
}

#connectBtn.connected{
    background:#1db954;
    border-color:#1db954;
    box-shadow: 0 0 12px #1db954;
}

/* LK/UL BUTTON */

#lkulBtn{
    width:44px;
    height:44px;
    border-radius:50%;
    border:3px solid #1db954;
    background:#1db954;
    color:white;
    font-weight:bold;
    font-size:13px;
    cursor:pointer;
    transition: background 0.3s, border-color 0.3s, box-shadow 0.3s;
    outline:none;
    padding:0;
}

#lkulBtn.locked{
    background:#e03030;
    border-color:#e03030;
    box-shadow: 0 0 12px #e03030;
}

/* SPEED */

#hud-speed-circle{

    width:210px;
    height:210px;

    border-radius:50%;

    border:none;

    background:rgba(0,0,0,0.4);

    display:flex;

    flex-direction:column;

    align-items:center;
    justify-content:center;

    margin-right:0;

    flex-shrink:0;

    position:relative;
}

#calibike-ring{
    position:absolute;
    top:-8px; left:-8px;
    width:calc(100% + 16px);
    height:calc(100% + 16px);
    pointer-events:none;
    object-fit:contain;
}

#speed-inner{
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    position:relative;
    z-index:2;
}

.hud-speed-label{

    font-size:22px;

    opacity:0.85;

    margin-bottom:10px;
}

.hud-speed-value{

    font-size:72px;

    font-weight:bold;

    line-height:1;
}

.hud-speed-unit{

    font-size:28px;

    opacity:0.9;
}

/* MAIN */

#hud-main{

    flex:1;

    display:flex;

    flex-direction:column;

    justify-content:center;
}

.hud-row{

    display:flex;

    justify-content:space-around;

    align-items:center;

    flex-wrap:nowrap;

    width:100%;

    overflow:hidden;
}

.hud-divider{

    height:1px;

    background:rgba(255,255,255,0.2);

    margin:8px 0;
}

.hud-box{

    flex:1;

    min-width:0;

    max-width:33.3%;

    text-align:center;

    padding:0 2px;

    overflow:hidden;
}

.hud-title{

    font-size:11px;

    opacity:0.72;

    margin-bottom:3px;

    white-space:nowrap;

    overflow:hidden;

    text-overflow:ellipsis;
}

.hud-value{

    font-size:22px;

    font-weight:bold;

    color:orange;

    white-space:nowrap;

    overflow:hidden;
}

.small-unit{

    font-size:13px;

    color:white;

    opacity:0.9;
}

/* =========================
   LOGO
========================= */

#logo-overlay{
    position:absolute;
    top:10px;
    left:50%;
    transform:translateX(-50%);
    z-index:9000;
    pointer-events:none;
}

#logo-overlay img{
    height:100px;
    width:auto;
    display:block;
}

/* =========================
   MOBILE FIX
========================= */

@media (max-width: 900px) {

    #hud-overlay {

        width: 98%;

        height: auto;

        max-height: 48vh;

        padding: 8px 8px max(8px, env(safe-area-inset-bottom, 8px)) 8px;

        bottom: max(4px, env(safe-area-inset-bottom, 4px));

        flex-direction: column;

        align-items: stretch;

        border-radius: 18px;

        overflow-y: auto;
    }

    /* SPEED */

    #hud-speed-circle {

        width: 110px;
        height: 110px;

        border-width: 7px;

        margin: 0 auto 10px auto;
    }

    .hud-speed-label {

        font-size: 13px;

        margin-bottom: 2px;
    }

    .hud-speed-value {

        font-size: 38px;
    }

    .hud-speed-unit {

        font-size: 18px;
    }

    /* MAIN */

    #hud-main {

        width: 100%;
    }

    .hud-row {

        display: grid;

        grid-template-columns:
            repeat(2,1fr);

        gap: 8px;
    }

    .hud-divider {

        margin: 8px 0;
    }

    .hud-box {

        min-width: 0;

        padding: 6px 2px;
    }

    .hud-title {

        font-size: 12px;

        margin-bottom: 2px;
    }

    .hud-value {

        font-size: 24px;
    }

    .small-unit {

        font-size: 14px;
    }

    /* SIDEBAR */

    #sidebar {

        width: 220px;
    }

    #bottom-terminal {

        height: 140px;
    }

    #terminal {

        font-size: 10px;
    }

    #cmd-input {

        font-size: 12px;

        padding: 6px;
    }

    button {

        padding: 6px 10px;

        font-size: 11px;
    }
}

</style>
</head>

<body>

<div id="wrapper">

    <!-- SIDEBAR -->

    <div id="sidebar">

        <div id="top-controls" style="display:none;">

            <button id="connectBtn_old">
                Connect to VESC
            </button>

            <span id="status_old">
                Disconnected
            </span>

            <label class="debug-check">

                <input type="checkbox"
                       id="debugCheckbox_old">

                Debug

            </label>

        </div>

        <!-- TERMINAL -->

        <div id="bottom-terminal">

            <div id="terminal"></div>

            <div class="input-line">

                <input type="text"
                       id="cmd-input"
                       placeholder="Type command"
                       autocomplete="off">

                <button id="sendBtn">
                    Send
                </button>

            </div>

        </div>

    </div>

    <!-- MAP -->

    <div id="map"></div>

    <!-- LOGO -->
    <div id="logo-overlay"><img src="data:image/png;base64,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" alt="CaliBike" /></div>

        <!-- HUD -->

    <div id="hud-overlay">

        <!-- SPEED -->

        <div id="hud-speed-wrap">

            <!-- CONNECT CIRCLE (left of speed) -->
            <div id="connect-col">
                <button id="connectBtn" title="Connect to VESC"></button>
                <div class="hud-title" id="status">BLE</div>
            </div>

            <div id="hud-speed-circle">

                <img id="calibike-ring" src="data:image/png;base64,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" alt="" />

                <div id="speed-inner">
                    <div class="hud-speed-label">Speed</div>
                    <div class="hud-speed-value"><span id="hud_speed">0.0</span></div>
                    <div class="hud-speed-unit">mph</div>
                </div>

            </div>

            <!-- LK/UL BUTTON (right of speed) -->
            <div id="lkul-col">
                <button id="lkulBtn" title="Lock / Unlock">UL</button>
                <div class="hud-title" id="lkul-status">Unlocked</div>
            </div>

        </div>

        <!-- MAIN -->

        <div id="hud-main">

            <!-- LINE 1: Volt / Amps / Watts -->

            <div class="hud-row">

                <div class="hud-box">
                    <div class="hud-title">Voltage</div>
                    <div class="hud-value">
                        <span id="hud_volt">0.0</span>
                        <span class="small-unit">V</span>
                    </div>
                </div>

                <div class="hud-box">
                    <div class="hud-title">Amps</div>
                    <div class="hud-value">
                        <span id="hud_amps">0.0</span>
                        <span class="small-unit">A</span>
                    </div>
                </div>

                <div class="hud-box">
                    <div class="hud-title">Watts</div>
                    <div class="hud-value">
                        <span id="hud_watts">0</span>
                        <span class="small-unit">W</span>
                    </div>
                </div>

            </div>

            <!-- DIVIDER -->

            <div class="hud-divider"></div>

            <!-- LINE 2: Temp / Max Speed / Dist -->

            <div class="hud-row">

                <div class="hud-box">
                    <div class="hud-title">Temp</div>
                    <div class="hud-value">
                        <span id="hud_temp">0</span>
                        <span class="small-unit">&deg;C</span>
                    </div>
                </div>

                <div class="hud-box">
                    <div class="hud-title">Max Speed</div>
                    <div class="hud-value">
                        <span id="hud_max">0</span>
                        <span class="small-unit">mph</span>
                    </div>
                </div>

                <div class="hud-box">
                    <div class="hud-title">Distance</div>
                    <div class="hud-value">
                        <span id="hud_dist">0.0</span>
                        <span class="small-unit">mi</span>
                    </div>
                </div>

            </div>

            <div style="text-align:center;margin-top:6px;">
                <label class="debug-check">
                    <input type="checkbox" id="debugCheckbox">
                    Debug
                </label>
            </div>

        </div>

    </div>

</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>

/* =========================
   DOM
========================= */

var terminal =
    document.getElementById('terminal');

var input =
    document.getElementById('cmd-input');

var statusEl =
    document.getElementById('status');

/* =========================
   BLE
========================= */

var writeChar      = null;
var rxBuffer       = [];
var telemetryTimer = null;

var SERVICE_UUID =
    '6e400001-b5a3-f393-e0a9-e50e24dcca9e';

var RX_UUID =
    '6e400002-b5a3-f393-e0a9-e50e24dcca9e';

var TX_UUID =
    '6e400003-b5a3-f393-e0a9-e50e24dcca9e';

/* =========================
   GPS
========================= */

var gpsMarker   = null;
var gpsCircle   = null;
var gpsCentered = false;

/* =========================
   STATS
========================= */

var maxSpeed = 0;
var totalDistance = 0;

var lastLat = null;
var lastLon = null;

var rideStart = Date.now();

/* =========================
   MAP
========================= */

var leafletMap =
    L.map('map')
    .setView([33.8752,-117.5664],16);

L.tileLayer(
'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'
).addTo(leafletMap);

/* =========================
   LOG
========================= */

function log(t){

    var d =
        document.createElement('div');

    d.textContent = t;

    terminal.appendChild(d);

    terminal.scrollTop =
        terminal.scrollHeight;
}

/* =========================
   DISTANCE
========================= */

function distanceMiles(lat1,lon1,lat2,lon2){

    var R = 6371e3;

    var p1 = lat1 * Math.PI/180;
    var p2 = lat2 * Math.PI/180;

    var dp =
        (lat2-lat1) * Math.PI/180;

    var dl =
        (lon2-lon1) * Math.PI/180;

    var a =
        Math.sin(dp/2)*Math.sin(dp/2)+
        Math.cos(p1)*Math.cos(p2)*
        Math.sin(dl/2)*Math.sin(dl/2);

    var c =
        2*Math.atan2(
            Math.sqrt(a),
            Math.sqrt(1-a)
        );

    var meters = R*c;

    return meters * 0.000621371;
}

/* =========================
   GPS
========================= */

function startGPS(){

    if(!navigator.geolocation){

        log("GPS unsupported");
        return;
    }

    navigator.geolocation.watchPosition(

        function(pos){

            var lat =
                pos.coords.latitude;

            var lng =
                pos.coords.longitude;

            var acc =
                pos.coords.accuracy;

            var spd =
                pos.coords.speed;

            var mph =
                (spd !== null && spd >=0)
                ? spd * 2.23694
                : 0;

            /* SPEED */

            document.getElementById(
                'hud_speed'
            ).textContent =
                mph.toFixed(1);

            if(mph > maxSpeed)
                maxSpeed = mph;

            document.getElementById(
                'hud_max'
            ).textContent =
                maxSpeed.toFixed(1);

            /* DISTANCE */

            if(lastLat !== null){

                totalDistance +=
                    distanceMiles(
                        lastLat,
                        lastLon,
                        lat,
                        lng
                    );
            }

            lastLat = lat;
            lastLon = lng;

            document.getElementById(
                'hud_dist'
            ).textContent =
                totalDistance.toFixed(2);

            /* TIME */

            var secs =
                Math.floor(
                    (Date.now()-rideStart)/1000
                );

            var mins =
                Math.floor(secs/60);

            var hrs =
                Math.floor(mins/60);

            mins %= 60;
            secs %= 60;

            document.getElementById(
                'hud_time'
            ).textContent =
                String(hrs).padStart(2,'0')
                + ':' +
                String(mins).padStart(2,'0')
                + ':' +
                String(secs).padStart(2,'0');

            /* MAP */

            if(!gpsMarker){

                var icon =
                    L.divIcon({

                    className:'',

                    html:
                    '<div style="' +
                    'width:16px;' +
                    'height:16px;' +
                    'background:#00ff00;' +
                    'border:3px solid #fff;' +
                    'border-radius:50%;' +
                    'box-shadow:0 0 8px #00ff00;' +
                    '"></div>',

                    iconSize:[16,16],

                    iconAnchor:[8,8]
                });

                gpsMarker =
                    L.marker([lat,lng],{
                        icon:icon
                    }).addTo(leafletMap);

            }else{

                gpsMarker.setLatLng(
                    [lat,lng]
                );
            }

            if(!gpsCircle){

                gpsCircle =
                    L.circle([lat,lng],{

                    radius:acc,

                    color:'#00ff00',

                    fillColor:'#00ff0033',

                    fillOpacity:0.3,

                    weight:1

                }).addTo(leafletMap);

            }else{

                gpsCircle.setLatLng(
                    [lat,lng]
                );

                gpsCircle.setRadius(acc);
            }

            if(!gpsCentered){

                leafletMap.setView(
                    [lat,lng],
                    17
                );

                gpsCentered = true;

            }else{

                leafletMap.panTo(
                    [lat,lng]
                );
            }
        },

        function(err){

            log(
                "GPS ERROR: "
                + err.message
            );
        },

        {
            enableHighAccuracy:true,
            maximumAge:2000,
            timeout:15000
        }
    );
}

/* =========================
   CRC
========================= */

function crc16(buf){

    var crc = 0;

    for(var i=0;i<buf.length;i++){

        crc ^= (buf[i] << 8);

        for(var j=0;j<8;j++){

            crc =
                (crc & 0x8000)
                ? (crc << 1) ^ 0x1021
                : (crc << 1);
        }
    }

    return crc & 0xFFFF;
}

/* =========================
   PACKETS
========================= */

function buildPacket(payload){

    var len = payload.length;

    var pkt =
        new Uint8Array(len + 5);

    pkt[0] = 0x02;
    pkt[1] = len;

    pkt.set(payload,2);

    var crc = crc16(payload);

    pkt[len+2] =
        (crc>>8)&0xFF;

    pkt[len+3] =
        crc&0xFF;

    pkt[len+4] = 0x03;

    return pkt;
}

function buildTerminalPacket(text){

    var tb =
        new TextEncoder().encode(text);

    var payload =
        new Uint8Array(1 + tb.length);

    payload[0] = 0x14;

    payload.set(tb,1);

    return buildPacket(payload);
}

function buildTelemetryPacket(){

    return buildPacket(
        new Uint8Array([0x04])
    );
}

/* =========================
   BLE CONNECT
========================= */

async function connect(){

    try{

        log("Requesting VESC...");

        var device =
            await navigator.bluetooth
            .requestDevice({

            filters:[{
                services:[SERVICE_UUID]
            }]
        });

        device.addEventListener(

            'gattserverdisconnected',

            function(){

                statusEl.textContent =
                    "Disconnected";

                document.getElementById('connectBtn').classList.remove('connected');

                log("Disconnected - retrying...");

                if(telemetryTimer){

                    clearInterval(
                        telemetryTimer
                    );

                    telemetryTimer = null;
                }

                // Auto-reconnect loop
                writeChar = null;
                var retry = setInterval(async function(){
                    try{
                        log("Reconnecting...");
                        var server = await device.gatt.connect();
                        var service = await server.getPrimaryService(SERVICE_UUID);
                        writeChar = await service.getCharacteristic(RX_UUID);
                        var nc = await service.getCharacteristic(TX_UUID);
                        await nc.startNotifications();
                        nc.addEventListener('characteristicvaluechanged', handleNotification);
                        statusEl.textContent = "Connected";
                        document.getElementById('connectBtn').classList.add('connected');
                        log("Reconnected!");
                        startTelemetry();
                        clearInterval(retry);
                    }catch(e){
                        log("Retry failed: " + e.message);
                    }
                }, 3000);
            }
        );

        var server =
            await device.gatt.connect();

        var service =
            await server.getPrimaryService(
                SERVICE_UUID
            );

        writeChar =
            await service.getCharacteristic(
                RX_UUID
            );

        var notifyChar =
            await service.getCharacteristic(
                TX_UUID
            );

        await notifyChar.startNotifications();

        notifyChar.addEventListener(
            'characteristicvaluechanged',
            handleNotification
        );

        statusEl.textContent =
            "Connected";

        document.getElementById('connectBtn').classList.add('connected');

        log("Connected to VESC");

        startTelemetry();

    }catch(e){

        log(
            "CONNECT ERROR: "
            + e.message
        );
    }
}

/* =========================
   TELEMETRY
========================= */

function startTelemetry(){

    if(telemetryTimer) return;

    telemetryTimer =
        setInterval(

        async function(){

            if(!writeChar) return;

            try{

                await writeChar
                .writeValueWithoutResponse(
                    buildTelemetryPacket()
                );

            }catch(e){

                log(
                    "TELE ERR: "
                    + e.message
                );
            }

        },500
    );
}

/* =========================
   RX
========================= */

function handleNotification(event){

    var chunk =
        new Uint8Array(
            event.target.value.buffer
        );

    for(var b of chunk)
        rxBuffer.push(b);

    parsePackets();
}

function parsePackets(){

    while(true){

        if(rxBuffer.length < 5)
            return;

        while(
            rxBuffer.length > 0 &&
            rxBuffer[0] !== 0x02
        ){
            rxBuffer.shift();
        }

        if(rxBuffer.length < 5)
            return;

        var len =
            rxBuffer[1];

        var total =
            len + 5;

        if(rxBuffer.length < total)
            return;

        var packet =
            rxBuffer.slice(0,total);

        rxBuffer =
            rxBuffer.slice(total);

        if(packet[total-1] !== 0x03)
            continue;

        var payload =
            packet.slice(2,2+len);

        if(!payload.length)
            continue;

        var cmd =
            payload[0];

        var data =
            payload.slice(1);

        if(cmd === 0x04){

            var mosTemp =
                ((data[0]<<8|data[1])/10);

            var mc =
                data[4]<<8|data[5];

            if(mc & 0x8000)
                mc -= 0x10000;

            var motorCurrent =
                mc / 10;

            var battVolt =
                ((data[26]<<8|data[27])/10);

            var watts =
                battVolt * motorCurrent;

            document.getElementById(
                'hud_temp'
            ).textContent =
                mosTemp.toFixed(1);

            document.getElementById(
                'hud_amps'
            ).textContent =
                motorCurrent.toFixed(1);

            document.getElementById(
                'hud_volt'
            ).textContent =
                battVolt.toFixed(1);

            document.getElementById(
                'hud_watts'
            ).textContent =
                watts.toFixed(0);

            continue;
        }

        try{

            var txt =
                new TextDecoder()
                .decode(
                    new Uint8Array(data)
                ).trim();

            if(txt)
                log("RCV: "+txt);

        }catch(e){}
    }
}

/* =========================
   SEND
========================= */

async function send(){

    if(!writeChar){

        log("Not connected");
        return;
    }

    var txt =
        input.value.trim();

    if(!txt) return;

    var pkt =
        buildTerminalPacket(txt);

    for(var i=0;i<pkt.length;i+=20){

        await writeChar
        .writeValueWithoutResponse(
            pkt.slice(i,i+20)
        );

        await new Promise(
            function(r){
                setTimeout(r,10);
            }
        );
    }

    log("> "+txt);

    input.value = "";
}

/* =========================
   UI
========================= */

document.getElementById(
'connectBtn'
).onclick = connect;

document.getElementById(
'sendBtn'
).onclick = send;

/* LK/UL toggle */
var systemLocked = false;
document.getElementById('lkulBtn').onclick = function(){
    systemLocked = !systemLocked;
    var btn = document.getElementById('lkulBtn');
    var lbl = document.getElementById('lkul-status');
    if(systemLocked){
        btn.textContent = 'LK';
        btn.classList.add('locked');
        lbl.textContent = 'Locked';
    } else {
        btn.textContent = 'UL';
        btn.classList.remove('locked');
        lbl.textContent = 'Unlocked';
    }
};

input.addEventListener(

'keypress',

function(e){

    if(e.key === 'Enter')
        send();
});

/* CaliBike ring: static SVG polygons in HTML */

/* =========================
   START GPS
========================= */

startGPS();

</script>
</body>
</html>