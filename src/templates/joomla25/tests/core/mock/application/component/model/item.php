<?template scope="application"?>
<?php
class JModelItem extends TestMockBase
{
    protected $class = 'JModelItem';

    protected $methods = array(
        '__construct',
        '_createTable',
        '_getList',
        '_getListCount',
        'cleanCache',
        'getDbo',
        'getName',
        'getState',
        'getStoreId',
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
