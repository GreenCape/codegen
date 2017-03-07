<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' %}
<?php
/**
 * Default {{ entity.name | class }} HTML Item Template
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
 * @see  {{ project.name | class }}Table{{ entity.name | plural | class }}::getBaseQuery() for available properties
{# endalign #}
 *
 * @var  {{ project.name | class }}View{{ entity.name | class }}  $this
 */

// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

$params  = $this->item->params;
$canEdit = $this->item->params->get('access-edit');
$user    = JFactory::getUser();
{% if entity.special.fulltext %}
{% if entity.special.introtext %}

$this->introtext = $this->item->{{ entity.special.introtext.name }};
$this->fulltext  = $this->item->{{ entity.special.fulltext.name }};
{% else %}

$pattern = '#<hr\s+id=("|\')system-readmore(?:$1)\s*/?>#i';
if (preg_match($pattern, $this->item->text))
{
	list ($this->introtext, $this->fulltext) = preg_split($pattern, $this->item->text, 2);
}
else
{
	$this->introtext = '';
	$this->fulltext  = $this->item->text;
}
{% endif %}
{% endif %}
?>

<?php if (!empty($this->item)) : ?>

	<div class="item-page<?php echo $this->pageclass_sfx ?>">

	<?php if ($params->get('show_page_heading')) : ?>
		<h1><?php echo $this->escape($params->get('page_heading')); ?></h1>
	<?php endif; ?>

	<?php
	if (!empty($this->item->pagination) && !$this->item->paginationposition && $this->item->paginationrelative)
	{
		echo $this->item->pagination;
	}
	?>

	<?php if ($params->get('{{ entity.name | singular | file }}_show_title', 1)) : ?>
		<h2><?php echo $this->escape($this->item->title); ?></h2>
	<?php endif; ?>

	<?php if ($canEdit || $params->get('{{ entity.name | singular | file }}_show_print_icon') || $params->get('{{ entity.name | singular | file }}_show_email_icon')) : ?>
		<ul class="actions">
			<?php if (!$this->print) : ?>

				<?php if ($params->get('{{ entity.name | singular | file }}_show_print_icon')) : ?>
						<li class="print-icon">
							<?php echo JHtml::_('icon.print_popup', $this->item, '{{ entity.name | singular | file }}', $params); ?>
						</li>
				<?php endif; ?>

				<?php if ($params->get('{{ entity.name | singular | file }}_show_email_icon')) : ?>
						<li class="email-icon">
							<?php echo JHtml::_('icon.email', $this->item, '{{ entity.name | singular | file }}', $params); ?>
						</li>
				<?php endif; ?>

				<?php if ($canEdit) : ?>
						<li class="edit-icon">
							<?php echo JHtml::_('icon.edit', $this->item, '{{ entity.name | singular | file }}', $params); ?>
						</li>
				<?php endif; ?>

			<?php else : ?>

					<li>
						<?php echo JHtml::_('icon.print_screen', $this->item, '{{ entity.name | singular | file }}', $params); ?>
					</li>

			<?php endif; ?>
		</ul>
	<?php endif; ?>

	<?php echo $this->item->event->afterDisplayTitle; ?>
	<?php echo $this->item->event->beforeDisplayContent; ?>
{% if entity.special.created_by or entity.special.category or entity.special.created or entity.special.modified or entity.special.publish_up or entity.special.hits %}

	<?php
	$useDefList = false;
{% if entity.special.created_by %}
	$useDefList |= $params->get('{{ entity.name | singular | file }}_show_author');
{% endif %}
{% if entity.special.category %}
	$useDefList |= $params->get('{{ entity.name | singular | file }}_show_category');
	$useDefList |= $params->get('{{ entity.name | singular | file }}_show_parent_category');
{% endif %}
{% if entity.special.created %}
	$useDefList |= $params->get('{{ entity.name | singular | file }}_show_create_date');
{% endif %}
{% if entity.special.modified %}
	$useDefList |= $params->get('{{ entity.name | singular | file }}_show_modify_date');
{% endif %}
{% if entity.special.publish_up %}
	$useDefList |= $params->get('{{ entity.name | singular | file }}_show_publish_date');
{% endif %}
{% if entity.special.hits %}
	$useDefList |= $params->get('{{ entity.name | singular | file }}_show_hits');
{% endif %}
	?>

	<?php if ($useDefList) : ?>
		<dl class="article-info">
			<dt class="article-info-term"><?php echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?></dt>
{% if entity.special.category %}

			<?php if ($params->get('{{ entity.name | singular | file }}_show_parent_category') && $this->item->parent_alias != 'root') : ?>
				<dd class="parent-category-name">
					<?php
					$title = $this->escape($this->item->parent_title);
					if ($params->get('{{ entity.name | singular | file }}_link_parent_category') and !empty($this->item->parent_id))
					{
						$title = '<a href="' . JRoute::_({{ project.name | class }}HelperRoute::get{{ entity.name | class }}CategoryRoute($this->item->parent_id)) . '">' . $title . '</a>';
					}
					echo JText::sprintf('COM_CONTENT_PARENT', $title);
					?>
				</dd>
			<?php endif; ?>

			<?php if ($params->get('{{ entity.name | singular | file }}_show_category')) : ?>
				<dd class="category-name">
					<?php
					$title = $this->escape($this->item->category_title);
					if ($params->get('{{ entity.name | singular | file }}_link_category') && !empty($this->item->catid))
					{
						$title = '<a href="' . JRoute::_({{ project.name | class }}HelperRoute::get{{ entity.name | class }}CategoryRoute($this->item->catid)) . '">' . $title . '</a>';
					}
					echo JText::sprintf('COM_CONTENT_CATEGORY', $title);
					?>
				</dd>
			<?php endif; ?>
{% endif %}
{% if entity.special.created %}

			<?php if ($params->get('{{ entity.name | singular | file }}_show_create_date')) : ?>
				<dd class="create">
					<?php echo JText::sprintf('COM_CONTENT_CREATED_DATE_ON', JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC2'))); ?>
				</dd>
			<?php endif; ?>
{% endif %}
{% if entity.special.modified %}

			<?php if ($params->get('{{ entity.name | singular | file }}_show_modify_date') && $this->item->modified > 0) : ?>
				<dd class="modified">
					<?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC2'))); ?>
				</dd>
			<?php endif; ?>
{% endif %}
{% if entity.special.publish_up %}

			<?php if ($params->get('{{ entity.name | singular | file }}_show_publish_date')) : ?>
				<dd class="published">
					<?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC2'))); ?>
				</dd>
			<?php endif; ?>
{% endif %}
{% if entity.special.created_by %}

			<?php if ($params->get('{{ entity.name | singular | file }}_show_author') && !empty($this->item->created_by_name)) : ?>
				<dd class="createdby">
					<?php
					$author = $this->item->created_by_name;
					if (!empty($this->item->contactid) && $params->get('{{ entity.name | singular | file }}_link_author'))
					{
						$needle  = 'index.php?option=com_contact&view=contact&id=' . $this->item->contactid;
						$contact = JFactory::getApplication()->getMenu()->getItems('link', $needle, true);
						$cntlink = !empty($contact) ? ($needle . '&Itemid=' . $contact->id) : $needle;
						$author  = JHtml::_('link', JRoute::_($cntlink), $author);
					}
					echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author);
					?>
				</dd>
			<?php endif; ?>
{% endif %}
{% if entity.special.hits %}

			<?php if ($params->get('{{ entity.name | singular | file }}_show_hits')) : ?>
				<dd class="hits">
					<?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $this->item->{{ entity.special.hits.name }}); ?>
				</dd>
			<?php endif; ?>
{% endif %}

		</dl>
	<?php endif; ?>
{% endif %}

	<?php if (isset ($this->item->toc)) : ?>
		<?php echo $this->item->toc; ?>
	<?php endif; ?>

	<?php
	if (!empty($this->item->pagination) && !$this->item->paginationposition && !$this->item->paginationrelative)
	{
		echo $this->item->pagination;
	}
{% if entity.special.fulltext %}

	if ($params->get('{{ entity.name | singular | file }}_show_intro') && !empty($this->introtext))
	{
		echo $this->introtext;
	}

	echo $this->fulltext;
{% endif %}

	?>
	<dl class="properties">
{% for property in entity.references.foreignKeys  %}
{% if property.role == 'category' %}
{% elseif property.form == 'parameters' %}
{% elseif property.role == 'created_by' %}
{% elseif property.role == 'modified_by' %}
{% else %}

		<dt><?php echo JText::_('COM_{{ project.name | constant }}_{{ entity.name | constant }}_FIELD_{{ property.name | constant }}_LABEL'); ?></dt>
		<dd><?php echo $this->item->{{ property.name | variable }}_name; ?></dd>
{% endif %}
{% endfor %}
{% for property in entity.properties %}
{% if property.role == 'key' %}
{% elseif property.role == 'title' %}
{% elseif property.role == 'category' %}
{% elseif entity.type.base == 'reference' %}
{% elseif property.role == 'introtext' %}
{% elseif property.role == 'fulltext' %}
{% elseif property.role == 'published' %}
{% elseif property.form == 'parameters' %}
{% else %}

		<dt><?php echo JText::_('COM_{{ project.name | constant }}_{{ entity.name | constant }}_FIELD_{{ property.name | constant }}_LABEL'); ?></dt>
		<dd><?php echo $this->item->{{ property.name | variable }}; ?></dd>
{% endif %}
{% endfor %}
{% for detail in entity.details %}
{% set foreignEntity = project.entities[detail.entity] %}

		<dt><?php echo JText::_('COM_{{ project.name | constant }}_{{ entity.name | constant }}_FIELD_{{ foreignEntity.name | constant }}_COUNT'); ?></dt>
		<dd><?php echo JText::plural('COM_{{ project.name | constant }}_{{ entity.name | constant }}_FIELD_{{ foreignEntity.name | constant }}_UNIT', $this->item->num_{{detail.entity | plural | variable }}_{{detail.reference | variable }}); ?></dd>
{% endfor %}

	</dl>
	<?php
	if (!empty($this->item->pagination) && $this->item->paginationposition && !$this->item->paginationrelative)
	{
		echo $this->item->pagination;
	}

	if (!empty($this->item->pagination) && $this->item->paginationposition && $this->item->paginationrelative)
	{
		echo $this->item->pagination;
	}
		echo $this->item->event->afterDisplayContent;
	?>

</div>

<?php endif; ?>

<?php
{% endif %}
