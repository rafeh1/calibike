<?php
// iproto.com/comment_section.php
// Expects $pageId to be defined in the calling file
$pageId = $pageId ?? 'main';
?>

<div class="comment-box-container" style="max-width: 600px; margin: 30px 0; font-family: sans-serif;">
    <h3>Discussion & Feedback</h3>
    
    <form action="/comments_api.php" method="POST" style="margin-bottom: 20px;">
        <input type="hidden" name="page_id" value="<?= htmlspecialchars($pageId) ?>">
        
        <div style="margin-bottom: 10px;">
            <input type="text" name="author" placeholder="Your Name / Handle" required 
                   style="width: 100%; padding: 8px; box-sizing: border-box;">
        </div>
        
        <div style="margin-bottom: 10px;">
            <textarea name="comment" rows="4" placeholder="Write a comment..." required 
                      style="width: 100%; padding: 8px; box-sizing: border-box;"></textarea>
        </div>
        
        <button type="submit" style="padding: 8px 16px; cursor: pointer;">Submit Comment</button>
    </form>

    <div id="comments-list">
        <?php
        $storageFile = __DIR__ . '/comment_data/' . preg_replace('/[^a-zA-Z0-9_\-]/', '', $pageId) . '.json';
        $comments = file_exists($storageFile) ? json_decode(file_get_contents($storageFile), true) : [];
        
        if (empty($comments)): ?>
            <p style="color: #666;">No comments yet.</p>
        <?php else:
            foreach ($comments as $c): ?>
                <div style="background: #f4f4f4; padding: 10px; margin-bottom: 8px; border-radius: 4px;">
                    <strong><?= $c['author'] ?></strong> 
                    <small style="color: #888;">• <?= $c['timestamp'] ?></small>
                    <p style="margin: 5px 0 0 0;"><?= nl2br($c['comment']) ?></p>
                </div>
            <?php endforeach;
        endif; ?>
    </div>
</div>