tinyMCEPopup.requireLangPack();
 
var MicsDialog = {
init : function() {
 var f = document.forms[0];
 f.mtext.value = tinyMCEPopup.editor.selection.getContent({
 format : 'text'
 });
 f.mhref.value = '';
 },
 
 insert : function() {
  if(document.forms[0].targ.value==1){
  tr='target=_self';  }
  else  {
  tr='target=_blank';
  }
  mlink= "<a "+tr+" href="+document.forms[0].mhref.value+">";
  mlink+=  document.forms[0].mtext.value+"</a>";
  tinyMCEPopup.editor.execCommand('mceInsertContent', false, mlink);
  tinyMCEPopup.close();
 }
};
tinyMCEPopup.onInit.add(MicsDialog.init, MicsDialog);