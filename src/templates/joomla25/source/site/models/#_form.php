<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' %}
<?php
/**
 * {{ project.title }} {{ entity.name | title }} Frontend Form Controller
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

/**
 * {{ entity.name | title }} Edit Model
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}Model{{ entity.name | class }}_Form extends {{ project.name | class }}AdminModel{{ entity.name | class }}
{
{# override properties #}{# endoverride #}
{# override populateState #}

	/**
	 * Auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
{# align("\t", '  ') #}
	 * @return  void
{# endalign #}
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication();

		// Load state from the request.
		$pk = JRequest::getInt('id');
		$this->setState('{{ entity.name | singular | file }}.id', $pk);
{% if entity.special.category %}
		$this->setState('{{ entity.name | singular | file }}.{{ entity.special.category.name }}', JRequest::getInt('{{ entity.special.category.name }}'));
{% endif %}

		$return = JRequest::getVar('return', null, 'default', 'base64');
		$this->setState('return_page', base64_decode($return));

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);

		$this->setState('layout', JRequest::getCmd('layout'));

		return;
	}
{# endoverride #}
{# override getItem #}

	/**
	 * Get {{ entity.name | singular | title }} data
	 *
{# align("\t", '  ') #}
	 * @param	integer	$itemId	  The id of the {{ entity.name | singular | title }}.
	 *
	 * @return  mixed  [[classname:singular:human:ucfirst/]] item data object on success, false on failure
{# endalign #}
	 */
	public function getItem($itemId = null)
	{
		$itemId = (int) (!empty($itemId)) ? $itemId : $this->getState('{{ entity.name | singular | file }}.id');
		$table  = $this->getTable();
		$return = $table->load($itemId);

		if ($return === false && $table->getError())
		{
			$this->setError($table->getError());
			return false;
		}

		$properties = $table->getProperties(1);
		$item = JArrayHelper::toObject($properties, 'JObject');

		$item->params = new JRegistry;
{% if entity.special.attribs %}
		$item->params->loadString($item->attribs);
{% endif %}

		$user   = JFactory::getUser();
		$userId = $user->get('id');

		$item->params->set('access-edit', false);
{% if entity.special.published %}
		$item->params->set('access-change', false);
{% endif %}

		if (!empty($userId))
		{
{% if entity.special.category %}
			$asset  = 'com_{{ project.name | file }}.category.' . $item->{{ entity.special.category.name }};
{% else %}
			$asset  = 'com_{{ project.name | file }}';
{% endif %}

			if ($user->authorise('core.edit', $asset))
			{
				$item->params->set('access-edit', true);
			}
{% if entity.special.created_by %}
			elseif ($user->authorise('core.edit.own', $asset))
			{
				if ($userId == $item->{{ entity.special.created_by.name }})
				{
					$item->params->set('access-edit', true);
				}
			}
{% endif %}
{% if entity.special.published %}

			if (!empty($itemId))
			{
				$item->params->set('access-change', $user->authorise('core.edit.state', $asset));
			}
{% if entity.special.category %}
			else
			{
				$catId = (int) $this->getState('{{ entity.name | singular | file }}.catid');
				if (!empty($catId))
				{
					$item->params->set('access-change', $user->authorise('core.edit.state', 'com_{{ project.name | file }}.category.' . $catId));
					$item->{{ entity.special.category.name }} = $catId;
				}
				else
				{
					$item->params->set('access-change', $user->authorise('core.edit.state', 'com_{{ project.name | file }}'));
				}
			}
{% else %}
			else
			{
				$item->params->set('access-change', $user->authorise('core.edit.state', 'com_{{ project.name | file }}'));
			}
{% endif %}
{% endif %}
		}
{% if entity.special.introtext and entity.special.fulltext %}

		$item->text = $item->{{ entity.special.introtext.name }};
		if (!empty($item->{{ entity.special.fulltext.name }}))
		{
			$item->text .= '<hr id="system-readmore" />'.$item->{{ entity.special.fulltext.name }};
		}
{% endif %}

		return $item;
	}
{# endoverride #}
{# override getReturnPage #}

	/**
	 * Get the return URL.
	 *
{# align("\t", '  ') #}
	 * @return  string  The return URL
{# endalign #}
	 */
	public function getReturnPage()
	{
		return base64_encode($this->getState('return_page'));
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
{% endif %}
