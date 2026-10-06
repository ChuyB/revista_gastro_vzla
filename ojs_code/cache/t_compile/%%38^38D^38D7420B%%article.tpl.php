<?php /* Smarty version 2.6.26, created on 2026-06-30 03:39:55
         compiled from article/article.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'translate', 'article/article.tpl', 31, false),array('function', 'call_hook', 'article/article.tpl', 46, false),array('function', 'url', 'article/article.tpl', 215, false),array('modifier', 'escape', 'article/article.tpl', 43, false),array('modifier', 'strip_unsafe_html', 'article/article.tpl', 47, false),array('modifier', 'nl2br', 'article/article.tpl', 190, false),array('modifier', 'to_array', 'article/article.tpl', 215, false),array('block', 'iterate', 'article/article.tpl', 242, false),)), $this); ?>
<?php echo ''; ?><?php if ($this->_tpl_vars['galley']): ?><?php echo ''; ?><?php $this->assign('pubObject', $this->_tpl_vars['galley']); ?><?php echo ''; ?><?php else: ?><?php echo ''; ?><?php $this->assign('pubObject', $this->_tpl_vars['article']); ?><?php echo ''; ?><?php endif; ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "article/header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?>


<?php if ($this->_tpl_vars['galley']): ?>
	<?php if ($this->_tpl_vars['galley']->isHTMLGalley()): ?>
		<?php echo $this->_tpl_vars['galley']->getHTMLContents(); ?>

	<?php elseif ($this->_tpl_vars['galley']->isPdfGalley()): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "article/pdfViewer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endif; ?>
<?php else: ?>
	<div id="topBar">
		<?php if (is_a ( $this->_tpl_vars['article'] , 'PublishedArticle' )): ?><?php $this->assign('galleys', $this->_tpl_vars['article']->getGalleys()); ?><?php endif; ?>
		<?php if ($this->_tpl_vars['galleys'] && $this->_tpl_vars['subscriptionRequired'] && $this->_tpl_vars['showGalleyLinks']): ?>
			<div id="accessKey">
				<img src="<?php echo $this->_tpl_vars['baseUrl']; ?>
/lib/pkp/templates/images/icons/fulltext_open_medium.gif" alt="<?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "article.accessLogoOpen.altText"), $this);?>
" />
				<?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "reader.openAccess"), $this);?>
&nbsp;
				<img src="<?php echo $this->_tpl_vars['baseUrl']; ?>
/lib/pkp/templates/images/icons/fulltext_restricted_medium.gif" alt="<?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "article.accessLogoRestricted.altText"), $this);?>
" />
				<?php if ($this->_tpl_vars['purchaseArticleEnabled']): ?>
					<?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "reader.subscriptionOrFeeAccess"), $this);?>

				<?php else: ?>
					<?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "reader.subscriptionAccess"), $this);?>

				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
	<?php if ($this->_tpl_vars['coverPagePath']): ?>
		<div id="articleCoverImage"><img src="<?php echo ((is_array($_tmp=$this->_tpl_vars['coverPagePath'])) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
<?php echo ((is_array($_tmp=$this->_tpl_vars['coverPageFileName'])) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
"<?php if ($this->_tpl_vars['coverPageAltText'] != ''): ?> alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['coverPageAltText'])) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
"<?php else: ?> alt="<?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "article.coverPage.altText"), $this);?>
"<?php endif; ?><?php if ($this->_tpl_vars['width']): ?> width="<?php echo ((is_array($_tmp=$this->_tpl_vars['width'])) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
"<?php endif; ?><?php if ($this->_tpl_vars['height']): ?> height="<?php echo ((is_array($_tmp=$this->_tpl_vars['height'])) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
"<?php endif; ?>/>
		</div>
	<?php endif; ?>
	<?php echo $this->_plugins['function']['call_hook'][0][0]->smartyCallHook(array('name' => "Templates::Article::Article::ArticleCoverImage"), $this);?>

	<div id="articleTitle"><h3><?php echo ((is_array($_tmp=$this->_tpl_vars['article']->getLocalizedTitle())) ? $this->_run_mod_handler('strip_unsafe_html', true, $_tmp) : String::stripUnsafeHtml($_tmp)); ?>
</h3></div>
	<div id="authorString"><em><?php echo ((is_array($_tmp=$this->_tpl_vars['article']->getAuthorString())) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
</em></div>
	<br/>
	
    <?php 
    $articleId = $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']);

    $viewsData = json_decode(file_get_contents('/var/www/revistag/cache/views.json'), true);
    $views = isset($viewsData[$articleId]) ? $viewsData[$articleId] : 0;

	$viewsLastMonthdata = json_decode(file_get_contents('/var/www/revistag/cache/last_month_views.json'), true);
	$viewsLastMonth = isset($viewsLastMonthdata[$articleId]) ? $viewsLastMonthdata[$articleId]: 0;
    
    // Metadata for sharing
    $titleText = $this->_tpl_vars['article']->getLocalizedTitle();
    $titleEnc = rawurlencode($titleText);
    $articleUrl = 'https://revistagen.com/index.php/GEN/article/view/' . $articleId;
    $urlEnc = rawurlencode($articleUrl);

	echo '<p style="font-size:11px;color:#888;margin:6px 0 0 2px;">Métricas obtenidas de Google Analytics a partir de octubre de 2024.</p>';

    echo '<div id="articleHeaderInfo" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">';

	echo '<div style="display:flex;align-items:center;gap:10px;">';

    echo '<div style="display:inline-flex;align-items:center;gap:7px;background:#e8f0fe;border-radius:20px;padding:5px 14px 5px 10px;">
		<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1a5fc8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
			<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
			<circle cx="12" cy="12" r="3"/>
		</svg>
		<span style="font-size:14px;font-weight:500;color:#1a5fc8;">' . $views . ' vistas totales</span>
	</div>';

	echo '<div style="display:inline-flex;align-items:center;gap:7px;background:#e8f0fe;border-radius:20px;padding:5px 14px 5px 10px;">
		<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#66a2fa" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
			<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
			<circle cx="12" cy="12" r="3"/>
		</svg>
		<span style="font-size:14px;font-weight:500;color:#1a5fc8;">' . $viewsLastMonth . ' vistas en el último mes</span>
	</div>';

	echo '</div>';

    // Share Button 
    echo '<button onclick="document.getElementById(\'shareModal\').style.display=\'flex\'" style="background:#1a73e8;color:#fff;border:none;padding:8px 18px;border-radius:6px;cursor:pointer;font-size:14px;font-weight:600;">&#8679; Compartir</button>';
	
	echo '</div>';

    // Modal
	echo '
	<div id="shareModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
	<div style="background:#fff;border-radius:12px;padding:32px 40px;max-width:420px;width:90%;text-align:center;position:relative;box-shadow:0 8px 32px rgba(0,0,0,0.18);">
		<button onclick="document.getElementById(\'shareModal\').style.display=\'none\'" style="position:absolute;top:12px;right:16px;background:none;border:none;font-size:22px;cursor:pointer;color:#666;">&times;</button>
		<h3 style="margin:0 0 24px 0;font-size:16px;color:#333;line-height:1.4;">Comparte el artículo haciendo clic en el icono de las redes</h3>
		<div style="display:flex;justify-content:center;gap:24px;flex-wrap:wrap;">

		<a href="https://www.facebook.com/sharer/sharer.php?u=' . $urlEnc . '" target="_blank" title="Facebook" style="display:flex;flex-direction:column;align-items:center;gap:6px;text-decoration:none;color:#1877F2;">
			<svg width="36" height="36" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
			<span style="font-size:12px;font-weight:500;">Facebook</span>
		</a>

		<a href="https://twitter.com/intent/tweet?url=' . $urlEnc . '&text=' . $titleEnc . '" target="_blank" title="X" style="display:flex;flex-direction:column;align-items:center;gap:6px;text-decoration:none;color:#000;">
			<svg width="36" height="36" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
			<span style="font-size:12px;font-weight:500;">X</span>
		</a>

		<a href="https://api.whatsapp.com/send?text=' . $titleEnc . '%20' . $urlEnc . '" target="_blank" title="WhatsApp" style="display:flex;flex-direction:column;align-items:center;gap:6px;text-decoration:none;color:#25D366;">
			<svg width="36" height="36" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.27 9.27 0 01-4.723-1.282l-.339-.202-3.51.92 1.023-3.23-.223-.354A9.261 9.261 0 013.313 12c0-5.11 4.156-9.265 9.27-9.265 2.477 0 4.805.965 6.555 2.716a9.208 9.208 0 012.71 6.549c0 5.111-4.156 9.267-9.27 9.267m8.053-17.314c-2.152-2.152-5.013-3.337-8.057-3.337-6.273 0-11.378 5.105-11.378 11.378 0 2.006.523 3.964 1.516 5.691L1.25 22.75l4.331-1.136a11.312 11.312 0 005.69 1.517h.005c6.272 0 11.377-5.106 11.377-11.378 0-3.045-1.184-5.904-3.336-8.056z"/></svg>
			<span style="font-size:12px;font-weight:500;">WhatsApp</span>
		</a>

		<a href="#" onclick="copyAndOpenInstagram(event, \'' . $articleUrl . '\')" title="Instagram" style="display:flex;flex-direction:column;align-items:center;gap:6px;text-decoration:none;color:#E1306C;">
			<svg width="36" height="36" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
			<span style="font-size:12px;font-weight:500;">Instagram</span>
		</a>

		<a href="https://mail.google.com/mail/?view=cm&fs=1&tf=1&su=' . $titleEnc . '&body=' . $urlEnc . '" target="_blank" title="Gmail" style="display:flex;flex-direction:column;align-items:center;gap:6px;text-decoration:none;color:#EA4335;">
			<svg width="36" height="36" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
			<span style="font-size:12px;font-weight:500;">Gmail</span>
		</a>

		<a href="#" onclick="copyArticleUrl(event, \'' . $articleUrl . '\')" title="Copiar enlace" style="display:flex;flex-direction:column;align-items:center;gap:6px;text-decoration:none;color:#555;">
			<svg width="36" height="36" viewBox="0 0 24 24" fill="currentColor"><path d="M10.59 13.41c.41.39.41 1.03 0 1.42-.39.39-1.03.39-1.42 0a5.003 5.003 0 0 1 0-7.07l3.54-3.54a5.003 5.003 0 0 1 7.07 0 5.003 5.003 0 0 1 0 7.07l-1.49 1.49c.01-.82-.12-1.64-.4-2.42l.47-.48a3.001 3.001 0 0 0 0-4.24 3.001 3.001 0 0 0-4.24 0l-3.53 3.53a3.001 3.001 0 0 0 0 4.24zm2.82-4.24c.39-.39 1.03-.39 1.42 0a5.003 5.003 0 0 1 0 7.07l-3.54 3.54a5.003 5.003 0 0 1-7.07 0 5.003 5.003 0 0 1 0-7.07l1.49-1.49c-.01.82.12 1.64.4 2.42l-.47.48a3.001 3.001 0 0 0 0 4.24 3.001 3.001 0 0 0 4.24 0l3.53-3.53a3.001 3.001 0 0 0 0-4.24z"/></svg>
			<span style="font-size:12px;font-weight:500;">Copiar enlace</span>
		</a>

		</div>
	</div>
	</div>';
     ?>

    <?php echo '
    <script type="text/javascript">
    document.getElementById(\'shareModal\').addEventListener(\'click\', function(e) {
		if (e.target === this) this.style.display = \'none\';
	});
	function copyArticleUrl(e, url) {
		e.preventDefault();
		var dummy = document.createElement(\'textarea\');
		document.body.appendChild(dummy);
		dummy.value = url;
		dummy.select();
		document.execCommand(\'copy\');
		document.body.removeChild(dummy);
		alert(\'¡Enlace copiado!\');
	}

	function copyAndOpenInstagram(e, url) {
		e.preventDefault();
		navigator.clipboard.writeText(url).then(function() {
			alert(\'¡Enlace copiado! Pégalo en un mensaje directo de Instagram.\');
			window.open(\'https://www.instagram.com/\', \'_blank\');
		}).catch(function() {
			// Fallback for older browsers
			window.open(\'https://www.instagram.com/\', \'_blank\');
		});
	}
    </script>
    <style type="text/css">
	#shareModal { cursor: pointer; }
	#shareModal > div { cursor: default; }
    #articleShare a { text-decoration: none; transition: transform 0.2s; display: inline-flex; }
    #articleShare a:hover { transform: scale(1.1); opacity: 0.8; }
	#articleViews { transition: background .2s; }
	@media (prefers-color-scheme: dark) {
		#articleViews { background: #0C447C !important; }
		#articleViews span { color: #B5D4F4 !important; }
		#articleViews svg { stroke: #B5D4F4 !important; }
	}
    #miIframe { border: none; }
    </style>
    '; ?>


	<?php if ($this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 503 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 581 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 711 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 712 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 713 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 714 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 715 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 716 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 717 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 719 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 725 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 726 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 727 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 728 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 729 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 730 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 731 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 732 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 733 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 791 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 795 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 812 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 813 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 815 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 816 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 818 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 817 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 819 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 820 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 814 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 821 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 823 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 825 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 826 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 827 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 828 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 829 || $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']) == 830): ?>

		<iframe id="miIframe" border="none" src="https://revistagen.com/affiliation/<?php echo ((is_array($_tmp=$this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']))) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
.html" title="Contenido Externo"></iframe>
		
	<?php endif; ?>
	
	<?php if ($this->_tpl_vars['article']->getLocalizedAbstract()): ?>
		<div id="articleAbstract">
		<h4><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "article.abstract"), $this);?>
</h4>
		<br />
		<div><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['article']->getLocalizedAbstract())) ? $this->_run_mod_handler('strip_unsafe_html', true, $_tmp) : String::stripUnsafeHtml($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</div>
		<br />
		</div>
	<?php endif; ?>

	<?php if ($this->_tpl_vars['article']->getLocalizedSubject()): ?>
		<div id="articleSubject">
		<h4><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "article.subject"), $this);?>
</h4>
		<br />
		<div><?php echo ((is_array($_tmp=$this->_tpl_vars['article']->getLocalizedSubject())) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
</div>
		<br />
		</div>
	<?php endif; ?>

	<?php if (( ! $this->_tpl_vars['subscriptionRequired'] || $this->_tpl_vars['article']->getAccessStatus() == @ARTICLE_ACCESS_OPEN || $this->_tpl_vars['subscribedUser'] || $this->_tpl_vars['subscribedDomain'] )): ?>
		<?php $this->assign('hasAccess', 1); ?>
	<?php else: ?>
		<?php $this->assign('hasAccess', 0); ?>
	<?php endif; ?>

	<?php if ($this->_tpl_vars['galleys']): ?>
		<div id="articleFullText">
		<h4><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "reader.fullText"), $this);?>
</h4>
		<?php if ($this->_tpl_vars['hasAccess'] || ( $this->_tpl_vars['subscriptionRequired'] && $this->_tpl_vars['showGalleyLinks'] )): ?>
			<?php $_from = $this->_tpl_vars['article']->getGalleys(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['galleyList'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['galleyList']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['galley']):
        $this->_foreach['galleyList']['iteration']++;
?>
				<a href="<?php echo $this->_plugins['function']['url'][0][0]->smartyUrl(array('page' => 'article','op' => 'view','path' => ((is_array($_tmp=$this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']))) ? $this->_run_mod_handler('to_array', true, $_tmp, $this->_tpl_vars['galley']->getBestGalleyId($this->_tpl_vars['currentJournal'])) : $this->_plugins['modifier']['to_array'][0][0]->smartyToArray($_tmp, $this->_tpl_vars['galley']->getBestGalleyId($this->_tpl_vars['currentJournal'])))), $this);?>
" class="file" <?php if ($this->_tpl_vars['galley']->getRemoteURL()): ?>target="_blank"<?php else: ?>target="_parent"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['galley']->getGalleyLabel())) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
</a>
				<?php if ($this->_tpl_vars['subscriptionRequired'] && $this->_tpl_vars['showGalleyLinks'] && $this->_tpl_vars['restrictOnlyPdf']): ?>
					<?php if ($this->_tpl_vars['article']->getAccessStatus() == @ARTICLE_ACCESS_OPEN || ! $this->_tpl_vars['galley']->isPdfGalley()): ?>
						<img class="accessLogo" src="<?php echo $this->_tpl_vars['baseUrl']; ?>
/lib/pkp/templates/images/icons/fulltext_open_medium.gif" alt="<?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "article.accessLogoOpen.altText"), $this);?>
" />
					<?php else: ?>
						<img class="accessLogo" src="<?php echo $this->_tpl_vars['baseUrl']; ?>
/lib/pkp/templates/images/icons/fulltext_restricted_medium.gif" alt="<?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "article.accessLogoRestricted.altText"), $this);?>
" />
					<?php endif; ?>
				<?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
			<?php if ($this->_tpl_vars['subscriptionRequired'] && $this->_tpl_vars['showGalleyLinks'] && ! $this->_tpl_vars['restrictOnlyPdf']): ?>
				<?php if ($this->_tpl_vars['article']->getAccessStatus() == @ARTICLE_ACCESS_OPEN): ?>
					<img class="accessLogo" src="<?php echo $this->_tpl_vars['baseUrl']; ?>
/lib/pkp/templates/images/icons/fulltext_open_medium.gif" alt="<?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "article.accessLogoOpen.altText"), $this);?>
" />
				<?php else: ?>
					<img class="accessLogo" src="<?php echo $this->_tpl_vars['baseUrl']; ?>
/lib/pkp/templates/images/icons/fulltext_restricted_medium.gif" alt="<?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "article.accessLogoRestricted.altText"), $this);?>
" />
				<?php endif; ?>
			<?php endif; ?>
		<?php else: ?>
			&nbsp;<a href="<?php echo $this->_plugins['function']['url'][0][0]->smartyUrl(array('page' => 'about','op' => 'subscriptions'), $this);?>
" target="_parent"><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "reader.subscribersOnly"), $this);?>
</a>
		<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ($this->_tpl_vars['citationFactory']->getCount()): ?>
		<div id="articleCitations">
		<h4><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "submission.citations"), $this);?>
</h4>
		<br />
		<div>
			<?php $this->_tag_stack[] = array('iterate', array('from' => 'citationFactory','item' => 'citation')); $_block_repeat=true;$this->_plugins['block']['iterate'][0][0]->smartyIterate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
				<p><?php echo ((is_array($_tmp=$this->_tpl_vars['citation']->getRawCitation())) ? $this->_run_mod_handler('strip_unsafe_html', true, $_tmp) : String::stripUnsafeHtml($_tmp)); ?>
</p>
			<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['iterate'][0][0]->smartyIterate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		</div>
		<br />
		</div>
	<?php endif; ?>
<?php endif; ?>

<?php $_from = $this->_tpl_vars['pubIdPlugins']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pubIdPlugin']):
?>
	<?php if ($this->_tpl_vars['issue']->getPublished()): ?>
		<?php $this->assign('pubId', $this->_tpl_vars['pubIdPlugin']->getPubId($this->_tpl_vars['pubObject'])); ?>
	<?php else: ?>
		<?php $this->assign('pubId', $this->_tpl_vars['pubIdPlugin']->getPubId($this->_tpl_vars['pubObject'],true)); ?>	<?php endif; ?>
	<?php if ($this->_tpl_vars['pubId']): ?>
		<br />
		<br />
		<?php echo ((is_array($_tmp=$this->_tpl_vars['pubIdPlugin']->getPubIdDisplayType())) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
: <?php if (((is_array($_tmp=$this->_tpl_vars['pubIdPlugin']->getResolvingURL($this->_tpl_vars['currentJournal']->getId(),$this->_tpl_vars['pubId']))) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp))): ?><a id="pub-id::<?php echo ((is_array($_tmp=$this->_tpl_vars['pubIdPlugin']->getPubIdType())) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
" href="<?php echo ((is_array($_tmp=$this->_tpl_vars['pubIdPlugin']->getResolvingURL($this->_tpl_vars['currentJournal']->getId(),$this->_tpl_vars['pubId']))) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['pubIdPlugin']->getResolvingURL($this->_tpl_vars['currentJournal']->getId(),$this->_tpl_vars['pubId']))) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
</a><?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['pubId'])) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
<?php endif; ?>
	<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
<?php if ($this->_tpl_vars['galleys']): ?>
	<?php $_from = $this->_tpl_vars['pubIdPlugins']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pubIdPlugin']):
?>
		<?php $_from = $this->_tpl_vars['galleys']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['galleyList'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['galleyList']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['galley']):
        $this->_foreach['galleyList']['iteration']++;
?>
			<?php if ($this->_tpl_vars['issue']->getPublished()): ?>
				<?php $this->assign('galleyPubId', $this->_tpl_vars['pubIdPlugin']->getPubId($this->_tpl_vars['galley'])); ?>
			<?php else: ?>
				<?php $this->assign('galleyPubId', $this->_tpl_vars['pubIdPlugin']->getPubId($this->_tpl_vars['galley'],true)); ?>			<?php endif; ?>
			<?php if ($this->_tpl_vars['galleyPubId']): ?>
				<br />
				<br />
				<?php echo ((is_array($_tmp=$this->_tpl_vars['pubIdPlugin']->getPubIdDisplayType())) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
 (<?php echo ((is_array($_tmp=$this->_tpl_vars['galley']->getGalleyLabel())) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
): <?php if (((is_array($_tmp=$this->_tpl_vars['pubIdPlugin']->getResolvingURL($this->_tpl_vars['currentJournal']->getId(),$this->_tpl_vars['galleyPubId']))) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp))): ?><a id="pub-id::<?php echo ((is_array($_tmp=$this->_tpl_vars['pubIdPlugin']->getPubIdType())) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
-g<?php echo $this->_tpl_vars['galley']->getId(); ?>
" href="<?php echo ((is_array($_tmp=$this->_tpl_vars['pubIdPlugin']->getResolvingURL($this->_tpl_vars['currentJournal']->getId(),$this->_tpl_vars['galleyPubId']))) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['pubIdPlugin']->getResolvingURL($this->_tpl_vars['currentJournal']->getId(),$this->_tpl_vars['galleyPubId']))) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
</a><?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['galleyPubId'])) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
<?php endif; ?>
			<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
	<?php endforeach; endif; unset($_from); ?>
<?php endif; ?>
<?php echo $this->_plugins['function']['call_hook'][0][0]->smartyCallHook(array('name' => "Templates::Article::MoreInfo"), $this);?>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "article/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>