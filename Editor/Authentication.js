"use strict";

var controller = {
  $ready : function() {
    if (window.top!=window.self) {
      window.top.location=window.self.location;
    } else {
      username.focus();
    }
    if (hui.location.getBoolean('logout')) {
      hui.ui.msg.success({text:{en:'You have been logged out',da:'Du er nu logget ud'}});
    }
    else if (hui.location.getBoolean('forgot')) {
      this.$click$forgot();
    }
    hui.ui.request({
      method : 'GET',
      url : '../hui/info/preload.json',
      $object : function(obj) {
        var p = new hui.Preloader({context:hui.ui.context});
        p.addImages(obj);
        p.load();
      }
    });
  },
  $click$english : function() {
    hui.location.setParameter('language','en');
  },
  $click$danish : function() {
    hui.location.setParameter('language','da');
  },
  $submit$formula : function() {
    if (this.loggingIn) {
      return;
    }
    var values = formula.getValues();
    if (username.isBlank()) {
      username.stress();
      username.focus();
      return;
    }
    if (password.isBlank()) {
      password.stress();
      password.focus();
      return;
    }
    hui.ui.msg({text:{en:'Logging in...',da:'Logger ind...'},busy:true,delay:100});
    this.loggingIn = true;
    login.disable();
    hui.ui.request({
      url:'Services/Core/Authentication.php',
      $success: 'login',
      parameters : values,
      $failure:function() {
        hui.ui.msg.fail({text:{en:'An internal error occurred',da:'Der skete en fejl internt i systemet'}});
        this._enableLogin();
      }.bind(this)
    });
  },
  $success$login : function(data) {
    if (data.success) {
      hui.ui.msg.success({text:{en:'You are now logged in, just a moment...',da:'Du er nu logget ind, øjeblik...'},delay:200});
      var page = hui.location.getParameter('page');
      document.location = page===null ? './index.php' : '.?page='+page;
    } else {
      box.shake();
      //hui.ui.stress(box);
      hui.ui.msg.fail({text:{en:'The user was not found',da:'Brugeren blev ikke fundet'}});
      formula.focus();
    }
    this._enableLogin();
  },
  _enableLogin : function() {
    this.loggingIn = false;
    login.enable();
  },

  $click$forgot : function() {
    hui.ui.changeState('recover');
    recoveryForm.focus();
  },

  $submit$recoveryForm : function() {
    var text = recoveryForm.getValues()['nameOrMail'];
    hui.ui.msg({text:{en:'Looking for user and sending e-mail...',da:'Leder efter bruger, og sender e-mail...'},busy:true});
    hui.ui.request({
      url : 'Services/Core/RecoverPassword.php',
    $success : 'recovery',
      parameters : {text:text},
      $failure : function() {
        hui.ui.msg.fail({text:{en:'An internal error occurred',da:'Der skete en fejl internt i systemet'}});
      }
    });
  },
  $success$recovery : function(data) {
    if (data.success) {
      hui.ui.hideMessage();
      hui.ui.changeState('recoveryMessage');
    } else {
      hui.ui.msg.fail({text:data.message});
    }
  },

  // Database...

  $click$updateDatabase : function() {
    databaseWindow.show();
    databaseFormula.focus();
  },

  $submit$databaseFormula : function(form) {
    var values = form.getValues();
    if (hui.isBlank(values.username) || hui.isBlank(values.password)) {
      form.focus();
      return;
    }
    hui.ui.msg({text:{en:'Updating database...',da:'Opdaterer database...'},busy:true});
    hui.ui.request({
      url : 'Services/Core/UpdateDatabase.php',
      parameters : values,
      $failure : function() {
        hui.ui.msg.fail({text:{en:'An internal error occurred',da:'Der skete en fejl internt i systemet'}});
      },
      $forbidden : function() {
        hui.ui.msg.fail({text:{en:'The username or password is incorrect',da:'Bruger eller kode er ikke korrekt'}});
        form.focus();
      },
      $object : function(response) {
        databaseLog.setValue(response.log);
        databaseLogWindow.show();
        if (!response.updated) {
          hui.ui.msg.fail({text:{en:'The database is not completely updated, please try again',da:'Databasen er endnu ikke fuldt opdateret, prøv igen'}});
          return;
        }
        hui.ui.msg.success({text:{en:'The database isupdated',da:'Databasen er nu opdateret'}});
        databaseFormula.reset();
        databaseWindow.hide();
        hui.ui.changeState('login');
        formula.focus()
      }
    });
  },

  // Admin...
  $click$createAdmin : function() {
    adminWindow.show();
    adminFormula.focus();
  },
  $submit$adminFormula : function(form) {
    var values = form.getValues();
    if (hui.isBlank(values.superUsername) || hui.isBlank(values.superPassword) || hui.isBlank(values.adminUsername) || hui.isBlank(values.adminPassword)) {
      form.focus();
      hui.ui.msg.fail({text:{en:'Please fill in all fields',da:'Udfyld venligst alle felter'}});
      return;
    }
    hui.ui.request({
      url : 'Services/Core/CreateAdministrator.php',
      parameters : values,
      $failure : function() {
        hui.ui.msg.fail({text:{en:'An internal error occurred',da:'Der skete en fejl internt i systemet'}});
      },
      $forbidden : function() {
        hui.ui.msg.fail({text:{en:'The username or password is incorrect',da:'Bruger eller kode er ikke korrekt'}});
        form.focus();
      },
      $success : function(response) {
        hui.ui.msg.success({text:{en:'The administrator has been created',da:'Administratoren er nu oprettet'}});
        form.reset();
        adminWindow.hide();
        hui.ui.changeState('login');
        formula.focus()
      }
    });
  }
}