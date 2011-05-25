/**
 * @constructor
 */
hui.ui.Menu = function(options) {
	this.options = hui.override({autoHide:false,parentElement:null},options);
	this.element = hui.get(options.element);
	this.name = options.name;
	this.value = null;
	this.subMenus = [];
	this.visible = false;
	hui.ui.extend(this);
	this.addBehavior();
}

hui.ui.Menu.create = function(options) {
	options = options || {};
	options.element = hui.build('div',{'class':'hui_menu'});
	var obj = new hui.ui.Menu(options);
	document.body.appendChild(options.element);
	return obj;
}

hui.ui.Menu.prototype = {
	/** @private */
	addBehavior : function() {
		var self = this;
		this.hider = function() {
			self.hide();
		}
		if (this.options.autoHide) {
			var x = function(e) {
				if (!hui.ui.isWithin(e,self.element) && (!self.options.parentElement || !hui.ui.isWithin(e,self.options.parentElement))) {
					if (!self.isSubMenuVisible()) {
						self.hide();
					}
				}
			};
			hui.listen(this.element,'mouseout',x);
			if (this.options.parentElement) {
				hui.listen(this.options.parentElement,'mouseout',x);
			}
		}
	},
	addDivider : function() {
		hui.build('div',{'class':'hui_menu_divider',parent:this.element});
	},
	addItem : function(item) {
		var self = this;
		var element = hui.build('div',{'class':'hui_menu_item',text:item.title});
		hui.listen(element,'click',function(e) {
			hui.stop(e);
			self.itemWasClicked(item.value);
		});
		if (item.children) {
			var sub = hui.ui.Menu.create({autoHide:true,parentElement:element});
			sub.addItems(item.children);
			hui.listen(element,'mouseover',function(e) {
				sub.showAtElement(element,e,'horizontal');
			});
			self.subMenus.push(sub);
			hui.addClass(element,'hui_menu_item_children');
		}
		this.element.appendChild(element);
	},
	addItems : function(items) {
		for (var i=0; i < items.length; i++) {
			if (items[i]==null) {
				this.addDivider();
			} else {
				this.addItem(items[i]);
			}
		};
	},
	getValue : function() {
		return this.value;
	},
	itemWasClicked : function(value) {
		this.value = value;
		this.fire('itemWasClicked',value);
		this.fire('select',value);
		this.hide();
	},
	showAtPointer : function(e) {
		e = hui.event(e);
		e.stop();
		this.showAtPoint({'top' : e.getTop(),'left' : e.getLeft()});
	},
	showAtElement : function(element,event,position) {
		event = hui.event(event);
		event.stop();
		element = hui.get(element);
		var point = hui.getPosition(element);
		if (position=='horizontal') {
			point.left += element.clientWidth;
		} else if (position=='vertical') {
			point.top += element.clientHeight;
		}
		this.showAtPoint(point);
	},
	showAtPoint : function(pos) {
		var innerWidth = hui.getViewPortWidth();
		var innerHeight = hui.getViewPortHeight();
		var scrollTop = hui.getScrollTop();
		var scrollLeft = hui.getScrollLeft();
		if (!this.visible) {
			hui.setStyle(this.element,{'display':'block','visibility':'hidden',opacity:0});
		}
		var width = this.element.clientWidth;
		var height = this.element.clientHeight;
		var left = Math.min(pos.left,innerWidth-width-26+scrollLeft);
		var top = Math.max(0,Math.min(pos.top,innerHeight-height-20+scrollTop));
		hui.setStyle(this.element,{'top':top+'px','left':left+'px','visibility':'visible',zIndex:hui.ui.nextTopIndex()});
		if (!this.element.style.width) {
			this.element.style.width=(width+6)+'px';
		}
		if (!this.visible) {
			hui.setStyle(this.element,{opacity:1});
			this.addHider();
			this.visible = true;
		}
	},
	hide : function() {
		if (!this.visible) return;
		var self = this;
		hui.animate(this.element,'opacity',0,200,{onComplete:function() {
			self.element.style.display='none';
		}});
		this.removeHider();
		for (var i=0; i < this.subMenus.length; i++) {
			this.subMenus[i].hide();
		};
		this.visible = false;
	},
	isSubMenuVisible : function() {
		for (var i=0; i < this.subMenus.length; i++) {
			if (this.subMenus[i].visible) return true;
		};
		return false;
	},
	addHider : function() {
		hui.listen(document.body,'click',this.hider);
	},
	removeHider : function() {
		hui.unListen(document.body,'click',this.hider);
	}
}



/* EOF */