<?php /* Smarty version 2.6.26, created on 2017-10-09 03:25:57
         compiled from controllers/statistics/form/reportGeneratorForm.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'url', 'controllers/statistics/form/reportGeneratorForm.tpl', 16, false),array('function', 'fbvElement', 'controllers/statistics/form/reportGeneratorForm.tpl', 56, false),array('function', 'html_select_date', 'controllers/statistics/form/reportGeneratorForm.tpl', 85, false),array('function', 'translate', 'controllers/statistics/form/reportGeneratorForm.tpl', 96, false),array('function', 'fbvFormButtons', 'controllers/statistics/form/reportGeneratorForm.tpl', 184, false),array('modifier', 'escape', 'controllers/statistics/form/reportGeneratorForm.tpl', 36, false),array('modifier', 'translate', 'controllers/statistics/form/reportGeneratorForm.tpl', 158, false),array('block', 'fbvFormArea', 'controllers/statistics/form/reportGeneratorForm.tpl', 54, false),array('block', 'fbvFormSection', 'controllers/statistics/form/reportGeneratorForm.tpl', 55, false),)), $this); ?>
<script type="text/javascript">
	$(function() {
		// Attach the form handler.
		$('#reportGeneratorForm').pkpHandler('$.pkp.statistics.ReportGeneratorFormHandler',
			{
				fetchFormUrl: '<?php echo $this->_plugins['function']['url'][0][0]->smartyUrl(array('op' => 'fetchReportGenerator'), $this);?>
',
				metricTypeSelectSelector: '#metricType',
				reportTemplateSelectSelector: '#reportTemplate',
				aggregationOptionsSelector: "input[type='checkbox'], #aggregationColumns",
				columnsSelector: '#columns', 
				timeFilterWrapperSelector: '#reportTimeFilterArea',
				currentMonthSelector: '#currentMonth',
				yesterdaySelector: '#yesterday',
				rangeByMonthSelector: '#rangeByMonth',
				rangeByDaySelector: '#rangeByDay',
				startDayElementSelector: "select[name='dateStartDay']",
				endDayElementSelector: "select[name='dateEndDay']",
				dateRangeWrapperSelector : '#dateRangeElementsWrapper',
				fetchArticlesUrl: '<?php echo $this->_plugins['function']['url'][0][0]->smartyUrl(array('op' => 'fetchArticlesInfo'), $this);?>
',
				articleSelectSelector: '#articles',
				issueSelectSelector: '#issues',
				objectTypeSelectSelector: '#objectTypes',
				fileTypeSelectSelector: '#fileTypes',
				fileAssocTypes: {
					<?php $_from = $this->_tpl_vars['fileAssocTypes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['assocType']):
?>
						<?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp, 'javascript')); ?>
: '<?php echo ((is_array($_tmp=$this->_tpl_vars['assocType'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp, 'javascript')); ?>
',
					<?php endforeach; endif; unset($_from); ?>
				},
				fetchRegionsUrl: '<?php echo $this->_plugins['function']['url'][0][0]->smartyUrl(array('op' => 'fetchRegions'), $this);?>
',
				regionSelectSelector: '#regions',
				countrySelectSelector: '#countries',
				optionalColumns: {
					<?php $_from = $this->_tpl_vars['optionalColumns']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['column']):
?>
						<?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp, 'javascript')); ?>
: '<?php echo ((is_array($_tmp=$this->_tpl_vars['column'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : $this->_plugins['modifier']['escape'][0][0]->smartyEscape($_tmp, 'javascript')); ?>
',
					<?php endforeach; endif; unset($_from); ?>
				}
			}
		);
	});
</script>

<form class="pkp_form" id="reportGeneratorForm" method="post" action="<?php echo $this->_plugins['function']['url'][0][0]->smartyUrl(array('op' => 'saveReportGenerator'), $this);?>
">
	<?php if ($this->_tpl_vars['metricTypeOptions']): ?>
		<?php $this->_tag_stack[] = array('fbvFormArea', array('id' => 'columnsFormArea','title' => "defaultMetric.availableMetrics")); $_block_repeat=true;$this->_plugins['block']['fbvFormArea'][0][0]->smartyFBVFormArea($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<?php $this->_tag_stack[] = array('fbvFormSection', array('inline' => true,'size' => $this->_tpl_vars['fbvStyles']['size']['SMALL'])); $_block_repeat=true;$this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
				<?php echo $this->_plugins['function']['fbvElement'][0][0]->smartyFBVElement(array('type' => 'select','name' => 'metricType','id' => 'metricType','from' => $this->_tpl_vars['metricTypeOptions'],'selected' => $this->_tpl_vars['metricType'],'translate' => false), $this);?>

			<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fbvFormArea'][0][0]->smartyFBVFormArea($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
	<?php else: ?>
		<?php echo $this->_plugins['function']['fbvElement'][0][0]->smartyFBVElement(array('type' => 'hidden','name' => 'metricType','id' => 'metricType','value' => $this->_tpl_vars['metricType']), $this);?>

	<?php endif; ?>
	
	<?php if ($this->_tpl_vars['reportTemplateOptions']): ?>
		<?php $this->_tag_stack[] = array('fbvFormArea', array('id' => 'reportTemplatesFormArea','title' => "manager.statistics.reports.defaultReportTemplates")); $_block_repeat=true;$this->_plugins['block']['fbvFormArea'][0][0]->smartyFBVFormArea($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<?php $this->_tag_stack[] = array('fbvFormSection', array('inline' => true,'size' => $this->_tpl_vars['fbvStyles']['size']['SMALL'])); $_block_repeat=true;$this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
				<?php echo $this->_plugins['function']['fbvElement'][0][0]->smartyFBVElement(array('type' => 'select','name' => 'reportTemplate','id' => 'reportTemplate','from' => $this->_tpl_vars['reportTemplateOptions'],'selected' => $this->_tpl_vars['reportTemplate'],'translate' => false), $this);?>

			<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
			<?php $this->_tag_stack[] = array('fbvFormSection', array('for' => 'aggregationColumns','label' => "manager.statistics.reports.aggregationColumns",'description' => "manager.statistics.reports.optionalColumns.description",'list' => true)); $_block_repeat=true;$this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
				<?php echo $this->_plugins['function']['fbvElement'][0][0]->smartyFBVElement(array('type' => 'checkboxgroup','name' => 'aggregationColumns','id' => 'aggregationColumns','from' => $this->_tpl_vars['aggregationOptions'],'selected' => $this->_tpl_vars['selectedAggregationOptions'],'translate' => false), $this);?>

			<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fbvFormArea'][0][0]->smartyFBVFormArea($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['showMonthInputs'] || $this->_tpl_vars['showDayInputs']): ?>
		<?php $this->_tag_stack[] = array('fbvFormArea', array('id' => 'reportTimeFilterArea','title' => "manager.statistics.reports.filters.byTime")); $_block_repeat=true;$this->_plugins['block']['fbvFormArea'][0][0]->smartyFBVFormArea($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>			
			<?php $this->_tag_stack[] = array('fbvFormSection', array('for' => 'currentMonth','size' => $this->_tpl_vars['fbvStyles']['size']['SMALL'],'list' => true)); $_block_repeat=true;$this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
				<?php echo $this->_plugins['function']['fbvElement'][0][0]->smartyFBVElement(array('type' => 'radio','name' => 'timeFilterOption','value' => @TIME_FILTER_OPTION_YESTERDAY,'id' => 'yesterday','checked' => $this->_tpl_vars['yesterday'],'label' => "manager.statistics.reports.yesterday"), $this);?>

				<?php echo $this->_plugins['function']['fbvElement'][0][0]->smartyFBVElement(array('type' => 'radio','name' => 'timeFilterOption','value' => @TIME_FILTER_OPTION_CURRENT_MONTH,'id' => 'currentMonth','checked' => $this->_tpl_vars['currentMonth'],'label' => "manager.statistics.reports.currentMonth"), $this);?>

			<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
			<?php $this->_tag_stack[] = array('fbvFormSection', array('title' => "manager.statistics.reports.filters.byTime.dimensionSelector",'list' => true,'size' => $this->_tpl_vars['fbvStyles']['size']['SMALL'],'inline' => true)); $_block_repeat=true;$this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
				<?php echo $this->_plugins['function']['fbvElement'][0][0]->smartyFBVElement(array('type' => 'radio','name' => 'timeFilterOption','value' => @TIME_FILTER_OPTION_RANGE_DAY,'id' => 'rangeByDay','inline' => true,'checked' => $this->_tpl_vars['byDay'],'label' => "common.day"), $this);?>

				<?php echo $this->_plugins['function']['fbvElement'][0][0]->smartyFBVElement(array('type' => 'radio','name' => 'timeFilterOption','value' => @TIME_FILTER_OPTION_RANGE_MONTH,'id' => 'rangeByMonth','checked' => $this->_tpl_vars['byMonth'],'label' => "common.month"), $this);?>

			<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
			<div id="dateRangeElementsWrapper">
				<?php $this->_tag_stack[] = array('fbvFormSection', array('title' => "search.dateFrom",'inline' => true,'size' => $this->_tpl_vars['fbvStyles']['size']['SMALL'])); $_block_repeat=true;$this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
					<?php echo smarty_function_html_select_date(array('prefix' => 'dateStart','time' => $this->_tpl_vars['dateStart'],'start_year' => $this->_tpl_vars['timeFilterStartYear'],'all_extra' => "class=\"selectMenu\"",'end_year' => $this->_tpl_vars['timeFilterEndYear'],'field_order' => 'YMD'), $this);?>

				<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
				<?php $this->_tag_stack[] = array('fbvFormSection', array('title' => "search.dateTo",'inline' => true,'size' => $this->_tpl_vars['fbvStyles']['size']['SMALL'])); $_block_repeat=true;$this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
					<?php echo smarty_function_html_select_date(array('prefix' => 'dateEnd','time' => $this->_tpl_vars['dateEnd'],'start_year' => $this->_tpl_vars['timeFilterStartYear'],'all_extra' => "class=\"selectMenu\"",'end_year' => $this->_tpl_vars['timeFilterEndYear'],'field_order' => 'YMD'), $this);?>

				<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
			</div>
		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fbvFormArea'][0][0]->smartyFBVFormArea($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
	<?php endif; ?>
	
	<?php ob_start(); ?>
		<?php $this->_tag_stack[] = array('fbvFormArea', array('id' => 'columnsFormArea','title' => "manager.statistics.reports.columns")); $_block_repeat=true;$this->_plugins['block']['fbvFormArea'][0][0]->smartyFBVFormArea($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<p><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "manager.statistics.reports.columns.description"), $this);?>
</p>
			<?php $this->_tag_stack[] = array('fbvFormSection', array('description' => "manager.statistics.reports.optionalColumns.description",'inline' => true,'size' => $this->_tpl_vars['fbvStyles']['size']['MEDIUM'])); $_block_repeat=true;$this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
				<?php echo $this->_plugins['function']['fbvElement'][0][0]->smartyFBVElement(array('type' => 'select','name' => "columns[]",'id' => 'columns','from' => $this->_tpl_vars['columnsOptions'],'multiple' => 'multiple','selected' => $this->_tpl_vars['columns'],'translate' => false,'required' => true), $this);?>

			<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fbvFormArea'][0][0]->smartyFBVFormArea($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		
		<?php $this->_tag_stack[] = array('fbvFormArea', array('id' => 'filterFormArea','title' => "manager.statistics.reports.filters")); $_block_repeat=true;$this->_plugins['block']['fbvFormArea'][0][0]->smartyFBVFormArea($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<?php if ($this->_tpl_vars['issuesOptions']): ?>
				<?php $this->_tag_stack[] = array('fbvFormSection', array('label' => "manager.statistics.reports.filters.byContext")); $_block_repeat=true;$this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
					<p><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "manager.statistics.reports.filters.byContext.description"), $this);?>
</p>
					<?php $this->_tag_stack[] = array('fbvFormSection', array('description' => "issue.issues",'for' => 'issues','inline' => true,'size' => $this->_tpl_vars['fbvStyles']['size']['MEDIUM'])); $_block_repeat=true;$this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
						<?php echo $this->_plugins['function']['fbvElement'][0][0]->smartyFBVElement(array('type' => 'select','name' => "issues[]",'id' => 'issues','from' => $this->_tpl_vars['issuesOptions'],'multiple' => 'multiple','selected' => $this->_tpl_vars['issues'],'translate' => false), $this);?>

					<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
					<?php if ($this->_tpl_vars['showArticleInput']): ?>
						<?php $this->_tag_stack[] = array('fbvFormSection', array('description' => "article.articles",'for' => 'articles','inline' => true,'size' => $this->_tpl_vars['fbvStyles']['size']['MEDIUM'])); $_block_repeat=true;$this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
							<?php echo $this->_plugins['function']['fbvElement'][0][0]->smartyFBVElement(array('type' => 'select','name' => "articles[]",'id' => 'articles','multiple' => 'multiple','translate' => false), $this);?>

						<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
					<?php endif; ?>
				<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
			<?php endif; ?>
			
			<?php $this->_tag_stack[] = array('fbvFormSection', array('label' => "manager.statistics.reports.filters.byObject")); $_block_repeat=true;$this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
				<p><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "manager.statistics.reports.filters.byObject.description"), $this);?>
</p>
				<?php $this->_tag_stack[] = array('fbvFormSection', array('description' => "manager.statistics.reports.objectType",'for' => 'objectTypes','inline' => true,'size' => $this->_tpl_vars['fbvStyles']['size']['SMALL'])); $_block_repeat=true;$this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
					<?php echo $this->_plugins['function']['fbvElement'][0][0]->smartyFBVElement(array('type' => 'select','name' => "objectTypes[]",'id' => 'objectTypes','from' => $this->_tpl_vars['objectTypesOptions'],'multiple' => 'multiple','selected' => $this->_tpl_vars['objectTypes'],'translate' => false), $this);?>

				<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
				<?php if ($this->_tpl_vars['fileTypesOptions']): ?>
					<?php $this->_tag_stack[] = array('fbvFormSection', array('description' => "common.fileType",'for' => 'fileTypes','inline' => true,'size' => $this->_tpl_vars['fbvStyles']['size']['SMALL'])); $_block_repeat=true;$this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
						<?php echo $this->_plugins['function']['fbvElement'][0][0]->smartyFBVElement(array('type' => 'select','name' => "fileTypes[]",'id' => 'fileTypes','from' => $this->_tpl_vars['fileTypesOptions'],'multiple' => 'multiple','selected' => $this->_tpl_vars['fileTypes'],'translate' => false), $this);?>

					<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
				<?php endif; ?>
				
				<?php $this->_tag_stack[] = array('fbvFormSection', array('description' => "manager.statistics.reports.objectId",'for' => 'objectIds','inline' => true,'size' => $this->_tpl_vars['fbvStyles']['size']['SMALL'])); $_block_repeat=true;$this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
					<?php echo $this->_plugins['function']['fbvElement'][0][0]->smartyFBVElement(array('type' => 'text','name' => 'objectIds','id' => 'objectIds','value' => $this->_tpl_vars['objectIds'],'label' => "manager.statistics.reports.objectId.label"), $this);?>

				<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
			<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
				
			<?php if ($this->_tpl_vars['countriesOptions']): ?>
				<?php $this->_tag_stack[] = array('fbvFormSection', array('label' => "manager.statistics.reports.filters.byLocation")); $_block_repeat=true;$this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
					<p><?php echo $this->_plugins['function']['translate'][0][0]->smartyTranslate(array('key' => "manager.statistics.reports.filters.byLocation.description"), $this);?>
</p>
					<?php $this->_tag_stack[] = array('fbvFormSection', array('description' => "common.country",'for' => 'countries','inline' => true,'size' => $this->_tpl_vars['fbvStyles']['size']['SMALL'])); $_block_repeat=true;$this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
						<?php echo $this->_plugins['function']['fbvElement'][0][0]->smartyFBVElement(array('type' => 'select','name' => "countries[]",'id' => 'countries','from' => $this->_tpl_vars['countriesOptions'],'multiple' => 'multiple','selected' => $this->_tpl_vars['countries'],'translate' => false), $this);?>

					<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
					<?php if ($this->_tpl_vars['showRegionInput']): ?>
						<?php $this->_tag_stack[] = array('fbvFormSection', array('description' => "manager.statistics.region",'for' => 'regions','inline' => true,'size' => $this->_tpl_vars['fbvStyles']['size']['SMALL'])); $_block_repeat=true;$this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
							<?php echo $this->_plugins['function']['fbvElement'][0][0]->smartyFBVElement(array('type' => 'select','name' => "regions[]",'id' => 'regions','from' => $this->_tpl_vars['regionsOptions'],'multiple' => 'multiple','selected' => $this->_tpl_vars['regions'],'translate' => false), $this);?>

						<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
					<?php endif; ?>
					<?php if ($this->_tpl_vars['showCityInput']): ?>
						<?php $this->_tag_stack[] = array('fbvFormSection', array('description' => "manager.statistics.city",'for' => 'cityNames','inline' => true,'size' => $this->_tpl_vars['fbvStyles']['size']['MEDIUM'])); $_block_repeat=true;$this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
							<?php echo $this->_plugins['function']['fbvElement'][0][0]->smartyFBVElement(array('type' => 'text','name' => 'cityNames','id' => 'cityNames','value' => $this->_tpl_vars['cityNames'],'label' => "manager.statistics.reports.cities.label"), $this);?>

						<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
					<?php endif; ?>
				<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
			<?php endif; ?>
			
		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fbvFormArea'][0][0]->smartyFBVFormArea($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		
		<?php $this->_tag_stack[] = array('fbvFormArea', array('id' => 'orderByFormArea','title' => "manager.statistics.reports.orderBy")); $_block_repeat=true;$this->_plugins['block']['fbvFormArea'][0][0]->smartyFBVFormArea($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<?php $this->_tag_stack[] = array('fbvFormSection', array('description' => "manager.statistics.reports.optionalColumns.description")); $_block_repeat=true;$this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
				<?php $_from = $this->_tpl_vars['orderColumnsOptions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
					<?php $this->_tag_stack[] = array('fbvFormSection', array('inline' => true,'size' => $this->_tpl_vars['fbvStyles']['size']['SMALL'])); $_block_repeat=true;$this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
						<?php echo $this->_plugins['function']['fbvElement'][0][0]->smartyFBVElement(array('type' => 'select','name' => "orderByColumn[]",'id' => "orderByColumn-".($this->_tpl_vars['key']),'from' => $this->_tpl_vars['orderColumnsOptions'],'defaultValue' => 0,'defaultLabel' => ((is_array($_tmp="manager.statistics.reports.columns")) ? $this->_run_mod_handler('translate', true, $_tmp) : AppLocale::translate($_tmp)),'selected' => $this->_tpl_vars['orderByColumn'],'translate' => false), $this);?>

					<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
					<?php $this->_tag_stack[] = array('fbvFormSection', array('inline' => true,'size' => $this->_tpl_vars['fbvStyles']['size']['SMALL'])); $_block_repeat=true;$this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
						<?php echo $this->_plugins['function']['fbvElement'][0][0]->smartyFBVElement(array('type' => 'select','name' => "orderByDirection[]",'id' => "orderByDirection-".($this->_tpl_vars['key']),'from' => $this->_tpl_vars['orderDirectionsOptions'],'defaultValue' => 0,'defaultLabel' => ((is_array($_tmp="manager.statistics.reports.orderDir")) ? $this->_run_mod_handler('translate', true, $_tmp) : AppLocale::translate($_tmp)),'selected' => $this->_tpl_vars['orderByDirection'],'translate' => false), $this);?>

					<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
					<div style="clear:both"></div>
				<?php endforeach; endif; unset($_from); ?>
			<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fbvFormArea'][0][0]->smartyFBVFormArea($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
	<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('advancedOptionsContent', ob_get_contents());ob_end_clean(); ?>
	
	<div id="advancedOptionsWrapper" class="left full">
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "controllers/extrasOnDemand.tpl", 'smarty_include_vars' => array('id' => 'advancedOptionsExtras','widgetWrapper' => "#advancedOptionsWrapper",'moreDetailsText' => "manager.statistics.reports.advancedOptions",'moreDetailsLabel' => "manager.statistics.reports.advancedOptions.label",'extraContent' => $this->_tpl_vars['advancedOptionsContent'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</div>
	
	<?php $this->_tag_stack[] = array('fbvFormArea', array('id' => 'reportUrlFormArea','title' => "manager.statistics.reports.reportUrl")); $_block_repeat=true;$this->_plugins['block']['fbvFormArea'][0][0]->smartyFBVFormArea($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		<?php $this->_tag_stack[] = array('fbvFormSection', array()); $_block_repeat=true;$this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<?php echo $this->_plugins['function']['fbvElement'][0][0]->smartyFBVElement(array('type' => 'text','name' => 'reportUrl','id' => 'reportUrl','value' => $this->_tpl_vars['reportUrl'],'label' => "manager.statistics.reports.reportUrl.label"), $this);?>

		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fbvFormSection'][0][0]->smartyFBVFormSection($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
	<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fbvFormArea'][0][0]->smartyFBVFormArea($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

	<?php echo $this->_plugins['function']['fbvFormButtons'][0][0]->smartyFBVFormButtons(array('id' => 'reportGeneratorFormSubmit','submitText' => "manager.statistics.reports.generateReport",'hideCancel' => true), $this);?>

</form>