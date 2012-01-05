/**
 * Damn Small Rich Text Editor v0.2.4 for jQuery
 * by Roi Avidan <roi@avidansoft.com>
 * Demo: http://www.avidansoft.com/dsrte/
 * Released under the GPL License
 *
 * Clean HTML code Plugin.
 *
 * This plugin is intended to be used after pasting a piece of HTML code from
 * Microsoft Word, which in general, arrives with alot of unnecessary tags.
 * It is NOT intended as a Style remove plugin!
 */

var dsRTE_cleanHtmlCode = function() {

    /**
     * Execute Plugin.
     */
    this.ExecuteCommand = function( dsrte ) {
    
        var s = dsrte.getDoc().replace( new RegExp( '<p \\/>', 'gi' ), '<p>&nbsp;</p>' );
        s = s.replace( new RegExp( '<p>\\s*<\\/p>', 'gi' ), '<p>&nbsp;</p>' );
        s = s.replace( new RegExp( '<br>\\s*<\\/br>', 'gi' ), '<br />' );
        s = s.replace( new RegExp( '<(h[1-6]|p|div|address|pre|form|table|li|ol|ul|td|b|font|em|strong|i|strike|u|span|a|ul|ol|li|blockquote)([a-z]*)([^\\\\|>]*)\\/>', 'gi' ), '<$1$2$3></$1$2>' );
        s = s.replace( new RegExp( '\\s+></','gi' ), '></' );
        s = s.replace( new RegExp( '<(img|br|hr)([^>]*)><\\/(img|br|hr)>', 'gi' ), '<$1$2 />' );
        if ( $.browser.msie ) {
            s = s.replace( new RegExp( '<p><hr \\/><\\/p>', 'gi' ), "<hr>" );
            s = s.replace( /<!(\s*)\/>/g, '' );
        };
        dsrte.setDoc( s );

        // signal callback was successfully processed.
        return true;
    };
};

// Register new plugin with dsRTE
dsRTE.RegisterPlugin( new dsRTE_cleanHtmlCode(), 'clean' );

