hui.ui.listen({
  $pageLoaded : function() {
    var win = hui.ui.get('partWindow');
    win && win.hide()
  },

  $showPartInfo : function() {
    var win = hui.ui.get('partWindow');
    win && win.show()
  },
  $openPart : function(event) {
    this._loadUI(event)
  },
  _loadUI : function(event) {
    hui.log('Loading UI...')
    hui.ui.include({
      url : '../../Template/document/live/gui/properties.php?type=' + event.part.type,
      $success : function() {
        var data = event.data;
        console.log(event.data)
        hui.ui.get('advancedFormula').setValues({
          class: data.section['class'],
          partStyle: data.part.style,
          sectionStyle: data.section.style
        });
        hui.ui.get('layoutFormula').setValues({
          top: data.section.top,
          bottom: data.section.bottom,
          left: data.section.left,
          right: data.section.right,
          float: data.section.float,
          width: data.section.width
        })
        var custom = hui.ui.get('textFormula');
        if (custom) {
          custom.setValues(event.custom);
        }
        hui.ui.get('bar').select(hui.ui.get('pages').getPageKey());
        hui.ui.get('partWindow').show();
      }.bind(this),
      $faulure : function() {
        // TODO
      }
    })
  },
  _destroy : function() {
    var win = hui.ui.get('partWindow');
    if (win) {
      hui.ui.remove(win);
    }
  },
  $closePart : function(event) {
    this._destroy();
  },
  $getPartInfo : function() {
    var advanced = hui.ui.get('advancedFormula').getValues();
    var layout = hui.ui.get('layoutFormula').getValues();
    return {
      section : {
        'class' : advanced['class'],
        style : advanced['sectionStyle'],
        width : layout.width,
        float : layout.float,
        top: layout.top,
        bottom: layout.bottom,
        left: layout.left,
        right: layout.right
      },
      part : {
        style : advanced['partStyle']
      }
    };
  },

  $clickButton$bar : function(button) {
    hui.ui.get('pages').goTo(button.getKey());
    hui.ui.get('bar').select(button.getKey());
  },
  $valuesChanged$layoutFormula : function(values) {
    this._tellEditor('$sectionChanged$parent',values);
  },
  $valuesChanged$textFormula : function(values) {
    this._tellEditor('$partChanged$parent',values);
  },
  _tellEditor : function(method,event) {
    var win = simulator.getFrameWindow();
    if (!win || !win.op || !win.op.DocumentEditor) {
      hui.log('Unable to find win.op.DocumentEditor')
      return
    }
    win.op.DocumentEditor[method](event)
  },

  // Columns...

  _editedColumn : null,

  $editColumn : function(event) {
    var self = this;
    this._requireStructureUI(function() {
      var win = hui.ui.get('columnWindow');
      win.show();
      win.setBusy('Loading...');
      hui.ui.request({
        url : '../../Template/document/live/LoadColumn.php',
        parameters : {
          id : event.id
        },
        $object : function(data) {
          self._editedColumn = data;
          var form = hui.ui.get('columnFormula');
          form.setValues(data);
          self._updateColumnStyle(data.style);
        },
        $finally : function() {
          win.setBusy(false);
        }
      });
    })
  },
  _requireStructureUI : function(callback) {
    if (this._structureLoaded) {
      return callback();
    }
    var self = this;
    hui.ui.require(['StyleEditor'],function() {
      hui.ui.include({
        url : '../../Template/document/live/gui/structure.php',
        $success : function() {
          self._structureLoaded = true;
          callback();
        }
      })
    })
  },
  $close$columnWindow : function() {
    this._tellEditor('$stopColumnEditing$parent');
  },
  $click$saveColumn : function() {
    var win = hui.ui.get('columnWindow');
    var form = hui.ui.get('columnFormula');
    var values = form.getValues();
    win.setBusy(true);
    var self = this;
    hui.ui.request({
      url : '../../Template/document/live/SaveColumn.php',
      parameters : {
        id : this._editedColumn.id,
        style : values.style,
        'class' : values['class']
      },
      $success : function() {
        win.hide();
        self._tellEditor('$columnWasUpdated$parent');
      },
      $failure : function() {
        // TODO
      },
      $finally : function() {
        win.setBusy(false);
      }
    });
  },
  $click$columnSample : function() {
    var xml = ['<if max-width="600px">',
    '  <component name="column">',
    '    <background of="red"/>',
    '  </component>',
    '</if>']
    var form = hui.ui.get('columnFormula');
    form.setValues({style:xml.join('\n')});
  },
  $valuesChanged$columnFormula : function(values) {
    this._updateColumnStyle(values.style);
  },
  $valueChanged$columnStyleEditor : function(value) {
    var xml = this._serializeStyle(value);
    hui.ui.get('columnFormula').setValues({style:xml});
  },
  _updateColumnStyle : function(xml) {
    var parsed = this._parseStyle(xml);
    hui.ui.get('columnStyleEditor').setValue(parsed);
  },

  _serializeStyle : function(value) {
    var doc = document.implementation.createDocument('', 'style');
    var style = doc.documentElement;
    hui.each(value.queries, function(query, i) {
      var ifNode = doc.createElement('if');
      if (i > 0) {
        style.appendChild(doc.createTextNode("\n"))
      }
      style.appendChild(ifNode);
      hui.each(query, function(key, value) {
        if (key !== 'components') {
          ifNode.setAttribute(key, value);
        }
      });
      hui.each(query.components, function(component, j) {
        var compNode = doc.createElement('component');
        compNode.setAttribute('name', component.name);
        if (j == 0) {
          ifNode.appendChild(doc.createTextNode("\n"))
        }
        ifNode.appendChild(doc.createTextNode("  "))
        ifNode.appendChild(compNode);
        ifNode.appendChild(doc.createTextNode("\n"))
        var first = true;
        hui.each(component.rules, function(rule) {
          if (!hui.isBlank(rule.value)) {
            if (first) {
              compNode.appendChild(doc.createTextNode("\n  "))
            }
            var ruleNode = doc.createElement(rule.name);
            ruleNode.setAttribute('of', rule.value);
            compNode.appendChild(doc.createTextNode("  "))
            compNode.appendChild(ruleNode);
            compNode.appendChild(doc.createTextNode("\n  "))
            first = false;
          }
        })
      })
    })
    var xml = hui.xml.serialize(doc);
    var found = xml.match(/<style>([\w\W]*)<\/style>/);
    return found ? found[1] : '';
  },
  _parseStyle : function(xml) {
    var obj = {queries:[]}
    var dom = hui.xml.parse('<style>' + xml + '</style>');
    if (!dom) {
      return obj;
    }
    var ifs = dom.getElementsByTagName('if');
    for (var i = 0; i < ifs.length; i++) {
      var query = {components:[]}
      obj.queries.push(query);
      ifs[i].getAttributeNames().forEach(function(attrName) {
        query[attrName] = ifs[i].getAttribute(attrName);
      })
      ifs[i].childNodes.forEach(function(child) {
        if (child.nodeType==1 && child.nodeName=='component') {
          var component = {rules:[]};
          query.components.push(component);
          component.name = child.getAttribute('name');
          child.childNodes.forEach(function(rule) {
            if (rule.nodeType==1) {
              component.rules.push({name:rule.nodeName, value:rule.getAttribute('of')});
            }
          });
        }
      })
    }
    return obj;
  },

  // Rows...

  _editedRow : null,
  _rowCallback : null,

  $editRow : function(info) {
    var self = this;
    this._rowCallback = info;
    this._requireStructureUI(function() {
      var win = hui.ui.get('rowWindow');
      win.show();
      win.setBusy('Loading...');
      hui.ui.request({
        url : '../../Template/document/live/LoadRow.php',
        parameters : {
          id : info.id
        },
        $object : function(data) {
          self._editedRow = data;
          var form = hui.ui.get('rowFormula');
          form.setValues(data);
          self._updateRowStyle(data.style);
        },
        $finally : function() {
          win.setBusy(false);
        }
      });
    })
  },
  $close$rowWindow : function() {
    this._tellEditor('$cancelRowEditing$parent');
  },
  $click$saveRow : function() {
    var win = hui.ui.get('rowWindow');
    var form = hui.ui.get('rowFormula');
    var values = form.getValues();
    var self = this;
    win.setBusy(true);
    hui.ui.request({
      url : '../../Template/document/live/SaveRow.php',
      parameters : {
        id : this._editedRow.id,
        style : values.style,
        layout : values.layout,
        'class' : values['class']
      },
      $success : function() {
        win.hide();
        self._tellEditor('$rowWasUpdated$parent');
      },
      $failure : function() {
        // TODO
      },
      $finally : function() {
        win.setBusy(false);
      }
    });
  },
  $valuesChanged$rowFormula : function(values) {
    this._updateRowStyle(values.style);
    this._reDrawRowStyle(values.style);
  },
  $valueChanged$rowStyleEditor : function(value) {
    var xml = this._serializeStyle(value);
    hui.ui.get('rowFormula').setValues({style:xml});
    this._reDrawRowStyle(xml);
  },
  _updateRowStyle : function(xml) {
    var parsed = this._parseStyle(xml);
    hui.ui.get('rowStyleEditor').setValue(parsed);
  },
  _reDrawRowStyle : function(xml) {
    var id = this._editedRow.id;
    hui.ui.request({
      url : '../../Template/document/live/RenderRowStyle.php',
      parameters : {
        id : id,
        style : xml
      },
      $object : function(obj) {
        this._changeRowStyle(obj.css);
      }.bind(this),
      $failure : function() {
        // TODO
      },
      $finally : function() {
        // TODO
      }
    });
  },
  _changeRowStyle : function(css) {
    this._rowCallback.$rowChanged({css:css});
  }
})

hui.ui.listen({
  $pageLoaded : function() {
    if (this.designWindow) {
      hui.ui.remove(this.designWindow);
      this.designWindow = undefined;
    }
  },
  $click$design : function() {
    if (!this.designWindow) {
      hui.ui.request({
        url : 'viewer/data/LoadDesignInfo.php',
        parameters : {id:controller.pageId},
        message : {start:'Henter design info...',delay:300},
        $object : function(parameters) {
          if (parameters.length>0) {
            this._buildDesignForm(parameters);
            this.designWindow.show();
          } else {
            hui.ui.showMessage({text:'Dette design har ingen indstillinger',duration:3000})
          }
        }.bind(this)
      })
    } else {
      this.designWindow.show();
    }
  },

  _buildDesignForm : function(parameters) {
    var win = this.designWindow = hui.ui.Window.create({width:300,title:'Design',icon:'common/info',padding:10});
    var form = this.designFormula = hui.ui.Formula.create();
    form.listen({
      $submit : function() {
        var values = form.getValues();
        hui.ui.request({
          url : 'viewer/data/SaveDesignParameters.php',
          parameters : {id:controller.pageId,parameters:hui.string.toJSON(values)},
          $success : function() {
            win.hide();
            controller.reload();
          }
        })
      }
    })
    this.designGroup = this.designFormula.createGroup();

    var group = this.designFormula.createGroup();
    var buttons = group.createButtons();
    var btn = hui.ui.Button.create({text:'Opdater',submit:true});
    buttons.add(btn);

    win.add(form);

    for (var i=0; i < parameters.length; i++) {
      var parm = parameters[i];
      if (parm.type=='text') {
        var field = hui.ui.TextField.create({key:parm.key,label:parm.label,value:parm.value});
        this.designGroup.add(field);
      }
      else if (parm.type=='color') {
        var field = hui.ui.ColorInput.create({key:parm.key,value:parm.value});
        this.designGroup.add(field,parm.label);
      }
      else if (parm.type=='selection') {
        parm.options.unshift({});
        var field = hui.ui.DropDown.create({key:parm.key,label:parm.label,value:parm.value,items:parm.options});
        this.designGroup.add(field);
      }
      else if (parm.type=='image') {
        var field = hui.ui.DropDown.create({
          key : parm.key,
          label : parm.label,
          value : parm.value,
          url : '../Model/Items.php?type=image&includeEmpty=true'
        });
        this.designGroup.add(field);
      }
    };
  }
})