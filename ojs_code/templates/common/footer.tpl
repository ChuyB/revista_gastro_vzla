{**
 * templates/common/footer.tpl
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Common site footer — Overridden for Design Refactor
 *
 * NB: this file no longer delegates to core:common/footer.tpl. Emitting
 * #pageFooter as a direct child of #body — below the main/sidebar row, rather
 * than nested inside #main — is only possible in the template that closes
 * #content / #main / #body, so that closing markup is inlined here instead of
 * being included. That keeps lib/pkp/ pristine, at the cost of having to track
 * core changes by hand.
 *
 * On upgrade: diff this file against lib/pkp/templates/common/footer.tpl and
 * re-apply anything the new version adds (hooks, debug info, extra markup).
 *
 * @see plugins/themes/revista/css/layout.css — #pageFooter grid placement
 *}

<div class="sponsors-footer-section">
	<h5 class="sponsors-title">NUESTROS PATROCINADORES</h5>
	<div class="sponsors-grid">
		<div class="sponsor-item"><a href="https://astormedic.com/" target="_blank"><img src="{$baseUrl}/public/site/images/root/astor_logo.png" alt="Astor Medic" /></a></div>
		<div class="sponsor-item"><a href="https://www.laboratoriosfarma.com/" target="_blank"><img src="{$baseUrl}/public/site/images/root/FARMA.png" alt="Farma" /></a></div>
		<div class="sponsor-item"><a href="https://www.laboratoriosvargas.com/" target="_blank"><img src="{$baseUrl}/public/site/images/root/vargas_logo.png" alt="Laboratorios Vargas" /></a></div>
		<div class="sponsor-item"><a href="https://www.pharmetiquelabs.com/quienes-somos/" target="_blank"><img src="{$baseUrl}/public/site/images/root/logo-Pharmetique.png" alt="Pharmetique" /></a></div>
		<div class="sponsor-item"><a href="https://www.ronava.com/" target="_blank"><img src="{$baseUrl}/public/site/images/root/ronava_logo.png" alt="Ronava" /></a></div>
	</div>
</div>

{strip}
{if $pageFooter==''}
	{if $currentJournal && $currentJournal->getSetting('onlineIssn')}
		{assign var=issn value=$currentJournal->getSetting('onlineIssn')}
	{elseif $currentJournal && $currentJournal->getSetting('printIssn')}
		{assign var=issn value=$currentJournal->getSetting('printIssn')}
	{/if}
	{if $issn}
		{translate|assign:"issnText" key="journal.issn"}
		{assign var=pageFooter value="$issnText: $issn"}
	{/if}
{/if}

{if $displayCreativeCommons}
	{translate key="common.ccLicense"}
{/if}
{call_hook name="Templates::Common::Footer::PageFooter"}
</div><!-- content -->
</div><!-- main -->

{if $pageFooter}
	<div id="pageFooter">{$pageFooter}</div>
{/if}
</div><!-- body -->

{get_debug_info}
{if $enableDebugStats}{include file=$pqpTemplate}{/if}

</div><!-- container -->
</body>
</html>
{/strip}
