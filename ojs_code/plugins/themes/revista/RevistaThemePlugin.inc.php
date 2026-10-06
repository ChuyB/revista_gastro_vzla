<?php

/**
 * @file plugins/themes/revista/RevistaThemePlugin.inc.php
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class RevistaThemePlugin
 * @ingroup plugins_themes_revista
 *
 * @brief "Revista GEN" theme plugin
 */

import('classes.plugins.ThemePlugin');

class RevistaThemePlugin extends ThemePlugin {
	/**
	 * Get the name of this plugin. The name must be unique within
	 * its category.
	 * @return String name of this plugin
	 */
	function getName() {
		return 'RevistaThemePlugin';
	}

	function getDisplayName() {
		return 'Revista GEN';
	}

	function getDescription() {
		return 'Modern layout for Revista GEN: full-width navbar with dropdown blocks, two-column body, ad strip under the main content.';
	}

	function getStylesheetFilename() {
		return 'revista.css';
	}

	function getLocaleFilename($locale) {
		return null; // No locale data
	}

	/**
	 * Activate the theme. Ships the navbar dropdown behaviour script in
	 * addition to the stylesheet. addJavaScript() appends the <script> tag
	 * to {$additionalHeadData}, which common/header.tpl already prints.
	 */
	function activate(&$templateMgr) {
		parent::activate($templateMgr);
		$templateMgr->addJavaScript('plugins/themes/revista/js/navbar.js');
	}
}

?>