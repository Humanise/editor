var controller = {
  pageId : null,

  $ready : function() {
    hui.ui.tellContainers('changeSelection','service:preview');
    this._refreshBase();
  },
  $pageLoaded : function(id) {
    this.pageId = id;
    this._updateState();
    this._refreshBase();
    this.$click$cancelNote();
    reviewPanel.hide();
    this.$click$cancelPageProperties();
  },
  _refreshBase : function() {
    hui.ui.tellContainers('refreshBase');
  },
  _updateState : function() {
    hui.ui.request({
      url : 'data/LoadPageStatus.php',
      parameters : {id:this.pageId},
      $object : function(obj) {
        publish.setEnabled(obj.changed);
        var overlays = {none:null,accepted:'success',rejected:'stop'};
        review.setOverlay(overlays[obj.review]);
      }
    });
  },
  $pageChanged : function() {
    publish.setEnabled(true);
  },

  $click$close : function() {
    this.getFrame().location='../../Tools/Sites/';
  },
  $click$edit : function() {
    var frame = this.getFrame();
    if (frame.templateController!==undefined) {
      frame.templateController.edit();
    } else {
      document.location='../../Template/Edit.php';
    }
  },
  $click$properties : function() {
    pageProperties.show();
    pageProperties.setBusy(true);
    hui.ui.request({
      url : 'viewer/data/LoadPageProperties.php',
      parameters : {id:this.pageId},
      message : {start:'Loading info...',delay:300},
      $object : function(obj) {
        pageFormula.setValues(obj);
        pageProperties.setBusy(false);
      }.bind(this)
    })
  },
  $click$cancelPageProperties : function() {
    pageFormula.reset();
    pageProperties.hide();
  },
  $submit$pageFormula : function() {
    var values = pageFormula.getValues();
    values.id = this.pageId;
    pageProperties.setBusy(true);
    hui.ui.request({
      url : 'viewer/data/SavePageProperties.php',
      parameters : values,
      message : {start:{en:'Saving info...',da:'Gemmer info...'},delay:300},
      $success : function() {
        hui.ui.showMessage({text:{en:'The information is saved', da: 'Informationen er gemt'},icon:'common/success',duration:2000});
        pageFormula.reset()
        pageProperties.hide();
      }.bind(this),
      $finally : function() {
        pageProperties.setBusy(false);
      }
    });
  },
  $click$morePageInfo : function() {
    window.location='../../Tools/Sites/?pageInfo='+this.pageId;
  },
  $click$view : function() {
    window.parent.location='ViewPublished.php';
  },
  $click$publish : function() {
    hui.ui.request({
      url : 'viewer/data/PublishPage.php',
      parameters : {id:this.pageId},
      $success : function(obj) {
        publish.setEnabled(false);
        hui.ui.tellContainers('pageChanged',this.pageId);
      }
    });
  },
  getFrame : function() {
    return hui.ui.get('simulator').getFrameWindow();
  },
  reload : function() {
    this.getFrame().location.reload();
  },
  $click$viewHistory : function() {
    this.getFrame().location = '../PageHistory/';
  },

/*
  ///////////// Design /////////////

  $click$design : function() {
    this.getFrame().op.Editor.editDesign();
  },
*/
  ///////////// Notes //////////////

  $click$addNote : function() {
    notePanel.show();
    noteFormula.focus();
  },
  $submit$noteFormula : function(form) {
    var values = form.getValues();
    hui.ui.request({
      message : {start:'Gemmer note...',delay:300},
      url : 'data/CreateNote.php',
      parameters : {pageId : this.pageId, text : values.text, kind : values.kind},
      $success : function() {
        hui.ui.showMessage({text:'Noten er gemt',icon:'common/success',duration:2000});
        this._refreshBase();
      }.bind(this)
    });
    noteFormula.reset();
    notePanel.hide();
  },
  $click$cancelNote : function() {
    noteFormula.reset();
    notePanel.hide();
  },

  //////////// Review //////////////

  $click$review : function() {
    reviewPanel.show();
    reviewList.setUrl('data/ListReviews.php?pageId='+this.pageId);
  },
  $click$reviewReject : function() {
    this._sendReview(false);
  },
  $click$reviewAccept : function() {
    this._sendReview(true);
  },
  _sendReview : function(accepted) {
    hui.ui.request({
      url : 'data/Review.php',
      parameters : {pageId : this.pageId, accepted : accepted},
      $success : function() {
        hui.ui.showMessage({text:'Revisionen er gemt!',icon:'common/success',duration:2000});
        reviewPanel.hide();
        this._updateState();
        this._refreshBase();
      }.bind(this)
    });
  },

  ////////////////// New page /////////////////

  $click$newPage : function() {
    newPagePanel.show();
    newPageFormula.focus();
  },
  $click$cancelNewPage : function() {
    newPagePanel.hide();
  },
  $submit$newPageFormula : function(form) {
    var values = form.getValues();
    if (hui.isBlank(values.title)) {
      newPageFormula.focus();
      return;
    }
    hui.ui.request({
      url : 'data/CreatePage.php',
      parameters : {
        pageId : this.pageId,
        title : values.title,
        placement : values.placement
      },
      $object : function(response) {
        document.location = 'index.php?id=' + response.id;
      }
    });
  }
};

hui.ui.listen(controller);

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
    hui.ui.include({
      url : '../../Template/document/live/gui/structure.php',
      $success : function() {
        self._structureLoaded = true;
        callback();
      }
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

  // Rows...

  _editedRow : null,

  $editRow : function(info) {
    var self = this;
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
        },
        $finally : function() {
          win.setBusy(false);
        }
      });
    })
  },
  $close$rowWindow : function() {
    this._tellEditor('$stopRowEditing$parent');
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