hui.on(['hui','hui.parallax'],function(hui) {

  var theater = hui.find('.theater'),
    theater_photo = hui.find('.theater_photo',theater),
    theaters = hui.find('.theater_stages',theater);

  if (!theater) {
    return;
  }


  var currentWidth = hui.window.getViewWidth();
  var darkened = false;

  hui.parallax.listen({
    element : theater,
    $scroll : function(pos) {
      var dark = pos>0.2 && pos<.8 && currentWidth > 700;
      if (darkened!=dark) {
        hui.cls.set(document.body,'is-full',dark);
        hui.cls.set(document.body,'is-dark',dark);
        darkened = dark;
      }
      var show = pos>.3 && pos<.7;
      if (this.shown!=show) {
        if (show) {
          hui.animate({node:theater_photo,css:{opacity:show ? 1 : 0},ease:hui.ease.flicker,duration:3000,$complete : function() {
            hui.cls.set(theater,'is-final',pos>0 && pos<1);
          }});
        }
        this.shown = show;
      }
    }
  })
  hui.parallax.listen({
    $resize : function(width,height) {
      theater.style.height = width > 700 ? Math.round(height*.8)+'px' : '';
      currentWidth = width;
    }
  })
});