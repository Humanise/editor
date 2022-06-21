hui.on(['hui','op'], function(hui, op) {
op.part.ImageGallery = function(options) {
  this.options = options;
  this.element = hui.get(options.element);
  this.images = options.images;
  if (options.variant=='masonry') {
    new op.part.ImageGallery.Masonry({
      element : options.element,
      height : options.height
    });
  }
  else if (options.variant=='changing') {
    op.part.ImageGallery.changing.init(this.element);
  }
  this._attach();
}

op.part.ImageGallery.prototype = {
  _attach : function() {
    hui.listen(this.element,'click',this._click.bind(this));
  },
  _click : function(e) {
    e = hui.event(e);
    var a = e.findByTag('a');
    if (a) {
      e.stop();
      this.showImage(a.getAttribute('data-id'), parseInt(a.getAttribute('data-index'),10), a);
    }
  },
  registerImage : function(node,image) {
    node = hui.get(node);
    if (this.options.editor) {
      return;
    }
    this.images.push(image);
    var self = this;
    node.onclick = function(e) {
      hui.stop(e);
      self.showImage(image.id);
      return false;
    }
  },
  showImage : function(id, index, node) {
    var previews = hui.findAll('a', this.element).map(function(node) {
      var img = hui.find('img', node);
      if (img) {
        return img.getAttribute('src');
      }
      var found = node.style.backgroundImage.match(/url\(["']?([^"'\)]+)/);
      if (found) {
        return found[1]
      }
    })
    if (!this._presentation) {
      this._presentation = hui.ui.Presentation.create({
        listen : {
          $getImage : function(e) {
            return op.getImageUrl(e.item,e.width,e.height);
          },
          $getPreview : function(e) {
            return previews[e.index];
          }
        }
      });
    }
    this._presentation.show({items:this.images, index: index, source: node});
  }
}

op.part.ImageGallery.changing = {
  init : function(element) {
    var nodes = hui.findAll('.part_imagegallery_item', element);
    if (nodes.length==0) {
      return;
    }
    element.style.height = element.clientHeight+'px';
    for (var i = 0; i < nodes.length; i++) {
      nodes[i].style.position = 'absolute';
    }
    var timer;
    var index = -1;
    var zIndex = 1;
    var first = true;
    timer = function() {
      if (index>-1) {
        hui.animate(nodes[index],'opacity',0,200,{hideOnComplete:true,delay:800});
      }
      index++;
      if (index > nodes.length - 1) {
        index = 0;
      }
      if (!first) {
        hui.style.set(nodes[index],{
          opacity: 0,
          zIndex : zIndex,
          display : 'block'
        })
        hui.animate(nodes[index],'opacity',1,1000,{ease:hui.ease.slowFastSlow});
      }
      window.setTimeout(timer,3000);
      zIndex++;
      first = false;
    }
    timer();
  }
}

op.part.ImageGallery.Masonry = function(options) {
  this.options = options;
  this.element = hui.get(options.element);
  this.name = options.name;

  this.height = parseInt(options.height,10) || 200;
  this.items = [];
  this.latestWidth = 0;

  hui.ui.extend(this);
  this._attach();
}

op.part.ImageGallery.Masonry.prototype = {
  _attach : function() {
    var links = hui.findAll('a', this.element);
    for (var i = 0; i < links.length; i++) {
      var data = hui.string.fromJSON(links[i].getAttribute('data'));
      data.href = links[i].href;
      this.items.push(data)
    }
    this.element.innerHTML = '';
    this._rebuild();
    hui.ui.listen({
      $$afterResize : this._rebuild.bind(this)
    })
    hui.listen(window,'scroll',this._reveal.bind(this));
  },
  _rebuild : function() {
    var fullWidth = this.element.clientWidth;
    if (Math.abs(this.latestWidth-fullWidth)<100) {
      return;
    }
    //hui.log('_rebuild');
    this.latestWidth = fullWidth;
    var rows = [];
    var row = [];
    rows.push(row);
    var pixels = 0;
    for (var i = 0,l = this.items.length; i < l; i++) {
      var item = this.items[i];
      item.revealed = false;
      item.index = i;
      var width = item.width/item.height * this.height;
      pixels+=width;
      var info = {width:width,item:item,percent:percent};
      if (pixels/fullWidth>1.2) {
        pixels = width;
        row = [];
        rows.push(row);
      }
      info.place = pixels;
      item.row = rows.length;
      row.push(info);
    }
    for (var i = 0; i < rows.length; i++) {
      var row = rows[i];
      var total = 0;
      for (var j = 0; j < row.length; j++) {
        total+=row[j].width;
      }
      var adjustment = fullWidth/total;
      var pos = 0;

      var rowHeight = Number.MAX_VALUE;

      for (var j = 0; j < row.length; j++) {
        var last = j == row.length - 1;
        var info = row[j], item = info.item;
        var percent = info.width / fullWidth * 100;
        percent = Math.round(adjustment * percent);
        pos+=percent;
        if (last) {
          percent+=100-pos;
          if (hui.browser.msie7 || hui.browser.msie6) {
            percent -= 0.1;
          }
        }
        info.percent = percent;
        rowHeight = Math.min(rowHeight, (percent/100 * fullWidth) * item.height/item.width );
      }
      rowHeight = Math.floor(rowHeight);
      for (var j = 0; j < row.length; j++) {
        var last = j == row.length - 1;
        var info = row[j], item = info.item;
        var percent = info.percent;

        var cls = last ? 'part_imagegallery_masonry_item part_imagegallery_masonry_item_last' : 'part_imagegallery_masonry_item';
        if (item.element) {
          item.element.style.width = percent + '%';
          item.element.style.height = rowHeight + 'px';
          item.element.className = cls;
        } else {
          item.element = hui.build('a',{
            'class' : cls,
            style : {
              width : percent + '%',
              height : rowHeight + 'px'
            },
            'data-id' : item.id,
            'data-index' : item.index,
            'data' : item.index,
            parent : this.element
          });
        }
      }
      for (var j = 0; j < row.length; j++) {
        row[j].item.element.style.height = rowHeight + 'px'
      }
    }
    this._reveal();
  },
  _getUrl : function(item,info) {
    var ratio = window.devicePixelRatio > 1 ? 2 : 1;
    return _editor.path('services/images/?id=' + item.id + '&width=' + (info.width * ratio) + '&height=' + (info.height * ratio) + '&method=crop');
  },
  _reveal : function() {
    var min = hui.window.getScrollTop();
    var max = min + hui.window.getViewHeight();
    for (var i = 0,l = this.items.length; i < l; i++) {
      var item = this.items[i],
        element = item.element;
      if (item.revealed) {
        continue;
      }
      var top = hui.position.getTop(element),
        bottom = top + this.height;
      if (top > max || bottom < min) {
        continue;
      }
      var height = item.element.clientHeight;
      //var width = item.element.clientWidth;
      var width = Math.round(item.width/item.height * height);
      item.element.style.backgroundImage = 'url(' + this._getUrl(item,{width:width,height:height}) + ')';
      item.revealed = true;
    }
  },
  _toggle : function(index) {
    var dur = 1000;
    if (this._toggled!==undefined) {
      var tog = this.items[this._toggled];
      hui.animate({node:tog.disclosed,css:{height:'0px'},duration:200,ease:hui.ease.fastSlow})
      hui.animate({node:tog.element,css:{marginBottom:'0px'},duration:200,ease:hui.ease.fastSlow,$complete : function() {
        tog.disclosed.style.display = 'none';
        var same = this._toggled === index;
        this._toggled = undefined;
        if (!same) {
          this._toggle(index);
        } else {
          this._reveal();
        }
      }.bind(this)})
      return;
    }
    this._toggled = index;
    var item = this.items[index],
      element = item.element;
    if (!item.disclosed) {
      item.disclosed = hui.build('div',{
        'class' : 'oo_masonry_disclosed',
        style : {top : (item.row*this.height+1)+'px'},
        parent: this.element
      })
    } else {
      item.disclosed.style.display='block';
    }
    var height = Math.round(this.latestWidth*.6);
    hui.animate({
      node : item.disclosed,
      css : {height: (height-1) + 'px'},
      duration : dur,
      ease : hui.ease.elastic
    })
    hui.animate({
      node : element,
      css : {marginBottom:height + 'px'},
      duration : dur,
      ease : hui.ease.elastic,
      $complete : function() {
        hui.window.scrollTo({
          element : item.disclosed,
          duration : 300
        });
      }
    })
  }
}

  hui.define('op.part.ImageGallery',op.part.ImageGallery)
})