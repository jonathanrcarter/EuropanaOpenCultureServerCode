var globals=require("/ui/common/globals"),css=require("/ui/common/css");
function fn(f,a){if(2==f){var b=Titanium.UI.createView({left:20,top:20,width:350,height:190,backgroundColor:"#000"}),c=Titanium.UI.createImageView({left:0,top:0,width:250,height:250,image:a.enclosure}),c=Titanium.UI.createWebView({left:0,top:0,width:350,height:350,html:"<html><head></head><body TOPMARGIN='0' LEFTMARGIN='0' MARGINHEIGHT='0' MARGINWIDTH='0'><img src='"+a.enclosure+"' style='border:0;padding:0;margin:0;' width='350'></body></html>",xcnt:cnt,xguid:a.guid}),g=Titanium.UI.createView({right:0,
top:0,width:100,height:200,backgroundColor:"#666"}),d=Titanium.UI.createScrollView({left:0,bottom:0,contentWidth:"auto",contentHeight:"auto",width:350,height:50,backgroundColor:"#000",opacity:0.7}),e=Titanium.UI.createLabel({height:Ti.UI.SIZE,width:300,left:20,color:"#fff",text:"C"+a.title,font:{fontFamily:"STHeitiTC-Medium"}});b.add(c);b.add(g)}else b=Titanium.UI.createView({left:20,top:20,width:250,height:120,backgroundColor:"#000"}),Titanium.UI.createImageView({left:0,top:0,width:250,height:250,
image:a.enclosure}),c=Titanium.UI.createWebView({left:0,top:0,width:250,height:250,html:"<html><head></head><body TOPMARGIN='0' LEFTMARGIN='0' MARGINHEIGHT='0' MARGINWIDTH='0'><img src='"+a.enclosure+"' style='border:0;padding:0;margin:0;' width='250'></body></html>",xcnt:cnt,xguid:a.guid}),d=Titanium.UI.createScrollView({left:0,bottom:0,contentWidth:"auto",contentHeight:"auto",width:250,height:50,backgroundColor:"#000",opacity:0.7}),e=Titanium.UI.createLabel({height:Ti.UI.SIZE,width:200,color:"#fff",
text:a.title,font:{fontFamily:"STHeitiTC-Medium"}}),b.add(c);b.add(d);d.add(e);return b}module.exports=fn;