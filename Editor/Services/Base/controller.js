var baseController = {
	$ready : function() {
		
	},
	changeSelection : function(key) {
		if (window['editToolbar']) {
			editToolbar.setSelection(key);
		}
		if (window['analyseToolbar']) {
			analyseToolbar.setSelection(key);
		}
		if (window['setupToolbar']) {
			setupToolbar.setSelection(key);
		}
		this.refresh();
	},
	refresh : function() {
		list.refresh();
		this._clearIssue();
	},
	
	goPublish : function() {
		dock.setUrl('Services/Publish/?close=../../Services/Start/');
	},
	
	$click$navNotes : function() {
		navPages.setSelected(!true);
		navNotes.setSelected(true);
		searchBar.hide();
		selector.hide();
		list.clear();
		list.show();
		list.setSource(issueSource);
	},
	
	$click$navPages : function() {
		navPages.setSelected(true);
		navNotes.setSelected(!true);
		searchBar.show();
		list.setSource(searchSource);
		if (search.isBlank()) {
			selector.show();
			list.hide();
			list.clear();
		} else {
			selector.hide();
			list.show();
		}
	},
	
	
	$selectionChanged$selector : function(item) {
		if (item.kind=='page') {
			dock.setUrl('Services/Preview/?id='+item.value);
		}
	},
	$valueChanged$search : function(value) {
		if (hui.isBlank(value)) {
			selector.show();
			list.hide();
		} else {
			selector.hide();
			list.show();
		}
	},
	
	$clickIcon$list : function(item) {
		if (item.row.kind=='page') {
			dock.setUrl('Template/Edit.php?id='+item.row.id);
		}
		if (item.row.kind=='issue') {
			issuePanel.position(item.node);
			this._loadIssue(item.row.id);
		}
	},
	
	$selectionChanged$list : function() {
		var row = list.getFirstSelection();
		if (row.kind=='page') {
			dock.setUrl('Services/Preview/?id='+row.id);
		}
	},
	
	///////////////// Notes ////////////////
	
	issueId : null,
	
	$click$cancelIssue : function() {
		this._clearIssue();
	},
	
	_clearIssue : function() {
		this.issueId = null;
		issueFormula.reset();
		issuePanel.hide();
		saveIssue.setEnabled(false);
		deleteIssue.setEnabled(false);
	},
	
	_loadIssue : function(id) {
		this.issueId = null;
		issueFormula.reset();
		saveIssue.setEnabled(false);
		deleteIssue.setEnabled(false);
		hui.ui.request({
			message : {start:'Henter note...',delay:300},
			url : 'Services/Model/LoadObject.php',
			parameters : {id:id},
			onJSON : function(obj) {
				this.issueId = id;
				issueFormula.setValues({text:obj.note,kind:obj.kind});
				saveIssue.setEnabled(true);
				deleteIssue.setEnabled(true);
				issuePanel.show();
			}.bind(this)
		})
	},
	$submit$issueFormula : function(form) {
		values = form.getValues();
		hui.ui.request({
			message : {start:'Gemmer note...',delay:300},
			url : 'Services/Base/data/SaveIssue.php',
			parameters : {id : this.issueId, text : values.text, kind : values.kind},
			onSuccess : function() {
				list.refresh();
			}
		})
		this._clearIssue();
	},
	
	$click$deleteIssue : function() {
		hui.ui.request({
			message : {start:'Sletter note...',delay:300},
			url : 'Services/Model/DeleteObject.php',
			parameters : {id : this.issueId},
			onSuccess : function() {
				list.refresh();
			}.bind(this)
		})
		this._clearIssue();
	}
}
hui.ui.listen(baseController);