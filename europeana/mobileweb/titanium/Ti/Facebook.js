define(["Ti/_/Evented","Ti/_/lang"],function(o,p){function h(){FB.init({appId:f,status:!1,cookie:!0,oauth:!0,xfbml:!0});FB.getLoginStatus(function(a){i=!0;"connected"==a.status&&j(a)||k&&l()},!0)}function j(a){var c=a.authResponse;if(c)return b.loggedIn=!0,b.uid=c.userID,b.expirationDate=new Date((new Date).getTime()+1E3*c.expiresIn),b.accessToken=c.accessToken,c.expiresIn&&setTimeout(function(){b.logout()},1E3*c.expiresIn),b.fireEvent("login",{cancelled:!1,data:a,success:!0,uid:b.uid}),!0}function g(a,
c,e,q){result={source:b,success:!1};result[c]=e;!a||a.error?a&&(result.error=a.error):(result.success=!0,result.result=JSON.stringify(a));q(result)}function l(){FB.login(function(a){j(a)||b.fireEvent("login",{cancelled:!0,data:a,error:"user cancelled or an internal error occured.",success:!1,uid:a.id})},{scope:b.permissions.join()})}var i=!1,k=!1,f=null,d=document.createElement("div"),m=!1,b;b=p.setObject("Ti.Facebook",o,{authorize:function(){if(!f)throw Error("App ID not set. Facebook authorization cancelled.");
i?l():k=!0},createLoginButton:function(a){return new (require("Ti/Facebook/LoginButton"))(a)},dialog:function(a,c,e){b.loggedIn?(c.method=a,FB.ui(c,function(b){g(b,"action",a,e)})):e({success:!1,error:"not logged in",action:a,source:b})},logout:function(){b.loggedIn&&FB.logout(function(){b.loggedIn=!1;b.fireEvent("logout",{success:!0})})},request:function(a,c,e){b.loggedIn?(c.method=a,c.urls="facebook.com,developers.facebook.com",FB.api(c,function(b){g(b,"method",a,e)})):e({success:!1,error:"not logged in",
method:a,source:b})},requestWithGraphPath:function(a,c,e,d){b.loggedIn?FB.api(a,e,c,function(b){g(b,"path",a,d)}):d({success:!1,error:"not logged in",path:a,source:b})},constants:{BUTTON_STYLE_NORMAL:1,BUTTON_STYLE_WIDE:2},properties:{accessToken:void 0,appid:{set:function(a){f=a;m&&h();return a}},expirationDate:void 0,forceDialogAuth:!0,loggedIn:!1,permissions:void 0,uid:void 0}});d.id="fb-root";document.body.appendChild(d);if(!document.getElementById("facebook-jssdk")){var d=document.createElement("script"),
n=document.getElementsByTagName("head")[0];d.id="facebook-jssdk";d.async=!0;d.src="//connect.facebook.net/en_US/all.js";n.insertBefore(d,n.firstChild)}window.fbAsyncInit=function(){m=!0;f&&h()};return b});