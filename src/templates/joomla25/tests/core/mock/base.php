<?template scope="application"?>
<?php
/**
 * Mock Base Class
 *
 * PHP version 5.3
 *
 * @version    1.0.0
 * @package    UnitTest
 * @author     Niels Braczek <nbraczek@bsds.de>
 * @copyright  Copyright (C)2012 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/**
 * Mock Base Class
 *
 * @version  Release: 1.0.0
 * @package  UnitTest
 * @since    1.0.0
 */
class TestMockBase implements PHPUnit_Framework_MockObject_MockObject
{
    /** @var  PHPUnit_Framework_TestCase  The test case using this mock */
    public $testCase = null;

    /** @var  string  Name of the mocked class */
    protected $class = null;

    /** @var  array  List of all supported methods of the original class */
    protected $methods = array();

    /** @var  array  List of all deprecated methods of the original class */
    protected $deprecated = array();

    /** @var  PHPUnit_Framework_MockObject_InvocationMocker */
    private $__phpunit_invocationMocker;

    /** @var  PHPUnit_Framework_TestCase  The test case using this mock */
    public static $staticTestCase = null;

    /** @var  string  Name of the mocked class */
    protected static $staticClass = null;

    /** @var  array  List of all supported static methods of the original class */
    protected static $staticMethods = array();

    /** @var  array  List of all deprecated static methods of the original class */
    protected static $staticDeprecated = array();

    /** @var  PHPUnit_Framework_MockObject_InvocationMocker */
    private static $__phpunit_staticInvocationMocker;

    public function __construct()
    {
        if (is_null($this->testCase) && !is_null(static::$staticTestCase)) {
            $this->testCase = static::$staticTestCase;
        }
    }

    /**
     * Generic method invoker
     *
     * Any method in the self::$methods is accepted. It will return NULL, if no mocking method is found in the
     * test case or in the class itself.
     *
     * To define a mock method (callback) in the test case, just add a method, which is named according to the pattern
     * mock<classname><methodname>. 'mockMyclassDisplay' will for example mock Myclass::display.
     *
     * If no callback is defined, this class (its descendant) is checked for a method named according to the pattern
     * mock<methodname>. 'mockDisplay' will mock Myclass::display, if Myclass is the child of this class.
     *
     * If no callback is defined, NULL is returned.
     *
     * @param	string	$method	  * @param	array	$arguments	  *
     * @throws  BadMethodCallException on call without test case
     * @return  mixed
     */
    public final function __call($method, $arguments)
    {
        if (is_null($this->testCase)) {
            throw new BadMethodCallException("Call of method {$this->class}::{$method} without test case");
        }
        if (!in_array($method, $this->methods)) {
            if (in_array($method, $this->deprecated)) {
                $this->testCase->fail("Call of deprecated method {$this->class}::{$method}");
            } else {
                $this->testCase->fail("Call of undefined method {$this->class}::{$method}");
            }
        }
        if (is_callable(array($this->testCase, 'mock' . $this->class . $method))) {
            return call_user_func_array(array($this->testCase, 'mock' . $this->class . ucfirst($method)), $arguments);
        } elseif (method_exists($this, 'mock' . $method)) {
            return call_user_func_array(array($this, 'mock' . ucfirst($method)), $arguments);
        } else {
            return null;
        }
    }

    /**
     * Generic static method invoker
     *
     * Any method in the self::$methods is accepted. It will return NULL, if no mocking method is found in the
     * test case or in the class itself.
     *
     * To define a mock method (callback) in the test case, just add a method, which is named according to the pattern
     * mock<classname><methodname>. 'mockMyclassDisplay' will for example mock Myclass::display.
     *
     * If no callback is defined, this class (its descendant) is checked for a method named according to the pattern
     * mock<methodname>. 'mockDisplay' will mock Myclass::display, if Myclass is the child of this class.
     *
     * If no callback is defined, NULL is returned.
     *
     * @param	string	$method	  * @param	array	$arguments	  *
     * @throws  BadMethodCallException on call without test case
     * @return  mixed
     */
    public final static function __callStatic($method, $arguments)
    {
        $class = static::$staticClass;
        if (is_null(static::$staticTestCase)) {
            throw new BadMethodCallException("Call of method {$class}::{$method} without test case");
        }
        if (!in_array($method, static::$staticMethods)) {
            if (in_array($method, static::$staticDeprecated)) {
                static::$staticTestCase->fail("Call of deprecated static method {$class}::{$method}");
            } else {
                static::$staticTestCase->fail("Call of undefined static method {$class}::{$method}");
            }
        }
        if (is_callable(array(static::$staticTestCase, 'mock' . $class . $method))) {
            return call_user_func_array(array(static::$staticTestCase, 'mock' . $class . ucfirst($method)), $arguments);
        } elseif (method_exists(get_called_class(), 'mock' . $method)) {
            return call_user_func_array(array(get_called_class(), 'mock' . ucfirst($method)), $arguments);
        } else {
            return null;
        }
    }

    /**
     * @param	PHPUnit_Framework_TestCase	$testCase	  *
     * @return  TestMockBase
     */
    public static function getMock($testCase)
    {
        static::$staticTestCase = $testCase;
        $instance = new static;

        return $instance;
    }

    public function expects(PHPUnit_Framework_MockObject_Matcher_Invocation $matcher)
    {
        return $this->__phpunit_getInvocationMocker()->expects($matcher);
    }

    public static function staticExpects(PHPUnit_Framework_MockObject_Matcher_Invocation $matcher)
    {
        return self::__phpunit_getStaticInvocationMocker()->expects($matcher);
    }

    public function __phpunit_getInvocationMocker()
    {
        if (is_null($this->__phpunit_invocationMocker)) {
            $this->__phpunit_invocationMocker = new PHPUnit_Framework_MockObject_InvocationMocker;
        }
        return $this->__phpunit_invocationMocker;
    }

    public static function __phpunit_getStaticInvocationMocker()
    {
        if (is_null(self::$__phpunit_staticInvocationMocker)) {
            self::$__phpunit_staticInvocationMocker = new PHPUnit_Framework_MockObject_InvocationMocker;
        }
        return self::$__phpunit_staticInvocationMocker;
    }

    public function __phpunit_hasMatchers()
    {
        return self::__phpunit_getStaticInvocationMocker()->hasMatchers() ||
            $this->__phpunit_getInvocationMocker()->hasMatchers();
    }

    public function __phpunit_verify()
    {
        self::__phpunit_getStaticInvocationMocker()->verify();
        $this->__phpunit_getInvocationMocker()->verify();
    }

    public function __phpunit_cleanup()
    {
        self::$__phpunit_staticInvocationMocker = null;
        $this->__phpunit_invocationMocker       = null;
    }
}
