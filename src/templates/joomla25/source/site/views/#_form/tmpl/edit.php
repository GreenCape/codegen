<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' %}
<?php
/**
 * {{ entity.name | title }} Frontend Form Template
 *
 * PHP version 5.3
 *
{# align("\t", '  ') #}
 * @version    {{ project.version }}
 * @package    {{ project.name | class }}
 * @author     {{ author.name }} <{{ author.email | lower }}>
 * @copyright  Copyright (C){{ "now" | date('Y') }} {{ copyright }}. All rights reserved.
 * @license    {{ project.license }}
{# endalign #}
 */

// No direct access
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.calendar');
JHtml::_('behavior.formvalidation');

// Create shortcut to parameters.
$params = $this->state->get('params');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == '{{ entity.name | singular | file }}.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			Joomla.submitform(task);
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>
<div class="edit item-page<?php echo $this->pageclass_sfx; ?>">
	<form action="<?php echo JRoute::_('index.php?option=com_{{ project.name | file }}&{{ entity.special.key.name }}='.(int) $this->item->{{ entity.special.key.name }}); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">

		<?php if ($params->get('show_page_heading')) : ?>
		<h1>
			<?php echo $this->escape($params->get('page_heading')); ?>
		</h1>
		<?php endif; ?>

		<div class="formelm-buttons">
			<button type="button" onclick="Joomla.submitbutton('{{ entity.name | singular | file }}.save')">
				<?php echo JText::_('JSAVE') ?>
			</button>
			<button type="button" onclick="Joomla.submitbutton('{{ entity.name | singular | file }}.cancel')">
				<?php echo JText::_('JCANCEL') ?>
			</button>
		</div>

		<fieldset>

			<legend><?php echo JText::_('COM_{{ project.name | constant }}_{{ entity.name | constant }}_DETAILS'); ?></legend>
{% for property in entity.properties %}
{% if property.form == 'parameters' %}
{% elseif property.input == 'hidden' %}
{% elseif property.input == 'textarea' %}

			<div class="formelm-area">
				<?php echo $this->form->getLabel('{{ property.name | variable }}'); ?>
				<?php echo $this->form->getInput('{{ property.name | variable }}'); ?>
			</div>
{% elseif property.input == 'richtext' %}

			<div class="formelm-area html">
				<?php echo $this->form->getLabel('{{ property.name | variable }}'); ?>
				<?php echo $this->form->getInput('{{ property.name | variable }}'); ?>
			</div>
{% else %}

			<div class="formelm">
				<?php echo $this->form->getLabel('{{ property.name | variable }}'); ?>
				<?php echo $this->form->getInput('{{ property.name | variable }}'); ?>
			</div>
{% endif %}
{% endfor %}

		</fieldset>

		<?php
		/** @todo Fix publishing fieldset: don't show if empty */
		if (0) {
		?>
		<?php if ($this->item->params->get('access-change')): ?>
			<fieldset>
				<legend><?php echo JText::_('COM_CONTENT_PUBLISHING'); ?></legend>
{% for property in entity.properties %}
{% if property.form == 'parameters' %}
{% elseif property.input == 'hidden' %}
{% elseif property.input == 'textarea' %}

				<div class="formelm-area">
					<?php echo $this->form->getLabel('{{ property.name | variable }}'); ?>
					<?php echo $this->form->getInput('{{ property.name | variable }}'); ?>
				</div>
{% elseif property.input == 'richtext' %}

				<div class="formelm-area html">
					<?php echo $this->form->getLabel('{{ property.name | variable }}'); ?>
					<?php echo $this->form->getInput('{{ property.name | variable }}'); ?>
				</div>
{% else %}

				<div class="formelm">
					<?php echo $this->form->getLabel('{{ property.name | variable }}'); ?>
					<?php echo $this->form->getInput('{{ property.name | variable }}'); ?>
				</div>
{% endif %}
{% endfor %}

			</fieldset>
		<?php endif;?>
		<?php
		}
		?>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="return" value="<?php echo $this->return_page;?>" />
		<?php if($this->params->get('enable_category', 0) == 1) :?>
		<input type="hidden" name="jform[catid]" value="<?php echo $this->params->get('catid', 1);?>"/>
		<?php endif;?>
{% for property in entity.properties %}
{% if property.input == 'hidden' %}
		<?php echo $this->form->getInput('{{ property.name | variable }}'); ?>
{% endif %}
{% endfor %}

		<?php echo JHtml::_( 'form.token' ); ?>
	</form>
</div>
{% endif %}
