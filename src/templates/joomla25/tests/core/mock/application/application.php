<?template scope="application"?>
<?php
class JApplication extends TestMockBase
{
    protected $class = 'JApplication';

    protected $methods = array(
        '__construct',
        '__toString',
        '_createConfiguration',
        '_createSession',
        'checkSession',
        'close',
        'dispatch',
        'enqueueMessage',
        'getCfg',
        'getClientId',
        'getHash',
        'getInstance',
        'getMenu',
        'getMessageQueue',
        'getName',
        'getPathway',
        'getRouter',
        'getTemplate',
        'getUserState',
        'getUserStateFromRequest',
        'initialise',
        'isAdmin',
        'isSite',
        'isWinOS',
        'login',
        'logout',
        'render',
        'route',
        'redirect',
        'registerEvent',
        'setUserState',
        'stringURLSafe',
        'triggerEvent',
    );

    protected $deprecated = array(
    );
}
