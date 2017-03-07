<?template scope="application"?>
<?php
class JControllerForm extends TestMockBase
{
    protected $class = 'JControllerForm';

    protected $methods = array(
        '__construct',
        'add',
        'addModelPath',
        'addPath',
        'addViewPath',
        'allowAdd',
        'allowEdit',
        'allowSave',
        'batch',
        'cancel',
        'checkEditId',
        'createFileName',
        'createModel',
        'createView',
        'display',
        'edit',
        'execute',
        'getInstance',
        'getModel',
        'getName',
        'getRedirectToItemAppend',
        'getRedirectToListAppend',
        'getTask',
        'getTasks',
        'getView',
        'holdEditId',
        'postSaveHook',
        'redirect',
        'registerDefaultTask',
        'registerTask',
        'releaseEditId',
        'save',
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
