<?template scope="application"?>
<?php
class JFactory extends TestMockBase
{
    protected static $staticClass = 'JFactory';

    protected static $staticMethods = array(
        'createConfig',
        'createDbo',
        'createDocument',
        'createLanguage',
        'createMailer',
        'createSession',
        'getACL',
        'getApplication',
        'getCache',
        'getConfig',
        'getDate',
        'getDbo',
        'getDocument',
        'getEditor',
        'getFeedParser',
        'getLanguage',
        'getMailer',
        'getSession',
        'getStream',
        'getURI',
        'getUser',
        'getXML',
    );

    protected static $staticDeprecated = array(
        '_createConfig',
        '_createDbo',
        '_createDocument',
        '_createLanguage',
        '_createMailer',
        '_createSession',
        'getXMLParser',
    );

    /** @var  JAcl  */
    public static $acl = null;

    /** @var  JApplication  */
    public static $application = null;

    /** @var  JCache  */
    public static $cache = null;

    /** @var  JConfig  */
    public static $config = null;

    /** @var  JDatabase  */
    public static $database = null;

    /** @var  JDate  */
    public static $date = null;

    /** @var  JDocument  */
    public static $document = null;

    /** @var  JEditor  */
    public static $editor = null;

    /** @var  JFeedParser  */
    public static $feedParser = null;

    /** @var  JLanguage  */
    public static $language = null;

    /** @var  JMailer  */
    public static $mailer = null;

    /** @var  JSession  */
    public static $session = null;

    /** @var  JStream  */
    public static $stream = null;

    /** @var  JUri  */
    public static $uri = null;

    /** @var  JUser  */
    public static $user = null;

    /** @var  JXml  */
    public static $xml = null;

    /**
     * @return JAcl|null
     */
    public static function mockGetACL()
    {
        return self::$acl;
    }

    /**
     * @return JApplication|null
     */
    public static function mockGetApplication()
    {
        return self::$application;
    }

    /**
     * @return JCache|null
     */
    public static function mockGetCache()
    {
        return self::$cache;
    }

    /**
     * @return JConfig|null
     */
    public static function mockGetConfig()
    {
        return self::$config;
    }

    /**
     * @return JDate|null
     */
    public static function mockGetDate()
    {
        return self::$date;
    }

    /**
     * @return JDatabase|null
     */
    public static function mockGetDbo()
    {
        return self::$database;
    }

    /**
     * @return JDocument|null
     */
    public static function mockGetDocument()
    {
        return self::$document;
    }

    /**
     * @return JEditor|null
     */
    public static function mockGetEditor()
    {
        return self::$editor;
    }

    /**
     * @return JFeedParser|null
     */
    public static function mockGetFeedParser()
    {
        return self::$feedParser;
    }

    /**
     * @return JLanguage|null
     */
    public static function mockGetLanguage()
    {
        return self::$language;
    }

    /**
     * @return JMailer|null
     */
    public static function mockGetMailer()
    {
        return self::$mailer;
    }

    /**
     * @return JSession|null
     */
    public static function mockGetSession()
    {
        return self::$session;
    }

    /**
     * @return JStream|null
     */
    public static function mockGetStream()
    {
        return self::$stream;
    }

    /**
     * @return JUri|null
     */
    public static function mockGetURI()
    {
        return self::$uri;
    }

    /**
     * @return JUser|null
     */
    public static function mockGetUser()
    {
        return self::$user;
    }

    /**
     * @return JXml|null
     */
    public static function mockGetXML()
    {
        return self::$xml;
    }
}
