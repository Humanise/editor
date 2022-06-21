if (!op) {
  var op = {};
}

op.ignite = function(loggedIn) {
  hui.on(document,'keydown',function(e) {
    e = hui.event(e);
    if(e.returnKey && e.shiftKey) {
      e.stop();
      var temp;
      temp = function(e) {
        e = hui.event(e);
        if (e.returnKey) {
          hui.unListen(document,'keyup',temp);
          if (!hui.browser.msie && !loggedIn) {
            e.stop();
            op.showLogin();
          } else {
            op.goToEditor();
          }
        }
      }
      hui.listen(document,'keyup',temp);
    }
  })
  hui.on(function() {
    hui.request({
      url : _editor.path('services/statistics/'),
      parameters : {page : _editor.getPageId(), referrer : document.referrer, uri : document.location.href}
    });
  })
}

op.goToEditor = function() {
  window.location=_editor.path("Editor/index.php?page=" + _editor.getPageId());
}

op.showLogin = function() {
  op.showLogin = function(){};
  hui.ui.msg({text:{en:'Loading...',da:'Indlæser...'},busy:true,delay:300});
  _editor.loadScript(_editor.path('style/basic/js/utils/Login.js'));
}

op.getImageUrl = function(img,width,height) {
  var w = img.width ? Math.min(width,img.width) : width;
  var h = img.height ? Math.min(height,img.height) : height;
  var precision = 50;
  w = Math.ceil(w / precision) * precision;
  h = Math.ceil(h / precision) * precision;
  return _editor.path('services/images/?id='+img.id+'&width='+w+'&height='+h+'&format=jpg&quality=90');
}

if (op.part===undefined) {
  op.part = {};
}


/************* Feedback *************/

op.feedback = function(a) {
  hui.require(_editor.path('style/basic/js/Feedback.js'),function() {
    op.feedback.Controller.init(a);
  })
}

hui.on(function() {hui.define('op', op)})
