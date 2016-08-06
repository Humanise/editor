hui.ui.listen({
  streamId : null,

  $open$selector : function(item) {
    if (item.kind=='stream') {
      streamEditor.edit(item.value);
    }
  },
  $select$selector : function(item) {
    if (item.kind=='stream') {
      listBarText.setText(item.title + " (" + item.value + ")");
    }
  },
  $open$list : function(row) {
    if (row.kind=='streamitem') {
      streamItemEditor.edit(row.id);
    }
  },
  $changed$streamItemEditor : function() {
    list.refresh();
  },

  $changed$streamEditor : function() {
    selectionSource.refresh();
  },

  $click$newStream : function() {
    streamEditor.makeNew()
  }
});