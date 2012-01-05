/**
 * Damn Small Rich Text Editor v0.2.4 for jQuery
 * by Roi Avidan <roi@avidansoft.com>
 * Demo: http://www.avidansoft.com/dsrte/
 * Released under the GPL License
 *
 * Example BBCode plugin.
 *
 * This plugin is only supplied as AN EXAMPLE (!)
 * Please do not expect it to treat all tags and be fully funcional.
 * Feel free to update it to your needs (and contribute it back so others can
 * enjoy it too!)
 */

var dsRTE_bbCode = function() {

    this.frombbcode = new Array(
          /\[img\](.*?)=\1\[\/img\]/,
          /\[email\](([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+))\[\/email\]/,
          /\[url\](http:\/\/[^ \\"\n\r\t<]*?)\[\/url\]/,
          /\[b\](.*?)\[\/b\]/,
          /\[u\](.*?)\[\/u\]/,
          /\[i\](.*?)\[\/i\]/
    );

    this.tohtml = new Array(
          "<img src=\"$1\" alt=\"\" />",
          "<a href=\"mailto:$1\">$1</a>",
          "<a href=\"$1\">$1</a>",
          "<span style=\"font-weight: bold;\">$1</span>",
          "<span style=\"text-decoration: underline;\">$1</span>",
          "<span style=\"font-style: italic;\">$1</span>"
    );

    this.fromhtml = new Array(
        /<img.*?src=\"(.*?)\".*?>/,
        /<a.*?href=\"mailto:(.*?)\".*?>(.*?)<\/a>/,
        /<a.*?href=\"(.*?)\".*?>(.*?)<\/a>/,
        /<span style=\"font-weight:\s*bold;?\">(.*?)<\/span>/,
        /<b>(.*?)<\/b>/,
        /<span style=\"text-decoration:\s*underline;?\">(.*?)<\/span>/,
        /<u>(.*?)<\/u>/,
        /<span style=\"font-style:\s*italic;?\">(.*?)<\/span>/,
        /<i>(.*?)<\/i>/
    );

    this.tobbcode = new Array(
        "[img]$1[/img]",
        "[email]$1[/email]",
        "[url=$1]$2[/url]",
        "[b]$1[/b]",
        "[b]$1[/b]",
        "[u]$1[/u]",
        "[u]$1[/u]",
        "[i]$1[/i]",
        "[i]$1[/i]"
    );

    // Convert BBCode to HTML
    this.OnLoad = function( dsrte ) {
    
        return this.helper( dsrte, this.frombbcode, this.tohtml );
    };

    // Convert HTML to BBCode
    this.OnSubmit = function( dsrte ) {
    
        return this.helper( dsrte, this.fromhtml, this.tobbcode );
    };

    this.helper = function( dsrte, from, to ) {
    
        var text = dsrte.getDoc();
        for( i = 0; i < from.length; i++ )
            text = text.replace( from[i], to[i] );
        dsrte.setDoc( text );

        return true;
    };
};

// Register new plugin with dsRTE
dsRTE.RegisterPlugin( new dsRTE_bbCode(), 'bbcode' );

