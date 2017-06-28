hui.ui.listen({

  $select$selector : function(item) {
    if (item.value=='views') {
      listBarText.setText('Views');
    }
  },
  $select$list : function(row) {
    renderView.setEnabled(row && row.kind=='view');
  },
  $click$renderView : function() {
    var row = list.getFirstSelection();
    if (row) {
      window.open('actions/render.php?id=' + row.id)
    }
  },
  $open$list : function(row) {
    if (row.kind=='view') {
      viewEditor.edit(row.id);
    }
  },
  $changed$viewEditor : function() {
    list.refresh();
  }
});