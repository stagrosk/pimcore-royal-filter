# Ploi nginx templates

Source-of-truth nginx configs for both sites that share the staging server.
Copy each file into the path commented at the top of that file (Ploi UI or `sudo nano …`).

```
.ploi/nginx/
├── prod/
│   ├── pimcore.infivea.com                       → /etc/nginx/sites-available/pimcore.infivea.com
│   ├── ssl-redirect.conf                          → /etc/nginx/ssl/pimcore.infivea.com
│   └── disable-basic-auth-well-known.conf         → /etc/nginx/ploi/pimcore.infivea.com/server/disable-basic-auth-well-known.conf
└── staging/
    ├── pim-staging.infivea.com.http               → /etc/nginx/sites-available/pim-staging.infivea.com   (BEFORE LE)
    ├── pim-staging.infivea.com.ssl                → /etc/nginx/sites-available/pim-staging.infivea.com   (AFTER LE swap)
    ├── ssl-redirect.conf                          → /etc/nginx/ssl/pim-staging.infivea.com               (after LE only)
    └── disable-basic-auth-well-known.conf         → /etc/nginx/ploi/pim-staging.infivea.com/server/disable-basic-auth-well-known.conf
```

## Prod (pimcore.infivea.com) — legacy Pimcore 11

Stays on the old stack until the OpenDXP cut-over. Uses:
- `php-pimcore10` upstream → `/run/php/php8.2-fpm.sock`
- Pimcore-style query params (`pimcore_editmode`, `pimcore_preview`, `pimcore_version`)
- Tailscale tunnel on `:8081` for admin (`/admin` blocked on the public vhost via `return 403;`)
- LE cert at `/etc/letsencrypt/live/pimcore.infivea.com/`

After fixing the broken vhost (missing `listen 443 ssl http2;` + `ssl_certificate`):

```bash
sudo cp .ploi/nginx/prod/pimcore.infivea.com /etc/nginx/sites-available/pimcore.infivea.com
sudo cp .ploi/nginx/prod/ssl-redirect.conf /etc/nginx/ssl/pimcore.infivea.com
sudo mkdir -p /etc/nginx/ploi/pimcore.infivea.com/server
sudo cp .ploi/nginx/prod/disable-basic-auth-well-known.conf \
        /etc/nginx/ploi/pimcore.infivea.com/server/disable-basic-auth-well-known.conf
sudo nginx -t && sudo systemctl reload nginx
```

## Staging (pim-staging.infivea.com) — OpenDXP

Two flavors. Use `.http` first (BEFORE LE issues a cert) so port 80 listens and the
ACME HTTP-01 challenge can succeed.

### Phase 1 — bring it up on HTTP

```bash
sudo cp .ploi/nginx/staging/pim-staging.infivea.com.http \
        /etc/nginx/sites-available/pim-staging.infivea.com

# The basic-auth bypass file is harmless even before SSL, install it now:
sudo mkdir -p /etc/nginx/ploi/pim-staging.infivea.com/server
sudo cp .ploi/nginx/staging/disable-basic-auth-well-known.conf \
        /etc/nginx/ploi/pim-staging.infivea.com/server/disable-basic-auth-well-known.conf

sudo nginx -t && sudo systemctl reload nginx

# Test
curl -I http://pim-staging.infivea.com/
```

### Phase 2 — issue the cert

In Ploi UI → **Site → SSL → Let's Encrypt** (or `sudo certbot certonly --webroot -w /home/ploi/pim-staging.infivea.com/public -d pim-staging.infivea.com`).

Cloudflare must NOT be in orange-cloud (proxied) mode while ACME runs. Switch the
DNS record to grey-cloud (DNS only) for the duration of the challenge.

### Phase 3 — switch to SSL vhost

```bash
sudo cp .ploi/nginx/staging/pim-staging.infivea.com.ssl \
        /etc/nginx/sites-available/pim-staging.infivea.com
sudo cp .ploi/nginx/staging/ssl-redirect.conf \
        /etc/nginx/ssl/pim-staging.infivea.com

sudo nginx -t && sudo systemctl reload nginx

# Test
curl -I https://pim-staging.infivea.com/
```

## Why the prod vhost broke yesterday

The vhost you posted is missing `listen 443 ssl http2;` and the `ssl_certificate` /
`ssl_certificate_key` directives in the **main** server block (the one with
`server_name pimcore.infivea.com;`).

Likely sequence:
1. You re-saved the vhost in Ploi UI → Ploi rewrote the block but forgot the listen line
   (or you toggled SSL off and on, and Ploi didn't replay the listen+cert).
2. Installing `php8.3-fpm` itself doesn't touch nginx, but `apt` may have run a deferred
   `systemctl reload nginx` — at that moment nginx re-read the broken config and the
   site stopped responding on `:443`.
3. The certbot post-hook then ran `nginx -t`, which **failed**, so the LE renewal
   couldn't reload nginx for the staging cert either.

The fix is to put back `listen 443 ssl http2;` + the cert paths in the main vhost,
which is exactly what `.ploi/nginx/prod/pimcore.infivea.com` already contains.

## Why this layout

Ploi splits a single site's config across three locations:

| Path | Purpose |
|---|---|
| `/etc/nginx/sites-available/<site>` | main vhost (the file we edit most) |
| `/etc/nginx/ssl/<site>` | redirect server blocks injected by Ploi when SSL is enabled |
| `/etc/nginx/ploi/<site>/{before,server,after}/*` | per-site fragments — Ploi UI writes some (rate-limit rules, IP allowlists, etc.); we add `disable-basic-auth-well-known.conf` so cert renewals never get blocked by basic-auth |

Mirroring the layout in this repo keeps the configs reviewable and reproducible without
clicking through Ploi's UI.
