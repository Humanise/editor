op.part.Image = function(options) {
  this.options = options;
  this.element = hui.get(options.element);
  this._attach();
}

op.part.Image.prototype = {
  _attach : function() {
    hui.on(this.element,'tap',function(e) {
      hui.stop(e);
      this._present();
    },this)
  },
  _present : function() {
    if (!this._presentation) {
      var preview = this.element.src;
      this._presentation = hui.ui.Presentation.create({
        listen : {
          $getImage : function(e) {
            return op.imageViewerDelegate.$resolveImageUrl(e.item,e.width,e.height);
          },
          $getPreview : function(e) {
            return preview;
          }
        }
      });
    }
    this._presentation.show({items:[{id:this.options.id,width:this.options.width, height:this.options.height}], source: this.element});
  }
}

window.define && define('op.part.Image');