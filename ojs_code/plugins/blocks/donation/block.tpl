{**
 * plugins/blocks/donation/block.tpl
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Common site sidebar menu -- donation links.
 *
 *}
{if $donationEnabled}
<div class="block" id="sidebarDonation">
	<h2 class="blockTitle"><a href="{url page="donations"}">{translate key="payment.type.donation"}</a></h2>
</div>
{/if}
