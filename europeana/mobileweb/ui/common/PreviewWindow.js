var globals=require("/ui/common/globals"),css=require("/ui/common/css");
function fn(e){var c=Titanium.UI.createWindow({navBarHidden:!0,backgroundColor:"#000"}),a=Titanium.UI.createButton({image:"/images/glyphicons_212_down_arrow.png"}),g=function(){c.close()};a.addEventListener("click",g);a=Titanium.UI.iOS.createToolbar({top:0,right:0,left:0,height:40,items:[a],barColor:"#000000",borderTop:!1,borderBottom:!0});c.add(a);a=Titanium.UI.createButton({image:"/images/glyphicons_349_fullscreen.png"});a.addEventListener("click",function(){new (require("/ui/common/PlayWindow"))(e)});
a=Titanium.UI.iOS.createToolbar({bottom:0,right:0,left:0,height:40,items:[a],barColor:"#000000",borderTop:!0,borderBottom:!1});c.add(a);var h=require("/helpers/ajax"),a={};a.identifier=e;a.action="json-get";h.getdata({url:"http://aws2.glimworm.com/api.php",data:a,fn:function(a){Titanium.API.info(a);var d=Titanium.UI.createImageView({left:0,top:40,height:200,right:0,backgroundColor:"#000",image:a.data.thumbsrc}),b=Titanium.UI.createLabel({top:220,height:150,text:a.data.description});c.add(d);d.addEventListener("click",
g);c.add(b);for(d=0;d<a.data.suggestions.length;d++)if(b=a.data.suggestions[d],0==b.indexOf("FOUND NODE ")){Titanium.API.info(b);var e=b.substring(11),f=b.substring(54);Titanium.API.info(f);Titanium.API.info(b);b=Titanium.UI.createLabel({bottom:10,height:300,text:e+" / "+f});c.add(b);h.getdata({url:"http://aws2.glimworm.com/api.php?action=json-path&from=37777&to="+f,fn:function(a){Titanium.API.info(a);a=Titanium.UI.createLabel({bottom:40,height:150,text:a.data.txt});c.add(a)}})}}});globals.openmodal(c)}
module.exports=fn;