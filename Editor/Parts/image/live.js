/**
 * @constructor
 */
op.Editor.Image = function(options) {
	this.element = hui.get(options.element);
	this.id = hui.ui.Editor.getPartId(this.element);
	this.header = hui.get.firstByTag(this.element,'*');
	this.section = {};
	this.field = null;
}

op.Editor.Image.prototype = {

	type : 'image',

	activate : function(callback) {
		this._load(callback);
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
	_edit : function() {
	},
	save : function(options) {
		op.DocumentEditor.savePart({
			part : this,
			parameters : {},
			$success : function(html) {
        // TODO
			}.bind(this),
			callback : options.callback
		});
	},
	cancel : function() {
	},
	deactivate : function(callback) {
		callback();
	},
	getValue : function() {
		return this.value;
	}
}