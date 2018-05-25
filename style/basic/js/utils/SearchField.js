hui.on(['op'], function(op) {
  op.SearchField = function(o) {
    o = this.options = hui.override({placeholderClass:'placeholder',placeholder:''},o);
    this.field = hui.get(o.element);
    this.field.onfocus = function() {
      if (this.field.value==o.placeholder) {
        this.field.value = '';
        hui.cls.add(this.field,o.placeholderClass);
      } else {
        this.field.select();
      }
    }.bind(this);
    this.field.onblur = function() {
      if (this.field.value=='') {
        hui.cls.add(this.field,o.placeholderClass);
        this.field.value=o.placeholder;
      }
    }.bind(this);
    this.field.onblur();
  }
  hui.define('op.SearchField', op.SearchField);
});