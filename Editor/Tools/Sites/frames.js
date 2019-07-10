hui.ui.listen({

  frameId : null,

  $click$newFrame : function() {
    this.frameId = null;
    frameWindow.show();
    frameFormula.reset();
    deleteFrame.setEnabled(false);
    frameFormula.focus();
  },
  $open$list : function(row) {
    this.loadFrame(row.id);
  },
  loadFrame : function(id) {
    frameFormula.reset();
    deleteFrame.setEnabled(false);
    saveFrame.setEnabled(false);
    hui.ui.request({
      parameters : {id:id},
      url : 'data/LoadFrame.php',
      message : {start:{en:'Loading setup...', da:'Åbner opsætning...'},delay:300},
      $object : function(data) {
        frameFormula.setValues(data.frame);
        searchFormula.setValues({
          enabled : data.frame.searchEnabled,
          pageId : data.frame.searchPageId
        })
        userFormula.setValues({
          enabled : data.frame.userStatusEnabled,
          pageId : data.frame.loginPageId
        })
        topLinks.setValue(data.topLinks);
        bottomLinks.setValue(data.bottomLinks);
        frameWindow.show();
        deleteFrame.setEnabled(data.canRemove);
        saveFrame.setEnabled(true);
        this.frameId = data.frame.id;
        frameFormula.focus();
      }.bind(this)
    });
  },
  $click$saveFrame : function() {
    this._saveFrame();
  },
  $click$cancelFrame : function() {
    this.frameId = null;
    frameFormula.reset();
    frameWindow.hide();
  },
  $submit$frameFormula : function() {
    this._saveFrame();
  },
  _saveFrame : function() {
    var values = frameFormula.getValues();
    if (hui.isBlank(values.title) || values.hierarchyId==null) {
      hui.log(values);
      hui.ui.msg.fail({text:{en:'Title and hierarchy is required',da:'Titel og hierarki skal udfyldes'}});
      frameFormula.focus();
      return;
    }
    hui.ui.request({
      json : {
        id : this.frameId,
        frame : values,
        search : searchFormula.getValues(),
        user : userFormula.getValues(),
        topLinks : topLinks.getValue(),
        bottomLinks : bottomLinks.getValue()
      },
      url : 'actions/SaveFrame.php',
      message : {start:{en:'Saving setup', da:'Gemmer opsætning...'},delay:300},
      $success : function() {
        listSource.refresh();
      }.bind(this)
    });
    this.frameId = null;
    frameFormula.reset();
    frameWindow.hide();
  },
  $click$deleteFrame : function() {
    deleteFrame.setEnabled(false);
    hui.ui.request({
      parameters : { id : this.frameId },
      url : 'actions/DeleteFrame.php',
      message : {start:{en:'Deleting setup...', da:'Sletter ramme...'},delay:300},
      $success : function() {
        listSource.refresh();
      }.bind(this)
    });
    this.frameId = null;
    frameFormula.reset();
    frameWindow.hide();
  }
});