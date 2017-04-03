<?template scope="application"?>
# {{ project.title }}{% if project.version %} Version {{ project.version }}{% endif %}{% if project.build %} Build {{ project.build }}{% endif %}

This is the documentation of the {{ project.title }} {{ project.type }}.

{{ project.description }}

## Author

{% for author in project.authors %}
{{ author.name | title }} <{{ author.email | lower }}>
{% endfor %}

## License

{{ project.license }}
