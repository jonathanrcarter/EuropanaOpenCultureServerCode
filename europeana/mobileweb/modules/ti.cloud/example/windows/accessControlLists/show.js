windowFunctions["Show ACL"]=function(){var f=createWindow(),b=addBackButton(f),b=Ti.UI.createScrollView({top:b+u,contentHeight:"auto",layout:"vertical"});f.add(b);var c=Ti.UI.createTextField({hintText:"Name",top:10+u,left:10+u,right:10+u,height:40+u,borderStyle:Ti.UI.INPUT_BORDERSTYLE_ROUNDED,autocapitalization:Ti.UI.TEXT_AUTOCAPITALIZATION_NONE,autocorrect:!1});b.add(c);var d={publicAccess:!1,ids:[]},a=Ti.UI.createButton({title:"Select Readers",top:10+u,left:10+u,right:10+u,bottom:10+u,height:40+
u});a.addEventListener("click",function(){handleOpenWindow({target:"Select Users for ACL",access:d})});b.add(a);var e={publicAccess:!1,ids:[]},a=Ti.UI.createButton({title:"Select Writers",top:0,left:10+u,right:10+u,bottom:10+u,height:40+u});a.addEventListener("click",function(){handleOpenWindow({target:"Select Users for ACL",access:e})});b.add(a);a=Ti.UI.createButton({title:"Show",top:0,left:10+u,right:10+u,bottom:10+u,height:40+u});a.addEventListener("click",function(){0==c.value.length?c.focus():
Cloud.ACLs.show({name:c.value},function(a){a.success?(a=a.acls[0],d.publicAccess=a.public_read||!1,d.ids=a.readers||[],e.publicAccess=a.public_write||!1,e.ids=a.writers||[],alert("Shown!")):error(a)})});b.add(a);a=Ti.UI.createButton({title:"Update",top:0,left:10+u,right:10+u,bottom:10+u,height:40+u});a.addEventListener("click",function(){Cloud.ACLs.update({name:c.value,reader_ids:d.ids.join(","),writer_ids:e.ids.join(","),public_read:d.publicAccess,public_write:e.publicAccess},function(a){a.success?
alert("Updated!"):error(a)})});b.add(a);a=Ti.UI.createButton({title:"Remove",top:0,left:10+u,right:10+u,bottom:10+u,height:40+u});a.addEventListener("click",function(){Cloud.ACLs.remove({name:c.value},function(a){a.success?alert("Removed!"):error(a)})});b.add(a);f.addEventListener("open",function(){c.focus()});f.open()};