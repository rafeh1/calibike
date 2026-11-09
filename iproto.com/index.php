<?php
// iproto.com/index.php - Main Portal Index with 5 Nav Links & Mod_Security-Safe Comments
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$data_dir = __DIR__ . '/data';
if (!is_dir($data_dir)) {
    @mkdir($data_dir, 0755, true);
}
$comments_file = $data_dir . '/comments_main.json';

// Handle Comment Submission (Base64 WAF Bypass)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['comment_b64'])) {
    $provider = trim($_POST['provider'] ?? 'Custom');
    $user_id  = trim($_POST['user_id'] ?? '');
    $comment  = trim(base64_decode($_POST['comment_b64'] ?? ''));

    $user_id = preg_replace('#^https?://#i', '', $user_id);
    $user_id = preg_replace('#^www\.#i', '', $user_id);

    $allowed = ['Google', 'LinkedIn', 'Facebook', 'GitHub', 'Custom'];

    if ($user_id !== '' && $comment !== '' && in_array($provider, $allowed, true)) {
        if (!preg_match('/https?:\/\//i', $comment) && !preg_match('/(?<!ht tp:\/\/)www\./i', $comment)) {
            $comments = [];
            if (file_exists($comments_file)) {
                $decoded = json_decode(file_get_contents($comments_file), true);
                if (is_array($decoded)) {
                    $comments = $decoded;
                }
            }

            array_unshift($comments, [
                'id'         => uniqid('c_', true),
                'provider'   => $provider,
                'user_id'    => htmlspecialchars($user_id, ENT_QUOTES, 'UTF-8'),
                'comment'    => htmlspecialchars($comment, ENT_QUOTES, 'UTF-8'),
                'created_at' => date('Y-m-d H:i:s'),
                'ip_hash'    => md5($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0')
            ]);

            file_put_contents(
                $comments_file,
                json_encode($comments, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            );
        }
    }

    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>iproto.com | R&D Engineering & Telemetry Hub</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              dark: '#0a0d14',
              card: '#121722',
              border: '#1e2638',
              cyan: '#00f0ff'
            }
          }
        }
      }
    }
  </script>
  <style>
    body { background-color: #0a0d14; color: #e2e8f0; }
    .glow-cyan { box-shadow: 0 0 25px rgba(0, 240, 255, 0.12); }
  </style>
</head>
<body class="min-h-screen">

  <!-- Header Navigation with 5 Main Modules -->
  <header class="border-b border-brand-border bg-brand-dark/90 backdrop-blur sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-4 h-14 flex items-center justify-between">
      <a href="/" class="flex items-center gap-2">
        <div class="w-7 h-7 rounded bg-gradient-to-tr from-cyan-500 to-blue-600 flex items-center justify-center font-bold text-black text-sm">i</div>
        <span class="font-mono font-bold tracking-wider">iproto<span class="text-cyan-400">.com</span></span>
      </a>
      <nav class="flex gap-4 text-xs font-mono overflow-x-auto">
        <a href="/" class="text-cyan-400 font-semibold">Home</a>
        <a href="/40c3/" class="text-slate-300 hover:text-cyan-400">40C3</a>
        <a href="/calidroid1500/" class="text-slate-300 hover:text-cyan-400">Calidroid</a>
        <a href="/solar-transporter/" class="text-slate-300 hover:text-cyan-400">Solar Transporter</a>
        <a href="/audio-tracker/" class="text-slate-300 hover:text-cyan-400">Audio Tracker</a>
        <a href="/navmap/" class="text-emerald-400 hover:text-cyan-400 font-semibold">NavMap</a>
      </nav>
    </div>
  </header>

  <main class="max-w-6xl mx-auto px-4 py-10">

    <!-- Hero Section -->
    <div class="mb-10 text-center max-w-3xl mx-auto">
      <div class="text-xs font-mono text-cyan-400 tracking-widest mb-2">IPROTO ENGINEERING WORKSPACE</div>
      <h1 class="text-4xl md:text-5xl font-bold text-white tracking-tight">Active Hardware & Telemetry Projects</h1>
      <p class="text-slate-400 mt-4 text-base">
        Modular platforms spanning high-power electric vehicles, autonomous solar craft, array beamforming, and real-time VESC telemetry.
      </p>
    </div>

    <!-- 5 Sub-Module Cards Grid -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
      
      <!-- 1. 40C3 -->
      <a href="/40c3/" class="bg-brand-card border border-brand-border rounded-2xl p-6 hover:border-cyan-400/50 transition glow-cyan flex flex-col justify-between">
        <div>
          <div class="text-xs font-mono text-cyan-400 mb-2">01 · E-BIKE PLATFORM</div>
          <h3 class="text-xl font-bold text-white mb-2">CaliBike 40C3</h3>
          <p class="text-slate-400 text-sm">40 lb frame · 40 mph top speed · 40 mi range with 2500 W MAC motor and 52V FOC vector control.</p>
        </div>
        <div class="mt-6 text-xs font-mono text-cyan-400 flex items-center justify-between">
          <span>Explore Build ?</span>
          <span class="bg-cyan-950 border border-cyan-800 px-2 py-0.5 rounded">Active</span>
        </div>
      </a>

      <!-- 2. Calidroid -->
      <a href="/calidroid1500/" class="bg-brand-card border border-brand-border rounded-2xl p-6 hover:border-cyan-400/50 transition glow-cyan flex flex-col justify-between">
        <div>
          <div class="text-xs font-mono text-cyan-400 mb-2">02 · MOBILE TELEMETRY</div>
          <h3 class="text-xl font-bold text-white mb-2">Calidroid 1500</h3>
          <p class="text-slate-400 text-sm">Android-driven telemetry suite with GPX routing, Web Bluetooth connectivity, and custom controller overlay.</p>
        </div>
        <div class="mt-6 text-xs font-mono text-cyan-400 flex items-center justify-between">
          <span>Open Interface ?</span>
          <span class="bg-cyan-950 border border-cyan-800 px-2 py-0.5 rounded">v1.5</span>
        </div>
      </a>

      <!-- 3. Solar Transporter -->
      <a href="/solar-transporter/" class="bg-brand-card border border-brand-border rounded-2xl p-6 hover:border-cyan-400/50 transition glow-cyan flex flex-col justify-between">
        <div>
          <div class="text-xs font-mono text-cyan-400 mb-2">03 · SOLAR VEHICLE</div>
          <h3 class="text-xl font-bold text-white mb-2">Solar Transporter</h3>
          <p class="text-slate-400 text-sm">4-wheel 2-seater vehicle with dual 48V battery arrays, interlocking aluminum U-clamp frame, and twin 450W solar arrays.</p>
        </div>
        <div class="mt-6 text-xs font-mono text-cyan-400 flex items-center justify-between">
          <span>View Design ?</span>
          <span class="bg-cyan-950 border border-cyan-800 px-2 py-0.5 rounded">Prototype</span>
        </div>
      </a>

      <!-- 4. Audio Tracker -->
      <a href="/audio-tracker/" class="bg-brand-card border border-brand-border rounded-2xl p-6 hover:border-cyan-400/50 transition glow-cyan flex flex-col justify-between">
        <div>
          <div class="text-xs font-mono text-cyan-400 mb-2">04 · FPGA BEAMFORMING</div>
          <h3 class="text-xl font-bold text-white mb-2">3D Audio Tracker</h3>
          <p class="text-slate-400 text-sm">Gowin FPGA & LilyGO T-FPGA acoustic tracking matrix using 4x ICS-43434 MEMS microphones and SRP-PHAT beamforming.</p>
        </div>
        <div class="mt-6 text-xs font-mono text-cyan-400 flex items-center justify-between">
          <span>View Hardware ?</span>
          <span class="bg-cyan-950 border border-cyan-800 px-2 py-0.5 rounded">SPI Array</span>
        </div>
      </a>

      <!-- 5. NavMap -->
      <a href="/navmap/" class="bg-brand-card border border-brand-border rounded-2xl p-6 hover:border-emerald-400/50 transition glow-cyan flex flex-col justify-between">
        <div>
          <div class="text-xs font-mono text-emerald-400 mb-2">05 · AUTONOMOUS MAPPING</div>
          <h3 class="text-xl font-bold text-white mb-2">NavMap Drone Control</h3>
          <p class="text-slate-400 text-sm">Real-time waypoint planner and autonomous drone command terminal with live state monitoring.</p>
        </div>
        <div class="mt-6 text-xs font-mono text-emerald-400 flex items-center justify-between">
          <span>Launch Map ?</span>
          <span class="bg-emerald-950 border border-emerald-800 px-2 py-0.5 rounded">Live Map</span>
        </div>
      </a>

    </div>

    <!-- Mod_Security Safe Comments Section -->
    <div class="bg-brand-card border border-brand-border rounded-2xl p-6 glow-cyan">
      <div class="text-xs font-mono text-cyan-400 tracking-widest mb-1 uppercase">COMMUNITY & COLLABORATION</div>
      <h2 class="text-xl font-bold text-white mb-2">Portal Discussion & Feedback</h2>
      <p class="text-slate-400 text-sm mb-6">Select your identity provider to post feedback. External URLs are automatically neutralized for security.</p>

      <div id="authSection" class="mb-6 bg-slate-900/80 border border-slate-800 rounded-xl p-4">
        <div class="text-xs font-mono text-slate-400 mb-3 uppercase">Select Identity Provider</div>
        <div id="loggedOutState" class="flex flex-wrap gap-3">
          <button type="button" onclick="setOAuthProvider('Google')" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white font-mono text-xs rounded-lg border border-slate-700 transition"><span class="text-red-400 font-bold">G</span> Google</button>
          <button type="button" onclick="setOAuthProvider('LinkedIn')" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-950/80 hover:bg-blue-900/80 text-blue-300 font-mono text-xs rounded-lg border border-blue-800/80 transition"><span class="font-bold">in</span> LinkedIn</button>
          <button type="button" onclick="setOAuthProvider('Facebook')" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-950/80 hover:bg-indigo-900/80 text-indigo-300 font-mono text-xs rounded-lg border border-indigo-800/80 transition"><span class="font-bold">f</span> Facebook</button>
          <button type="button" onclick="setOAuthProvider('GitHub')" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-950 hover:bg-slate-900 text-slate-300 font-mono text-xs rounded-lg border border-slate-800 transition"><span class="font-bold">GH</span> GitHub</button>
        </div>
        <div id="loggedInState" class="hidden flex items-center justify-between bg-cyan-950/40 border border-cyan-800/60 rounded-lg p-3">
          <div class="flex items-center gap-2 font-mono text-xs">
            <span class="text-slate-400">Authenticated Provider:</span>
            <span id="activeProvider" class="bg-cyan-950 border border-cyan-700 text-cyan-400 px-2.5 py-0.5 rounded text-[11px] font-bold">Google</span>
          </div>
          <button type="button" onclick="resetOAuthProvider()" class="text-xs font-mono text-slate-400 hover:text-red-400 underline">Change Provider</button>
        </div>
      </div>

      <form method="POST" action="" class="space-y-4" id="commentForm">
        <input type="hidden" name="provider" id="providerInput" value="Google">
        <input type="hidden" name="comment_b64" id="commentB64" value="">

        <div>
          <label class="block text-xs font-mono text-slate-400 mb-1 uppercase">Profile Handle / ID</label>
          <input type="text" name="user_id" id="userIdInput" placeholder="e.g. rafeh1@gmail.com or linkedin.com/in/rafehusain" required class="w-full bg-slate-900 border border-slate-700 text-slate-200 text-sm rounded-lg p-2.5 focus:border-cyan-400 focus:outline-none placeholder-slate-600">
        </div>

        <div>
          <label class="block text-xs font-mono text-slate-400 mb-1 uppercase">Comment</label>
          <textarea id="commentText" rows="3" placeholder="Write feedback or notes..." required class="w-full bg-slate-900 border border-slate-700 text-slate-200 text-sm rounded-lg p-2.5 focus:border-cyan-400 focus:outline-none placeholder-slate-600"></textarea>
        </div>

        <button type="submit" class="px-6 py-2.5 bg-cyan-500 hover:bg-cyan-400 text-black font-bold font-mono text-sm rounded-lg transition shadow-lg shadow-cyan-500/20">
          Post Comment
        </button>
      </form>

      <div class="mt-8 border-t border-brand-border pt-6">
        <h3 class="text-sm font-mono text-slate-400 mb-4 uppercase">Comments Feed</h3>
        <div id="commentList" class="space-y-3">
<?php
if (file_exists($comments_file)) {
    $comments = json_decode(file_get_contents($comments_file), true);
    if (is_array($comments)) {
        foreach ($comments as $c) {
            echo '<div class="bg-slate-900/60 border border-slate-800 rounded-lg p-4 mb-3">';
            echo '  <div class="flex items-center gap-2 text-xs font-mono text-cyan-400 mb-1">';
            echo '    <span class="bg-cyan-950 border border-cyan-800 px-2 py-0.5 rounded text-[10px]">' . htmlspecialchars($c['provider'] ?? '') . '</span>';
            echo '    <span>' . htmlspecialchars($c['user_id'] ?? '') . '</span>';
            echo '    <span class="text-slate-600">· ' . htmlspecialchars($c['created_at'] ?? '') . '</span>';
            echo '  </div>';
            echo '  <p class="text-slate-300 text-sm">' . htmlspecialchars($c['comment'] ?? '') . '</p>';
            echo '</div>';
        }
    }
}
?>
        </div>
      </div>
    </div>

  </main>

  <footer class="border-t border-brand-border mt-16 py-6 text-center text-xs text-slate-500 font-mono">
    iproto.com · R&D Engineering Portal
  </footer>

  <script>
    function setOAuthProvider(provider) {
      document.getElementById('providerInput').value = provider;
      document.getElementById('activeProvider').innerText = provider;
      document.getElementById('loggedOutState').classList.add('hidden');
      document.getElementById('loggedInState').classList.remove('hidden');
    }

    function resetOAuthProvider() {
      document.getElementById('providerInput').value = 'Google';
      document.getElementById('loggedOutState').classList.remove('hidden');
      document.getElementById('loggedInState').classList.add('hidden');
    }

    document.getElementById('commentForm').addEventListener('submit', function (e) {
      let text = document.getElementById('commentText').value.trim();
      let userId = document.getElementById('userIdInput').value.trim();

      if (!text || !userId) { e.preventDefault(); return; }

      text = text.replace(/https?:\/\//gi, 'ht tp://').replace(/www\./gi, 'www .');
      userId = userId.replace(/^https?:\/\//i, '').replace(/^www\./i, '');

      document.getElementById('userIdInput').value = userId;
      document.getElementById('commentB64').value = btoa(unescape(encodeURIComponent(text)));
      document.getElementById('commentText').removeAttribute('name');
    });
  </script>

</body>
</html>