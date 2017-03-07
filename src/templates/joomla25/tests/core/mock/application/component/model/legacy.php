<?template scope="application"?>
<?php
class JModelLegacy extends TestMockBase
{
    protected $class = 'JModelLegacy';

    protected $methods = array(
        '__construct',
        '_createTable',
        '_getList',
        '_getListCount',
        'cleanCache',
        'getDbo',
        'getName',
        'getState',
        'getTable',
        'populateState',
        'setDbo',
        'setState',
    );

    protected $deprecated = array(
    );

    protected static $staticMethods = array(
        '_createFileName',
        'addIncludePath',
        'addTablePath',
        'getInstance',
    );
}
