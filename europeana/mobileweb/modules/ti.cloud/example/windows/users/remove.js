windowFunctions["Remove Current User"]=function(){var a=createWindow(),d=addBackButton(a),b=Ti.UI.createButton({title:"Are you sure?",top:d+10+u,left:10+u,right:10+u,bottom:10+u,height:40+u});a.add(b);var c=Ti.UI.createLabel({text:"",textAlign:"center",left:20+u,right:20+u});a.add(c);b.addEventListener("click",function(){b.hide();c.text="Removing, please wait...";Cloud.Users.remove(function(a){c.text=a.success?"Removed!":""+(a.error&&a.message)||a})});a.open()};