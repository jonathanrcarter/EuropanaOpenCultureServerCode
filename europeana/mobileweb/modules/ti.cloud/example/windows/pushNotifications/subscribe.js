windowFunctions.Subscribe=function(){function f(){for(var a=0;a<b.length;a++){if(!b[a].value.length){b[a].focus();return}b[a].blur()}d.hide();Cloud.PushNotifications.subscribe({channel:c.value,device_token:pushDeviceToken,type:"iPhone OS"===Ti.Platform.name?"ios":Ti.Platform.name},function(a){a.success?(c.value="",alert("Subscribed!")):error(a);d.show()})}var e=createWindow(),a=addBackButton(e),a=Ti.UI.createScrollView({top:a+u,contentHeight:"auto",layout:"vertical"});e.add(a);if(pushDeviceToken){var c=
Ti.UI.createTextField({hintText:"Channel",top:10+u,left:10+u,right:10+u,height:40+u,borderStyle:Ti.UI.INPUT_BORDERSTYLE_ROUNDED,autocapitalization:Ti.UI.TEXT_AUTOCAPITALIZATION_NONE,autocorrect:!1});a.add(c);var d=Ti.UI.createButton({title:"Subscribe",top:10+u,left:10+u,right:10+u,bottom:10+u,height:40+u});a.add(d);var b=[c];d.addEventListener("click",f);for(a=0;a<b.length;a++)b[a].addEventListener("return",f);e.addEventListener("open",function(){c.focus()})}else a.add(Ti.UI.createLabel({text:"Please visit Push Notifications > Settings to enable push!",
textAlign:"center",color:"#000",height:"auto"}));e.open()};