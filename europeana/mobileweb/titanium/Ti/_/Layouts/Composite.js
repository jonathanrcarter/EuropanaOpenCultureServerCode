define(["Ti/_/Layouts/Base","Ti/_/declare","Ti/UI","Ti/_/lang"],function(I,J,m,K){var l=K.isDef,E=Math.round;return J("Ti._.Layouts.Composite",I,{_doLayout:function(c,e,h,H,i){for(var g={width:0,height:0},n=c._children,b,f=0,j,a,d,k,t,r,u,x,y,v,w,l=[],C=[],z=n.length,G=this._measureNode,f=0;f<z;f++)b=c._children[f],!b._alive||!b.domNode?this.handleInvalidState(b,c):b._markedForLayout&&((b._preLayout&&b._preLayout(e,h,H,i)||b._needsMeasuring)&&G(b,b,b._layoutCoefficients,this),j=b._layoutCoefficients,
a=j.width,u=j.minWidth,d=j.height,x=j.minHeight,k=j.sandboxWidth,t=j.sandboxHeight,r=j.left,j=j.top,a=a.x1*e+a.x2,void 0!==u.x1&&(a=Math.max(a,u.x1*e+u.x2)),d=d.x1*h+d.x2,void 0!==x.x1&&(d=Math.max(d,x.x1*h+x.x2)),y=b._getContentSize?b._getContentSize(a,d):b._layout._doLayout(b,isNaN(a)?e:a-b._borderLeftWidth-b._borderRightWidth,isNaN(d)?h:d-b._borderTopWidth-b._borderBottomWidth,isNaN(a),isNaN(d)),isNaN(a)&&(a=y.width+b._borderLeftWidth+b._borderRightWidth,void 0!==u.x1&&(a=Math.max(a,u.x1*e+u.x2))),
isNaN(d)&&(d=y.height+b._borderTopWidth+b._borderBottomWidth,void 0!==x.x1&&(d=Math.max(d,x.x1*h+x.x2))),H&&0!==r.x1?l.push(b):v=r.x1*e+r.x2*a+r.x3,i&&0!==j.x1?C.push(b):w=j.x1*h+j.x2*d+j.x3,b._measuredSandboxWidth=k=k.x1*h+k.x2+a+(isNaN(v)?0:v),b._measuredSandboxHeight=t=t.x1*h+t.x2+d+(isNaN(w)?0:w),k>g.width&&(g.width=k),t>g.height&&(g.height=t),b._measuredWidth=a,b._measuredHeight=d,b._measuredLeft=v,b._measuredTop=w);z=l.length;for(f=0;f<z;f++)b=l[f],r=b._layoutCoefficients.left,k=b._layoutCoefficients.sandboxWidth,
b._measuredLeft=v=r.x1*g.width+r.x2*a+r.x3,b._measuredSandboxWidth=k.x1*h+k.x2+b._measuredWidth+v,k=b._measuredSandboxWidth,k>g.width&&(g.width=k);z=C.length;for(f=0;f<z;f++)b=C[f],j=b._layoutCoefficients.top,t=b._layoutCoefficients.sandboxHeight,b._measuredTop=w=j.x1*g.height+j.x2*d+j.x3,b._measuredSandboxHeight=t.x1*h+t.x2+b._measuredHeight+w,t=b._measuredSandboxHeight,t>g.height&&(g.height=t);z=n.length;for(f=0;f<z;f++)b=n[f],b._markedForLayout&&(m._elementLayoutCount++,c=b.domNode.style,c.zIndex=
b.zIndex,c.left=E(b._measuredLeft)+"px",c.top=E(b._measuredTop)+"px",c.width=E(b._measuredWidth-b._borderLeftWidth-b._borderRightWidth)+"px",c.height=E(b._measuredHeight-b._borderTopWidth-b._borderBottomWidth)+"px",b._markedForLayout=!1,b.fireEvent("postlayout"));return this._computedSize=g},_getWidth:function(c,e){!l(e)&&2>l(c.left)+l(c.center&&c.center.x)+l(c.right)&&(e=c._defaultWidth);return e===m.INHERIT?c._parent._parent?c._parent._parent._layout._getWidth(c._parent,c._parent.width)===m.SIZE?
m.SIZE:m.FILL:m.FILL:e},_getHeight:function(c,e){!l(e)&&2>l(c.top)+l(c.center&&c.center.y)+l(c.bottom)&&(e=c._defaultHeight);return e===m.INHERIT?c._parent._parent?c._parent._parent._layout._getHeight(c._parent,c._parent.height)===m.SIZE?m.SIZE:m.FILL:m.FILL:e},_isDependentOnParent:function(c){c=c._layoutCoefficients;return!isNaN(c.width.x1)&&0!==c.width.x1||!isNaN(c.height.x1)&&0!==c.height.x1||0!==c.left.x1||0!==c.top.x1},_doAnimationLayout:function(c,e){var h=c._parent._measuredWidth,m=c._parent._measuredHeight,
i=e.width.x1*h+e.width.x2,g=e.height.x1*m+e.height.x2;return{width:i,height:g,left:e.left.x1*h+e.left.x2*i+e.left.x3,top:e.top.x1*m+e.top.x2*g+e.top.x3}},_measureNode:function(c,e,h,l){c._needsMeasuring=!1;for(var i=l.getValueType,g=l.computeValue,n=l._getWidth(c,e.width),b=i(n),f=g(n,b),j=e._minWidth,n=i(j),j=g(j,n),c=l._getHeight(c,e.height),a=i(c),d=g(c,a),k=e._minHeight,c=i(k),t=g(k,c),r=e.left,k=i(r),r=g(r,k),u=e.center&&e.center.x,x=i(u),u=g(u,x),y=e.right,v=i(y),y=g(y,v),w=e.top,E=i(w),w=g(w,
E),C=e.center&&e.center.y,z=i(C),C=g(C,z),e=e.bottom,i=i(e),g=g(e,i),e=h.sandboxWidth,G=h.sandboxHeight,b=[[b,f,k,r,x,u,v,y],[a,d,E,w,z,C,i,g]],s,F,B,o,D,A,p,q,f=0;2>f;f++)a=b[f],s=a[0],F=a[1],B=a[2],o=a[3],D=a[4],A=a[5],p=a[6],q=a[7],a=d=0,s===m.SIZE?a=d=NaN:s===m.FILL?(a=1,"%"===B?a-=o:"#"===B?d=-o:"%"===p?a-=q:"#"===p&&(d=-q)):"%"===s?a=F:"#"===s?d=F:"%"===B?"%"===D?a=2*(A-o):"#"===D?(a=-2*o,d=2*A):"%"===p?a=1-o-q:"#"===p&&(a=1-o,d=-q):"#"===B?"%"===D?(a=2*A,d=-2*o):"#"===D?d=2*(A-o):"%"===p?(a=
1-q,d=-o):"#"===p&&(a=1,d=-q-o):"%"===D?"%"===p?a=2*(q-A):"#"===p&&(a=-2*A,d=2*q):"#"===D&&("%"===p?(a=2*q,d=-2*A):"#"===p&&(d=2*(q-A))),h[s=0===f?"width":"height"].x1=a,h[s].x2=d;b={minWidth:[n,j,k,r,x,u,v,y],minHeight:[c,t,E,w,z,C,i,g]};for(f in b)a=b[f],s=a[0],F=a[1],B=a[2],o=a[3],p=a[6],q=a[7],a=d=n=0,s===m.SIZE?a=d=NaN:s===m.FILL?(a=1,"%"===B?a-=o:"#"===B?d=-o:"%"===p?a-=q:"#"===p&&(d=-q)):"%"===s?a=F:"#"===s?d=F:a=d=n=void 0,h[f].x1=a,h[f].x2=d,h[f].x3=n;b=[[k,r,x,u,v,y],[E,w,z,C,i,g]];for(f=
0;2>f;f++){a=b[f];B=a[0];o=a[1];D=a[2];A=a[3];p=a[4];q=a[5];a=d=n=0;if("%"===B)a=o;else if("#"===B)n=o;else if("%"===D)a=A,d=-0.5;else if("#"===D)d=-0.5,n=A;else if("%"===p)a=1-q,d=-1;else if("#"===p)a=1,d=-1,n=-q;else switch("left"===f?l._defaultHorizontalAlignment:l._defaultVerticalAlignment){case "center":a=0.5;d=-0.5;break;case "end":a=1,d=-1}h[s=0===f?"left":"top"].x1=a;h[s].x2=d;h[s].x3=n}e.x1="%"===v?y:0;e.x2="#"===v?y:0;G.x1="%"===i?g:0;G.x2="#"===i?g:0},_defaultHorizontalAlignment:"center",
_defaultVerticalAlignment:"center"})});