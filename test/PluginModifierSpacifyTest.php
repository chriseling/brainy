<?php
/**
* Smarty PHPunit tests of modifier
*
* @package PHPunit
* @author Rodney Rehm
*/

namespace Box\Brainy\Tests;


class PluginModifierSpacifyTest extends Smarty_TestCase
{
    public function setUp() {
        $this->smarty = SmartyTests::$smarty;
        SmartyTests::init();
    }

    public function testDefault() {
        $result = 'h e l l o   w ö r l d';
        $tpl = $this->smarty->createTemplate('eval:{"hello wörld"|spacify}');
        $this->assertEquals($result, $this->smarty->fetch($tpl));
    }

    public function testCharacter() {
        $result = 'h##e##l##l##o## ##w##ö##r##l##d';
        $tpl = $this->smarty->createTemplate('eval:{"hello wörld"|spacify:"##"}');
        $this->assertEquals($result, $this->smarty->fetch($tpl));
    }

}
