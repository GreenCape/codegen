<?template scope="entity"?>
{% if entity.special.key %}
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

if (intval($this->item->get('{{ entity.special.key.name }}')) == 0)
{
	$legend = JText::_('COM_{{ project.name | constant }}_{{ entity.name | constant }}_NEW');
}
else
{
	$legend = JText::sprintf('COM_{{ project.name | constant }}_{{ entity.name | constant }}_EDIT', $this->item->get('{{ entity.special.key.name }}'));
}
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

<form action="<?php echo JRoute::_('index.php?option=com_{{ project.name | file }}&layout=edit&id=' . (int) $this->item->get('{{ entity.special.key.name }}')); ?>"
	  method="post" name="adminForm" id="{{ entity.name | singular | file }}-form" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo $legend; ?></legend>
			<ul class="adminformlist">
				<?php foreach ($this->form->getFieldset('details') as $field) : ?>
					<li>
						<?php echo $field->hidden ? '' : $field->label; ?>
						<?php echo $field->input; ?>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="clr"></div>
		</fieldset>
	</div>

	<div class="width-40 fltrt">
		<?php echo JHtml::_('sliders.start', '{{ entity.name | singular | file }}-sliders-' . $this->item->get('{{ entity.special.key.name }}'), array('useCookie' => 1)); ?>

		<?php echo JHtml::_('sliders.panel', JText::_('COM_{{ project.name | constant }}_FIELDSET_PARAMS'), 'additional-params'); ?>

		<fieldset class="panelform">
			<ul class="adminformlist">
				<?php foreach ($this->form->getFieldset('params') as $field) : ?>
					<li>
						<?php echo $field->hidden ? '' : $field->label; ?>
						<?php echo $field->input; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</fieldset>

		<?php echo JHtml::_('sliders.end'); ?>
	</div>

	<div class="clr"></div>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="return" value="<?php echo JFactory::getApplication()->input->getCmd('return', null); ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
{% endif %}
