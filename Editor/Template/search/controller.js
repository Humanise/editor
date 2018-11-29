var controller = {
  id : null,

  $ready : function() {
    hui.ui.request({
      url : 'data/Load.php',
      parameters : {id:this.id},
      $object : function(values) {
        formula.setValues(values);
      }.bind(this)
    });
  },
  $valuesChanged$formula : function() {
    save.enable();
    //hui.ui.stress(save);
  },
  $click$save : function() {
    save.disable();
    var values = formula.getValues();
    values.id = this.id;
    hui.ui.request({
      url : 'data/Save.php',
      json : {data:values},
      $success : function(values) {
        window.parent.Toolbar.publish.enable();
        hui.ui.msg.success({text:'Ændringerne er nu gemt'});
      }
    });
  }
};

hui.ui.listen(controller);