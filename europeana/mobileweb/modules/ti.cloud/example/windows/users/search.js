windowFunctions["Search User"]=function(){function g(){b.value.length?(b.blur(),e.hide(),Cloud.Users.search({q:b.value},function(c){e.show();if(c.success)if(0==c.users.length)f.setData([{title:"No Results!"}]);else{for(var b=[],a=0,d=c.users.length;a<d;a++)b.push(Ti.UI.createTableViewRow({title:c.users[a].first_name+" "+c.users[a].last_name,id:c.users[a].id}));f.setData(b)}else error(c)})):b.focus()}var a=createWindow(),d=addBackButton(a),b=Ti.UI.createTextField({hintText:"Full Text Search",top:d+
10+u,left:10+u,right:10+u,height:40+u,borderStyle:Ti.UI.INPUT_BORDERSTYLE_ROUNDED});a.add(b);var e=Ti.UI.createButton({title:"Search",top:d+60+u,left:10+u,right:10+u,height:40+u});a.add(e);var f=Ti.UI.createTableView({backgroundColor:"#fff",top:d+110+u,bottom:0});f.addEventListener("click",function(a){a.row.id&&handleOpenWindow({target:"Show User",id:a.row.id})});a.add(f);e.addEventListener("click",g);b.addEventListener("return",g);a.addEventListener("open",function(){b.focus()});a.open()};