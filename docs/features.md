# Feature Support

Some core features use the same data structures all over.
To avoid verbose repetitions in the project definition, CodeGen allows to add a list of supported features.

Templates are expected to cater for that information.

## Features

All features should be provided by all templates. However, some environments do not allow every feature.
The generator ignores a requested feature silently, if it is not supported by the template.

Standard features are

- **acl** - access control
- **categories**
- **tags**
- **workflow** - in its simplest form, workflow just keeps track of creation and modification of an entity.
- **locking** - prevents content to be edited more than once at a time.
- **lifecycle** - in its simplest form, this is a state like published/unpublished
- **versioning**
- **metadata** - supports meta data for publishing
- **multilanguage** - makes content translatable
 
## Definition of Features

Features are defined in a file named '`<template>/.codegen/features.json`'. 
The structure is similar to that of the project definition.
The file contains a JSON encoded array indexed by feature name
containing a list of (fractions of) entities for each feature.
```json
{
  "acl": {
    "entities": [...]
  },
  "categories": {
    "entities": [...]
  },
  "tags": {
    "entities": [...]
  },
  "workflow": {
    "entities": [...]
  },
  "locking": {
    "entities": [...]
  },
  "lifecycle": {
    "entities": [...]
  },
  "versioning": {
    "entities": [...]
  },
  "metadata": {
    "entities": [...]
  },
  "multilanguage": {
    "entities": [...]
  }
}
```
The `entities` array may contain two different kinds of entity definitions.

The first one is the same as the project entity definition.
It should be used for embedded entities only.
The entity is added to the project as a whole.

The second one is only a fragment of a project entity definition.
It is known apart from the former by having no `name` attribute.
The attributes `filters`, `properties` and `relations` are merged into project's entity.

To control which entity the features are merged into, the feature entity definition uses one of the two attributes
`include` or `exclude`. Both can be an array of entity roles.
If `include` contains roles, only entities with one of those roles will be changed. 
If `exclude` contains roles, only entities not having one of those roles will be changed. 
Using both attributes can lead to unexpected results and should be avoided. 

**Examples**
```json
{
  "include": [
    "main"
  ],
  "properties": [...],
  "relations": [...]
}
```
This feature applies to the main entity (aggregate root) only.
```json
{
  "exclude": [
    "lookup",
    "map",
    "config",
    "compound"
  ],
  "properties": [...],
  "relations": [...]
}
```
This feature applies to all entities that have neither `lookup`, `map`, `config` nor `compound` roles.

