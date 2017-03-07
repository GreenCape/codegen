<?template scope="entity"?>
{% if entity.role != 'map' %}
<?php
/**
 * {{ project.title }} {{ entity.name | title }} Modal List Template
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

/** @var {{ project.name | class }}View{{ entity.name | plural | class }} $this  */

if (JFactory::getApplication()->isSite())
{
	JSession::checkToken('get') or die(JText::_('JINVALID_TOKEN'));
}

JHtml::_('behavior.tooltip');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_{{ project.name | file }}/assets/css/{{ project.name | file }}.css');

{% if entity.role != 'lookup' %}

$function  = JFactory::getApplication()->input->getCmd('function', null, 'jSelect{{ entity.name | class }}');
{% endif %}

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task, id) {
		form = document.getElementById('adminForm');
		if (id)
		{
			document.getElementById('cid').value = id;
			form.boxchecked.value = 1;
		}
		Joomla.submitform(task, document.getElementById('adminForm'));
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_{{ project.name | file }}&view={{ entity.name | plural | file }}&layout=modal&tmpl=component&{% if entity.role != 'lookup' %}function=' . $function . '&{% endif %}' . JSession::getFormToken() . '=1');?>"
	  method="post" name="adminForm" id="adminForm">
	<?php
	/** @todo Repair filter in modal popup and re-enable it */
	if (0) {
	?>
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
				   title="<?php echo JText::_('COM_{{ project.name | constant }}_{{ entity.name | constant }}_SEARCH_IN_TITLE'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
	</fieldset>
	<div class="clr"> </div>
	<?php
	}
	?>

	<table class="adminlist">
		<thead>
			<tr>
{% if entity.special.title or entity.dynName %}

				<th class="title" style="text-align:left;"><!-- 1 -->
					<?php echo JHtml::_('grid.sort', {% if entity.special.title %}'COM_{{ project.name | constant }}_{{ entity.name | constant }}_FIELD_{{ entity.special.title.name | constant }}_LABEL', '{{ entity.name }}.{{ entity.special.title.name | variable }}'{% else %}'COM_{{ project.name | constant }}_{{ entity.name | constant }}_ITEM', 'name'{% endif %}, $listDirn, $listOrder); ?>
				</th>
{% endif %}
{% for property in entity.listFields %}
{% if property.role == 'key' %}
{% elseif property.role == 'title' %}
{% elseif property.role == 'category' %}
{% else %}

				<th class="center"><!-- 2 -->
					<?php echo JHtml::_('grid.sort', 'COM_{{ project.name | constant }}_{{ entity.name | constant }}_FIELD_{{ property.name | constant }}_LABEL', '{{ entity.name }}.{{ property.name | variable }}', $listDirn, $listOrder); ?>
				</th>
{% endif %}
{% endfor %}
{% if entity.special.featured %}

				<th class="center" width="5%"><!-- 3 -->
					<?php echo JHtml::_('grid.sort', 'COM_{{ project.name | constant }}_{{ entity.name | constant }}_FIELD_{{ property.name | constant }}_LABEL', '{{ entity.name }}.{{ entity.special.featured.name }}', $listDirn, $listOrder); ?>
				</th>
{% endif %}
{% if entity.special.published %}

				<th class="center" width="5%"><!-- 4 -->
					<?php echo JHtml::_('grid.sort', 'JSTATUS', '{{ entity.name }}.{{ entity.special.published.name }}', $listDirn, $listOrder); ?>
				</th>
{% endif %}
{% if entity.special.sticky %}

				<th class="center" width="5%"><!-- 5 -->
					<?php echo JHtml::_('grid.sort', 'COM_{{ project.name | constant }}_{{ entity.name | constant }}_FIELD_{{ property.name | constant }}_LABEL', '{{ entity.name }}.{{ entity.special.sticky.name }}', $listDirn, $listOrder); ?>
				</th>
{% endif %}
{% for property in entity.references.foreignKeys  %}
{% if property.role == 'category' %}
{% elseif property.role == 'title' %}
{% elseif property.role == 'created_by' %}
{% elseif property.role == 'modified_by' %}
{% else %}

				<th class="center nowrap" width="10%"><!-- 6 -->
					<?php echo JHtml::_('grid.sort', 'COM_{{ project.name | constant }}_{{ entity.name | constant }}_FIELD_{{ property.name | constant }}_LABEL', '{{ property.name | variable }}_name', $listDirn, $listOrder); ?>
				</th>
{% endif %}
{% endfor %}
{% if entity.special.category %}

				<th class="center" width="10%"><!-- 7 -->
					<?php echo JHtml::_('grid.sort', 'JCATEGORY', 'category_title', $listDirn, $listOrder); ?>
				</th>
{% endif %}
{% if entity.special.language %}

				<th class="center" width="5%"><!-- 8 -->
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', '{{ entity.name }}.{{ entity.special.language.name }}', $listDirn, $listOrder); ?>
				</th>
{% endif %}

				<th class="center" width="1%" class="nowrap"><!-- 9 -->
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', '{{ entity.name }}.{{ entity.special.key.name }}', $listDirn, $listOrder); ?>
				</th>
{% if entity.role == 'lookup' %}

				<th class="center" width="1%" class="nowrap"><!-- 10 -->
					<a onclick="Joomla.submitbutton('{{ entity.name | singular | file }}.add')" href="#">
						ADD
					</a>
				</th>
{% endif %}

			</tr>
		</thead>
		<tbody>
		<?php foreach ($this->items as $i => $item) : ?>
			<tr class="row<?php echo $i % 2; ?>">
{% if entity.special.title or entity.dynName %}

				<td class="title nowrap"><!-- 1 -->
{% if entity.role == 'lookup' %}
					<a onclick="Joomla.submitbutton('{{ entity.name | singular | file }}.edit', <?php echo $item->id; ?>)" href="#">
						<?php echo $this->escape($item->title); ?>
					</a>
{% else %}
{% if entity.special.title %}
					<a class="pointer" onclick="if (window.parent) window.parent.<?php echo $this->escape($function);?>('<?php echo $item->id; ?>', '<?php echo $this->escape($item->title); ?>',{% if entity.special.category %}'<?php echo $this->escape($item->{{ entity.special.category.name }}); ?>',{% endif %}null);">
						<?php echo $this->escape($item->title); ?>
					</a>
{% endif %}
{% if entity.dynName %}
					<?php $title = implode(' ', array({% for property in entity.dynName %}$item->{{ property.name | variable }}, {% endfor %})); ?>
					<a class="pointer" onclick="if (window.parent) window.parent.<?php echo $this->escape($function);?>('<?php echo $item->id; ?>', '<?php echo $this->escape($title); ?>',{% if entity.special.category %}'<?php echo $this->escape($item->{{ entity.special.category.name }}); ?>',{% endif %}null);">
						<?php echo $this->escape($title); ?>
					</a>
{% endif %}
{% endif %}

				</td>
{% endif %}
{% for property in entity.listFields %}
{% if property.role == 'key' %}
{% elseif property.role == 'title' %}
{% elseif property.role == 'category' %}
{% else %}

				<td class="center nowrap"><!-- 2 -->
					<?php echo $item->{{ property.name | variable }}; ?>
				</td>
{% endif %}
{% endfor %}
{% if entity.special.featured %}

				<td class="center"><!-- 3 -->
					<?php echo JHtml::_('jgrid.published', $item->{{ entity.special.featured.name }}, $i, '{{ entity.name | singular | file }}.featured_', $canChange); ?>
				</td>
{% endif %}
{% if entity.special.published %}

				<td class="center"><!-- 4 -->
					<?php echo JHtml::_('jgrid.published', $item->{{ entity.special.published.name }}, $i, '{{ entity.name | singular | file }}.', $canChange, 'cb', $item->publish_up, $item->publish_down); ?>
				</td>
{% endif %}
{% if entity.special.sticky %}

				<td class="center"><!-- 5 -->
					<?php echo JHtml::_('jgrid.published', $item->{{ entity.special.sticky.name }}, $i, '{{ entity.name | singular | file }}.sticky_', $canChange); ?>
				</td>
{% endif %}
{% for property in entity.references.foreignKeys  %}
{% set foreignEntity = project.entities[property.type.entity] %}
{% if property.role == 'category' %}
{% elseif property.role == 'title' %}
{% elseif property.role == 'created_by' %}
{% elseif property.role == 'modified_by' %}
{% else %}

				<td class="center nowrap"><!-- 6 -->
					<a href="<?php echo JRoute::_('index.php?option=com_{{ project.name | file }}&view={{ foreignEntity.name | plural | file }}&filter_search=' . urlencode($item->{{ property.name | variable }}_name)); ?>">
						<?php echo $item->{{ property.name | variable }}_name; ?>
					</a>
				</td>
{% endif %}
{% endfor %}
{% if entity.special.category %}

				<td class="center nowrap"><!-- 7 -->
					<?php echo $this->escape($item->category_title); ?>
				</td>
{% endif %}
{% if entity.special.language %}

				<td class="center nowrap"><!-- 8 -->
					<?php if ($item->language == '*'):?>
						<?php echo JText::alt('JALL', 'language'); ?>
					<?php else:?>
						<?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
					<?php endif;?>
				</td>
{% endif %}

				<td class="center"><!-- 9 -->
					<?php echo $item->id; ?>
				</td>
{% if entity.role == 'lookup' %}

				<td class="center nowrap"><!-- 10 -->
					<a href="<?php echo JRoute::_('index.php?option=com_{{ project.name | file }}&view={{ entity.name | plural | file }}&task={{ entity.name | plural | file }}.delete&cid[]=' . $item->id . '&layout=modal&tmpl=component&' . JSession::getFormToken().'=1');?>">
						DEL
					</a>
				</td>
{% endif %}

			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
{% if entity.role != 'lookup' %}

	<div><?php echo $this->pagination->getListFooter(); ?></div>
{% endif %}

	<div>
		<input type="hidden" name="cid[]" id="cid" value="0" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
{% endif %}
