hui.ui.listen({
  $widgetShowUI : function(event) {
    this._buildUI();
    this.code.setValue(event.xml);
    this.win.show();
    this.callback = event.$valueChanged;
  },
  $widgetDeactivate : function() {
    this.win.hide();
  },
  $pageLoaded : function() {
    if (this.win) {
      this.win.hide();
    }
  },
  _buildUI : function() {
    if (!this.win) {
      this.win = hui.ui.Window.create({width:500,title:'Widget',close:false, variant:'light'});
      this.code = hui.ui.CodeInput.create();
      this.code.listen({
        $valueChanged : this._valueChanged.bind(this)
      })
      this.win.add(this.code);
      this.msg = hui.build('div',{style:'font-size: 11px; color: red; text-align: left; padding: 2px 0 0 3px; display: none;'});
      this.win.add(this.msg);
    }
  },
  _valueChanged : function(value) {
    var valid = hui.xml.parse(value) != null;
    this.msg.style.display = valid ? 'none' : '';
    if (valid) {
      this.callback(value);
    }
  }
})