<?php
declare(strict_types=1);

function owned_page(array $u, int $id): array
{
    $st = db()->prepare('SELECT * FROM pages WHERE id = ? AND user_id = ?');
    $st->execute([$id, $u['id']]);
    return $st->fetch() ?: not_found();
}

/* ---------- template data <-> form text ---------- */

function data_to_form(array $d): array
{
    $pairs = static function (mixed $rows, string $a, string $b): string {
        $out = [];
        foreach ((array)$rows as $r) {
            if (is_array($r)) {
                $out[] = trim(($r[$a] ?? '') . ' | ' . ($r[$b] ?? ''), ' |');
            }
        }
        return implode("\n", $out);
    };
    return [
        'kicker'           => (string)($d['kicker'] ?? ''),
        'subtitle'         => (string)($d['subtitle'] ?? ''),
        'tagline'          => (string)($d['tagline'] ?? ''),
        'main_video_title' => (string)($d['main_video']['title'] ?? ''),
        'main_video_url'   => (string)($d['main_video']['url'] ?? ''),
        'videos'           => $pairs($d['videos'] ?? [], 'title', 'url'),
        'stats'            => $pairs($d['stats'] ?? [], 'value', 'label'),
        'notes_title'      => (string)($d['notes_title'] ?? ''),
        'notes'            => implode("\n\n", array_filter((array)($d['notes'] ?? []), 'is_string')),
        'callout'          => (string)($d['callout'] ?? ''),
        'links'            => $pairs($d['links'] ?? [], 'label', 'url'),
    ];
}

function form_to_data(array $f): array
{
    // "left | right" per line. A single value is treated as the URL for videos/links.
    $rows = static function (string $text, string $a, string $b, bool $singleIsRight): array {
        $out = [];
        foreach (preg_split('/\R/', $text) ?: [] as $line) {
            $line = trim($line);
            if ($line === '') continue;
            $parts = array_map('trim', explode('|', $line, 2));
            if (count($parts) === 1) {
                $parts = $singleIsRight ? ['', $parts[0]] : [$parts[0], ''];
            }
            $out[] = [$a => $parts[0], $b => $parts[1]];
        }
        return $out;
    };
    $paras = array_values(array_filter(array_map('trim', preg_split('/\R\s*\R/', trim($f['notes'])) ?: []), 'strlen'));

    return [
        'kicker'      => trim($f['kicker']),
        'subtitle'    => trim($f['subtitle']),
        'tagline'     => trim($f['tagline']),
        'main_video'  => ['title' => trim($f['main_video_title']), 'url' => trim($f['main_video_url'])],
        'videos'      => $rows($f['videos'], 'title', 'url', true),
        'stats'       => $rows($f['stats'], 'value', 'label', false),
        'notes_title' => trim($f['notes_title']),
        'notes'       => $paras,
        'callout'     => trim($f['callout']),
        'links'       => $rows($f['links'], 'label', 'url', true),
    ];
}

/* ---------- pages ---------- */

function page_dashboard(): void
{
    $u = require_login();
    $st = db()->prepare('SELECT * FROM pages WHERE user_id = ? ORDER BY sort_order, created_at DESC');
    $st->execute([$u['id']]);
    $pages = $st->fetchAll();

    layout_start('Dashboard · ' . site_name());
    ?>
  <main class="max-w-5xl mx-auto px-4 py-10">
    <div class="flex flex-wrap items-end justify-between gap-4 mb-8">
      <div>
        <div class="text-xs font-mono text-cyan-400 tracking-widest mb-1">DASHBOARD</div>
        <h1 class="text-3xl font-bold text-white"><?= e($u['display_name'] ?: $u['username']) ?></h1>
        <a href="/<?= e($u['username']) ?>/" class="font-mono text-sm text-slate-400 hover:text-cyan-400"><?= e(site_name()) ?>/<?= e($u['username']) ?>/</a>
      </div>
      <div class="flex gap-3">
        <a href="/dashboard/profile/" class="btn bg-slate-800 border border-slate-600 text-slate-200 hover:bg-slate-700">Profile</a>
        <a href="/dashboard/new/" class="btn bg-cyan-500 text-black hover:bg-cyan-400">+ New page</a>
      </div>
    </div>

<?php if (!$pages): ?>
    <div class="bg-brand-card border border-dashed border-brand-border rounded-2xl p-10 text-center">
      <p class="text-slate-300">No pages yet.</p>
      <p class="text-slate-500 text-sm mt-1">Each build gets its own page at <?= e(site_name()) ?>/<?= e($u['username']) ?>/<span class="text-slate-300">name</span>/</p>
      <a href="/dashboard/new/" class="btn mt-5 bg-cyan-500 text-black hover:bg-cyan-400">Create your first page</a>
    </div>
<?php else: ?>
    <div class="bg-brand-card border border-brand-border rounded-2xl divide-y divide-slate-800">
<?php foreach ($pages as $p): $url = '/' . $u['username'] . '/' . $p['slug'] . '/'; ?>
      <div class="p-4 flex flex-wrap items-center justify-between gap-3">
        <div class="min-w-0">
          <div class="font-semibold text-white">
            <?= e($p['title']) ?>
<?php if (!$p['published']): ?><span class="ml-2 text-xs font-mono text-amber-400">DRAFT</span><?php endif; ?>
            <span class="ml-2 text-xs font-mono text-slate-500"><?= $p['mode'] === 'html' ? 'HTML' : 'TEMPLATE' ?></span>
          </div>
          <div class="text-xs font-mono text-slate-500 truncate"><?= e($url) ?></div>
        </div>
        <div class="flex gap-4 text-sm">
          <a href="<?= e($url) ?>" class="text-slate-300 hover:text-cyan-400">View</a>
          <a href="/dashboard/edit/<?= (int)$p['id'] ?>/" class="text-cyan-400 hover:underline">Edit</a>
          <a href="/dashboard/delete/<?= (int)$p['id'] ?>/" class="text-red-400 hover:underline">Delete</a>
        </div>
      </div>
<?php endforeach; ?>
    </div>
<?php endif; ?>
  </main>
<?php
    layout_end();
}

function page_edit(?int $id): void
{
    $u = require_login();
    $page = $id ? owned_page($u, $id) : null;

    $f = [
        'title'      => (string)($page['title'] ?? ''),
        'slug'       => (string)($page['slug'] ?? ''),
        'summary'    => (string)($page['summary'] ?? ''),
        'mode'       => (string)($page['mode'] ?? 'template'),
        'published'  => (int)($page['published'] ?? 1),
        'sort_order' => (string)($page['sort_order'] ?? '0'),
        'html'       => (string)($page['html'] ?? ''),
    ] + data_to_form($page ? page_data($page) : []);
    $errors = [];

    if (is_post()) {
        csrf_check();
        foreach (array_keys($f) as $k) {
            if (isset($_POST[$k]) && is_string($_POST[$k])) {
                $f[$k] = $_POST[$k];
            }
        }
        $f['published'] = isset($_POST['published']) ? 1 : 0;
        $f['mode']      = $f['mode'] === 'html' ? 'html' : 'template';
        $f['title']     = trim($f['title']);
        $f['summary']   = trim($f['summary']);
        $f['slug']      = strtolower(trim($f['slug']));
        if ($f['slug'] === '') {
            $f['slug'] = slugify($f['title']);
        }

        if ($f['title'] === '' || mb_strlen($f['title']) > 150) {
            $errors[] = 'Title is required (max 150 characters).';
        }
        if (!preg_match('/^[a-z0-9](?:[a-z0-9-]{0,58}[a-z0-9])?$/', $f['slug'])) {
            $errors[] = 'URL can only use lowercase letters, numbers and hyphens (max 60).';
        }
        if (mb_strlen($f['summary']) > 255) {
            $errors[] = 'Card summary is too long (max 255 characters).';
        }
        if ($f['mode'] === 'html' && trim($f['html']) === '') {
            $errors[] = 'Paste or load some HTML, or switch to Template.';
        }
        if (strlen($f['html']) > 2_000_000) {
            $errors[] = 'HTML is over 2 MB. Host large images/video elsewhere and link them.';
        }
        if (!$errors) {
            $st = db()->prepare('SELECT id FROM pages WHERE user_id = ? AND slug = ? AND id <> ?');
            $st->execute([$u['id'], $f['slug'], $page['id'] ?? 0]);
            if ($st->fetch()) {
                $errors[] = 'You already have a page at /' . $u['username'] . '/' . $f['slug'] . '/.';
            }
        }

        if (!$errors) {
            $json = json_encode(form_to_data($f), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $vals = [$f['title'], $f['slug'], $f['summary'], $f['mode'], $json, $f['html'], (int)$f['sort_order'], $f['published']];
            if ($page) {
                db()->prepare('UPDATE pages SET title = ?, slug = ?, summary = ?, mode = ?, data = ?, html = ?, sort_order = ?, published = ? WHERE id = ? AND user_id = ?')
                    ->execute([...$vals, $page['id'], $u['id']]);
            } else {
                db()->prepare('INSERT INTO pages (title, slug, summary, mode, data, html, sort_order, published, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)')
                    ->execute([...$vals, $u['id']]);
            }
            flash_set('Saved.');
            redirect('/' . $u['username'] . '/' . $f['slug'] . '/');
        }
    }

    $uname = e($u['username']);
    layout_start(($page ? 'Edit ' . $page['title'] : 'New page') . ' · ' . site_name());
    ?>
  <main class="max-w-3xl mx-auto px-4 py-10">
    <div class="text-xs font-mono text-cyan-400 tracking-widest mb-1"><?= $page ? 'EDIT PAGE' : 'NEW PAGE' ?></div>
    <h1 class="text-3xl font-bold text-white mb-6"><?= e($page['title'] ?? 'New page') ?></h1>

    <form method="post" class="space-y-6">
      <?= csrf_field() ?>
      <?php f_errors($errors); ?>

      <section class="bg-brand-card border border-brand-border rounded-2xl p-6 space-y-4">
        <?php f_input('Title', 'title', $f['title'], '', 'text', 'required maxlength="150"'); ?>
        <?php f_input('URL', 'slug', $f['slug'], e(site_name()) . '/' . $uname . '/<b class="text-slate-300">this-part</b>/ — leave blank to make one from the title', 'text', 'maxlength="60" autocapitalize="none"'); ?>
        <?php f_input('Card summary', 'summary', $f['summary'], 'Shown on your profile and in link previews (LinkedIn, texts). Defaults to the subtitle.', 'text', 'maxlength="255"'); ?>
        <div class="flex flex-wrap items-center gap-6 text-sm">
          <label class="flex items-center gap-2"><input type="checkbox" name="published" value="1" <?= $f['published'] ? 'checked' : '' ?>> Published</label>
          <label class="flex items-center gap-2">Order <input type="number" name="sort_order" value="<?= (int)$f['sort_order'] ?>" class="field" style="width:6rem"></label>
        </div>
        <div class="flex flex-wrap gap-2 text-sm font-mono">
          <label class="flex items-center gap-2 px-3 py-2 rounded-lg border border-brand-border cursor-pointer"><input type="radio" name="mode" value="template" <?= $f['mode'] !== 'html' ? 'checked' : '' ?>> Template</label>
          <label class="flex items-center gap-2 px-3 py-2 rounded-lg border border-brand-border cursor-pointer"><input type="radio" name="mode" value="html" <?= $f['mode'] === 'html' ? 'checked' : '' ?>> Raw HTML</label>
        </div>
      </section>

      <section id="tpl" class="bg-brand-card border border-brand-border rounded-2xl p-6 space-y-4">
        <h2 class="text-sm font-mono text-cyan-400">TEMPLATE</h2>
        <?php f_input('Kicker', 'kicker', $f['kicker'], 'Small label above the title, e.g. CALIBIKE · FIRST RUN'); ?>
        <?php f_input('Subtitle', 'subtitle', $f['subtitle'], 'e.g. 40 lb · 40 mph · 40 mi range'); ?>
        <?php f_input('Tagline', 'tagline', $f['tagline']); ?>
        <div class="grid md:grid-cols-2 gap-4">
          <?php f_input('Main video (YouTube URL)', 'main_video_url', $f['main_video_url']); ?>
          <?php f_input('Main video heading', 'main_video_title', $f['main_video_title']); ?>
        </div>
        <?php f_textarea('More videos', 'videos', $f['videos'], 3, 'One per line: <code>Heading | YouTube URL</code>', true); ?>
        <?php f_textarea('Stats', 'stats', $f['stats'], 4, 'One per line: <code>value | label</code> — e.g. <code>39 | mph first run</code>. Four fit one row.', true); ?>
        <?php f_input('Notes heading', 'notes_title', $f['notes_title']); ?>
        <?php f_textarea('Notes', 'notes', $f['notes'], 10, 'Blank line between paragraphs. <code>**bold**</code> for emphasis.'); ?>
        <?php f_textarea('Callout box', 'callout', $f['callout'], 3); ?>
        <?php f_textarea('Link buttons', 'links', $f['links'], 3, 'One per line: <code>Label | URL</code> — YouTube, Strava, LinkedIn and GitHub links get their own colors.', true); ?>
      </section>

      <section id="raw" class="bg-brand-card border border-brand-border rounded-2xl p-6 space-y-3">
        <div class="flex flex-wrap items-center justify-between gap-3">
          <h2 class="text-sm font-mono text-cyan-400">RAW HTML</h2>
          <label class="btn bg-slate-800 border border-slate-600 text-slate-200 hover:bg-slate-700 cursor-pointer text-xs">Load .html file<input type="file" id="htmlfile" accept=".html,.htm,text/html" class="hidden"></label>
        </div>
        <textarea name="html" rows="24" class="field font-mono text-xs" spellcheck="false"><?= e($f['html']) ?></textarea>
        <p class="text-xs text-slate-500">Runs in an isolated frame. Links open on <?= e(site_name()) ?>; relative paths resolve from this page’s URL.</p>
      </section>

      <div class="flex gap-3">
        <button class="btn bg-cyan-500 text-black hover:bg-cyan-400">Save</button>
        <a href="/dashboard/" class="btn bg-slate-800 border border-slate-600 text-slate-200 hover:bg-slate-700">Cancel</a>
      </div>
    </form>
  </main>
  <script>
    (function () {
      var tpl = document.getElementById('tpl'), raw = document.getElementById('raw');
      function sync() {
        var m = document.querySelector('input[name=mode]:checked').value;
        tpl.hidden = m !== 'template';
        raw.hidden = m !== 'html';
      }
      document.querySelectorAll('input[name=mode]').forEach(function (r) { r.addEventListener('change', sync); });
      sync();
      document.getElementById('htmlfile').addEventListener('change', function (ev) {
        var file = ev.target.files[0];
        if (!file) return;
        var rd = new FileReader();
        rd.onload = function () { document.querySelector('textarea[name=html]').value = rd.result; };
        rd.readAsText(file);
      });
    })();
  </script>
<?php
    layout_end();
}

function page_delete(int $id): void
{
    $u = require_login();
    $page = owned_page($u, $id);
    if (is_post()) {
        csrf_check();
        db()->prepare('DELETE FROM pages WHERE id = ? AND user_id = ?')->execute([$id, $u['id']]);
        flash_set('Deleted “' . $page['title'] . '”.');
        redirect('/dashboard/');
    }
    layout_start('Delete page · ' . site_name());
    ?>
  <main class="max-w-md mx-auto px-4 py-16">
    <div class="bg-brand-card border border-red-900/60 rounded-2xl p-6">
      <h1 class="text-xl font-bold text-white">Delete “<?= e($page['title']) ?>”?</h1>
      <p class="text-slate-400 text-sm mt-2">/<?= e($u['username']) ?>/<?= e($page['slug']) ?>/ will stop working. This can’t be undone.</p>
      <form method="post" class="flex gap-3 mt-6">
        <?= csrf_field() ?>
        <button class="btn bg-red-600 text-white hover:bg-red-500">Delete page</button>
        <a href="/dashboard/" class="btn bg-slate-800 border border-slate-600 text-slate-200 hover:bg-slate-700">Cancel</a>
      </form>
    </div>
  </main>
<?php
    layout_end();
}

function page_profile(): void
{
    $u = require_login();
    $errors = [];
    $f = ['display_name' => (string)$u['display_name'], 'bio' => (string)($u['bio'] ?? '')];

    if (is_post()) {
        csrf_check();
        $f['display_name'] = trim(mb_substr(post('display_name'), 0, 80));
        $f['bio']          = trim(mb_substr(post('bio'), 0, 2000));
        $newPass           = post('new_password');
        if ($newPass !== '') {
            if (!password_verify(post('current_password'), $u['password_hash'])) {
                $errors[] = 'Current password is wrong.';
            } elseif (strlen($newPass) < 10) {
                $errors[] = 'New password must be at least 10 characters.';
            }
        }
        if (!$errors) {
            db()->prepare('UPDATE users SET display_name = ?, bio = ? WHERE id = ?')
                ->execute([$f['display_name'] ?: $u['username'], $f['bio'], $u['id']]);
            if ($newPass !== '') {
                db()->prepare('UPDATE users SET password_hash = ? WHERE id = ?')
                    ->execute([password_hash($newPass, PASSWORD_DEFAULT), $u['id']]);
                session_regenerate_id(true);
            }
            flash_set('Profile saved.');
            redirect('/' . $u['username'] . '/');
        }
    }

    layout_start('Profile · ' . site_name());
    ?>
  <main class="max-w-xl mx-auto px-4 py-10">
    <div class="text-xs font-mono text-cyan-400 tracking-widest mb-1">PROFILE</div>
    <h1 class="text-3xl font-bold text-white mb-6"><?= e(site_name()) ?>/<?= e($u['username']) ?>/</h1>
    <form method="post" class="space-y-6">
      <?= csrf_field() ?>
      <?php f_errors($errors); ?>
      <section class="bg-brand-card border border-brand-border rounded-2xl p-6 space-y-4">
        <?php f_input('Display name', 'display_name', $f['display_name'], '', 'text', 'maxlength="80"'); ?>
        <?php f_textarea('Bio', 'bio', $f['bio'], 5, 'Shown at the top of your page.'); ?>
      </section>
      <section class="bg-brand-card border border-brand-border rounded-2xl p-6 space-y-4">
        <h2 class="text-sm font-mono text-cyan-400">CHANGE PASSWORD</h2>
        <?php f_input('Current password', 'current_password', '', '', 'password', 'autocomplete="current-password"'); ?>
        <?php f_input('New password', 'new_password', '', 'Leave blank to keep your current password.', 'password', 'autocomplete="new-password"'); ?>
      </section>
      <div class="flex gap-3">
        <button class="btn bg-cyan-500 text-black hover:bg-cyan-400">Save</button>
        <a href="/dashboard/" class="btn bg-slate-800 border border-slate-600 text-slate-200 hover:bg-slate-700">Cancel</a>
      </div>
    </form>
  </main>
<?php
    layout_end();
}
