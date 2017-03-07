<?template scope="application"?>
<?php
class JController extends TestMockBase
{
    protected $class = 'JController';

    protected $methods = array(
        '__construct',
        'addModelPath',
        'addPath',
        'addViewPath',
        'checkEditId',
        'createFileName',
        'createModel',
        'createView',
        'display',
        'execute',
        'getInstance',
        'getModel',
        'getName',
        'getTask',
        'getTasks',
        'getView',
        'holdEditId',
        'redirect',
        'registerDefaultTask',
        'registerTask',
        'releaseEditId',
        'setMessage',
        'setPath',
        'setRedirect',
        'unregisterTask',
    );

    protected $deprecated = array(
        'authorise',
        'authorize',
        'setAccessControl',
    );
}
