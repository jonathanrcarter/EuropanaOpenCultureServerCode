var gwdb=require("/helpers/gwdb"),globals=require("/ui/common/globals"),css=require("/ui/common/css");
function fn(a){var b={state:0};this.state=b;this.cnt=0;this.setstateOpen=function(a){try{this.state.state=1}catch(b){Ti.API.debug({fn:"AlertWin",from:a,e:b})}try{this.cnt=0}catch(c){Ti.API.debug({fn:"AlertWin",from:a,e:c})}};this.setstateClosed=function(a){try{this.state.state=0}catch(b){Ti.API.debug({fn:"AlertWin",from:a,e:b})}try{this.cnt=0}catch(c){Ti.API.debug({fn:"AlertWin",from:a,e:c})}};var c=Titanium.UI.createWindow({navBarHidden:!1,backgroundColor:"transparent",barColor:css.BARCOLOUR,top:0,
bottom:0,left:0,right:0,title:a}),d=Titanium.UI.createView({backgroundImage:"/images/wallpapers/wallpaper_3_1280x800.jpg",opacity:0.9,top:0,bottom:0,left:0,right:0}),a=Titanium.UI.createLabel({left:20,right:20,top:20}),f=Titanium.UI.createLabel({backgroundColor:"#0F2597",color:"#fff",textAlign:"center",text:"ALERT",left:0,right:0,height:40}),h=Titanium.UI.createTableView({left:10,top:10,right:10,bottom:10,borderWidth:5,borderRadius:20,borderColor:css.blue}),e=Titanium.UI.createView({left:10,top:10,
right:10,bottom:10,backgroundColor:"#fff",borderWidth:5,layout:"vertical",borderRadius:20,borderColor:css.blue}),i=Titanium.UI.createButton({title:"Breng me naar de auto",left:20,right:20,top:40,height:40,backgroundColor:"#0F2597",style:Titanium.UI.iPhone.SystemButtonStyle.PLAIN,bottom:null,borderRadius:10,borderWidth:0}),g=function(){Titanium.App.fireEvent("close_all_windows",{});Titanium.App.fireEvent("goto_map",{});c.close();b.state=0;globals.gototab(0)};i.addEventListener("click",g);var j=Titanium.UI.createButton({title:"Naar dichtstbijzijnde automaat om parkeertijd te verlengen",
left:20,right:20,top:20,height:40,backgroundColor:"#0F2597",style:Titanium.UI.iPhone.SystemButtonStyle.PLAIN,bottom:null,borderRadius:10,borderWidth:0});j.addEventListener("click",g);var k=Titanium.UI.createButton({title:"Ignore",left:20,right:20,top:20,height:40,backgroundColor:"#0F2597",style:Titanium.UI.iPhone.SystemButtonStyle.PLAIN,bottom:null,borderRadius:10,borderWidth:0}),g=function(){Titanium.App.fireEvent("close_all_windows",{});c.close();b.state=0};k.addEventListener("click",g);c.add(d);
c.add(e);e.add(f);e.add(a);e.add(i);e.add(j);e.add(k);d=Titanium.UI.createButton({title:"close"});d.addEventListener("click",g);Titanium.UI.createButton({title:"close"});f=function(){Titanium.App.fireEvent("close_all_windows",{});c.close();b.state=0;globals.gototab(0)};h.addEventListener("click",f);a.addEventListener("click",f);e.addEventListener("click",f);c.leftNavButton=d;this.win=c;this.lbl=a;this.tbl=h;this._view=e}module.exports=fn;fn.prototype.getView=function(){return this._view};
fn.prototype.close=function(){1==this.state.state&&(this.win.close(),this.setstateClosed())};
fn.prototype.open_nomessage=function(){require("/ui/common/globals").set("notifications","N");var a=require("/helpers/notify"),b=a.get(),c="";b&&0<b.length&&(c=b[b.length-1].msg);a.getrows();0==this.state.state?(this.win.title="Alarm!",this.lbl.text=c,this.win.open({modal:!1,modalTransitionStyle:Ti.UI.iPhone.MODAL_TRANSITION_STYLE_COVER_VERTICAL,modalStyle:Ti.UI.iPhone.MODAL_PRESENTATION_FORMSHEET}),this.setstateOpen.call(this,"prototype open")):(this.cnt++,this.win.title="Alarm!",this.lbl.text=c)};
fn.prototype.open=function(a,b,c){var d=require("/helpers/notify");d.notify(a,b,c);"fg"==globals.get("app_in")?(require("/ui/common/globals").set("notifications","N"),a=d.get(),b="",a&&0<a.length&&(b=a[a.length-1].msg),d.getrows(),0==this.state.state?(this.win.title="Alarm!",this.lbl.text=b,this.win.open({modal:!1,modalTransitionStyle:Ti.UI.iPhone.MODAL_TRANSITION_STYLE_COVER_VERTICAL,modalStyle:Ti.UI.iPhone.MODAL_PRESENTATION_FORMSHEET}),this.setstateOpen.call(this,"prototype open")):(this.cnt++,
this.win.title="Alarm!",this.lbl.text=b)):require("/ui/common/globals").set("notifications","Y")};