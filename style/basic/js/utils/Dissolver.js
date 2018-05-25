hui.on(['op'], function(op) {
  op.Dissolver = function(options) {
    options = this.options = hui.override({wait:4000,transition:2000,delay:0},options);
    this.pos = Math.floor(Math.random()*(options.elements.length-.00001));
    this.z = 1;
    options.elements[this.pos].style.display='block';
    window.setTimeout(this.next.bind(this),options.wait+options.delay);
  }

  op.Dissolver.prototype = {
    next : function() {
      this.pos++;
      this.z++;
      var elm = this.options.elements;
      if (this.pos==elm.length) {
        this.pos=0;
      }
      var e = elm[this.pos];
      hui.style.setOpacity(e,0);
      hui.style.set(e,{display:'block',zIndex:this.z});
      hui.animate(e,'opacity',1,this.options.transition,{ease:hui.ease.slowFastSlow,onComplete:function() {
        window.setTimeout(this.next.bind(this),this.options.wait);
      }.bind(this)});
    }
  }
  hui.define('op.Dissolver', op.Disolver);
})