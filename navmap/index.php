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
   SETTINGS PAGE
========================= */

#settings-page{
    display:none;
    position:fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:#111;
    z-index:99999;
    flex-direction:column;
    overflow-y:auto;
}

#settings-page.open{
    display:flex;
}

#settings-header{
    display:flex;
    align-items:center;
    gap:12px;
    padding:16px;
    background:#1a1a1a;
    border-bottom:1px solid #333;
    position:sticky;
    top:0;
    z-index:2;
}

#settings-back{
    background:#222;
    color:#0f0;
    border:1px solid #0f0;
    border-radius:8px;
    padding:8px 16px;
    font-size:16px;
    cursor:pointer;
}

#settings-header h2{
    color:#0f0;
    font-size:20px;
    font-family:Arial,sans-serif;
}

#settings-body{
    flex:1;
    display:flex;
    flex-direction:column;
    padding:20px 16px;
    gap:16px;
}

.settings-section-title{
    color:#0f0;
    font-size:13px;
    opacity:0.7;
    text-transform:uppercase;
    letter-spacing:1px;
    margin-bottom:4px;
}

button.compliance-btn{
    display:block !important;
    width:100% !important;
    background:#333 !important;
    color:#fff !important;
    border:3px solid #888 !important;
    border-radius:12px !important;
    padding:14px 20px !important;
    font-size:16px !important;
    font-weight:bold !important;
    text-align:left !important;
    cursor:pointer !important;
    outline:none !important;
    box-shadow:none !important;
    transition: background 0.3s, border-color 0.3s, box-shadow 0.3s !important;
}

button.compliance-btn:hover{
    border-color:#aaa !important;
    background:#444 !important;
}

button.compliance-btn.active{
    background:#1db954 !important;
    border-color:#1db954 !important;
    color:#fff !important;
    box-shadow: 0 0 12px #1db954 !important;
}

/* SETTINGS TERMINAL */

#settings-terminal-wrap{
    margin-top:8px;
    display:flex;
    flex-direction:column;
    border-radius:14px;
    overflow:hidden;
    border:1px solid #333;
    height:220px;
    flex-shrink:0;
}

#settings-terminal-title{
    background:#1a1a1a;
    color:#0f0;
    font-size:12px;
    padding:8px 12px;
    letter-spacing:1px;
    text-transform:uppercase;
    opacity:0.7;
}

/* =========================
   TERMINAL (shared styles)
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
    box-shadow:0 0 20px rgba(0,0,0,0.45);
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
    background:none;
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
    overflow:visible;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
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
    font-size:33px;
    font-weight:bold;
    color:orange;
    white-space:nowrap;
    overflow:visible;
}

.small-unit{
    font-size:20px;
    color:white;
    opacity:0.9;
}

/* =========================
   SETTINGS BUTTON (HUD)
========================= */

#settings-btn{
    background:rgba(0,0,0,0.5);
    color:#0f0;
    border:1px solid #0f0;
    border-radius:8px;
    padding:6px 12px;
    font-size:13px;
    cursor:pointer;
    margin-top:6px;
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
        padding: 10px 8px;
        bottom: max(120px, env(safe-area-inset-bottom, 120px));
        flex-direction: column;
        align-items: stretch;
        border-radius: 18px;
    }

    .hud-row {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 2px;
        width: 100%;
    }

    .hud-box {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        padding: 4px 2px;
        min-width: 0;
        max-width: none !important;
        flex: none !important;
        overflow: visible;
    }

    .hud-title {
        font-size: 10px;
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: visible;
    }

    .hud-value {
        font-size: 18px;
        white-space: nowrap;
        overflow: visible;
    }

    .small-unit {
        font-size: 11px;
    }

    .hud-divider {
        margin: 6px 0;
    }

    .hud-box {
        text-align: center;
        padding: 4px 2px;
        min-width: 0;
    }

    .hud-title {
        font-size: 10px;
        margin-bottom: 2px;
    }

    .hud-value {
        font-size: 22px;
    }

    .small-unit {
        font-size: 13px;
    }

    .hud-speed-label {
        font-size: 11px;
    }

    .hud-speed-value {
        font-size: 32px;
        font-weight: bold;
    }

    .hud-speed-unit {
        font-size: 14px;
    }

    #connectBtn {
        width: 44px;
        height: 44px;
    }

    #lkulBtn {
        width: 44px;
        height: 44px;
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

    button:not(.compliance-btn) {
        padding: 6px 10px;
        font-size: 11px;
    }
}

</style>
</head>

<body>

<div id="wrapper">

    <!-- MAP -->

    <div id="map"></div>

    <!-- LOGO -->
    <div id="logo-overlay"><img src="CaliBike_logo_full.svg" alt="CaliBike" /></div>

    <!-- HUD -->

    <div id="hud-overlay">

        <!-- ROW 1: BLE / Speed / Unlock -->
        <div class="hud-row">

            <div class="hud-box">
                <button id="connectBtn" title="Connect to VESC"></button>
                <div class="hud-title" id="status">BLE</div>
            </div>

            <div class="hud-box">
                <div class="hud-title">Speed</div>
                <div class="hud-value" style="color:white;"><span id="hud_speed">0.0</span></div>
                <div class="hud-title">mph</div>
            </div>

            <div class="hud-box">
                <button id="lkulBtn" title="Lock / Unlock">UL</button>
                <div class="hud-title" id="lkul-status">Unlocked</div>
            </div>

        </div>

        <div class="hud-divider"></div>

        <!-- ROW 2: Volt / Amps / Watts -->
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

        <div class="hud-divider"></div>

        <!-- ROW 3: Temp / Max Speed / Dist -->
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

        <!-- SETTINGS BUTTON -->
        <div style="text-align:center;margin-top:6px;">
            <button id="settings-btn" onclick="openSettings()">&#9881; Settings</button>
        </div>

    </div>

</div>

<!-- ===========================
     SETTINGS PAGE
=========================== -->

<div id="settings-page">

    <div id="settings-header">
        <button id="settings-back" onclick="closeSettings()">&#8592; Back</button>
        <h2>&#9881; Settings</h2>
    </div>

    <!-- LOGO -->
    <div style="text-align:center;padding:16px 0 8px 0;background:#1a1a1a;">
        <img src="CaliBike_logo_full.svg" alt="CaliBike" style="height:70px;width:auto;">
    </div>

    <div id="settings-body">

        <!-- COMPLIANCE BUTTONS -->

        <div class="settings-section-title">Compliance Mode</div>

        <button class="compliance-btn active" id="btn-all" onclick="setCompliance('all',this)">
            All Compliant (motor off)
        </button>

        <button class="compliance-btn" id="btn-eu" onclick="setCompliance('eu',this)">
            EU Compliant (25 km/h)
        </button>

        <button class="compliance-btn" id="btn-us" onclick="setCompliance('us',this)">
            US Compliant (28 mph)
        </button>

        <button class="compliance-btn" id="btn-non" onclick="setCompliance('non',this)">
            Non Compliant
        </button>

        <!-- TERMINAL -->

        <div class="settings-section-title" style="margin-top:8px;">Ride Track</div>

        <div style="display:flex;gap:10px;">
            <button class="compliance-btn active" id="btn-track-line" onclick="setTrackStyle('line')" style="text-align:center;">
                — Line
            </button>
            <button class="compliance-btn" id="btn-track-dots" onclick="setTrackStyle('dots')" style="text-align:center;">
                ••• Dots
            </button>
        </div>

        <button class="compliance-btn" id="btn-gpx" onclick="exportGPX()" style="text-align:center;margin-top:4px;">
            ? Export GPX (Strava)
        </button>

        <!-- TERMINAL -->

        <div class="settings-section-title" style="margin-top:8px;">Terminal</div>

        <div id="settings-terminal-wrap">
            <div id="settings-terminal-title">VESC Terminal</div>
            <div id="terminal" style="flex:1;overflow-y:auto;padding:10px;color:#0f0;font-family:monospace;font-size:13px;background:#000;height:0;"></div>
            <div class="input-line">
                <input type="text"
                       id="cmd-input"
                       placeholder="Type command"
                       autocomplete="off"
                       style="flex:1;background:#000;color:#0f0;border:1px solid #444;padding:10px;border-radius:8px;">
                <button id="sendBtn">Send</button>
            </div>
        </div>

    </div>

</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>

/* =========================
   SETTINGS PAGE
========================= */

function openSettings(){
    document.getElementById('settings-page').classList.add('open');
}

function closeSettings(){
    document.getElementById('settings-page').classList.remove('open');
}

var currentCompliance = localStorage.getItem('compliance') || 'all';

function setCompliance(mode, btn){
    currentCompliance = mode;
    localStorage.setItem('compliance', mode);
    document.querySelectorAll('.compliance-btn').forEach(function(b){
        b.classList.remove('active');
    });
    btn.classList.add('active');
    log('Compliance mode set: ' + mode.toUpperCase());
}

/* restore saved compliance on load */
function restoreCompliance(){
    var saved = localStorage.getItem('compliance') || 'all';
    var map = {all:'btn-all', eu:'btn-eu', us:'btn-us', non:'btn-non'};
    var btnEl = document.getElementById(map[saved]);
    if(btnEl){
        document.querySelectorAll('.compliance-btn').forEach(function(b){ b.classList.remove('active'); });
        btnEl.classList.add('active');
        currentCompliance = saved;
    }
}

/* track display style: 'line' or 'dots' */
var trackStyle = localStorage.getItem('trackStyle') || 'line';

function setTrackStyle(style){
    trackStyle = style;
    localStorage.setItem('trackStyle', style);
    redrawTrack();
    document.getElementById('btn-track-line').classList.toggle('active', style==='line');
    document.getElementById('btn-track-dots').classList.toggle('active', style==='dots');
}

function redrawTrack(){
    if(!trackLine) return;
    if(trackStyle === 'dots'){
        trackLine.setStyle({opacity:0, weight:0});
    } else {
        trackLine.setStyle({color:'#00ff00', weight:3, opacity:0.8});
    }
}

/* GPX EXPORT */
function exportGPX(){
    if(rideTrack.length < 2){
        log('Not enough track points to export');
        return;
    }
    var now = new Date();
    var name = 'CaliBike-' + now.toISOString().slice(0,10);
    var lt = '<';
    var xml = lt + '?xml version="1.0" encoding="UTF-8"?' + '>' + '\n';
    xml += '<gpx version="1.1" creator="CaliBike Dashboard" xmlns="http://www.topografix.com/GPX/1/1">\n';
    xml += '  <metadata><name>' + name + '</name><time>' + now.toISOString() + '</time></metadata>\n';
    xml += '  <trk><name>' + name + '</name><trkseg>\n';
    rideTrack.forEach(function(p){
        xml += '    <trkpt lat="' + p.lat.toFixed(7) + '" lon="' + p.lng.toFixed(7) + '">';
        xml += '<ele>' + (p.ele||0).toFixed(1) + '</ele>';
        xml += '<time>' + p.time + '</time>';
        xml += '<extensions><speed>' + (p.speed||0).toFixed(2) + '</speed></extensions>';
        xml += '</trkpt>\n';
    });
    xml += '  </trkseg></trk>\n</gpx>';

    var blob = new Blob([xml], {type:'application/gpx+xml'});
    var url = URL.createObjectURL(blob);
    var a = document.createElement('a');
    a.href = url;
    a.download = name + '.gpx';
    a.click();
    URL.revokeObjectURL(url);
    log('GPX exported: ' + name + '.gpx (' + rideTrack.length + ' points)');
}

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
var rideTrack   = [];      // array of {lat,lng,ele,time,speed} for GPX + polyline
var trackLine   = null;    // Leaflet polyline

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

/* stop auto-follow if user drags the map */
var userPanned = false;
leafletMap.on('dragstart', function(){ userPanned = true; });
/* tap the map to re-enable follow */
leafletMap.on('dblclick', function(){ userPanned = false; });

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

            /* TRACK POINT */
            rideTrack.push({
                lat: lat,
                lng: lng,
                ele: pos.coords.altitude || 0,
                time: new Date().toISOString(),
                speed: mph
            });

            /* DRAW TRACK */
            if(!trackLine){
                trackLine = L.polyline([[lat,lng]], {
                    color:'#00ff00',
                    weight:4,
                    opacity:0.85
                }).addTo(leafletMap);
            } else {
                trackLine.addLatLng([lat, lng]);
            }

            /* dots mode: drop a circle marker at each point */
            if(trackStyle === 'dots'){
                trackLine.setStyle({opacity:0, weight:0});
                L.circleMarker([lat,lng],{
                    radius:4,
                    color:'#00ff00',
                    fillColor:'#00ff00',
                    fillOpacity:1,
                    weight:0
                }).addTo(leafletMap);
            } else {
                trackLine.setStyle({color:'#00ff00', weight:4, opacity:0.85});
            }

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

            var timeEl = document.getElementById('hud_time');
            if(timeEl){
                timeEl.textContent =
                    String(hrs).padStart(2,'0')
                    + ':' +
                    String(mins).padStart(2,'0')
                    + ':' +
                    String(secs).padStart(2,'0');
            }

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

            }else if(!userPanned){

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
                ((data[8]<<24)>>>0 | data[9]<<16 | data[10]<<8 | data[11]);

            // sign-extend int32
            if(mc & 0x80000000)
                mc = mc - 0x100000000;

            var motorCurrent =
                mc / 100;

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
                motorCurrent.toFixed(2);

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

/* =========================
   START GPS
========================= */

restoreCompliance();

/* restore track style button */
document.getElementById('btn-track-' + trackStyle).classList.add('active');
document.getElementById('btn-track-' + (trackStyle==='line'?'dots':'line')).classList.remove('active');

startGPS();

</script>
</body>
</html>
