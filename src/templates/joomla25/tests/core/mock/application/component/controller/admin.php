<?template scope="application"?>
<?php
class JControllerAdmin extends TestMockBase
{
    protected $class = 'JControllerAdmin';

    protected $methods = array(
        '__construct',
        'addModelPath',
        'addPath',
        'addViewPath',
        'checkEditId',
        'checkin',
        'createFileName',
        'createModel',
        'createView',
        'delete',
        'display',
        'execute',
        'getInstance',
        'getModel',
        'getName',
        'getTask',
        'getTasks',
        'getView',
        'holdEditId',
        'publish',
        'redirect',
        'registerDefaultTask',
        'registerTask',
        'releaseEditId',
        'reorder',
        'saveorder',
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
