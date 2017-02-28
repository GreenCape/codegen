<?template scope="application"?>
# {{ project.title }}

{{ project.description }}

## Author

{% for author in project.authors %}
{{ author.name | title }} <{{ author.email | lower }}>
{% endfor %}

## License

{{ project.license }}
