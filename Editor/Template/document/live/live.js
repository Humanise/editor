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
    hui.ui.request({
      url : op.context + 'Editor/Template/document/live/LoadPart.php',
      parameters : { type : part.type, id : part.id },
      $object : function(data) {
        hui.ui.tellContainers('openPart', {
          part : { id : part.id, type : part.type },
          data : data,
          custom : part.$getFormValues ? part.$getFormValues(data.part) : {}
        });
      },
      $failure : function() {
        // TODO
      }
    });
  },
  $cancelPart$huiEditor : function(part) {
    this.part.element.setAttribute('style',this.originalStyle);
  },
  $deactivatePart$huiEditor : function() {
    hui.ui.tellContainers('closePart');
  },
  // Called from parent frame TODO
  $partChanged$parent : function(values) {
    if (this.part.$updateFromForm) {
      this.part.$updateFromForm(values);
    }
  },
  // Called from parent frame TODO
  $sectionChanged$parent : function(values) {
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
    hui.ui.tellContainers('showPartInfo');
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
    var info = hui.ui.tellContainers('getPartInfo');
    if (!info) {
      hui.log('Unable to get part info');
      return options.callback();
    }
    var section = {
      id : this.section.id,
      'class' : info.section['class'],
      style : info.section.style,
      top : info.section.top,
      bottom : info.section.bottom,
      left : info.section.left,
      right : info.section.right,
      width : info.section.width,
      float : info.section.float
    };
    var parameters = hui.override({
      id : options.part.id,
      pageId : op.page.id,
      type : options.part.type,
      style : info.part.style,
      section : hui.string.toJSON(section)
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
    var id = info.node.getAttribute('data-id');
    hui.ui.tellContainers('editRow', {id:id});
  },
  $stopRowEditing$parent : function() {
    hui.ui.Editor.get().stopRowEditing();
  },
  $rowWasUpdated$parent : function() {
    hui.ui.Editor.get().stopRowEditing();
    op.Editor.signalChange();
  },

  // Columns

  _editedColumn : null,

  $editColumn$huiEditor : function(info) {
    var id = info.node.getAttribute('data-id');
    hui.ui.tellContainers('editColumn', {id:id});
  },
  $stopColumnEditing$parent : function() {
    hui.ui.Editor.get().stopColumnEditing();
  },
  $columnWasUpdated$parent : function() {
    hui.ui.Editor.get().stopColumnEditing();
    op.Editor.signalChange();
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