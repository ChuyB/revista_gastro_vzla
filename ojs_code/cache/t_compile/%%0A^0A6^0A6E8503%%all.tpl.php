<?php /* Smarty version 2.6.26, created on 2024-11-16 18:11:09
         compiled from file:/var/www/revistag/plugins/importexport/crossref/templates/all.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'plugin_url', 'file:/var/www/revistag/plugins/importexport/crossref/templates/all.tpl', 30, false),array('function', 'translate', 'file:/var/www/revistag/plugins/importexport/crossref/templates/all.tpl', 39, false),array('function', 'fieldLabel', 'file:/var/www/revistag/plugins/importexport/crossref/templates/all.tpl', 56, false),array('function', 'url', 'file:/var/www/revistag/plugins/importexport/crossref/templates/all.tpl', 57, false),array('modifier', 'strip_tags', 'file:/var/www/revistag/plugins/importexport/crossref/templates/all.tpl', 57, false),array('modifier', 'strip_unsafe_html', 'file:/var/www/revistag/plugins/importexport/crossref/templates/all.tpl', 58, false),array('modifier', 'escape', 'file:/var/www/revistag/plugins/importexport/crossref/templates/all.tpl', 59, false),)), $this); ?>
<?php echo ''; ?><?php $this->assign('pageTitle', "plugins.importexport.crossref.export.selectUnregistered"); ?><?php echo ''; ?><?php $this->assign('pageCrumbTitle', "plugins.importexport.crossref.export.selectUnregistered"); ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?>


<script type="text/javascript"><?php echo '
	function toggleChecked() {
		var elements = document.getElementById(\'exportAllForm\').elements;
		for (var i=0; i < elements.length; i++) {
			if (elements[i].name == \'articleId[]\') {
				elements[i].checked = !elements[i].checked;
			}
		}
	}
'; ?>
</script>

<br/>

<div id="allUnregistered">
	<form action="<?php echo $this->_plugins['function']['plugin_url'][0][0]->smartyPluginUrl(array('path' => 'process'), $this);?>
" method="post" id="exportAllForm">
		<input type="hidden" name="target" value="all" />
		<table width="100%" class="listing">
			<tr>
				<td colspan="6" class="headseparator">&nbsp;</td>
			</tr>
			<tr class="heading" valign="bottom">
				<td width="5%">&nbsp;</td>
				<td width="5%">&nbsp;</td>
				<td width="20%"><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "issue.issue"), $this);?>
</td>
				<td width="40%"><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "article.title"), $this);?>
</td>
				<td width="20%"><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "article.authors"), $this);?>
</td>
				<td width="10%"><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "common.status"), $this);?>
</td>
			</tr>
			<tr>
				<td colspan="6" class="headseparator">&nbsp;</td>
			</tr>

			<?php $this->assign('noObjects', true); ?>
			<?php $_from = $this->_tpl_vars['articles']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['articleData']):
?>
				<?php $this->assign('article', $this->_tpl_vars['articleData']['article']); ?>
				<?php if ($this->_tpl_vars['article']->getPubId('doi')): ?>
					<?php $this->assign('noObjects', false); ?>
					<?php $this->assign('issue', $this->_tpl_vars['articleData']['issue']); ?>
					<tr valign="top">
						<td><input type="checkbox" name="articleId[]" value="<?php echo $this->_tpl_vars['article']->getId(); ?>
" checked="checked" /></td>
						<td><?php echo $this->_plugins['function']['fieldLabel'][0][0]->smartyFieldLabel(array('name' => "articleId[]",'key' => "article.article"), $this);?>
</td>
						<td><a href="<?php echo $this->_plugins['function']['url'][0][0]->smartyUrl(array('page' => 'issue','op' => 'view','path' => $this->_tpl_vars['issue']->getId()), $this);?>
" class="action"><?php echo ((is_array($_tmp=$this->_tpl_vars['issue']->getIssueIdentification())) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
</a></td>
						<td><a href="<?php echo $this->_plugins['function']['url'][0][0]->smartyUrl(array('page' => 'article','op' => 'view','path' => $this->_tpl_vars['article']->getId()), $this);?>
" class="action"><?php echo ((is_array($_tmp=$this->_tpl_vars['article']->getLocalizedTitle())) ? $this->_run_mod_handler('strip_unsafe_html', true, $_tmp) : String::stripUnsafeHtml($_tmp)); ?>
</a></td>
						<td><?php echo ((is_array($_tmp=$this->_tpl_vars['article']->getAuthorString())) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
</td>
						<td><?php if (((is_array($_tmp=$this->_tpl_vars['article']->getData($this->_tpl_vars['depositStatusUrlSettingName']))) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp))): ?>
							<a href="https://api.crossref.org<?php echo ((is_array($_tmp=$this->_tpl_vars['article']->getData($this->_tpl_vars['depositStatusUrlSettingName']))) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
" target="_blank"><?php echo ((is_array($_tmp=$this->_tpl_vars['article']->getData($this->_tpl_vars['depositStatusSettingName']))) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
</a>
						<?php else: ?>
							-
						<?php endif; ?>
						</td>
					</tr>
					<tr>
						<td colspan="6" class="separator">&nbsp;</td>
					</tr>
				<?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
			<?php if ($this->_tpl_vars['noObjects']): ?>
				<tr>
					<td colspan="6" class="nodata"><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "plugins.importexport.crossref.export.noUnregistered"), $this);?>
</td>
				</tr>
				<tr>
					<td colspan="6" class="endseparator">&nbsp;</td>
				</tr>
			<?php endif; ?>
		</table>
		<p>
			<?php if (! empty ( $this->_tpl_vars['testMode'] )): ?><input type="hidden" name="testMode" value="1" /><?php endif; ?>
			<?php if ($this->_tpl_vars['hasCredentials']): ?>
				<input type="submit" name="register" value="<?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "plugins.importexport.common.register"), $this);?>
" title="<?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "plugins.importexport.common.registerDescription.multi"), $this);?>
" class="button defaultButton"/>
				&nbsp;
				<input type="submit" name="markRegistered" value="<?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "plugins.importexport.common.markRegistered"), $this);?>
" title="<?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "plugins.importexport.common.markRegisteredDescription"), $this);?>
" class="button"/>
				&nbsp;
			<?php endif; ?>
			<input type="submit" name="export" value="<?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "common.export"), $this);?>
" title="<?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "plugins.importexport.common.exportDescription"), $this);?>
" class="button<?php if (! $this->_tpl_vars['hasCredentials']): ?>  defaultButton<?php endif; ?>"/>
			&nbsp;
			<input type="button" value="<?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "common.selectAll"), $this);?>
" class="button" onclick="toggleChecked()" />
		</p>
		<p>
			<?php if ($this->_tpl_vars['hasCredentials']): ?>
				<?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "plugins.importexport.common.register.warning"), $this);?>

			<?php else: ?>
				<?php ob_start(); ?><?php echo $this->_plugins['function']['plugin_url'][0][0]->smartyPluginUrl(array('path' => 'settings'), $this);?>
<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('settingsUrl', ob_get_contents());ob_end_clean(); ?>
				<?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "plugins.importexport.common.register.noCredentials",'settingsUrl' => $this->_tpl_vars['settingsUrl']), $this);?>

			<?php endif; ?>
		</p>
	</form>
</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>