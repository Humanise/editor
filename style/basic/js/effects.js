hui.on(function() {

  var Thing = function(node) {
    this.node = node;
    this.effect = node.getAttribute('data-build-in');
    this.top = 0;
    this.calc();
    this.shown = undefined;
  }

  Thing.prototype = {
    top : 0,
    calc : function() {
      this.top = hui.position.getTop(this.node) + (this.node.clientHeight * .8);
    },
    check : function(scrl) {
      var node = this.node;
      var effect = this.effect;
      var visible = scrl - this.top > 0;
      if (!visible && this.shown === undefined) {
        hui.cls.add(node,'effects_hidden');
        console.log('hiding',this.node);
      } else if (visible && this.shown === undefined) {
      } else if (visible && this.shown === false) {
        window.setTimeout(function() {
          hui.cls.remove(node,'effects_hidden');
          hui.cls.add(node,'effects_' + effect);
          setTimeout(function() {
            hui.cls.add(node,'effects_' + effect + '_visible');
          },50)
        }, 200)
        console.log('showing',this.node)
      }
      this.shown = this.shown || visible;
    }
  }

  var nodes = hui.findAll('*[data-build-in]');
  if (nodes.length == 0) { return; }
  var things = [];
  for (var i = 0; i < nodes.length; i++) {
    things.push(new Thing(nodes[i]));
  }
  var checker = function(e) {
    var scrl = hui.window.getScrollTop() + hui.window.getViewHeight();
    for (var i = 0; i < things.length; i++) {
      things[i].check(scrl);
    }
  };
  hui.on(window, 'scroll', checker);
  checker();
})