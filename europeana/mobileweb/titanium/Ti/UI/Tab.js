define("Ti/_/declare,Ti/UI/View,Ti/_/dom,Ti/Locale,Ti/UI,Ti/UI/MobileWeb".split(","),function(g,h,d,i,c,j){var d={post:function(){this._tabTitle.text=this._getTitle()}},f=c.FILL,e=c.SIZE;return g("Ti.UI.Tab",h,{constructor:function(a){var a=a&&a.window,b=c.createView({layout:c._LAYOUT_CONSTRAINING_VERTICAL,width:"100%",height:e}),k=this._tabNavigationGroup=j.createNavigationGroup({window:a,_tab:this});this._add(b);b._add(this._tabIcon=c.createImageView({height:e,width:e}));b._add(this._tabTitle=c.createLabel({width:"100%",
wordWrap:!0,textAlign:c.TEXT_ALIGNMENT_CENTER}));a&&require.on(this,"singletap",this,function(){var a=this._tabGroup;a&&(a.activeTab===this?k._reset():a.activeTab=this)})},_defaultWidth:f,_defaultHeight:f,open:function(a,b){this._tabNavigationGroup.open(a,b)},close:function(a,b){this._tabNavigationGroup.close(a,b)},_focus:function(){this.fireEvent("focus",this._tabGroup._getEventData());var a=this._tabNavigationGroup._getTopWindow();a&&(this._tabGroup&&this._tabGroup._opened&&!a._opened&&(a._opened=
1,a.fireEvent("open")),a._handleFocusEvent())},_blur:function(){var a=this._tabNavigationGroup._getTopWindow();a&&a._handleBlurEvent();this.fireEvent("blur",this._tabGroup._getEventData())},_getTitle:function(){return i._getString(this.titleid,this.title)},_setTabGroup:function(a){this._tabGroup=a;this._tabNavigationGroup.navBarAtTop=a.tabsAtTop;this._win&&(this._win.tabGroup=a)},_setNavBarAtTop:function(a){this._tabNavigationGroup.navBarAtTop=a},properties:{active:{get:function(){return this._tabGroup&&
this._tabGroup.activeTab===this},post:function(a){var b=this._tabGroup,c=this._tabNavigationGroup,d=b._focused&&b._opened;a?(c.navBarAtTop=b.tabsAtBottom,c._updateNavBar(),b._addTabContents(c),d&&this._focus()):(b._removeTabContents(c),d&&this._blur())}},icon:{set:function(a){return this._tabIcon.image=a}},title:d,titleid:d}})});