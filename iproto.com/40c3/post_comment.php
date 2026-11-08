<?php
// Start session & set response headers
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json; charset=utf-8');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method Not Allowed']);
    exit;
}

// Ensure comments & upload storage directories exist
$data_dir = __DIR__ . '/data';
$upload_dir = __DIR__ . '/uploads';

if (!is_dir($data_dir)) {
    mkdir($data_dir, 0755, true);
}
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// ---------------------------------------------------------
// 1. INPUT SANITIZATION & LAYER 1 VALIDATION
// ---------------------------------------------------------
$provider    = filter_input(INPUT_POST, 'provider', FILTER_SANITIZE_SPECIAL_CHARS);
$user_id     = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_SPECIAL_CHARS);
$comment_raw = trim($_POST['comment_text'] ?? '');

// Validate Social ID presence (Layer 1)
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

// ---------------------------------------------------------
// 2. LAYER 2: REGEX SPAM & LINK FILTERING
// ---------------------------------------------------------
// Regex pattern matching http, https, www, domain names (.com, .xyz, etc), and Telegram links
$link_pattern = '/(https?:\/\/|www\.|[a-zA-Z0-9-]+\.(com|net|org|xyz|io|co|me|info|biz|tk)|t\.me\/|telegram\.me\/)/i';

if (preg_match($link_pattern, $comment_raw)) {
    http_response_code(422);
    echo json_encode([
        'success' => false, 
        'error' => 'Layer 2 Violation: External website links or domain handles are not allowed to prevent spam.'
    ]);
    exit;
}

// Clean HTML tags from comment text
$comment_clean = htmlspecialchars($comment_raw, ENT_QUOTES, 'UTF-8');

// ---------------------------------------------------------
// 3. SECURE IMAGE FILE UPLOAD (OPTIONAL)
// ---------------------------------------------------------
$image_path = null;

if (isset($_FILES['comment_image']) && $_FILES['comment_image']['error'] === UPLOAD_ERR_OK) {
    $file_tmp  = $_FILES['comment_image']['tmp_name'];
    $file_size = $_FILES['comment_image']['size'];

    // Limit image size to 5MB
    if ($file_size > 5 * 1024 * 1024) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Image size exceeds 5MB limit.']);
        exit;
    }

    // Server-side MIME validation (never trust $_FILES['type'])
    $finfo     = new finfo(FILEINFO_MIME_TYPE);
    $mime_type = $finfo->file($file_tmp);

    $allowed_mimes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp'
    ];

    if (!array_key_exists($mime_type, $allowed_mimes)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid file format. Only JPG, PNG, GIF, and WEBP allowed.']);
        exit;
    }

    // Generate secure random filename to prevent shell execution or file overwrites
    $file_ext   = $allowed_mimes[$mime_type];
    $new_name   = bin2hex(random_bytes(16)) . '.' . $file_ext;
    $target_file = $upload_dir . '/' . $new_name;

    if (move_uploaded_file($file_tmp, $target_file)) {
        $image_path = '/40c3/uploads/' . $new_name;
    }
}

// ---------------------------------------------------------
// 4. SAVE COMMENT TO JSON DATABASE
// ---------------------------------------------------------
$comments_file = $data_dir . '/comments.json';
$comments_data = file_exists($comments_file) ? json_decode(file_get_contents($comments_file), true) : [];

if (!is_array($comments_data)) {
    $comments_data = [];
}

$new_entry = [
    'id'         => uniqid('c_', true),
    'provider'   => $provider,
    'user_id'    => $user_id,
    'comment'    => $comment_clean,
    'image'      => $image_path,
    'created_at' => date('Y-m-d H:i:s'),
    'ip_hash'    => md5($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0') // Hashed IP for moderation/rate limiting
];

// Prepend to top of list
array_unshift($comments_data, $new_entry);

// Save back to JSON
file_put_contents($comments_file, json_encode($comments_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

// Return success JSON
echo json_encode([
    'success' => true,
    'comment' => $new_entry
]);
exit;