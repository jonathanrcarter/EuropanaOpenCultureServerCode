windowFunctions["Key Values"]=function(){var f=createWindow(),c=addBackButton(f),c=Ti.UI.createScrollView({top:c+u,contentHeight:"auto",layout:"vertical"});f.add(c);var d=Ti.UI.createTextField({hintText:"Name",top:10+u,left:10+u,right:10+u,height:40+u,borderStyle:Ti.UI.INPUT_BORDERSTYLE_ROUNDED,autocapitalization:Ti.UI.TEXT_AUTOCAPITALIZATION_NONE,autocorrect:!1});c.add(d);var e=Ti.UI.createTextField({hintText:"Value",top:10+u,left:10+u,right:10+u,height:40+u,borderStyle:Ti.UI.INPUT_BORDERSTYLE_ROUNDED,
autocapitalization:Ti.UI.TEXT_AUTOCAPITALIZATION_NONE,autocorrect:!1});c.add(e);var b=Ti.UI.createButton({title:"Set",top:10+u,left:10+u,right:10+u,bottom:10+u,height:40+u});b.addEventListener("click",function(){Cloud.KeyValues.set({name:d.value,value:e.value},function(a){a.success?alert("Set!"):error(a)})});c.add(b);b=Ti.UI.createButton({title:"Get",top:0,left:10+u,right:10+u,bottom:10+u,height:40+u});b.addEventListener("click",function(){Cloud.KeyValues.get({name:d.value},function(a){a.success?
(e.value=a.keyvalues[0].value,alert("Got!")):error(a)})});c.add(b);b=Ti.UI.createButton({title:"Append",top:0,left:10+u,right:10+u,bottom:10+u,height:40+u});b.addEventListener("click",function(){Cloud.KeyValues.append({name:d.value,value:e.value},function(a){a.success?alert("Appended!"):error(a)})});c.add(b);b=Ti.UI.createButton({title:"Increment",top:0,left:10+u,right:10+u,bottom:10+u,height:40+u});b.addEventListener("click",function(){var a=parseInt(e.value,10);isNaN(a)?(alert("Enter a valid number for the increment"),
e.focus()):Cloud.KeyValues.increment({name:d.value,value:a},function(a){a.success?alert("Incremented!"):error(a)})});c.add(b);b=Ti.UI.createButton({title:"Remove",top:0,left:10+u,right:10+u,bottom:10+u,height:40+u});b.addEventListener("click",function(){Cloud.KeyValues.remove({name:d.value},function(a){a.success?(alert("Removed!"),e.value=""):error(a)})});c.add(b);f.addEventListener("open",function(){d.focus()});f.open()};