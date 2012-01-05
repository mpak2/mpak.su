/**
 * Damn Small Rich Text Editor v0.2.4 for jQuery
 * by Roi Avidan <roi@avidansoft.com>
 * Demo: http://www.avidansoft.com/dsrte/
 * Released under the GPL License
 *
 * Sample SPAN injection Plugin
 */

var dsRTE_mySpan = function() {

    /**
     * Execute Plugin.
     */
    this.ExecuteCommand = function( dsrte ) {
    
        // Get selection contents
        var html = dsrte.GetSelection();
        if ( html != '' )
            dsrte.PasteHTML( '<span style="color:green" class="myspanclass">' + html + '</span>' );

        // signal callback was successfully processed.
        return true;
    };
};

// Register new plugin with dsRTE
dsRTE.RegisterPlugin( new dsRTE_mySpan(), 'myspan' );

