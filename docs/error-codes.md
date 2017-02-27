# Error Codes

## Template Errors (10xx)

### 1001 Template has no template declaration

Each template must have a template declaration
  
```
<?template scope="application"?>
```

### 1002 Template declaration has no scope attribute

A template must have a scope attribute
  
```
<?template scope="application"?>
```

Scope can be `application` or `entity`.
