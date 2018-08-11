op.SearchTemplate = function() {
  var container = hui.get.firstByClass(document.body,'search','div');
  hui.get.firstByClass(container,'text','input').focus();
}

hui.on(function() {
  new op.SearchTemplate();
});