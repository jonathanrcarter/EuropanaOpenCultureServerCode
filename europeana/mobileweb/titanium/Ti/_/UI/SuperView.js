define(["Ti/_/declare","Ti/UI","Ti/UI/View"],function(e,d,c){var b=[];return e("Ti._.UI.SuperView",c,{destroy:function(){this.close();c.prototype.destroy.apply(this,arguments)},open:function(){if(!this._opened){this._opened=1;d._addWindow(this,1).show();var a=b.length;a&&b[a-1]._handleBlurEvent(2);b.push(this);this.fireEvent("open");this._handleFocusEvent()}},close:function(){if(this.tab)this.tab.close(this);else if(this._opened){var a=b.indexOf(this),c=a===b.length-1;d._removeWindow(this);~a&&(c&&
this._handleBlurEvent(1),b.splice(a,1));this.fireEvent("close");if(c){for(a=b.length-1;0<=a&&!b[a]._opened;a--);0<=a&&b[a]._handleFocusEvent()}this._opened=0}},_handleFocusEvent:function(a){this.fireEvent("focus",a)},_handleBlurEvent:function(a){this.fireEvent("blur",a)}})});