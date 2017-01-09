op.DocumentEditor = {

  part : null,
  section : null,
  partData : null,

  $partWasMoved$huiEditor : function(info) {
    var data = hui.string.fromJSON(info.dragged.getAttribute('data'));
    var p = {
      sectionId : data.id,
      rowIndex : info.rowIndex,
      columnIndex : info.columnIndex,
      sectionIndex : info.partIndex
    }
    hui.ui.request({
      url : op.context+'Editor/Template/document/live/MoveSection.php',
      parameters : p,
      message : {start:{en:'Moving...',da:'Flytter...'},delay:300},
      $success : function() {
        info.$success();
        op.Editor.signalChange();
      }.bind(this)
    })
  },

  $editPart$huiEditor : function(part) {
    this.part = part;
    this.originalStyle = this.part.element.getAttribute('style');
    hui.ui.tellContainers('editPart',{id:part.id});
  },
  $cancelPart$huiEditor : function(part) {
    this.part.element.setAttribute('style',this.originalStyle);
  },
  _initiatePartWindow : function() {
    hui.ui.get('layoutFormula').setValues(this.section);
    hui.ui.get('advancedFormula').setValues({
      'class' : this.section['class'],
      'sectionStyle' : this.section['style'],
      'partStyle' : this.partData.style
    });
    if (this.part.$partWindowLoaded) {
      this.part.$partWindowLoaded()
    }
    // Select the current page
    hui.ui.get('bar').select(hui.ui.get('pages').getPageKey());
    hui.ui.get('partWindow').show();
  },
  $deactivatePart$huiEditor : function() {
    var partWindow = hui.ui.get('partWindow');
    if (partWindow) {
      partWindow.hide();
      hui.ui.destroy(partWindow);
      hui.ui.destroyDescendants(partWindow);
      hui.dom.remove(partWindow.element);
    }
    hui.ui.tellContainers('cancelPart');
  },
  $clickButton$bar : function(button) {
    hui.ui.get('pages').goTo(button.getKey());
    hui.ui.get('bar').select(button.getKey());
  },
  $valuesChanged$layoutFormula : function(values) {
    this._updateSection(values);
    hui.override(this.section,values);
  },
  $valuesChanged$advancedFormula : function(values) {
    this.section['class'] = values['class'];
    this.section['style'] = values['sectionStyle'];
    this.partData.style = values['partStyle'];
  },
  _updateSection : function(values) {
    hui.style.set(this.part.element,{
      paddingTop : values.top,
      paddingBottom : values.bottom,
      paddingLeft : values.left,
      paddingRight : values.right,
      'float' : values.float,
      'width' : values.width
    });
  },
  $toggleInfo$huiEditor : function() {
    if (this._loadingPartWindow) {
      return;
    }
    if (hui.ui.get('partWindow')) {
      this._initiatePartWindow();
      return;
    }
    this._loadingPartWindow = true;
    hui.ui.include({
      url : op.context+'Editor/Template/document/live/gui/properties.php?type=' + this.part.type,
      $success : function() {
        this._initiatePartWindow();
        this._loadingPartWindow = false;
      }.bind(this)
    })
  },

  loadPart : function(options) {
    this.section = {};
    hui.ui.request({
      url : op.context+'Editor/Template/document/live/LoadPart.php',
      parameters : {type:options.part.type,id:options.part.id},
      $object : function(data) {
        options.$success(data.part);
        this.section = data.section;
        this.partData = data.part;
        options.callback();
      }.bind(this),
      $failure : function() {
        options.callback();
      }
    });
  },
  savePart : function(options) {
    var parameters = hui.override({
      id : options.part.id,
      pageId : op.page.id,
      type : options.part.type,
      style : this.partData.style,
      section : hui.string.toJSON(this.section)
    },options.parameters);
    hui.ui.request({
      url : op.context+'Editor/Template/document/live/SavePart.php',
      parameters : parameters,
      $text : function(html) {
        options.$success(html);
        options.callback();
      },
      $failure : function() {
        options.callback();
      }
    });
  },

  // Rows

  _editedRow : null,

  $editRow$huiEditor : function(info) {
    var self = this;
    this._requireStructureUI(function() {
      var win = hui.ui.get('rowWindow');
      win.show();
      win.setBusy('Loading...');
      hui.ui.request({
        url : op.context+'Editor/Template/document/live/LoadRow.php',
        parameters : {
          id : info.node.getAttribute('data-id')
        },
        $object : function(data) {
          self._editedRow = data;
          var form = hui.ui.get('rowFormula');
          form.setValues(data);
        },
        $finally : function() {
          win.setBusy(false);
        }
      });
    })
  },
  $close$rowWindow : function() {
    hui.ui.Editor.get().stopRowEditing();
  },
  $click$saveRow : function() {
    var win = hui.ui.get('rowWindow');
    var form = hui.ui.get('rowFormula');
    var values = form.getValues();
    win.setBusy(true);
    hui.ui.request({
      url : op.context + 'Editor/Template/document/live/SaveRow.php',
      parameters : {
        id : this._editedRow.id,
        style : values.style,
        layout : values.layout,
        'class' : values['class']
      },
      $success : function() {
        win.hide();
        hui.ui.Editor.get().stopRowEditing();
        op.Editor.signalChange();
      },
      $failure : function() {
        // TODO
      },
      $finally : function() {
        win.setBusy(false);
      }
    });
  },

  // Columns

  _editedColumn : null,

  $editColumn$huiEditor : function(info) {
    var self = this;
    this._requireStructureUI(function() {
      var win = hui.ui.get('columnWindow');
      win.show();
      win.setBusy('Loading...');
      hui.ui.request({
        url : op.context+'Editor/Template/document/live/LoadColumn.php',
        parameters : {
          id : info.node.getAttribute('data-id')
        },
        $object : function(data) {
          self._editedColumn = data;
          var form = hui.ui.get('columnFormula');
          form.setValues(data);
          self.$valuesChanged$columnFormula(data);
        },
        $finally : function() {
          win.setBusy(false);
        }
      });
    })
  },
  $close$columnWindow : function() {
    hui.ui.Editor.get().stopColumnEditing();
  },
  $click$saveColumn : function() {
    var win = hui.ui.get('columnWindow');
    var form = hui.ui.get('columnFormula');
    var values = form.getValues();
    win.setBusy(true);
    hui.ui.request({
      url : op.context + 'Editor/Template/document/live/SaveColumn.php',
      parameters : {
        id : this._editedColumn.id,
        style : values.style,
        'class' : values['class']
      },
      $success : function() {
        win.hide();
        hui.ui.Editor.get().stopColumnEditing();
        op.Editor.signalChange();
      },
      $failure : function() {
        // TODO
      },
      $finally : function() {
        win.setBusy(false);
      }
    });
  },

  _requireStructureUI : function(callback) {
    if (this._structureLoaded) {
      return callback();
    }
    var self = this;
    hui.ui.include({
      url : op.context + 'Editor/Template/document/live/gui/structure.php',
      $success : function() {
        self._structureLoaded = true;
        callback();
      }
    })
  },
  _parseStyle : function(xml) {
    var defs = [];
    var dom = hui.xml.parse('<style>' + xml + '</style>');
    if (dom) {
      var roots = dom.documentElement.children;
      for (var i = 0; i < roots.length; i++) {
        var root = roots[i];
        if (root.nodeName=='if') {
          var def = {
            'min-width' : root.getAttribute('min-width'),
            'max-width' : root.getAttribute('max-width'),
            'components' : []
          }
          defs.push(def);
          var components = root.children;
          for (var j = 0; j < components.length; j++) {
            if (components[j].nodeName == 'component') {
              var componentNode = components[j];
              var comDef = {
                name : componentNode.getAttribute('name'),
                rules : []
              }
              def.components.push(comDef);
              var ruleNodes = componentNode.children;
              for (var k = 0; k < ruleNodes.length; k++) {
                comDef.rules.push({
                  name : ruleNodes[k].nodeName,
                  value : ruleNodes[k].getAttribute('of')
                });
              }
            }
          }
        }
      }
    }
    return defs;
  },

  $valuesChanged$columnFormula : function(values) {
    var styleDefinition = this._parseStyle(values.style);
    var node = this._getColumnStyle();
    hui.dom.clear(node);
    for (var i = 0; i < styleDefinition.length; i++) {
      var def = styleDefinition[i];
      var text = 'Default';
      if (def['max-width'] || def['min-width']) {
        text = 'Max-width: ' + def['max-width'] + ', min-width: ' + def['min-width'];
      }
      hui.build('a',{text:text,parent:node});
    }
    //hui.dom.setText(node,hui.string.toJSON(styleDefinition))
  },
  _getColumnStyle : function() {
    if (!this._columnStyle) {
      this._columnStyle = hui.build('div');
      hui.ui.get('columnStyle').setContent(this._columnStyle);
      hui.listen(this._columnStyle,'click',function(e) {
        e = hui.event(e);
        var a = e.findByTag('a');
        console.log(a);
      })
    }
    return this._columnStyle;
  }
};

hui.ui.listen(op.DocumentEditor);

hui.ui.listen({
  $valuesChanged$columnFormula : function(values) {
    var dom = hui.xml.parse('<style>' + values.style + '</style>');
    if (dom) {
      var roots = dom.documentElement.children;
      for (var i = 0; i < roots.length; i++) {
        var root = roots[i];
        if (root.nodeName=='if') {
          var def = {
            'min-width' : root.getAttribute('min-width'),
            'max-width' : root.getAttribute('max-width'),
            'components' : []
          }
          console.log(def)
        }
      }
    }
  }
})

op.FieldResizer = function(options) {
  this.options = options;
  this.options.field.style.overflow='hidden';
  this.dummy = document.createElement('div');
  document.body.appendChild(this.dummy);
  this.dummy.style.position='absolute';
  this.dummy.style.left='-999999px';
  this.dummy.style.top='-999999px';
  this.dummy.style.visibility='hidden';
  var self = this;
  hui.listen(options.field,'keyup',function(){self.resize(false,true)});
  hui.listen(options.field,'keydown',function(){self.options.field.scrollTop=0;});
}

op.FieldResizer.prototype = {
  resize : function(instantly,focused) {

    var field = this.options.field;
    hui.style.copy(field,this.dummy,[
      'font-size','line-height','font-weight','letter-spacing','word-spacing','font-family','text-transform','font-variant','text-indent'
    ]);
    var html = field.value;
    if (html[html.length-1]==='\n') {
      html+='x';
    }
    // Force webkit redraw
    if (!focused) {
      field.style.display='none';
      field.offsetHeight; // no need to store this anywhere, the reference is enough
      field.style.display='block';
    }
    this.dummy.innerHTML = html.replace(/\n/g,'<br/>');
    this.options.field.style.webkitTransform = 'scale(1)';
    this.dummy.style.width=this.options.field.clientWidth+'px';
    var height = Math.max(50,this.dummy.clientHeight)+'px';
    if (instantly) {
      this.options.field.style.height=height;
    } else {
      //this.options.field.scrollTop=0;
      hui.animate(this.options.field,'height',height,200,{ease:hui.ease.slowFastSlow});
    }
  }
}