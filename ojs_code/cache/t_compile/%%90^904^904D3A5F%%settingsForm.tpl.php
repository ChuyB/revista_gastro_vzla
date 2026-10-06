<?php /* Smarty version 2.6.26, created on 2016-04-20 20:49:57
         compiled from file:/var/www/revistag/plugins/generic/customBlockManager/settingsForm.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'url', 'file:/var/www/revistag/plugins/generic/customBlockManager/settingsForm.tpl', 14, false),array('function', 'translate', 'file:/var/www/revistag/plugins/generic/customBlockManager/settingsForm.tpl', 16, false),array('function', 'plugin_url', 'file:/var/www/revistag/plugins/generic/customBlockManager/settingsForm.tpl', 21, false),array('modifier', 'assign', 'file:/var/www/revistag/plugins/generic/customBlockManager/settingsForm.tpl', 14, false),array('modifier', 'escape', 'file:/var/www/revistag/plugins/generic/customBlockManager/settingsForm.tpl', 26, false),)), $this); ?>
<?php $this->assign('pageTitle', "plugins.generic.customBlockManager.displayName"); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php echo ((is_array($_tmp=$this->_plugins['function']['url'][0][0]->smartyUrl(array('page' => 'manager','op' => 'plugins'), $this))) ? $this->_run_mod_handler('assign', true, $_tmp, 'sysPluginsUrl') : $this->_plugins['modifier']['assign'][0][0]->smartyAssign($_tmp, 'sysPluginsUrl'));?>

<?php echo ((is_array($_tmp=$this->_plugins['function']['url'][0][0]->smartyUrl(array('page' => 'manager','op' => 'setup','path' => '5'), $this))) ? $this->_run_mod_handler('assign', true, $_tmp, 'setup56') : $this->_plugins['modifier']['assign'][0][0]->smartyAssign($_tmp, 'setup56'));?>

<?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "plugin.generic.customBlockManager.introduction",'systemPluginsUrl' => $this->_tpl_vars['sysPluginsUrl'],'setupStep56' => $this->_tpl_vars['setup56']), $this);?>


<br />
<br />

<form method="post" action="<?php echo $this->_plugins['function']['plugin_url'][0][0]->smartyPluginUrl(array('path' => 'settings'), $this);?>
">

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/formErrors.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br />

<input type="hidden" name="deletedBlocks" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['deletedBlocks'])) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
" />

<table width="100%" class="listing">
	<tr>
		<td colspan="4" class="headseparator">&nbsp;</td>
	</tr>
	<tr class="heading" valign="bottom">
	<td width="20%" align="left"><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "plugins.generic.customBlockManager.blockName"), $this);?>
</td>
	<td width="80%" align="left" ><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "plugins.generic.customBlockManager.action"), $this);?>
</td>
	</tr>
	<tr>
		<td colspan="4" class="headseparator">&nbsp;</td>
	</tr>
	
<?php $_from = $this->_tpl_vars['blocks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['blocks'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['blocks']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['blockIndex'] => $this->_tpl_vars['block']):
        $this->_foreach['blocks']['iteration']++;
?>
<tr valign="top">
	<td width="20%" class="value" align="left"><input type="text" class="textField" name="blocks[<?php echo ((is_array($_tmp=$this->_tpl_vars['blockIndex'])) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
]" id="blocks-<?php echo ((is_array($_tmp=$this->_tpl_vars['blockIndex'])) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['block'])) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
" size="20" maxlength="40" /></td>
	<td  align="left"><input type="submit" name="delBlock[<?php echo ((is_array($_tmp=$this->_tpl_vars['blockIndex'])) ? $this->_run_mod_handler('escape', true, $_tmp) : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp)); ?>
]" value="<?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "plugins.generic.customBlockManager.delete"), $this);?>
" class="button" /></td>
</tr>
<tr>
	<td colspan="4" class="separator">&nbsp;</td>
</tr>
<?php endforeach; else: ?>
<tr valign="top">
	<td width="20%" class="value" align="right"><input type="text" class="textField" name="blocks[0]" id="blocks-0" size="20" maxlength="40" /></td>
</tr>

<?php endif; unset($_from); ?>
</table>
<p><input type="submit" class="button" name="addBlock" value="<?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "plugins.generic.customBlockManager.addBlock"), $this);?>
" />
<input type="submit" class="button" name="save" value="<?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "common.save"), $this);?>
" />
<input type="button" value="<?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "common.cancel"), $this);?>
" class="button" onclick="document.location.href='<?php echo $this->_plugins['function']['url'][0][0]->smartyUrl(array('page' => 'manager','op' => 'plugins','escape' => false), $this);?>
'" /></p>
</form>

<p><span class="formRequired"><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "common.requiredField"), $this);?>
</span></p>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>