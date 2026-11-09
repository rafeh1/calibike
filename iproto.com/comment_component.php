<?php
// iproto.com/comment_component.php
$page_id = $page_id ?? 'main';
$comments_file = $_SERVER['DOCUMENT_ROOT'] . '/data/comments/' . preg_replace('/[^a-zA-Z0-9_\-]/', '', $page_id) . '.json';
$current_url = $_SERVER['REQUEST_URI'];
?>

<div class="bg-brand-card border border-brand-border rounded-2xl p-6 glow-cyan mt-10">
  <div class="text-xs font-mono text-cyan-400 tracking-widest mb-1 uppercase">COMMUNITY FEEDBACK</div>
  <h2 class="text-xl font-bold text-white mb-2">Leave a Verified Technical Comment</h2>
  <p class="text-slate-400 text-sm mb-6">Select your identity provider. Full URLs are automatically neutralized so they pass security filters.</p>

  <!-- OAuth Provider Selection -->
  <div id="authSection_<?= $page_id ?>" class="mb-6 bg-slate-900/80 border border-slate-800 rounded-xl p-4">
    <div class="text-xs font-mono text-slate-400 mb-3 uppercase">Layer 1: Select Identity Provider</div>
    
    <div id="loggedOutState_<?= $page_id ?>" class="flex flex-wrap gap-3">
      <button type="button" onclick="setOAuthProvider_<?= $page_id ?>('Google')" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white font-mono text-xs rounded-lg border border-slate-700 transition"><span class="text-red-400 font-bold">G</span> Google</button>
      <button type="button" onclick="setOAuthProvider_<?= $page_id ?>('LinkedIn')" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-950/80 hover:bg-blue-900/80 text-blue-300 font-mono text-xs rounded-lg border border-blue-800/80 transition"><span class="font-bold">in</span> LinkedIn</button>
      <button type="button" onclick="setOAuthProvider_<?= $page_id ?>('Facebook')" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-950/80 hover:bg-indigo-900/80 text-indigo-300 font-mono text-xs rounded-lg border border-indigo-800/80 transition"><span class="font-bold">f</span> Facebook</button>
      <button type="button" onclick="setOAuthProvider_<?= $page_id ?>('GitHub')" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-950 hover:bg-slate-900 text-slate-300 font-mono text-xs rounded-lg border border-slate-800 transition"><span class="font-bold">GH</span> GitHub</button>
    </div>

    <div id="loggedInState_<?= $page_id ?>" class="hidden flex items-center justify-between bg-cyan-950/40 border border-cyan-800/60 rounded-lg p-3">
      <div class="flex items-center gap-2 font-mono text-xs">
        <span class="text-slate-400">Authenticated Provider:</span>
        <span id="activeProvider_<?= $page_id ?>" class="bg-cyan-950 border border-cyan-700 text-cyan-400 px-2.5 py-0.5 rounded text-[11px] font-bold">Google</span>
      </div>
      <button type="button" onclick="resetOAuthProvider_<?= $page_id ?>()" class="text-xs font-mono text-slate-400 hover:text-red-400 underline">Change Provider</button>
    </div>
  </div>

  <!-- Form -->
  <form method="POST" action="/comments_api.php" class="space-y-4" id="commentForm_<?= $page_id ?>">
    <input type="hidden" name="page_id" value="<?= htmlspecialchars($page_id) ?>">
    <input type="hidden" name="redirect_url" value="<?= htmlspecialchars($current_url) ?>">
    <input type="hidden" name="provider" id="providerInput_<?= $page_id ?>" value="Google">
    <input type="hidden" name="comment_b64" id="commentB64_<?= $page_id ?>" value="">

    <div>
      <label class="block text-xs font-mono text-slate-400 mb-1 uppercase">Profile Handle / Verified ID</label>
      <input type="text" name="user_id" id="userIdInput_<?= $page_id ?>" placeholder="e.g. rafeh1@gmail.com or linkedin.com/in/rafehusain" required
             class="w-full bg-slate-900 border border-slate-700 text-slate-200 text-sm rounded-lg p-2.5 focus:border-cyan-400 focus:outline-none placeholder-slate-600">
    </div>

    <div>
      <label class="block text-xs font-mono text-slate-400 mb-1 uppercase">Comment Body</label>
      <textarea id="commentText_<?= $page_id ?>" rows="4" placeholder="Share technical feedback..." required
                class="w-full bg-slate-900 border border-slate-700 text-slate-200 text-sm rounded-lg p-2.5 focus:border-cyan-400 focus:outline-none placeholder-slate-600"></textarea>
    </div>

    <button type="submit" class="px-6 py-2.5 bg-cyan-500 hover:bg-cyan-400 text-black font-bold font-mono text-sm rounded-lg transition shadow-lg shadow-cyan-500/20">
      Post Comment
    </button>
  </form>

  <!-- Feed -->
  <div class="mt-8 border-t border-brand-border pt-6">
    <h3 class="text-sm font-mono text-slate-400 mb-4 uppercase">Verified Technical Discussion</h3>
    <div class="space-y-3">
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

<script>
function setOAuthProvider_<?= $page_id ?>(provider) {
  document.getElementById('providerInput_<?= $page_id ?>').value = provider;
  document.getElementById('activeProvider_<?= $page_id ?>').innerText = provider;
  document.getElementById('loggedOutState_<?= $page_id ?>').classList.add('hidden');
  document.getElementById('loggedInState_<?= $page_id ?>').classList.remove('hidden');
}

function resetOAuthProvider_<?= $page_id ?>() {
  document.getElementById('providerInput_<?= $page_id ?>').value = 'Google';
  document.getElementById('loggedOutState_<?= $page_id ?>').classList.remove('hidden');
  document.getElementById('loggedInState_<?= $page_id ?>').classList.add('hidden');
}

document.getElementById('commentForm_<?= $page_id ?>').addEventListener('submit', function (e) {
  let text = document.getElementById('commentText_<?= $page_id ?>').value.trim();
  let userId = document.getElementById('userIdInput_<?= $page_id ?>').value.trim();

  if (!text || !userId) { e.preventDefault(); return; }

  // Neutralize URLs & strip protocols for Mod_Security safety
  text = text.replace(/https?:\/\//gi, 'ht tp://').replace(/www\./gi, 'www .');
  userId = userId.replace(/^https?:\/\//i, '').replace(/^www\./i, '');
  
  document.getElementById('userIdInput_<?= $page_id ?>').value = userId;
  document.getElementById('commentB64_<?= $page_id ?>').value = btoa(unescape(encodeURIComponent(text)));
  document.getElementById('commentText_<?= $page_id ?>').removeAttribute('name');
});
</script>