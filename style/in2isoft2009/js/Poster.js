hui.on(function() {
  Poster.getInstance().preload();
});

Poster = function() {
  this.poster = hui.get('poster');
  this.left = hui.get('poster_left');
  this.right = hui.get('poster_right');
  this.progress = hui.get('poster_loader');
  this.context = 'style/in2isoft2009/gfx/';
  this.leftImages = ['poster_in2isoft_image.png','poster_publisher_image.png','poster_in2igui_image.png','poster_onlineobjects_image.png'];
  this.rightImages = ['poster_in2isoft_text.png','poster_publisher_text.png','poster_in2igui_text.png','poster_onlineobjects_text.png'];
  this.links = ['om/','produkter/onlinepublisher/','teknologi/in2iGui/','produkter/onlineobjects/'];
  this.leftPos = 0;
  this.rightPos = 0;
  var self = this;
  this.poster.onclick = function() {
    document.location = _editor.path(self.links[self.leftPos]);
  }
}

Poster.getInstance = function() {
  if (!Poster.instance) {
    Poster.instance = new Poster();
  }
  return Poster.instance;
}

Poster.prototype.start = function() {
  this.left.scrollLeft = 495;
  var self = this;
  var base = _editor.path(this.context);
  new hui.animation.Loop([
    function() {
      self.leftPos++;
      if (self.leftPos>=self.leftImages.length) {
        self.leftPos=0;
      }
      hui.get('poster_inner_left').style.backgroundImage='url(\''+base+self.leftImages[self.leftPos]+'\')';

      self.rightPos++;
      if (self.rightPos>=self.rightImages.length) self.rightPos=0;
      hui.get('poster_inner_right').style.backgroundImage='url(\''+base+self.rightImages[self.rightPos]+'\')';
    },
    {duration:1000},
    {element:this.left,property:'scrollLeft',value:'0',duration:1000,ease:hui.ease.fastSlow,wait:500},
    {element:this.right,property:'scrollLeft',value:'495',duration:1000,ease:hui.ease.fastSlow},
    {duration:4000},
    {element:this.left,property:'scrollLeft',value:'495',duration:1000,ease:hui.ease.quintIn,wait:500},
    {element:this.right,property:'scrollLeft',value:'0',duration:1000,ease:hui.ease.quintIn}
  ]).start();
}

Poster.prototype.preload = function() {
  var loader = new hui.Preloader({context:_editor.path(this.context)});
  loader.setDelegate(this);
  loader.addImages(this.leftImages);
  loader.addImages(this.rightImages);
  loader.load();
}

Poster.prototype.allImagesDidLoad = function() {
  this.progress.style.display='none';
  this.start();
}

Poster.prototype.imageDidLoad = function(loaded,total) {
  this.progress.innerHTML=Math.round(loaded/total*100)+'%';
}