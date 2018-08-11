hui.on(['hui'], function(hui) {
  var root = hui.find('.js-hero');
  var pencil = hui.find('.hero_pencil');
  var src = hui.find('.hero_pencil').getAttribute('data');
  var img = new Image();
  img.onload = function() {
    hui.cls.add(pencil, 'is-loaded');
  }
  img.src = src;
});