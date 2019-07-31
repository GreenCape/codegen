# Relations

## belongsTo

#### Receipe: `A` belongsTo `B`

* Set `A.property.name` to a convenient name, fx. `b_id` (lowercase of `B` with suffix `_id`).
* Set `A.property.type` to the same type as the primary key of `B`. If you omit it, it will be set automatically.
* Set `A.relation.name` to the name of the property to contain the related `B` entity, fx. `b` (lowercase of `B`).
* Set `A.relation.type` to `"belongsTo"`. 
* Set `A.relation.property` to `A.property.name`. 
* Set `A.relation.entity` to `B`. 
* Set `A.relation.reference`  to the identifying property of `B` (if different from `id`). The referenced column should have a unique index. 

**Example** with `A` = Article, `B` = User: 
```json
{
  "name": "Article",
  "properties": [
    {
      "name": "author_id"
    }
  ],
  "relations": [
    {
      "name": "author",
      "type": "belongsTo",
      "property": "author_id",
      "entity": "User",
      "reference": "id"
    }
  ]
}
```

## hasOne

## hasMany

## hasManyThru

There are two kinds of `hasManyThru` relations. One is using a mapping entity, the other uses a list of foreign keys, either as CSV or as JSON.

### Mapped Relation

### Embedded Relation

This relation uses a list of foreign keys, either as CSV or as JSON.
You must provide the name of the property for the ids in the relation definition.

#### Receipe: `A` hasMany `B` thru property

* Set `A.property.name` to a convenient name, fx. `b_ids` (lowercase of `B` with suffix `_ids`).
* Set `A.property.type` to `csv` or `json`.
* Set `A.relation.name` to the name of the property to contain the array/collection of related `B` entities, fx. `bs` (lowercase plural of `B`).
* Set `A.relation.type` to `hasManyThru`. 
* Set `A.relation.property` to `A.property.name`. 
* Set `A.relation.entity` to `B`.
* Set `A.relation.reference` to the identifying property of `B` (if different from `id`). The referenced column should have a unique index.

**Example** with `A` = Article, `B` = Tag: 
```json
{
  "name": "Article",
  "properties": [
    {
      "name": "tag_ids",
      "type": "csv"
    }
  ],
  "relations": [
    {
      "name": "tags",
      "type": "hasManyThru",
      "property": "tag_ids",
      "entity": "Tag"
    }
  ]
}
```

Article:
id | tag_ids
---|--------
1 | 1,3,4,7,8

Tags:
id | tag
---|----
1 | One
2 | Two
3 | Three
4 | Four
5 | Five
6 | Six
7 | Seven
8 | Eight

The article is tagged with One, Three, Four, Seven, and Eight. While it is easy to retrieve the tags for an article, this method is not suitable for searching articles for a specific tag.
