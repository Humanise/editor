hui.on(function() {
  op.showLogin = function() {
    if (!this.loginBox) {
      if (this.loadingLogin) {
        hui.log('Aborting, the box is loading')
        return;
      }
      this.loadingLogin = true;
      hui.ui.require(['Formula','Button','TextInput','Box'],
        function() {
          hui.ui.hideMessage();
          var box = this.loginBox = hui.ui.Box.create({width:300,title:{en:'Access control',da:'Adgangskontrol'},modal:true,absolute:true,closable:true,curtainCloses:true,padding:10});
          this.loginBox.addToDocument();
          var form = this.loginForm = hui.ui.Formula.create();
          form.listen({
            $submit : function() {
              if (!box.isVisible()) {
                // Be sure to not submit if no box
                return;
              }
              var values = form.getValues();
              op.login(values.username,values.password);
            }
          });
          var g = form.buildGroup(null,[
            {type:'TextInput',options:{label:{en:'Username',da:'Brugernavn'},key:'username'}},
            {type:'TextInput',options:{label:{en:'Password',da:'Kodeord'},key:'password',secret:true}}
          ]);
          var b = g.createButtons();

          var forgot = hui.ui.Button.create({text:{en:'Forgot password?',da:'Glemt kode?'}})
          forgot.listen({$click:function() {
            document.location = hui.ui.getContext() + 'Editor/Authentication.php?forgot=true';
          }});
          b.add(forgot);

          var cancel = hui.ui.Button.create({text:{en:'Cancel',da:'Annuller'}})
          cancel.listen({$click:function() {
            form.reset();
            box.hide();
            document.body.focus();
          }});
          b.add(cancel);

          b.add(hui.ui.Button.create({text:{en:'Log in',da:'Log ind'},highlighted:true,submit:true}));
          this.loginBox.add(form);
          this.loginBox.show();
          window.setTimeout(function() {
            form.focus();
          },100);
          this.loadingLogin = false;
          op.startListening();
        }.bind(this)
      );
    } else {
      hui.ui.hideMessage();
      this.loginBox.show();
      this.loginForm.focus();
    }
  }

  op.startListening = function() {
    hui.listen(window,'keyup',function(e) {
      e = hui.event(e);
      if (e.escapeKey && this.loginBox.isVisible()) {
        this.loginBox.hide();
        var a = hui.get.firstByTag(document.body,'a');
        if (a) {
          a.focus();
          a.blur();
        }
        document.body.blur();
      }
    }.bind(this));
  }

  op.login = function(username,password) {
    if (hui.isBlank(username) || hui.isBlank(password)) {
      hui.ui.showMessage({text:{en:'Please fill in both fields',da:'Udfyld venligst begge felter'},duration:3000});
      this.loginForm.focus();
      return;
    }

    hui.ui.request({
      message : {start:{en:'Logging in...',da:'Logger ind...'},delay:300},
      url : hui.ui.getContext() + 'Editor/Services/Core/Authentication.php',
      parameters : {username:username,password:password},
      $object : function(response) {
        if (response.success) {
          hui.ui.showMessage({text:{en:'You are now logged in',da:'Du er nu logget ind'},icon:'common/success',duration:4000});
          op.goToEditor();
        } else {
          hui.ui.showMessage({text:{en:'The user was not found',da:'Brugeren blev ikke fundet'},icon:'common/warning',duration:4000});
        }
      },
      $failure : function() {
        hui.ui.showMessage({text:{en:'An internal error occurred',da:'Der skete en fejl internt i systemet'},icon:'common/warning',duration:4000});
      }
    });
  }
  op.showLogin();
})