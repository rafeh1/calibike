<?php
declare(strict_types=1);

/* ---------------- layout ---------------- */

function layout_start(string $title, array $meta = []): void
{
    $u = current_user();
    $flash = flash_take();
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($title) ?></title>
<?php if (!empty($meta['description'])): ?>
  <meta name="description" content="<?= e($meta['description']) ?>">
  <meta property="og:description" content="<?= e($meta['description']) ?>">
<?php endif; ?>
  <meta property="og:title" content="<?= e($meta['og_title'] ?? $title) ?>">
  <meta property="og:site_name" content="<?= e(site_name()) ?>">
<?php if (!empty($meta['image'])): ?>
  <meta property="og:image" content="<?= e($meta['image']) ?>">
  <meta name="twitter:card" content="summary_large_image">
<?php endif; ?>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { theme: { extend: { colors: { brand: { dark: '#0a0d14', card: '#121722', border: '#1e2638', cyan: '#00f0ff' } } } } }
  </script>
  <style>
    body { background-color: #0a0d14; color: #e2e8f0; }
    .glow-cyan { box-shadow: 0 0 25px rgba(0, 240, 255, 0.12); }
    .field { width: 100%; background: #0a0d14; border: 1px solid #1e2638; border-radius: .5rem; padding: .6rem .8rem; color: #e2e8f0; }
    .field:focus { outline: none; border-color: #22d3ee; }
    .btn { display: inline-flex; align-items: center; justify-content: center; gap: .5rem; padding: .6rem 1.1rem; border-radius: .5rem; font-size: .875rem; font-weight: 600; transition: background-color .15s; }
  </style>
</head>
<body class="min-h-screen flex flex-col">
  <header class="border-b border-brand-border bg-brand-dark/90 backdrop-blur sticky top-0 z-50">
    <div class="max-w-5xl mx-auto px-4 h-14 flex items-center justify-between gap-4">
      <a href="/" class="flex items-center gap-2 shrink-0">
        <div class="w-7 h-7 rounded bg-gradient-to-tr from-cyan-500 to-blue-600 flex items-center justify-center font-bold text-black text-sm">i</div>
        <span class="font-mono font-bold tracking-wider"><?= e(site_brand()[0]) ?><span class="text-cyan-400"><?= e(site_brand()[1]) ?></span></span>
      </a>
      <nav class="flex items-center gap-4 text-sm font-mono">
<?php if ($u): ?>
        <a href="/<?= e($u['username']) ?>/" class="text-slate-300 hover:text-cyan-400"><?= e($u['username']) ?></a>
        <a href="/dashboard/" class="text-slate-400 hover:text-cyan-400">Dashboard</a>
        <form method="post" action="/logout/" class="m-0"><?= csrf_field() ?><button class="text-slate-500 hover:text-cyan-400">Log out</button></form>
<?php else: ?>
        <a href="/login/" class="text-slate-400 hover:text-cyan-400">Log in</a>
        <a href="/signup/" class="text-cyan-400 hover:text-cyan-300">Sign up</a>
<?php endif; ?>
      </nav>
    </div>
  </header>
<?php if ($flash): ?>
  <div class="max-w-5xl mx-auto w-full px-4 pt-4">
    <div class="p-3 rounded-lg bg-cyan-950/40 border border-cyan-800/50 text-cyan-200 text-sm"><?= e($flash) ?></div>
  </div>
<?php endif; ?>
  <div class="flex-1">
<?php
}

function layout_end(): void
{
    ?>
  </div>
  <footer class="border-t border-brand-border mt-16 py-6 text-center text-xs text-slate-500 font-mono"><?= e(site_name()) ?></footer>
</body>
</html>
<?php
}

function not_found(): never
{
    http_response_code(404);
    layout_start('Not found · ' . site_name());
    echo '<main class="max-w-3xl mx-auto px-4 py-24 text-center">'
       . '<div class="text-6xl font-bold text-cyan-400 font-mono">404</div>'
       . '<p class="text-slate-400 mt-4">Nothing here.</p>'
       . '<a href="/" class="inline-block mt-6 text-cyan-400 hover:underline">← ' . e(site_name()) . '</a></main>';
    layout_end();
    exit;
}

/* ---------------- form helpers ---------------- */

/** $hint is trusted HTML (escape user data at the call site). */
function f_input(string $label, string $name, string $value, string $hint = '', string $type = 'text', string $attrs = ''): void
{
    ?>
  <label class="block">
    <span class="text-xs font-mono text-slate-400"><?= e($label) ?></span>
    <input type="<?= e($type) ?>" name="<?= e($name) ?>" value="<?= e($value) ?>" class="field mt-1" <?= $attrs ?>>
<?php if ($hint !== ''): ?>
    <span class="block text-xs text-slate-500 mt-1"><?= $hint ?></span>
<?php endif; ?>
  </label>
<?php
}

function f_textarea(string $label, string $name, string $value, int $rows = 4, string $hint = '', bool $mono = false): void
{
    ?>
  <label class="block">
    <span class="text-xs font-mono text-slate-400"><?= e($label) ?></span>
    <textarea name="<?= e($name) ?>" rows="<?= $rows ?>" class="field mt-1<?= $mono ? ' font-mono text-xs' : '' ?>" spellcheck="<?= $mono ? 'false' : 'true' ?>"><?= e($value) ?></textarea>
<?php if ($hint !== ''): ?>
    <span class="block text-xs text-slate-500 mt-1"><?= $hint ?></span>
<?php endif; ?>
  </label>
<?php
}

function f_errors(array $errors): void
{
    if (!$errors) {
        return;
    }
    echo '<div class="p-3 rounded-lg bg-red-950/40 border border-red-800/50 text-red-200 text-sm space-y-1">';
    foreach ($errors as $err) {
        echo '<div>' . e($err) . '</div>';
    }
    echo '</div>';
}

/* ---------------- shared pieces ---------------- */

function page_card(array $p, string $username, bool $byline = false): void
{
    $d = page_data($p);
    $vid = youtube_id((string)($d['main_video']['url'] ?? ''));
    $summary = page_summary($p);
    ?>
  <a href="/<?= e($username) ?>/<?= e($p['slug']) ?>/" class="group block bg-brand-card border border-brand-border rounded-xl overflow-hidden hover:border-cyan-700 transition">
<?php if ($vid): ?>
    <div class="aspect-video bg-black"><img src="https://i.ytimg.com/vi/<?= e($vid) ?>/hqdefault.jpg" alt="" loading="lazy" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition"></div>
<?php else: ?>
    <div class="aspect-video bg-gradient-to-br from-cyan-950 to-brand-dark flex items-center justify-center font-mono font-bold text-cyan-400 text-2xl px-4 text-center"><?= e(mb_substr((string)$p['title'], 0, 24)) ?></div>
<?php endif; ?>
    <div class="p-4">
      <div class="font-semibold text-white group-hover:text-cyan-400"><?= e($p['title']) ?></div>
<?php if ($summary !== ''): ?>
      <div class="text-sm text-slate-400 mt-1"><?= e($summary) ?></div>
<?php endif; ?>
<?php if ($byline): ?>
      <div class="text-xs font-mono text-slate-500 mt-2">/<?= e($username) ?>/</div>
<?php endif; ?>
<?php if (!$p['published']): ?>
      <div class="text-xs font-mono text-amber-400 mt-2">DRAFT</div>
<?php endif; ?>
    </div>
  </a>
<?php
}

function yt_embed(string $id, string $title, bool $hero): void
{
    $wrap = $hero ? 'rounded-2xl overflow-hidden border border-brand-border glow-cyan' : 'rounded-xl overflow-hidden border border-brand-border';
    ?>
      <div class="<?= $wrap ?>">
        <div class="relative w-full" style="padding-bottom: 56.25%;">
          <iframe class="absolute top-0 left-0 w-full h-full" src="https://www.youtube.com/embed/<?= e($id) ?>" title="<?= e($title) ?>" frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe>
        </div>
      </div>
<?php
}

/* ---------------- template page (the 40C3 look) ---------------- */

function render_template_page(array $owner, array $page, bool $isOwner): void
{
    $d       = page_data($page);
    $base    = '/' . $owner['username'] . '/';
    $mainId  = youtube_id((string)($d['main_video']['url'] ?? ''));
    $summary = page_summary($page);
    $name    = $owner['display_name'] !== '' ? $owner['display_name'] : $owner['username'];

    $videos = [];
    foreach ((array)($d['videos'] ?? []) as $v) {
        if (is_array($v) && ($id = youtube_id((string)($v['url'] ?? '')))) {
            $videos[] = ['id' => $id, 'title' => (string)($v['title'] ?? '')];
        }
    }
    $stats = array_values(array_filter((array)($d['stats'] ?? []), 'is_array'));
    $notes = array_values(array_filter((array)($d['notes'] ?? []), 'is_string'));
    $links = [];
    foreach ((array)($d['links'] ?? []) as $l) {
        if (is_array($l) && ($url = safe_url((string)($l['url'] ?? '')))) {
            $label = trim((string)($l['label'] ?? ''));
            $links[] = ['url' => $url, 'label' => $label !== '' ? $label : (string)(parse_url($url, PHP_URL_HOST) ?: $url)];
        }
    }

    layout_start($page['title'] . ($summary !== '' ? ' | ' . $summary : '') . ' | ' . site_name(), [
        'description' => $summary,
        'og_title'    => $page['title'],
        'image'       => $mainId ? "https://i.ytimg.com/vi/$mainId/hqdefault.jpg" : null,
    ]);
    ?>
  <main class="max-w-5xl mx-auto px-4 py-10">

    <div class="text-xs font-mono text-slate-500 mb-6">
      <a href="<?= e($base) ?>" class="hover:text-cyan-400"><?= e($owner['username']) ?></a> / <?= e($page['slug']) ?>
<?php if ($isOwner): ?>
      · <a href="/dashboard/edit/<?= (int)$page['id'] ?>/" class="text-cyan-400 hover:underline">Edit</a>
<?php if (!$page['published']): ?> · <span class="text-amber-400">DRAFT — only you can see this</span><?php endif; ?>
<?php endif; ?>
    </div>

    <div class="mb-8">
<?php if (!empty($d['kicker'])): ?>
      <div class="text-xs font-mono text-cyan-400 tracking-widest mb-2"><?= e($d['kicker']) ?></div>
<?php endif; ?>
      <h1 class="text-4xl md:text-5xl font-bold text-white leading-tight"><?= e($page['title']) ?></h1>
<?php if (!empty($d['subtitle'])): ?>
      <p class="text-xl text-slate-300 mt-2"><?= e($d['subtitle']) ?></p>
<?php endif; ?>
<?php if (!empty($d['tagline'])): ?>
      <p class="text-slate-400 mt-3 max-w-2xl"><?= e($d['tagline']) ?></p>
<?php endif; ?>
    </div>

<?php if ($mainId): ?>
    <div class="mb-6">
<?php if (!empty($d['main_video']['title'])): ?>
      <h2 class="text-sm font-mono text-cyan-400 mb-2"><?= e($d['main_video']['title']) ?></h2>
<?php endif; ?>
<?php yt_embed($mainId, (string)($d['main_video']['title'] ?? $page['title']), true); ?>
    </div>
<?php endif; ?>

<?php if ($videos): ?>
    <div class="grid md:grid-cols-2 gap-6 mb-12">
<?php foreach ($videos as $v): ?>
      <div>
<?php if ($v['title'] !== ''): ?>
        <h2 class="text-sm font-mono text-slate-400 mb-2"><?= e($v['title']) ?></h2>
<?php endif; ?>
<?php yt_embed($v['id'], $v['title'] ?: $page['title'], false); ?>
      </div>
<?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if ($stats): ?>
    <div class="grid grid-cols-2 md:grid-cols-<?= min(4, max(2, count($stats))) ?> gap-4 mb-12">
<?php foreach ($stats as $s): ?>
      <div class="bg-brand-card border border-brand-border rounded-xl p-5 text-center">
        <div class="text-3xl font-bold text-cyan-400"><?= e($s['value'] ?? '') ?></div>
        <div class="text-xs text-slate-400 mt-1 uppercase tracking-wider"><?= e($s['label'] ?? '') ?></div>
      </div>
<?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if ($notes || !empty($d['callout'])): ?>
    <div class="bg-brand-card border border-brand-border rounded-2xl p-6 md:p-8 mb-10">
<?php if (!empty($d['notes_title'])): ?>
      <h2 class="text-xl font-bold text-white mb-4"><?= e($d['notes_title']) ?></h2>
<?php endif; ?>
<?php foreach ($notes as $para): ?>
      <p class="text-slate-300 leading-relaxed mb-4"><?= inline_md($para) ?></p>
<?php endforeach; ?>
<?php if (!empty($d['callout'])): ?>
      <div class="mt-6 p-4 bg-cyan-950/40 border border-cyan-800/50 rounded-xl text-sm text-cyan-200"><?= inline_md((string)$d['callout']) ?></div>
<?php endif; ?>
    </div>
<?php endif; ?>

    <div class="flex flex-wrap gap-4">
<?php foreach ($links as $l): $external = !str_starts_with($l['url'], '/'); ?>
      <a href="<?= e($l['url']) ?>"<?= $external ? ' target="_blank" rel="noopener"' : '' ?>
         class="inline-flex items-center gap-2 px-5 py-2.5 border rounded-lg text-sm font-medium transition <?= link_style($l['url']) ?>"><?= e($l['label']) ?></a>
<?php endforeach; ?>
      <a href="<?= e($base) ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-800 border border-slate-600 text-slate-300 rounded-lg text-sm font-medium hover:bg-slate-700 transition">← <?= e($name) ?></a>
    </div>

  </main>
<?php
    layout_end();
}

/* ---------------- raw HTML page shell ---------------- */

function render_raw_shell(array $owner, array $page, bool $isOwner): void
{
    $src = raw_url($page);
    $summary = page_summary($page);
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($page['title']) ?> | <?= e(site_name()) ?></title>
<?php if ($summary !== ''): ?>
  <meta name="description" content="<?= e($summary) ?>">
  <meta property="og:description" content="<?= e($summary) ?>">
<?php endif; ?>
  <meta property="og:title" content="<?= e($page['title']) ?>">
  <style>
    html, body { margin: 0; height: 100%; background: #0a0d14; color: #94a3b8; font: 12px ui-monospace, SFMono-Regular, Menlo, monospace; }
    .bar { height: 32px; display: flex; align-items: center; gap: 8px; padding: 0 16px; border-bottom: 1px solid #1e2638; white-space: nowrap; overflow: hidden; }
    .bar a { color: #94a3b8; text-decoration: none; } .bar a:hover { color: #22d3ee; } .bar b { color: #e2e8f0; } .sp { flex: 1; }
    iframe { display: block; border: 0; width: 100%; height: calc(100% - 33px); }
  </style>
</head>
<body>
  <div class="bar">
    <a href="/"><b><?= e(site_brand()[0]) ?></b><?= e(site_brand()[1]) ?></a><span>/</span><a href="/<?= e($owner['username']) ?>/"><?= e($owner['username']) ?></a><span>/</span><span><?= e($page['slug']) ?></span>
    <span class="sp"></span>
<?php if ($isOwner): ?>
<?php if (!$page['published']): ?><span style="color:#fbbf24">DRAFT</span><?php endif; ?>
    <a href="/dashboard/edit/<?= (int)$page['id'] ?>/" style="color:#22d3ee">Edit</a>
<?php endif; ?>
  </div>
<?php if ($src): ?>
  <iframe src="<?= e($src) ?>" title="<?= e($page['title']) ?>"
    sandbox="allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox allow-forms allow-presentation allow-top-navigation-by-user-activation"
    allow="autoplay; encrypted-media; fullscreen; picture-in-picture; clipboard-write" allowfullscreen></iframe>
<?php else: ?>
  <iframe srcdoc="<?= e(with_base((string)$page['html'], page_url($owner['username'], $page['slug']))) ?>" title="<?= e($page['title']) ?>"
    sandbox="allow-scripts allow-popups allow-popups-to-escape-sandbox allow-forms allow-top-navigation-by-user-activation"></iframe>
<?php endif; ?>
</body>
</html>
<?php
}
