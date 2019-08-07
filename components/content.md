# Content
Content is a core component of Joomla. It manages articles.
## ACL
Access control is supported.
The database record contains a pointer to the asset table, which stores the permissions as a nested set.
There is exactly one asset per article.

`Article::asset_id [1]-->[1] Asset::id`

## Categories
Content (articles) can be organised in categories.
The database record contains a pointer to the categories table, which stores the categories as a nested set.
An article can belog to one category, a category can contain many articles.

`Article::catid [n]-->[1] Category::id`

## Workflow
Content supports a basic workflow by tracking creation and last modification time and user

`Article::created_by [n]-->[1] User::id`
`Article::modified_by [n]-->[1] User::id`

## Lifecycle
Content supports a basic lifecycle management by providing fields for the beginning and the end of the publishing period.

## Versioning
Content supports versioning.

## Meta Data
Content supports meta data for search engine optimisation.

## Tags
Content supports tags.
