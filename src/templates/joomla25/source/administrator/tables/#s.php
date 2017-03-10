<?template scope="entity"?>
{% if entity.special.key %}
<?php
/**
 * {{ project.title }} {{ entity.name | title }} Table
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
 *
 * @startuml{uml-class-{{ project.name | file }}table{{ entity.name | plural | file }}.svg}
 * JObject <|-- JTable
 * JTable -> JDatabase : #_db
 *
 * class "{{ project.name | class }}Table{{ entity.name | plural | class }}" as {{ entity.name }}
{% if entity.special.category %}
 * class "JTableCategory" as category
{% endif %}
{% if entity.special.created_by or entity.special.modified_by or entity.special.checkout_by %}
 * class "JTableUser" as user
{% endif %}
{% for property in entity.references.foreignKeys  %}
{% set foreignEntity = project.entities[property.type.entity] %}
{% if property.role == 'category' %}
{% elseif property.role == 'created_by' %}
{% elseif property.role == 'modified_by' %}
{% elseif property.role == 'checkout_by' %}
{% else %}
 * class "{{ project.name | class }}Table<{{ foreignEntity.name | plural | class }}" as {{ foreignEntity.name }}
{% endif %}
{% endfor %}
{% for detail in entity.details %}
{% set foreignEntity = detail.entity %}
 * class "{{ project.name | class }}Table<{{ foreignEntity.name | plural | class }}" as {{ foreignEntity.name }}
{% endfor %}
 *
 * JTable <|-- {{ entity.name }}
 *
{% for property in entity.properties %}
{% if property.type.base != 'reference' %}
 * {{ entity.name }}: +{{ property.name | variable }}: {{ property.type.php }}
{% endif %}
{% endfor %}
 *
{% for property in entity.references.foreignKeys  %}
{% set foreignEntity = project.entities[property.type.entity] %}
{% if property.role == 'category' %}
{% elseif property.role == 'created_by' %}
{% elseif property.role == 'modified_by' %}
{% elseif property.role == 'checkout_by' %}
{% else %}
 * {{ entity.name }} -> {{ foreignEntity.name }} : +{{ property.name }}
{% endif %}
{% endfor %}
{% if entity.special.category %}
 * {{ entity.name }} -> category : +{{ entity.special.category.name }}
{% endif %}
{% if entity.special.created_by %}
 * {{ entity.name }} -> user : +{{ entity.special.created_by.name }}
{% endif %}
{% if entity.special.modified_by %}
 * {{ entity.name }} -> user : +{{ entity.special.modified_by.name }}
{% endif %}
{% if entity.special.checkout_by %}
 * {{ entity.name }} -> user : +{{ entity.special.checkout_by.name }}
{% endif %}
{% for detail in entity.details %}
{% set foreignEntity = detail.entity %}
 * {{ foreignEntity.name }} -> {{ entity.name }} : +{{ detail.reference.name }}
{% endfor %}
 * @enduml
 */

// No direct access
defined('_JEXEC') or die;

/**
 * {{ entity.name | title }} Table
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 *
{# align("\t", '  ') #}
{% for property in entity.properties %}
 * @property  {{ property.type.php }}  ${{ property.name | variable }}  {{ property.description }}
{% endfor %}
{# endalign #}
 */
class {{ project.name | class }}Table{{ entity.name | plural | class }} extends JTable
{
{# override properties #}{# endoverride #}
{# override __construct #}

	/**
	 * Constructor
	 *
{# align("\t", '  ') #}
	 * @param	JDatabase	$db	  A database connector object
{# endalign #}
	 */
	public function __construct($db)
	{
		parent::__construct('#__{{ entity.storage.table }}', '{{ entity.special.key.name }}', $db);
{% if entity.special.created %}

		$this->created = JFactory::getDate()->toSql();
{% endif %}

		return;
	}
{# endoverride #}
{# override bind #}

	/**
	 * Bind an associative array or object to the JTable instance
	 *
	 * This method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
{# align("\t", '  ') #}
	 * @param	mixed	$src	  An associative array or object to bind to the JTable instance
	 * @param	mixed	$ignore	  An optional array or space separated list of properties to ignore while binding
	 *
	 * @return  bool  True on success
{# endalign #}
	 */
	public function bind($src, $ignore = array())
	{
		if (is_object($src))
		{
			$src = get_object_vars($src);
		}
{% if entity.special.attribs %}

		if (isset($src['{{ entity.special.attribs.name }}']) && is_array($src['{{ entity.special.attribs.name }}']))
		{
			$registry = new JRegistry();
			$registry->loadArray($src['{{ entity.special.attribs.name }}']);
			$src['{{ entity.special.attribs.name }}'] = (string)$registry;
		}
{% endif %}

		return parent::bind($src, $ignore);
	}
{# endoverride #}
{# override check #}

	/**
	 * Perform sanity checks on the JTable instance properties
	 *
{# align("\t", '  ') #}
	 * @throws  InvalidArgumentException on invalid data
	 * @return  bool  True if the instance is sane and able to be stored in the database
{# endalign #}
	 */
	public function check()
	{
{% if entity.special.title %}
		if (trim($this->{{ entity.special.title.name }}) == '')
		{
			throw new InvalidArgumentException(JText::_('COM_{{ project.name | constant }}_{{ entity.name | constant }}_MISSING_NAME'));
		}

{% if entity.special.alias %}
		if (trim($this->{{ entity.special.alias.name }}) == '')
		{
			$this->{{ entity.special.alias.name }} = $this->{{ entity.special.title.name }};
		}

		$this->{{ entity.special.alias.name }} = JApplication::stringURLSafe($this->{{ entity.special.alias.name }});

		if (trim(str_replace('-', '', $this->{{ entity.special.alias.name }})) == '')
		{
			$this->{{ entity.special.alias.name }} = JFactory::getDate()->format('Y-m-d-H-i-s');
		}

{% endif %}
{% endif %}
{% for property in entity.references.foreignKeys  %}
{% if property.role != 'category' %}
		if (empty($this->{{ property.name | variable }}))
		{
			$this->{{ property.name | variable }} = null;
		}

{% endif %}
{% endfor %}
{% if entity.special.publish_up and entity.special.publish_down %}
		// Check the publish down date is not earlier than publish up.
		if ($this->{{ entity.special.publish_down.name }} > $this->_db->getNullDate() && $this->{{ entity.special.publish_down.name }} < $this->{{ entity.special.publish_up.name }})
		{
			throw new InvalidArgumentException(JText::_('JGLOBAL_START_PUBLISH_AFTER_FINISH'));
		}

{% endif %}
{% if entity.special.published and entity.special.ordering %}
		if ($this->{{ entity.special.published.name }} < 0)
		{
			// Set ordering to 0 if state is archived or trashed
			$this->{{ entity.special.ordering.name }} = 0;
		}
		elseif (empty($this->{{ entity.special.ordering.name }}))
		{
			// Set ordering to last if ordering was 0
			$this->{{ entity.special.ordering.name }} = $this->getNextOrder({% if entity.special.category %}$this->_db->quoteName('{{ entity.special.category.name }}').'=' . $this->_db->Quote($this->{{ entity.special.category.name }}).' AND {{ entity.special.published.name }}>=0'{% else %}'{{ entity.special.published.name }}>=0'{% endif %});
		}

{% endif %}
{% if not entity.special.published and entity.special.ordering %}
		if (empty($this->{{ entity.special.ordering.name }}))
		{
			// Set ordering to last if ordering was 0
			$this->{{ entity.special.ordering.name }} = $this->getNextOrder({% if entity.special.category %}$this->_db->quoteName('{{ entity.special.category.name }}').'=' . $this->_db->Quote($this->{{ entity.special.category.name }}){% endif %});
		}

{% endif %}
		return parent::check();
	}
{# endoverride #}
{# override store #}
{% if (entity.special.modified and entity.special.modified_by and entity.special.created and entity.special.created_by) or entity.special.alias %}
 
	/**
	 * Store a row in the database from the JTable instance properties
	 *
	 * If a primary key value is set the row with that primary key value will be
	 * updated with the instance property values.  If no primary key value is set
	 * a new row will be inserted into the database with the properties from the
	 * JTable instance.
	 *
{# align("\t", '  ') #}
	 * @param	bool	$updateNulls	  True to update fields even if they are null
	 *
	 * @return  bool  True on success
{# endalign #}
	 */
	public function store($updateNulls = false)
	{
{% if entity.special.modified and entity.special.modified_by and entity.special.created and entity.special.created_by %}
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		if ($this->{{ entity.special.key.name }})
		{
			// Existing {{ entity.name | title }}
			$this->{{ entity.special.modified.name }} = $date->toSql();
			$this->{{ entity.special.modified_by.name }} = $user->get('id');
		}
		else
		{
			// New {{ entity.name | title }}
			if (!intval($this->{{ entity.special.created.name }}))
			{
				$this->{{ entity.special.created.name }} = $date->toSql();
			}
			if (empty($this->{{ entity.special.created_by.name }}))
			{
				$this->{{ entity.special.created_by.name }} = $user->get('id');
			}
		}

{% endif %})
{% if entity.special.alias %}
		// Verify that the alias is unique
		$table = JTable::getInstance('{{ entity.name | class }}', '{{ project.name | class }}Table');

		if ($table->load(array('{{ entity.special.alias.name }}' => $this->{{ entity.special.alias.name }}{% if entity.special.category %}, '{{ entity.special.category.name }}'=>$this->{{ entity.special.category.name }}{% endif %})) && ($table->{{ entity.special.key.name }} != $this->{{ entity.special.key.name }} || $this->{{ entity.special.key.name }} == 0))
		{
			$this->setError(JText::_('COM_WEBLINKS_ERROR_UNIQUE_ALIAS'));
			return false;
		}

{% endif %}
		// Attempt to store the {{ entity.name | title }} data
		return parent::store($updateNulls);
	}
{% endif %}
{# endoverride #}
{% if entity.special.published %}
{# override publish #}

	/**
	 * Set the publishing state for a row or list of rows
	 *
	 * The method respects checked out rows by other users and will attempt
	 * to check-in rows that it can after adjustments are made.
	 *
{# align("\t", '  ') #}
	 * @param	mixed	$pks	  An optional array of primary key values to update.  If not
	 *			set the instance property value is used.
	 * @param	integer	$state	  The publishing state. eg. [0 = unpublished, 1 = published]
	 * @param	int	$userId	  The user id of the user performing the operation
	 *
	 * @return  bool  True on success
{# endalign #}
	 */
	public function publish($pks = null, $state = 1, $userId = 0)
	{
		return $this->setFlag($pks, '{{ entity.special.published.name }}', $state, $userId);
	}
{# endoverride #}
{% endif %}
{% if entity.special.featured %}
{# override featured #}

	/**
	 * Set the feature state for a row or list of rows.
	 *
	 * The method respects checked out rows by other users and will attempt
	 * to check-in rows that it can after adjustments are made.
	 *
{# align("\t", '  ') #}
	 * @param	mixed	$pks	  An optional array of primary key values to update.  If not
	 *                       set the instance property value is used.
	 * @param	int	$state	  The feature state. eg. [0 = unfeatured, 1 = featured]
	 * @param	int	$userId	  The user id of the user performing the operation
	 *
	 * @return  bool  True on success
{# endalign #}
	 */
	public function featured($pks = null, $state = 1, $userId = 0)
	{
		return $this->setFlag($pks, '{{ entity.special.featured.name }}', $state, $userId);
	}
{# endoverride #}
{% endif %}
{% if entity.special.sticky %}
{# override stick #}

	/**
	 * Set the sticky state for a row or list of rows
	 *
	 * The method respects checked out rows by other users and will attempt
	 * to check-in rows that it can after adjustments are made.
	 *
{# align("\t", '  ') #}
	 * @param	mixed	$pks	  An optional array of primary key values to update.  If not
	 *                       set the instance property value is used.
	 * @param	int	$state	  The sticky state. eg. [0 = unsticked, 1 = sticked]
	 * @param	int	$userId	  The user id of the user performing the operation
	 *
	 * @return  bool  True on success
{# endalign #}
	 */
	public function stick($pks = null, $state = 1, $userId = 0)
	{
		return $this->setFlag($pks, '{{ entity.special.sticky.name }}', $state, $userId);
	}
{# endoverride #}
{% endif %}
{% if entity.special.published or entity.special.featured or entity.special.sticky %}
{# override setFlag #}

	/**
	 * Set a flag for a row or list of rows
	 *
	 * The method respects checked out rows by other users and will attempt
	 * to check-in rows that it can after adjustments are made.
	 *
{# align("\t", '  ') #}
	 * @param	mixed	$pks	  An optional array of primary key values to update.  If not
	 *			set the instance property value is used.
	 * @param	string	$flag	  The name of the property
	 * @param	int	$value	  The new value for the flag
	 * @param	int	$userId	  The user id of the user performing the operation
	 *
	 * @return  bool  True on success
{# endalign #}
	 */
	protected function setFlag($pks = null, $flag = null, $value = 1, $userId = 0)
	{
		$k = $this->_tbl_key;

		JArrayHelper::toInteger($pks);
		$userId = (int) $userId;
		$value  = (int) $value;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			if ($this->$k)
			{
				$pks = array($this->$k);
			}
			else
			{
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		$where = $k . ' IN (' . implode(',', $pks) . ')';
{% if entity.special.checked_out_by %}
		$checkin = ' AND ({{ entity.special.checked_out_by.name }}=0 OR {{ entity.special.checked_out_by.name }}=' . (int) $userId . ')';
{% endif %}

		$this->_db->setQuery('UPDATE `' . $this->_tbl . '`' . ' SET `' . $flag . '` = ' . $value . ' WHERE (' . $where . ')'{% if entity.special.checked_out_by %} . $checkin{% endif %});
		$this->_db->query();

		if ($this->_db->getErrorNum())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
{% if entity.special.checked_out %}

		if ($checkin && (count($pks) == $this->_db->getAffectedRows()))
		{
			// Check-in the rows.
			foreach ($pks as $pk)
			{
				$this->checkin($pk);
			}
		}
{% endif %}

		// If the JTable instance value is in the list of primary keys that were set, set the instance.
		if (in_array($this->$k, $pks))
		{
			$this->$flag = $value;
		}

		$this->setError('');
		return true;
	}
{# endoverride #}
{% endif %}
{# override getBaseQuery #}

	/**
	 * Get the base query used in all models
	 *
	 * The query includes these fields:
	 *
{# sort #}
{# align("\t", '  ') #}
{% for property in entity.properties %}
{% if property.role == 'key' %}
	 * id  {{ entity.name }}.{{ property.name | variable }}	The primary key
{% elseif property.role == 'title' %}
	 * title  {{ entity.name }}.{{ property.name | variable }}	The display name
{% elseif property.role == 'category' %}
	 * catid  {{ entity.name }}.{{ property.name | variable }}	The category id
{% elseif property.role == 'introtext' %}
	 * introtext  {{ entity.name }}.{{ property.name | variable }}	The introduction text
{% elseif property.role == 'language' %}
	 * language  {{ entity.name }}.{{ property.name | variable }}	The language ISO code
{% elseif property.role == 'created' %}
	 * created  {{ entity.name }}.{{ property.name | variable }}	Date/time of creation
{% elseif property.role == 'created_by' %}
	 * created_by  {{ entity.name }}.{{ property.name | variable }}	Id of the user who created the {{ entity.name | singular | title }}
{% elseif property.role == 'modified' %}
	 * modified  {{ entity.name }}.{{ property.name | variable }}	Date/time of modification
{% elseif property.role == 'modified_by' %}
	 * modified_by  {{ entity.name }}.{{ property.name | variable }}	Id of the user who modified the {{ entity.name | singular | title }}
{% elseif property.role == 'publish_up' %}
	 * publish_up  {{ entity.name }}.{{ property.name | variable }}	Date/time of the begin of the publishing period
{% elseif property.role == 'fulltext' %}
{% if entity.special.introtext %}
	 * fulltext  {{ entity.name }}.{{ property.name | variable }}	The main text
	 * readmore  calculated  The length of the main text
{% else %}
	 * text  {{ entity.name }}.{{ property.name | variable }}	The main text
{% endif %}
{% elseif property.role == 'published' %}
{% else %}
	 * {{ property.name | variable }}	{{ entity.name }}.{{ property.name | variable }}	{{ property.description }}
{% endif %}
{% endfor %}
{% if entity.special.published %}
	 * {{ entity.special.published.name }}	{{ entity.name }}.{{ entity.special.published.name }}	The publish state
{% if entity.special.category %}
	 * compiled_state  	The publish state with the category taken into account
{% endif %}
{% else %}
{% if entity.special.category %}
	 * compiled_state  	The inherited publish state of the category
{% else %}
	 * compiled_state  	The publish state (always 1 = published)
{% endif %}
{% endif %}
{% if entity.special.access %}
	 * {{ entity.special.access.name }}	{{ entity.name }}.{{ entity.special.access.name }}	The view access level
{% endif %}
{% if entity.special.access and entity.special.category %}
	 * compiled_access  	The view access level with category taken into account
{% elseif not entity.special.access and entity.special.category %}
	 * compiled_access  	The inherited view access level of the category
{% elseif not entity.special.access and not entity.special.category %}
	 * compiled_access  	The view access level (always 1 = public)
{% endif %}
{% if entity.special.language %}
	 * language_title  language.title  The language name
{% endif %}
{% if entity.special.checked_out_by %}
	 * checked_out_by_name  checked_out_by.name  The user who checked out the {{ entity.name | singular | title }}
{% endif %}
{% if entity.special.category %}
	 * category_title  category.title  The category display name
	 * category_alias  category.alias  The category route alias
	 * category_access  category.access  The category view access level
{% endif %}
{% for property in entity.references.foreignKeys  %}
{% set foreignEntity = project.entities[property.type.entity] %}
{% if property.role == 'category' %}
{% elseif property.role == 'created_by' %}
{% elseif property.role == 'modified_by' %}
{% elseif property.role == 'checked_out_by' %}
	 * {{ property.name | variable }}_name  {{ foreignEntity.name }}.{{foreignEntity.special.title.name }}	The display value for {{ property.name }}
{% endif %}
{% endfor %}
{% for detail in entity.details %}
{% set foreignEntity = detail.entity %}
	 * num_{{ detail.entity.name | plural | variable }}_{{ detail.reference.name | variable }}		The number of associated {{ foreignEntity.name }} items
{% endfor %}
{% if entity.special.created_by %}
{% if entity.special.author_alias %}
	 * created_by_name  {{ entity.name }}.{{ entity.special.author_alias.name }}	The name of the author or, if empty, created_by.name)
{% else %}
	 * created_by_name  created_by.name  The name of the author
{% endif %}
	 * created_by_name_email  created_by.email  The author's email address
	 * contactid  contact.id  The author's contact id
{% endif %}
{% if entity.special.modified_by %}
	 * modified_by_name  modified_by.name  The name of the user who last modified the {{ entity.name | singular | title }}
{% endif %}
{% if entity.special.category %}
	 * parent_title  parent_category.title  The parent category display name
	 * parent_id  parent_category.id  The parent category id
	 * parent_route  parent_category.path  The parent category path
	 * parent_alias  parent_category.alias  The parent category route alias
{% endif %}
{# endalign #}
{# endsort #}
	 *
	 * The following aliases ase used:
	 *
{# sort #}
{# align("\t", '  ') #}
	 * {{ entity.name }}	The {{ entity.name | singular | title }} table (#__{{ entity.storage.table }})
{% if entity.special.language %}
	 * language  The core language table table (#__languages)
{% endif %}
{% if entity.special.checked_out_by %}
	 * checked_out_by  The core user table (linked by {{ entity.name }}.{{ entity.special.checked_out_by.name }})
{% endif %}
{% if entity.special.category %}
	 * category  The core category table (linked by {{ entity.name }}.{{ entity.special.category.name }})
{% endif %}
{% for property in entity.references.foreignKeys  %}
{% set foreignEntity = project.entities[property.type.entity] %}
{% if property.role == 'category' %}
{% elseif property.role == 'created_by' %}
{% elseif property.role == 'modified_by' %}
{% elseif property.role == 'checked_out_by' %}
	 * {{ foreignEntity.name }}	The {{ foreignEntity.name }} table (#__{{ foreignEntity.storage.table }}, linked by {{ entity.name }}.{{ property.name }})
{% endif %}
{% endfor %}
{% for detail in entity.details %}
{% set foreignEntity = detail.entity %}
	 * {{ foreignEntity.name }}	The {{ foreignEntity.name }} table with detail records (#__{{ foreignEntity.storage.table }})
{% endfor %}
{% if entity.special.created_by %}
	 * created_by  The core user table (linked by {{ entity.name }}.{{ entity.special.created_by.name }})
	 * contact  The result of a subquery on #__contact_details; not really useful
{% endif %}
{% if entity.special.modified_by %}
	 * modified_by  The core user table (linked by {{ entity.name }}.{{ entity.special.modified_by.name }})
{% endif %}
{% if entity.special.category %}
	 * parent_category  The core category table (linked by category.parent_id)
{% endif %}
{# endalign #}
{# endsort #}
	 *
{# align("\t", '  ') #}
	 * @param	JUser	$user	  The current user
	 * @param	JObject	$state	  The state object
	 * @param	JRegistry	$params	  The parameters
	 *
	 * @return  JDatabaseQuery  A new query object with appropriate initialization
{# endalign #}
	 */
	public function getBaseQuery(JUser $user, JObject $state, JRegistry $params)
	{
		static $query = null;

		$db = $this->getDbo();

		if (is_null($query))
		{
			$query = $db->getQuery(true);

{% for property in entity.properties %}
{% if property.role == 'key' %}
			$query->select($db->quoteName('{{ entity.name }}.{{ property.name | variable }}', 'id'));
{% elseif property.role == 'title' %}
			$query->select($db->quoteName('{{ entity.name }}.{{ property.name | variable }}', 'title'));
{% elseif property.role == 'category' %}
			$query->select($db->quoteName('{{ entity.name }}.{{ property.name | variable }}', 'catid'));
{% elseif property.role == 'introtext' %}
			$query->select($db->quoteName('{{ entity.name }}.{{ property.name | variable }}', 'introtext'));
{% elseif property.role == 'language' %}
			$query->select($db->quoteName('{{ entity.name }}.{{ property.name | variable }}', 'language'));
{% elseif property.role == 'created' %}
			$query->select($db->quoteName('{{ entity.name }}.{{ property.name | variable }}', 'created'));
{% elseif property.role == 'created_by' %}
{% elseif property.role == 'modified' %}
{% if entity.special.created %}
			$query->select(
				'CASE WHEN ' . $db->quoteName('{{ entity.name }}.{{ property.name | variable }}') . ' = 0'
				. ' THEN ' . $db->quoteName('{{ entity.name }}.{{ entity.special.created.name }}')
				. ' ELSE ' . $db->quoteName('{{ entity.name }}.{{ property.name | variable }}')
				. ' END AS ' . $db->quoteName('modified')
			);
{% else %}
			$query->select($db->quoteName('{{ entity.name }}.{{ property.name | variable }}', 'modified'));
{% endif %}
{% elseif property.role == 'modified_by' %}
{% elseif property.role == 'publish_up' %}
{% if entity.special.created %}
			$query->select(
				'CASE WHEN ' . $db->quoteName('{{ entity.name }}.{{ property.name | variable }}') . ' = 0'
				. ' THEN ' . $db->quoteName('{{ entity.name }}.{{ entity.special.created.name }}')
				. ' ELSE ' . $db->quoteName('{{ entity.name }}.{{ property.name | variable }}')
				. ' END AS ' . $db->quoteName('publish_up')
			);
{% else %}
			$query->select($db->quoteName('{{ entity.name }}.{{ property.name | variable }}', 'publish_up'));
{% endif %}
{% elseif property.role == 'fulltext' %}
{% if entity.special.introtext %}
			$query->select($db->quoteName('{{ entity.name }}.{{ property.name | variable }}', 'fulltext'));
			$query->select($query->length($db->quoteName('{{ entity.name }}.{{ property.name | variable }}')) . ' AS '.$db->quoteName('readmore'));
{% else %}
			$query->select($db->quoteName('{{ entity.name }}.{{ property.name | variable }}', 'text'));
{% endif %}
{% else %}
			$query->select($db->quoteName('{{ entity.name }}.{{ property.name | variable }}', '{{ property.name | variable }}'));
{% endif %}
{% endfor %}
{% if entity.special.published %}
{% if entity.special.category %}

			$query->select(
				'CASE WHEN ' . $db->quoteName('{{ entity.name }}.{{ entity.special.published.name }}') . ' < 1 OR ' . $db->quoteName('p.minstate') . ' < 1'
				. ' THEN LEAST(' . $db->quoteName('{{ entity.name }}.{{ entity.special.published.name }}') . ', ' . $db->quoteName('p.minstate') . ')'
				. ' ELSE COALESCE('
					. 'GREATEST(' . $db->quoteName('{{ entity.name }}.{{ entity.special.published.name }}') . ', ' . $db->quoteName('p.maxstate') . '), '
					. $db->quoteName('{{ entity.name }}.{{ entity.special.published.name }}')
				. ')'
				. ' END AS ' . $db->quoteName('compiled_state')
			);
{% else %}
			$query->select($db->quoteName('{{ entity.name }}.{{ entity.special.published.name }}', 'compiled_state'));
{% endif %}
{% else %}
{% if entity.special.category %}
			$query->select(
				'CASE WHEN ' . $db->quoteName($db->quoteName('p.minstate') . ' < 1'
				. ' THEN ' . $db->quoteName('p.minstate')
				. ' ELSE COALESCE(GREATEST(1, ' . $db->quoteName('p.maxstate') . '), 1)'
				. ' END AS ' . $db->quoteName('compiled_state')
				);
{% else %}
			$query->select('1 AS ' . $db->quoteName('compiled_state'));
{% endif %}
{% endif %}
{% if entity.special.access %}
			$query->select($db->quoteName('{{ entity.name }}.{{ entity.special.access.name }}', 'compiled_access'));
{% else %}
			$query->select('1 AS ' . $db->quoteName('compiled_access'));
{% endif %}
			$query->from($db->quoteName('#__{{ entity.storage.table }}', '{{ entity.name }}'));
{% if entity.special.language %}

			// Join over the language
			$query->select($db->quoteName('language.title', 'language_title'));
			$query->join(
				'LEFT', $db->quoteName('#__languages', 'language')
				. ' ON ' . $db->quoteName('language.lang_code') . ' = ' . $db->quoteName('{{ entity.name }}.{{ entity.special.language.name }}')
			);
{% endif %}
{% if entity.special.checked_out_by %}

			// Join over the users for the checked out user
			$query->select($db->quoteName('checked_out_by.name', 'checked_out_by_name'));
			$query->join(
				'LEFT', $db->quoteName('#__users', 'checked_out_by')
				. ' ON ' . $db->quoteName('checked_out_by.id') . ' = ' . $db->quoteName('{{ entity.name }}.{{ entity.special.checked_out_by.name }}')
			);
{% endif %}
{% if entity.special.category %}

			// Join over the categories
			$query->select($db->quoteName('category.title', 'category_title'));
			$query->select($db->quoteName('category.alias', 'category_alias'));
			$query->select($db->quoteName('category.access', 'category_access'));
			$query->join(
				'LEFT', $db->quoteName('#__categories', 'category')
				. ' ON ' . $db->quoteName('category.id') . ' = ' . $db->quoteName('{{ entity.name }}.{{ entity.special.category.name }}')
			);
{% endif %}
{% for property in entity.references.foreignKeys  %}
{% set foreignEntity = project.entities[property.type.entity] %}
{% if property.role == 'category' %}
{% elseif property.role == 'created_by' %}
{% elseif property.role == 'modified_by' %}
{% elseif property.role == 'checked_out_by' %}
{% else %}

			// Join over the {{ foreignEntity.name | plural | title }}
			$query->select($db->quoteName('{{ foreignEntity.name }}.{{foreignEntity.special.title.name }}', '{{ property.name | variable }}_name'));
			$query->join(
				'LEFT', $db->quoteName('#__{{ foreignEntity.storage.table }}', '{{ foreignEntity.name }}')
				. ' ON ' . $db->quoteName('{{ foreignEntity.name }}.{{ foreignEntity.special.key.name }}') . ' = ' . $db->quoteName('{{ entity.name }}.{{ property.name }}')
			);
{% endif %}
{% endfor %}
{% for detail in entity.details %}
{% set foreignEntity = detail.entity %}

			// Join over {{ foreignEntity.name | plural | title }} for counting
			$query->select('COUNT(' . $db->quoteName('{{ foreignEntity.name }}.{{ foreignEntity.special.key.name }}') . ') AS ' . $db->quoteName('num_{{ detail.entity.name | plural | variable }}_{{ detail.reference.name | variable }}'));
			$query->join(
				'LEFT', $db->quoteName('#__{{ foreignEntity.storage.table }}', '{{ foreignEntity.name }}')
				. ' ON ' . $db->quoteName('{{ foreignEntity.name }}.{{ detail.reference.name }}') . ' = ' . $db->quoteName('{{ entity.name }}.{{ entity.special.key.name }}')
			);
{% endfor %}
{% if entity.special.created_by %}

			// Join over the users for the author name.
			$query->select($db->quoteName('{{ entity.name }}.{{ entity.special.created_by.name }}', 'created_by'));
{% if entity.special.author_alias %}
			$query->select(
				'CASE WHEN {{ entity.name }}.{{ entity.special.author_alias.name }} > \' \''
				. ' THEN {{ entity.name }}.{{ entity.special.author_alias.name }}'
				. ' ELSE created_by.name'
				. ' END AS created_by_name'
			);
{% else %}
			$query->select($db->quoteName('created_by.name', 'created_by_name'));
{% endif %}
			$query->select($db->quoteName('created_by.email', 'created_by_name_email'));
			$query->join(
				'LEFT', $db->quoteName('#__users', 'created_by')
				. ' ON ' . $db->quoteName('created_by.id') . ' = ' . $db->quoteName('{{ entity.name }}.{{ entity.special.created_by.name }}')
			);

			// Join on contact table
			$subQuery = $db->getQuery(true);
			$subQuery->select($db->quoteName('contact.user_id'));
			$subQuery->select('MAX(' . $db->quoteName('contact.id') . ') AS ' . $db->quoteName('id'));
			$subQuery->select($db->quoteName('contact.language'));
			$subQuery->from($db->quoteName('#__contact_details', 'contact'));
			$subQuery->where($db->quoteName('contact.published') . ' = 1');
			$subQuery->group($db->quoteName('contact.user_id') . ', ' . $db->quoteName('contact.language'));
			$query->select($db->quoteName('contact.id', 'contactid'));
			$query->join(
				'LEFT', '(' . $subQuery . ') AS ' . $db->quoteName('contact')
				. ' ON ' . $db->quoteName('contact.user_id') . ' = ' . $db->quoteName('{{ entity.name }}.{{ entity.special.created_by.name }}')
			);
{% endif %}
{% if entity.special.modified_by %}

			// Join on user table for modifier
			$query->select($db->quoteName('{{ entity.name }}.{{ entity.special.modified_by.name }}', 'modified_by'));
			$query->select($db->quoteName('modified_by.name', 'modified_by_name'));
			$query->join(
				'LEFT', $db->quoteName('#__users', 'modified_by')
				. ' ON ' . $db->quoteName('modified_by.id') . ' = ' . $db->quoteName('{{ entity.name }}.{{ entity.special.modified_by.name }}')
			);
{% endif %}
{% if entity.special.category %}

			// Join over the categories to get parent category titles
			$query->select($db->quoteName('parent_category.title', 'parent_title'));
			$query->select($db->quoteName('parent_category.id', 'parent_id'));
			$query->select($db->quoteName('parent_category.path', 'parent_route'));
			$query->select($db->quoteName('parent_category.alias', 'parent_alias'));
			$query->join(
				'LEFT', $db->quoteName('#__categories', 'parent_category')
				. ' ON ' . $db->quoteName('parent_category.id') . ' = ' . $db->quoteName('category.parent_id')
			);

			/*
			 * This query returns the compiled state from the item *and* its category (the complete branch up to root)
			 *
			 * SELECT
			 *   a.id AS a_id, a.state AS a_state,
			 *   CASE WHEN a.state < 1 OR p.minstate < 1
			 *     THEN LEAST(a.state, p.minstate)
			 *     ELSE COALESCE(GREATEST(a.state, p.maxstate), a.state)
			 *     END AS compiled_state
			 * FROM j25_content AS a
			 * LEFT JOIN (
			 *   SELECT c.id AS id, COUNT(p.id) AS num, MIN(p.published) AS minstate, MAX(p.published) AS maxstate
			 *   FROM j25_categories AS c
			 *   LEFT JOIN j25_categories AS p ON c.lft BETWEEN p.lft AND p.rgt
			 *   GROUP BY c.id
			 * ) AS p ON p.id = a.catid
			 *
			 * The approach behind this is, that if an item or its parent (may be category and its parent as well)
			 *  - are trashed (-2), the item is treated as trashed
			 *  - are unpublished (0), the item is treated as unpublished
			 *  - are archived (2), the item is treated as archived
			 *  - are both published, the item is treated as published.
			 */
			$subQuery = $db->getQuery(true);
			$subQuery->select($db->quoteName('c.id', 'id'));
			$subQuery->select('MIN(' . $db->quoteName('p.published') . ') AS ' . $db->quoteName('minstate'));
			$subQuery->select('MAX(' . $db->quoteName('p.published') . ') AS ' . $db->quoteName('maxstate'));
			$subQuery->from($db->quoteName('#__categories', 'c'));
			$subQuery->join(
				'LEFT', $db->quoteName('#__categories', 'p')
				. ' ON ' . $db->quoteName('c.lft') . ' BETWEEN ' . $db->quoteName('p.lft') . ' AND ' . $db->quoteName('p.rgt')
			);
			$subQuery->group($db->quoteName('c.id'));
			$query->join(
				'LEFT', '(' . $subQuery . ') AS ' . $db->quoteName('p')
				. ' ON ' . $db->quoteName('p.id') . ' = ' . $db->quoteName('{{ entity.name }}.{{ entity.special.category.name }}')
			);
{% endif %}
		}
		$newQuery = clone($query);

		$this->applyFilterAccess($db, $newQuery, $state, $user);
{% if entity.special.created_by %}
		$this->applyFilterViewRestriction($db, $newQuery, $user, $params);
{% endif %}
		$this->applyFilterState($db, $newQuery, $state);
{% if entity.special.featured %}
		$this->applyFilterFeatured($db, $newQuery, $state);
{% endif %}
		$this->applyFilterId($db, $newQuery, $state);
{% if entity.special.category %}
		$this->applyFilterCategory($db, $newQuery, $state);
{% endif %}
{% if entity.special.created_by or entity.special.author_alias %}
		$this->applyFilterAuthor($db, $newQuery, $state);
{% endif %}
{% if entity.special.publish_up %}
		$this->applyFilterPublishingPeriod($db, $newQuery, $state);
{% endif %}
{% if entity.special. created or entity.special. modified or entity.special. publish_up %}
		$this->applyFilterDateRange($db, $newQuery, $state);
{% endif %}
		$this->applyFilterListFilter($db, $newQuery, $state, $params);
{% if entity.special.language %}
		$this->applyFilterLanguage($db, $newQuery, $state);
{% endif %}
{% for property in entity.references.foreignKeys  %}
{% set foreignEntity = project.entities[property.type.entity] %}
{% if property.role == 'category' %}
{% elseif property.role == 'created_by' %}
{% else %}
		$this->applyFilter{{ property.name | class }}($db, $newQuery, $state);
{% endif %}
{% endfor %}
		$this->applyFilterSearch($db, $newQuery, $state);
		$this->applyOrdering($db, $newQuery, $state);
{% if entity.special.master %}

		$newQuery->group($db->quoteName('{{ entity.name }}.{{ entity.special.key.name }}'));
{% endif %}

		return $newQuery;
	}
{# endoverride #}
{# override applyFilterAccess #}

	/**
	 * Filter by view level
	 *
{# align("\t", '  ') #}
	 * @param	JDatabase	$db	  The database connector
	 * @param	JDatabaseQuery	$query	  The query to apply the filter to
	 * @param	JObject	$state	  The current state
	 * @param	JUser	$user	  The current user
	 *
	 * @return  void
{# endalign #}
	 */
	private function applyFilterAccess($db, $query, $state, $user)
	{
		if ($user->authorise('core.admin'))
		{
			return;
		}
		if ($state->get('filter.access'))
		{
			$groups = implode(', ', array_unique($user->getAuthorisedViewLevels()));
			$query->having($db->quoteName('compiled_access') . ' IN (' . $groups . ')');
		}
	}
{# endoverride #}
{% if entity.special.created_by %}
{# override applyFilterViewRestriction #}

	/**
	 * Filter by view restriction
	 *
{# align("\t", '  ') #}
	 * @param	JDatabase	$db	  The database connector
	 * @param	JDatabaseQuery	$query	  The query to apply the filter to
	 * @param	JUser	$user	  The current user
	 * @param	JRegistry	$params	  The parameters
	 *
	 * @return  void
{# endalign #}
	 */
	private function applyFilterViewRestriction($db, $query, $user, $params)
	{
		$viewRestriction = $params->get('{{ entity.name | singular | file }}_view_restriction', 'all');
		$showNoAuth      = (bool) $params->get('{{ entity.name | singular | file }}_show_noauth', 0);
		if ($viewRestriction == 'own' && !$showNoAuth)
		{
			$query->where($db->quoteName('{{ entity.name }}.{{ entity.special.created_by.name }}') . ' = ' . $user->get('id'));
		}
	}
{# endoverride #}
{% endif %}
{# override applyFilterState #}

	/**
	 * Filter by published state
	 *
{# align("\t", '  ') #}
	 * @param	JDatabase	$db	  The database connector
	 * @param	JDatabaseQuery	$query	  The query to apply the filter to
	 * @param	JObject	$state	  The current state
	 *
	 * @return  void
{# endalign #}
	 */
	private function applyFilterState($db, $query, $state)
	{
		$published = $state->get('filter.state');
		if (is_numeric($published))
		{
			$query->having($db->quoteName('compiled_state') . ' = ' . (int) $published);
		}
		elseif (is_array($published))
		{
			JArrayHelper::toInteger($published);
			$published = implode(', ', array_unique($published));
			$query->having($db->quoteName('compiled_state') . ' IN (' . $published . ')');
		}
		elseif ($published === '')
		{
			$query->having($db->quoteName('compiled_state') . ' IN (0, 1)');
		}
		return;
	}
{# endoverride #}
{% if entity.special.featured %}
{# override applyFilterFeatured #}

	/**
	 * Filter by featured state
	 *
{# align("\t", '  ') #}
	 * @param	JDatabase	$db	  The database connector
	 * @param	JDatabaseQuery	$query	  The query to apply the filter to
	 * @param	JObject	$state	  The current state
	 *
	 * @return  void
{# endalign #}
	 */
	private function applyFilterFeatured($db, $query, $state)
	{
		switch ($state->get('filter.featured'))
		{
			case 'hide':
				$query->where($db->quoteName('{{ entity.name }}.{{ entity.special.featured.name }}') . ' = 0');
				break;

			case 'only':
				$query->where($db->quoteName('{{ entity.name }}.{{ entity.special.featured.name }}') . ' = 1');
				break;

			case 'show':
			default:
				// Normally we do not distinguish between featured/unfeatured items.
				break;
		}
		return;
	}
{# endoverride #}
{% endif %}
{# override applyFilterId #}

	/**
	 * Filter by a single or group of {{ entity.name | singular | title }}
	 *
{# align("\t", '  ') #}
	 * @param	JDatabase	$db	  The database connector
	 * @param	JDatabaseQuery	$query	  The query to apply the filter to
	 * @param	JObject	$state	  The current state
	 *
	 * @return  void
{# endalign #}
	 */
	private function applyFilterId($db, $query, $state)
	{
		$item_id = $state->get('filter.item_id');
		if (is_numeric($item_id))
		{
			$op = $state->get('filter.item_id.include', true) ? ' = ' : ' <> ';
			$query->where($db->quoteName('{{ entity.name }}.{{ entity.special.key.name }}') . $op . (int) $item_id);
		}
		elseif (is_array($item_id))
		{
			JArrayHelper::toInteger($item_id);
			$item_id = implode(', ', array_unique($item_id));
			$op = $state->get('filter.item_id.include', true) ? ' IN ' : ' NOT IN ';
			$query->where($db->quoteName('{{ entity.name }}.{{ entity.special.key.name }}') . $op . ' (' . $item_id . ')');
		}
		return;
	}
{# endoverride #}
{% if entity.special.category %}
{# override applyFilterCategory #}

	/**
	 * Filter by a single or group of categories
	 *
{# align("\t", '  ') #}
	 * @param	JDatabase	$db	  The database connector
	 * @param	JDatabaseQuery	$query	  The query to apply the filter to
	 * @param	JObject	$state	  The current state
	 *
	 * @return  void
{# endalign #}
	 */
	private function applyFilterCategory($db, $query, $state)
	{
		$categoryId = $state->get('filter.category_id');
		if (is_numeric($categoryId))
		{
			$op = $state->get('filter.category_id.include', true) ? ' = ' : ' <> ';

			// Add subcategory check
			$includeSubcategories = $state->get('filter.subcategories', false);
			$categoryEquals = $db->quoteName('{{ entity.name }}.{{ entity.special.category.name }}') . $op . (int) $categoryId;

			if ($includeSubcategories)
			{
				$levels = (int) $state->get('filter.max_category_levels', '1');
				$subQuery = $db->getQuery(true);
				$subQuery->select($db->quoteName('sub.id'));
				$subQuery->from($db->quoteName('#__categories', 'sub'));
				$subQuery->join(
					'INNER', $db->quoteName('#__categories') . ' AS ' . $db->quoteName('this')
					. ' ON ' . $db->quoteName('sub.lft') . ' > ' . $db->quoteName('this.lft')
					. ' AND ' . $db->quoteName('sub.rgt') . ' < ' . $db->quoteName('this.rgt')
				);
				$subQuery->where($db->quoteName('this.id') . ' = ' . (int) $categoryId);
				if ($levels >= 0)
				{
					$subQuery->where($db->quoteName('sub.level') . ' <= ' . $db->quoteName('this.level') . ' + ' . $levels);
				}
				$query->where('(' . $categoryEquals . ' OR ' . $db->quoteName('{{ entity.name }}.{{ entity.special.category.name }}') . ' IN (' . $subQuery . '))');
			}
			else
			{
				$query->where($categoryEquals);
			}
		}
		elseif (is_array($categoryId))
		{
			JArrayHelper::toInteger($categoryId);
			$categoryId = implode(', ', array_unique($categoryId));
			if (!empty($categoryId))
			{
				$op = $state->get('filter.category_id.include', true) ? ' IN ' : ' NOT IN ';
				$query->where($db->quoteName('{{ entity.name }}.{{ entity.special.category.name }}') . $op . '(' . $categoryId . ')');
			}
		}
		return;
	}
{# endoverride #}
{% endif %}
{% if entity.special.created_by or entity.special.author_alias %}
{# override applyFilterAuthor #}

	/**
	 * Filter by author
	 *
{# align("\t", '  ') #}
	 * @param	JDatabase	$db	  The database connector
	 * @param	JDatabaseQuery	$query	  The query to apply the filter to
	 * @param	JObject	$state	  The current state
	 *
	 * @return  void
{# endalign #}
	 */
	private function applyFilterAuthor($db, $query, $state)
	{
{% if entity.special.created_by %}
		$authorWhere = '';
		$authorId = $state->get('filter.author_id');

		if (is_numeric($authorId))
		{
			$op = $state->get('filter.author_id.include', true) ? ' = ' : ' <> ';
			$authorWhere = $db->quoteName('{{ entity.name }}.{{ entity.special.created_by.name }}') . $op . (int) $authorId;
		}
		elseif (is_array($authorId))
		{
			JArrayHelper::toInteger($authorId);
			$authorId = implode(', ', array_unique($authorId));

			if (!empty($authorId))
			{
				$op = $state->get('filter.author_id.include', true) ? ' IN ' : ' NOT IN ';
				$authorWhere = $db->quoteName('{{ entity.name }}.{{ entity.special.created_by.name }}') . $op . '(' . $authorId . ')';
			}
		}

{% endif %}
{% if entity.special.author_alias %}
		$authorAliasWhere = '';
		$authorAlias = $state->get('filter.author_alias');
		if (is_string($authorAlias))
		{
			$op = $state->get('filter.author_alias.include', true) ? ' = ' : ' <> ';
			$authorAliasWhere = $db->quoteName('{{ entity.name }}.{{ entity.special.author_alias.name }}') . $op . $db->quote($authorAlias);
		}
		elseif (is_array($authorAlias))
		{
			JArrayHelper::toString($authorAlias);
			array_walk($authorAlias, 'trim');
			$authorAlias = array_filter($authorAlias);
			array_walk($authorAlias, array($db, 'quote'));
			$authorAlias = implode(', ', array_unique($authorAlias));
			if (!empty($authorAlias))
			{
				$op = $state->get('filter.author_alias.include', true) ? ' IN ' : ' NOT IN ';
				$authorAliasWhere = $db->quoteName('{{ entity.name }}.{{ entity.special.author_alias.name }}') . $op . '(' . $authorAlias . ')';
			}
		}

{% endif %}
{% if entity.special.created_by and entity.special.author_alias %}
		if (!empty($authorWhere) && !empty($authorAliasWhere))
		{
			$query->where('('.$authorWhere.' OR '.$authorAliasWhere.')');
		}
		elseif (empty($authorWhere) && empty($authorAliasWhere))
		{
			// If both are empty we don't want to add to the query
		}
		else
		{
			// One of these is empty, the other is not so we just add both
			$query->where($authorWhere.$authorAliasWhere);
		}

{% elseif entity.special.created_by and not entity.special.author_alias %}
		if (!empty($authorWhere))
		{
			$query->where($authorWhere);
		}

{% elseif not entity.special.created_by and entity.special.author_alias %}
		if (!empty($authorAliasWhere))
		{
			$query->where($authorAliasWhere);
		}

{% endif %}
		return;
	}
{# endoverride #}
{% endif %}
{% if entity.special.publish_up %}
{# override applyFilterPublishingPeriod #}

	/**
	 * Filter by start and end dates
	 *
{# align("\t", '  ') #}
	 * @param	JDatabase	$db	  The database connector
	 * @param	JDatabaseQuery	$query	  The query to apply the filter to
	 * @param	JObject	$state	  The current state
	 *
	 * @return  void
{# endalign #}
	 */
	private function applyFilterPublishingPeriod($db, $query, $state)
	{
		$nullDate = $db->quote($db->getNullDate());
		$nowDate  = $db->quote(JFactory::getDate()->toSql());
		$query->where($db->quoteName('({{ entity.name }}.{{ entity.special.publish_up.name }}') . ' = ' . $nullDate . ' OR ' . $db->quoteName('{{ entity.name }}.{{ entity.special.publish_up.name }}') . ' <= ' . $nowDate . ')');
		$query->where($db->quoteName('({{ entity.name }}.{{ entity.special.publish_down.name }}') . ' = ' . $nullDate . ' OR ' . $db->quoteName('{{ entity.name }}.{{ entity.special.publish_down.name }}') . ' >= ' . $nowDate . ')');
		return;
	}
{# endoverride #}
{% endif %}
{% if entity.special.created or entity.special.modified or entity.special.publish_up %}
{# override applyFilterDateRange #}

	/**
	 * Filter by date range or relative date
	 *
{# align("\t", '  ') #}
	 * @param	JDatabase	$db	  The database connector
	 * @param	JDatabaseQuery	$query	  The query to apply the filter to
	 * @param	JObject	$state	  The current state
	 *
	 * @return  void
{# endalign #}
	 */
	private function applyFilterDateRange($db, $query, $state)
	{
		$dateField = $db->quoteName($state->get('filter.date_field', '{{ entity.name }}.{{ entity.special.created.name }}'));
		$nullDate  = $db->quote($db->getNullDate());
		$nowDate   = $db->quote(JFactory::getDate()->toSql());
		switch ($state->get('filter.date_filtering', 'off'))
		{
			case 'range':
				$startDateRange = $db->quote($state->get('filter.start_date_range', $nullDate));
				$endDateRange   = $db->quote($state->get('filter.end_date_range', $nullDate));
				$query->where($dateField . ' BETWEEN ' . $startDateRange . ' AND ' . $endDateRange);
				break;

			case 'relative':
				$relativeDate = (int) $state->get('filter.relative_date', 0);
				$query->where($dateField . ' >= DATE_SUB(' . $nowDate . ', INTERVAL ' . $relativeDate . ' DAY)');
				break;

			case 'off':
			default:
				break;
		}
		return;
	}
{# endoverride #}
{% endif %}
{# override applyFilterListFilter #}

	/**
	 * Filter list views by user-entered filters
	 *
{# align("\t", '  ') #}
	 * @param	JDatabase	$db	  The database connector
	 * @param	JDatabaseQuery	$query	  The query to apply the filter to
	 * @param	JObject	$state	  The current state
	 * @param	JRegistry	$params	  The parameters
	 *
	 * @return  void
{# endalign #}
	 */
	private function applyFilterListFilter($db, $query, $state, $params)
	{
		$filter = $state->get('list.filter');
		if ($params->get('filter_field') != 'hide' && !empty($filter))
		{
			$filter = JString::strtolower($filter);
			$filter = $db->quote('%' . $db->escape($filter, true) . '%', false);

			switch ($params->get('filter_field'))
			{
{% if entity.special.created_by %}
				case 'author':
					$query->having('LOWER(' . $db->quoteName('author') . ') LIKE ' . $filter);
					break;

{% endif %}
{% if entity.special.hits %}
				case 'hits':
					$query->where($db->quoteName('{{ entity.name }}.{{ entity.special.hits.name }}') . ' >= ' . (int) $filter);
					break;

{% endif %}
				case 'title':
				default:
					// Default to 'title' if parameter is not valid
					$query->where('LOWER(' . $db->quoteName('{{ entity.name }}.{{ entity.special.title.name }}') . ') LIKE ' . $filter);
					break;
			}
		}

		return;
	}
{# endoverride #}
{% if entity.special.language %}
{# override applyFilterLanguage #}

	/**
	 * Filter by language
	 *
{# align("\t", '  ') #}
	 * @param	JDatabase	$db	  The database connector
	 * @param	JDatabaseQuery	$query	  The query to apply the filter to
	 * @param	JObject	$state	  The current state
	 *
	 * @return  void
{# endalign #}
	 */
	private function applyFilterLanguage($db, $query, $state)
	{
		if ($state->get('filter.language')) {
			$language = array($state->get('filter.language'), '*');
			array_walk($language, 'trim');
			$language = array_filter($language);
			array_walk($language, array($db, 'quote'));
			$query->where($db->quoteName('{{ entity.name }}.{{ entity.special.language.name }}') . ' IN (' . implode(', ', array_unique($language)) . ')');
		}
		return;
	}
{# endoverride #}
{% endif %}
{% for property in entity.references.foreignKeys  %}
{% set foreignEntity = project.entities[property.type.entity] %}
{% if property.role == 'category' %}
{% elseif property.role == 'created_by' %}
{% else %}
{# override applyFilter property.name #}

	/**
	 * Filter by {{ foreignEntity.name | title }}
	 *
{# align("\t", '  ') #}
	 * @param	JDatabase	$db	  The database connector
	 * @param	JDatabaseQuery	$query	  The query to apply the filter to
	 * @param	JObject	$state	  The current state
	 *
	 * @return  void
{# endalign #}
	 */
	private function applyFilter{{ property.name | class }}($db, $query, $state)
	{
		${{ property.name | variable }} = $state->get('filter.{{ property.name | variable }}');
		if (is_numeric(${{ property.name | variable }}))
		{
			$query->where($db->quoteName('{{ entity.name }}.{{ property.name | variable }}') . ' = ' . (int) ${{ property.name | variable }});
		}
		return;
	}
{# endoverride #}
{% endif %}
{% endfor %}
{# override applyFilterSearch #}

	/**
	 * Filter by search in title
	 *
{# align("\t", '  ') #}
	 * @param	JDatabase	$db	  The database connector
	 * @param	JDatabaseQuery	$query	  The query to apply the filter to
	 * @param	JObject	$state	  The current state
	 *
	 * @return  void
{# endalign #}
	 */
	private function applyFilterSearch($db, $query, $state)
	{
		$search = trim($state->get('filter.search'));
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where($db->quoteName('{{ entity.name }}.{{ entity.special.key.name }}') . ' = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search, true) . '%');
{% if entity.special.alias %}

				$query->where('(' . $db->quoteName('{{ entity.name }}.{{ entity.special.title.name }}') . ' LIKE ' . $search . ' OR ' . $db->quoteName('{{ entity.name }}.{{ entity.special.alias.name }}') . ' LIKE ' . $search . ')');
{% endif %}
{% if not entity.special.alias %}
				$query->where($db->quoteName('{{ entity.name }}.{{ entity.special.title.name }}') . ' LIKE ' . $search);{% endif %}
			}
		}
		return;
	}
{# endoverride #}
{# override applyOrdering #}

	/**
	 * Apply the ordering clause
	 *
{# align("\t", '  ') #}
	 * @param	JDatabase	$db	  The database connector
	 * @param	JDatabaseQuery	$query	  The query to apply the filter to
	 * @param	JObject	$state	  The current state
	 *
	 * @return  void
{# endalign #}
	 */
	private function applyOrdering($db, $query, $state)
	{
		$ordering  = trim($state->get('list.ordering', '{% if entity.special.ordering %}{{ entity.special.ordering.name }}{% else %}{{ entity.special.key.name }}{% endif %}'));
		$direction = strtoupper($state->get('list.direction', 'ASC'));
		switch ($ordering)
		{
			case '':
				$ordering = '{% if entity.special.ordering %}{{ entity.special.ordering.name }}{% else %}{{ entity.special.key.name }}{% endif %}';
{% if entity.special.category and entity.special.ordering %}
				// no break;

			case '{{ entity.special.ordering.name }}':
			case 'category_title':
				$ordering = $db->quoteName('category.title') . ' ' . $direction . ', ' . $db->quoteName('{{ entity.name }}.{{ entity.special.ordering.name }}');
{% endif %}
				break;
{% for property in entity.references.foreignKeys  %}
{% set foreignEntity = project.entities[property.type.entity] %}
{% if property.role == 'category' %}

			case '{{ property.name | variable }}_name':
				$ordering = $db->quoteName('{{ foreignEntity.name }}.{{foreignEntity.special.title.name }}');
				break;
{% endif %}
{% endfor %}

			default:
				$ordering = $db->quoteName($ordering);
				break;
		}
		$query->order($ordering . ' ' . $direction);

		return;
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
{% endif %}
