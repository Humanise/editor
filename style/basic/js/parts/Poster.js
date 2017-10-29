hui.onReady(['op'], function(op) {
  op.part.Poster = function(options) {
    this.options = hui.override({duration:1500,interval:5000,delay:0},options);
    this.name = options.name;
    this.element = hui.get(options.element);
    this.container = hui.get.firstByClass(this.element,'part_poster_pages');
    this.pages = hui.get.byClass(this.element,'part_poster_page');
    this.index = 0;
    this.indicators = [];
    this._buildNavigator();
    if (!this.options.editmode) {
      window.setTimeout(this._callNext.bind(this),this.options.delay);
    }
    hui.listen(this.element,'click',this._onClick.bind(this));
    hui.ui.extend(this);
  }

  op.part.Poster.prototype = {
    _buildNavigator : function() {
      this.navigator = hui.build('div',{'class':'part_poster_navigator',parent:this.element});
      for (var i=0; i < this.pages.length; i++) {
        this.indicators.push(hui.build('a',{
          parent : this.navigator,
          data : i,
          href : 'javascript://',
          'class' : 'part_poster_point' + (i==0 ? ' is-current' : '')
        }));
      };
    },
    next : function() {
      var index = this.index+1;
      if (index>=this.pages.length) {
        index = 0;
      }
      this.goToPage(index);
    },
    previous : function() {
      var index = this.index - 1;
      if (index<0) {
        index = this.pages.length - 1;
      }
      this.goToPage(index);
    },
    setPage : function(index) {
      if (index===null || index===undefined || index == this.index || this.pages.length-1 < index) {
        return;
      }
      this.pages[this.index].style.display = 'none';
      this.pages[index].style.display = 'block';
      this.index = index;
      for (var i=0; i < this.indicators.length; i++) {
        hui.cls.set(this.indicators[i],'is-current',i==index);
      };
    },
    goToPage : function(index) {
      if (index==this.index) return;
      window.clearTimeout(this.timer);
      var recipe = {container:this.container,duration:this.options.duration};
      recipe.hide = {element:this.pages[this.index],effect:'slideLeft'};
      hui.cls.remove(this.indicators[this.index],'is-current');
      this.index = index;
      recipe.show = {element : this.pages[this.index],effect:'slideRight'};
      hui.cls.add(this.indicators[this.index],'is-current');
      hui.transition(recipe);
      if (!this.options.editmode) {
        this._callNext();
      }
      this.fire('pageChanged',index);
    },
    _callNext : function() {
      this.timer = window.setTimeout(this.next.bind(this),this.options.interval);
    },
    _onClick : function(e) {
      e = hui.event(e);
      var a = e.findByTag('a');
      if (a && hui.cls.has(a.parentNode,'part_poster_navigator')) {
        this.goToPage(parseInt(a.getAttribute('data')));
      }
    }
  }
  window.define && define('op.part.Poster');


  // Stuff...

  hui.transition = function(options) {
    var hide = options.hide,
      show = options.show;
    var showController = hui.transition[show.effect],
      hideController = hui.transition[hide.effect];

    hui.style.set(options.container,{height:options.container.clientHeight+'px',position:'relative'})
    hui.style.set(hide.element,{width:options.container.clientWidth+'px',position:'absolute',boxSizing:'border-box'})
    hui.style.set(show.element,{width:options.container.clientWidth+'px',position:'absolute',display:'block',visibility:'hidden',boxSizing:'border-box'})

    hui.animate({
      node : options.container,
      css : {height:show.element.clientHeight+'px'},
      duration : options.duration+10,
      ease : hui.ease.slowFastSlow,
      onComplete : function() {
        hui.style.set(options.container,{height:'',position:''})
      }
    })
    hideController.beforeHide(hide.element);
    hideController.hide(hide.element,options.duration,function() {
      hui.style.set(hide.element,{display:'none',position:'static',width:''})
    })

    showController.beforeShow(show.element);
    hui.style.set(show.element,{display:'block',visibility:'visible'})
    showController.show(show.element,options.duration,function() {
      hui.style.set(show.element,{position:'static',width:''})
    });
  }

  hui.transition.css = function(options) {
    this.options = options;
  }

  hui.transition.css.prototype = {
    beforeShow : function(element) {
      hui.style.set(element,this.options.hidden)
    },
    show : function(element,duration,onComplete) {
      hui.animate({
        node : element,
        css : this.options.visible,
        duration : duration,
        ease : hui.ease.slowFastSlow,
        onComplete : onComplete
      })
    },
    beforeHide : function(element) {
      hui.style.set(element,this.options.visible);
    },
    hide : function(element,duration,onComplete) {
      hui.animate({
        node : element,
        css : this.options.hidden,
        duration : duration,
        ease : hui.ease.slowFastSlow,
        onComplete : function() {
          onComplete();
          hui.style.set(element,this.options.visible);
        }.bind(this)
      })
    }
  }

  hui.transition.dissolve = new hui.transition.css({visible:{opacity:1},hidden:{opacity:0}});

  hui.transition.scale = new hui.transition.css({visible:{opacity:1,transform:'scale(1)'},hidden:{opacity:0,transform:'scale(.9)'}});

  hui.transition.slideLeft = new hui.transition.css({visible:{opacity:1,marginLeft:'0%'},hidden:{opacity:0,marginLeft:'-100%'}});

  hui.transition.slideRight = new hui.transition.css({visible:{opacity:1,marginLeft:'0%'},hidden:{opacity:0,marginLeft:'100%'}});

  hui.define('op.part.Poster', op.part.Poster);
})
