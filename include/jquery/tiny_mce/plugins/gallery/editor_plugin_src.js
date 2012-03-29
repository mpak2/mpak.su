/**
 * editor_plugin_src.js
 *
 * Copyright 2009, Moxiecode Systems AB
 * Released under LGPL License.
 *
 * License: http://tinymce.moxiecode.com/license
 * Contributing: http://tinymce.moxiecode.com/contributing
 */

(function() {
	tinymce.create('tinymce.plugins.Gallery', {
		init : function(ed, url) {
			var t = this;

			t.editor = ed;

			// Register commands
			ed.addCommand('mceGallery', t._gallery, t);
			ed.addCommand('mceCancel', t._cancel, t);

			// Register buttons
			ed.addButton('gallery', {title : 'gallery.gallery_desc', cmd : 'mceGallery'});

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
			var ed = this.editor, formObj, os, i, elementId;

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
				ed.windowManager.alert("Error: No form element found.");
		},

		_cancel : function() {
			var ed = this.editor, os, h = tinymce.trim(ed.startContent);

			// Use callback instead
			if (os = ed.getParam("gallery_oncancelcallback")) {
				ed.execCallback('gallery_oncancelcallback', ed);
				return;
			}

			ed.setContent(h);
			ed.undoManager.clear();
			ed.nodeChanged();
		}
	});

	// Register plugin
	tinymce.PluginManager.add('gallery', tinymce.plugins.Gallery);
})();