# Types

The Code Generator supports different data types for properties. Many of them are implicitly setting other aspects as defaults.

## boolean

**Implicit settings:**

```json
{
  "type": "boolean",
  "len": 1,
  "input": "yesno"
}
```

## csv

Data is stored in a string with comma separated values.

**Implicit settings:**

```json
{
  "type": "string",
  "len": 255
}
```

Usually, the application is supposed to expand the list into an array or a collection.

## date

**Implicit settings:**

```json
{
  "type": "date",
  "input": "calendar"
}
```

## id

May occur only once per entity.

**Implicit settings:**

```json
{
  "type": "integer",
  "len": 10,
  "role": "key",
  "index": "unique",
  "validation": [
    "positive"
  ]
}
```

## integer

**Implicit settings:**

```json
{
  "type": "integer",
  "len": 10
}
```

## json

Data is stored in a JSON string.

**Implicit settings:**

```json
{
  "type": "string",
  "len": 255
}
```

Usually, the application is supposed to expand the value into an array, a collection, or an object.

## password

**Implicit settings:**

```json
{
  "type": "string",
  "len": 64,
  "input": "password"
}
```

## richtext


**Implicit settings:**

```json
{
  "type": "string",
  "len": 4096,
  "input": "editor"
}
```

## select

For this type, options must be provided.

**Implicit settings:**

```json
{
  "type": "string",
  "len": 64,
  "input": "select"
}
```

## string

**Implicit settings:**

```json
{
  "type": "string",
  "len": 255
}
```

## title

May occur only once per entity.

**Implicit settings:**

```json
{
  "type": "string",
  "len": 64,
  "role": "title",
  "index": "unique"
}
```
