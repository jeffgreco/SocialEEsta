<?php
require_once PATH_THIRD .'socialeesta/TemplateParams/Scripts.php';
class ScriptsTemplateParams extends Testee_unit_test_case {
    private $_params;

    
    public function __construct(){
        parent::__construct('Javascripts Template Params class test');
    }
    public function setUp(){
        parent::setUp();
    }
    public function tearDown(){
        unset($this->_params);
    }
    public function testGetScriptsReturnsArray(){
        $expected = '';
        $this->EE->TMPL->returns('fetch_param', 'facebook|twitter', array('scripts'));
        $this->_params = new TemplateParams_Scripts($this->EE->TMPL);
        $this->EE->TMPL->expectOnce('fetch_param', array('scripts'));
        $this->assertIsA($this->_params->getScripts(), 'array');
    }
    public function testGetScriptsReturnsNoScriptsByDefault(){
        $expected = array(
            "facebook" => FALSE,
            "twitter" => FALSE,
            "google" => FALSE
        );
        $this->EE->TMPL->returns('fetch_param', '', array('scripts'));
        $this->_params = new TemplateParams_Scripts($this->EE->TMPL);
        $this->EE->TMPL->expectOnce('fetch_param', array('scripts'));
        $this->assertIdentical($expected, $this->_params->getScripts());
    }
    public function testGetScriptsReturnsCorrectTemplateParams(){
        $expected = array(
            "facebook" => TRUE,
            "twitter" => TRUE,
            "google" => FALSE
        );
        $this->EE->TMPL->returns('fetch_param', 'facebook|twitter', array('scripts'));
        $this->_params = new TemplateParams_Scripts($this->EE->TMPL);
        $this->EE->TMPL->expectOnce('fetch_param', array('scripts'));
        $this->assertIdentical($expected, $this->_params->getScripts());
    }
    public function testIncludeFacebookReturnsFalseByDefault(){
        $this->EE->TMPL->returns('fetch_param', '', array('scripts'));
        $this->_params = new TemplateParams_Scripts($this->EE->TMPL);
        $this->EE->TMPL->expectOnce('fetch_param', array('scripts'));
        $this->assertFalse($this->_params->includeFacebook());
    }
    public function testIncludeTwitterReturnsFalseByDefault(){
        $this->EE->TMPL->returns('fetch_param', '', array('scripts'));
        $this->_params = new TemplateParams_Scripts($this->EE->TMPL);
        $this->EE->TMPL->expectOnce('fetch_param', array('scripts'));
        $this->assertFalse($this->_params->includeTwitter());
    }
    public function testIncludeGoogleReturnsFalseByDefault(){
        $this->EE->TMPL->returns('fetch_param', '', array('scripts'));
        $this->_params = new TemplateParams_Scripts($this->EE->TMPL);
        $this->EE->TMPL->expectOnce('fetch_param', array('scripts'));
        $this->assertFalse($this->_params->includeGoogle());
    }
    public function testIncludeFacebookRetunsTrueWhenIncludedInParams(){
        $this->EE->TMPL->returns('fetch_param', 'facebook|twitter|google', array('scripts'));
        $this->_params = new TemplateParams_Scripts($this->EE->TMPL);
        $this->EE->TMPL->expectOnce('fetch_param', array('scripts'));
        $this->assertTrue($this->_params->includeFacebook());
    }
    public function testIncludeTwitterRetunsTrueWhenIncludedInParams(){
        $this->EE->TMPL->returns('fetch_param', 'facebook|twitter|google', array('scripts'));
        $this->_params = new TemplateParams_Scripts($this->EE->TMPL);
        $this->EE->TMPL->expectOnce('fetch_param', array('scripts'));
        $this->assertTrue($this->_params->includeTwitter());
    }
    public function testIncludeGoogleRetunsTrueWhenIncludedInParams(){
        $this->EE->TMPL->returns('fetch_param', 'facebook|twitter|google', array('scripts'));
        $this->_params = new TemplateParams_Scripts($this->EE->TMPL);
        $this->EE->TMPL->expectOnce('fetch_param', array('scripts'));
        $this->assertTrue($this->_params->includeGoogle());
    }
    public function testGetFbChannelUrlReturnsEmptyStringByDefault(){
        $this->EE->TMPL->returns('fetch_param', '', array('fb_channel_url'));
        $this->_params = new TemplateParams_Scripts($this->EE->TMPL);
        $this->EE->TMPL->expectAtLeastOnce('fetch_param', array('fb_channel_url'));
        $this->assertIdentical('', $this->_params->getFbChannelUrl());
    }
    public function testGetFbChannelUrlReturnsTemplateParam(){
        $expected = "http://www.bluestatedigital.com/path/to/channel.html";
        $this->EE->TMPL->returns('fetch_param', $expected, array('fb_channel_url'));
        $this->_params = new TemplateParams_Scripts($this->EE->TMPL);
        $this->EE->TMPL->expectAtLeastOnce('fetch_param', array('fb_channel_url'));
        $this->assertIdentical($expected, $this->_params->getFbChannelUrl());
    }
    public function testGetFbAppIdReturnsEmptyStringByDefault(){
        $this->EE->TMPL->returns('fetch_param', '', array('fb_app_id'));
        $this->_params = new TemplateParams_Scripts($this->EE->TMPL);
        $this->EE->TMPL->expectAtLeastOnce('fetch_param', array('fb_app_id'));
        $this->assertIdentical('', $this->_params->getFbAppId());
    }
    public function testGetFbAppIdReturnsTemplateParam(){
        $expected = "1234567890123456";
        $this->EE->TMPL->returns('fetch_param', $expected, array('fb_app_id'));
        $this->_params = new TemplateParams_Scripts($this->EE->TMPL);
        $this->EE->TMPL->expectAtLeastOnce('fetch_param', array('fb_app_id'));
        $this->assertIdentical($expected, $this->_params->getFbAppId());
    }
}