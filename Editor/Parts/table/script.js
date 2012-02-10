var partController = {
	$ready : function() {
		var container = this.container = hui.get('part_table');
		this.base = hui.get.firstByClass(container,'part_table') || container;
		this.base.setAttribute('contenteditable','true');
		hui.listen(this.base,'keyup',function() {
			this._syncValue();
			this._syncSource();
		}.bind(this));
		hui.listen(this.base,'contextmenu',this._onMenu.bind(this));
		this._syncInfo();
		this.showInfo();
	},
	
	
	_onMenu : function(e) {
		e = hui.event(e);
		
	},
	
	_getTable : function() {
		var found = hui.get.firstByTag(this.container,'table');
		if (!found) {
			found = hui.build('table',{parent:this.container});
			hui.build('tbody',{parent:found})
		}
		return found;
	},
	clean : function() {
		var table = this._getTable();
		if (!table) {return}
		var nodes = this.container.getElementsByTagName('*');
		for (var i = nodes.length - 1; i >= 0; i--){
			nodes[i].removeAttribute('style');
		};
		var nodes = this.container.getElementsByTagName('td');
		for (var i = nodes.length - 1; i >= 0; i--) {
			hui.dom.setText(nodes[i],hui.dom.getText(nodes[i]));
		};
		hui.ui.showMessage({text:'Your royalty is now clean!',duration:3000});
		this._syncValue();
		this._syncSource();
		this._syncInfo();
	},
	addRow : function() {
		var table = this._getTable();
		hui.table.addRow(table);
		this._syncValue();
		this._syncSource();
	},
	addColumn : function() {
		var table = this._getTable();
		hui.table.addColumn(table);
		this._syncValue();
		this._syncSource();
	},
	showInfo : function() {
		propertiesWindow.show();
	},
	_syncSource : function() {
		sourceFormula.setValues({source:this.base.innerHTML});
	},
	_syncInfo : function() {
		var table = this._getTable();
		propertiesFormula.setValues({
			width : table.style.width
		});
	},
	_syncValue : function() {
		document.forms.PartForm.html.value = this.base.innerHTML;
	},
	
	// Source...
	
	editSource : function() {
		sourceWindow.show();
		sourceFormula.setValues({source:this.base.innerHTML});
	},
	$valuesChanged$sourceFormula : function(values) {
		this.base.innerHTML = values.source;
		this._syncValue();
		this._syncInfo();
	},
	
	
	// Info...
	
	$valuesChanged$propertiesFormula : function(values) {
		var table = this._getTable();
		table.style.width = values.width;
		this._syncSource();
		this._syncValue();
	}
	
};

hui.table = {
	addRow : function(table) {
		var trs = hui.get.byTag(table,'tr');
		if (trs.length>0) {
			var last = trs[trs.length-1];
			var tr = hui.build('tr');
			hui.dom.insertAfter(last,tr);
			var cells = hui.get.children(last);
			for (var i=0; i < cells.length; i++) {
				hui.build(cells[i].nodeName,{parent:tr,html:cells[i].innerHTML});
			};
		}
	},
	addColumn : function(table) {
		var trs = hui.get.byTag(table,'tr');
		for (var i=0; i < trs.length; i++) {
			var tr = trs[i];
			var cells = hui.get.children(tr);
			var last = cells[cells.length-1];
			hui.build(last.nodeName,{parent:tr,html:last.innerHTML});
		};
	}
}

hui.ui.listen(partController);