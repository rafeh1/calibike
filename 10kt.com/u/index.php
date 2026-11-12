<?php
// Serves raw-HTML pages from the separate u.10kt.com origin.
// Only answers on that host and only with a valid signed token from the main site.
declare(strict_types=1);
define('NO_SESSION', true);
require dirname(__DIR__) . '/app/lib.php';

$rawHost = strtolower((string)parse_url((string)cfg('raw_origin'), PHP_URL_HOST));
$host    = strtolower(explode(':', (string)($_SERVER['HTTP_HOST'] ?? ''))[0]);
if ($rawHost === '' || $host !== $rawHost) {
    http_response_code(404);
    exit('Not found');
}

$st = db()->prepare("SELECT p.*, u.username FROM pages p JOIN users u ON u.id = p.user_id WHERE p.id = ? AND p.mode = 'html'");
$st->execute([(int)($_GET['id'] ?? 0)]);
$page = $st->fetch();
if (!$page || !hash_equals(raw_token($page), (string)($_GET['t'] ?? ''))) {
    http_response_code(404);
    exit('Not found');
}

$site = rtrim((string)cfg('site_origin'), '/');
header('Content-Type: text/html; charset=UTF-8');
header('X-Robots-Tag: noindex');
header('Content-Security-Policy: frame-ancestors ' . $site);
header('Cache-Control: private, max-age=60');
echo with_base((string)$page['html'], page_url($page['username'], $page['slug']));
