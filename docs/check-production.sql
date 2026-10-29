-- ---------------------------------------------------------------------------
-- Revista GEN - production check (READ-ONLY, changes nothing)
--
--   mysql -u ojs_user -p ojsg < docs/check-production.sql
--
-- Run this before and after applying production-db.sql. Every row below should
-- read PASS once the deploy is complete.
--
-- If a DB row says PASS but the site still renders unthemed, the database is
-- correct and the OJS file cache is stale -- see the last check.
-- ---------------------------------------------------------------------------

SELECT '--- am I on the right host? ---' AS '';
SELECT DATABASE() AS db_schema, @@hostname AS db_host;
SELECT journal_id, path, primary_locale, enabled FROM journals;

SELECT '--- database state ---' AS '';
SELECT 'journal theme [journal_settings.journalTheme]' AS check_name,
       IFNULL(setting_value,'(unset)') AS value,
       IF(setting_value='revista','PASS','FAIL') AS result
  FROM journal_settings WHERE journal_id=1 AND setting_name='journalTheme'
UNION ALL
SELECT 'site theme [site_settings.siteTheme]',
       IFNULL(setting_value,'(unset)'),
       IF(setting_value='revista','PASS','FAIL')
  FROM site_settings WHERE setting_name='siteTheme'
UNION ALL
SELECT CONCAT('site hero [',locale,']'),
       IF(setting_value LIKE '%pageHeaderTitleImage.png%','set','(unset or wrong file)'),
       IF(setting_value LIKE '%pageHeaderTitleImage.png%','PASS','FAIL')
  FROM site_settings WHERE setting_name='pageHeaderTitleImage'
UNION ALL
SELECT 'site hero type count [pageHeaderTitleType]',
       CAST(COUNT(*) AS CHAR),
       IF(COUNT(*)>0,'PASS','FAIL')
  FROM site_settings WHERE setting_name='pageHeaderTitleType'
UNION ALL
SELECT 'journal footer [journal_settings.journalPageFooter]',
       IF(setting_value LIKE '%BellTech%','set','(unset or missing BellTech)'),
       IF(setting_value LIKE '%BellTech%','PASS','FAIL')
  FROM journal_settings WHERE journal_id=1 AND setting_name='journalPageFooter'
UNION ALL
SELECT 'custom blocks [customblockmanagerplugin.blocks]',
       IFNULL(setting_value,'(unset)'),
       IF(setting_value LIKE '%PUBLICIDAD left%','PASS','FAIL')
  FROM plugin_settings WHERE plugin_name='customblockmanagerplugin' AND setting_name='blocks'
UNION ALL
SELECT CONCAT('block ',plugin_name),
       CONCAT('ctx=',MAX(IF(setting_name='context',setting_value,'')),
              ' seq=',MAX(IF(setting_name='seq',setting_value,'')),
              ' enabled=',MAX(IF(setting_name='enabled',setting_value,''))),
       'info'
  FROM plugin_settings
  WHERE journal_id=1 AND setting_name IN ('context','seq','enabled')
    AND plugin_name IN ('userblockplugin','navigationblockplugin','informationblockplugin',
                        'publicidad','publicidad left','roleblockplugin','developedbyblockplugin')
  GROUP BY plugin_name;

SELECT '--- the two things that silently break it ---' AS '';
SELECT '1. mysql aborts on the first error' AS trap,
       'Re-run production-db.sql with: mysql --force -u ojs_user -p ojsg < production-db.sql' AS remedy;
SELECT '2. OJS caches settings in cache/fc-*.php' AS trap,
       'rm -rf cache/t_compile/* does NOT clear them' AS remedy,
       'Run: find cache -maxdepth 1 -name ''fc-*'' -delete' AS command_to_run;

-- Final confirmation, in the browser: view source on a journal-less page and
-- look for this line. If it is absent the theme is not being applied.
--   <link rel="stylesheet" href="http://<host>/plugins/themes/revista/revista.css" ...>
