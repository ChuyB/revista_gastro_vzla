{**
 * templates/issue/archive.tpl
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Issue Archive — Overridden for Design Refactor
 *
 * Issues are grouped by year. #issues is the first div child of #content and
 * holds every issue; each year is an .issueYear heading plus an .issueGrid, and
 * each issue is an .issueCard keyed #issue-<issueId>.
 *
 * The previous markup used a `float: left; width: 100%` wrapper per year, a
 * `div.separator` between years and a `.issueCoverImage` whose `img` is forced
 * to `float: left; width: 100px` by styles/common.css — which is why covers and
 * titles sat side by side. Those wrappers and the .issueCoverImage class are
 * gone; see plugins/themes/revista/css/archive.css for the grid.
 *}
{strip}
{assign var="pageTitle" value="archive.archives"}
{include file="common/header.tpl"}
{/strip}

<div id="issues">
{iterate from=issues item=issue}
	{if $issue->getYear() != $lastYear}
		{if !$notFirstYear}
			{assign var=notFirstYear value=1}
		{else}
			</div>
			</div>
		{/if}
		<div class="issueYear">
			<h3 class="issueYearTitle">{$issue->getYear()|escape}</h3>
			<div class="issueGrid">
		{assign var=lastYear value=$issue->getYear()}
	{/if}

	{if $issue->getFileName($locale)}
		{assign var="coverLocale" value="$locale"}
	{else}
		{assign var="coverLocale" value="$primaryLocale"}
	{/if}

	{* Resolved once so the card body can be shared by both branches *}
	{assign var="showCover" value="0"}
	{if $issue->getFileName($coverLocale) && $issue->getShowCoverPage($coverLocale) && !$issue->getHideCoverPageArchives($coverLocale)}
		{assign var="showCover" value="1"}
	{/if}

	<div class="issueCard{if !$showCover} issueCardNoCover{/if}" id="issue-{$issue->getId()}">
		{if $showCover}
			<a class="issueCardCover" href="{url op="view" path=$issue->getBestIssueId($currentJournal)}">
				<img src="{$coverPagePath|escape}{$issue->getFileName($coverLocale)|escape}"{if $issue->getCoverPageAltText($coverLocale) != ''} alt="{$issue->getCoverPageAltText($coverLocale)|escape}"{else} alt="{translate key="issue.coverPage.altText"}"{/if} />
			</a>
		{/if}
		<div class="issueCardBody">
			<h4 class="issueCardTitle"><a href="{url op="view" path=$issue->getBestIssueId($currentJournal)}">{$issue->getIssueIdentification()|escape}</a></h4>
			{if $showCover}
				<div class="issueCoverDescription">{$issue->getLocalizedCoverPageDescription()|strip_unsafe_html|nl2br}</div>
			{else}
				<div class="issueDescription">{$issue->getLocalizedDescription()|strip_unsafe_html|nl2br}</div>
			{/if}
		</div>
	</div>

{/iterate}
{if $notFirstYear}
	</div>
	</div>
{/if}
</div>

{if !$issues->wasEmpty()}
	<div id="issuesPagination">
		{page_info iterator=$issues}&nbsp;&nbsp;&nbsp;&nbsp;
		{page_links anchor="issues" name="issues" iterator=$issues}
	</div>
{else}
	<p class="issuesEmpty">{translate key="current.noCurrentIssueDesc"}</p>
{/if}

{include file="common/footer.tpl"}