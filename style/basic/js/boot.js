hui = window.hui || {_:[],on:function() {this._.push(arguments)}};

(function(window,document) {

  window.onerror = function(msg, url, line) {
    try {
      var encode = encodeURIComponent;
      var url = '/services/issues/scripterror/' +
        '?message=' + encode(msg) +
        '&file=' + encode(url) +
        '&line=' + encode(line) +
        '&url=' + encode(document.location.href);
      var img = document.createElement('img');
      img.src = url;
      img.style = 'width:1px;height:1px;position:absolute;';
      document.body.appendChild(img);
    } catch (ignore) {}
  }

  window._editor = {
    viewReady : function(func) {
      var raf = window.requestAnimationFrame || window.mozRequestAnimationFrame || window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
      if (raf) {
        return raf(func);
      }
      hui.on(func);
    },
    loadPart : function(info) {
      hui.on(['hui','hui.ui','op'],function() {
        _editor.loadScript(hui.ui.getContext()+'style/basic/js/parts/' + info.name + '.js');
      });
      hui.on(['op.part.'+info.name],info.$ready);
    },
    getPageId : function() {
      return parseInt(document.querySelector('*[data-page-id]').getAttribute('data-page-id'),10)
    },
    loadCSS : function(href) {
      this.viewReady(function() {
        _editor.inject(_editor._build('link',{
          rel : 'stylesheet',
          type : 'text/css',
          href : href
        }));
      });
    },
    _loaded : {},
    loadScript : function(src) {
      if (!this._loaded[src]) {
        this._loaded[src] = 1;
        _editor.inject(this._build('script',{async:'async',src:src}));
      }
    },
    _build : function(name,attributes) {
        var e = document.createElement(name);
        for (variable in attributes) {
          e.setAttribute(variable,attributes[variable]);
        }
        return e;
    },
    inject : function(node) {
        var h = document.getElementsByTagName('head')[0];
        if (h) {
          h.appendChild(node);
        } else {
          hui.on(function() {
            _editor.inject(node);
          })
        }
    }
  }

  hui.on(function() {
    var noscripts = document.getElementsByTagName('noscript');
    for (var i = 0; i < noscripts.length; i++) {
      var noscript = noscripts[i];
      if (noscript.className=='js-async' && noscript.firstChild) {
        var x = document.createElement('div');
        x.innerHTML = noscript.firstChild.nodeValue;
        var c = x.childNodes;
        while (c.length) {
          var removed = x.removeChild(c[0]);
          noscript.parentNode.insertBefore(removed,noscript);
        }
      }
    }
  });
})(window,document);