<?template scope="application"?>
<?php
class JFormField extends TestMockBase
{
    protected $class = 'JFormField';

    protected $methods = array(
        '__construct',
        'getFieldName',
        'getId',
        'getLabel',
        'getName',
        'getTitle',
        'setForm',
        'setup',
    );

    protected $deprecated = array(
    );

    protected static $staticMethods = array(
    );
}
