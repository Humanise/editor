/**
 * @constructor
 */
op.Editor.Widget = function(options) {
  this.element = hui.get(options.element);
  this.id = hui.ui.Editor.getPartId(this.element);
  this.part = null;
  this.original = this.element.innerHTML;
}

op.Editor.Widget.prototype = {

  type : 'widget',

  activate : function(callback) {
    this._load(callback);
  },
  save : function(options) {
    op.DocumentEditor.savePart({
      part : this,
      parameters : {data : this.part.data, key: this.part.key},
      $success : function(html) {
        this.element.innerHTML = html;
      }.bind(this),
      callback : options.callback
    });
  },
  cancel : function() {
    this.element.innerHTML = this.original;
    this.part = null;
  },
  deactivate : function(callback) {
    this.win.hide();
    callback();
  },
  getValue : function() {
    return this.value;
  },
  _load : function(callback) {
    op.DocumentEditor.loadPart({
      part : this,
      $success : function(part) {
        this.part = part;
        this._edit();
      }.bind(this),
      callback : callback
    })
  },
  _buildUI : function() {
    if (!this.win) {
      this.win = hui.ui.Window.create({width:500,title:'Widget',close:false});
      this.code = hui.ui.CodeInput.create();
      this.code.listen({
        $valueChanged : this._valueChanged.bind(this)
      })
      this.win.add(this.code);
      this.msg = hui.build('div',{style:'font-size: 11px; color: red; text-align: left; padding: 2px 0 0 3px'});
      this.win.add(this.msg);
    }
  },
  _valueChanged : function(value) {
    var valid = hui.xml.parse('<div>'+value+'</div>')!=null;
    this.part.data = value;
    hui.dom.setText(this.msg,valid ? '' : 'Ikke valid');
    if (valid) {
      this._draw();
    }
  },
  _edit : function() {
    this._buildUI();
    this.code.setValue(this.part.data);
    this.win.show();
    this._draw();
  },
  _draw : function() {
    window.clearTimeout(this._timer);
    var self = this;
    this._timer = window.setTimeout(function() {
      var url = op.context + 'Editor/Services/Parts/Preview.php';
      hui.ui.request({
        url : url,
        parameters : {
          type : 'widget',
          id : self.part.id,
          pageId : op.page.id,
          data : self.part.data,
          key : self.part.key
        },
        $text : function(str) {
          self.element.innerHTML = str;
          console.log(str)
        }
      });
    })
  }
}