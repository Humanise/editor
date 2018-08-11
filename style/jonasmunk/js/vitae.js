hui.on(function() {
  function check() {
    var scrl = document.body.scrollTop || document.documentElement.scrollTop
    hero.style.filter = 'blur(' + Math.min(20,scrl/20) + 'px) brightness(' + (1 + scrl/400 * 1) + ')'
    //   saturate(' + Math.min(1,document.body.scrollTop/-150 + 1) + ') 
    var scale = Math.min(0,scrl) / -100 + 1;
    scale = Math.log(scale)*.6 + 1
    hero.style.transform = 'scale(' + scale + ') translate3d(0, -' + Math.max(0,scrl/5) + 'px, 0)'
    hero.style.opacity = 1 - scrl/320;
  }
  var hero = hui.find('.vitae_hero_body');
  if (!hero) {return};
  hui.listen(window,'scroll',function() {
    hui.onDraw(check)
  })
  check()
})
