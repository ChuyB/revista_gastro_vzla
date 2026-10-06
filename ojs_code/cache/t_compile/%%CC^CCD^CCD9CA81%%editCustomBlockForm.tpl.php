<?php /* Smarty version 2.6.26, created on 2016-04-20 20:56:33
         compiled from file:/var/www/revistag/plugins/generic/customBlockManager/editCustomBlockForm.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'plugin_url', 'file:/var/www/revistag/plugins/generic/customBlockManager/editCustomBlockForm.tpl', 14, false),array('function', 'fieldLabel', 'file:/var/www/revistag/plugins/generic/customBlockManager/editCustomBlockForm.tpl', 20, false),array('function', 'form_language_chooser', 'file:/var/www/revistag/plugins/generic/customBlockManager/editCustomBlockForm.tpl', 23, false),array('function', 'translate', 'file:/var/www/revistag/plugins/generic/customBlockManager/editCustomBlockForm.tpl', 24, false),array('modifier', 'assign', 'file:/var/www/revistag/plugins/generic/customBlockManager/editCustomBlockForm.tpl', 22, false),array('modifier', 'escape', 'file:/var/www/revistag/plugins/generic/customBlockManager/editCustomBlockForm.tpl', 35, false),)), $this); ?>
<?php $this->assign('pageTitle', "plugins.generic.customBlock.editContent"); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br />
<form method="post" id="editCustomBlockForm" action="<?php echo $this->_plugins['function']['plugin_url'][0][0]->smartyPluginUrl(array('path' => 'save'), $this);?>
" >
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/formErrors.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php if (count ( $this->_tpl_vars['formLocales'] ) > 1): ?>
<div id="locales">
<table width="100%" class="data">
	<tr valign="top">
		<td width="20%" class="label"><?php echo $this->_plugins['function']['fieldLabel'][0][0]->smartyFieldLabel(array('name' => 'formLocale','key' => "form.formLanguage"), $this);?>
</td>
		<td width="80%" class="value">
			<?php echo ((is_array($_tmp=$this->_plugins['function']['plugin_url'][0][0]->smartyPluginUrl(array('page' => 'manager','op' => 'plugin','path' => 'edit','escape' => false), $this))) ? $this->_run_mod_handler('assign', true, $_tmp, 'editCustomBlockFormUrl') : $this->_plugins['modifier']['assign'][0][0]->smartyAssign($_tmp, 'editCustomBlockFormUrl'));?>

			<?php echo $this->_plugins['function']['form_language_chooser'][0][0]->smartyFormLanguageChooser(array('form' => 'editCustomBlockForm','url' => $this->_tpl_vars['editCustomBlockFormUrl']), $this);?>

			<span class="instruct"><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "form.formLanguage.description"), $this);?>
</span>
		</td>
	</tr>
</table>
</div>
<?php endif; ?>
<br />
<table class="data" width="100%">
	<tr>
		<td width="20%" class="label" valign="top"><?php echo $this->_plugins['function']['fieldLabel'][0][0]->smartyFieldLabel(array('required' => 'true','name' => 'blockContent','key' => "plugins.generic.customBlock.content"), $this);?>
</td>
		<td>
		<textarea name="blockContent[<?php echo ((is_array($_tmp=$this->_tpl_vars['formLocale'])) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
]" cols="30" rows="30"><?php echo ((is_array($_tmp=$this->_tpl_vars['blockContent'][$this->_tpl_vars['formLocale']])) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
</textarea>
		</td>
	</tr>
</table>

<p><input type="submit" value="<?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "common.save"), $this);?>
" class="button defaultButton" />
<input type="button" value="<?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "common.cancel"), $this);?>
" class="button" onclick="document.location.href='<?php echo $this->_plugins['function']['plugin_url'][0][0]->smartyPluginUrl(array('path' => 'settings'), $this);?>
'" /></p>

</form>

<p><span class="formRequired"><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "common.requiredField"), $this);?>
</span></p>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>