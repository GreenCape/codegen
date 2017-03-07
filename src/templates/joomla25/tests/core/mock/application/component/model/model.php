<?template scope="application"?>
<?php
class JModel extends TestMockBase
{
    protected $class = 'JModel';

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
