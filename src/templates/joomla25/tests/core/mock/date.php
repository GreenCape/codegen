<?template scope="application"?>
<?php
class JDate extends TestMockBase
{
    protected $class = 'JDate';

    protected $methods = array(
        '__construct',
        '__toString',
        'calendar',
        'dayToString',
        'format',
        'getOffsetFromGMT',
        'monthToString',
        'setTimezone',
        'toISO8601',
        'toRFC822',
        'toSql',
        'toUnix',
    );

    protected $deprecated = array(
        'setOffset',
        'toFormat',
        'toMySQL',
    );

    protected static $staticClass = 'JDate';

    protected static $staticMethods = array(
        'getInstance',
    );

    protected static $staticDeprecated = array(
    );
}
