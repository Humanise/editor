var controller = {
  $ready : function() {
    password1.focus();
    this.key = hui.location.getParameter('key');
  },
  $submit$formula : function() {
    var values = formula.getValues();
    if (hui.isBlank(values.password1) || hui.isBlank(values.password2)) {
      hui.ui.msg.fail({text:{en:'Please fill in both password',da:'Begge kodeord skal være udfyldt'}});
      formula.focus();
      return;
    } else if (values.password1!==values.password2) {
      hui.ui.msg.fail({text:{en:'The two passwords must be the same',da:'De to kodeord skal være ens'}});
      formula.focus();
      return;
    }
    change.disable();
    hui.ui.msg({text: {en:'Changing password...',da:'Ændrer kode...'}, busy: true});
    hui.ui.request({
      url : 'Services/Core/ChangePassword.php',
      $success : 'change',
      parameters : {key:this.key,password:values.password1},
      $failure : function() {
        hui.ui.msg.fail({text:{en:'An internal error occurred',da:'Der skete en fejl internt i systemet'}});
        change.enable();
      }
    });
  },
  $success$change : function(response) {
    change.enable();
    if (response.success) {
      hui.ui.hideMessage();
      hui.ui.changeState('success');
    } else {
      hui.ui.msg.fail({text:{en:'It was not possible to change the password',da:'Det lykkedes ikke at ændre kodeordet'}});
    }
  },
  $click$english : function() {
    hui.location.setParameter('language','en');
  },
  $click$danish : function() {
    hui.location.setParameter('language','da');
  }

}