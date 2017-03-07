<?template scope="application"?>
<?php
class JModelForm extends TestMockBase
{
    protected $class = 'JModelForm';

    protected $methods = array(
        '__construct',
        '_createTable',
        '_getList',
        '_getListCount',
        'checkin',
        'checkout',
        'cleanCache',
        'getDbo',
        'getName',
        'getState',
        'getTable',
        'loadForm',
        'loadFormData',
        'populateState',
        'preprocessForm',
        'setDbo',
        'setState',
        'validate',
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
