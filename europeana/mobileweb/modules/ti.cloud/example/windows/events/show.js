windowFunctions["Show Event"]=function(d){var b=createWindow(),f=addBackButton(b),c=Ti.UI.createScrollView({top:f+u,contentHeight:"auto",layout:"vertical"});b.add(c);var e=Ti.UI.createLabel({text:"Loading, please wait...",textAlign:"left",height:30+u,left:20+u,right:20+u});c.add(e);Cloud.Events.show({event_id:d.id},function(b){c.remove(e);var a=Ti.UI.createButton({title:"Update Event",top:10+u,left:10+u,right:10+u,bottom:10+u,height:40+u});a.addEventListener("click",function(){handleOpenWindow({target:"Update Event",
id:d.id})});c.add(a);a=Ti.UI.createButton({title:"Remove Event",top:10+u,left:10+u,right:10+u,bottom:10+u,height:40+u});a.addEventListener("click",function(){handleOpenWindow({target:"Remove Event",id:d.id})});c.add(a);a=Ti.UI.createButton({title:"Show Occurrences",top:10+u,left:10+u,right:10+u,bottom:10+u,height:40+u});a.addEventListener("click",function(){handleOpenWindow({target:"Show Event Occurrences",id:d.id})});c.add(a);b.success?enumerateProperties(c,b.events[0],20):error(b)});b.open()};