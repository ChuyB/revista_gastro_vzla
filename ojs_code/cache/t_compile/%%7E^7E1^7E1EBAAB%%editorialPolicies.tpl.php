<?php /* Smarty version 2.6.26, created on 2016-04-20 19:54:37
         compiled from about/editorialPolicies.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'url', 'about/editorialPolicies.tpl', 17, false),array('function', 'translate', 'about/editorialPolicies.tpl', 17, false),array('modifier', 'escape', 'about/editorialPolicies.tpl', 30, false),array('modifier', 'nl2br', 'about/editorialPolicies.tpl', 37, false),array('modifier', 'string_format', 'about/editorialPolicies.tpl', 94, false),)), $this); ?>
<?php echo ''; ?><?php $this->assign('pageTitle', "about.editorialPolicies"); ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?>


<ul>
	<?php if ($this->_tpl_vars['currentJournal']->getLocalizedSetting('focusScopeDesc') != ''): ?><li id="linkFocusScopeDesc"><a href="<?php echo $this->_plugins['function']['url'][0][0]->smartyUrl(array('op' => 'editorialPolicies','anchor' => 'focusAndScope'), $this);?>
"><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "about.focusAndScope"), $this);?>
</a></li><?php endif; ?>
	<?php if ($this->_tpl_vars['currentJournal']->getLocalizedSetting('reviewPolicy') != ''): ?><li id="linkReviewPolicy"><a href="<?php echo $this->_plugins['function']['url'][0][0]->smartyUrl(array('op' => 'editorialPolicies','anchor' => 'peerReviewProcess'), $this);?>
"><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "about.peerReviewProcess"), $this);?>
</a></li><?php endif; ?>
	<?php if ($this->_tpl_vars['currentJournal']->getLocalizedSetting('pubFreqPolicy') != ''): ?><li id="linkPubFreqPolicy"><a href="<?php echo $this->_plugins['function']['url'][0][0]->smartyUrl(array('op' => 'editorialPolicies','anchor' => 'publicationFrequency'), $this);?>
"><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "about.publicationFrequency"), $this);?>
</a></li><?php endif; ?>
	<?php if ($this->_tpl_vars['currentJournal']->getSetting('publishingMode') == @PUBLISHING_MODE_OPEN && $this->_tpl_vars['currentJournal']->getLocalizedSetting('openAccessPolicy') != ''): ?><li id="linkOpenAccessPolicy"><a href="<?php echo $this->_plugins['function']['url'][0][0]->smartyUrl(array('op' => 'editorialPolicies','anchor' => 'openAccessPolicy'), $this);?>
"><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "about.openAccessPolicy"), $this);?>
</a></li><?php endif; ?>
	<?php if ($this->_tpl_vars['currentJournal']->getSetting('publishingMode') == @PUBLISHING_MODE_SUBSCRIPTION): ?>
		<?php if ($this->_tpl_vars['currentJournal']->getSetting('enableAuthorSelfArchive')): ?><li id="enabledAuthorSelfArchive"><a href="<?php echo $this->_plugins['function']['url'][0][0]->smartyUrl(array('op' => 'editorialPolicies','anchor' => 'authorSelfArchivePolicy'), $this);?>
"><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "about.authorSelfArchive"), $this);?>
</a></li><?php endif; ?>
		<?php if ($this->_tpl_vars['currentJournal']->getSetting('enableDelayedOpenAccess')): ?><li id="enabledDelayedOpenAccess"><a href="<?php echo $this->_plugins['function']['url'][0][0]->smartyUrl(array('op' => 'editorialPolicies','anchor' => 'delayedOpenAccessPolicy'), $this);?>
"><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "about.delayedOpenAccess"), $this);?>
</a></li><?php endif; ?>
		<?php if ($this->_tpl_vars['paymentConfigured'] && $this->_tpl_vars['currentJournal']->getSetting('journalPaymentsEnabled') && $this->_tpl_vars['currentJournal']->getSetting('acceptSubscriptionPayments') && $this->_tpl_vars['currentJournal']->getSetting('purchaseIssueFeeEnabled') && $this->_tpl_vars['currentJournal']->getSetting('purchaseIssueFee') > 0): ?><li id="purchaseIssue"><a href="<?php echo $this->_plugins['function']['url'][0][0]->smartyUrl(array('op' => 'editorialPolicies','anchor' => 'purchaseIssue'), $this);?>
"><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "about.purchaseIssue"), $this);?>
</a></li><?php endif; ?>
		<?php if ($this->_tpl_vars['paymentConfigured'] && $this->_tpl_vars['currentJournal']->getSetting('journalPaymentsEnabled') && $this->_tpl_vars['currentJournal']->getSetting('acceptSubscriptionPayments') && $this->_tpl_vars['currentJournal']->getSetting('purchaseArticleFeeEnabled') && $this->_tpl_vars['currentJournal']->getSetting('purchaseArticleFee') > 0): ?><li id="purchaseArticle"><a href="<?php echo $this->_plugins['function']['url'][0][0]->smartyUrl(array('op' => 'editorialPolicies','anchor' => 'purchaseArticle'), $this);?>
"><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "about.purchaseArticle"), $this);?>
</a></li><?php endif; ?>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['currentJournal']->getSetting('enableLockss') && $this->_tpl_vars['currentJournal']->getLocalizedSetting('lockssLicense') != ''): ?><li id="linkLockssLicense"><a href="<?php echo $this->_plugins['function']['url'][0][0]->smartyUrl(array('op' => 'editorialPolicies','anchor' => 'archiving'), $this);?>
"><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "about.archiving"), $this);?>
</a></li><?php endif; ?>
	<?php $_from = $this->_tpl_vars['currentJournal']->getLocalizedSetting('customAboutItems'); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['customAboutItem']):
?>
		<?php if (! empty ( $this->_tpl_vars['customAboutItem']['title'] )): ?>
			<li><a href="<?php echo $this->_plugins['function']['url'][0][0]->smartyUrl(array('op' => 'editorialPolicies','anchor' => "custom-".($this->_tpl_vars['key'])), $this);?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['customAboutItem']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
</a></li>
		<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
</ul>

<?php if ($this->_tpl_vars['currentJournal']->getLocalizedSetting('focusScopeDesc') != ''): ?>
<div id="focusAndScope"><h3><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "about.focusAndScope"), $this);?>
</h3>
<p><?php echo ((is_array($_tmp=$this->_tpl_vars['currentJournal']->getLocalizedSetting('focusScopeDesc'))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</p>

<div class="separator">&nbsp;</div>
</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['currentJournal']->getLocalizedSetting('reviewPolicy') != ''): ?><div id="peerReviewProcess"><h3><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "about.peerReviewProcess"), $this);?>
</h3>
<p><?php echo ((is_array($_tmp=$this->_tpl_vars['currentJournal']->getLocalizedSetting('reviewPolicy'))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</p>

<div class="separator">&nbsp;</div>
</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['currentJournal']->getLocalizedSetting('pubFreqPolicy') != ''): ?>
<div id="publicationFrequency"><h3><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "about.publicationFrequency"), $this);?>
</h3>
<p><?php echo ((is_array($_tmp=$this->_tpl_vars['currentJournal']->getLocalizedSetting('pubFreqPolicy'))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</p>

<div class="separator">&nbsp;</div>
</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['currentJournal']->getSetting('publishingMode') == @PUBLISHING_MODE_OPEN && $this->_tpl_vars['currentJournal']->getLocalizedSetting('openAccessPolicy') != ''): ?> 
<div id="openAccessPolicy"><h3><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "about.openAccessPolicy"), $this);?>
</h3>
<p><?php echo ((is_array($_tmp=$this->_tpl_vars['currentJournal']->getLocalizedSetting('openAccessPolicy'))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</p>

<div class="separator">&nbsp;</div>
</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['currentJournal']->getSetting('publishingMode') == @PUBLISHING_MODE_SUBSCRIPTION && $this->_tpl_vars['currentJournal']->getSetting('enableAuthorSelfArchive')): ?> 
<div id="authorSelfArchivePolicy"><h3><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "about.authorSelfArchive"), $this);?>
</h3> 
<p><?php echo ((is_array($_tmp=$this->_tpl_vars['currentJournal']->getLocalizedSetting('authorSelfArchivePolicy'))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</p>

<div class="separator">&nbsp;</div>
</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['currentJournal']->getSetting('publishingMode') == @PUBLISHING_MODE_SUBSCRIPTION && $this->_tpl_vars['currentJournal']->getSetting('enableDelayedOpenAccess')): ?>
<div id="delayedOpenAccessPolicy"><h3><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "about.delayedOpenAccess"), $this);?>
</h3> 
<p><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "about.delayedOpenAccessDescription1"), $this);?>
 <?php echo $this->_tpl_vars['currentJournal']->getSetting('delayedOpenAccessDuration'); ?>
 <?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "about.delayedOpenAccessDescription2"), $this);?>
</p>
<?php if ($this->_tpl_vars['currentJournal']->getLocalizedSetting('delayedOpenAccessPolicy') != ''): ?>
	<p><?php echo ((is_array($_tmp=$this->_tpl_vars['currentJournal']->getLocalizedSetting('delayedOpenAccessPolicy'))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</p>
<?php endif; ?>

<div class="separator">&nbsp;</div>
</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['currentJournal']->getSetting('publishingMode') == @PUBLISHING_MODE_SUBSCRIPTION && $this->_tpl_vars['paymentConfigured'] && $this->_tpl_vars['currentJournal']->getSetting('journalPaymentsEnabled') && $this->_tpl_vars['currentJournal']->getSetting('acceptSubscriptionPayments') && $this->_tpl_vars['currentJournal']->getSetting('purchaseIssueFeeEnabled') && $this->_tpl_vars['currentJournal']->getSetting('purchaseIssueFee') > 0): ?>
		<div id="purchaseIssue">
			<h3><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "about.purchaseIssue"), $this);?>
</h3>
			<p><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "about.purchaseIssueDescription"), $this);?>
</p>
			<p><?php if ($this->_tpl_vars['currentJournal']->getLocalizedSetting('purchaseIssueFeeName') != ''): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['currentJournal']->getLocalizedSetting('purchaseIssueFeeName'))) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
: <?php endif; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['currentJournal']->getSetting('purchaseIssueFee'))) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
 <?php if ($this->_tpl_vars['currentJournal']->getSetting('currency') != ''): ?>(<?php echo ((is_array($_tmp=$this->_tpl_vars['currentJournal']->getSetting('currency'))) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
)<?php endif; ?></p>
			<?php if ($this->_tpl_vars['currentJournal']->getLocalizedSetting('purchaseIssueFeeDescription') != ''): ?>
				<p><?php echo ((is_array($_tmp=$this->_tpl_vars['currentJournal']->getLocalizedSetting('purchaseIssueFeeDescription'))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</p>
			<?php endif; ?>

			<div class="separator">&nbsp;</div>
		</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['currentJournal']->getSetting('publishingMode') == @PUBLISHING_MODE_SUBSCRIPTION && $this->_tpl_vars['paymentConfigured'] && $this->_tpl_vars['currentJournal']->getSetting('journalPaymentsEnabled') && $this->_tpl_vars['currentJournal']->getSetting('acceptSubscriptionPayments') && $this->_tpl_vars['currentJournal']->getSetting('purchaseArticleFeeEnabled') && $this->_tpl_vars['currentJournal']->getSetting('purchaseArticleFee') > 0): ?>
		<div id="purchaseArticle">
			<h3><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "about.purchaseArticle"), $this);?>
</h3>
			<p><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "about.purchaseArticleDescription"), $this);?>
</p>
			<p><?php if ($this->_tpl_vars['currentJournal']->getLocalizedSetting('purchaseArticleFeeName') != ''): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['currentJournal']->getLocalizedSetting('purchaseArticleFeeName'))) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
: <?php endif; ?> <?php echo ((is_array($_tmp=$this->_tpl_vars['currentJournal']->getSetting('purchaseArticleFee'))) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
 <?php if ($this->_tpl_vars['currentJournal']->getSetting('currency') != ''): ?>(<?php echo ((is_array($_tmp=$this->_tpl_vars['currentJournal']->getSetting('currency'))) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
)<?php endif; ?></p>
			<?php if ($this->_tpl_vars['currentJournal']->getLocalizedSetting('purchaseArticleFeeDescription') != ''): ?>
				<p><?php echo ((is_array($_tmp=$this->_tpl_vars['currentJournal']->getLocalizedSetting('purchaseArticleFeeDescription'))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</p>
			<?php endif; ?>

			<div class="separator">&nbsp;</div>
		</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['currentJournal']->getSetting('enableLockss') && $this->_tpl_vars['currentJournal']->getLocalizedSetting('lockssLicense') != ''): ?>
<div id="archiving"><h3><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "about.archiving"), $this);?>
</h3>
<p><?php echo ((is_array($_tmp=$this->_tpl_vars['currentJournal']->getLocalizedSetting('lockssLicense'))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</p>

<div class="separator">&nbsp;</div>
</div>
<?php endif; ?>

<?php $_from = $this->_tpl_vars['currentJournal']->getLocalizedSetting('customAboutItems'); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['customAboutItems'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['customAboutItems']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['customAboutItem']):
        $this->_foreach['customAboutItems']['iteration']++;
?>
	<?php if (! empty ( $this->_tpl_vars['customAboutItem']['title'] )): ?>
		<div id="custom-<?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
"><h3><?php echo ((is_array($_tmp=$this->_tpl_vars['customAboutItem']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
</h3>
		<p><?php echo ((is_array($_tmp=$this->_tpl_vars['customAboutItem']['content'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</p>
		<?php if (! ($this->_foreach['customAboutItems']['iteration'] == $this->_foreach['customAboutItems']['total'])): ?><div class="separator">&nbsp;</div><?php endif; ?>
		</div>
	<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
