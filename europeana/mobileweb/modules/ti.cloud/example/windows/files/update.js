windowFunctions["Update File"]=function(i){function h(){d.hide();Ti.UI.createProgressBar&&(b.value=0,Cloud.onsendstream=function(a){b.value=0.5*a.progress},Cloud.ondatastream=function(a){b.value=0.5*a.progress+0.5});var a={file_id:i.id};""!=c.value&&(a.name=c.value);""!=e.value&&(a.file=Titanium.Filesystem.getFile(Ti.Filesystem.resourcesDirectory,"windows/files/"+e.value));Cloud.Files.update(a,function(a){Cloud.onsendstream=Cloud.ondatastream=null;a.success?alert("Updated!"):error(a);d.show()})}var f=
createWindow(),a=addBackButton(f),a=Ti.UI.createScrollView({top:a+u,contentHeight:"auto",layout:"vertical"});f.add(a);if(Ti.UI.createProgressBar){var b=Ti.UI.createProgressBar({top:10+u,right:10+u,left:10+u,max:1,min:0,value:0,height:25+u});a.add(b);b.show()}var c=Ti.UI.createTextField({hintText:"Name",top:10+u,left:10+u,right:10+u,height:40+u,borderStyle:Ti.UI.INPUT_BORDERSTYLE_ROUNDED});a.add(c);var e=Ti.UI.createTextField({hintText:"File name",top:10+u,left:10+u,right:10+u,height:40+u,borderStyle:Ti.UI.INPUT_BORDERSTYLE_ROUNDED});
a.add(e);var d=Ti.UI.createButton({title:"Update",top:10+u,left:10+u,right:10+u,bottom:10+u,height:40+u});a.add(d);d.addEventListener("click",h);for(var a=[c,e],g=0;g<a.length;g++)a[g].addEventListener("return",h);f.addEventListener("open",function(){c.focus()});f.open()};