<?template scope="application"?>
<?php
class JCategoryNode extends TestMockBase
{
    protected $class = 'JCategoryNode';

    protected $methods = array(
        '__construct',
        'addChild',
        'getAuthor',
        'getChildren',
        'getMetadata',
        'getNumItems',
        'getParams',
        'getParent',
        'getPath',
        'getSibling',
        'hasChildren',
        'hasParent',
        'removeChild',
        'setAllLoaded',
        'setParent',
        'setSibling',
    );

    protected $deprecated = array(
    );

    protected static $staticClass = 'JCategoryNode';

    protected static $staticMethods = array(
    );
}
