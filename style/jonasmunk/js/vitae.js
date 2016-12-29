hui.onReady(function() {
  var hero = hui.find('.vitae_hero_body');
  hui.listen(window,'scroll',function() {
    hui.onDraw(function() {
      var scrl = document.body.scrollTop || document.documentElement.scrollTop
      hero.style.filter = 'blur(' + Math.min(20,scrl/20) + 'px) brightness(' + Math.max(.5,1 - scrl/400) + ')'
      //   saturate(' + Math.min(1,document.body.scrollTop/-150 + 1) + ') 
      var scale = Math.min(0,document.body.scrollTop) / -100 + 1;
      scale = Math.log(scale)*.6 + 1
      hero.style.transform = 'scale(' + scale + ') translate3d(0, -' + Math.max(0,scrl/5) + 'px, 0)'
    })
  })
})
