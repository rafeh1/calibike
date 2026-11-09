<?php
// post_comment.php - Integrated Comment Section & Backend API
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$data_dir = __DIR__ . '/data';
if (!is_dir($data_dir)) {
    @mkdir($data_dir, 0755, true);
}
$comments_file = $data_dir . '/comments.json';

// ============================================================================
// 1. POST REQUEST HANDLER (API Submission)
// ============================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');

    $provider    = filter_input(INPUT_POST, 'provider', FILTER_SANITIZE_SPECIAL_CHARS);
    $user_id     = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_SPECIAL_CHARS);
    $comment_raw = trim($_POST['comment_text'] ?? '');

    // Layer 1 Validation: Social Provider
    $allowed_providers = ['Google', 'LinkedIn', 'Facebook', 'GitHub', 'Custom'];
    if (empty($provider) || !in_array($provider, $allowed_providers) || empty($user_id)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Layer 1 Violation: Valid Social Identity required.']);
        exit;
    }

    if (empty($comment_raw)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Comment body cannot be empty.']);
        exit;
    }

    // Layer 2 Filter: Regex Spam & Link Blocking
    $link_pattern = '/(https?:\/\/|www\.|[a-zA-Z0-9-]+\.(com|net|org|xyz|io|co|me|info|biz|tk)|t\.me\/|telegram\.me\/)/i';
    if (preg_match($link_pattern, $comment_raw)) {
        http_response_code(422);
        echo json_encode([
            'success' => false, 
            'error' => 'Layer 2 Violation: External website links or domain handles are not allowed to prevent spam.'
        ]);
        exit;
    }

    $comment_clean = htmlspecialchars($comment_raw, ENT_QUOTES, 'UTF-8');
    $comments_data = file_exists($comments_file) ? json_decode(file_get_contents($comments_file), true) : [];
    if (!is_array($comments_data)) {
        $comments_data = [];
    }

    $image_path = null;
    if (isset($_FILES['comment_image']) && $_FILES['comment_image']['error'] === UPLOAD_ERR_OK) {
        $img_dir = __DIR__ . '/data/uploads';
        if (!is_dir($img_dir)) {
            @mkdir($img_dir, 0755, true);
        }
        $ext = strtolower(pathinfo($_FILES['comment_image']['name'], PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($ext, $allowed_exts)) {
            $filename = uniqid('img_', true) . '.' . $ext;
            if (move_uploaded_file($_FILES['comment_image']['tmp_name'], $img_dir . '/' . $filename)) {
                $image_path = '/40c3/data/uploads/' . $filename;
            }
        }
    }

    $new_entry = [
        'id'         => uniqid('c_', true),
        'provider'   => $provider,
        'user_id'    => $user_id,
        'comment'    => $comment_clean,
        'image'      => $image_path,
        'created_at' => date('Y-m-d H:i:s'),
        'ip_hash'    => md5($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0')
    ];

    array_unshift($comments_data, $new_entry);
    file_put_contents($comments_file, json_encode($comments_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    echo json_encode([
        'success' => true,
        'comment' => $new_entry
    ]);
    exit;
}

// ============================================================================
// 2. GET REQUEST / INCLUDE HANDLER (Renders HTML UI Component)
// ============================================================================
?>
<div class="bg-brand-card border border-brand-border rounded-2xl p-6 glow-cyan">
  <div class="text-xs font-mono text-cyan-400 tracking-widest mb-1 uppercase">COMMUNITY FEEDBACK</div>
  <h2 class="text-xl font-bold text-white mb-2">Leave a Verified Technical Comment</h2>
  <p class="text-slate-400 text-sm mb-6">Select your identity provider to sign in. External website URLs are automatically blocked to prevent spam.</p>

  <!-- Layer 1: Social Identity Selection -->
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

    <!-- Selected Provider Indicator -->
    <div id="loggedInState" class="hidden flex items-center justify-between bg-cyan-950/40 border border-cyan-800/60 rounded-lg p-3">
      <div class="flex items-center gap-2 font-mono text-xs">
        <span class="text-slate-400">Authenticated Provider:</span>
        <span id="activeProvider" class="bg-cyan-950 border border-cyan-700 text-cyan-400 px-2.5 py-0.5 rounded text-[11px] font-bold">Google</span>
      </div>
      <button type="button" onclick="resetOAuthProvider()" class="text-xs font-mono text-slate-400 hover:text-red-400 underline">Change Provider</button>
    </div>
  </div>

  <!-- Comment Submission Form -->
  <form id="commentForm" onsubmit="submitFilteredComment(event)" class="space-y-4">
    <div>
      <label class="block text-xs font-mono text-slate-400 mb-1 uppercase">Profile Handle / Verified ID</label>
      <input type="text" id="userIdInput" placeholder="e.g. user@gmail.com or linkedin.com/in/profile" required
             class="w-full bg-slate-900 border border-slate-700 text-slate-200 text-sm rounded-lg p-2.5 focus:border-cyan-400 focus:outline-none placeholder-slate-600">
    </div>

    <div>
      <label class="block text-xs font-mono text-slate-400 mb-1 uppercase">Comment Body</label>
      <textarea id="commentText" rows="4" placeholder="Share technical feedback on the 40C3 build (Note: External web links are blocked)..." required
                class="w-full bg-slate-900 border border-slate-700 text-slate-200 text-sm rounded-lg p-2.5 focus:border-cyan-400 focus:outline-none placeholder-slate-600"></textarea>
    </div>

    <div>
      <label class="block text-xs font-mono text-slate-400 mb-1 uppercase">Optional Attachment Image</label>
      <input type="file" id="imageUploadInput" accept="image/*"
             class="text-xs font-mono text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-mono file:bg-slate-800 file:text-cyan-400 hover:file:bg-slate-700">
    </div>

    <!-- Layer 2 Alert Box -->
    <div id="linkWarning" class="hidden bg-red-950/60 border border-red-800 text-red-300 text-xs font-mono p-3 rounded-lg">
      &#9888; <strong>Spam Protection Triggered:</strong> External website links or domain references are blocked.
    </div>

    <button type="submit" 
            class="px-6 py-2.5 bg-cyan-500 hover:bg-cyan-400 text-black font-bold font-mono text-sm rounded-lg transition shadow-lg shadow-cyan-500/20">
      Post Comment
    </button>
  </form>

  <!-- Verified Comments Feed -->
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
              echo '    <span class="bg-cyan-950 border border-cyan-800 px-2 py-0.5 rounded text-[10px]">' . htmlspecialchars($c['provider']) . '</span>';
              echo '    <span>' . htmlspecialchars($c['user_id']) . '</span>';
              echo '    <span class="text-slate-600">• ' . htmlspecialchars($c['created_at']) . '</span>';
              echo '  </div>';
              echo '  <p class="text-slate-300 text-sm">' . htmlspecialchars($c['comment']) . '</p>';
              if (!empty($c['image'])) {
                echo '  <div class="mt-3 max-w-sm rounded border border-slate-800 overflow-hidden"><img src="' . htmlspecialchars($c['image']) . '" class="w-full h-auto"></div>';
              }
              echo '</div>';
            }
          }
        }
      ?>
      <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-4">
        <div class="flex items-center gap-2 text-xs font-mono text-cyan-400 mb-1">
          <span class="bg-cyan-950 border border-cyan-800 px-2 py-0.5 rounded text-[10px]">LinkedIn</span>
          <span>rafe-husain</span>
          <span class="text-slate-600">• Admin</span>
        </div>
        <p class="text-slate-300 text-sm">FOC current limit tuned to 65A peak. High torque restored with zero wheel spinouts.</p>
      </div>
    </div>
  </div>
</div>

<script>
  let selectedProvider = "Google";

  function setOAuthProvider(provider) {
    selectedProvider = provider;
    document.getElementById('activeProvider').innerText = provider;
    document.getElementById('loggedOutState').classList.add('hidden');
    document.getElementById('loggedInState').classList.remove('hidden');
  }

  function resetOAuthProvider() {
    document.getElementById('loggedOutState').classList.remove('hidden');
    document.getElementById('loggedInState').classList.add('hidden');
  }

  async function submitFilteredComment(e) {
    e.preventDefault();
    const warning = document.getElementById('linkWarning');
    warning.classList.add('hidden');

    const userId = document.getElementById('userIdInput').value.trim();
    const text = document.getElementById('commentText').value.trim();

    // Layer 2 Client-side Regex Check
    const linkPattern = /(https?:\/\/|www\.|[a-zA-Z0-9-]+\.(com|net|org|xyz|io|co|me|info|biz|tk)|t\.me\/|telegram\.me\/)/i;
    if (linkPattern.test(text)) {
      warning.innerHTML = "&#9888; <strong>Layer 2 Filter Triggered:</strong> External links or URLs are not allowed in comments.";
      warning.classList.remove('hidden');
      return;
    }

    const formData = new FormData();
    formData.append('provider', selectedProvider);
    formData.append('user_id', userId);
    formData.append('comment_text', text);

    const fileInput = document.getElementById('imageUploadInput');
    if (fileInput && fileInput.files[0]) {
      formData.append('comment_image', fileInput.files[0]);
    }

    try {
      const response = await fetch('/40c3/post_comment.php', {
        method: 'POST',
        headers: {
          'Accept': 'application/json'
        },
        body: formData
      });

      if (!response.ok) {
        throw new Error(`Server returned HTTP ${response.status} (${response.statusText})`);
      }

      const result = await response.json();

      if (!result.success) {
        warning.innerHTML = `&#9888; <strong>Error:</strong> ${result.error}`;
        warning.classList.remove('hidden');
        return;
      }

      const c = result.comment;
      const commentList = document.getElementById('commentList');
      const newComment = document.createElement('div');
      newComment.className = "bg-slate-900/60 border border-slate-800 rounded-lg p-4 mb-3";

      let imgHTML = c.image ? `<div class="mt-3 max-w-sm rounded border border-slate-800 overflow-hidden"><img src="${c.image}" class="w-full h-auto"></div>` : '';

      newComment.innerHTML = `
        <div class="flex items-center gap-2 text-xs font-mono text-cyan-400 mb-1">
          <span class="bg-cyan-950 border border-cyan-800 px-2 py-0.5 rounded text-[10px]">${c.provider}</span>
          <span>${c.user_id}</span>
          <span class="text-slate-600">• Just now</span>
        </div>
        <p class="text-slate-300 text-sm">${c.comment}</p>
        ${imgHTML}
      `;

      commentList.prepend(newComment);
      document.getElementById('commentForm').reset();

    } catch (err) {
      warning.innerHTML = `&#9888; <strong>Backend Fetch Error:</strong> ${err.message}`;
      warning.classList.remove('hidden');
    }
  }
</script>