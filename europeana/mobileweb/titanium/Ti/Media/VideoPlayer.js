define("Ti/_/declare,Ti/_/dom,Ti/_/event,Ti/_/lang,Ti/Media,Ti/UI/View".split(","),function(o,i,p,q,d,j){function h(a){return k?!!g.mozFullScreen||!!g.webkitIsFullScreen:!!a}var g=document,e=require.on,l=require.config.vendorPrefixes.dom,m="requestFullScreen",n="exitFullScreen",k=function(){for(var a=0,c;a<l.length;a++)if(c=l[a].toLowerCase(),g[c+"CancelFullScreen"])return m=c+"RequestFullScreen",n=c+"ExitFullScreen",1;return!!g.cancelFullScreen}(),r={m4v:"video/mp4",mov:"video/quicktime",mp4:"video/mp4",
ogg:"video/ogg",ogv:"video/ogg",webm:"video/webm"};return o("Ti.Media.VideoPlayer",j,{_currentState:0,constructor:function(){this._handles=[]},properties:{autoplay:!1,currentPlaybackTime:{get:function(){return this._video?1E3*this._video.currentTime:0},set:function(a){this._video&&(this._video.currentTime=a/1E3|0);return a}},fullscreen:{value:h(),set:function(a){var c,b=this._video,a=!!a;if(k)try{a===h()&&(a=!a),b[a?m:n]()}catch(d){}else b.className=a?"fullscreen":"",a&&(c=e(window,"keydown",function(a){27===
a.keyCode&&(this.fullscreen=0,c())}));this.fireEvent("fullscreen",{entering:a});return a}},mediaControlStyle:{value:d.VIDEO_CONTROL_DEFAULT,set:function(a){this._video&&(this._video.controls=a===d.VIDEO_CONTROL_DEFAULT);return a}},repeatMode:d.VIDEO_REPEAT_MODE_NONE,scalingMode:{set:function(a){var c=this.domNode,b=d.VIDEO_SCALING_ASPECT_FIT,e={};e[d.VIDEO_SCALING_NONE]="TiScalingNone";e[b]="TiScalingAspectFit";c.className=c.className.replace(/(scaling\-[\w\-]+)/,"")+" "+(e[a]||e[a=b]);return a}},
url:{set:function(a){this.constants.playing=!1;this._currentState=0;this.properties.__values__.url=a;this._createVideo();return a}}},constants:{playbackState:d.VIDEO_PLAYBACK_STATE_STOPPED,playing:!1,initialPlaybackTime:0,endPlaybackTime:0,playableDuration:0,loadState:d.VIDEO_LOAD_STATE_UNKNOWN,duration:0},_set:function(a,c){var b={};b[a]=this.constants[a]=c;this.fireEvent("loadState"===a?a.toLowerCase():a,b)},_complete:function(a){a="ended"===a.type;this.constants.playing=!1;this._currentState=0;
this.fireEvent("complete",{reason:a?d.VIDEO_FINISH_REASON_PLAYBACK_ENDED:d.VIDEO_FINISH_REASON_USER_EXITED});a&&this.repeatMode===d.VIDEO_REPEAT_MODE_ONE&&setTimeout(q.hitch(this,function(){this._video.play()}),1)},_stalled:function(){this._set("loadState",d.VIDEO_LOAD_STATE_STALLED)},_fullscreenChange:function(){this.properties.__values__.fullscreen=!h(this.fullscreen)},_durationChange:function(){var a=1E3*this._video.duration,c=this.constants;Infinity!==a&&(this.duration||this.fireEvent("durationAvailable",
{duration:a}),c.duration=c.playableDuration=c.endPlaybackTime=a)},_paused:function(){var a=d.VIDEO_PLAYBACK_STATE_STOPPED;this.constants.playing=!1;3===this._currentState?(this._currentState=2,a=d.VIDEO_PLAYBACK_STATE_PAUSED):1===this._currentState&&(this._video.currentTime=0);this._set("playbackState",a)},_createVideo:function(a){var c,b=this._video,f=this.url;if(f){if(a&&b&&b.parentNode)return b;this.release();b=this._video=i.create("video",{tabindex:0});this.mediaControlStyle===d.VIDEO_CONTROL_DEFAULT&&
(b.controls=1);this.scalingMode=d.VIDEO_SCALING_ASPECT_FIT;this._handles=[e(b,"playing",this,function(){this._currentState=3;this.constants.playing=!0;this.fireEvent("playing",{url:b.currentSrc});this._set("playbackState",d.VIDEO_PLAYBACK_STATE_PLAYING)}),e(b,"pause",this,"_paused"),e(b,"canplay",this,function(){this._set("loadState",d.VIDEO_LOAD_STATE_PLAYABLE);0===this._currentState&&this.autoplay&&b.play()}),e(b,"canplaythrough",this,function(){this._set("loadState",d.VIDEO_LOAD_STATE_PLAYTHROUGH_OK);
this.fireEvent("preload")}),e(b,"loadeddata",this,function(){this.fireEvent("load")}),e(b,"loadedmetadata",this,"_durationChange"),e(b,"durationchange",this,"_durationChange"),e(b,"timeupdate",this,function(){this.constants.currentPlaybackTime=1E3*this._video.currentTime;1===this._currentState&&this.pause()}),e(b,"error",this,function(){var a="Unknown error";switch(b.error.code){case 1:a="Aborted";break;case 2:a="Decode error";break;case 3:a="Network error";break;case 4:a="Unsupported format"}this.constants.playing=
!1;this._set("loadState",d.VIDEO_LOAD_STATE_UNKNOWN);this.fireEvent("error",{message:a});this.fireEvent("complete",{reason:d.VIDEO_FINISH_REASON_PLAYBACK_ERROR})}),e(b,"abort",this,"_complete"),e(b,"ended",this,"_complete"),e(b,"stalled",this,"_stalled"),e(b,"waiting",this,"_stalled"),e(b,"mozfullscreenchange",this,"_fullscreenChange"),e(b,"webkitfullscreenchange",this,"_fullscreenChange")];this.domNode.appendChild(b);require.is(f,"Array")||(f=[f]);for(a=0;a<f.length;a++)c=f[a].match(/.+\.([^\/\.]+?)$/),
i.create("source",{src:f[a],type:c&&r[c[1]]},b);return b}},play:function(){3!==this._currentState&&this._createVideo(1).play()},pause:function(){3===this._currentState&&this._createVideo(1).pause()},destroy:function(){this.release();j.prototype.destroy.apply(this,arguments)},release:function(){var a=this._video,c=a&&a.parentNode;this._currentState=0;this.constants.playing=!1;c&&(p.off(this._handles),c.removeChild(a));this._video=null},stop:function(){var a=this._video;this._currentState=1;a&&(a.pause(),
a.currentTime=0)}})});