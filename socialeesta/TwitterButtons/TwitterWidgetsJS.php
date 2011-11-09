<?php 

class TwitterWidgetsJS {
    const URL = '//platform.twitter.com/widgets.js';

    public function getHtml() {
        return "<script>\n"
            . "(function(){\n"
            . "if ( !document.getElementById('socialeesta-tw') ){\n"
            . "var twsc = document.createElement('script');\n"
            . "twsc.type = 'text/javascript';\n"
            . "twsc.id = 'socialeesta-tw';\n" 
            . "twsc.src = '"  . self::URL . "';\n"
            . "document.body.appendChild(twsc);\n"
            . "}})();\n"
            . "</script>\n";
    }
}