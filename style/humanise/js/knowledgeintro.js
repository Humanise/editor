hui.on(['hui'],function(hui) {
  var root = hui.find('.js-knowledgeintro');
  hui.log(root)

  var max = hui.findAll('.js-knowledgeintro-screen', root).length;
  var num = 1;
  var next;
  next = function() {
    hui.cls.remove(root,'is-'+num);
    num++;
    if (num > max) num = 1;
    hui.cls.add(root,'is-'+num);
    setTimeout(next, 4000)
  }
  setTimeout(next, 4000)
})