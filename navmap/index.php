<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
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
    bottom:18px;

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

/* SPEED */

#hud-speed-circle{

    width:210px;
    height:210px;

    border-radius:50%;

    border:12px solid orange;

    background:rgba(0,0,0,0.4);

    display:flex;

    flex-direction:column;

    align-items:center;
    justify-content:center;

    margin-right:35px;

    flex-shrink:0;
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
}

.hud-divider{

    height:1px;

    background:rgba(255,255,255,0.2);

    margin:16px 0;
}

.hud-box{

    min-width:150px;

    text-align:center;
}

.hud-title{

    font-size:21px;

    opacity:0.72;

    margin-bottom:8px;
}

.hud-value{

    font-size:46px;

    font-weight:bold;

    color:orange;
}

.small-unit{

    font-size:24px;

    color:white;

    opacity:0.9;
}

/* =========================
   MOBILE FIX
========================= */

@media (max-width: 900px) {

    #hud-overlay {

        width: 98%;

        height: auto;

        padding: 10px;

        bottom: 6px;

        flex-direction: column;

        align-items: stretch;

        border-radius: 18px;
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

        <div id="top-controls">

            <button id="connectBtn">
                Connect to VESC
            </button>

            <span id="status">
                Disconnected
            </span>

            <label class="debug-check">

                <input type="checkbox"
                       id="debugCheckbox">

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

    <!-- HUD -->

    <div id="hud-overlay">

        <!-- SPEED -->

        <div id="hud-speed-circle">

            <div class="hud-speed-label">
                Speed
            </div>

            <div class="hud-speed-value">
                <span id="hud_speed">0.0</span>
            </div>

            <div class="hud-speed-unit">
                mph
            </div>

        </div>

        <!-- MAIN -->

        <div id="hud-main">

            <!-- TOP -->

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

                <div class="hud-box">
                    <div class="hud-title">Temp</div>
                    <div class="hud-value">
                        <span id="hud_temp">0</span>
                        <span class="small-unit">°C</span>
                    </div>
                </div>

            </div>

            <!-- DIVIDER -->

            <div class="hud-divider"></div>

            <!-- BOTTOM -->

            <div class="hud-row">

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

                <div class="hud-box">
                    <div class="hud-title">Ride Time</div>
                    <div class="hud-value">
                        <span id="hud_time">00:00:00</span>
                    </div>
                </div>

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

                log("Disconnected");

                if(telemetryTimer){

                    clearInterval(
                        telemetryTimer
                    );

                    telemetryTimer = null;
                }
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

input.addEventListener(

'keypress',

function(e){

    if(e.key === 'Enter')
        send();
});

/* =========================
   START GPS
========================= */

startGPS();

</script>
</body>
</html>