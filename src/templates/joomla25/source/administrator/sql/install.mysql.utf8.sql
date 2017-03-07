<?template scope="application"?>
-- /**
--  * {{ project.title }} SQL installation file
--  *
--  * MySQL version 5
--  *
{# align("\t", '  ') #}
--  * @version    {{ project.version }}
--  * @package    {{ project.name | class }}
{% for author in project.authors %}
--  * @author     {{ author.name }} <{{ author.email | lower }}>
{% endfor %}
--  * @copyright  Copyright (C){{ "now" | date('Y') }} {{ project.copyright }}. All rights reserved.
--  * @license    {{ project.license }}
{# endalign #}
--  */
{% for entity in project.entities %}
{% if entity.role != 'external' %}
{# override entity.storage.table #}

CREATE TABLE IF NOT EXISTS `#__{{ entity.storage.table }}` (
{% for property in entity.properties %}
  `{{ property.name | table }}` {{ property.type.mysql }},
{% endfor %}
{% if entity.special.key %}
  PRIMARY KEY (`{{ entity.special.key.name }}`)
{% else %}
  PRIMARY KEY ({# trim #}{% for property in entity.dynKey %}`{{ property.name }}`, {% endfor %}}{# endtrim #})
{% endif %}
);
{# endoverride #}
{% endif %}
{% endfor %}
{# override sample_data #}
