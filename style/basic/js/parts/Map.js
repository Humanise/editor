hui.onReady(function() {
  op.part.Map = function(options) {
    this.options = hui.override({maptype:'roadmap',zoom:8},options);
    this.container = hui.get(options.element);
    hui.ui.onReady(this.initialize.bind(this));
  }

  op.part.Map.defered = [];

  op.part.Map.onReady = function(callback) {
    hui.log('onReady... loaded:'+this.loaded)
    if (this.loaded) {
      callback();
    } else {
      this.defered.push(callback);
    }
    if (this.loaded===undefined) {
      this.loaded = false;
      window.opMapReady = function() {
        hui.log('ready')
        for (var i=0; i < this.defered.length; i++) {
          this.defered[i]();
        };
        window.opMapReady = null;
        this.loaded = true;
      }.bind(this)
      hui.require('https://maps.googleapis.com/maps/api/js?callback=opMapReady&key=AIzaSyAJEsQcWdC9lpcA9BTmNaeVbRWF-XqCyh0');
    }
  }

  op.part.Map.types = {roadmap : 'ROADMAP',terrain:'TERRAIN'};

  op.part.Map.prototype = {
    initialize : function() {
      hui.log('init')
      op.part.Map.onReady(this.ready.bind(this));
    },
    ready : function() {
      var options = {
        zoom : this.options.zoom,
        center : new google.maps.LatLng(-34.397, 150.644),
        mapTypeId : google.maps.MapTypeId[this.options.type.toUpperCase()],
        scrollwheel : false
      };
      var markers = this.options.markers;
      if (this.options.center) {
        options.center = new google.maps.LatLng(this.options.center.latitude, this.options.center.longitude);
      }
      this.map = new google.maps.Map(this.container,options);

      if (this.options.center) {
          var marker = new google.maps.Marker({
              position : new google.maps.LatLng(this.options.center.latitude, this.options.center.longitude),
              map : this.map,
              icon : new google.maps.MarkerImage(
            hui.ui.getContext() + 'style/basic/gfx/part_map_pin.png',
                new google.maps.Size(29, 30), // Size
                new google.maps.Point(0,0), // Location (sprite)
                new google.maps.Point(8, 26)) // anchor
            });
        var text = hui.get.firstByClass(this.element,'part_map_text');
        if (text) {
          var info = new google.maps.InfoWindow({
            content : hui.build('div',{
              text : text.innerHTML,
              'class' : 'part_map_bubble'
            })
          })
          info.open(this.map,marker);
        }
      return
          var marker = new google.maps.Marker({
              position: new google.maps.LatLng(this.options.center.latitude, this.options.center.longitude),
              map: this.map
          });
      }
    }
  }

  hui.define('op.part.Map', op.part.Map)
})