<?php
/**
 * Damn Small Rich Text Editor v0.2.4 for jQuery
 * by Roi Avidan <roi@avidansoft.com>
 * Demo: http://www.avidansoft.com/dsrte/
 * Released under the GPL License
 *
 * Damn Small Rich Text Editor PHP implementation.
 */

// Base classes
require_once 'dsrte.command.php';

/**
 * Main class.
 * This is the only class that should be instantiated when creating a DSRTE element in the document.
 *
 * Sample Usage:
 *   $dsrte = new dsRTE( 'my_dsrte_id' );
 *
 * <html>
 * <head>
 *   ...
 *   <?=$dsrte->getScripts()?>
 *   ...
 * </head>
 * <body>
 *   ...
 *   <?=$dsrte->getHTML()?>
 *   ...
 * </body>
 * </html>
 */
class dsRTE extends dsRTECommand
{
    private static $plugins = array();
    private static $notAddedYet = true;
    private $commands = array();
    private $resizable;

    /**
     * Constructor.
     *
     * @param String $id
     *   Instance ID for this editor.
     * @param String $str
     *   (Optional) List of available editor commands (or all commands will be shown)
     * @param Boolean $resizable
     *   (Optional) If False, then the editor will not be resizable.
     */
    public function __construct( $id, $str = false, $resizable = true )
    {
        $this->id = $id;
        $this->resizable = $resizable;
        $this->attributes = array();

        // determine command panel's commands
        $cmds = $str !== false ? $str : 'bold italic underline strikeout | sub sup | left center right | ul ol | indent outdent | color bgcolor  | clean , font fontsize headings | link unlink image table emoticons html rule source';

        // Parse command string into an array of lines of command objects (built-in and plugins)
        foreach ( explode( ',', $cmds ) as $cmdline )
        {
            $line = array();
            foreach ( explode( ' ', $cmdline ) as $cmd )
            {
                $cmd = trim( $cmd );
                if ( $cmd )
                {
                    if ( $cmd == '|' )
                    {
                        // insert block separator
                        $line[] = new dsRTECommandSeparator( '', '', '', '', 0 );
                    }
                    else
                    {
                        // always check for a built-in command first. If not applicable, try
                        // to check for a plugin implementation of that command.
                        if ( ($obj = self::buttonFactory( $id, $cmd )) === false )
                        {
                            // try a plugin command
                            if ( isset( self::$plugins[$cmd] ) )
                                $obj = self::$plugins[$cmd];
                        }

                        if ( $obj )
                            $line[] = $obj;
                        else
                            trigger_error( "Unknown command or plugin $cmd", E_USER_ERROR );
                    }
                }
            }

            $this->commands[] = $line;
        }
    }

    /**
     * Construct the editor's HTML code.
     *
     * @param String $default_text
     *   (Optional) If given, this will be the HTML text that will be shown in the editor
     *   on page load.
     * @return
     *   Editor's HTML code.
     */
    public function getHTML( $default_text = '' )
    {
        $html .= '<table cellspacing="0" cellpadding="0" class="rte">';
        $html .= '<tr><td class="rte-cmd" id="'.$this->id.'-cmd">';

        // create command's HTML
        foreach ( $this->commands as $lines )
        {
            $html .= '<div style="clear:both">';
            foreach ( $lines as $cmd )
            {
                $html .= $cmd->getHTML();
                $divs .= $cmd->getPanelHTML();
                $this->attributes[] = $cmd->getAttributes();
            }
            $html .= '</div>';
        }

        $html .= '</td></tr>';

        // add any necessary hidden divs (panels)
        if ( $divs )
            $html .= '<tr><td>'.$divs.'</td></tr>';

        // add the editor IFRAME element (this is the editor itself)
        $html .= '<tr><td><iframe id="'.$this->id.'" frameborder="0" marginwidth="0" marginheight="0" style="border:0" width="100%"></iframe></td></tr>';

        // should this editor be resizable?
        if ( $this->resizable )
            $html .= '<tr><td style="border-top:1px solid #cccccc"><div class="rte-resize" id="'.$this->id.'-resize"><img alt="" src="images/resize.gif" /></div></td></tr>';

        $html .= '</table>';

        // add necessary attributes using javascript (for XHTML compatibility)
        $html .= '<script type="text/javascript"><!--'."\n".implode( "\n", array_filter( $this->attributes ) )."\n".'//-->'."\n".'</script>';

        // add hidden textarea tag with default editor text (or html)
        $html .= '<textarea rows="1" cols="1" id="'.$this->id.'-ta" name="'.$this->id.'_text" style="display:none">'.$default_text.'</textarea>';

        return  $html;
    }

    /**
     * Return any needed script tags for the HEAD tag.
     */
    public function getScripts()
    {
        $scripts = array();
        if ( self::$notAddedYet )
        {
            // the main dsRTE JavaScript code
            $scripts[] = '<script type="text/javascript" src="/include/dsrte/lib/dsrte.js"></script>';

            // check for resizable editor
            if ( $this->resizable )
                $scripts[] = '<script type="text/javascript" src="/include/dsrte/lib/dsrte.resizer.js"></script>';

            self::$notAddedYet = false;
        }

        foreach ( $this->commands as $lines )
        {
            foreach ( $lines as $cmd )
            {
                $script = trim( $cmd->getScripts() );
                if ( $script )
                    $scripts[] = $script;
            }
        }

        // allow plugins to add their script tags also
        if ( $temp = self::CallAllPlugins( 'getScripts', $this->id ) )
            $scripts[] = $temp;

        return implode( "\n", $scripts );
    }

    /**
     * Factory function for instantiating commands according to their name.
     *
     * @param String $id
     *   Editor instance's ID
     * @param String $name
     *   Built-in Command's name to instantiate
     * @return
     *   Command object or Boolean false if no such command.
     */
    private static function buttonFactory( $id, $name )
    {
        switch ( $name )
        {
            case 'bold':
                $button = new dsRTECommandButton( $id, 'bold', '', t( 'Bold' ), 0 );
                break;

            case 'italic':
                $button = new dsRTECommandButton( $id, 'italic', '', t( 'Italic' ), 1 );
                break;

            case 'underline':
                $button = new dsRTECommandButton( $id, 'underline', '', t( 'Underline' ), 2 );
                break;

            case 'strikeout':
                $button = new dsRTECommandButton( $id, 'strikethrough', '', t( 'Strikeout' ), 3 );
                break;

            case 'left':
                $button = new dsRTECommandButton( $id, 'justifyleft', '', t( 'Left Justify' ), 4 );
                break;

            case 'center':
                $button = new dsRTECommandButton( $id, 'justifycenter', '', t( 'Center' ), 5 );
                break;

            case 'right':
                $button = new dsRTECommandButton( $id, 'justifyright', '', t( 'Right Justify' ), 6 );
                break;

            case 'justify':
                $button = new dsRTECommandButton( $id, 'justify', '', t( 'Justify' ), 7 );
                break;

            case 'ul':
                $button = new dsRTECommandButton( $id, 'insertunorderedlist', '', t( 'Bullets' ), 8 );
                break;

            case 'ol':
                $button = new dsRTECommandButton( $id, 'insertorderedlist', '', t( 'Numbered List' ), 9 );
                break;

            case 'indent':
                $button = new dsRTECommandButton( $id, 'indent', '', t( 'Indent' ), 10 );
                break;

            case 'outdent':
                $button = new dsRTECommandButton( $id, 'outdent', '', t( 'Outdent' ), 11 );
                break;

            case 'color':
                $button = new dsRTEColorPlugin( $id, 'fgcolor', 'forecolor', t( 'Font Color' ), 12 );
                break;

            case 'bgcolor':
                $button = new dsRTEColorPlugin( $id, 'bgcolor', dsRTECommand::isMSIE() ? 'backcolor' : 'hilitecolor', t( 'Background Color' ), 13 );
                break;

            case 'link':
                $button = new dsRTELinkPlugin( $id, 'link', 'link', t( 'Insert Link' ), 14 );
                break;

            case 'unlink':
                $button = new dsRTECommandButton( $id, 'unlink', '', t( 'Remove Link' ), 15 );
                break;

            case 'image':
                $button = new dsRTEImagePlugin( $id, 'image', 'image', t( 'Insert Image' ), 16 );
                break;

            case 'html':
                $button = new dsRTEHTMLCommand( $id, dsRTECommand::isMSIE() ? 'pastehtml' : 'inserthtml', 'html', t( 'Insert HTML' ), 17 );
                break;

            case 'emoticons':
                $button = new dsRTEEmoticonsPlugin( $id, 'emoticons', 'emoticons', t( 'Insert Emoticon' ), 18 );
                break;

            case 'rule':
                $button = new dsRTECommandButton( $id, 'inserthorizontalrule', '', t( 'Horizontal Rule' ), 19 );
                break;

            case 'clean':
                $button = new dsRTECleanPlugin( $id, 'clean', 'clean', t( 'Cleanup' ), 20 );
                break;

            case 'font':
                $button = new dsRTECommandSelect( $id, 'fontname', '', t( 'Font' ), 'Arial Times Verdana Helvetica Sans' );
                break;

            case 'fontsize':
                $button = new dsRTECommandSelect( $id, 'fontsize', '', t( 'Size' ), '1 2 3 4 5 6 7 8 9 10' );
                break;

            case 'headings':
                $button = new dsRTECommandSelect( $id, 'formatBlock', '', t( 'Headings' ), 'h1 h2 h3 h4 h5 h6 p' );
                break;

            case 'sub':
                $button = new dsRTECommandButton( $id, 'subscript', '', t( 'Subscript' ), 21 );
                break;

            case 'sup':
                $button = new dsRTECommandButton( $id, 'superscript', '', t( 'Superscript' ), 22 );
                break;

            case 'source':
                $button = new dsRTECommandButton( $id, 'source', 'text', t( 'Switch Source / WYSIWYG' ), 23 );
                break;

            case 'table':
                $button = new dsRTETablePlugin( $id, 'table', 'table', t( 'Insert Table' ), 24 );
                break;

            default:
                $button = false;
        }

        return $button;
    }

    /**
     * Plugin registration function.
     * All plugins should call this function before instantiating a dsRTE object, so that their
     * command behaviour can be incorporated into the editor.
     *
     * @param String $name
     *   Plugin command's name.
     * @param String $className
     *   Plugin's PHP class name.
     * @return
     *   Nothing.
     */
    public static function RegisterPlugin( $name, $classInstance )
    {
        if ( !is_object( $classInstance ) )
            trigger_error( "$classInstance is not a plugin class instance!", E_USER_ERROR );

        dsRTE::$plugins[$name] = $classInstance;
    }

    public static function CallPlugin( $name, $method, $id )
    {
        if ( isset( dsRTE::$plugins[$name] ) && method_exists( dsRTE::$plugins[$name], $method ) )
            return dsRTE::$plugins[$name]->$method( $id );

        return false;
    }

    /**
     * Call all registered plugin's given method.
     *
     * @param String $method
     *   Method name to call.
     * @param String $id
     *   ID of given dsRTE instance.
     * @return
     *   Combined return values of all plugins.
     */
    public static function CallAllPlugins( $method, $id )
    {
        $output = '';
        foreach ( dsRTE::$plugins as $name => $obj )
        {
            if ( method_exists( dsRTE::$plugins[$name], $method ) )
                $output .= dsRTE::$plugins[$name]->$method( $id );
        }

        return $output;
    }
}

/**
 * This function is used to translate strings, so your Editor may appear multi-lingual.
 * Currently, that logic is not implemented - I'm leaving it to you.
 * And yes... the name's inspirted by Drupal ;)
 *
 * @param $str
 *   String to translate.
 * @return
 *   Translated string.
 */
function t( $str )
{
    return $str;
}

//mpre(glob( 'include/dsrte/lib/plugins/*.php' ));
foreach ( glob( 'include/dsrte/lib/plugins/*.php' ) as $plugin ){
	require_once($plugin);
}

?>
