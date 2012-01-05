<?php
/**
 * Damn Small Rich Text Editor v0.2.4 for jQuery
 * by Roi Avidan <roi@avidansoft.com>
 * Demo: http://www.avidansoft.com/dsrte/
 * Released under the GPL License
 *
 * Basic Command classes.
 */

/**
 * Base Command Class.
 *
 * All command buttons, selects and plugins should inherit and implement at least one
 * method (getHTML) of this base class.
 */
abstract class dsRTECommand
{
    protected $id;
    protected $command;
    protected $arguments;
    protected $title;
    protected $icon_offset;
    protected $attributes = array();
    private $scriptsOnce = array();

    /**
     * Constructor.
     *
     * @param String $id
     *   dsRTE instance ID.
     * @param String $command
     *   Implemented command's name (usually the command value for JS's ExecCommand() function).
     * @param String $arguments
     *   Command arguements (used to identify special commands in the JS part).
     * @param String $title
     *   Tooltip hint string for command buttons or title (first select element) for comboboxes.
     * @param Mixed $icon_offset
     *   If String - then it represents a path to a button icon to be displayed
     *   If Number - then it's a zero-based offset into the icons.gif image for this command's icon.
     */
    protected function __construct( $id, $command, $arguments, $title, $icon_offset )
    {
        $this->id = $id;
        $this->command = $command;
        $this->arguments = $arguments;
        $this->title = $title;
        $this->icon_offset = is_numeric( $icon_offset ) ? 18*$icon_offset : $icon_offset;

        if ( $command )
            $this->attributes[] = '"cmd":"'.$command.'"';

        if ( $arguments )
            $this->attributes[] = '"args":"'.$arguments.'"';
    }

    /**
     * This method is called for each command to generate it's command button or selectbox
     * for that specific command.
     * It is generally not necessary to override it in derived classes if you're creating
     * a new class and deriving it from either dsRTECommandSelect or dsRTECommandButton which
     * both provide the necessary implementation select boxes and icons respectively.
     */
    abstract public function getHTML();

    /**
     * Return HTML for a command's special hidden panel.
     * See example in the dsRTELinkCommand class.
     */
    public function getPanelHTML()
    {
        return '';
    }

    /**
     * Return SCRIPT (and/or LINK) tags that should be placed into the document's HEAD
     * tag for this command to work.
     * This method is only used by plugins which need to add extra JavaScript or CSS files
     * to the current document.
     * See example in the Image plugin.
     */
    public function getScripts()
    {
        return '';
    }

    /**
     * Return the command's Attributes JavaScript code (in jQuery) that should be executed to
     * complete the command's initialization.
     */
    final public function getAttributes()
    {
        return empty( $this->attributes ) ? '' : '$("#cmd-'.$this->id.'-'.$this->command.'").attr({'.implode( ',', $this->attributes ).'});';
    }

    /**
     * Checks if the current browser is Internet Explorer.
     * I know there are better ways, but this one does the trick in most cases.
     */
    public static function isMSIE()
    {
        return strpos( strtolower( $_SERVER['HTTP_USER_AGENT'] ), 'msie' ) !== false;
    }
}

/**
 * Special case class for defining command separation.
 * I cannot imagine any need to override this class, thus it's marked as 'final'.
 */
final class dsRTECommandSeparator extends dsRTECommand
{
    /**
     * Return HTML for a block separator.
     */
    public function getHTML()
    {
        return '<div class="sep"></div>';
    }
}

/**
 * Implement a base class for handling simple select options (comboboxes).
 * Used by the Font and Font Size commands.
 */
class dsRTECommandSelect extends dsRTECommand
{
    /**
     * Get HTML code for a combobox.
     */
    public function getHTML()
    {
        $html .= '<select id="cmd-'.$this->id.'-'.$this->command.'" size="1"><option value="">'.$this->title.'</option>';
        foreach ( explode( ' ', $this->icon_offset ) as $opt )
            $html .= '<option value="'.$opt.'">'.$opt.'</option>';
        $html .= '</select>';

        return $html;
    }
}

/**
 * Implement a base class for button commands.
 * Used (and subclassed) by almost all the commands in the system.
 */
class dsRTECommandButton extends dsRTECommand
{
    /**
     * Return a command icon either by offset into the icons.gif image or as an external icon image.
     */
    public function getHTML()
    {
        $html = '<a class="cmd" id="cmd-'.$this->id.'-'.$this->command.'" title="'.$this->title.'">';
        if ( is_numeric( $this->icon_offset ) )
            $html .= '<span style="background-position:-'.$this->icon_offset.'px 0px"></span>';
        else
            $html .= '<span style="background:url('.$this->icon_offset.') no-repeat 0 0"></span>';
        $html .= '</a>';

        return $html;
    }
}

/**
 * Implementation of the HTML Insert command.
 */
class dsRTEHTMLCommand extends dsRTECommandButton
{
    /**
     * Prepare a row for inserting HTML code.
     */
    public function getPanelHTML()
    {
        $html = '<div class="rte panel" id="'.$this->id.'-'.$this->arguments.'">';
        $html .= t( 'HTML' ).': ';
        $html .= '<input size="40" id="'.$this->id.'-'.$this->arguments.'-html" />';
        $html .= '<input type="button" id="'.$this->id.'-'.$this->arguments.'-ok" value="'.t( 'OK' ).'" />';
        $html .= '<input type="button" value="'.t( 'Cancel' ).'" onclick="$(\'#'.$this->id.'-'.$this->arguments.'\').slideUp()" />';
        $html .= '</div>';

        return $html;
    }
}

?>
