<?php /* Smarty version 2.6.26, created on 2026-04-02 05:32:22
         compiled from common/footer.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'translate', 'common/footer.tpl', 31, false),array('modifier', 'assign', 'common/footer.tpl', 31, false),)), $this); ?>

<div class="sponsors-footer-section">
	<h5 class="sponsors-title">NUESTROS PATROCINADORES</h5>
	<div class="sponsors-grid">
		<div class="sponsor-item"><a href="https://astormedic.com/" target="_blank"><img src="<?php echo $this->_tpl_vars['baseUrl']; ?>
/public/site/images/root/astor_logo.png" alt="Astor Medic" /></a></div>
		<div class="sponsor-item"><a href="https://www.laboratoriosfarma.com/" target="_blank"><img src="<?php echo $this->_tpl_vars['baseUrl']; ?>
/public/site/images/root/FARMA.png" alt="Farma" /></a></div>
		<div class="sponsor-item"><a href="https://www.laboratoriosvargas.com/" target="_blank"><img src="<?php echo $this->_tpl_vars['baseUrl']; ?>
/public/site/images/root/vargas_logo.png" alt="Laboratorios Vargas" /></a></div>
		<div class="sponsor-item"><a href="https://www.pharmetiquelabs.com/quienes-somos/" target="_blank"><img src="<?php echo $this->_tpl_vars['baseUrl']; ?>
/public/site/images/root/logo-Pharmetique.png" alt="Pharmetique" /></a></div>
		<div class="sponsor-item"><a href="https://www.ronava.com/" target="_blank"><img src="<?php echo $this->_tpl_vars['baseUrl']; ?>
/public/site/images/root/ronava_logo.png" alt="Ronava" /></a></div>
	</div>
</div>

<?php echo ''; ?><?php if ($this->_tpl_vars['pageFooter'] == ''): ?><?php echo ''; ?><?php if ($this->_tpl_vars['currentJournal'] && $this->_tpl_vars['currentJournal']->getSetting('onlineIssn')): ?><?php echo ''; ?><?php $this->assign('issn', $this->_tpl_vars['currentJournal']->getSetting('onlineIssn')); ?><?php echo ''; ?><?php elseif ($this->_tpl_vars['currentJournal'] && $this->_tpl_vars['currentJournal']->getSetting('printIssn')): ?><?php echo ''; ?><?php $this->assign('issn', $this->_tpl_vars['currentJournal']->getSetting('printIssn')); ?><?php echo ''; ?><?php endif; ?><?php echo ''; ?><?php if ($this->_tpl_vars['issn']): ?><?php echo ''; ?><?php echo ((is_array($_tmp=$this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "journal.issn"), $this))) ? $this->_run_mod_handler('assign', true, $_tmp, 'issnText') : $this->_plugins['modifier']['assign'][0][0]->smartyAssign($_tmp, 'issnText'));?><?php echo ''; ?><?php $this->assign('pageFooter', ($this->_tpl_vars['issnText']).": ".($this->_tpl_vars['issn'])); ?><?php echo ''; ?><?php endif; ?><?php echo ''; ?><?php endif; ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "core:common/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?>
