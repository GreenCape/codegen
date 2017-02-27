# {{title}}

{{description}}

## Author

{% for author in authors %}
{{author.name}} <{{author.email}}>
{% endfor %}

## License

{{license}}
