<?php
/**
 * Damn Small Rich Text Editor v0.2.3 for jQuery
 * by Roi Avidan <roi@avidansoft.com>
 * Demo: http://www.avidansoft.com/dsrte/
 * Released under the GPL License
 *
 * Insert Link command class.
 */

class dsRTELinkPlugin extends dsRTECommandButton
{
    private static $scripted = false;
    
    /**
     * Prepare the Link command's special hidden div with a Target and URL fields.
     */
    public function getPanelHTML()
    {
        $html = '<div class="rte panel" id="'.$this->id.'-'.$this->arguments.'">';
        $html .= t( 'URL' ).': ';
        $html .= '<select size="1" id="'.$this->id.'-'.$this->arguments.'-target"><option value=""></option><option value="_blank">'.t( 'New' ).'</option></select>';
        $html .= '<input size="35" id="'.$this->id.'-'.$this->arguments.'-url" />';
        $html .= '<input type="button" id="'.$this->id.'-'.$this->arguments.'-ok" value="'.t( 'OK' ).'" />';
        $html .= '<input type="button" value="'.t( 'Cancel' ).'" onclick="$(\'#'.$this->id.'-'.$this->arguments.'\').slideUp()" />';
        $html .= '</div>';

        return $html;
    }

    /**
     * This plugin requires additional JavaScript files to operate.
     * Return them for inclusion.
     */
    public function getScripts()
    {
        if ( self::$scripted )
            return '';
            
        self::$scripted = true;
        return implode( "\n", array(
            '<script type="text/javascript" src="/include/dsrte/lib/plugins/link.js"></script>',
        ) );
    }
}

?>