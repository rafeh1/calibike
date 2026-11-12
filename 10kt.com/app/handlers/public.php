<?php
declare(strict_types=1);

function page_home(): void
{
    $pages = db()->query(
        'SELECT p.*, u.username FROM pages p JOIN users u ON u.id = p.user_id
         WHERE p.published = 1 ORDER BY p.updated_at DESC LIMIT 12'
    )->fetchAll();
    $u = current_user();

    layout_start(site_name() . ' — prototype pages for people who build things', [
        'description' => 'Make an account, get ' . site_name() . '/you/, and publish a page per build: videos, specs, notes and links.',
    ]);
    ?>
  <main class="max-w-5xl mx-auto px-4 py-12">
    <div class="mb-12">
      <div class="text-xs font-mono text-cyan-400 tracking-widest mb-2"><?= e(strtoupper(site_name())) ?></div>
      <h1 class="text-4xl md:text-5xl font-bold text-white leading-tight">Prototype pages for people who build things.</h1>
      <p class="text-slate-400 mt-4 max-w-2xl">Get your own <span class="font-mono text-slate-300"><?= e(site_name()) ?>/you/</span> and publish a page per build — first-run videos, specs, notes and links.</p>
      <div class="flex flex-wrap gap-3 mt-6">
<?php if ($u): ?>
        <a href="/<?= e($u['username']) ?>/" class="btn bg-cyan-500 text-black hover:bg-cyan-400">Your page</a>
        <a href="/dashboard/new/" class="btn bg-slate-800 border border-slate-600 text-slate-200 hover:bg-slate-700">+ New page</a>
<?php else: ?>
        <a href="/signup/" class="btn bg-cyan-500 text-black hover:bg-cyan-400">Create your page</a>
        <a href="/login/" class="btn bg-slate-800 border border-slate-600 text-slate-200 hover:bg-slate-700">Log in</a>
<?php endif; ?>
      </div>
    </div>

<?php if ($pages): ?>
    <h2 class="text-sm font-mono text-slate-400 mb-4">RECENT BUILDS</h2>
    <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-6">
<?php foreach ($pages as $p) { page_card($p, $p['username'], true); } ?>
    </div>
<?php endif; ?>
  </main>
<?php
    layout_end();
}

function page_user_home(string $username): void
{
    $owner = find_user($username) ?? not_found();
    $me = current_user();
    $isOwner = $me && (int)$me['id'] === (int)$owner['id'];

    $st = db()->prepare('SELECT * FROM pages WHERE user_id = ? AND (published = 1 OR ? = 1) ORDER BY sort_order, created_at DESC');
    $st->execute([$owner['id'], $isOwner ? 1 : 0]);
    $pages = $st->fetchAll();
    $name = $owner['display_name'] !== '' ? $owner['display_name'] : $owner['username'];

    $firstVid = null;
    foreach ($pages as $p) {
        if ($p['published'] && ($firstVid = youtube_id((string)(page_data($p)['main_video']['url'] ?? '')))) break;
    }

    layout_start($name . ' · ' . site_name(), [
        'description' => (string)($owner['bio'] ?: $name . '’s builds on ' . site_name()),
        'image'       => $firstVid ? "https://i.ytimg.com/vi/$firstVid/hqdefault.jpg" : null,
    ]);
    ?>
  <main class="max-w-5xl mx-auto px-4 py-10">
    <div class="flex flex-wrap items-start justify-between gap-6 mb-10">
      <div class="flex items-center gap-4">
        <div class="w-16 h-16 rounded-xl bg-gradient-to-tr from-cyan-500 to-blue-600 flex items-center justify-center text-2xl font-bold text-black"><?= e(mb_strtoupper(mb_substr($name, 0, 1))) ?></div>
        <div>
          <h1 class="text-3xl font-bold text-white"><?= e($name) ?></h1>
          <div class="font-mono text-sm text-slate-500"><?= e(site_name()) ?>/<?= e($owner['username']) ?>/</div>
        </div>
      </div>
<?php if ($isOwner): ?>
      <div class="flex gap-3">
        <a href="/dashboard/profile/" class="btn bg-slate-800 border border-slate-600 text-slate-200 hover:bg-slate-700">Edit profile</a>
        <a href="/dashboard/new/" class="btn bg-cyan-500 text-black hover:bg-cyan-400">+ New page</a>
      </div>
<?php endif; ?>
    </div>
<?php if (!empty($owner['bio'])): ?>
    <p class="text-slate-300 max-w-2xl -mt-4 mb-10 leading-relaxed"><?= nl2br(e($owner['bio'])) ?></p>
<?php endif; ?>

<?php if ($pages): ?>
    <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-6">
<?php foreach ($pages as $p) { page_card($p, $owner['username']); } ?>
    </div>
<?php else: ?>
    <p class="text-slate-500"><?= $isOwner ? 'No pages yet — <a href="/dashboard/new/" class="text-cyan-400 hover:underline">create one</a>.' : 'No pages yet.' ?></p>
<?php endif; ?>
  </main>
<?php
    layout_end();
}

function page_user_page(string $username, string $slug): void
{
    $owner = find_user($username) ?? not_found();
    $st = db()->prepare('SELECT * FROM pages WHERE user_id = ? AND slug = ?');
    $st->execute([$owner['id'], $slug]);
    $page = $st->fetch() ?: not_found();

    $me = current_user();
    $isOwner = $me && (int)$me['id'] === (int)$owner['id'];
    if (!$page['published'] && !$isOwner) {
        not_found();
    }

    if ($page['mode'] === 'html') {
        render_raw_shell($owner, $page, $isOwner);
    } else {
        render_template_page($owner, $page, $isOwner);
    }
}
