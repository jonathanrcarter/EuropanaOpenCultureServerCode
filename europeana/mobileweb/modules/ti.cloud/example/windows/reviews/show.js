windowFunctions["Show Review"]=function(b){var a=createWindow(),f=addBackButton(a),c=Ti.UI.createScrollView({top:f+u,contentHeight:"auto",layout:"vertical"});a.add(c);var e=Ti.UI.createLabel({text:"Loading, please wait...",textAlign:"left",height:30+u,left:20+u,right:20+u});c.add(e);Cloud.Reviews.show({user_id:b.user_id,review_id:b.review_id},function(a){c.remove(e);var d=Ti.UI.createButton({title:"Update Review",top:10+u,left:10+u,right:10+u,bottom:10+u,height:40+u});d.addEventListener("click",function(){handleOpenWindow({target:"Update Review",
user_id:b.user_id,review_id:b.review_id})});c.add(d);d=Ti.UI.createButton({title:"Remove Review",top:10+u,left:10+u,right:10+u,bottom:10+u,height:40+u});d.addEventListener("click",function(){handleOpenWindow({target:"Remove Review",user_id:b.user_id,review_id:b.review_id})});c.add(d);a.success?enumerateProperties(c,a.reviews[0],20):error(a)});a.open()};