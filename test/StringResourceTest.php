<?php
/**
* Smarty PHPunit tests for string resources
*
* @package PHPunit
* @author Uwe Tews
*/

/**
* class for string resource tests
*/
class StringResourceTest extends PHPUnit_Framework_TestCase
{
    public function setUp() {
        $this->smarty = SmartyTests::$smarty;
        $this->smarty->clearAllCache();
        $this->smarty->clearCompiledTemplate();
        SmartyTests::init();
    }

    protected function relative($path) {
        $path = str_replace( dirname(__FILE__), '.', $path );
        if (DS == "\\") {
            $path = str_replace( "\\", "/", $path );
        }

        return $path;
    }

    /**
    * test template string exits
    */
    public function testTemplateStringExists1() {
        $tpl = $this->smarty->createTemplate('string:{$foo}');
        $this->assertTrue($tpl->source->exists);
    }
    public function testTemplateStringExists2() {
        $this->assertTrue($this->smarty->templateExists('string:{$foo}'));
    }
    /**
    * test getTemplateFilepath
    */
    public function testGetTemplateFilepath() {
        $tpl = $this->smarty->createTemplate('string:hello world');
        $this->assertEquals('2aae6c35c94fcfb415dbe95f408b9ce91ee846ed', $tpl->source->filepath);
    }
    /**
    * test getTemplateTimestamp
    */
    public function testGetTemplateTimestamp() {
        $tpl = $this->smarty->createTemplate('string:hello world');
        $this->assertEquals(0,$tpl->source->timestamp);
    }
    /**
    * test getTemplateSource
    */
    public function testGetTemplateSource() {
        $tpl = $this->smarty->createTemplate('string:hello world{$foo}');
        $this->assertEquals('hello world{$foo}', $tpl->source->content);
    }
    /**
    * test usesCompiler
    */
    public function testUsesCompiler() {
        $tpl = $this->smarty->createTemplate('string:hello world');
        $this->assertFalse($tpl->source->uncompiled);
    }
    /**
    * test isEvaluated
    */
    public function testIsEvaluated() {
        $tpl = $this->smarty->createTemplate('string:hello world');
        $this->assertFalse($tpl->source->recompiled);
    }
    /**
    * test mustCompile
    */
    public function testMustCompile() {
        $tpl = $this->smarty->createTemplate('string:hello world');
        $this->assertTrue($tpl->mustCompile());
    }
    /**
    * test getCompiledFilepath
    */
    public function testGetCompiledFilepath() {
        $tpl = $this->smarty->createTemplate('string:hello world');
        $this->assertEquals('./compiled/2aae6c35c94fcfb415dbe95f408b9ce91ee846ed.string.php', $this->relative($tpl->compiled->filepath));
    }
    /**
    * test getCompiledTimestamp
    */
    public function testGetCompiledTimestamp() {
        $tpl = $this->smarty->createTemplate('string:hello world');
        $this->assertFalse($tpl->compiled->timestamp);
    }
    /**
    * test getCachedTimestamp
    */
    public function testGetCachedTimestamp() {
        $tpl = $this->smarty->createTemplate('string:hello world');
        $this->assertFalse($tpl->cached->timestamp);
    }
    /**
    * test writeCachedContent
    */
    public function testWriteCachedContent() {
        $tpl = $this->smarty->createTemplate('string:hello world');
        $this->assertFalse($tpl->writeCachedContent('dummy'));
    }
    /**
    * test isCached
    */
    public function testIsCached() {
        $tpl = $this->smarty->createTemplate('string:hello world');
        $this->assertFalse($tpl->isCached());
    }
    /**
    * test getRenderedTemplate
    */
    public function testGetRenderedTemplate() {
        $tpl = $this->smarty->createTemplate('string:hello world');
        $this->assertEquals('hello world', $tpl->fetch());
    }

    public function testUrlencodeTemplate() {
        $tpl = $this->smarty->createTemplate('string:urlencode:%7B%22foobar%22%7Cescape%7D');
        $this->assertEquals('foobar', $tpl->fetch());
    }

    public function testBase64Template() {
        $tpl = $this->smarty->createTemplate('string:base64:eyJmb29iYXIifGVzY2FwZX0=');
        $this->assertEquals('foobar', $tpl->fetch());
    }
}
