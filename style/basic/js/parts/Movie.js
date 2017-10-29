hui.onReady(['op'],function(op) {
  op.part.Movie = function(options) {
    this.options = options;
    this.element = hui.get(options.element);
    this._attach();
  }

  op.part.Movie.prototype = {
    _attach : function() {
      hui.listen(this.element,'click',this._activate.bind(this));
      var poster = hui.get.firstByClass(this.element,'part_movie_poster');
      if (poster) {
        var id = poster.getAttribute('data-id');
        if (id) {
          // TODO listen for event when ayc style is loaded (or make css inline)
          window.setTimeout(function() {
            var x = window.devicePixelRatio || 1;
            var url = hui.ui.getContext() + 'services/images/?id=' + id + '&width=' + (poster.clientWidth * x) + '&height=' + (poster.clientHeight * x);
              poster.style.backgroundImage = 'url(' + url + ')';
          },200)
        } else {
          var vimeoId = poster.getAttribute('data-vimeo-id');
          if (vimeoId) {
            this._vimeo(vimeoId,poster);
          }
        }
      }
    },
    _activate : function() {
      var body = hui.get.firstByClass(this.element,'part_movie_body');
      var code = hui.get.firstByTag(this.element,'noscript');
      if (code) {
        body.innerHTML = hui.dom.getText(code);
        var iframe = hui.get.firstByTag(body,'iframe');
        if (iframe) {
          iframe.setAttribute('allowfullscreen','allowfullscreen');
          iframe.setAttribute('mozallowfullscreen','mozallowfullscreen');
          iframe.setAttribute('webkitallowfullscreen','webkitallowfullscreen');
        }
      }
      body.style.background='';
    },
    _vimeo : function(id,poster) {
      var cb = 'callback_' + id;

      var url = "http://vimeo.com/api/v2/video/" + id + ".json?callback=" + cb;

      window[cb] = function(data) {
        poster.style.backgroundImage = 'url(' + data[0].thumbnail_large + ')';
      }
      var script = hui.build('script',{type:'text/javascript',src:url,parent:document.head});
    }
  }

  hui.define(['op.part.Movie'],op.part.Movie);
})