hui.ui.listen({
  id : null,

  $open$list : function(obj) {
    if (obj.kind=='design') {
      designFormula.reset();
      designEditor.show();
      hui.ui.request({parameters:{id:obj.id},url:'../../Services/Model/LoadObject.php',$success:'loadDesign'});
    }
  },
  $success$loadDesign : function(data) {
    this.id = data.id;
    designFormula.setValues(data);
    deleteDesign.setEnabled(true);
  },
  $click$newDesign : function() {
    this.id = null;
    designEditor.show();
    deleteDesign.setEnabled(false);
  },
  $click$cancelDesign : function() {
    designFormula.reset();
    designEditor.hide();
  },
  $click$deleteDesign : function() {
    hui.ui.request({json:{data:{id:this.id}},url:'../../Services/Model/DeleteObject.php',$success:'deleteDesign'});
  },
  $success$deleteDesign : function() {
    list.refresh();
    designFormula.reset();
    designEditor.hide();
  },
  $click$saveDesign : function() {
    var values = designFormula.getValues();
    if (hui.isBlank(values.title)) {
      hui.ui.msg.fail({text:'Du skal angive en titel!'});
      designFormula.focus();
    } else if (values.unique===null) {
      hui.ui.msg.fail({text:'Du skal vælge et design!'});
      designFormula.focus();
    } else {
      values.id = this.id;
      hui.ui.request({json:{data:values},url:'actions/SaveDesign.php',$success:'designSaved'});
    }
  },
  $success$designSaved : function() {
    list.refresh();
    designFormula.reset();
    designEditor.hide();
  }
});