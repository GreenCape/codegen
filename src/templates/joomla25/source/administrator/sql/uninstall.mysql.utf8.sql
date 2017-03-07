<?template scope="application"?>
-- /**
--  * {{ project.title }} SQL Removal file
--  *
--  * MySQL version 5
--  *
{# align("\t", '  ') #}
--  * @version    {{ project.version }}
--  * @package    {{ project.name | class }}
--  * @author     {{ author.name }} <{{ author.email | lower }}>
--  * @copyright  Copyright (C){{ "now" | date('Y') }} {{ copyright }}. All rights reserved.
--  * @license    {{ project.license }}
{# endalign #}
--  */

{% for entity in project.entities %}
{# override entity.storage.table #}
DROP TABLE IF EXISTS `#__{{ entity.storage.table }}`;
{# endoverride #}
{% endfor %}
