<?template scope="application"?>
<?php
class JVersion extends TestMockBase
{
    protected $class = 'JVersion';

    protected $methods = array(
        'isCompatible',
        'getHelpVersion',
        'getShortVersion',
        'getLongVersion',
        'getUserAgent',
    );

    protected $deprecated = array(
    );
}
