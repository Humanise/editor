hui.on(function() {
  function Bestilling() {
    this.form = hui.get('bestilling');
    this.form.onsubmit = function() {
      try {this.submit();}
      catch (e) {
        console && console.error(e);
        hui.ui.msg.fail({text:'Der skete en fejl og bestillingen kunne ikke afsendes'});
      }
      return false;
    }.bind(this);
  }

  Bestilling.prototype = {
    submit : function(e) {
      if (hui.isBlank(this.form['name'].value)) {
        this.form['name'].focus();
        hui.ui.msg({text:'Navn skal udfyldes',duration:2000});
        return;
      }
      if (hui.isBlank(this.form['address'].value)) {
        this.form['address'].focus();
        hui.ui.msg({text:'Adressen skal udfyldes',duration:2000});
        return;
      }
      if (hui.isBlank(this.form['zipcode'].value)) {
        this.form['zipcode'].focus();
        hui.ui.msg({text:'Postnummeret skal udfyldes',duration:2000});
        return;
      }
      if (hui.isBlank(this.form['city'].value)) {
        this.form['city'].focus();
        hui.ui.msg({text:'Byen skal udfyldes',duration:2000});
        return;
      }
      if (hui.isBlank(this.form['phone'].value)) {
        this.form['phone'].focus();
        hui.ui.msg({text:'Telefonnummeret skal udfyldes',duration:2000});
        return;
      }
      if (hui.isBlank(this.form['email'].value)) {
        this.form['email'].focus();
        hui.ui.msg({text:'Emailadressen skal udfyldes',duration:2000});
        return;
      }
      var small8 = parseInt(this.form['small_8'].value);
      var large8 = parseInt(this.form['large_8'].value);
      var small10 = parseInt(this.form['small_10'].value);
      var large10 = parseInt(this.form['large_10'].value);
      var small24 = parseInt(this.form['small_24'].value);
      var large24 = parseInt(this.form['large_24'].value);
      //var modelE = parseInt(this.form['model_E'].value);
      if (!(small8>0) && !(large8>0) && !(small10>0) && !(large10>0) && !(small24>0) && !(large24>0)) { // && !(modelE>0)
        this.form['small_8'].focus();
        hui.ui.msg({text:'Udfyld venligst antal for mindst een af modellerne',duration:2000});
        return;
      }
      var message = [
        'Navn: '+this.form['name'].value,
        'Adresse: '+this.form['address'].value,
        'Postnummer: '+this.form['zipcode'].value,
        'By: '+this.form['city'].value,
        'Telefon: '+this.form['phone'].value,
        'Email: '+this.form['email'].value,
        '8 pigge:',
        '  Antal små: '+this.form['small_8'].value,
        '  Antal store: '+this.form['large_8'].value,
        '10 pigge:',
        '  Antal små: '+this.form['small_10'].value,
        '  Antal store: '+this.form['large_10'].value,
        '24 pigge:',
        '  Antal små: '+this.form['small_24'].value,
        '  Antal store: '+this.form['large_24'].value,
        //'Model E:',
        //'  Antal små: '+this.form['model_E'].value,
        'Besked: '+this.form['message'].value
      ];
      message = message.join('\n');
      parameters = {
        name : this.form['name'].value,
        email : this.form['email'].value,
        message : message
      }
      hui.ui.msg({text:'Sender bestilling, vent venligst...'});
      hui.ui.request({
        url : '/services/feedback/',
        parameters : parameters,
        $success:function() {
          hui.ui.msg({text:'Bestillingen er afsendt',icon:'common/ok',duration:4000});
          hui.ui.alert({title:'Bestillingen er modtaget',text:'Vi kontakter dig hurtigst muligt med yderligere information '});
        },
        $failure:function() {
          hui.ui.msg({text:'Sender bestilling, vent venligst... Fejl!',duration:2000});
          alert('Der skete en uventet fejl, kontakt venligst via email eller telefon istedet');
        }
      })
    }
  }
  if (hui.find('#bestilling')) {
    new Bestilling();
  }
});
