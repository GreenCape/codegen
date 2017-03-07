<?template scope="application"?>
<?php
class JView extends TestMockBase
{
    protected $class = 'JView';

    protected $methods = array(
        '__construct',
        '_addPath',
        '_createFileName',
        '_setPath',
        'addHelperPath',
        'addTemplatePath',
        'assign',
        'assignRef',
        'display',
        'escape',
        'get',
        'getLayout',
        'getLayoutTemplate',
        'getModel',
        'getName',
        'loadHelper',
        'loadTemplate',
        'setEscape',
        'setLayout',
        'setLayoutExt',
        'setModel',
    );

    protected $deprecated = array(
    );

    protected static $staticMethods = array(
    );
}
