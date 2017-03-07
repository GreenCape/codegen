<?template scope="application"?>
<?php
class JModelList extends TestMockBase
{
    protected $class = 'JModelList';

    protected $methods = array(
        '__construct',
        '_createTable',
        '_getList',
        '_getListCount',
        '_getListQuery',
        'cleanCache',
        'getDbo',
        'getItems',
        'getListQuery',
        'getName',
        'getPagination',
        'getStart',
        'getState',
        'getStoreId',
        'getTable',
        'getTotal',
        'getUserStateFromRequest',
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
