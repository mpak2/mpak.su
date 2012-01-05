<?php
/**
 * Damn Small Rich Text Editor v0.2.3 for jQuery
 * by Roi Avidan <roi@avidansoft.com>
 * Demo: http://www.avidansoft.com/dsrte/
 * Released under the GPL License
 *
 * Image Upload Command class.
 */

class dsRTEImagePlugin extends dsRTECommandButton
{
    private static $scripted = false;
    
    /**
     * Prepare the special hidden div for this command with a file browse and upload buttons.
     */
    public function getPanelHTML()
    {
        $this->attributes[] = '"path":"/include/dsrte/uploadhandler.php"';

        $html = '<div class="rte panel" id="'.$this->id.'-'.$this->arguments.'">';
        $html .= t( 'Image' ).': ';
        $html .= '<input type="file" size="25" id="'.$this->id.'-'.$this->arguments.'-file" name="'.$this->arguments.'-file" />';
        $html .= '<input type="button" id="'.$this->id.'-'.$this->arguments.'-ok" value="'.t( 'Upload' ).'" />';
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
            '<script type="text/javascript" src="/include/dsrte/lib/plugins/image.js"></script>',
            '<script type="text/javascript" src="/include/dsrte/lib/plugins/ajaxfileupload.min.js"></script>',
        ) );
    }
}

?>