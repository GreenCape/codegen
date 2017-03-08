# Definitions

## Project Definition

The information is defined in a JSON file, usually `project.json`.

```json
{
  "name": "my_project",
  "title": "My Project",
  "version": "1.0.0",
  "build": 213,
  "description": "A cool project",
  "type": "application",
  "copyright": "Company Name",
  "license": "MIT",
  "authors": [
    {
      "name": "John Doe",
      "email": "john.doe@example.com"
    }
  ]
}
```

+ `name`

    The name of the project (package). Should be URL friendy, i.e., all lowercase without spaces. If omitted, the name is derived from the title. At least one of name or title must be declared.

+ `title`

    The title of the project. If omitted, the title is derived from the name. At least one of name or title must be declared.

+ `version`

    *Optional*. The version number for the project.

+ `build`

    *Optional*. A build number. If present, the generator will increase it after every successful execution.

+ `description`

    *Optional*. A short description of the project. Should be less than 256 characters.

+ `type`

    *Optional*. The type of the project.

+ `copyright`

    *Optional*. The name of the copyright holder.

+ `licence`

    *Optional*. The license for the project.

+ `authors`

    *Optional*. A list of `name` and `email` address of the authors.

## Entity Definition

```json
{
  "name": "Article",
  "role": "main",
  "storage": {
    "type": "default",
    "table": "articles"
  },
  "dynKey": [
    "part1",
    "part2"
  ],
  "dynName": [
    "part1",
    "part2"
  ],
  "filters": [
    "category",
    "created_by",
    "modified_by"
  ],
  "properties": [
  ]
}
```

+ `name`

    The name of the entity.

+ `role`

    *Optional*. The function of this entity (table). If omitted, no special treatment is conducted.

    + `main` (was: `firsttable`)

        This role denotes the main entity of the application (i.e., the aggregate root in DDD). Only one main table should be declared.
 
    + `lookup` (was: `lookup_table`)

        A lookup table mostly contains key/value pairs; they can be seen as a kind of enum representation. Example: language code => language name.

    + `map` (was: `nm_table`)

        This is a mapping table, containing id pairs (and optionally additional data) of many-to-many relations.

+ `storage`

    Information about the storage. Currently only the `type` 'default' is supported, requiring the declaration of a `table` name. 

+ `dynKey`

    *Optional*. If the entity uses a compound key, you can declare the list of properties making up the key. If omitted, a property with `role` 'key' is required.

+ `dynName`

    *Optional*. If the entity uses a compound name, you can declare the list of properties making up the display name. If omitted, a property with `role` 'title' is required.

+ `filters`

    *Optional*. A list of properties that may be used for filtering.

+ `properties`

    A list of the entity's properties. See next section for details.

## Property Definition

```json
  "properties": [
    {
      "name": "id", # <field>
      "type": {
        "base": "integer",
        "sign": "unsigned",
        "len": 10,
        "null": false
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
    {
      "name": "title",
      "type": "title",
      "role": "title",
      "input": "string",
      "label": "Title",
      "description": "The name of the entity",
      "hint": "Enter a title",
      "translate": "text",
      "search": "title",
      "form": "record",
      "validation": [
        {
          "rule": "maxlen",
          "value": 64
        }
      ],
      "index": "unique"
    },
    {
      "name": "category",
      "type": {
        "base": "reference",      
        "table": "categories", # <ref>
        "entity": "Category",
        "key": "id", # <refid>
        "title": "name", # <refname>
        "constraint": "" # <refcond>
      }
    },
    {
      "name": "foo",
      "type": "int",
      "input": "select",
      "options": [ # <values>
        {
          "key": 1,
          "value": "One"
        },
        {
          "key": 2,
          "value": "Two"
        },
        {
          "key": 3,
          "value": "Three"
        }
      ],
      "form": "parameters"
    }
```

+ `name`
+ `type`

    - enum

    Additionally, templates can pre-define field types by semantic names according to the implementation.

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

+ `role`

    Property roles allow to use any property name you want in your entities, and make the generator aware of its special meaning.

    Within a template, you can access these special properties with the `entity.special` namespace.

    ```twig
    {% for entity in project.entities %}
    Entity: {{ entity.name }}
    {% if entity.special.key %}
    - Primary key: {{ entity.special.key.name }}
    {% endif %}
    {% if entity.special.title %}
    - Display name: {{ entity.special.title.name }}
    {% endif %}
    ```

    - `key`

        The primary key (id) of the entity

    - `title`

        The display name of the entity

    - `alias`

        Alternative name of the entity, usually URL friendy

    - `category`

        A foreign key to a category

    - `featured`

        A flag, whether the entity is featured

    - `sticky`

        A flag, whether the entity is sticky

    - `published`

        A flag, whether the entity is published

    - `created_by`

        A foreign key to a user

    - `parent`

        A foreign key to another entity of same class

    - `language`

        A language key

    - `ordering`

        An ordering number
    
    - `created`
    - `modified`
    - `modified_by`
    - `publish_up`
    - `publish_down`
    - `attribs`
    - `version`
    - `introtext`
    - `fulltext`
    - `author_alias`

+ `input`

    - `none`
    - `string`
    - `text`

        Textarea

    - `richtext`

        Editor

    - `num`
    - `date`
    - `datetime`
    - `yesno`
    - `multi`

        Multiselect. `options` is required.

    - `check`

        Checkbox. `options` is required.

    - `select`

        `options` is required.

    - `radio`

        `options` is required.

    - `file`
    - `image`
    - `url`

+ `options`
+ `label`
+ `description`
+ `hint`
+ `translate`
+ `search`
+ `default`
+ `position`
+ `form`
+ `validation`
+ `index`
