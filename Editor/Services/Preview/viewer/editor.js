op.Editor = {
  language : 'en',
  $ready : function() {
    hui.ui.tellContainers('pageLoaded',_editor.getPageId());
    if (hui.location.hasHash('edit')) {
      if (templateController!==undefined) {
        templateController.edit();
      }
    }
  },
  getToolbarController : function() {
    try {
      return window.parent.controller;
    } catch (e) {
      hui.log('Unable to find toolbar controller');
    }
  },
  $partChanged : function() {
    this.signalChange();
  },
  signalChange : function() {
    hui.ui.tellContainers('pageChanged',_editor.getPageId());
  }
}

hui.ui.listen(op.Editor);

//////////////////////// Parameters ////////////////////

hui.ui.listen({
  $ready : function() {
    var editable = document.querySelectorAll('*[data-editable]');
    var self = this;
    hui.each(editable,function(node) {
      //hui.cls.add(node,'editor_editable');
      hui.listen(node,'click',function(e) {
        e = hui.event(e);
        if (e.altKey) {
          self._edit(node);
        }
      })
    })
  },
  _edit : function(node) {
    // TODO only one per parameter
    new op.Editor.ParameterController(node);
  }
});

op.Editor.ParameterController = function(node) {
  this.node = node;
  this.original = node.innerHTML;
  this.id = null;
  var data = hui.string.fromJSON(node.getAttribute('data-editable'));
  this.name = data.name;
  this._load();
}

op.Editor.ParameterController.prototype = {
  _load : function(node) {
    hui.ui.request({
      url : 'data/LoadParameter.php',
      parameters : {name:this.name},
      $object : this._go.bind(this)
    })
  },
  _go : function(parameter) {
    this.id = parameter.id;
    var widgets = this._build();
    widgets.input.setValue(this.node.innerHTML);
    widgets.input.listen({$valueChanged:function(str) {
      this.node.innerHTML = str;
    }.bind(this)})
    widgets.window.setTitle(parameter.name + parameter.id);
    widgets.window.listen({
      $userClosedWindow : this._cancel.bind(this)
    })
    widgets.window.show();
  },
  _cancel : function() {
    this.node.innerHTML = this.original;
    hui.ui.destroy(this.window);
  },
  _save : function() {
    var html = this.node.innerHTML;
    var self = this;
    hui.ui.request({
      url : 'data/SaveParameter.php',
      parameters : {name:this.name,id:this.id,value:html},
      $success : function() {
        self.original = html;
        hui.ui.msg.success({text:'Saved'})
      }
    })
  },
  _delete : function() {
    hui.ui.request({
      url : 'data/DeleteParameter.php',
      parameters : {id:this.id},
      $success : this._cancel.bind(this)
    })
  },
  _build : function() {
    var win = this.window = hui.ui.Window.create({width:400});
    var input = hui.ui.CodeInput.create({height:300});
    var bar = hui.ui.Toolbar.create({align : 'left'});
    bar.add(hui.ui.Toolbar.Icon.create({
      icon : 'common/ok',
      title : 'Save',
      listener : {$click : this._save.bind(this)}
    }));
    bar.add(hui.ui.Toolbar.Icon.create({
      icon : 'common/delete',
      title : 'Delete',
      listener : {$click : this._delete.bind(this)}
    }));
    win.add(bar);
    win.add(input);
    return {window:win,input:input};
  }
}