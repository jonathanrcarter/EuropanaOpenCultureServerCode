windowFunctions["Query Checkin"]=function(){var b=createWindow(),e=addBackButton(b),c=Ti.UI.createTableView({backgroundColor:"#fff",top:e+u,bottom:0,data:[{title:"Loading, please wait..."}]});c.addEventListener("click",function(a){a.row.id&&handleOpenWindow({target:"Show Checkin",id:a.row.id})});b.add(c);b.addEventListener("open",function(){Cloud.Checkins.query(function(a){if(a.success)if(0==a.checkins.length)c.setData([{title:"No Results!"}]);else{for(var b=[],d=0,e=a.checkins.length;d<e;d++)b.push(Ti.UI.createTableViewRow({title:a.checkins[d].place.name,
id:a.checkins[d].id}));c.setData(b)}else error(a)})});b.open()};