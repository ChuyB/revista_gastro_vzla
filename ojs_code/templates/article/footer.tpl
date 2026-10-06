{**
 * templates/article/footer.tpl
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Article View -- Footer component.
 *}

{if $sharingEnabled}
<!-- start AddThis -->
        {if isset($sharingDropDownMenu)}
                {if isset($sharingUserName)}
                        <script type="text/javascript">
                                var addthis_pub = '{$sharingUserName}';
                        </script>
                {/if}
                <br />
                <br />
                <div class="addthis_container">
                        <a href="http://www.addthis.com/bookmark.php"
                                onmouseover="return addthis_open(this, '', '{$sharingArticleURL|escape:"javascript"}', '{$sharingArticleTitle|escape:"javascript"}')"
                                onmouseout="addthis_close()" onclick="return addthis_sendto()">
                                        <img src="{$sharingButtonUrl}" width="{$sharingButtonWidth}" height="{$sharingButtonHeight}" border="0" alt="Bookmark and Share" style="border:0;padding:0" />
                        </a>
                        <script type="text/javascript" src="//s7.addthis.com/js/200/addthis_widget.js"></script>
                </div>
        {else}
                <a href="http://www.addthis.com/bookmark.php"
                        onclick="window.open('http://www.addthis.com/bookmark.php?pub={$sharingUserName|escape:"url"}&amp;url={$sharingRequestURL|escape:"url"}&amp;title={$sharingArticleTitle|escape:"url"}', 'addthis',
                                             'scrollbars=yes,menubar=no,width=620,height=520,resizable=yes,toolbar=no,location=no,status=no');
                                 return false;"
                        title="Bookmark using any bookmark manager!" target="_blank">
                                <img src="{$sharingButtonUrl}" width="{$sharingButtonWidth}" height="{$sharingButtonHeight}" border="0" alt="Bookmark and Share" style="border:0;padding:0" />
                </a>
        {/if}
<!-- end AddThis -->
{/if}

{if $currentJournal}
        {if $currentJournal->getSetting('includeCopyrightStatement')}
                <br/><br/>
                {translate key="submission.copyrightStatement" copyrightYear=$article->getCopyrightYear()|escape copyrightHolder=$article->getLocalizedCopyrightHolder()|escape}
        {/if}
        {if $currentJournal->getSetting('includeLicense')}
                <br /><br />
                {if $ccLicenseBadge}
                        {$ccLicenseBadge}
                {elseif $article->getLicenseURL()}
                        {translate key="submission.licenseURL"}: <a href="{$article->getLicenseURL()|escape}" rel="license">{$article->getLicenseURL()|escape}</a>
                {/if}
        {/if}
{/if}

{call_hook name="Templates::Article::Footer::PageFooter"}

<div class="sponsors-footer-section">
	<h5 class="sponsors-title">NUESTROS PATROCINADORES</h5>
	<div class="sponsors-grid">
		<div class="sponsor-item"><a href="https://astormedic.com/" target="_blank"><img src="{$baseUrl}/public/site/images/root/astor_logo.png" alt="Astor Medic" /></a></div>
		<div class="sponsor-item"><a href="https://calox.com/" target="_blank"><img src="{$baseUrl}/public/site/images/root/calox_logo.jpg" alt="Calox" /></a></div>
		<div class="sponsor-item"><a href="https://grupoleti.com/" target="_blank"><img src="{$baseUrl}/public/site/images/root/leti_logo.png" alt="Grupo Leti" /></a></div>
		<div class="sponsor-item"><a href="https://www.laboratoriosvargas.com/" target="_blank"><img src="{$baseUrl}/public/site/images/root/vargas_logo.png" alt="Laboratorios Vargas" /></a></div>
		<div class="sponsor-item"><a href="https://www.instrumentalia.com.ve/" target="_blank"><img src="{$baseUrl}/public/site/images/root/instrumentalia_logo.png" alt="Instrumentalia" /></a></div>
		<div class="sponsor-item"><a href="https://www.ronava.com/" target="_blank"><img src="{$baseUrl}/public/site/images/root/ronava_logo.png" alt="Ronava" /></a></div>
		<div class="sponsor-item full-width"><a href="https://endosuplymed.com/" target="_blank"><img src="{$baseUrl}/public/site/images/root/suplymed_logo.png" alt="Endosuplymed" /></a></div>
	</div>
</div>

{if $pageFooter}
<div id="pageFooter">
{$pageFooter}
</div>
{/if}

</div><!-- content -->
</div><!-- main -->
</div><!-- body -->

{if $defineTermsContextId}
<script type="text/javascript">
{literal}
<!--
        // Open "Define Terms" context when double-clicking any text
        function openSearchTermWindow(url) {
                var term;
                if (window.getSelection) {
                        term = window.getSelection();
                } else if (document.getSelection) {
                        term = document.getSelection();
                } else if(document.selection && document.selection.createRange && document.selection.type.toLowerCase() == 'text') {
                        var range = document.selection.createRange();
                        term = range.text;
                }
                if (term != ""){
                        if (url.indexOf('?') > -1) openRTWindowWithToolbar(url + '&defineTerm=' + term);
                        else openRTWindowWithToolbar(url + '?defineTerm=' + term);
                }
        }

        if(document.captureEvents) {
                document.captureEvents(Event.DBLCLICK);
        }

        // Make sure to only open the reading tools when double clicking within the galley
        if (document.getElementById('inlinePdfResizer')) {
                context = document.getElementById('inlinePdfResizer');
        }
        else if (document.getElementById('content')) {
                context = document.getElementById('content');
        }
        else {
                context = document;
        }

        context.ondblclick = new Function("openSearchTermWindow('{/literal}{url page="rt" op="context" path=$articleId|to_array:$galleyId:$defineTermsContextId escape=false}{literal}')");
// -->
{/literal}
</script>
{/if}

{get_debug_info}
{if $enableDebugStats}{include file=$pqpTemplate}{/if}
</div> <!-- container -->
</body>
</html>