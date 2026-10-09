-- ---------------------------------------------------------------------------
-- Revista GEN - production database changes
--
-- Target: MySQL 5.7, schema `ojsg` (adjust the schema name to match the host).
-- Journal id: 1 (adjust if the production journal has a different id).
-- Derived from the development database; every value below was read back from
-- the running site, not hand-typed.
--
-- Safe to run more than once: all statements are INSERT ... ON DUPLICATE KEY
-- UPDATE against the real primary keys.
--   plugin_settings   PK: (plugin_name, locale, journal_id, setting_name)
--   journal_settings  PK: (journal_id, locale, setting_name)
--
-- Take a backup first:  mysqldump -u ojs_user -p ojsg plugin_settings journal_settings > pre-revista.sql
--
-- Not included on purpose: journalPageHeader, journalPageFooter, authorInformation,
-- homeHeaderTitleType, pageHeaderTitleType, journalThumbnailAltText, displayCurrentIssue,
-- itemsPerPage, numPageLinks. Those six were deleted by accident during development
-- and restored; production should already hold its own correct values, so they are
-- left untouched here. See the "Do not" section of the deploy guide.
-- ---------------------------------------------------------------------------

-- 1. Activate the revista theme
INSERT INTO journal_settings (journal_id, locale, setting_name, setting_value, setting_type)
VALUES (1, '', 'journalTheme', 'revista', 'string')
ON DUPLICATE KEY UPDATE setting_value='revista';

-- 2. Block placement: context (1=left sidebar, 2=right sidebar), seq, enabled
INSERT INTO plugin_settings (plugin_name, locale, journal_id, setting_name, setting_value, setting_type) VALUES
  ('userblockplugin', '', 1, 'context', '1', 'int'),
  ('userblockplugin', '', 1, 'seq', '0', 'int'),
  ('userblockplugin', '', 1, 'enabled', '1', 'bool'),
  ('navigationblockplugin', '', 1, 'context', '1', 'int'),
  ('navigationblockplugin', '', 1, 'seq', '1', 'int'),
  ('navigationblockplugin', '', 1, 'enabled', '1', 'bool'),
  ('informationblockplugin', '', 1, 'context', '2', 'int'),
  ('informationblockplugin', '', 1, 'seq', '1', 'int'),
  ('informationblockplugin', '', 1, 'enabled', '1', 'bool'),
  ('publicidad', '', 1, 'context', '2', 'int'),
  ('publicidad', '', 1, 'seq', '0', 'int'),
  ('publicidad', '', 1, 'enabled', '1', 'bool'),
  ('publicidad left', '', 1, 'context', '2', 'int'),
  ('publicidad left', '', 1, 'seq', '2', 'int'),
  ('publicidad left', '', 1, 'enabled', '1', 'bool'),
  ('roleblockplugin', '', 1, 'context', '2', 'int'),
  ('roleblockplugin', '', 1, 'seq', '1', 'int'),
  ('roleblockplugin', '', 1, 'enabled', '0', 'bool'),
  ('developedbyblockplugin', '', 1, 'context', '1', 'int'),
  ('developedbyblockplugin', '', 1, 'seq', '4', 'int'),
  ('developedbyblockplugin', '', 1, 'enabled', '0', 'bool')
ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value), setting_type=VALUES(setting_type);

-- 3. Custom block content (chatbot + sponsors, and podcast/capsula)
-- Keys are the serialized PHP arrays OJS stores; do not hand-edit the hex.
INSERT INTO plugin_settings (plugin_name, locale, journal_id, setting_name, setting_value, setting_type)
VALUES ('publicidad', '', 1, 'blockContent', 'a:1:{s:5:"es_AR";s:2450:"<!-- Google tag (gtag.js) -->
<script type="text/javascript" src="https://www.googletagmanager.com/gtag/js?id=G-2NXMG9D3DP"></script>
<script type="text/javascript">// <![CDATA[
window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag(\'js\', new Date());
  gtag(\'config\', \'G-2NXMG9D3DP\');
// ]]></script>
<h2 class="blockTitle">CHATBOT GEN-IA</h2>
<div class="chatPanel">
	<iframe src="https://www.chatbase.co/chatbot-iframe/m6u0FJRyOIHTD_-NaB8G7" title="Chatbot de la Revista GEN" loading="lazy"></iframe>
</div>
<p class="blockNote">Este chatbot ofrece informaci&oacute;n cient&iacute;fica de la Revista GEN y no reemplaza el consejo o diagn&oacute;stico de un profesional m&eacute;dico calificado. Consulte nuestra <a href="https://revistagen.com/index.php/GEN/about/editorialPolicies#custom-9">pol&iacute;tica de uso de Inteligencia Artificial</a>.</p>
<hr class="blockRule" />
<h3 class="blockTitle">PATROCINADORES</h3>
<p class="blockNote">Toda la informaci&oacute;n sobre nuestros patrocinadores al hacer clic sobre su logo</p>
<div class="sponsorList">
	<p><a href="https://astormedic.com/" target="_blank"><img src="/public/site/images/root/astor_logo.png" alt="Astor Medic" /></a></p>
	<p><a href="https://www.laboratoriosfarma.com/" target="_blank"><img src="/public/site/images/root/FARMA.png" alt="Farma" /></a></p>
	<p><a href="https://www.laboratoriosvargas.com/" target="_blank"><img src="/public/site/images/root/vargas_logo.png" alt="Laboratorios Vargas" /></a></p>
	<p><a href="http://www.pharmetiquelabs.com/quienes-somos/" target="_blank"><img src="/public/site/images/root/logo-Pharmetique.png" alt="Pharmetique" /></a></p>
	<p><a href="https://www.ronava.com/" target="_blank"><img src="/public/site/images/root/ronava_logo.png" alt="Ronava" /></a></p>
</div>
<!-- Google tag (gtag.js) -->
<script type="mce-text/javascript" src="https://www.googletagmanager.com/gtag/js?id=G-C7DEN8ZDRR"></script>
<script type="mce-text/javascript">// <![CDATA[
window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag(\'js\', new Date());
  gtag(\'config\', \'G-C7DEN8ZDRR\');
// ]]></script>
<script type="text/javascript">// <![CDATA[
window.embeddedChatbotConfig = {
  chatbotId: "m6u0FJRyOIHTD_-NaB8G7",
  domain: "www.chatbase.co"
}
// ]]></script>
<script type="text/javascript" src="https://www.chatbase.co/embed.min.js" defer="defer">// <![CDATA[

// ]]></script>";}', 'object')
ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value);

INSERT INTO plugin_settings (plugin_name, locale, journal_id, setting_name, setting_value, setting_type)
VALUES ('publicidad left', '', 1, 'blockContent', 'a:1:{s:5:"es_AR";s:827:"<h3 class="blockTitle">PODCAST GEN</h3>
<p class="blockNote">Clic para escuchar podcasts GEN</p>
<ul class="mediaList">
	<li><a href="https://youtube.com/@podcastgen-n4m?si=TKx14eH9PgFrGedt" target="_blank"><img src="/public/site/images/root/Youtube.PNG" alt="YouTube" /><span>Escuchar en YouTube</span></a></li>
	<li><a href="https://open.spotify.com/show/09A4fsNMxNrevysFq1byUr" target="_blank"><img src="/public/site/images/root/spotify.png" alt="Spotify" /><span>Escuchar en Spotify</span></a></li>
</ul>
<h3 class="blockTitle">CAPSULA ACADEMICA GEN</h3>
<p class="blockNote">Clic para ver c&aacute;psulas GEN</p>
<div class="mediaFeature">
	<a href="https://www.youtube.com/@RevistaGen" target="_blank"><img src="/public/site/images/root/capsula3.png" alt="C&aacute;psula del Dr. Juan Carlos Gonz&aacute;lez" /></a>
</div>";}', 'object')
ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value);

-- 4. Register the second custom block.
--    Pre-check this on production first: if it already lists custom blocks that
--    must be kept, merge them into the array instead of replacing it.
--    Check with:  SELECT setting_value FROM plugin_settings
--                 WHERE plugin_name='customblockmanagerplugin' AND setting_name='blocks';
--    A custom block that is not in this list does not render at all.
INSERT INTO plugin_settings (plugin_name, locale, journal_id, setting_name, setting_value, setting_type)
VALUES ('customblockmanagerplugin', '', 1, 'blocks', 'a:2:{i:0;s:10:"PUBLICIDAD";i:1;s:15:"PUBLICIDAD left";}', 'object')
ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value);

-- ---------------------------------------------------------------------------
-- ---------------------------------------------------------------------------
-- 5. Site-wide theme and hero, for pages with no journal context
-- ---------------------------------------------------------------------------
--
-- TemplateManager renders /index.php/index/... (the "Pagina personal" user page,
-- /index.php/index/login) through its `else` branch, which reads these
-- site_settings instead of the journal's. With siteTheme left at classicBlue
-- those pages render unthemed; with no pageHeaderTitleImage the hero falls back
-- to the site title ("GEN") instead of the journal banner.
--
-- siteTheme is also settable in the admin UI (Settings -> Website -> Admin).
--
-- PK site_settings is (setting_name, locale) and the DAO already groups rows by
-- locale, so each row below holds the UN-KEYED value for that locale. Do not
-- wrap the serialized payload in a locale key, or the setting resolves to
-- array('es_AR' => array('es_AR' => ...)) and the template sees no uploadName.
--
-- uploadName is resolved against getSiteFilesPath() = public_files_dir . '/site',
-- so this file must exist at public/site/pageHeaderTitleImage.png on the
-- production host (deploy guide section 6.1).
INSERT INTO site_settings (setting_name, locale, setting_value, setting_type) VALUES
  ('siteTheme', '', 'revista', 'string'),
  ('pageHeaderTitleType', 'en_US', 'i:1;', 'object'),
  ('pageHeaderTitleType', 'es_AR', 'i:1;', 'object'),
  ('pageHeaderTitleType', 'es_ES', 'i:1;', 'object'),
  ('pageHeaderTitleImage', 'en_US', 'a:6:{s:16:"originalFilename";s:11:"banner2.PNG";s:5:"width";i:1140;s:6:"height";i:253;s:10:"uploadName";s:24:"pageHeaderTitleImage.png";s:12:"dateUploaded";s:19:"2026-10-09 17:02:13";s:7:"altText";s:11:"Revista GEN";}', 'object'),
  ('pageHeaderTitleImage', 'es_AR', 'a:6:{s:16:"originalFilename";s:11:"banner2.PNG";s:5:"width";i:1140;s:6:"height";i:253;s:10:"uploadName";s:24:"pageHeaderTitleImage.png";s:12:"dateUploaded";s:19:"2026-10-09 17:02:13";s:7:"altText";s:11:"Revista GEN";}', 'object'),
  ('pageHeaderTitleImage', 'es_ES', 'a:6:{s:16:"originalFilename";s:11:"banner2.PNG";s:5:"width";i:1140;s:6:"height";i:253;s:10:"uploadName";s:24:"pageHeaderTitleImage.png";s:12:"dateUploaded";s:19:"2026-10-09 17:02:13";s:7:"altText";s:11:"Revista GEN";}', 'object')
ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value), setting_type=VALUES(setting_type);

-- ---------------------------------------------------------------------------
-- 6. Verification (read-only)
-- ---------------------------------------------------------------------------
SELECT 'theme' AS check_name, setting_value AS value FROM journal_settings
  WHERE journal_id=1 AND setting_name='journalTheme'
UNION ALL
SELECT CONCAT('custom blocks: ', setting_value), '' FROM plugin_settings
  WHERE plugin_name='customblockmanagerplugin' AND setting_name='blocks'
UNION ALL
SELECT 'site theme', setting_value FROM site_settings WHERE setting_name='siteTheme'
UNION ALL
SELECT CONCAT('site hero [', locale, ']'), setting_value FROM site_settings
  WHERE setting_name='pageHeaderTitleImage'
UNION ALL
SELECT 'block placement', CONCAT(plugin_name,' ctx=',MAX(IF(setting_name='context',setting_value,'')),
       ' seq=',MAX(IF(setting_name='seq',setting_value,'')),
       ' enabled=',MAX(IF(setting_name='enabled',setting_value,'')))
  FROM plugin_settings
  WHERE journal_id=1 AND setting_name IN ('context','seq','enabled')
    AND plugin_name IN ('userblockplugin','navigationblockplugin','informationblockplugin',
                        'publicidad','publicidad left','roleblockplugin','developedbyblockplugin')
  GROUP BY plugin_name;

-- Each blockContent row must unserialize to an array keyed by 'es_AR'.
-- From a shell:  php -r '$d=unserialize(file_get_contents("php://stdin")); var_dump(array_keys($d));'
