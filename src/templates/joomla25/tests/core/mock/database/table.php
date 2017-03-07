<?template scope="application"?>
<?php
class JTable extends TestMockBase
{
    protected $class = 'JTable';

    protected $methods = array(
        '__construct',
        '_getAssetName',
        '_getAssetParentId',
        '_getAssetTitle',
        '_lock',
        '_unlock',
        'bind',
        'check',
        'checkIn',
        'checkOut',
        'delete',
        'getDbo',
        'getFields',
        'getInstance',
        'getKeyName',
        'getNextOrder',
        'getRules',
        'getTableName',
        'hit',
        'isCheckedOut',
        'load',
        'move',
        'publish',
        'reorder',
        'reset',
        'save',
        'setDBO',
        'setRules',
        'store',
    );

    protected $deprecated = array(
        'canDelete',
        'toXML',
    );

    public function __construct()
    {
        $this->class = get_class();
    }

    protected static $staticClass = 'JTable';

    protected static $staticMethods = array(
        'addIncludePath',
    );

    protected static $staticDeprecated = array(
    );
}
