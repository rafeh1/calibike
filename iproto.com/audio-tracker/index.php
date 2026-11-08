<?php
/**
 * iproto.com/audio-tracker/
 * Acoustic Drone Audio Tracker Platform
 */
$page_title = 'Audio Tracker — Acoustic Drone Tracking Platform';
$page_desc  = '8-channel circular I2S MEMS microphone array with microsecond TDOA hardware timestamping on a Gowin FPGA and DoA beamforming on an ESP32-S3.';

$specs = [
    'Microphones'        => '8× ICS-43434 I2S MEMS, circular array',
    'FPGA'               => 'Gowin GW1NSR-LV4C (LILYGO T-FPGA) — DSP macros + hard CPU',
    'Host MCU'           => 'ESP32-S3 (dual-core Xtensa LX7, 240 MHz)',
    'Sample rates'       => '48 kHz (tracking) / 16 kHz (detection)',
    'Timing resolution'  => '~1 µs hardware timestamps (sample period 20.8 µs @ 48 kHz)',
    'Data link'          => 'Quad SPI (SPI2, QIO half-duplex), FPGA as audio-FIFO slave',
    'Control link'       => 'SPI3 3-wire, ESP32 master → FPGA registers',
    'Output'             => 'Azimuth + elevation, real-time to servo / gimbal',
];

$chain = [
    ['01', 'Capture',      'Eight ICS-43434 mics share one BCLK/WS from the FPGA, so every channel is sampled on the same clock edge — zero inter-channel skew by design.'],
    ['02', 'Deserialize',  'Parallel I2S receivers in fabric unpack 24-bit samples from all four data lines (two mics per line, L/R slots).'],
    ['03', 'Timestamp',    'A free-running counter latches a hardware timestamp on each frame and on onset events, giving microsecond-scale TDOA without software jitter.'],
    ['04', 'Beam sweep',   'DSP blocks run a delay-and-sum sweep with half-sample delay steps — one summed beam per look direction instead of every mic-pair combination.'],
    ['05', 'Transfer',     'Frames, timestamps and beam energies are batched into an audio FIFO and pulled by the ESP32-S3 over quad SPI.'],
    ['06', 'DoA estimate', 'The ESP32-S3 refines the peak with GCC-PHAT / TDOA least-squares to resolve azimuth and elevation.'],
    ['07', 'Track & act',  'Smoothed bearing drives a pan/tilt servo or gimbal and is streamed to the web dashboard.'],
];

$modes = [
    ['track on', '48 kHz', 'Full-rate capture for the TDOA / beamforming tracker. Best angular resolution.'],
    ['va on',    '16 kHz', 'Decimated stream for the VolAnti acoustic drone detector (classification of rotor signatures).'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($page_title) ?></title>
<meta name="description" content="<?= htmlspecialchars($page_desc) ?>">
<style>
  :root{
    --bg:#0b0f14; --panel:#121922; --line:#1f2a36; --text:#d9e2ec; --muted:#8a9bb0;
    --accent:#3ad1a0; --accent2:#4aa3ff; --warn:#ffb547;
    --mono:ui-monospace,SFMono-Regular,Menlo,Consolas,monospace;
  }
  *{box-sizing:border-box}
  body{margin:0;background:var(--bg);color:var(--text);font:16px/1.6 system-ui,-apple-system,Segoe UI,Roboto,sans-serif}
  a{color:var(--accent2);text-decoration:none} a:hover{text-decoration:underline}
  .wrap{max-width:1080px;margin:0 auto;padding:0 20px}
  nav{border-bottom:1px solid var(--line);padding:14px 0;font-size:14px}
  nav .wrap{display:flex;gap:18px;align-items:center}
  nav .brand{font-weight:700;color:var(--text)}
  nav .crumb{color:var(--muted)}
  header{padding:56px 0 32px}
  .eyebrow{font-family:var(--mono);color:var(--accent);font-size:13px;letter-spacing:.08em;text-transform:uppercase}
  h1{font-size:clamp(28px,5vw,44px);line-height:1.15;margin:8px 0 12px}
  h2{font-size:22px;margin:0 0 16px}
  .lede{color:var(--muted);font-size:18px;max-width:760px}
  section{padding:32px 0;border-top:1px solid var(--line)}
  .panel{background:var(--panel);border:1px solid var(--line);border-radius:10px;padding:20px}
  .diagram{overflow-x:auto}
  .diagram svg{display:block;min-width:900px;width:100%;height:auto}
  .grid{display:grid;gap:16px;grid-template-columns:repeat(auto-fit,minmax(280px,1fr))}
  table{width:100%;border-collapse:collapse;font-size:15px}
  td{padding:10px 8px;border-bottom:1px solid var(--line);vertical-align:top}
  td:first-child{color:var(--muted);width:38%;white-space:nowrap}
  .step{display:flex;gap:14px;padding:14px 0;border-bottom:1px solid var(--line)}
  .step:last-child{border-bottom:0}
  .num{font-family:var(--mono);color:var(--accent);font-size:13px;min-width:28px;padding-top:3px}
  .step b{display:block}
  .step p{margin:2px 0 0;color:var(--muted)}
  .mode{border-left:3px solid var(--accent);padding-left:14px;margin-bottom:16px}
  .mode code{font-family:var(--mono);background:#0b1118;border:1px solid var(--line);padding:2px 6px;border-radius:4px;color:var(--accent)}
  .math{font-family:var(--mono);background:#0b1118;border:1px solid var(--line);border-radius:8px;padding:14px;font-size:14px;overflow-x:auto;color:#cfe8ff}
  footer{padding:28px 0 48px;color:var(--muted);font-size:14px;border-top:1px solid var(--line)}
</style>
</head>
<body>

<nav>
  <div class="wrap">
    <a class="brand" href="https://iproto.com/">iproto.com</a>
    <a href="https://iproto.com/">← Home</a>
    <span class="crumb">/ Audio Tracker</span>
  </div>
</nav>

<header>
  <div class="wrap">
    <div class="eyebrow">FPGA + ESP32-S3 Signal Processing Chain</div>
    <h1>Acoustic Drone Audio Tracker Platform</h1>
    <p class="lede">An 8-channel circular I2S MEMS microphone array running microsecond TDOA hardware
      timestamping on a Gowin FPGA, with direction-of-arrival beamforming on an ESP32-S3.
      The system listens for the tonal rotor signature of small drones, resolves its
      bearing in azimuth and elevation, and steers a pan/tilt gimbal in real time.</p>
  </div>
</header>

<main class="wrap">

  <!-- ===== Block diagram ===== -->
  <section>
    <h2>System block diagram</h2>
    <div class="panel diagram">
      <svg viewBox="0 0 1000 470" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Audio tracker block diagram">
        <defs>
          <marker id="ar" viewBox="0 0 10 10" refX="9" refY="5" markerWidth="7" markerHeight="7" orient="auto">
            <path d="M0,0 L10,5 L0,10 z" fill="#4aa3ff"/>
          </marker>
          <style>
            .box{fill:#0f1620;stroke:#2b3a4b;stroke-width:1.5}
            .grp{fill:none;stroke:#3ad1a0;stroke-width:1.2;stroke-dasharray:5 4}
            .t{fill:#d9e2ec;font:600 14px system-ui,sans-serif}
            .s{fill:#8a9bb0;font:12px system-ui,sans-serif}
            .g{fill:#3ad1a0;font:600 12px ui-monospace,monospace;letter-spacing:.06em}
            .w{stroke:#4aa3ff;stroke-width:1.8;fill:none;marker-end:url(#ar)}
            .wl{fill:#4aa3ff;font:11px ui-monospace,monospace}
          </style>
        </defs>

        <!-- Mic array -->
        <text class="g" x="20" y="28">MIC ARRAY</text>
        <circle cx="105" cy="150" r="70" fill="none" stroke="#2b3a4b" stroke-width="1.5"/>
        <?php for ($i = 0; $i < 8; $i++):
            $a = deg2rad($i * 45 - 90);
            $x = 105 + 70 * cos($a); $y = 150 + 70 * sin($a); ?>
          <circle cx="<?= round($x,1) ?>" cy="<?= round($y,1) ?>" r="9" fill="#3ad1a0"/>
          <text x="<?= round($x,1) ?>" y="<?= round($y+4,1) ?>" text-anchor="middle" style="fill:#0b0f14;font:700 10px ui-monospace,monospace"><?= $i ?></text>
        <?php endfor; ?>
        <text class="s" x="105" y="154" text-anchor="middle">8× ICS-43434</text>
        <text class="s" x="105" y="250" text-anchor="middle">24-bit I2S MEMS</text>

        <!-- FPGA group -->
        <rect class="grp" x="225" y="40" width="330" height="320" rx="10"/>
        <text class="g" x="240" y="62">GOWIN GW1NSR-LV4C FPGA</text>

        <rect class="box" x="245" y="80" width="140" height="60" rx="6"/>
        <text class="t" x="315" y="105" text-anchor="middle">I2S Clock Gen</text>
        <text class="s" x="315" y="124" text-anchor="middle">BCLK / WS shared</text>

        <rect class="box" x="245" y="160" width="140" height="60" rx="6"/>
        <text class="t" x="315" y="185" text-anchor="middle">8-ch I2S RX</text>
        <text class="s" x="315" y="204" text-anchor="middle">4 data lines, L/R</text>

        <rect class="box" x="400" y="80" width="140" height="60" rx="6"/>
        <text class="t" x="470" y="105" text-anchor="middle">TDOA Timestamp</text>
        <text class="s" x="470" y="124" text-anchor="middle">µs counter latch</text>

        <rect class="box" x="400" y="160" width="140" height="60" rx="6"/>
        <text class="t" x="470" y="185" text-anchor="middle">Delay-and-Sum</text>
        <text class="s" x="470" y="204" text-anchor="middle">DSP beam sweep</text>

        <rect class="box" x="245" y="240" width="140" height="60" rx="6"/>
        <text class="t" x="315" y="265" text-anchor="middle">Decimator</text>
        <text class="s" x="315" y="284" text-anchor="middle">48 → 16 kHz</text>

        <rect class="box" x="400" y="240" width="140" height="60" rx="6"/>
        <text class="t" x="470" y="265" text-anchor="middle">Audio FIFO</text>
        <text class="s" x="470" y="284" text-anchor="middle">frame batching</text>

        <text class="s" x="245" y="340">REG1 mode: track (48 k) / va (16 k)</text>

        <!-- ESP32 group -->
        <rect class="grp" x="625" y="40" width="200" height="320" rx="10"/>
        <text class="g" x="640" y="62">ESP32-S3</text>

        <rect class="box" x="645" y="80" width="160" height="60" rx="6"/>
        <text class="t" x="725" y="105" text-anchor="middle">Quad-SPI Master</text>
        <text class="s" x="725" y="124" text-anchor="middle">raw-register DMA</text>

        <rect class="box" x="645" y="160" width="160" height="60" rx="6"/>
        <text class="t" x="725" y="185" text-anchor="middle">DoA Estimator</text>
        <text class="s" x="725" y="204" text-anchor="middle">GCC-PHAT / LSQ</text>

        <rect class="box" x="645" y="240" width="160" height="60" rx="6"/>
        <text class="t" x="725" y="265" text-anchor="middle">Drone Detector</text>
        <text class="s" x="725" y="284" text-anchor="middle">VolAnti classifier</text>

        <!-- Outputs -->
        <text class="g" x="860" y="62">OUTPUTS</text>
        <rect class="box" x="855" y="80" width="130" height="60" rx="6"/>
        <text class="t" x="920" y="105" text-anchor="middle">Pan/Tilt Gimbal</text>
        <text class="s" x="920" y="124" text-anchor="middle">az + el servo</text>

        <rect class="box" x="855" y="160" width="130" height="60" rx="6"/>
        <text class="t" x="920" y="185" text-anchor="middle">Web Dashboard</text>
        <text class="s" x="920" y="204" text-anchor="middle">Wi-Fi / WS</text>

        <!-- Wires -->
        <path class="w" d="M175 150 H215 V190 H243"/>
        <path class="w" d="M245 110 H200 V120 H182"/>
        <path class="w" d="M385 190 H398"/>
        <path class="w" d="M385 180 H392 V110 H398"/>
        <path class="w" d="M315 220 V238"/>
        <path class="w" d="M470 220 V238"/>
        <path class="w" d="M385 270 H398"/>
        <path class="w" d="M540 110 H560 V250 H545" style="marker-end:none"/>
        <path class="w" d="M540 270 H600 V110 H643"/>
        <text class="wl" x="565" y="100">SPI2 QIO</text>
        <path class="w" d="M643 330 H590 V325 H557" />
        <rect class="box" x="645" y="315" width="160" height="30" rx="6"/>
        <text class="s" x="725" y="335" text-anchor="middle">SPI3 control / regs</text>
        <text class="wl" x="565" y="318">SPI3</text>
        <path class="w" d="M725 140 V158"/>
        <path class="w" d="M725 220 V238" style="marker-end:none"/>
        <path class="w" d="M805 190 H830 V110 H853"/>
        <path class="w" d="M805 200 H853"/>
        <path class="w" d="M805 270 H840 V210 H853"/>

        <!-- Legend -->
        <text class="s" x="20" y="410">Single shared BCLK/WS → all channels sampled on the same edge; inter-mic delay is purely acoustic.</text>
        <text class="s" x="20" y="430">FPGA does the time-critical work (capture, timestamps, beam energy); ESP32-S3 refines bearing, classifies, and drives outputs.</text>
      </svg>
    </div>
  </section>

  <!-- ===== Signal chain ===== -->
  <section>
    <h2>Signal processing chain</h2>
    <div class="panel">
      <?php foreach ($chain as [$n, $title, $text]): ?>
        <div class="step">
          <div class="num"><?= $n ?></div>
          <div><b><?= htmlspecialchars($title) ?></b><p><?= htmlspecialchars($text) ?></p></div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- ===== Specs + modes ===== -->
  <section>
    <div class="grid">
      <div>
        <h2>Hardware specifications</h2>
        <div class="panel">
          <table>
            <?php foreach ($specs as $k => $v): ?>
              <tr><td><?= htmlspecialchars($k) ?></td><td><?= htmlspecialchars($v) ?></td></tr>
            <?php endforeach; ?>
          </table>
        </div>
      </div>
      <div>
        <h2>Operating modes</h2>
        <div class="panel">
          <?php foreach ($modes as [$cmd, $rate, $desc]): ?>
            <div class="mode">
              <code><?= htmlspecialchars($cmd) ?></code> &nbsp;<strong><?= $rate ?></strong>
              <p style="margin:6px 0 0;color:var(--muted)"><?= htmlspecialchars($desc) ?></p>
            </div>
          <?php endforeach; ?>
          <p style="color:var(--muted);font-size:14px;margin:0">Mode is selected by a config bit in FPGA register REG1, written over the SPI3 control bus.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== Theory ===== -->
  <section>
    <h2>How direction finding works</h2>
    <p>A sound wave from a distant drone reaches each microphone at a slightly different time.
      For a plane wave arriving from unit direction <b>u</b> (azimuth θ, elevation φ), the delay at mic <i>i</i>
      at position <b>p</b><sub>i</sub> relative to the array centre is:</p>
    <div class="math">τᵢ = −(pᵢ · u) / c&nbsp;&nbsp;&nbsp;&nbsp;u = [cos φ cos θ, cos φ sin θ, sin φ],&nbsp; c ≈ 343 m/s</div>
    <p>The FPGA sweeps a grid of look directions, shifts each channel by its predicted delay and sums them.
      The direction with maximum summed energy is the coarse bearing:</p>
    <div class="math">P(θ, φ) = Σₙ | Σᵢ xᵢ[n − τᵢ(θ, φ)·fₛ] |²</div>
    <p>At 48 kHz one sample is 20.8 µs, or about 7.1 mm of acoustic path. Half-sample steps in the FPGA give a
      fast coarse sweep; the ESP32-S3 then refines the peak using hardware-timestamped TDOA and
      GCC-PHAT cross-correlation for sub-sample accuracy. Elevation is recoverable because the array is
      not purely planar — mic arms are raised so the geometry spans three dimensions.</p>
  </section>

</main>

<footer>
  <div class="wrap">
    © <?= date('Y') ?> iproto.com · Audio Tracker · <a href="https://iproto.com/">← Back to Home</a>
  </div>
</footer>

</body>
</html>
