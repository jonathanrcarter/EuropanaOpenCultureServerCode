define("Ti/_/declare,Ti/_/UI/Widget,Ti/_/dom,Ti/_/css,Ti/_/style,Ti/_/lang,Ti/UI".split(","),function(j,d,g,f,k,h,i){var e=require.on,c=k.set;return j("Ti.UI.Slider",d,{constructor:function(){var a=this,b,d,c=a._track=g.create("div",{className:"TiUISliderTrack"},a.domNode),f=a._thumb=g.create("div",{className:"TiUIElementGradient TiUISliderThumb"},a.domNode);e(a,"touchstart",function(c){b=c.x;d=a.value});e(a,"touchmove",function(e){a.value=(e.x-b)*(a.max-a.min)/(c.offsetWidth-f.offsetWidth)+d});e(a,
"postlayout",a,"_updatePosition")},_constrainedUpdate:function(a){this.properties.__values__.value=this._constrainValue(a);this._updatePosition()},_constrainValue:function(a){return Math.min(h.val(this.maxRange,this.max),Math.max(h.val(this.minRange,this.min),a))},_updatePosition:function(){var a=this._thumb;this._thumbLocation=Math.round((this._track.offsetWidth-a.offsetWidth)*((this.value-this.min)/(this.max-this.min)));c(a,"transform","translateX("+this._thumbLocation+"px)")},_defaultWidth:i.FILL,
_defaultHeight:i.SIZE,_setTouchEnabled:function(a){var b=a?"auto":"none";d.prototype._setTouchEnabled.call(this,a);c(this._track,"pointerEvents",b);c(this._thumb,"pointerEvents",b)},_handleTouchEvent:function(a,b){b.value=this.value;d.prototype._handleTouchEvent.call(this,a,b)},_getContentSize:function(){return{width:200,height:40}},properties:{enabled:{set:function(a,b){a!==b&&(f.remove(this._thumb,["TiUIElementGradient","TiUISliderThumbDisabled"]),f.add(this._thumb,a?"TiUIElementGradient":"TiUISliderThumbDisabled"),
this._setTouchEnabled(a));return a},value:!0},max:{set:function(a){return Math.max(this.min,a)},post:"_constrainedUpdate",value:1},maxRange:{set:function(a){return Math.min(this.max,a)},post:"_constrainedUpdate"},min:{set:function(a){return Math.min(this.max,a)},post:"_constrainedUpdate",value:0},minRange:{set:function(a){return Math.max(this.min,a)},post:"_constrainedUpdate"},value:{set:function(a){return this._constrainValue(a)},post:function(a,b){a!==b&&this.fireEvent("change",{value:a,thumbOffset:{x:0,
y:0},thumbSize:{height:0,width:0}});this._updatePosition()},value:0}}})});