<?template scope="application"?>
<?php
class JDatabase extends TestMockBase
{
    protected $class = 'JDatabase';

    protected $methods = array(
        '__construct',
        'connected',
        'dropTable',
        'escape',
        'fetchArray',
        'fetchAssoc',
        'fetchObject',
        'freeResult',
        'getAffectedRows',
        'getCollation',
        'getConnection',
        'getCount',
        'getDatabase',
        'getDateFormat',
        'getLog',
        'getMinimum',
        'getNullDate',
        'getNumRows',
        'getPrefix',
        'getQuery',
        'getTableColumns',
        'getTableCreate',
        'getTableKeys',
        'getTableList',
        'getUTFSupport',
        'getVersion',
        'insertid',
        'insertObject',
        'isMinimumVersion',
        'loadAssoc',
        'loadAssocList',
        'loadColumn',
        'loadNextObject',
        'loadNextRow',
        'loadObject',
        'loadObjectList',
        'loadResult',
        'loadRow',
        'loadRowList',
        'lockTable',
        'query',
        'execute',
        'quote',
        'quoteName',
        'quoteNameStr',
        'replacePrefix',
        'renameTable',
        'select',
        'setDebug',
        'setQuery',
        'setUTF',
        'transactionCommit',
        'transactionRollback',
        'transactionStart',
        'truncateTable',
        'updateObject',
        'unlockTables',
        'getTableFields',
    );

    protected $deprecated = array(
        'addQuoted',
        'debug',
        'explain',
        'getErrorMsg',
        'getErrorNum',
        'getEscaped',
        'getTicker',
        'hasUTF',
        'isQuoted',
        'loadResultArray',
        'nameQuote',
        'queryBatch',
        'stderr',
    );

    protected static $staticClass = 'JDatabase';

    protected static $staticMethods = array(
        'getConnectors',
        'getInstance',
        'splitSql',
    );

    protected static $staticDeprecated = array(
    );

    public function mockQuoteName($str)
    {
        return "`{$str}`";
    }
}
