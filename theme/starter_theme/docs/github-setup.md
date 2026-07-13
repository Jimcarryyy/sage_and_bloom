# GitHub Backup & Restore Guide

This project is split across two custom code folders on a normal WordPress install:

| Path | What it is |
|------|------------|
| `wp-content/themes/starter_theme/` | WordPress theme (this folder) |
| `wp-content/plugins/sage-bloom-core/` | Custom booking + Stripe plugin |

Third-party plugins (**ACF**, **WooCommerce**, **Contact Form 7**) are **not** part of this repo. Install them from the WordPress plugin directory after restore.

---

## Recommended: one GitHub repo (monorepo)

Put **both** the theme and your custom plugin in a single repository so you can clone once and recover the full stack.

### Folder layout on GitHub

```
sage-and-bloom/
├── .gitignore
├── README.md
├── theme/
│   └── starter_theme/
└── plugins/
    └── sage-bloom-core/
```

---

## Step 1 — Create the repo folder

Pick **one** folder for the repo, e.g. Desktop or `C:/Projects`. Use the **same path** in every command below.

### Git Bash (recommended on Windows)

```bash
# Go to where you want the repo (example: Desktop)
cd ~/Desktop

mkdir -p sage-and-bloom/theme sage-and-bloom/plugins
cd sage-and-bloom

# Copy theme + custom plugin from XAMPP (use cp, not xcopy, in Git Bash)
cp -r /c/xampp/htdocs/wordpress/wp-content/themes/starter_theme theme/
cp -r /c/xampp/htdocs/wordpress/wp-content/plugins/sage-bloom-core plugins/

# Quick check — both folders should exist
ls theme/starter_theme
ls plugins/sage-bloom-core
```

> **Why `xcopy` failed:** In Git Bash, `xcopy` is a Windows CMD tool and often breaks on paths/flags. Use `cp -r` instead. Also make sure the **destination** matches the folder you `cd` into (you created `~/Desktop/sage-and-bloom`, not `C:/Projects/sage-and-bloom` unless you use that path).

### PowerShell (alternative)

```powershell
mkdir C:\Projects\sage-and-bloom\theme, C:\Projects\sage-and-bloom\plugins
cd C:\Projects\sage-and-bloom

xcopy "C:\xampp\htdocs\wordpress\wp-content\themes\starter_theme" "theme\starter_theme" /E /I /H
xcopy "C:\xampp\htdocs\wordpress\wp-content\plugins\sage-bloom-core" "plugins\sage-bloom-core" /E /I /H
```

---

## Step 2 — Add a root `.gitignore`

Inside `sage-and-bloom/`, create `.gitignore`:

```gitignore
.DS_Store
Thumbs.db
.idea/
.vscode/
node_modules/
vendor/
*.log
.env
.env.*
wp-config.php
```

**Git Bash:**

```bash
cat > .gitignore << 'EOF'
.DS_Store
Thumbs.db
.idea/
.vscode/
node_modules/
vendor/
*.log
.env
.env.*
wp-config.php
EOF
```

Copy the theme README to the repo root (optional but nice for GitHub):

```bash
cp theme/starter_theme/README.md ./README.md
```

---

## Step 3 — Initialize Git and commit

Run these **inside** `sage-and-bloom/` (not on Desktop itself):

```bash
git init
git add .
git status
git commit -m "Initial commit: Sage & Bloom theme and core plugin"
```

If `git status` shows you are inside a parent repo (e.g. Desktop is already a git repo), run `git init` only inside `sage-and-bloom/`, or remove the parent `.git` on Desktop if you created it by mistake.

---

## Step 4 — Create GitHub repo and push

1. Go to [github.com/new](https://github.com/new)
2. Name it e.g. `sage-and-bloom` (Public or Private)
3. **Do not** add README/license on GitHub if you already committed locally
4. Push:

```bash
git branch -M main
git remote add origin https://github.com/YOUR_USERNAME/sage-and-bloom.git
git push -u origin main
```

Replace `YOUR_USERNAME` with your GitHub username. Git may prompt for login (browser or personal access token).

---

## Restore on a new machine (after losing local files)

### 1. Install base stack

- [XAMPP](https://www.apachefriends.org/)
- [WordPress](https://wordpress.org/download/) into `htdocs/wordpress`
- Complete the WordPress installer

### 2. Clone your repo

```bash
cd ~/Desktop   # or wherever you keep projects
git clone https://github.com/YOUR_USERNAME/sage-and-bloom.git
cd sage-and-bloom
```

### 3. Copy code into WordPress (Git Bash)

```bash
cp -r theme/starter_theme /c/xampp/htdocs/wordpress/wp-content/themes/
cp -r plugins/sage-bloom-core /c/xampp/htdocs/wordpress/wp-content/plugins/
```

### 4. Install third-party plugins (wp-admin)

**Plugins → Add New** — install and activate:

- Advanced Custom Fields
- WooCommerce (run setup wizard)
- Contact Form 7 (optional)

### 5. Activate your code

**Plugins →** activate **Sage & Bloom Core**  
**Appearance → Themes →** activate **starter_theme**

### 6. Configure

- **Business Settings** — phone, email, hours
- **Settings → Reading** — static front page = Home
- **Sage & Bloom → Settings** — Stripe test keys (see [local-setup.md](./local-setup.md))
- **Settings → Permalinks** — click Save

### 7. Optional — restore database content

Code + demo seeders restore structure; they do **not** restore custom edits, orders, or bookings unless you export the database.

- **Tools → Export** (WordPress XML), or
- **All-in-One WP Migration** / **WP Migrate**

---

## What to commit vs. not commit

| Commit to GitHub | Do not commit |
|------------------|---------------|
| `starter_theme/` (theme) | `wp-config.php` |
| `sage-bloom-core/` (plugin) | Stripe secret keys |
| `docs/`, `README.md` | `wp-content/uploads/` |
| `.gitignore` | WordPress core |
| | ACF, WooCommerce (install from WP.org) |

---

## Keeping GitHub in sync after changes

After editing files in XAMPP, copy back into the repo, then commit:

```bash
cd ~/Desktop/sage-and-bloom

cp -r /c/xampp/htdocs/wordpress/wp-content/themes/starter_theme theme/
cp -r /c/xampp/htdocs/wordpress/wp-content/plugins/sage-bloom-core plugins/

git add .
git commit -m "Describe what you changed"
git push
```

---

## Alternative: two separate repos

| Repo | Contents |
|------|----------|
| `sage-and-bloom-theme` | `starter_theme` only |
| `sage-bloom-core` | plugin only |

A single monorepo is simpler for full restore.

---

## Related

- [local-setup.md](./local-setup.md) — XAMPP, Stripe, Mailpit
- [demo-walkthrough.md](./demo-walkthrough.md) — portfolio presentation script
- [architecture.md](./architecture.md) — system overview
