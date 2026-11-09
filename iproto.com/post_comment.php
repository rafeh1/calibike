<?php
// post_comment.php - Optional API endpoint (Mod_Security safe via base64)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$data_dir = __DIR__ . '/data';
if (!is_dir($data_dir)) {
    @mkdir($data_dir, 0755, true);
}
$comments_file = $data_dir . '/comments.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');

    $provider = trim($_POST['provider'] ?? '');
    $user_id  = trim($_POST['user_id'] ?? '');

    if (!empty($_POST['comment_b64'])) {
        $comment_raw = trim(base64_decode($_POST['comment_b64']));
    } else {
        $comment_raw = trim($_POST['comment_text'] ?? '');
    }

    $allowed_providers = ['Google', 'LinkedIn', 'Facebook', 'GitHub', 'Custom'];
    if (empty($provider) || !in_array($provider, $allowed_providers, true) || empty($user_id)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Valid social identity required.']);
        exit;
    }

    if ($comment_raw === '') {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Comment body cannot be empty.']);
        exit;
    }

    $link_pattern = '/(https?:\/\/|www\.|[a-zA-Z0-9-]+\.(com|net|org|xyz|io|co|me|info|biz|tk)|t\.me\/|telegram\.me\/)/i';
    if (preg_match($link_pattern, $comment_raw)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'error' => 'External website links are not allowed.']);
        exit;
    }

    $comment_clean = htmlspecialchars($comment_raw, ENT_QUOTES, 'UTF-8');
    $comments_data = [];
    if (file_exists($comments_file)) {
        $decoded = json_decode(file_get_contents($comments_file), true);
        if (is_array($decoded)) {
            $comments_data = $decoded;
        }
    }

    $new_entry = [
        'id'         => uniqid('c_', true),
        'provider'   => $provider,
        'user_id'    => htmlspecialchars($user_id, ENT_QUOTES, 'UTF-8'),
        'comment'    => $comment_clean,
        'image'      => null,
        'created_at' => date('Y-m-d H:i:s'),
        'ip_hash'    => md5($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0')
    ];

    array_unshift($comments_data, $new_entry);
    file_put_contents($comments_file, json_encode($comments_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    echo json_encode(['success' => true, 'comment' => $new_entry]);
    exit;
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'ok'      => true,
    'message' => 'post_comment.php is ready.'
]);
