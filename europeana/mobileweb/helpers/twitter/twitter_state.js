exports.data={username:"",displayname:"",loggedin:!1,connected:!1};exports.loaded=!1;exports.set=function(a,b,c,d){this.data.username=a;this.data.displayname=b;this.data.loggedin=c;this.data.connected=d};exports.get=function(){this.populate();return this.data};exports.username=function(){this.populate();return this.data.username};exports.displayname=function(){this.populate();return this.data.displayname};exports.loggedin=function(){this.populate();return this.data.loggedin};
exports.connected=function(){this.populate();return this.data.connected};exports.save=function(){require("/helpers/LocalStorage").setObject("twitter_state",this.data)};exports.load=function(){var a=require("/helpers/LocalStorage").getObject("twitter_state");null!=a&&a!={}&&(this.data=a)};exports.populate=function(){!1==this.loaded&&this.load()};