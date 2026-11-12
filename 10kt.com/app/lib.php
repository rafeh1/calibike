<?php
declare(strict_types=1);

$GLOBALS['CFG'] = require __DIR__ . '/config.php';

function cfg(string $key): mixed
{
    return $GLOBALS['CFG'][$key] ?? null;
}

function site_name(): string
{
    return (string)(cfg('site_name') ?: parse_url((string)cfg('site_origin'), PHP_URL_HOST));
}

/** Splits "10kt.com" into ["10kt", ".com"] for the two-tone logo. */
function site_brand(): array
{
    $n = site_name();
    $dot = strpos($n, '.');
    return $dot === false ? [$n, ''] : [substr($n, 0, $dot), substr($n, $dot)];
}

ini_set('display_errors', '0');

if (!defined('NO_SESSION')) {
    $https = (($_SERVER['HTTPS'] ?? '') !== '' && $_SERVER['HTTPS'] !== 'off')
          || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    session_name('site_sid');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => $https,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

/* ---------------- basics ---------------- */

function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO(
            'mysql:host=' . cfg('db_host') . ';dbname=' . cfg('db_name') . ';charset=utf8mb4',
            (string)cfg('db_user'),
            (string)cfg('db_pass'),
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );
    }
    return $pdo;
}

function e(mixed $s): string
{
    return htmlspecialchars((string)($s ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $to, int $code = 302): never
{
    header('Location: ' . $to, true, $code);
    exit;
}

function is_post(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function post(string $key): string
{
    $v = $_POST[$key] ?? '';
    return is_string($v) ? $v : '';
}

/* ---------------- auth / session ---------------- */

function current_user(): ?array
{
    static $loaded = false, $user = null;
    if (!$loaded) {
        $loaded = true;
        $uid = $_SESSION['uid'] ?? null;
        if ($uid) {
            $st = db()->prepare('SELECT * FROM users WHERE id = ?');
            $st->execute([(int)$uid]);
            $user = $st->fetch() ?: null;
        }
    }
    return $user;
}

function require_login(): array
{
    $u = current_user();
    if (!$u) {
        redirect('/login/?next=' . rawurlencode((string)($_SERVER['REQUEST_URI'] ?? '/')));
    }
    return $u;
}

function log_in(int $uid): void
{
    session_regenerate_id(true);
    $_SESSION['uid'] = $uid;
}

function safe_next(string $next): string
{
    $ok = str_starts_with($next, '/') && !str_starts_with($next, '//') && !str_contains($next, '\\');
    return $ok ? $next : '/dashboard/';
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . csrf_token() . '">';
}

function csrf_check(): void
{
    if (!hash_equals((string)($_SESSION['csrf'] ?? ''), post('csrf'))) {
        http_response_code(400);
        exit('Your session expired. Go back, reload the page and try again.');
    }
}

function flash_set(string $msg): void
{
    $_SESSION['flash'] = $msg;
}

function flash_take(): ?string
{
    if (defined('NO_SESSION')) {
        return null;
    }
    $m = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $m;
}

function client_ip(): string
{
    return (string)($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
}

function too_many_attempts(): bool
{
    $st = db()->prepare('SELECT COUNT(*) FROM login_attempts WHERE ip = ? AND attempted_at > (NOW() - INTERVAL 15 MINUTE)');
    $st->execute([client_ip()]);
    return (int)$st->fetchColumn() >= 10;
}

function record_attempt(): void
{
    db()->prepare('INSERT INTO login_attempts (ip, attempted_at) VALUES (?, NOW())')->execute([client_ip()]);
    db()->exec('DELETE FROM login_attempts WHERE attempted_at < (NOW() - INTERVAL 1 DAY)');
}

/* ---------------- users & pages ---------------- */

function username_error(string $u): ?string
{
    if (!preg_match('/^[a-z0-9][a-z0-9-]{2,29}$/', $u)) {
        return 'Usernames are 3–30 characters: lowercase letters, numbers and hyphens.';
    }
    if (in_array($u, (array)cfg('reserved'), true) || file_exists(dirname(__DIR__) . '/' . $u)) {
        return 'That username is reserved.';
    }
    return null;
}

function find_user(string $username): ?array
{
    if (!preg_match('/^[a-z0-9-]{1,30}$/', $username)) {
        return null;
    }
    $st = db()->prepare('SELECT * FROM users WHERE username = ?');
    $st->execute([$username]);
    return $st->fetch() ?: null;
}

function slugify(string $s): string
{
    $s = strtolower(trim((string)preg_replace('/[^A-Za-z0-9]+/', '-', $s), '-'));
    return trim(substr($s, 0, 60), '-');
}

function page_data(array $page): array
{
    $d = json_decode((string)($page['data'] ?? ''), true);
    return is_array($d) ? $d : [];
}

function page_summary(array $page): string
{
    if (($page['summary'] ?? '') !== '') {
        return (string)$page['summary'];
    }
    return (string)(page_data($page)['subtitle'] ?? '');
}

/* ---------------- content helpers ---------------- */

function youtube_id(string $s): ?string
{
    $s = trim($s);
    if (preg_match('~(?:youtu\.be/|[?&]v=|/embed/|/shorts/|/live/)([A-Za-z0-9_-]{11})~', $s, $m)) {
        return $m[1];
    }
    return preg_match('~^[A-Za-z0-9_-]{11}$~', $s) ? $s : null;
}

function safe_url(string $u): ?string
{
    $u = trim($u);
    if ($u === '') {
        return null;
    }
    if (str_starts_with($u, '/') && !str_starts_with($u, '//')) {
        return $u;
    }
    return preg_match('~^https?://~i', $u) ? $u : null;
}

function link_style(string $url): string
{
    $h = strtolower((string)parse_url($url, PHP_URL_HOST));
    return match (true) {
        str_contains($h, 'youtube') || str_contains($h, 'youtu.be') => 'bg-red-950/60 border-red-700/50 text-red-300 hover:bg-red-900/40',
        str_contains($h, 'strava')   => 'bg-orange-950/60 border-orange-700/50 text-orange-300 hover:bg-orange-900/40',
        str_contains($h, 'linkedin') => 'bg-blue-950/60 border-blue-700/50 text-blue-300 hover:bg-blue-900/40',
        str_contains($h, 'github')   => 'bg-slate-800 border-slate-500 text-slate-100 hover:bg-slate-700',
        default                      => 'bg-cyan-950/50 border-cyan-800/50 text-cyan-300 hover:bg-cyan-900/40',
    };
}

/** Escapes text, then allows **bold** and line breaks. */
function inline_md(string $s): string
{
    $h = e($s);
    $h = (string)preg_replace('/\*\*(.+?)\*\*/s', '<strong class="text-white">$1</strong>', $h);
    return nl2br($h);
}

/** Inserts <base> so links open on the main site (top window) and relative paths resolve from the page URL. */
function with_base(string $html, string $href): string
{
    $tag = '<base href="' . e($href) . '" target="_top">';
    if (preg_match('/<head\b[^>]*>/i', $html)) {
        return (string)preg_replace('/<head\b[^>]*>/i', '$0' . $tag, $html, 1);
    }
    return $tag . $html;
}

function page_url(string $username, string $slug): string
{
    return rtrim((string)cfg('site_origin'), '/') . '/' . $username . '/' . $slug . '/';
}

function raw_token(array $page): string
{
    return hash_hmac('sha256', $page['id'] . '|' . $page['updated_at'], (string)cfg('secret'));
}

function raw_url(array $page): ?string
{
    $o = rtrim((string)cfg('raw_origin'), '/');
    return $o === '' ? null : $o . '/?id=' . (int)$page['id'] . '&t=' . raw_token($page);
}
