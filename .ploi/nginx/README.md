# Ploi nginx templates

Single self-contained vhost per site — no `include /etc/nginx/ssl/<site>;` and no
`include /etc/nginx/ploi/<site>/{before,server,after}/*;`. Everything lives in one
file: redirect blocks, ACME well-known overrides, Tailnet block (prod), main app.

```
.ploi/nginx/
├── prod/
│   └── pimcore.infivea.com                        → /etc/nginx/sites-available/pimcore.infivea.com
└── staging/
    ├── pim-staging.infivea.com.http               → /etc/nginx/sites-available/pim-staging.infivea.com   (BEFORE LE)
    └── pim-staging.infivea.com.ssl                → /etc/nginx/sites-available/pim-staging.infivea.com   (AFTER LE swap)
```

## Why no Ploi includes

Ploi UI sometimes generates `/etc/nginx/ssl/<site>` with full `server { ... }` blocks
and includes it from inside the main `server { }`. nginx then errors out:
- `"listen" directive is not allowed here` — when the include lands inside a server block
- `duplicate listen 0.0.0.0:443` — when Ploi includes the same SSL bundle twice

Inlining everything into one vhost file removes both failure modes. We also drop the
`/etc/nginx/ploi/<site>/{before,server,after}/*` includes — they were never used for
anything that this vhost needs.

## Apply on the server

Both sites share the staging server. Each site is one file copy:

### Prod (pimcore.infivea.com — legacy Pimcore 11)

```bash
sudo cp .ploi/nginx/prod/pimcore.infivea.com /etc/nginx/sites-available/pimcore.infivea.com
sudo nginx -t
sudo systemctl reload nginx
curl -I https://pimcore.infivea.com/ | head -3   # expect: HTTP/2 200 / 302 / 403
```

### Staging — Phase 1 (HTTP, before LE)

```bash
sudo cp .ploi/nginx/staging/pim-staging.infivea.com.http \
        /etc/nginx/sites-available/pim-staging.infivea.com
sudo nginx -t
sudo systemctl reload nginx
curl -I http://pim-staging.infivea.com/ | head -3
```

Now issue the cert (Ploi UI → SSL → Let's Encrypt, or `certbot certonly --webroot -w /home/ploi/pim-staging.infivea.com/public -d pim-staging.infivea.com`).

Cloudflare must be on **grey cloud (DNS only)** for the duration of the ACME challenge.

### Staging — Phase 2 (SSL, after LE)

```bash
sudo cp .ploi/nginx/staging/pim-staging.infivea.com.ssl \
        /etc/nginx/sites-available/pim-staging.infivea.com
sudo nginx -t
sudo systemctl reload nginx
curl -I https://pim-staging.infivea.com/ | head -3
```

## Heads-up on Ploi's SSL toggle

If you click **SSL → Enable** in the Ploi UI for either site, Ploi rewrites
`/etc/nginx/sites-available/<site>` AND drops a generated
`/etc/nginx/ssl/<site>` next to it. That generated vhost re-introduces the
include collision we just removed. The fix: re-paste the matching template
from this folder back into the Ploi UI's nginx config field, then save.

When the cert renewal runs (cron / Ploi automation), it only touches the
files under `/etc/letsencrypt/`. The vhost stays untouched, so the inlined
`ssl_certificate` paths keep working.

## What changed vs. yesterday's broken vhost

- Added `listen 443 ssl http2;` + `ssl_certificate{,_key}` to the main app server block
- Added a `:80 → 301 https://` redirect server (covers the apex AND every subdomain)
- Added a `:443 www.<host> → 301 non-www` redirect server
- Added an explicit `location /.well-known/acme-challenge/` block on `:80` so cert
  renewals never get blocked by basic auth or the catch-all redirect
- Removed every `include` that pulled in Ploi-generated fragments — those were the
  source of the parser errors you hit
