# 10kt.com — user pages

Every user gets `10kt.com/<username>/`, and each build is a subpage like `10kt.com/rafeh/40c3/`.
Pages are public; logging in lets you create and edit your own. Anyone can sign up.

Requires PHP 8.1+ and MySQL/MariaDB (both standard on Hostinger).

## URLs

| URL | What |
|---|---|
| `/` | Landing page + recent builds |
| `/signup/`, `/login/` | Accounts |
| `/dashboard/` | Your pages: new, edit, delete, profile |
| `/<user>/` | A user's profile and page cards |
| `/<user>/<slug>/` | One page (template or raw HTML) |
| `/40c3/` | 301 redirect to `/rafeh/40c3/` |

Any real folder you upload next to `index.php` is served as-is, and its name is blocked as a username.

## Deploy on Hostinger

1. **Database:** hPanel → Databases → MySQL Databases. Create a database and user, then open phpMyAdmin and import `public_html/app/schema.sql`.
2. **Config:** edit `public_html/app/config.php` with the DB name, user and password. Set `secret` to the output of `php -r "echo bin2hex(random_bytes(32));"`. `site_name` (10kt.com) drives the logo, titles and page addresses; change it there if the domain ever changes.
3. **Subdomain (for raw-HTML pages):** hPanel → Domains → Subdomains. Create `u` (u.10kt.com) with a custom folder of `public_html/u` and make sure SSL is active on it. If you skip this, set `raw_origin` to `''`; template pages are unaffected.
4. **PHP version:** hPanel → Advanced → PHP Configuration, choose 8.1 or newer.
5. **Upload:** copy everything in `public_html/` into your site's `public_html/`, for example:
   `scp -P 65002 -r public_html/* public_html/.htaccess <user>@<host>:~/domains/10kt.com/public_html/`
6. **Default page:** if Hostinger put a default `index.html` in public_html, delete it so `index.php` serves `/`.
7. **Your account:** go to `10kt.com/signup/` and register as `rafeh`.
8. **Migrate 40C3:** in phpMyAdmin, run `public_html/app/seed_40c3.sql`. `10kt.com/rafeh/40c3/` is now live and editable from your dashboard.

## Moving iproto.com over (optional)

To send old iproto.com links to the new site, replace the `.htaccess` in iproto.com's public_html with:

```
RewriteEngine On
RewriteRule ^(.*)$ https://10kt.com/$1 [R=301,L]
```

`iproto.com/40c3/` then lands on `10kt.com/40c3/`, which redirects to `/rafeh/40c3/`. Move `calidroid1500/` and `audio-tracker/` into 10kt.com's public_html first, or they will 404 after the redirect.

## Page modes

- **Template:** form fields (kicker, subtitle, videos, stats, notes, callout, link buttons) rendered in the 40C3 dark style. Use `**bold**` in notes.
- **Raw HTML:** paste or load a full `.html` file. It is served from `u.10kt.com` inside a sandboxed frame. Because other people can sign up, their scripts must never run on the same origin as everyone's login cookie. `<base>` is injected so links like `/` open on 10kt.com in the top window.

## Security notes

- Passwords are hashed with `password_hash`. Sessions are HttpOnly and SameSite=Lax, and every form uses a CSRF token.
- Logins are limited to 10 attempts per IP per 15 minutes. Signup has a honeypot field.
- `app/` is blocked from the web.

## Not built yet

- Password reset by email
- Image uploads (link to hosted images for now)
- Admin moderation for open signup
