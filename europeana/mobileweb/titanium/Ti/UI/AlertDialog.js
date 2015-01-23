define("Ti/_/css,Ti/_/declare,Ti/_/lang,Ti/_/Evented,Ti/Locale,Ti/UI".split(","),function(h,i,j,k,c,a){return i("Ti.UI.AlertDialog",k,{show:function(){var f=this._alertWindow=a.createWindow(),b=a.createView({backgroundColor:"black",opacity:0,left:0,top:0,right:0,bottom:0}),d=a.createView({backgroundColor:"white",borderRadius:3,height:a.SIZE,layout:a._LAYOUT_CONSTRAINING_VERTICAL,opacity:0,width:"50%"}),g=this.buttonNames||[];f._add(b);f._add(d);d._add(a.createLabel({text:c._getString(this.titleid,
this.title),font:{fontWeight:"bold"},left:5,right:5,top:5,height:a.SIZE,textAlign:a.TEXT_ALIGNMENT_CENTER}));d._add(a.createLabel({text:c._getString(this.messageid,this.message),left:5,right:5,top:5,height:a.SIZE,textAlign:a.TEXT_ALIGNMENT_CENTER}));g.length||g.push(c._getString(this.okid,this.ok||"OK"));g.forEach(function(c,e){var b=a.createButton({left:5,right:5,top:5,bottom:e===g.length-1?5:0,height:a.SIZE,title:c,index:e});e===this.cancel&&h.add(b.domNode,"TiUIElementGradientCancel");d._add(b);
b.addEventListener("singletap",j.hitch(this,function(){f.close();this._alertWindow=void 0;this.fireEvent("click",{index:e,cancel:this.cancel===e})}))},this);b.addEventListener("postlayout",function(){setTimeout(function(){b.animate({opacity:0.5,duration:200},function(){d.animate({opacity:1,duration:200})})},0)});f.open()},hide:function(){this._alertWindow&&this._alertWindow.close()},properties:{buttonNames:void 0,cancel:-1,message:void 0,messageid:void 0,ok:void 0,okid:void 0,title:void 0,titleid:void 0}})});