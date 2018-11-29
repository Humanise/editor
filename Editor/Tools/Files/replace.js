hui.ui.listen({
  $click$replace : function() {
    var obj = list.getFirstSelection();
    replaceFile.setParameter('id',obj.id);
    replaceWindow.show();
  },
  $uploadDidStartQueue$replaceFile : function() {
    cancelReplaceUpload.disable();
  },
  $uploadDidFail$replaceFile : function() {
    hui.ui.msg.fail({text:{en:'Unable to replace file. It may be too large.',da:'Det lykkedes ikke at erstatte filen. Den er måske for stor.'}});
    replaceFile.clear();
    cancelReplaceUpload.enable();
  },
  $uploadDidComplete$replaceFile : function() {
    filesSource.refresh();
    typesSource.refresh();
    replaceFile.clear();
    replaceWindow.hide();
    hui.ui.msg.success({text:{en:'The file has been replaced',da:'Filen er nu erstattet'}});
    cancelReplaceUpload.enable();
  },
  $click$cancelReplaceUpload : function() {
    replaceWindow.hide();
  }
});