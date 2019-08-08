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
          form.buildGroup(null,[
            {type:'TextInput',label:{en:'Username',da:'Brugernavn'},options:{key:'username'}},
            {type:'TextInput',label:{en:'Password',da:'Kodeord'},options:{key:'password',secret:true}}
          ]);
          var b = form.createButtons();

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
      hui.ui.msg.fail({text:{en:'Please fill in both fields',da:'Udfyld venligst begge felter'}});
      this.loginForm.focus();
      return;
    }

    hui.ui.request({
      message : {start:{en:'Logging in...',da:'Logger ind...'},delay:300},
      url : hui.ui.getContext() + 'Editor/Services/Core/Authentication.php',
      parameters : {username:username,password:password},
      $object : function(response) {
        if (response.success) {
          hui.ui.msg.success({text:{en:'You are now logged in',da:'Du er nu logget ind'}});
          op.goToEditor();
        } else {
          hui.ui.msg.fail({text:{en:'The user was not found',da:'Brugeren blev ikke fundet'}});
        }
      },
      $failure : function() {
        hui.ui.msg.fail({text:{en:'An internal error occurred',da:'Der skete en fejl internt i systemet'}});
      }
    });
  }
  op.showLogin();
})