windowFunctions["Update Place"]=function(k){function l(){e.hide();Cloud.Places.update({place_id:k.id,name:c.value,address:f.value,city:g.value,state:h.value,postal_code:i.value},function(a){a.success?alert("Updated!"):error(a);e.show()})}var d=createWindow(),m=addBackButton(d),b=Ti.UI.createScrollView({top:m+u,contentHeight:"auto",layout:"vertical"});d.add(b);var c=Ti.UI.createTextField({hintText:"Name",top:10+u,left:10+u,right:10+u,height:40+u,borderStyle:Ti.UI.INPUT_BORDERSTYLE_ROUNDED});b.add(c);
var f=Ti.UI.createTextField({hintText:"Address",top:10+u,left:10+u,right:10+u,height:40+u,borderStyle:Ti.UI.INPUT_BORDERSTYLE_ROUNDED});b.add(f);var g=Ti.UI.createTextField({hintText:"City",top:10+u,left:10+u,right:10+u,height:40+u,borderStyle:Ti.UI.INPUT_BORDERSTYLE_ROUNDED});b.add(g);var h=Ti.UI.createTextField({hintText:"State",top:10+u,left:10+u,right:10+u,height:40+u,borderStyle:Ti.UI.INPUT_BORDERSTYLE_ROUNDED});b.add(h);var i=Ti.UI.createTextField({hintText:"Postal Code",top:10+u,left:10+u,
right:10+u,height:40+u,borderStyle:Ti.UI.INPUT_BORDERSTYLE_ROUNDED,keyboardType:Ti.UI.KEYBOARD_NUMBER_PAD});b.add(i);var e=Ti.UI.createButton({title:"Update",top:10+u,left:10+u,right:10+u,bottom:10+u,height:40+u});b.add(e);e.addEventListener("click",l);for(var b=[c,f,g,h,i],j=0;j<b.length;j++)b[j].addEventListener("return",l);var n=Ti.UI.createLabel({text:"Loading, please wait...",textAlign:"center",top:m+u,right:0,bottom:0,left:0,backgroundColor:"#fff",zIndex:2});d.add(n);d.addEventListener("open",
function(){Cloud.Places.show({place_id:k.id},function(a){n.hide();a.success?(a=a.places[0],c.value=a.name,f.value=a.address,g.value=a.city,h.value=a.state,i.value=a.postal_code,c.focus()):error(a)})});d.open()};