/**
 * Damn Small Rich Text Editor v0.2.4 for jQuery
 * by Roi Avidan <roi@avidansoft.com>
 * Demo: http://www.avidansoft.com/dsrte/
 * Released under the GPL License
 *
 * Resizer helper class for dsRTE
 */

var dsrteResizer = function( dsrte ) {

    var self = this;
    this.dsrte = dsrte;

    $( '#'+dsrte.iframe.id+'-resize').mousedown( function( e ) {
        dsrteResizer.OnMouseDown( self, e );
        return false;
    });

    $($.browser.msie ? document : window).bind( "mousemove", function( e ) {
        dsrteResizer.OnMouseMove( self, e );
    } );

    $(document).bind( "mouseup", function( e ) {
        dsrteResizer.OnMouseUp( self, e )
    } );
};

/**
 * Mouse Down handler.
 * Save current mouse position and signal starting of drag state.
 *
 * @param resizer
 *   dsRTE Resizer object
 * @param Event e
 *   Window Event object
 */
dsrteResizer.OnMouseDown = function( resizer, e ) {

    e = typeof( e ) == "undefined" ? window.event : e;
    resizer.x = e.screenX;
    resizer.y = e.screenY;
    resizer.width = parseInt( resizer.dsrte.iframe.clientWidth );
    resizer.height = parseInt( resizer.dsrte.iframe.clientHeight );
    resizer.moving = true;
};

/**
 * Mouse Move handler.
 * Change size according to mouse's location.
 *
 * @param resizer
 *   dsRTE Resizer object
 * @param Event e
 *   Window Event object
 */
dsrteResizer.OnMouseMove = function( resizer, e ) {

    if ( resizer.moving ) {
        e = typeof( e ) == "undefined" ? window.event : e;
        var w = Math.max( 1, resizer.width + e.screenX - resizer.x );
        var h = Math.max( 1, resizer.height + e.screenY - resizer.y );
        resizer.dsrte.iframe.style.width = w + "px";
        resizer.dsrte.iframe.style.height = h + "px";
    }
};

/**
 * Mouse Up handler.
 * Signal drag (resize) stop.
 *
 * @param resizer
 *   dsRTE Resizer object
 * @param Event e
 *   Window Event object
 */
dsrteResizer.OnMouseUp = function( resizer, e ) {

    resizer.moving = false;
};

