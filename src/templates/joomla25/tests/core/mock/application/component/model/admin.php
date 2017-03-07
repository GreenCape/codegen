<?template scope="application"?>
<?php
class JModelAdmin extends TestMockBase
{
    protected $class = 'JModelAdmin';

    protected $methods = array(
        '__construct',
        '_createTable',
        '_getList',
        '_getListCount',
        'batch',
        'batchAccess',
        'batchCopy',
        'batchLanguage',
        'batchMove',
        'canDelete',
        'canEditState',
        'checkin',
        'checkout',
        'cleanCache',
        'delete',
        'generateNewTitle',
        'getDbo',
        'getItem',
        'getName',
        'getReorderConditions',
        'getState',
        'getTable',
        'loadForm',
        'loadFormData',
        'populateState',
        'prepareTable',
        'preprocessForm',
        'publish',
        'reorder',
        'save',
        'saveorder',
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
