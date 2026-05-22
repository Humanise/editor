hui.on(function() {
  var search = hui.get('search');
  if (search) {
    hui.on(['hui.ui.SearchField'],function() {
      new hui.ui.SearchField({element:search,expandedWidth:200});
    })
  }
})
if (window.devicePixelRatio > 1) {
  document.body.className+=' retina';
}
