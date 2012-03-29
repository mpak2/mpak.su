(function() {
	tinymce.create('tinymce.plugins.Gallery', {
		init : function(ed, url) {
			var t = this;

			t.editor = ed;

			// Register commands
			ed.addCommand('mceGallery', t._gallery, t);

			// Register buttons
			ed.addButton('gallery', {title : 'gallery.gallery_desc', cmd : 'mceGallery', image: url + '/img/gallery.png'});

			ed.onNodeChange.add(t._nodeChange, t);
			ed.addShortcut('ctrl+g', ed.getLang('gallery.gallery_desc'), 'mceGallery');
		},

		getInfo : function() {
			return {
				longname : 'Gallery',
				author : 'Moxiecode Systems AB',
				authorurl : 'http://tinymce.moxiecode.com',
				infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/gallery',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		},

		// Private methods

		_nodeChange : function(ed, cm, n) {
			var ed = this.editor;

			if (ed.getParam('gallery_enablewhendirty')) {
				cm.setDisabled('gallery', !ed.isDirty());
				cm.setDisabled('cancel', !ed.isDirty());
			}
		},

		// Private methods

		_gallery : function() {
			alert(123);
/*			var ed = this.editor, formObj, os, i, elementId;

			formObj = tinymce.DOM.get(ed.id).form || tinymce.DOM.getParent(ed.id, 'form');

			if (ed.getParam("gallery_enablewhendirty") && !ed.isDirty())
				return;

			tinyMCE.triggerGallery();

			// Use callback instead
			if (os = ed.getParam("gallery_ongallerycallback")) {
				if (ed.execCallback('gallery_ongallerycallback', ed)) {
					ed.startContent = tinymce.trim(ed.getContent({format : 'raw'}));
					ed.nodeChanged();
				}

				return;
			}

			if (formObj) {
				ed.isNotDirty = true;

				if (formObj.onsubmit == null || formObj.onsubmit() != false)
					formObj.submit();

				ed.nodeChanged();
			} else
				ed.windowManager.alert("Error: No form element found.");*/
		},
	});

	// Register plugin
	tinymce.PluginManager.add('gallery', tinymce.plugins.Gallery);
})();