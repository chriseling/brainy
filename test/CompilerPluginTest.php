<?php
/**
* Smarty PHPunit tests compiler plugin
*
* @package PHPunit
* @author Uwe Tews
*/

/**
* class for compiler plugin tests
*/
class CompilerPluginTest extends PHPUnit_Framework_TestCase
{
    public function setUp() {
        $this->smarty = SmartyTests::$smarty;
        SmartyTests::init();
    }

    /**
    * test compiler plugin
    */
    public function testCompilerPlugin() {
        $this->smarty->addPluginsDir(dirname(__FILE__)."/PHPunitplugins/");
        $this->assertEquals('test output', $this->smarty->fetch('eval:{test data="test output"}{/test}'));
    }

}
