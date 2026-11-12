<?php
declare(strict_types=1);

require __DIR__ . '/app/lib.php';
require __DIR__ . '/app/render.php';
require __DIR__ . '/app/handlers/auth.php';
require __DIR__ . '/app/handlers/dashboard.php';
require __DIR__ . '/app/handlers/public.php';

$uriPath = (string)(parse_url((string)($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/');
$path    = trim($uriPath, '/');

// Canonical trailing slash: /rafeh/40c3 -> /rafeh/40c3/
if ($path !== '' && !str_ends_with($uriPath, '/')) {
    $qs = (string)($_SERVER['QUERY_STRING'] ?? '');
    redirect('/' . $path . '/' . ($qs !== '' ? '?' . $qs : ''), 301);
}

$p = $path === '' ? [] : explode('/', strtolower($path));
$n = count($p);

try {
    $first = $p[0] ?? '';
    $sub   = $p[1] ?? '';
    $id    = (isset($p[2]) && ctype_digit($p[2])) ? (int)$p[2] : 0;

    if ($n === 0)                                     page_home();
    elseif ($first === 'login' && $n === 1)           page_login();
    elseif ($first === 'signup' && $n === 1)          page_signup();
    elseif ($first === 'logout' && $n === 1)          page_logout();
    elseif ($first === 'dashboard') {
        if ($n === 1)                                 page_dashboard();
        elseif ($sub === 'new' && $n === 2)           page_edit(null);
        elseif ($sub === 'edit' && $id && $n === 3)   page_edit($id);
        elseif ($sub === 'delete' && $id && $n === 3) page_delete($id);
        elseif ($sub === 'profile' && $n === 2)       page_profile();
        else                                          not_found();
    }
    elseif ($n === 1)                                 page_user_home($first);
    elseif ($n === 2)                                 page_user_page($first, $sub);
    else                                              not_found();
} catch (PDOException $ex) {
    error_log('site db error: ' . $ex->getMessage());
    http_response_code(500);
    echo 'Database error — check app/config.php and that schema.sql has been imported.';
}
