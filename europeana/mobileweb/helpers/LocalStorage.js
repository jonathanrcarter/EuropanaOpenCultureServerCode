function LocalStorage(){}LocalStorage.prototype.setObject=function(a,b){try{Titanium.App.Properties.setString(a,JSON.stringify(b))}catch(c){}};LocalStorage.prototype.getObject=function(a){try{return JSON.parse(Titanium.App.Properties.getString(a))}catch(b){}return{}};LocalStorage.prototype.setString=function(a,b){try{Titanium.App.Properties.setString(a,b)}catch(c){}};LocalStorage.prototype.getString=function(a){try{return Titanium.App.Properties.getString(a)}catch(b){}return""};module.exports=LocalStorage;
module.exports.setObject=function(a,b){try{Titanium.App.Properties.setString(a,JSON.stringify(b))}catch(c){}};module.exports.getObject=function(a){try{return JSON.parse(Titanium.App.Properties.getString(a))}catch(b){}return{}};module.exports.setString=function(a,b){try{Titanium.App.Properties.setString(a,b)}catch(c){}};module.exports.getString=function(a){try{return Titanium.App.Properties.getString(a)}catch(b){}return""};