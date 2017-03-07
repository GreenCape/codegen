<?template scope="entity"?>
{% if entity.role == 'lookup' and entity.special.key %}
<?php
/**
 * {{ project.title }} {{ entity.name | title }} Admin Form Template
 *
 * PHP version 5.3
 *
{# align("\t", '  ') #}
 * @version    {{ project.version }}
 * @package    {{ project.name | class }}
{% for author in project.authors %}
 * @author     {{ author.name }} <{{ author.email | lower }}>
{% endfor %}
 * @copyright  Copyright (C){{ "now" | date('Y') }} {{ project.copyright }}. All rights reserved.
 * @license    {{ project.license }}
{# endalign #}
 */

// No direct access
defined('_JEXEC') or die;

/** @var {{ project.name | class }}AdminView{{ entity.name | class }} $this */

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_{{ project.name | file }}/assets/css/{{ project.name | file }}.css');

$action = 'index.php?option=com_{{ project.name | file }}&layout=modal&tmpl=component&id=' . (int) $this->item->get('{{ entity.special.key.name }}');
$legend = intval($this->item->get('{{ entity.special.key.name }}')) == 0 ? JText::_('COM_{{ project.name | constant }}_{{ entity.name | constant }}_NEW') : JText::sprintf('COM_{{ project.name | constant }}_{{ entity.name | constant }}_EDIT', $this->item->get('{{ entity.special.key.name }}'))
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == '{{ entity.name | singular | file }}.cancel' || document.formvalidator.isValid(document.id('{{ entity.name | singular | file }}-form')))
		{
			Joomla.submitform(task, document.getElementById('{{ entity.name | singular | file }}-form'));
		}
		else
		{
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_($action); ?>" method="post" name="{{ entity.name | singular | file }}Form" id="{{ entity.name | singular | file }}-form" class="form-validate">
	<fieldset class="adminform">
		<legend><?php echo $legend; ?></legend>
		<ul class="adminformlist">
			<?php foreach ($this->form->getFieldset('details') as $field) : ?>
				<li>
					<?php echo $field->hidden ? '' : $field->label; ?>
					<?php echo $field->input; ?>
				</li>
			<?php endforeach; ?>
			<?php foreach ($this->form->getFieldset('params') as $field) : ?>
				<li>
					<?php echo $field->hidden ? '' : $field->label; ?>
					<?php echo $field->input; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</fieldset>

	<div class="clr"></div>
	<a class="toolbar" onclick="Joomla.submitbutton('{{ entity.name | singular | file }}.save')" href="#">
		Save
	</a>
	<a class="toolbar" onclick="Joomla.submitbutton('{{ entity.name | singular | file }}.cancel')" href="#">
		Cancel
	</a>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="return" value="<?php echo JFactory::getApplication()->input->getCmd('return', null); ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
{% endif %}
