hui.onReady(function() {
  op.part.Formula = function(options) {
    this.element = hui.get(options.element);
    this.id = options.id;
    this.inputs = options.inputs;
    hui.listen(this.element,'submit',this._send.bind(this));
  }

  op.part.Formula.prototype = {
    _send : function(e) {
      hui.stop(e);

      var fields = [];

      for (var i=0; i < this.inputs.length; i++) {
        var info = this.inputs[i];
        var input = hui.get(info.id);
        var validation = info.validation;
        if (validation.required) {
          if (hui.isBlank(input.value)) {
            hui.ui.showMessage({text : validation.message,duration:2000});
            input.focus();
            return;
          }
        }
        if (validation.syntax=='email' && !hui.isBlank(input.value)) {
          var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\\n".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA\n-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          if (!re.test(input.value)) {
            hui.ui.showMessage({text : validation.message ,duration:2000});
            input.focus();
            return;
          }
        }
        fields.push({
          label : info.label,
          value : input.value
        })
      };

      var url = hui.ui.getContext() + 'services/parts/formula/';
      var data = {
        id : this.id,
        fields : fields
      }
      hui.ui.showMessage({text:'Sender besked...',busy:true});
      hui.ui.request({
        url : url,
        json : {data:data},
        $success : this._success.bind(this),
        $failure : this._failure.bind(this)
      });
    },
    _success : function() {
      hui.ui.showMessage({text:'Beskeden er nu sendt',icon:'common/success',duration:2000});
      this.element.reset();
    },
    _failure : function() {
      hui.ui.showMessage({text:'Beskeden kunne desværre ikke afleveres',duration:5000});
    }
  }
  hui.define('op.part.Formula',op.part.Formula);
})