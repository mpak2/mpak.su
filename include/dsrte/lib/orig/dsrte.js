/**
 * Damn Small Rich Text Editor v0.2.4 for jQuery
 * by Roi Avidan <roi@avidansoft.com>
 * Demo: http://www.avidansoft.com/dsrte/
 * Released under the GPL License
 *
 * Damn Small Rich Text Editor Javascript implementation.
 */

var dsRTE = function( dsrteIframe ) {

    var self = this;

    this.modified = false;
    this.iframe = dsrteIframe;
    this.frame = $.browser.msie ? frames[dsrteIframe.id] : this.iframe.contentWindow;
    this.doc = $.browser.msie ? this.frame.document: this.iframe.contentDocument;
    if ($.browser.msie) this.window = this.frame.window;
    this.preloadedHtml = $( '#'+this.iframe.id+'-ta' ).text();

    // Activate Design Mode
    this.doc.designMode = 'on';
    try {
    
        this.doc.execCommand( 'useCSS', false, true );
    } catch ( e ) {};

    // Allow plugins to modify content before we display it
    dsRTE.CallAllPlugins( this, 'OnLoad' );

    // Create document
    this.doc.open();
    this.doc.write( this.preloadedHtml );
    this.doc.close();
    $( 'head', this.doc ).append( '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><link rel="stylesheet" href="styles.css" style="text/css" />' );
    $( 'body', this.doc ).css( 'backgroundColor', '#ffffff' );
    this.preloadedHtml = false;

    // Add keypress event handler for shortcut keys and modified flag updates
    if ( $.browser.msie ) {
    
        this.doc.onmouseup = function( e ) {
        
            self.updateIERange();
        };
        
        this.doc.onkeyup = function( e ) {
        
          self.updateIERange();
        };
    } else {
    
        this.doc.addEventListener( 'keypress', function( e ) {
      
            self.OnKeyPress( e );
        }, true );
    }

    // Add Resize handler if needed
    if ( $('#'+this.iframe.id+'-resize').size() > 0 ) {
    
        new dsrteResizer( this );
    }

    // Activate commands on panel
    this.PreparePanel();

    // Bind to form's submit handler
    $(this.iframe).parents( 'form:first' ).submit( function() {
    
        dsRTE.CallAllPlugins( self, 'OnSubmit' );
        $('#'+self.iframe.id+'-ta').text( self.getDoc() );
    } );
};

/**
 * Retrieve current editing HTML.
 *
 * @return
 *   HTML string
 */
dsRTE.prototype.getDoc = function() {

    return typeof( this.preloadedHtml ) == 'string' ? this.preloadedHtml : this.doc.body.innerHTML;
};

/**
 * Set current editing HTML.
 *
 * @param String html
 *   HTML string to apply to this document
 * @return
 *   Nothing
 */
dsRTE.prototype.setDoc = function( html ) {

    if ( typeof( this.preloadedHtml ) == 'string' )
        this.preloadedHtml = html;
    else
        this.doc.body.innerHTML = html;
};

/**
 * Hide all command panels (i.e. color panel, link panel, etc.)
 *
 * @return
 *   Nothing
 */
dsRTE.prototype.hidePanels = function() {

    $( this.iframe ).parents( 'table:first' ).find( '.panel' ).hide();
};

/**
 * Needed for IE compatibility.
 * Thanks to Eugene Minaev <eugene20237@gmail.com> for this!
 *
 * @param Event e
 *   Window Event object
 */
dsRTE.prototype.updateIERange = function() {

    if ( this.doc.selection && this.doc.selection.createRange )
        this.iframe.rng = this.doc.selection.createRange().duplicate();
};

/**
 * Keypress handler for editor.
 * This helps us execute shortcut keys on Mozilla and keep track of
 * text modification.
 *
 * @param Event e
 *   Window Event object
 */
dsRTE.prototype.OnKeyPress = function( e ) {

    // Update document modification flag
    this.modified = true;

    // Handle shortcut keys, if necessary
    if ( e.ctrlKey ) {
    
        var k = String.fromCharCode( e.charCode ).toLowerCase();
        var c = '';
        if ( k == ctrlb )
            c = 'bold';
        else if ( k == ctrli )
            c = 'italic';
        else if ( k == ctrlu )
            c = 'underline';
        else
            return;

        // Apply shortcut
        this.frame.focus();
        this.doc.execCommand( c, false, null );
        this.frame.focus();

        // Stop event propagation for this shortcut
        e.preventDefault();
        e.stopPropagation();
    }
};

/**
 * Prepare the command panels - buttons, selects and special command hidden divs (i.e. for color).
 */
dsRTE.prototype.PreparePanel = function() {

    var dsrte = this;
    var id = dsrte.iframe.id;

    // Handle command buttons
    $( '#'+id+'-cmd .cmd' ).each( function() {
    
        var $this = $( this );

        // css hover fix for IE
        if ( $.browser.msie )
            $( 'div', $this ).mouseover( function() { this.className = 'hvr' }).mouseout( function() { this.className = '' });

        // Call Plugins' PrepareCommand method
        var a = $this.attr( 'args' );
        var pnl = $( '#'+id+'-'+a );
        if ( dsRTE.CallPlugin( dsrte, 'PrepareCommand', $this.attr( 'cmd' ), a, pnl, $this ) == false ) {
        
            // special case - Insert HTML command is not implemented as a plugin!
            if ( a == 'html' ) {
            
                $( '#'+id+'-html-ok' ).click( function() {
                
                    dsrte.frame.focus();
                    dsrte.PasteHTML( $( '#'+id+'-html-html' ).val() );
                    dsrte.frame.focus();
                    $( '#'+id+'-html' ).val( '' );
                    pnl.slideUp();
                } );
            }
        }

        // Handle command click events
        $this.click( function() {
        
            var cmd = $this.attr( 'cmd' );
            var args = $this.attr( 'args' );
            var panel = $( '#'+dsrte.iframe.id+'-'+args );

            // Hide all open panels
            dsrte.hidePanels();

            // Execute command
            dsrte.CommandClick( $this, cmd, args, panel );

            // Update modification flag
            dsrte.modified = true;
            return false;
        } );
    });

    // Handle comboboxes
    $( '#'+dsrte.iframe.id+'-cmd select' ).each( function() {
    
        var $this = $(this);

        $this.change( function() {
        
            if ( this.selectedIndex > 0 ) {
            
                dsrte.doc.execCommand( $this.attr( 'cmd' ), false, this.value );
                dsrte.modified = true;
            }

            this.selectedIndex = 0;
            dsrte.frame.focus();
        });
    });
};

/**
 * Implement cross-browser HTML insertion.
 *
 * @param String html
 *   HTML to insert at current cursor position
 */
dsRTE.prototype.PasteHTML = function( html ) {

    if ( $.browser.msie ) {
    
      if ( this.iframe && this.iframe.rng && this.iframe.rng.pasteHTML )
        this.iframe.rng.pasteHTML( html );
    } else {
    
      if ( this.doc.selection ) {
      
          var rng = this.doc.selection.createRange();
          rng.pasteHTML( html );
      } else {
      
          var rng = this.frame.getSelection().getRangeAt(0);
          rng.deleteContents();
          rng.insertNode( $( html.indexOf( '<' ) == -1 ? '<span>'+html+'</span>' : html )[0] );
      }
    }
};

/**
 * Implement cross-browser user selection retrieval.
 *
 * @return
 *   User Selection.
 */
dsRTE.prototype.GetSelection = function() {

    var html = '';

    this.frame.focus();
    if ( $.browser.msie )
        this.iframe.rng.select();

    if ( this.doc.selection ) {
    
        var rng = this.doc.selection.createRange();
        html = rng.htmlText;
    } else {
    
        var rng = this.frame.getSelection().getRangeAt(0);
        var e = this.doc.createElement( 'div' );
        e.appendChild( rng.extractContents() );
        html = e.innerHTML;
    }

    return html;
};

/**
 * Handle command button click for built-in and plugins' commands.
 *
 * @param jQuery $this
 *   jQuery object of command div clicked
 * @param String cmd
 *   Command identifier
 * @param String args
 *   Additional command arguments
 * @param jQuery panel
 *   jQuery object of associated panel
 */
dsRTE.prototype.CommandClick = function( $this, cmd, args, panel ) {

    if ( dsRTE.CallPlugin( this, 'ExecuteCommand', cmd, args, panel ) == false ) {
    
        switch ( args ) {
        
            // Switch editor's text to normal text (show HTML tags)
            case 'text':
                $this.attr( 'args', 'wysiwyg' );
                if ( $.browser.msie ) {
                
                    this.doc.body.innerText = this.doc.body.innerHTML;
                } else {
                
                    var src = this.doc.createTextNode( this.doc.body.innerHTML );
                    this.doc.body.innerHTML = "";
                    this.doc.body.appendChild( src );
                }
                break;

            // Switch editor's text to WYSIWYG view (HTML view)
            case 'wysiwyg':
                $this.attr( 'args', 'text' );
                if ( $.browser.msie ) {
                
                    var o = escape( this.doc.body.innerText );
                    o = o.replace( "%3CP%3E%0D%0A%3CHR%3E", "%3CHR%3E" );
                    o = o.replace( "%3CHR%3E%0D%0A%3C/P%3E", "%3CHR%3E" );
                    this.doc.body.innerHTML = unescape( o );
                } else {
                
                    var src = this.doc.body.ownerDocument.createRange();
                    src.selectNodeContents( this.doc.body );
                    this.doc.body.innerHTML = src.toString();
                }
                break;

            // Show Insert HTML panel
            case 'html':
                panel.slideToggle();
                break;

            // Execute internal browser command
            default:
                if ( $.browser.msie )
                    this.iframe.rng.select();
                this.doc.execCommand( cmd, false, '' );
                this.frame.focus();
                break;
        }
    }
};

/**
 * Register a new Plugin class with dsRTE.
 * It is recommended to call this function before creating a dsRTE instance so the OnLoad event
 * may also be called.
 *
 * @param Object pluginObj
 *   Plugin instance.
 * @param String command
 *   command related to this plugin
 */
dsRTE.RegisterPlugin = function( pluginObj, command ) {

    if ( dsRTE.plugins == null )
        dsRTE.plugins = {};

    dsRTE.plugins[command] = pluginObj;
};

/**
 * Internal use.
 * Call a plugin's method to perform a specific action.
 * If the Plugin implements the called function, it MUST RETURN TRUE!
 * Otherwise, processing will continue and the results may be undefined.
 *
 * @param Object dsrte
 *   dsRTE object
 * @param String func
 *   Plugin function to execute
 * @param String command
 *   Command to execute (plugin-registered)
 * @param String arguments
 *   Command argumetns to pass to plugin
 * @param jQuery panel
 *   jQuery object of associated command panel (only for PrepareCommand call)
 * @param jQuery $this
 *   jQuery object of command div
 * @return
 *   True if command was handled, False otherwise
 */
dsRTE.CallPlugin = function( dsrte, func, command, arguments, panel, $this ) {

    return (dsRTE.plugins != null) && (dsRTE.plugins[command] != null) && (dsRTE.plugins[command][func] != null) && (dsRTE.plugins[command][func]( dsrte, arguments, panel, $this ) == true);
};

/**
 * Call all registered plugins to perform some action.
 *
 * @param Object dsrte
 *   dsRTE object
 * @param String func
 *   Plugin function to execute
 * @param String arguments
 *   Command arguments to pass to plugin
 * @return
 *   Nothing
 */
dsRTE.CallAllPlugins = function( dsrte, func, arguments ) {

    if ( dsRTE.plugins != null ) {
    
        $.each( dsRTE.plugins, function( cmd, obj ) {
        
            if ( obj[func] != null )
                obj[func]( dsrte, arguments );
        } );
    }
};

/**
 * Bind all the document's RTEs.
 */
$(function() {

    $('table.rte iframe').each( function() {
    
        new dsRTE( this );
    });
});

