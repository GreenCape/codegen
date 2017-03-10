# Error Codes

## Template Errors (10xx)

### 1000 Twig template error

This error happens when Twig fails to render a template.
The line number in the message does not account for the template declaration,
since that is removed, before Twig gets access to the template.
Thus the correct line number is the stated number + 1. 

### 1001 *Unused*
  
### 1002 Template declaration has no scope attribute

A template must have a scope attribute
  
```
<?template scope="application"?>
```

Scope can be `application` or `entity`.

## Project Definition Errors (11xx)

### 1101 One of "name" or "title" must be set.

A project declaration must contain a name or title property. It is valid to provide both, of course.

### 1102 Properties are read-only

Project properties can not be written.

## Entity Definition Errors (12xx)

### 1201 "name" must be set.

An entity declaration must contain a name.

### 1202 Properties are read-only

Entity properties can not be written.

### 1203 Unknown relation type '<type>'

Only 'belongsTo', 'hasMany', 'hasOne' and 'hasManyThru' relations are supported.

### 1204 Relation type must be specified

A releation declaration  must contain one of 'belongsTo', 'hasMany', 'hasOne' or 'hasManyThru'.

### Logic Errors (90xxx)

### 9001 Properties are read-only

Template data objects have read-only properties. A write attempt causes this error.
