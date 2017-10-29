hui.onReady(['hui'],function(hui) {
  var check = function() {
    return (document.documentElement.scrollTop || document.body.scrollTop) > 42;
  }
  if (hui.browser.windows) {
    hui.cls.add(document.body,'windows');
  }
  if (hui.browser.webkit) {
    hui.cls.add(document.body,'webkit');
  }
    var scrolling = check();
    if (scrolling) {
      hui.cls.add(document.body,'scroll');
    }
    hui.listen(document,'scroll',function() {
    hui.onDraw(function() {
      var test = check();
      if (test!==scrolling) {
        hui.cls.set(document.body,'scroll',test);
        scrolling = test;
      }
    })
  })
})