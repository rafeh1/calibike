<?php
// 10kt.com configuration — fill in from hPanel → Databases → MySQL Databases.
return [
    'db_host'     => 'localhost',
    'db_name'     => 'u123456789_10kt',
    'db_user'     => 'u123456789_10kt',
    'db_pass'     => 'CHANGE_ME',

    // Long random string. Generate with:  php -r "echo bin2hex(random_bytes(32));"
    'secret'      => 'CHANGE_ME_TO_A_LONG_RANDOM_STRING',

    // Domain shown in the header, titles and page addresses.
    'site_name'   => '10kt.com',

    // Main site origin (no trailing slash).
    'site_origin' => 'https://10kt.com',

    // Separate origin that serves raw-HTML pages, so user scripts can never
    // touch 10kt.com login cookies. Create subdomain "u" in hPanel pointing
    // at public_html/u. Set to '' to fall back to a locked-down srcdoc frame
    // (YouTube embeds inside raw pages may not play in that mode).
    'raw_origin'  => 'https://u.10kt.com',

    // Usernames nobody can register (existing top-level folders are blocked automatically too).
    'reserved'    => [
        'login', 'logout', 'signup', 'register', 'dashboard', 'admin', 'app', 'api',
        'assets', 'static', 'u', 'www', 'mail', 'ftp', 'help', 'about', 'terms',
        'privacy', 'settings', 'account', 'search', 'explore', 'new', 'edit', 'index',
        '40c3', 'calidroid1500', 'audio-tracker',
    ],
];
