{**
 * templates/article/article.tpl
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Article View.
 *}
{strip}
{if $galley}
	{assign var=pubObject value=$galley}
{else}
	{assign var=pubObject value=$article}

{/if}
{include file="article/header.tpl"}
{/strip}

{if $galley}
	{if $galley->isHTMLGalley()}
		{$galley->getHTMLContents()}
	{elseif $galley->isPdfGalley()}
		{include file="article/pdfViewer.tpl"}
	{/if}
{else}
	<div id="topBar">
		{if is_a($article, 'PublishedArticle')}{assign var=galleys value=$article->getGalleys()}{/if}
		{if $galleys && $subscriptionRequired && $showGalleyLinks}
			<div id="accessKey">
				<img src="{$baseUrl}/lib/pkp/templates/images/icons/fulltext_open_medium.gif" alt="{translate key="article.accessLogoOpen.altText"}" />
				{translate key="reader.openAccess"}&nbsp;
				<img src="{$baseUrl}/lib/pkp/templates/images/icons/fulltext_restricted_medium.gif" alt="{translate key="article.accessLogoRestricted.altText"}" />
				{if $purchaseArticleEnabled}
					{translate key="reader.subscriptionOrFeeAccess"}
				{else}
					{translate key="reader.subscriptionAccess"}
				{/if}
			</div>
		{/if}
	</div>
	{if $coverPagePath}
		<div id="articleCoverImage"><img src="{$coverPagePath|escape}{$coverPageFileName|escape}"{if $coverPageAltText != ''} alt="{$coverPageAltText|escape}"{else} alt="{translate key="article.coverPage.altText"}"{/if}{if $width} width="{$width|escape}"{/if}{if $height} height="{$height|escape}"{/if}/>
		</div>
	{/if}
	{call_hook name="Templates::Article::Article::ArticleCoverImage"}
	<div id="articleTitle"><h3>{$article->getLocalizedTitle()|strip_unsafe_html}</h3></div>
	<div id="authorString"><em>{$article->getAuthorString()|escape}</em></div>
	<br/>
	
    {php}
    $articleId = $this->_tpl_vars['article']->getBestArticleId($this->_tpl_vars['currentJournal']);

    $baseDir = Core::getBaseDir();
    $viewsData = json_decode(file_get_contents($baseDir . '/cache/views.json'), true);
    $views = isset($viewsData[$articleId]) ? $viewsData[$articleId] : 0;

	$viewsLastMonthdata = json_decode(file_get_contents($baseDir . '/cache/last_month_views.json'), true);
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
    {/php}

    {literal}
    <script type="text/javascript">
    document.getElementById('shareModal').addEventListener('click', function(e) {
		if (e.target === this) this.style.display = 'none';
	});
	function copyArticleUrl(e, url) {
		e.preventDefault();
		var dummy = document.createElement('textarea');
		document.body.appendChild(dummy);
		dummy.value = url;
		dummy.select();
		document.execCommand('copy');
		document.body.removeChild(dummy);
		alert('¡Enlace copiado!');
	}

	function copyAndOpenInstagram(e, url) {
		e.preventDefault();
		navigator.clipboard.writeText(url).then(function() {
			alert('¡Enlace copiado! Pégalo en un mensaje directo de Instagram.');
			window.open('https://www.instagram.com/', '_blank');
		}).catch(function() {
			// Fallback for older browsers
			window.open('https://www.instagram.com/', '_blank');
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
    {/literal}

	{if $article->getBestArticleId($currentJournal)== 503  || $article->getBestArticleId($currentJournal)== 581 || $article->getBestArticleId($currentJournal)== 711 || $article->getBestArticleId($currentJournal)== 712 || $article->getBestArticleId($currentJournal)== 713 || $article->getBestArticleId($currentJournal)== 714 || $article->getBestArticleId($currentJournal)== 715 || $article->getBestArticleId($currentJournal)== 716 || $article->getBestArticleId($currentJournal)== 717 || $article->getBestArticleId($currentJournal)== 719 || $article->getBestArticleId($currentJournal)== 725 || $article->getBestArticleId($currentJournal)== 726 || $article->getBestArticleId($currentJournal)== 727 || $article->getBestArticleId($currentJournal)== 728 || $article->getBestArticleId($currentJournal)== 729 || $article->getBestArticleId($currentJournal)== 730 || $article->getBestArticleId($currentJournal)== 731 || $article->getBestArticleId($currentJournal)== 732 || $article->getBestArticleId($currentJournal)== 733 || $article->getBestArticleId($currentJournal)== 791 || $article->getBestArticleId($currentJournal)== 795 || $article->getBestArticleId($currentJournal)== 812 || $article->getBestArticleId($currentJournal)== 813 || $article->getBestArticleId($currentJournal)== 815 || $article->getBestArticleId($currentJournal)== 816 || $article->getBestArticleId($currentJournal)== 818 || $article->getBestArticleId($currentJournal)== 817 || $article->getBestArticleId($currentJournal)== 819 || $article->getBestArticleId($currentJournal)== 820 || $article->getBestArticleId($currentJournal)== 814 || $article->getBestArticleId($currentJournal)== 821 || $article->getBestArticleId($currentJournal)== 823 || $article->getBestArticleId($currentJournal)== 825 || $article->getBestArticleId($currentJournal)== 826 || $article->getBestArticleId($currentJournal)== 827 || $article->getBestArticleId($currentJournal)== 828 || $article->getBestArticleId($currentJournal)== 829 || $article->getBestArticleId($currentJournal)== 830 }

		<iframe id="miIframe" border="none" src="https://revistagen.com/affiliation/{$article->getBestArticleId($currentJournal)|escape}.html" title="Contenido Externo"></iframe>
		
	{/if}
	
	{if $article->getLocalizedAbstract()}
		<div id="articleAbstract">
		<h4>{translate key="article.abstract"}</h4>
		<br />
		<div>{$article->getLocalizedAbstract()|strip_unsafe_html|nl2br}</div>
		<br />
		</div>
	{/if}

	{if $article->getLocalizedSubject()}
		<div id="articleSubject">
		<h4>{translate key="article.subject"}</h4>
		<br />
		<div>{$article->getLocalizedSubject()|escape}</div>
		<br />
		</div>
	{/if}

	{if (!$subscriptionRequired || $article->getAccessStatus() == $smarty.const.ARTICLE_ACCESS_OPEN || $subscribedUser || $subscribedDomain)}
		{assign var=hasAccess value=1}
	{else}
		{assign var=hasAccess value=0}
	{/if}

	{if $galleys}
		<div id="articleFullText">
		<h4>{translate key="reader.fullText"}</h4>
		{if $hasAccess || ($subscriptionRequired && $showGalleyLinks)}
			{foreach from=$article->getGalleys() item=galley name=galleyList}
				<a href="{url page="article" op="view" path=$article->getBestArticleId($currentJournal)|to_array:$galley->getBestGalleyId($currentJournal)}" class="file" {if $galley->getRemoteURL()}target="_blank"{else}target="_parent"{/if}>{$galley->getGalleyLabel()|escape}</a>
				{if $subscriptionRequired && $showGalleyLinks && $restrictOnlyPdf}
					{if $article->getAccessStatus() == $smarty.const.ARTICLE_ACCESS_OPEN || !$galley->isPdfGalley()}
						<img class="accessLogo" src="{$baseUrl}/lib/pkp/templates/images/icons/fulltext_open_medium.gif" alt="{translate key="article.accessLogoOpen.altText"}" />
					{else}
						<img class="accessLogo" src="{$baseUrl}/lib/pkp/templates/images/icons/fulltext_restricted_medium.gif" alt="{translate key="article.accessLogoRestricted.altText"}" />
					{/if}
				{/if}
			{/foreach}
			{if $subscriptionRequired && $showGalleyLinks && !$restrictOnlyPdf}
				{if $article->getAccessStatus() == $smarty.const.ARTICLE_ACCESS_OPEN}
					<img class="accessLogo" src="{$baseUrl}/lib/pkp/templates/images/icons/fulltext_open_medium.gif" alt="{translate key="article.accessLogoOpen.altText"}" />
				{else}
					<img class="accessLogo" src="{$baseUrl}/lib/pkp/templates/images/icons/fulltext_restricted_medium.gif" alt="{translate key="article.accessLogoRestricted.altText"}" />
				{/if}
			{/if}
		{else}
			&nbsp;<a href="{url page="about" op="subscriptions"}" target="_parent">{translate key="reader.subscribersOnly"}</a>
		{/if}
		</div>
	{/if}

	{if $citationFactory->getCount()}
		<div id="articleCitations">
		<h4>{translate key="submission.citations"}</h4>
		<br />
		<div>
			{iterate from=citationFactory item=citation}
				<p>{$citation->getRawCitation()|strip_unsafe_html}</p>
			{/iterate}
		</div>
		<br />
		</div>
	{/if}
{/if}

{foreach from=$pubIdPlugins item=pubIdPlugin}
	{if $issue->getPublished()}
		{assign var=pubId value=$pubIdPlugin->getPubId($pubObject)}
	{else}
		{assign var=pubId value=$pubIdPlugin->getPubId($pubObject, true)}{* Preview rather than assign a pubId *}
	{/if}
	{if $pubId}
		<br />
		<br />
		{$pubIdPlugin->getPubIdDisplayType()|escape}: {if $pubIdPlugin->getResolvingURL($currentJournal->getId(), $pubId)|escape}<a id="pub-id::{$pubIdPlugin->getPubIdType()|escape}" href="{$pubIdPlugin->getResolvingURL($currentJournal->getId(), $pubId)|escape}">{$pubIdPlugin->getResolvingURL($currentJournal->getId(), $pubId)|escape}</a>{else}{$pubId|escape}{/if}
	{/if}
{/foreach}
{if $galleys}
	{foreach from=$pubIdPlugins item=pubIdPlugin}
		{foreach from=$galleys item=galley name=galleyList}
			{if $issue->getPublished()}
				{assign var=galleyPubId value=$pubIdPlugin->getPubId($galley)}
			{else}
				{assign var=galleyPubId value=$pubIdPlugin->getPubId($galley, true)}{* Preview rather than assign a pubId *}
			{/if}
			{if $galleyPubId}
				<br />
				<br />
				{$pubIdPlugin->getPubIdDisplayType()|escape} ({$galley->getGalleyLabel()|escape}): {if $pubIdPlugin->getResolvingURL($currentJournal->getId(), $galleyPubId)|escape}<a id="pub-id::{$pubIdPlugin->getPubIdType()|escape}-g{$galley->getId()}" href="{$pubIdPlugin->getResolvingURL($currentJournal->getId(), $galleyPubId)|escape}">{$pubIdPlugin->getResolvingURL($currentJournal->getId(), $galleyPubId)|escape}</a>{else}{$galleyPubId|escape}{/if}
			{/if}
		{/foreach}
	{/foreach}
{/if}
{call_hook name="Templates::Article::MoreInfo"}

{include file="article/footer.tpl"}