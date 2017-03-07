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
