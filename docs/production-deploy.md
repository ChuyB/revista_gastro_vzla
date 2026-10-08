# Deploying the Revista GEN redesign to production

Scope of this document: everything committed between the baseline commit and
`HEAD`, plus the working-tree changes, promoted to the production OJS instance.

- Baseline (do **not** deploy): `1168800` — *OJS Baseline*
- Redesign commits: `a058216` … `d748d71` (7 commits)
- Companion SQL: [`production-db.sql`](./production-db.sql)

Deployment was verified on the Docker development stack (OJS 2.x / PHP 5.6 /
MySQL 5.7). Every value in the SQL was read back out of the running site rather
than hand-typed, and the script was applied against the live database to confirm
it is syntactically valid and a byte-level no-op when the state already matches.

---

## 1. What is being deployed

38 files, in four groups.

### 1.1 New theme plugin (14 files, all new)

```
ojs_code/plugins/themes/revista/RevistaThemePlugin.inc.php
ojs_code/plugins/themes/revista/index.php
ojs_code/plugins/themes/revista/revista.css
ojs_code/plugins/themes/revista/settings.xml
ojs_code/plugins/themes/revista/version.xml
ojs_code/plugins/themes/revista/css/tokens.css
ojs_code/plugins/themes/revista/css/base.css
ojs_code/plugins/themes/revista/css/layout.css
ojs_code/plugins/themes/revista/css/navbar.css
ojs_code/plugins/themes/revista/css/blocks.css
ojs_code/plugins/themes/revista/css/dropdown.css
ojs_code/plugins/themes/revista/css/publicidad.css
ojs_code/plugins/themes/revista/css/archive.css
ojs_code/plugins/themes/revista/js/navbar.js
```

Plugin id `revista`, type `plugins.themes`, release `1.0.0.0`, `enabled=true` by
default. It is activated purely through a journal setting (see §4), so there is no
plugin-install step and no schema migration.

`ojs_code/plugins/themes/classicBlue/` is **untouched** — it remains the rollback
target.

### 1.2 Block templates (19 files, modified in place)

```
ojs_code/plugins/blocks/authorBios/block.tpl
ojs_code/plugins/blocks/donation/block.tpl
ojs_code/plugins/blocks/fontSize/block.tpl
ojs_code/plugins/blocks/information/block.tpl
ojs_code/plugins/blocks/keywordCloud/block.tpl
ojs_code/plugins/blocks/languageToggle/block.tpl
ojs_code/plugins/blocks/navigation/block.tpl
ojs_code/plugins/blocks/notification/block.tpl
ojs_code/plugins/blocks/readingTools/block.tpl
ojs_code/plugins/blocks/relatedItems/block.tpl
ojs_code/plugins/blocks/role/author.tpl
ojs_code/plugins/blocks/role/copyeditor.tpl
ojs_code/plugins/blocks/role/editor.tpl
ojs_code/plugins/blocks/role/layoutEditor.tpl
ojs_code/plugins/blocks/role/proofreader.tpl
ojs_code/plugins/blocks/role/reviewer.tpl
ojs_code/plugins/blocks/role/sectionEditor.tpl
ojs_code/plugins/blocks/subscription/block.tpl
ojs_code/plugins/blocks/user/block.tpl
```

All 19 share one mechanical change: `<span class="blockTitle">` became
`<h2 class="blockTitle">`, and the user/navigation blocks gained a
`.blockPanel` wrapper plus a `<button class="blockToggle" aria-expanded>` so
`js/navbar.js` can drive them as dropdowns. `css/base.css` restyles
`.blockTitle` accordingly.

This is the change most likely to surprise a reviewer, because it is a set of
core plugin files rather than a single template. It is required: `navbar.js`
looks up the panels by these hooks.

### 1.3 Core templates (4 files, modified in place)

```
ojs_code/templates/common/header.tpl     navbarBar + mobile menu bar
ojs_code/templates/common/footer.tpl     #pageFooter moved out of #main
ojs_code/templates/article/header.tpl    mirror of common/header.tpl
ojs_code/templates/issue/archive.tpl      per-year grid of issue cards
```

`common/footer.tpl` inlines the core footer closings so `#pageFooter` lands as a
direct child of `#body`.

### 1.4 Stylesheet (1 file)

```
ojs_code/styles/mobile.css
```

Heavily rewritten: the floating circular hamburger became a full-width "Menu" bar
pinned to the top when open, plus the responsive sidebar/block rules and the
narrow-screen form rules.

### 1.5 Deliberately NOT deployed

| File | Why |
| --- | --- |
| `ojs_code/config.inc.php` | Dev-environment only. See §3. |
| `.gitignore` | Repository housekeeping, not an OJS artifact. |

No other file contains `localhost:8080`, `127.0.0.1`, `/var/www/html` or any
container path — this was checked explicitly across every file in §1.

---

## 2. Before you start

```bash
# 1. Back up the database (at minimum the two tables this touches)
mysqldump -u ojs_user -p ojsg plugin_settings journal_settings > pre-revista.sql
mysqldump -u ojs_user -p ojsg > full-pre-revista.sql     # belt and braces

# 2. Back up the files that are about to be overwritten
cd /var/www/revistag
tar czf ~/revistag-templates-pre.tgz \
  templates/common/header.tpl templates/common/footer.tpl \
  templates/article/header.tpl templates/issue/archive.tpl \
  styles/mobile.css plugins/blocks
```

Confirm the production journal id and schema name. The SQL assumes journal `1`
and schema `ojsg`; both are on line 1 of `production-db.sql` if they differ.

```sql
SELECT id, path, title FROM journals;
```

---

## 3. `config.inc.php` — do not ship it

It differs from baseline in three places, all of them dev-environment fixes:

| Setting | Dev value | Production |
| --- | --- | --- |
| `base_url` | `http://localhost:8080` | keep as-is |
| `enable_cdn` | `Off` — the container had no outbound network, so jQuery never loaded | keep as-is |
| `font_location` | `/var/www/html/public/freefont/FreeSerif.ttf` | keep as-is |

The third one deserves a note. Baseline shipped
`/var/www/revistag/public/freefont/FreeSerif.ttf`, which is correct **for the
production host** and is why the CAPTCHA renders letters there. In Docker the
docroot is `/var/www/html`, so `imagefttext()` silently returned `false` and
`CaptchaManager::generateImage()` emitted only the background plus its 400 noise
dots — no glyphs. **No production action is needed**; just do not copy the dev
`config.inc.php` over the production one.

If production is ever run from a docroot other than `/var/www/revistag`, that
setting needs to match. It is the only functional difference, and the failure is
silent, so check it if the CAPTCHA ever renders blank again.

---

## 4. Database changes

All of them live in [`production-db.sql`](./production-db.sql). Everything is
`INSERT ... ON DUPLICATE KEY UPDATE` against the real primary keys
(`plugin_settings`: plugin_name, locale, journal_id, setting_name;
`journal_settings`: journal_id, locale, setting_name), so the script is safe to
re-run.

```bash
# Substitute the production schema name and user if they differ from the
# defaults below (the development values are shown for orientation only).
mysql -u ojs_user -p ojsg < docs/production-db.sql
```

Two reasons to prefer the script over clicking through the admin UI: it writes
only the rows listed below and never rewrites the other 22 settings on the Setup
step 5 form (§6), and it pins the block content to the `es_AR` locale key
explicitly rather than inheriting whatever language the admin account is browsing
in (§4.6).

A UI-only route exists for three of the four changes — see §4.6.

### 4.1 Activate the theme — required

```sql
INSERT INTO journal_settings (journal_id, locale, setting_name, setting_value, setting_type)
VALUES (1, '', 'journalTheme', 'revista', 'string')
ON DUPLICATE KEY UPDATE setting_value='revista';
```

Without this the plugin is present but never loads. Equivalent to selecting
*Website Settings → Setup, step 5 → Theme → Revista GEN*.

### 4.2 Block placement — required

`context`: `1` = left sidebar, `2` = right sidebar.

| Plugin | context | seq | enabled | Effect |
| --- | --- | --- | --- | --- |
| `userblockplugin` | 1 | 0 | 1 | moves into the navbar as a dropdown |
| `navigationblockplugin` | 1 | 1 | 1 | moves into the navbar as a dropdown |
| `informationblockplugin` | 2 | 1 | 1 | right sidebar |
| `publicidad` | 2 | 0 | 1 | **right sidebar, first** — chatbot, then sponsors |
| `publicidad left` | 2 | 2 | 1 | right sidebar, last — podcast / cápsula |
| `roleblockplugin` | 2 | 1 | 0 | disabled |
| `developedbyblockplugin` | 1 | 4 | 0 | disabled |

The left sidebar is deliberately empty; the two-column grid keeps
`#sidebarLeft` present but collapsed.

### 4.3 Custom block content — required

Two `blockContent` rows, stored as serialized PHP arrays keyed by `es_AR`:

- **`publicidad`** — chatbase iframe (so the chat is inline in the sidebar on
  desktop), the disclaimer note, a divider, then the sponsor logo list. This
  replaces the chatbot that used to sit in its own grey sub-panel.
- **`publicidad left`** — new block: podcast list plus the *Cápsula Académica*
  feature.

Both also fix a recursive `gtag()` call that had been mangled in the stored
content.

The stored value is a serialized PHP array, not plain HTML, and the editor's
TinyMCE configuration strips the `<script>` tags this content depends on (§4.6) —
so this one genuinely has to go through the SQL.

### 4.4 Register the second custom block — required, easy to miss

```sql
INSERT INTO plugin_settings (plugin_name, locale, journal_id, setting_name, setting_value, setting_type)
VALUES ('customblockmanagerplugin', '', 1, 'blocks',
        'a:2:{i:0;s:10:"PUBLICIDAD";i:1;s:15:"PUBLICIDAD left";}', 'object')
ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value);
```

**Pre-check this on production.** If the row already lists custom blocks that
must be kept, merge them into the array rather than replacing it:

```sql
SELECT setting_value FROM plugin_settings
WHERE plugin_name='customblockmanagerplugin' AND setting_name='blocks';
```

A custom block missing from this list does not render at all, even with
`enabled=1` and valid content — the symptom is a silently absent block.

### 4.5 Deliberately excluded from the SQL

`journalPageHeader`, `journalPageFooter`, `authorInformation`,
`homeHeaderTitleType`, `pageHeaderTitleType`, `journalThumbnailAltText`,
`displayCurrentIssue`, `itemsPerPage`, `numPageLinks`.

The first six were lost by accident during development (see §6) and restored.
Production should already hold its own correct values, so this script leaves them
untouched. Verify rather than write:

```sql
SELECT setting_name, setting_value FROM journal_settings
WHERE journal_id=1 AND setting_name IN
  ('journalPageHeader','journalPageFooter','authorInformation',
   'homeHeaderTitleType','pageHeaderTitleType','journalThumbnailAltText',
   'displayCurrentIssue','itemsPerPage','numPageLinks');
```

### 4.6 Applying these changes through the admin UI instead

If whoever deploys has no shell access to `mysql`, three of the four changes are
still reachable from the admin interface:

| Change | Where in the UI |
| --- | --- |
| §4.1 theme | Website Settings → Setup, step 5 → *Theme* dropdown |
| §4.2 all block `context`/`seq`/`enabled` rows | Same page → *Assign Blocks to Sidebars*, drag between Left / Unselected / Right |
| §4.4 register `PUBLICIDAD left` | Website Settings → Plugins → Custom Block Manager → *Add Block* |
| §4.3 block **content** | **Not possible** — see below |

The block placement is genuinely complete through the UI. That page's form calls
exactly the three methods the SQL writes (`JournalSetupStep5Form::execute()` in
`classes/manager/form/setup/JournalSetupStep5Form.inc.php`):

```php
$plugin->setEnabled(!in_array($plugin->getName(), $blockUnselected));
if (in_array($plugin->getName(), $blockSelectLeft)) {
    $plugin->setBlockContext(BLOCK_CONTEXT_LEFT_SIDEBAR);
    $plugin->setSeq(array_search($key, $blockSelectLeft));
}
```

`Add Block` also registers the new block name in `customblockmanagerplugin.blocks`
by itself, so §4.4 comes for free on that route.

### Two blockers on the UI route

**Block content cannot be saved through the editor.** `CustomBlockEditForm::addTinyMCE()`
initialises TinyMCE with:

```js
extended_valid_elements : "span[*], div[*]"
```

`script` is not in that list, and TinyMCE 3 strips `<script>` elements on save.
Both blocks need them — two `gtag()` analytics snippets and the Chatbase
`embed.min.js` + `embeddedChatbotConfig` that create the floating mobile chat
launcher. Pasting the content into the Custom Block Manager would save it
successfully and silently drop all three, leaving no analytics and no chat on
mobile. **This part must go through the SQL** (or the scripts must first be moved
out of the block content into a template, which has not been done).

**Block content is stored per locale.** `CustomBlockEditForm::getLocaleFieldNames()`
returns `blockContent`, and `Form::getDefaultFormLocale()` falls back to
`AppLocale::getLocale()` — the language the admin account is currently browsing
the site in. The edit form also renders a language selector carrying forward the
other locales' values. An admin working in anything other than `es_AR` writes the
content under that other locale key, and the Spanish front-end then shows an
empty block with no error. Set the form's language dropdown to `es_AR`.

If the UI route is used, §6 still applies in full: saving Setup step 5 rewrites
every setting on that form, so re-run the §4.5 verification query afterwards.

---

## 5. Clear the cache — do not skip

OJS caches plugin settings **on disk**, outside the directories people normally
clear. During development a `blockContent` change appeared to have no effect at
all: the page kept serving old markup, the old sidebar order, and did not render
a newly added block. The database was provably correct, but
`cache/fc-pluginSettings-*.php` was still being read.

Clearing `cache/t_compile/` alone does **not** fix this.

```bash
cd /var/www/revistag
find cache -maxdepth 1 -name 'fc-*' -delete
rm -rf cache/t_compile/* cache/_db/* cache/t_cache/* cache/t_config/*
```

Then reload PHP so compiled templates and any opcode cache are dropped:

```bash
apachectl -k graceful   # or systemctl reload apache2
```

Nothing under `cache/` is tracked in git, so this stays out of your diffs.

---

## 6. Deployment order

1. Back up (§2).
2. Transfer and replace the files — §6.1.
3. Run `docs/production-db.sql` (§4).
4. Clear the cache (§5) and reload PHP.
5. Work through §7.

Steps 3 and 4 must stay in that order. The plugin-settings cache in step 4 is
populated by page requests, so if anything is requested between the file swap and
the SQL, OJS re-caches the *old* `plugin_settings` values and the SQL appears to
have no effect — the same failure described in §5.

### Do not

- **Do not treat Setup step 5 as a page you can "just save".** Every save of that
  form rewrites **all 22** settings in its list from the submitted values. The
  handler is unconditional:

  ```php
  // classes/manager/form/setup/JournalSetupForm.inc.php
  foreach ($this->_data as $name => $value) {
      if (isset($this->settings[$name])) {
          $settingsDao->updateSetting($journal->getId(), $name, $value, ...);
      }
  }
  ```

  So an unchecked checkbox becomes `0` and an empty text field becomes empty. In
  development this reset `displayCurrentIssue`, `itemsPerPage` and `numPageLinks`
  to zero and blanked `journalPageHeader`, `journalPageFooter`,
  `authorInformation`, `homeHeaderTitleType`, `pageHeaderTitleType` and
  `journalThumbnailAltText`. Setting the theme from here is fine — just do not
  save it casually, and re-run the §4.5 verification query afterwards either way.
- **Do not use the per-image Delete buttons on that form.** They additionally call
  `deleteImage()` (`pages/manager/SetupHandler.inc.php:233-266`), which removes
  the setting outright rather than blanking it.
- Do not deploy `config.inc.php` (§3).
- Do not delete `classicBlue`; it is the rollback path.

### 6.1 Transfer and replace the files

`updates/` holds three directory packages plus a copy of the SQL. Each package is
a full dump of one directory, so the remote originals are **moved aside, not
overwritten in place** — that doubles as the file-level rollback.

| Package | Contains | Extracted to | Installed as |
| --- | --- | --- | --- |
| `blocks.tar.gz` | `plugins/blocks/` (534 real files) | `blocks/` | `plugins/blocks` |
| `templates.tar.gz` | `templates/` (339 real files) | `templates/` | `templates` |
| `theme.tar.gz` | the new `revista/` theme (14 files) | `revista/` | `plugins/themes/revista` |
| `styles/mobile.css` | **not packaged** — copied directly | — | `styles/mobile.css` |

**On the workstation**

```bash
cd /Users/jesus/Code/revistag

# sanity check what is going over
ls -la updates/

scp -r updates <user>@<host>:/tmp/
scp    ojs_code/styles/mobile.css <user>@<host>:/tmp/mobile.css
```

**On the production host**

The docroot is `/var/www/revistag` — note it is called `revistag`, not `ojs_code`,
which is also why the baseline `font_location` in `config.inc.php` points at
`/var/www/revistag/public/freefont/FreeSerif.ttf` (§3).

```bash
ssh <user>@<host>
cd /var/www/revistag

# Record ownership and modes BEFORE replacing anything, so the new copies match.
stat -c '%U:%G %a %n' plugins/blocks templates plugins/themes styles/mobile.css
```

```bash
# 1. Unpack somewhere outside the docroot, so a half-finished extraction can
#    never be served.
rm -rf /tmp/revista-stage && mkdir -p /tmp/revista-stage
tar xzf /tmp/updates/blocks.tar.gz     -C /tmp/revista-stage
tar xzf /tmp/updates/templates.tar.gz -C /tmp/revista-stage
tar xzf /tmp/updates/theme.tar.gz     -C /tmp/revista-stage
ls /tmp/revista-stage          # expect exactly: blocks  revista  templates

# 2. Move the originals aside. Do not delete: this is the rollback.
mv plugins/blocks plugins/blocks.pre-revista
mv templates      templates.pre-revista
if [ -d plugins/themes/revista ]; then
    mv plugins/themes/revista plugins/themes/revista.pre-revista
fi

# 3. Move the new directories into place.
mv /tmp/revista-stage/blocks    plugins/blocks
mv /tmp/revista-stage/templates templates
mv /tmp/revista-stage/revista   plugins/themes/revista

# 4. styles/mobile.css is not in any package.
install -m 644 /tmp/mobile.css styles/mobile.css

# 5. Match the ownership and modes recorded in step 0.
#    <OWNER>:<GROUP> below is whatever step 0 printed.
chown -R <OWNER>:<GROUP> plugins/blocks templates plugins/themes/revista styles/mobile.css
find plugins/blocks templates plugins/themes/revista -type d -exec chmod 755 {} \;
find plugins/blocks templates plugins/themes/revista -type f -exec chmod 644 {} \;

# 6. Confirm the four modified block templates and the four templates landed.
grep -c 'blockToggle' plugins/blocks/user/block.tpl          # expect >= 1
grep -c 'hamburger-btn' templates/common/header.tpl           # expect >= 1
grep -c 'issueCoverImage' plugins/themes/revista/css/layout.css  # expect >= 1
grep -c 'max-width: 520px' styles/mobile.css                  # expect >= 1
```

`plugins/themes/classicBlue/` is not touched by any of this and stays in place as
the rollback theme.

**Rollback if anything above goes wrong**, before running the SQL:

```bash
cd /var/www/revistag
rm -rf plugins/blocks templates plugins/themes/revista
mv plugins/blocks.pre-revista plugins/blocks
mv templates.pre-revista      templates
if [ -d plugins/themes/revista.pre-revista ]; then
    mv plugins/themes/revista.pre-revista plugins/themes/revista
fi
rm -rf /tmp/revista-stage /tmp/updates /tmp/mobile.css
```

### 6.2 Known gaps in the current packages

Worth fixing before shipping; none of them break the deploy, but two are noise
and one is a missed file.

**`styles/mobile.css` is not packaged.** It lives in a fourth directory,
`styles/`, and none of the three tarballs contains it. The command above copies
it directly, but it means the packages alone are not a complete deployment set.
If these packages are the unit of transport, build a fourth one:

```bash
cd /Users/jesus/Code/revistag/ojs_code
COPYFILE_DISABLE=1 tar czf ../updates/styles.tar.gz styles/mobile.css
```

**macOS metadata files are in the packages.** Built on macOS without
`COPYFILE_DISABLE=1`, so `blocks.tar.gz` carries 1007 `._*` entries and
`templates.tar.gz` 397 — more than half the entries in the first. They extract as
dotfiles into the docroot and are ignored by OJS, but they are 100% avoidable:

```bash
cd /Users/jesus/Code/revistag/ojs_code
COPYFILE_DISABLE=1 tar czf ../updates/blocks.tar.gz    plugins/blocks
COPYFILE_DISABLE=1 tar czf ../updates/templates.tar.gz templates
COPYFILE_DISABLE=1 tar czf ../updates/theme.tar.gz    plugins/themes/revista
```

**Eight `.tpl_` backup templates are included** (`issue/view.tpl_`,
`article/article.tpl_`, `common/navbar.tpl_`, `about/index.tpl_`,
`about/editorialPolicies.tpl_`, `user/login.tpl_`, `user/register.tpl_`,
`user/lostPassword.tpl_`). These are **pre-existing** — all eight are tracked in
the baseline commit, so they are presumably already on production and are
harmless; OJS only loads `.tpl`. They are listed here only so nobody assumes they
are new debris.

**The replacements are wholesale, not partial.** `plugins/blocks/` and
`templates/` are dumped in full, so anything edited directly on production since
the baseline will be lost. Before starting, compare and look at what you are
about to discard:

```bash
cd /var/www/revistag
diff -rq plugins/blocks.pre-revista plugins/blocks | head -40
diff -rq templates.pre-revista      templates      | head -40
```

Only the 19 block templates and 4 templates listed in §1.2 / §1.3 should differ.
If that diff shows much more, production has local changes that need porting
into this repo first.

---

## 7. Verification

**Theme is active.** *Website Settings → Setup, step 5* shows *Revista GEN*
selected in the Theme dropdown,
and the admin bar renders two-column.

**Database state.** The last statement of `production-db.sql` is read-only and
should report:

```
theme                revista
custom blocks        a:2:{i:0;s:10:"PUBLICIDAD";i:1;s:15:"PUBLICIDAD left";}
block placement      publicidad            ctx=2 seq=0 enabled=1
block placement      publicidad left       ctx=2 seq=2 enabled=1
block placement      userblockplugin       ctx=1 seq=0 enabled=1
block placement      navigationblockplugin ctx=1 seq=1 enabled=1
block placement      informationblockplugin ctx=2 seq=1 enabled=1
block placement      roleblockplugin       enabled=0
block placement      developedbyblockplugin enabled=0
```

**Layout, at 1280px.**

- Full-width hero, navbar with *Usuario* and *Contenido de la Revista* dropdowns
  to the trailing edge.
- Right sidebar in this order: **Chatbot GEN-IA** (working iframe, first),
  Patrocinadores, Información, Podcast / Cápsula.
- No floating chat bubble anywhere on the page.

**Cover image, 1100px / 1024px / 900px** — this was the original symptom and the
easiest to regress. `#issueCoverImage img` must measure exactly the width of its
parent and must not reach the sidebar:

```
1440 → 864px   1100 → 716px   900 → 516px   801 → 417px
```

**At ≤800px.**

- "Menu" bar, full width, centred label, below the hero.
- Tapping opens a full-screen overlay; the bar pins to the top; tapping again
  closes it.
- Sponsor logos visible (served from the footer sponsors section, since the
  `publicidad` block is hidden at this width).
- Floating chat bubble present at 55×55.
- No horizontal overflow.

**At ≤520px on `/index.php/GEN/user/register`** — the two bugs fixed last.

- Fields stacked label-above-input, full width; no horizontal overflow.
- **CAPTCHA shows letters**, not a blank field.

**`/index.php/GEN/issue/archive`** — issues grouped under year headings as cards.

**Console** — no JavaScript errors on any of the above. `js/navbar.js` has no
jQuery dependency; the only third-party scripts are the analytics tags and the
Chatbase widget, which are expected to be blocked when offline.

---

## 8. Rollback

Fastest path, no file restore:

```sql
UPDATE journal_settings SET setting_value='classicBlue'
WHERE journal_id=1 AND setting_name='journalTheme';
```

Then clear the cache (§5) and reload PHP. The site returns to the original
layout, though the `publicidad` block will still carry the inline chatbot and the
sidebar ordering will be whatever the original values were.

For a full revert, restore `~/revistag-templates-pre.tgz` and
`pre-revista.sql`, remove `plugins/themes/revista/`, then clear the cache.

---

## 9. If something goes wrong

| Symptom | Cause |
| --- | --- |
| Original layout, admin bar shows no theme option | `journalTheme` not set, or cache not cleared |
| Block absent from the sidebar | Missing from `customblockmanagerplugin.blocks` (§4.4) |
| Stale block content or sidebar order | `cache/fc-*` not cleared (§5) |
| Dropdowns do nothing | One of the 19 block templates in §1.2 was not copied |
| CAPTCHA background with no letters | `font_location` does not match the host docroot (§3) |
| Horizontal overflow, unscrollable | `styles/mobile.css` not updated (§1.4) |
| Cover image under the sidebar | `layout.css` not updated (§1.1) |