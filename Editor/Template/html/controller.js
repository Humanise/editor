var controller = {
  id : null,

  $ready : function() {
    hui.ui.request({
      url : 'data/Load.php',
      parameters : {id:this.id},
      $object : function(values) {
        formula.setValues(values);
        formula.focus();
      }.bind(this)
    });
  },
  $valuesChanged$formula : function() {
    save.enable();
    //hui.ui.stress(save);
  },
  $submit$formula : function() {
    save.disable();
    var values = formula.getValues();
    values.id = this.id;
    hui.ui.request({
      url : 'data/Save.php',
      parameters : values,
      $object : function(obj) {
        window.parent.frames[0].controller.markChanged();
        hui.ui.showMessage({text:'Ændringerne er nu gemt'+(obj.valid ? ' (valid)' : ' (invalid)'),duration:2000});
      }
    });
  },
  $click$convert : function() {
    hui.ui.request({
      url : 'data/Convert.php',
      parameters : {id: this.id},
      $success : function() {
        window.parent.location.reload();
      }
    });
  }
};

hui.ui.listen(controller);