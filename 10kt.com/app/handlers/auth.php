<?php
declare(strict_types=1);

function auth_shell_start(string $heading): void
{
    ?>
  <main class="max-w-md mx-auto px-4 py-16">
    <div class="text-xs font-mono text-cyan-400 tracking-widest mb-2"><?= e(strtoupper(site_name())) ?></div>
    <h1 class="text-3xl font-bold text-white mb-6"><?= e($heading) ?></h1>
    <div class="bg-brand-card border border-brand-border rounded-2xl p-6 glow-cyan">
<?php
}

function auth_shell_end(): void
{
    echo "    </div>\n  </main>\n";
}

function page_login(): void
{
    if (current_user()) {
        redirect('/dashboard/');
    }
    $error = '';
    $login = '';
    if (is_post()) {
        csrf_check();
        $login = strtolower(trim(post('login')));
        if (too_many_attempts()) {
            $error = 'Too many attempts. Try again in 15 minutes.';
        } else {
            $st = db()->prepare('SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1');
            $st->execute([$login, $login]);
            $u = $st->fetch();
            if ($u && password_verify(post('password'), $u['password_hash'])) {
                if (password_needs_rehash($u['password_hash'], PASSWORD_DEFAULT)) {
                    db()->prepare('UPDATE users SET password_hash = ? WHERE id = ?')
                        ->execute([password_hash(post('password'), PASSWORD_DEFAULT), $u['id']]);
                }
                log_in((int)$u['id']);
                redirect(safe_next((string)($_GET['next'] ?? '')));
            }
            record_attempt();
            $error = 'Wrong username/email or password.';
        }
    }

    layout_start('Log in · ' . site_name());
    auth_shell_start('Log in');
    ?>
      <form method="post" class="space-y-4">
        <?= csrf_field() ?>
        <?php f_errors($error ? [$error] : []); ?>
        <?php f_input('Username or email', 'login', $login, '', 'text', 'required autofocus autocomplete="username"'); ?>
        <?php f_input('Password', 'password', '', '', 'password', 'required autocomplete="current-password"'); ?>
        <button class="btn w-full bg-cyan-500 text-black hover:bg-cyan-400">Log in</button>
        <p class="text-sm text-slate-400 text-center">No account? <a href="/signup/" class="text-cyan-400 hover:underline">Sign up</a></p>
      </form>
<?php
    auth_shell_end();
    layout_end();
}

function page_signup(): void
{
    if (current_user()) {
        redirect('/dashboard/');
    }
    $errors = [];
    $f = ['username' => '', 'email' => '', 'display_name' => ''];

    if (is_post()) {
        csrf_check();
        if (post('website') !== '') {          // honeypot: bots fill every field
            redirect('/');
        }
        $f['username']     = strtolower(trim(post('username')));
        $f['email']        = strtolower(trim(post('email')));
        $f['display_name'] = trim(mb_substr(post('display_name'), 0, 80));
        $pass              = post('password');

        if ($err = username_error($f['username'])) {
            $errors[] = $err;
        }
        if (!filter_var($f['email'], FILTER_VALIDATE_EMAIL) || strlen($f['email']) > 190) {
            $errors[] = 'Enter a valid email.';
        }
        if (strlen($pass) < 10) {
            $errors[] = 'Password must be at least 10 characters.';
        } elseif ($pass !== post('password2')) {
            $errors[] = 'Passwords don’t match.';
        }
        if (!$errors) {
            $st = db()->prepare('SELECT username, email FROM users WHERE username = ? OR email = ?');
            $st->execute([$f['username'], $f['email']]);
            foreach ($st->fetchAll() as $row) {
                if ($row['username'] === $f['username']) $errors[] = 'That username is taken.';
                if ($row['email'] === $f['email'])       $errors[] = 'That email already has an account.';
            }
        }
        if (!$errors) {
            db()->prepare('INSERT INTO users (username, email, password_hash, display_name) VALUES (?, ?, ?, ?)')
                ->execute([$f['username'], $f['email'], password_hash($pass, PASSWORD_DEFAULT), $f['display_name'] ?: $f['username']]);
            log_in((int)db()->lastInsertId());
            flash_set('Welcome! Your page is live at ' . site_name() . '/' . $f['username'] . '/');
            redirect('/dashboard/');
        }
    }

    layout_start('Sign up · ' . site_name());
    auth_shell_start('Create your page');
    ?>
      <form method="post" class="space-y-4">
        <?= csrf_field() ?>
        <?php f_errors($errors); ?>
        <?php f_input('Username', 'username', $f['username'], 'Your address: ' . e(site_name()) . '/<b class="text-slate-300">username</b>/', 'text', 'required maxlength="30" autocomplete="username" autocapitalize="none"'); ?>
        <?php f_input('Display name', 'display_name', $f['display_name'], '', 'text', 'maxlength="80"'); ?>
        <?php f_input('Email', 'email', $f['email'], '', 'email', 'required autocomplete="email"'); ?>
        <?php f_input('Password', 'password', '', 'At least 10 characters.', 'password', 'required autocomplete="new-password"'); ?>
        <?php f_input('Confirm password', 'password2', '', '', 'password', 'required autocomplete="new-password"'); ?>
        <div style="position:absolute;left:-9999px" aria-hidden="true"><input name="website" tabindex="-1" autocomplete="off"></div>
        <button class="btn w-full bg-cyan-500 text-black hover:bg-cyan-400">Create account</button>
        <p class="text-sm text-slate-400 text-center">Already have one? <a href="/login/" class="text-cyan-400 hover:underline">Log in</a></p>
      </form>
<?php
    auth_shell_end();
    layout_end();
}

function page_logout(): void
{
    if (is_post()) {
        csrf_check();
        $_SESSION = [];
        session_regenerate_id(true);
    }
    redirect('/');
}
