# Templates

The templates for the generator use the Twig syntax. Actually, the final files are rendered using Twig.

## File Names

The generator is provided with the name of a directory containing all application files. The file names of the templates are used for the generated files. Some special characters allow interpolation of file (and directory) names.
  
Character | Replacement    | Example
----------+----------------+--------
`$`       | `project.name` | Project's name is 'My Project': `$.css` => `my_project.css`
`#`       | `entity.name`  | Entity's name is 'Person': `#_model.php` => `person_model.php`
`#s`      | `entity.name | plural` | Entity's name is 'Person': `#s_view.php` => `people_view.php`

## Scopes

Templates can be made for different scopes.
The generator supports `application` and `entity`. 
To declare the template scope, add a template declaration as the very first line of the template file:

```
<?template scope="entity"?>
```

The template declaration is removed from the output.

Files without a template declaration are copied verbatim.

## Template Defaults

You can use pre-defined project settings, data types and entity definitions to address specific implementation details of the target system.

> *Example:* If your template requires `project.version` to have a value, you can define a default value.

> *Example:* If your template supports references to existing entities of the target system (f.x. categories or tags), you can define the corresponding category and tag entities to provide the template with the additional data.

On the top level of your template directory, create a directory `.codegen` to store your pre-definitions.

+ `.codegen/project.json`

    May contain default settings for for the [Project Definitions](definitions.md).

+ `.codegen/types.json`

    Templates can pre-define field types by semantic names according to the implementation. The declaration is the same as for type within a property; just the keyword 'type' is replaced with the name of the type. See [Property Definitions](definitions.md).

    ```json
    "id": {
      "base": "integer",
      "sign": "unsigned",
      "len": 10,
      "null": false
    },
    "title": {
      "base": "string",
      "len": 64,
      "null": false
    },
    "category": {
      "base": "reference",      
      "table": "categories", # <ref>
      "entity": "Category",
      "key": "id", # <refid>
      "title": "name", # <refname>
      "constraint": "" # <refcond>
    }
    ```

+ `.codegen/entities.json` or `.codegen/entities/*.json`

    Entities from the target system that may be referenced by project entities may be defined in `.codegen/entities.json` (all in one) or `.codegen/entities/*.json` (one file for each entity). See [Entity Definitions](definitions.md).
    
    If the application project defines an entity with the same name as a pre-defined entity, the pre-defined entity gets overwritten.

The generator will read the content of these files, if present, and merge them with the definitions provided by the application project.

## Project Data

When a template is rendered, it is provided with information about the project and its entities.

```json
{
  "project":  {
    "name": "my_project",
    "title": "My Project",
    "version": "1.0.0",
    "description": "A cool project",
    "type": "application",
    "license": "MIT",
    "authors": [
      {
        "name": "John Doe",
        "email": "john.doe@example.com"
      }
    ],
    "entities": [...]
  }
}
```

You can access the data in the `project` namespace.

```twig
# {{ project.title }} {{ project.version }}
{% for author in project.authors %}
{{ author.name }} <{{ author.email }}>
{% endfor %}
```

will result in

```markdown
# My Project 1.0.0
John Doe <john.doe@example.com>
```

Non-existing variables will be rendered as empty strings.

Depending on scope, additional data is provided.

## Entity Data

In all scopes, the `project.entities` contains all entities.
In the `entity` scope, the current entity is additionally provided in the `entity` namespace. 

Entity data structure:
```json
{
  "name": "Article",
  "role": "main",
  "storage": {
    "type": "default",
    "table": "articles"
  },
  "properties": [ /* list of properties */ ],
  "special": [ /* list of properties */ ],
  "dynKey": [ /* list of properties */ ],
  "dynName": [ /* list of properties */ ],
  "filters": [ /* list of properties */ ],
  "listFields": [ /* list of properties */ ],
  "details": [ /* list of entities with referencing properties */ ],
  "references": [ /* list of referenced entities and properties referencing those entities */ ]
}
```

+ `properties`

    Each entity has properties. All properties are listed in the `properties` section.

+ `special`

    Properties may have a special meaning, expressed by their role. All properties with a role are collected in `special`, so it is possible to access the properties by role.

    ```twig
    {% if entity.special.category %}
    This is only rendered, if the entity has a category property
    The current entity stores the category in the property named
    '{{ entity.special.category.name }}'.
    {% endif %}
    ```

+ `dynKey`

    Ordered list of properties making up a composed primary key.

+ `dynName`

    Ordered list of properties making up a composed display name.

+ `filters`

    Ordered list of properties usable for filtering

+ `listFields`

    Ordered list of properties to be displayed in list (table) views.

+ `details`

    List of foreign keys pointing to this entity.

    ```json
    [
      {
        "entity": Article,
        "reference": author
      },
      {
        "entity": Article,
        "reference": modified
      }
    ]
    ```

    - `entity`

        The related (foreign) entity.

    - `reference`

        The property in the foreign entity pointing to the current one.

+ `references`

    List of foreign keys in this entity.

    ```json
    {
      "users": [ /* list of properties referencing users */ ],
      "categories": [ /* list of properties referencing categories */ ],
      "foreignKeys": [ /* list of properties referencing entities */ ]
    }
    ```

    - Entites

        For each referenced entity a list of properties (foreign keys) pointing to that entity is provided.

    - `foreignKeys`

        List of all foreign keys from this entity.

## Property Data

```json
    {
      "name": "id", # <field>
      "type": {
        "base": "int",
        "sign": "unsigned",
        "len": 10,
        "null": false,
        "mysql": "INT(10) UNSIGNED NOT NULL AUTOINBREMENT",
        "php": "int"
      },
      "role": "key", # <special>
      "input": "none",
      "label": "ID",
      "description": "The ID of the entity", # <comment>
      "default": "null",
      "position": 1, # <pos>
      "form": "record",
      "validation": [
        {
          "rule": "positive",
          "value": 1
        },
        {
          "rule": "integer",
          "value": 1
        }
      ],
      "index": "primary" # <key>
    },

```

## Filters

You can use any [predefined Twig filter](http://twig.sensiolabs.org/doc/2.x/filters/index.html). The generator provides additional filters.

### Numerus

- `singular`
- `plural`

### Format

- `title` ('My Variable')
- `variable` ('myVariable')
- `class` ('MyVariable')
- `table` ('my_variable')
- `file` ('my_variable')
- `dash` ('my-variable')
- `constant` ('MY_VARIABLE')

## Functions

+ `align(separator[, new_separator])`

    *Not yet implemented*. Splits all lines at `separator`, pads all parts to match the length of the longest item in that column, and joins the parts using `new_separator`.

+ `override item`

    *Not yet implemented*.

+ `noDebug`

    *Not yet implemented*.

+ `context`

    *Not yet implemented*.

+ `trim`

    *Not yet implemented*. Removes leading and trailing white spaces and commas.

+ `sort`

    *Not yet implemented*.
