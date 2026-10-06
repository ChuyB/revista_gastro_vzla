<?php /* Smarty version 2.6.26, created on 2019-03-01 21:55:43
         compiled from nlm30-ref-list.tpl */ ?>
<ref-list>
	<?php $_from = $this->_tpl_vars['citationsOutput']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['seq'] => $this->_tpl_vars['citationOutput']):
?>
		<ref id="B<?php echo $this->_tpl_vars['seq']; ?>
">
			<label><?php echo $this->_tpl_vars['seq']; ?>
</label>
			<?php echo $this->_tpl_vars['citationOutput']; ?>

		</ref>
	<?php endforeach; endif; unset($_from); ?>
</ref-list>