<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine - by EllisLab
 *
 * @package     ExpressionEngine
 * @author      ExpressionEngine Dev Team
 * @copyright   Copyright (c) 2003 - 2011, EllisLab, Inc.
 * @license     http://expressionengine.com/user_guide/license.html
 * @link        http://expressionengine.com
 * @since       Version 2.0
 * @filesource
 */
 
// ------------------------------------------------------------------------

/**
 * BSD SocialEEsta Plugin
 *
 * @package     ExpressionEngine
 * @subpackage  Addons
 * @category    Plugin
 * @author      Douglas Back
 * @link        http://www.bluestatedigital.com
 */

$plugin_info = array(
    'pi_name'       => 'SocialEEsta',
    'pi_version'    => '1.0b',
    'pi_author'     => 'Douglas Back',
    'pi_author_url' => 'http://www.bluestatedigital.com',
    'pi_description'=> 'Generate social sharing plugins for your EE pages.',
    'pi_usage'      => Socialeesta::usage()
);

require_once 'Utils/QueryString.php';
require_once 'Utils/DataAttrs.php';

class Socialeesta {
    public $return_data;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->EE =& get_instance();
    }
    
    public function tweet()
    {
        require_once 'TemplateParams/Tweet.php';
        
        $params = new TemplateParams_Tweet($this->EE->TMPL);


        switch ($params->getType()) {
            case 'iframe':
                require_once 'TwitterButtons/Tweet_Iframe.php';
                $queryString = new QueryString();
                $queryString->addParam('url', $params->getUrl());
                $queryString->addParam('counturl', $params->getCountUrl());
                $queryString->addParam('via', $params->getVia());
                $queryString->addParam('text', $params->getText());
                $queryString->addParam('count', $params->getCountPosition());
                $queryString->addParam('related', $params->getRelatedAccts());
                $queryString->addParam('lang', $params->getLang());
                $iframe = new Tweet_Iframe($queryString);
                return $iframe->getHtml();
                
            case 'js':
            default:
                require_once 'TwitterButtons/Tweet_JS.php';
                require_once 'TwitterButtons/TwitterWidgetsJS.php';
                $dataAttrs = new DataAttrs();
                $dataAttrs->addAttr('url', $params->getUrl());
                $dataAttrs->addAttr('counturl', $params->getCountUrl());
                $dataAttrs->addAttr('via', $params->getVia());
                $dataAttrs->addAttr('text', $params->getText());
                $dataAttrs->addAttr('count', $params->getCountPosition());
                $dataAttrs->addAttr('related', $params->getRelatedAccts());
                $dataAttrs->addAttr('lang', $params->getLang());
                $button = new Tweet_JS(new TwitterWidgetsJS(), $dataAttrs, $params->getCssId(), $params->getCssClass());
                $button->setId($params->getCssId());
                $button->setClass($params->getCssClass());
                $button->setIncludeJs($params->getIncludeJS());
                return $button->getHtml($params->getLinkText());                
        }
    }
    
    function follow(){
        require_once 'TemplateParams/Follow.php';

        $params = new TemplateParams_Follow($this->EE->TMPL);
        switch ($params->getType()) {
            case 'iframe':
                require_once 'TwitterButtons/Follow_Iframe.php';
                $queryString = new QueryString();
                $queryString->addParam('screen_name', $params->getUser());
                $queryString->addParam('show_count', $params->getFollowerCount());
                $queryString->addParam('button', $params->getButtonColor());
                $queryString->addParam('text_color', $params->getTextColor());
                $queryString->addParam('link_color', $params->getLinkColor());
                $queryString->addParam('lang', $params->getLang());
                $iframe = new FollowIframe($queryString, $params->getWidth());
                return $iframe->getHtml();
            case 'js':
            default:
                require_once 'TwitterButtons/Follow_JS.php';
                require_once 'TwitterButtons/TwitterWidgetsJS.php';
                $dataAttr = new DataAttrs();
                $dataAttr->addAttr('screen-name', $params->getUser());
                $dataAttr->addAttr('show-count', $params->getFollowerCount());
                $dataAttr->addAttr('button', $params->getButtonColor());
                $dataAttr->addAttr('text-color', $params->getTextColor());
                $dataAttr->addAttr('link-color', $params->getLinkColor());
                $dataAttr->addAttr('lang', $params->getLang());
                $dataAttr->addAttr('width', $params->getWidth());
                $dataAttr->addAttr('align', $params->getAlign());
                $button = new Follow_JS(new TwitterWidgetsJS(), $dataAttr, $params->getCssId(), $params->getCssClass());
                $button->setId($params->getCssId());
                $button->setClass($params->getCssClass());
                $button->setIncludeJs($params->getIncludeJS());
                return $button->getHtml();
        }

    } // end function follow()
    
    function like(){ //Facebook Like Buttons
        global $IN;
        // Assign variables to params or defaults.
        $url = $this->EE->TMPL->fetch_param('url', $this->EE->uri->config->config["site_url"]);
        $type = $this->EE->TMPL->fetch_param('type', 'iframe');
        $layout = $this->EE->TMPL->fetch_param('layout', 'standard');
        $faces = $this->EE->TMPL->fetch_param('faces', 'false');
        $faces === "yes" ? $faces = true : $faces = false; // convert to boolean
        switch ( $layout ){ //The like button has 3 layout modes; each has their own default height/width values
            case "standard":
                $width = $this->EE->TMPL->fetch_param('width', '450');
                // Use the $faces param to figure height default for standard layout
                $faces ? $height = $this->EE->TMPL->fetch_param('height', '80') : $height = $this->EE->TMPL->fetch_param('height', '35');
                break;
            case "button_count":
                $width = $this->EE->TMPL->fetch_param('width', '90');
                $height = $this->EE->TMPL->fetch_param('height', '20');
                break;
            case "box_count":
                $width = $this->EE->TMPL->fetch_param('width', '55');
                $height = $this->EE->TMPL->fetch_param('height', '65');
                break;

        }
        $verb = $this->EE->TMPL->fetch_param('verb', 'like');
        $color = $this->EE->TMPL->fetch_param('color', 'light');
        
        // Build Like Button Code
        
        switch ( $type ){
            case "xfbml":
                $like_button = '<fb:like href="' . $url . '" send="false" width="' . $width . '" show_faces="' . $faces . '" colorscheme="' . $color .'" font=""></fb:like>';
                break;
            case "iframe";
            default:
                $like_button = '<iframe src="http://www.facebook.com/plugins/like.php?href=' . urlencode($url) . '&amp;send=false&amp;layout=' . $layout .'&amp;width=' . $width . '&amp;show_faces=' . $faces . '&amp;action=' . $verb . '&amp;colorscheme=' . $color . '&amp;font&amp;height=' . $height . '" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:' . $width . 'px; height:' . $height . 'px;" allowTransparency="true"></iframe>';
                break;
        } // end switch($type)
        
        return $like_button;
        
    }
    
    // function plusone() {
    //     
    //             
    // } // end plusone
    // ----------------------------------------------------------------
    
    /**
     * Plugin Usage
     */
    public static function usage()
    {
        ob_start();
?>
    This plugin has three uses:
    
    - Generate Twitter "Tweet" and button
    - Generate Twitter "Follow" and button 
    - Generate a Facebook "Like" button
    
    
    =============================
    = Twitter "Tweet" Button Parameters =
    =============================
    
    (based on Tweet Button specs). All Parameters are optional, but the Tweet Button won't function as expected without at least "url" or "text".
    
        - url  :  The URL to share on Twitter. The URL should be absolute.
        - type  :  "iframe", "js" :  Default value: "iframe"  :  The "js" version will also insert the Javascript. See "include_js".
        - count_url  :  The URL to which your shared URL resolves to; useful is the URL you are sharing has already been shortened. This affects the display of the Tweet count.
        - via  :  Screen name of the user to attribute the Tweet to.
        - text  :  Text of the suggested Tweet.
        - count_position  :  "none", "horizontal", or "vertical"  :  Default value: "none".
        - related  :  Related accounts.
        
        Type-specific Options:
        ************************
        Type "none" & "js":
        - class  :  Assign a class attribute to the element. 
        - id  :  Assigns an ID attribute to the  element. Only used when type="none".
        - link_text  :  If type="none", this will display as the text of the "Tweet" link. Defaults to "Tweet"
        
        Type "js":
        - include_js  :  "yes" or "no"  :  Default value: yes  :  If "yes", the Twitter widgets.js file will be loaded.


    Example tag:
    **************
    {exp:socialeesta:tweet url="{title_permalink='blog/entry'}" type="js" via="bsdwire" text="{title}" count_position="horizontal"}
    
    ==============================
    = Twitter "Follow" Button Parameters =
    ==============================
    
    Required Parameters
    **************************
    - user  :   Default value: none  :  Which user to follow. Do not include the '@'.

    Optional Parameters
    **************************
    - type  :  "js" or "iframe"  :  Default value: "iframe"  :  Defines whether to use Javascript version or IFRAME version of the Follow Button.
    - follower_count  :  "yes" or "no"  :  Default value: "no"  :  Whether to display the follower count adjacent to the follow button. 
    - button_color  :  "blue" or "grey"  :  Default value: "blue"  :  Change the color of the button itself.
    - text_color  :  Default value: none  :  Specify a hexadecimal color code for the "Followers count" and "Following state" text
    - link_color  :  Default value: none  :  Specify a hexadecimal color code for the Username text
    - lang  :  Default value: "en"  :  Specify the language for the button using ISO-639-1 Language code. Defaults to "en" (english).
    - include_js  :  "yes" or "no"  :  Default value: "yes"  :  If "yes", the Twitter widget.js file will be loaded.


    Javascript button specific parameters — not supported with IFRAME version
    **********************************************************************************
    - width  :  A pixel or percentage value to set the button element width
    - align  :  "right" or "left" - Defaults to "left".


    Example tag:
    **************
    {exp:socialeesta:follow user="bsdwire" follower_count="yes" type="js"}
    
    
    =============================
    = Facebook Like Button Parameters =
    =============================
    
    All parameters are optional, but the button won't function as expected without at least a "url".
        
        - url  :  The URL to Like on Facebook. Defaults to the Site Index (homepage) if no value is present.
        - type  :  "iframe" or "xfbml"  :  Defaults to "iframe". **If you choose "xfbml", you must include the Facebook Javascript SDK on your page.**
        - layout  :  "standard", "button_count" or "box_count"  :  Default value: "standard"  :  1) "standard" : No counter is displayed; 2) "button_count" : A counter is displayed to the right of the like button; 3) "box_count" : A counter is displayed above the like button
        - faces  :  "yes" or "no"  :  Default value: "no"  :  whether to display profile photos below the button (standard layout only)
        - verb  :  "like" or "recommend"  :  Default value: "like".
        - color  :  "light" or "dark"  :  Default value: "light".


        IFRAME specific parameters, not supported in the XFBML version
        ************************************************************************
        The height and width parameters have default values that depend upon the button layout chosen. Refer to Facebook's documentation for more info: https://developers.facebook.com/docs/reference/plugins/like/
        
        - width  :  a value in pixels
        - height  :  a value in pixels
        

    Example tag: 
    **************
    {exp:socialeesta:like url="{pages_url}" type="iframe" verb="recommend" color="light" layout="button_count"}
    
<?php
        $buffer = ob_get_contents();
        ob_end_clean();
        return $buffer;
    }
}


/* End of file pi.socialeesta.php */
/* Location: /system/expressionengine/third_party/socialeesta/pi.socialeesta.php */