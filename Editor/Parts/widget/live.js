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
    op.DocumentEditor.loadPart({
      part : this,
      $success : function(part) {
        this.part = part;
        this._edit();
      }.bind(this),
      callback : callback
    })
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
    hui.ui.tellContainers('widgetDeactivate');
    callback();
  },
  getValue : function() {
    return this.value;
  },
  _valueChanged : function(value) {
    this.part.data = value;
    this._draw();
  },
  _edit : function() {
    hui.ui.tellContainers('widgetShowUI',{
      xml : this.part.data,
      $valueChanged : this._valueChanged.bind(this)
    });
    this._draw();
  },
  _draw : function() {
    window.clearTimeout(this._timer);
    var self = this;
    this._timer = window.setTimeout(function() {
      var url = hui.ui.getContext() + 'Editor/Services/Parts/Preview.php';
      hui.ui.request({
        url : url,
        parameters : {
          type : 'widget',
          id : self.part.id,
          pageId : _editor.getPageId(),
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