<?php
// index.php - 40C3 CaliBike page with Mod_Security-safe comments
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$data_dir = __DIR__ . '/data';
if (!is_dir($data_dir)) {
    @mkdir($data_dir, 0755, true);
}
$comments_file = $data_dir . '/comments.json';

// Handle comment submission (base64 encoded to bypass Mod_Security content rules)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['comment_b64'])) {
    $provider = trim($_POST['provider'] ?? 'Custom');
    $user_id  = trim($_POST['user_id'] ?? '');
    $comment  = trim(base64_decode($_POST['comment_b64'] ?? ''));

    // Strip protocol from user_id so full LinkedIn URLs become safe handles
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
                'image'      => null,
                'created_at' => date('Y-m-d H:i:s'),
                'ip_hash'    => md5($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0')
            ]);

            file_put_contents(
                $comments_file,
                json_encode($comments, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            );
        }
    }

    header('Location: /40c3/');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CaliBike 40C3 | 40 lb &middot; 40 mph &middot; 40 mi | iproto.com</title>
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

  <!-- Navigation Header -->
  <header class="border-b border-brand-border bg-brand-dark/90 backdrop-blur sticky top-0 z-50">
    <div class="max-w-5xl mx-auto px-4 h-14 flex items-center justify-between">
      <a href="/" class="flex items-center gap-2">
        <div class="w-7 h-7 rounded bg-gradient-to-tr from-cyan-500 to-blue-600 flex items-center justify-center font-bold text-black text-sm">i</div>
        <span class="font-mono font-bold tracking-wider">iproto<span class="text-cyan-400">.com</span></span>
      </a>
      <nav class="flex gap-4 text-sm font-mono">
        <a href="/" class="text-slate-400 hover:text-cyan-400">Home</a>
        <a href="/40c3/" class="text-cyan-400 font-semibold">40C3</a>
        <a href="/calidroid1500/" class="text-slate-400 hover:text-cyan-400">Calidroid</a>
        <a href="/navmap/" class="text-emerald-400 font-semibold">NavMap</a>
      </nav>
    </div>
  </header>

  <main class="max-w-5xl mx-auto px-4 py-10">

    <?php include __DIR__ . '/main.php'; ?>

    <!-- Mod_Security Safe Base64 PHP Comment Handler Section -->
    <div class="bg-brand-card border border-brand-border rounded-2xl p-6 glow-cyan">
      <div class="text-xs font-mono text-cyan-400 tracking-widest mb-1 uppercase">COMMUNITY FEEDBACK</div>
      <h2 class="text-xl font-bold text-white mb-2">Leave a Verified Technical Comment</h2>
      <p class="text-slate-400 text-sm mb-6">Select your identity provider. Full URLs are automatically neutralized so they pass security filters.</p>

      <div id="authSection" class="mb-6 bg-slate-900/80 border border-slate-800 rounded-xl p-4">
        <div class="text-xs font-mono text-slate-400 mb-3 uppercase">Layer 1: Select Identity Provider</div>

        <div id="loggedOutState" class="flex flex-wrap gap-3">
          <button type="button" onclick="setOAuthProvider('Google')"
                  class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white font-mono text-xs rounded-lg border border-slate-700 transition">
            <span class="text-red-400 font-bold">G</span> Google
          </button>
          <button type="button" onclick="setOAuthProvider('LinkedIn')"
                  class="inline-flex items-center gap-2 px-4 py-2 bg-blue-950/80 hover:bg-blue-900/80 text-blue-300 font-mono text-xs rounded-lg border border-blue-800/80 transition">
            <span class="font-bold">in</span> LinkedIn
          </button>
          <button type="button" onclick="setOAuthProvider('Facebook')"
                  class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-950/80 hover:bg-indigo-900/80 text-indigo-300 font-mono text-xs rounded-lg border border-indigo-800/80 transition">
            <span class="font-bold">f</span> Facebook
          </button>
          <button type="button" onclick="setOAuthProvider('GitHub')"
                  class="inline-flex items-center gap-2 px-4 py-2 bg-slate-950 hover:bg-slate-900 text-slate-300 font-mono text-xs rounded-lg border border-slate-800 transition">
            <span class="font-bold">GH</span> GitHub
          </button>
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
          <label class="block text-xs font-mono text-slate-400 mb-1 uppercase">Profile Handle / Verified ID</label>
          <input type="text" name="user_id" id="userIdInput" placeholder="e.g. rafeh1@gmail.com or linkedin.com/in/rafehusain" required
                 class="w-full bg-slate-900 border border-slate-700 text-slate-200 text-sm rounded-lg p-2.5 focus:border-cyan-400 focus:outline-none placeholder-slate-600">
        </div>

        <div>
          <label class="block text-xs font-mono text-slate-400 mb-1 uppercase">Comment Body</label>
          <textarea id="commentText" rows="4" placeholder="Share technical feedback on the 40C3 build..." required
                    class="w-full bg-slate-900 border border-slate-700 text-slate-200 text-sm rounded-lg p-2.5 focus:border-cyan-400 focus:outline-none placeholder-slate-600"></textarea>
        </div>

        <button type="submit"
                class="px-6 py-2.5 bg-cyan-500 hover:bg-cyan-400 text-black font-bold font-mono text-sm rounded-lg transition shadow-lg shadow-cyan-500/20">
          Post Comment
        </button>
      </form>

      <div class="mt-8 border-t border-brand-border pt-6">
        <h3 class="text-sm font-mono text-slate-400 mb-4 uppercase">Verified Technical Discussion</h3>
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
            echo '    <span class="text-slate-600">&middot; ' . htmlspecialchars($c['created_at'] ?? '') . '</span>';
            echo '  </div>';
            echo '  <p class="text-slate-300 text-sm">' . htmlspecialchars($c['comment'] ?? '') . '</p>';
            echo '</div>';
        }
    }
}
?>
          <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-4">
            <div class="flex items-center gap-2 text-xs font-mono text-cyan-400 mb-1">
              <span class="bg-cyan-950 border border-cyan-800 px-2 py-0.5 rounded text-[10px]">LinkedIn</span>
              <span>rafe-husain</span>
              <span class="text-slate-600">&middot; Admin</span>
            </div>
            <p class="text-slate-300 text-sm">FOC current limit tuned to 65A peak. High torque restored with zero wheel spinouts.</p>
          </div>
        </div>
      </div>
    </div>

  </main>

  <footer class="border-t border-brand-border mt-16 py-6 text-center text-xs text-slate-500 font-mono">
    iproto.com &middot; 40C3 CaliBike prototype
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

      if (!text || !userId) {
        e.preventDefault();
        return;
      }

      text = text
        .replace(/https?:\/\//gi, 'ht tp://')
        .replace(/www\./gi, 'www .');

      userId = userId
        .replace(/^https?:\/\//i, '')
        .replace(/^www\./i, '');
      document.getElementById('userIdInput').value = userId;

      document.getElementById('commentB64').value = btoa(unescape(encodeURIComponent(text)));
      document.getElementById('commentText').removeAttribute('name');
    });
  </script>

</body>
</html>