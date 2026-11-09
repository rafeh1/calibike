<?php
// post_comment.php - Optional API endpoint (also Mod_Security safe via base64)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$data_dir = __DIR__ . '/data';
if (!is_dir($data_dir)) {
    @mkdir($data_dir, 0755, true);
}
$comments_file = $data_dir . '/comments.json';

// ============================================================================
// POST handler
// ============================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');

    $provider = trim($_POST['provider'] ?? '');
    $user_id  = trim($_POST['user_id'] ?? '');

    // Prefer base64 version if present (avoids Mod_Security)
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

    // Block external links
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

    $image_path = null;
    if (isset($_FILES['comment_image']) && $_FILES['comment_image']['error'] === UPLOAD_ERR_OK) {
        $img_dir = __DIR__ . '/data/uploads';
        if (!is_dir($img_dir)) {
            @mkdir($img_dir, 0755, true);
        }
        $ext = strtolower(pathinfo($_FILES['comment_image']['name'], PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($ext, $allowed_exts, true)) {
            $filename = uniqid('img_', true) . '.' . $ext;
            if (move_uploaded_file($_FILES['comment_image']['tmp_name'], $img_dir . '/' . $filename)) {
                $image_path = '/40c3/data/uploads/' . $filename;
            }
        }
    }

    $new_entry = [
        'id'         => uniqid('c_', true),
        'provider'   => $provider,
        'user_id'    => htmlspecialchars($user_id, ENT_QUOTES, 'UTF-8'),
        'comment'    => $comment_clean,
        'image'      => $image_path,
        'created_at' => date('Y-m-d H:i:s'),
        'ip_hash'    => md5($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0')
    ];

    array_unshift($comments_data, $new_entry);
    file_put_contents($comments_file, json_encode($comments_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    echo json_encode(['success' => true, 'comment' => $new_entry]);
    exit;
}

// ============================================================================
// GET – simple status
// ============================================================================
header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'ok'      => true,
    'message' => 'post_comment.php is ready. POST with provider, user_id and comment_b64 (recommended) or comment_text.'
]);