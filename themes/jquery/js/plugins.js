
/*! matchMedia() polyfill - Test a CSS media type/query in JS. Authors & copyright (c) 2012: Scott Jehl, Paul Irish, Nicholas Zakas. Dual MIT/BSD license */
/*! NOTE: If you're already including a window.matchMedia polyfill via Modernizr or otherwise, you don't need this part */
window.matchMedia=window.matchMedia||(function(e,f){var c,a=e.documentElement,b=a.firstElementChild||a.firstChild,d=e.createElement("body"),g=e.createElement("div");g.id="mq-test-1";g.style.cssText="position:absolute;top:-100em";d.style.background="none";d.appendChild(g);return function(h){g.innerHTML='&shy;<style media="'+h+'"> #mq-test-1 { width: 42px; }</style>';a.insertBefore(d,b);c=g.offsetWidth==42;a.removeChild(d);return{matches:c,media:h}}})(document);

/*! Respond.js v1.1.0: min/max-width media query polyfill. (c) Scott Jehl. MIT/GPLv2 Lic. j.mp/respondjs  */
(function(e){e.respond={};respond.update=function(){};respond.mediaQueriesSupported=e.matchMedia&&e.matchMedia("only all").matches;if(respond.mediaQueriesSupported){return}var w=e.document,s=w.documentElement,i=[],k=[],q=[],o={},h=30,f=w.getElementsByTagName("head")[0]||s,g=w.getElementsByTagName("base")[0],b=f.getElementsByTagName("link"),d=[],a=function(){var D=b,y=D.length,B=0,A,z,C,x;for(;B<y;B++){A=D[B],z=A.href,C=A.media,x=A.rel&&A.rel.toLowerCase()==="stylesheet";if(!!z&&x&&!o[z]){if(A.styleSheet&&A.styleSheet.rawCssText){m(A.styleSheet.rawCssText,z,C);o[z]=true}else{if((!/^([a-zA-Z:]*\/\/)/.test(z)&&!g)||z.replace(RegExp.$1,"").split("/")[0]===e.location.host){d.push({href:z,media:C})}}}}u()},u=function(){if(d.length){var x=d.shift();n(x.href,function(y){m(y,x.href,x.media);o[x.href]=true;u()})}},m=function(I,x,z){var G=I.match(/@media[^\{]+\{([^\{\}]*\{[^\}\{]*\})+/gi),J=G&&G.length||0,x=x.substring(0,x.lastIndexOf("/")),y=function(K){return K.replace(/(url\()['"]?([^\/\)'"][^:\)'"]+)['"]?(\))/g,"$1"+x+"$2$3")},A=!J&&z,D=0,C,E,F,B,H;if(x.length){x+="/"}if(A){J=1}for(;D<J;D++){C=0;if(A){E=z;k.push(y(I))}else{E=G[D].match(/@media *([^\{]+)\{([\S\s]+?)$/)&&RegExp.$1;k.push(RegExp.$2&&y(RegExp.$2))}B=E.split(",");H=B.length;for(;C<H;C++){F=B[C];i.push({media:F.split("(")[0].match(/(only\s+)?([a-zA-Z]+)\s?/)&&RegExp.$2||"all",rules:k.length-1,hasquery:F.indexOf("(")>-1,minw:F.match(/\(min\-width:[\s]*([\s]*[0-9\.]+)(px|em)[\s]*\)/)&&parseFloat(RegExp.$1)+(RegExp.$2||""),maxw:F.match(/\(max\-width:[\s]*([\s]*[0-9\.]+)(px|em)[\s]*\)/)&&parseFloat(RegExp.$1)+(RegExp.$2||"")})}}j()},l,r,v=function(){var z,A=w.createElement("div"),x=w.body,y=false;A.style.cssText="position:absolute;font-size:1em;width:1em";if(!x){x=y=w.createElement("body");x.style.background="none"}x.appendChild(A);s.insertBefore(x,s.firstChild);z=A.offsetWidth;if(y){s.removeChild(x)}else{x.removeChild(A)}z=p=parseFloat(z);return z},p,j=function(I){var x="clientWidth",B=s[x],H=w.compatMode==="CSS1Compat"&&B||w.body[x]||B,D={},G=b[b.length-1],z=(new Date()).getTime();if(I&&l&&z-l<h){clearTimeout(r);r=setTimeout(j,h);return}else{l=z}for(var E in i){var K=i[E],C=K.minw,J=K.maxw,A=C===null,L=J===null,y="em";if(!!C){C=parseFloat(C)*(C.indexOf(y)>-1?(p||v()):1)}if(!!J){J=parseFloat(J)*(J.indexOf(y)>-1?(p||v()):1)}if(!K.hasquery||(!A||!L)&&(A||H>=C)&&(L||H<=J)){if(!D[K.media]){D[K.media]=[]}D[K.media].push(k[K.rules])}}for(var E in q){if(q[E]&&q[E].parentNode===f){f.removeChild(q[E])}}for(var E in D){var M=w.createElement("style"),F=D[E].join("\n");M.type="text/css";M.media=E;f.insertBefore(M,G.nextSibling);if(M.styleSheet){M.styleSheet.cssText=F}else{M.appendChild(w.createTextNode(F))}q.push(M)}},n=function(x,z){var y=c();if(!y){return}y.open("GET",x,true);y.onreadystatechange=function(){if(y.readyState!=4||y.status!=200&&y.status!=304){return}z(y.responseText)};if(y.readyState==4){return}y.send(null)},c=(function(){var x=false;try{x=new XMLHttpRequest()}catch(y){x=new ActiveXObject("Microsoft.XMLHTTP")}return function(){return x}})();a();respond.update=a;function t(){j(true)}if(e.addEventListener){e.addEventListener("resize",t,false)}else{if(e.attachEvent){e.attachEvent("onresize",t)}}})(this);


/*
	ColorBox v1.3.23
	(c) 2013 Jack Moore - jacklmoore.com
	license: http://www.opensource.org/licenses/mit-license.php
*/
(function(e,t,i){function o(i,o,n){var h=t.createElement(i);return o&&(h.id=J+o),n&&(h.style.cssText=n),e(h)}function n(e){var t=b.length,i=(A+e)%t;return 0>i?t+i:i}function h(e,t){return Math.round((/%/.test(e)?("x"===t?T.width():T.height())/100:1)*parseInt(e,10))}function r(e){return _.photo||/\.(gif|png|jp(e|g|eg)|bmp|ico)((#|\?).*)?$/i.test(e)}function l(){var t,i=e.data(j,$);null==i?(_=e.extend({},X),console&&console.log&&console.log("Error: cboxElement missing settings object")):_=e.extend({},i);for(t in _)e.isFunction(_[t])&&"on"!==t.slice(0,2)&&(_[t]=_[t].call(j));_.rel=_.rel||j.rel||e(j).data("rel")||"nofollow",_.href=_.href||e(j).attr("href"),_.title=_.title||j.title,"string"==typeof _.href&&(_.href=e.trim(_.href))}function s(i,o){e(t).trigger(i),e("*",p).trigger(i),o&&o.call(j)}function a(){var e,t,i,o=J+"Slideshow_",n="click."+J;_.slideshow&&b[1]?(t=function(){I.html(_.slideshowStop).unbind(n).bind(Z,function(){(_.loop||b[A+1])&&(e=setTimeout(N.next,_.slideshowSpeed))}).bind(Y,function(){clearTimeout(e)}).one(n+" "+et,i),p.removeClass(o+"off").addClass(o+"on"),e=setTimeout(N.next,_.slideshowSpeed)},i=function(){clearTimeout(e),I.html(_.slideshowStart).unbind([Z,Y,et,n].join(" ")).one(n,function(){N.next(),t()}),p.removeClass(o+"on").addClass(o+"off")},_.slideshowAuto?t():i()):p.removeClass(o+"off "+o+"on")}function d(i){q||(j=i,l(),b=e(j),A=0,"nofollow"!==_.rel&&(b=e("."+U).filter(function(){var t,i=e.data(this,$);return i&&(t=e(this).data("rel")||i.rel||this.rel),t===_.rel}),A=b.index(j),-1===A&&(b=b.add(j),A=b.length-1)),P||(P=R=!0,p.show(),_.returnFocus&&(e(j).blur(),e(t).one(tt,function(){e(j).focus()})),f.css({opacity:+_.opacity,cursor:_.overlayClose?"pointer":"auto"}).show(),_.w=h(_.initialWidth,"x"),_.h=h(_.initialHeight,"y"),N.position(),nt&&T.bind("resize."+ht+" scroll."+ht,function(){f.css({width:T.width(),height:T.height(),top:T.scrollTop(),left:T.scrollLeft()})}).trigger("resize."+ht),s(V,_.onOpen),F.add(k).hide(),S.html(_.close).show()),N.load(!0))}function c(){!p&&t.body&&(Q=!1,T=e(i),p=o(rt).attr({id:$,"class":ot?J+(nt?"IE6":"IE"):""}).hide(),f=o(rt,"Overlay",nt?"position:absolute":"").hide(),H=o(rt,"LoadingOverlay").add(o(rt,"LoadingGraphic")),w=o(rt,"Wrapper"),m=o(rt,"Content").append(C=o(rt,"LoadedContent","width:0; height:0; overflow:hidden"),k=o(rt,"Title"),E=o(rt,"Current"),L=o(rt,"Next"),M=o(rt,"Previous"),I=o(rt,"Slideshow").bind(V,a),S=o(rt,"Close")),w.append(o(rt).append(o(rt,"TopLeft"),g=o(rt,"TopCenter"),o(rt,"TopRight")),o(rt,!1,"clear:left").append(x=o(rt,"MiddleLeft"),m,y=o(rt,"MiddleRight")),o(rt,!1,"clear:left").append(o(rt,"BottomLeft"),v=o(rt,"BottomCenter"),o(rt,"BottomRight"))).find("div div").css({"float":"left"}),W=o(rt,!1,"position:absolute; width:9999px; visibility:hidden; display:none"),F=L.add(M).add(E).add(I),e(t.body).append(f,p.append(w,W)))}function u(){return p?(Q||(Q=!0,K=g.height()+v.height()+m.outerHeight(!0)-m.height(),z=x.width()+y.width()+m.outerWidth(!0)-m.width(),D=C.outerHeight(!0),B=C.outerWidth(!0),L.click(function(){N.next()}),M.click(function(){N.prev()}),S.click(function(){N.close()}),f.click(function(){_.overlayClose&&N.close()}),e(t).bind("keydown."+J,function(e){var t=e.keyCode;P&&_.escKey&&27===t&&(e.preventDefault(),N.close()),P&&_.arrowKey&&b[1]&&(37===t?(e.preventDefault(),M.click()):39===t&&(e.preventDefault(),L.click()))}),e(t).delegate("."+U,"click",function(e){e.which>1||e.shiftKey||e.altKey||e.metaKey||(e.preventDefault(),d(this))})),!0):!1}var f,p,w,m,g,x,y,v,b,T,C,W,H,k,E,I,L,M,S,F,_,K,z,D,B,j,A,O,P,R,q,G,N,Q,X={transition:"elastic",speed:300,width:!1,initialWidth:"600",innerWidth:!1,maxWidth:!1,height:!1,initialHeight:"450",innerHeight:!1,maxHeight:!1,scalePhotos:!0,scrolling:!0,inline:!1,html:!1,iframe:!1,fastIframe:!0,photo:!1,href:!1,title:!1,rel:!1,opacity:.9,preloading:!0,current:"image {current} of {total}",previous:"previous",next:"next",close:"close",xhrError:"This content failed to load.",imgError:"This image failed to load.",open:!1,returnFocus:!0,reposition:!0,loop:!0,slideshow:!1,slideshowAuto:!0,slideshowSpeed:2500,slideshowStart:"start slideshow",slideshowStop:"stop slideshow",onOpen:!1,onLoad:!1,onComplete:!1,onCleanup:!1,onClosed:!1,overlayClose:!0,escKey:!0,arrowKey:!0,top:!1,bottom:!1,left:!1,right:!1,fixed:!1,data:void 0},$="colorbox",J="cbox",U=J+"Element",V=J+"_open",Y=J+"_load",Z=J+"_complete",et=J+"_cleanup",tt=J+"_closed",it=J+"_purge",ot=!e.support.leadingWhitespace,nt=ot&&!i.XMLHttpRequest,ht=J+"_IE6",rt="div";e.colorbox||(e(c),N=e.fn[$]=e[$]=function(t,i){var o=this;if(t=t||{},c(),u()){if(e.isFunction(o))o=e("<a/>"),t.open=!0;else if(!o[0])return o;i&&(t.onComplete=i),o.each(function(){e.data(this,$,e.extend({},e.data(this,$)||X,t))}).addClass(U),(e.isFunction(t.open)&&t.open.call(o)||t.open)&&d(o[0])}return o},N.position=function(e,t){function i(e){g[0].style.width=v[0].style.width=m[0].style.width=parseInt(e.style.width,10)-z+"px",m[0].style.height=x[0].style.height=y[0].style.height=parseInt(e.style.height,10)-K+"px"}var o,n,r,l=0,s=0,a=p.offset();T.unbind("resize."+J),p.css({top:-9e4,left:-9e4}),n=T.scrollTop(),r=T.scrollLeft(),_.fixed&&!nt?(a.top-=n,a.left-=r,p.css({position:"fixed"})):(l=n,s=r,p.css({position:"absolute"})),s+=_.right!==!1?Math.max(T.width()-_.w-B-z-h(_.right,"x"),0):_.left!==!1?h(_.left,"x"):Math.round(Math.max(T.width()-_.w-B-z,0)/2),l+=_.bottom!==!1?Math.max(T.height()-_.h-D-K-h(_.bottom,"y"),0):_.top!==!1?h(_.top,"y"):Math.round(Math.max(T.height()-_.h-D-K,0)/2),p.css({top:a.top,left:a.left}),e=p.width()===_.w+B&&p.height()===_.h+D?0:e||0,w[0].style.width=w[0].style.height="9999px",o={width:_.w+B+z,height:_.h+D+K,top:l,left:s},0===e&&p.css(o),p.dequeue().animate(o,{duration:e,complete:function(){i(this),R=!1,w[0].style.width=_.w+B+z+"px",w[0].style.height=_.h+D+K+"px",_.reposition&&setTimeout(function(){T.bind("resize."+J,N.position)},1),t&&t()},step:function(){i(this)}})},N.resize=function(e){P&&(e=e||{},e.width&&(_.w=h(e.width,"x")-B-z),e.innerWidth&&(_.w=h(e.innerWidth,"x")),C.css({width:_.w}),e.height&&(_.h=h(e.height,"y")-D-K),e.innerHeight&&(_.h=h(e.innerHeight,"y")),e.innerHeight||e.height||(C.css({height:"auto"}),_.h=C.height()),C.css({height:_.h}),N.position("none"===_.transition?0:_.speed))},N.prep=function(i){function h(){return _.w=_.w||C.width(),_.w=_.mw&&_.mw<_.w?_.mw:_.w,_.w}function l(){return _.h=_.h||C.height(),_.h=_.mh&&_.mh<_.h?_.mh:_.h,_.h}if(P){var a,d="none"===_.transition?0:_.speed;C.remove(),C=o(rt,"LoadedContent").append(i),C.hide().appendTo(W.show()).css({width:h(),overflow:_.scrolling?"auto":"hidden"}).css({height:l()}).prependTo(m),W.hide(),e(O).css({"float":"none"}),a=function(){function i(){ot&&p[0].style.removeAttribute("filter")}var h,l,a=b.length,c="frameBorder",u="allowTransparency";P&&(l=function(){clearTimeout(G),H.detach().hide(),s(Z,_.onComplete)},ot&&O&&C.fadeIn(100),k.html(_.title).add(C).show(),a>1?("string"==typeof _.current&&E.html(_.current.replace("{current}",A+1).replace("{total}",a)).show(),L[_.loop||a-1>A?"show":"hide"]().html(_.next),M[_.loop||A?"show":"hide"]().html(_.previous),_.slideshow&&I.show(),_.preloading&&e.each([n(-1),n(1)],function(){var t,i,o=b[this],n=e.data(o,$);n&&n.href?(t=n.href,e.isFunction(t)&&(t=t.call(o))):t=o.href,r(t)&&(i=new Image,i.src=t)})):F.hide(),_.iframe?(h=o("iframe")[0],c in h&&(h[c]=0),u in h&&(h[u]="true"),_.scrolling||(h.scrolling="no"),e(h).attr({src:_.href,name:(new Date).getTime(),"class":J+"Iframe",allowFullScreen:!0,webkitAllowFullScreen:!0,mozallowfullscreen:!0}).one("load",l).appendTo(C),e(t).one(it,function(){h.src="//about:blank"}),_.fastIframe&&e(h).trigger("load")):l(),"fade"===_.transition?p.fadeTo(d,1,i):i())},"fade"===_.transition?p.fadeTo(d,0,function(){N.position(0,a)}):N.position(d,a)}},N.load=function(i){var n,a,d,c=N.prep;R=!0,O=!1,j=b[A],i||l(),s(it),s(Y,_.onLoad),_.h=_.height?h(_.height,"y")-D-K:_.innerHeight&&h(_.innerHeight,"y"),_.w=_.width?h(_.width,"x")-B-z:_.innerWidth&&h(_.innerWidth,"x"),_.mw=_.w,_.mh=_.h,_.maxWidth&&(_.mw=h(_.maxWidth,"x")-B-z,_.mw=_.w&&_.w<_.mw?_.w:_.mw),_.maxHeight&&(_.mh=h(_.maxHeight,"y")-D-K,_.mh=_.h&&_.h<_.mh?_.h:_.mh),n=_.href,G=setTimeout(function(){H.show().appendTo(m)},100),_.inline?(d=o(rt).hide().insertBefore(e(n)[0]),e(t).one(it,function(){d.replaceWith(C.children())}),c(e(n))):_.iframe?c(" "):_.html?c(_.html):r(n)?(e(O=new Image).addClass(J+"Photo").bind("error",function(){_.title=!1,c(o(rt,"Error").html(_.imgError))}).load(function(){var e;O.onload=null,_.scalePhotos&&(a=function(){O.height-=O.height*e,O.width-=O.width*e},_.mw&&O.width>_.mw&&(e=(O.width-_.mw)/O.width,a()),_.mh&&O.height>_.mh&&(e=(O.height-_.mh)/O.height,a())),_.h&&(O.style.marginTop=Math.max(_.h-O.height,0)/2+"px"),b[1]&&(_.loop||b[A+1])&&(O.style.cursor="pointer",O.onclick=function(){N.next()}),ot&&(O.style.msInterpolationMode="bicubic"),setTimeout(function(){c(O)},1)}),setTimeout(function(){O.src=n},1)):n&&W.load(n,_.data,function(t,i){c("error"===i?o(rt,"Error").html(_.xhrError):e(this).contents())})},N.next=function(){!R&&b[1]&&(_.loop||b[A+1])&&(A=n(1),N.load())},N.prev=function(){!R&&b[1]&&(_.loop||A)&&(A=n(-1),N.load())},N.close=function(){P&&!q&&(q=!0,P=!1,s(et,_.onCleanup),T.unbind("."+J+" ."+ht),f.fadeTo(200,0),p.stop().fadeTo(300,0,function(){p.add(f).css({opacity:1,cursor:"auto"}).hide(),s(it),C.remove(),setTimeout(function(){q=!1,s(tt,_.onClosed)},1)}))},N.remove=function(){e([]).add(p).add(f).remove(),p=null,e("."+U).removeData($).removeClass(U),e(t).undelegate("."+U)},N.element=function(){return e(j)},N.settings=X)})(jQuery,document,window);


/*
 * jQuery FlexSlider v2.1
 * Copyright 2012 WooThemes
 * Contributing Author: Tyler Smith
 */
;(function(d){d.flexslider=function(i,k){var a=d(i),c=d.extend({},d.flexslider.defaults,k),e=c.namespace,p="ontouchstart"in window||window.DocumentTouch&&document instanceof DocumentTouch,t=p?"touchend":"click",l="vertical"===c.direction,m=c.reverse,h=0<c.itemWidth,r="fade"===c.animation,s=""!==c.asNavFor,f={};d.data(i,"flexslider",a);f={init:function(){a.animating=!1;a.currentSlide=c.startAt;a.animatingTo=a.currentSlide;a.atEnd=0===a.currentSlide||a.currentSlide===a.last;a.containerSelector=c.selector.substr(0,
 c.selector.search(" "));a.slides=d(c.selector,a);a.container=d(a.containerSelector,a);a.count=a.slides.length;a.syncExists=0<d(c.sync).length;"slide"===c.animation&&(c.animation="swing");a.prop=l?"top":"marginLeft";a.args={};a.manualPause=!1;var b=a,g;if(g=!c.video)if(g=!r)if(g=c.useCSS)a:{g=document.createElement("div");var n=["perspectiveProperty","WebkitPerspective","MozPerspective","OPerspective","msPerspective"],e;for(e in n)if(void 0!==g.style[n[e]]){a.pfx=n[e].replace("Perspective","").toLowerCase();
 a.prop="-"+a.pfx+"-transform";g=!0;break a}g=!1}b.transitions=g;""!==c.controlsContainer&&(a.controlsContainer=0<d(c.controlsContainer).length&&d(c.controlsContainer));""!==c.manualControls&&(a.manualControls=0<d(c.manualControls).length&&d(c.manualControls));c.randomize&&(a.slides.sort(function(){return Math.round(Math.random())-0.5}),a.container.empty().append(a.slides));a.doMath();s&&f.asNav.setup();a.setup("init");c.controlNav&&f.controlNav.setup();c.directionNav&&f.directionNav.setup();c.keyboard&&
 (1===d(a.containerSelector).length||c.multipleKeyboard)&&d(document).bind("keyup",function(b){b=b.keyCode;if(!a.animating&&(39===b||37===b))b=39===b?a.getTarget("next"):37===b?a.getTarget("prev"):!1,a.flexAnimate(b,c.pauseOnAction)});c.mousewheel&&a.bind("mousewheel",function(b,g){b.preventDefault();var d=0>g?a.getTarget("next"):a.getTarget("prev");a.flexAnimate(d,c.pauseOnAction)});c.pausePlay&&f.pausePlay.setup();c.slideshow&&(c.pauseOnHover&&a.hover(function(){!a.manualPlay&&!a.manualPause&&a.pause()},
 function(){!a.manualPause&&!a.manualPlay&&a.play()}),0<c.initDelay?setTimeout(a.play,c.initDelay):a.play());p&&c.touch&&f.touch();(!r||r&&c.smoothHeight)&&d(window).bind("resize focus",f.resize);setTimeout(function(){c.start(a)},200)},asNav:{setup:function(){a.asNav=!0;a.animatingTo=Math.floor(a.currentSlide/a.move);a.currentItem=a.currentSlide;a.slides.removeClass(e+"active-slide").eq(a.currentItem).addClass(e+"active-slide");a.slides.click(function(b){b.preventDefault();var b=d(this),g=b.index();
 !d(c.asNavFor).data("flexslider").animating&&!b.hasClass("active")&&(a.direction=a.currentItem<g?"next":"prev",a.flexAnimate(g,c.pauseOnAction,!1,!0,!0))})}},controlNav:{setup:function(){a.manualControls?f.controlNav.setupManual():f.controlNav.setupPaging()},setupPaging:function(){var b=1,g;a.controlNavScaffold=d('<ol class="'+e+"control-nav "+e+("thumbnails"===c.controlNav?"control-thumbs":"control-paging")+'"></ol>');if(1<a.pagingCount)for(var n=0;n<a.pagingCount;n++)g="thumbnails"===c.controlNav?
 '<img src="'+a.slides.eq(n).attr("data-thumb")+'"/>':"<a>"+b+"</a>",a.controlNavScaffold.append("<li>"+g+"</li>"),b++;a.controlsContainer?d(a.controlsContainer).append(a.controlNavScaffold):a.append(a.controlNavScaffold);f.controlNav.set();f.controlNav.active();a.controlNavScaffold.delegate("a, img",t,function(b){b.preventDefault();var b=d(this),g=a.controlNav.index(b);b.hasClass(e+"active")||(a.direction=g>a.currentSlide?"next":"prev",a.flexAnimate(g,c.pauseOnAction))});p&&a.controlNavScaffold.delegate("a",
 "click touchstart",function(a){a.preventDefault()})},setupManual:function(){a.controlNav=a.manualControls;f.controlNav.active();a.controlNav.live(t,function(b){b.preventDefault();var b=d(this),g=a.controlNav.index(b);b.hasClass(e+"active")||(g>a.currentSlide?a.direction="next":a.direction="prev",a.flexAnimate(g,c.pauseOnAction))});p&&a.controlNav.live("click touchstart",function(a){a.preventDefault()})},set:function(){a.controlNav=d("."+e+"control-nav li "+("thumbnails"===c.controlNav?"img":"a"),
 a.controlsContainer?a.controlsContainer:a)},active:function(){a.controlNav.removeClass(e+"active").eq(a.animatingTo).addClass(e+"active")},update:function(b,c){1<a.pagingCount&&"add"===b?a.controlNavScaffold.append(d("<li><a>"+a.count+"</a></li>")):1===a.pagingCount?a.controlNavScaffold.find("li").remove():a.controlNav.eq(c).closest("li").remove();f.controlNav.set();1<a.pagingCount&&a.pagingCount!==a.controlNav.length?a.update(c,b):f.controlNav.active()}},directionNav:{setup:function(){var b=d('<ul class="'+
 e+'direction-nav"><li><a class="'+e+'prev" href="#">'+c.prevText+'</a></li><li><a class="'+e+'next" href="#">'+c.nextText+"</a></li></ul>");a.controlsContainer?(d(a.controlsContainer).append(b),a.directionNav=d("."+e+"direction-nav li a",a.controlsContainer)):(a.append(b),a.directionNav=d("."+e+"direction-nav li a",a));f.directionNav.update();a.directionNav.bind(t,function(b){b.preventDefault();b=d(this).hasClass(e+"next")?a.getTarget("next"):a.getTarget("prev");a.flexAnimate(b,c.pauseOnAction)});
 p&&a.directionNav.bind("click touchstart",function(a){a.preventDefault()})},update:function(){var b=e+"disabled";1===a.pagingCount?a.directionNav.addClass(b):c.animationLoop?a.directionNav.removeClass(b):0===a.animatingTo?a.directionNav.removeClass(b).filter("."+e+"prev").addClass(b):a.animatingTo===a.last?a.directionNav.removeClass(b).filter("."+e+"next").addClass(b):a.directionNav.removeClass(b)}},pausePlay:{setup:function(){var b=d('<div class="'+e+'pauseplay"><a></a></div>');a.controlsContainer?
 (a.controlsContainer.append(b),a.pausePlay=d("."+e+"pauseplay a",a.controlsContainer)):(a.append(b),a.pausePlay=d("."+e+"pauseplay a",a));f.pausePlay.update(c.slideshow?e+"pause":e+"play");a.pausePlay.bind(t,function(b){b.preventDefault();d(this).hasClass(e+"pause")?(a.manualPause=!0,a.manualPlay=!1,a.pause()):(a.manualPause=!1,a.manualPlay=!0,a.play())});p&&a.pausePlay.bind("click touchstart",function(a){a.preventDefault()})},update:function(b){"play"===b?a.pausePlay.removeClass(e+"pause").addClass(e+
 "play").text(c.playText):a.pausePlay.removeClass(e+"play").addClass(e+"pause").text(c.pauseText)}},touch:function(){function b(b){j=l?d-b.touches[0].pageY:d-b.touches[0].pageX;p=l?Math.abs(j)<Math.abs(b.touches[0].pageX-e):Math.abs(j)<Math.abs(b.touches[0].pageY-e);if(!p||500<Number(new Date)-k)b.preventDefault(),!r&&a.transitions&&(c.animationLoop||(j/=0===a.currentSlide&&0>j||a.currentSlide===a.last&&0<j?Math.abs(j)/q+2:1),a.setProps(f+j,"setTouch"))}function g(){i.removeEventListener("touchmove",
 b,!1);if(a.animatingTo===a.currentSlide&&!p&&null!==j){var h=m?-j:j,l=0<h?a.getTarget("next"):a.getTarget("prev");a.canAdvance(l)&&(550>Number(new Date)-k&&50<Math.abs(h)||Math.abs(h)>q/2)?a.flexAnimate(l,c.pauseOnAction):r||a.flexAnimate(a.currentSlide,c.pauseOnAction,!0)}i.removeEventListener("touchend",g,!1);f=j=e=d=null}var d,e,f,q,j,k,p=!1;i.addEventListener("touchstart",function(j){a.animating?j.preventDefault():1===j.touches.length&&(a.pause(),q=l?a.h:a.w,k=Number(new Date),f=h&&m&&a.animatingTo===
 a.last?0:h&&m?a.limit-(a.itemW+c.itemMargin)*a.move*a.animatingTo:h&&a.currentSlide===a.last?a.limit:h?(a.itemW+c.itemMargin)*a.move*a.currentSlide:m?(a.last-a.currentSlide+a.cloneOffset)*q:(a.currentSlide+a.cloneOffset)*q,d=l?j.touches[0].pageY:j.touches[0].pageX,e=l?j.touches[0].pageX:j.touches[0].pageY,i.addEventListener("touchmove",b,!1),i.addEventListener("touchend",g,!1))},!1)},resize:function(){!a.animating&&a.is(":visible")&&(h||a.doMath(),r?f.smoothHeight():h?(a.slides.width(a.computedW),
 a.update(a.pagingCount),a.setProps()):l?(a.viewport.height(a.h),a.setProps(a.h,"setTotal")):(c.smoothHeight&&f.smoothHeight(),a.newSlides.width(a.computedW),a.setProps(a.computedW,"setTotal")))},smoothHeight:function(b){if(!l||r){var c=r?a:a.viewport;b?c.animate({height:a.slides.eq(a.animatingTo).height()},b):c.height(a.slides.eq(a.animatingTo).height())}},sync:function(b){var g=d(c.sync).data("flexslider"),e=a.animatingTo;switch(b){case "animate":g.flexAnimate(e,c.pauseOnAction,!1,!0);break;case "play":!g.playing&&
 !g.asNav&&g.play();break;case "pause":g.pause()}}};a.flexAnimate=function(b,g,n,i,k){s&&1===a.pagingCount&&(a.direction=a.currentItem<b?"next":"prev");if(!a.animating&&(a.canAdvance(b,k)||n)&&a.is(":visible")){if(s&&i)if(n=d(c.asNavFor).data("flexslider"),a.atEnd=0===b||b===a.count-1,n.flexAnimate(b,!0,!1,!0,k),a.direction=a.currentItem<b?"next":"prev",n.direction=a.direction,Math.ceil((b+1)/a.visible)-1!==a.currentSlide&&0!==b)a.currentItem=b,a.slides.removeClass(e+"active-slide").eq(b).addClass(e+
 "active-slide"),b=Math.floor(b/a.visible);else return a.currentItem=b,a.slides.removeClass(e+"active-slide").eq(b).addClass(e+"active-slide"),!1;a.animating=!0;a.animatingTo=b;c.before(a);g&&a.pause();a.syncExists&&!k&&f.sync("animate");c.controlNav&&f.controlNav.active();h||a.slides.removeClass(e+"active-slide").eq(b).addClass(e+"active-slide");a.atEnd=0===b||b===a.last;c.directionNav&&f.directionNav.update();b===a.last&&(c.end(a),c.animationLoop||a.pause());if(r)p?(a.slides.eq(a.currentSlide).css({opacity:0}),
 a.slides.eq(b).css({opacity:1}),a.animating=!1,a.currentSlide=a.animatingTo):(a.slides.eq(a.currentSlide).fadeOut(c.animationSpeed,c.easing),a.slides.eq(b).fadeIn(c.animationSpeed,c.easing,a.wrapup));else{var q=l?a.slides.filter(":first").height():a.computedW;h?(b=c.itemWidth>a.w?2*c.itemMargin:c.itemMargin,b=(a.itemW+b)*a.move*a.animatingTo,b=b>a.limit&&1!==a.visible?a.limit:b):b=0===a.currentSlide&&b===a.count-1&&c.animationLoop&&"next"!==a.direction?m?(a.count+a.cloneOffset)*q:0:a.currentSlide===
 a.last&&0===b&&c.animationLoop&&"prev"!==a.direction?m?0:(a.count+1)*q:m?(a.count-1-b+a.cloneOffset)*q:(b+a.cloneOffset)*q;a.setProps(b,"",c.animationSpeed);if(a.transitions){if(!c.animationLoop||!a.atEnd)a.animating=!1,a.currentSlide=a.animatingTo;a.container.unbind("webkitTransitionEnd transitionend");a.container.bind("webkitTransitionEnd transitionend",function(){a.wrapup(q)})}else a.container.animate(a.args,c.animationSpeed,c.easing,function(){a.wrapup(q)})}c.smoothHeight&&f.smoothHeight(c.animationSpeed)}};
 a.wrapup=function(b){!r&&!h&&(0===a.currentSlide&&a.animatingTo===a.last&&c.animationLoop?a.setProps(b,"jumpEnd"):a.currentSlide===a.last&&(0===a.animatingTo&&c.animationLoop)&&a.setProps(b,"jumpStart"));a.animating=!1;a.currentSlide=a.animatingTo;c.after(a)};a.animateSlides=function(){a.animating||a.flexAnimate(a.getTarget("next"))};a.pause=function(){clearInterval(a.animatedSlides);a.playing=!1;c.pausePlay&&f.pausePlay.update("play");a.syncExists&&f.sync("pause")};a.play=function(){a.animatedSlides=
 setInterval(a.animateSlides,c.slideshowSpeed);a.playing=!0;c.pausePlay&&f.pausePlay.update("pause");a.syncExists&&f.sync("play")};a.canAdvance=function(b,g){var d=s?a.pagingCount-1:a.last;return g?!0:s&&a.currentItem===a.count-1&&0===b&&"prev"===a.direction?!0:s&&0===a.currentItem&&b===a.pagingCount-1&&"next"!==a.direction?!1:b===a.currentSlide&&!s?!1:c.animationLoop?!0:a.atEnd&&0===a.currentSlide&&b===d&&"next"!==a.direction?!1:a.atEnd&&a.currentSlide===d&&0===b&&"next"===a.direction?!1:!0};a.getTarget=
 function(b){a.direction=b;return"next"===b?a.currentSlide===a.last?0:a.currentSlide+1:0===a.currentSlide?a.last:a.currentSlide-1};a.setProps=function(b,g,d){var e,f=b?b:(a.itemW+c.itemMargin)*a.move*a.animatingTo;e=-1*function(){if(h)return"setTouch"===g?b:m&&a.animatingTo===a.last?0:m?a.limit-(a.itemW+c.itemMargin)*a.move*a.animatingTo:a.animatingTo===a.last?a.limit:f;switch(g){case "setTotal":return m?(a.count-1-a.currentSlide+a.cloneOffset)*b:(a.currentSlide+a.cloneOffset)*b;case "setTouch":return b;
 case "jumpEnd":return m?b:a.count*b;case "jumpStart":return m?a.count*b:b;default:return b}}()+"px";a.transitions&&(e=l?"translate3d(0,"+e+",0)":"translate3d("+e+",0,0)",d=void 0!==d?d/1E3+"s":"0s",a.container.css("-"+a.pfx+"-transition-duration",d));a.args[a.prop]=e;(a.transitions||void 0===d)&&a.container.css(a.args)};a.setup=function(b){if(r)a.slides.css({width:"100%","float":"left",marginRight:"-100%",position:"relative"}),"init"===b&&(p?a.slides.css({opacity:0,display:"block",webkitTransition:"opacity "+
 c.animationSpeed/1E3+"s ease"}).eq(a.currentSlide).css({opacity:1}):a.slides.eq(a.currentSlide).fadeIn(c.animationSpeed,c.easing)),c.smoothHeight&&f.smoothHeight();else{var g,n;"init"===b&&(a.viewport=d('<div class="'+e+'viewport"></div>').css({overflow:"hidden",position:"relative"}).appendTo(a).append(a.container),a.cloneCount=0,a.cloneOffset=0,m&&(n=d.makeArray(a.slides).reverse(),a.slides=d(n),a.container.empty().append(a.slides)));c.animationLoop&&!h&&(a.cloneCount=2,a.cloneOffset=1,"init"!==
 b&&a.container.find(".clone").remove(),a.container.append(a.slides.first().clone().addClass("clone")).prepend(a.slides.last().clone().addClass("clone")));a.newSlides=d(c.selector,a);g=m?a.count-1-a.currentSlide+a.cloneOffset:a.currentSlide+a.cloneOffset;l&&!h?(a.container.height(200*(a.count+a.cloneCount)+"%").css("position","absolute").width("100%"),setTimeout(function(){a.newSlides.css({display:"block"});a.doMath();a.viewport.height(a.h);a.setProps(g*a.h,"init")},"init"===b?100:0)):(a.container.width(200*
 (a.count+a.cloneCount)+"%"),a.setProps(g*a.computedW,"init"),setTimeout(function(){a.doMath();a.newSlides.css({width:a.computedW,"float":"left",display:"block"});c.smoothHeight&&f.smoothHeight()},"init"===b?100:0))}h||a.slides.removeClass(e+"active-slide").eq(a.currentSlide).addClass(e+"active-slide")};a.doMath=function(){var b=a.slides.first(),d=c.itemMargin,e=c.minItems,f=c.maxItems;a.w=a.width();a.h=b.height();a.boxPadding=b.outerWidth()-b.width();h?(a.itemT=c.itemWidth+d,a.minW=e?e*a.itemT:a.w,
 a.maxW=f?f*a.itemT:a.w,a.itemW=a.minW>a.w?(a.w-d*e)/e:a.maxW<a.w?(a.w-d*f)/f:c.itemWidth>a.w?a.w:c.itemWidth,a.visible=Math.floor(a.w/(a.itemW+d)),a.move=0<c.move&&c.move<a.visible?c.move:a.visible,a.pagingCount=Math.ceil((a.count-a.visible)/a.move+1),a.last=a.pagingCount-1,a.limit=1===a.pagingCount?0:c.itemWidth>a.w?(a.itemW+2*d)*a.count-a.w-d:(a.itemW+d)*a.count-a.w-d):(a.itemW=a.w,a.pagingCount=a.count,a.last=a.count-1);a.computedW=a.itemW-a.boxPadding};a.update=function(b,d){a.doMath();h||(b<
 a.currentSlide?a.currentSlide+=1:b<=a.currentSlide&&0!==b&&(a.currentSlide-=1),a.animatingTo=a.currentSlide);if(c.controlNav&&!a.manualControls)if("add"===d&&!h||a.pagingCount>a.controlNav.length)f.controlNav.update("add");else if("remove"===d&&!h||a.pagingCount<a.controlNav.length)h&&a.currentSlide>a.last&&(a.currentSlide-=1,a.animatingTo-=1),f.controlNav.update("remove",a.last);c.directionNav&&f.directionNav.update()};a.addSlide=function(b,e){var f=d(b);a.count+=1;a.last=a.count-1;l&&m?void 0!==
 e?a.slides.eq(a.count-e).after(f):a.container.prepend(f):void 0!==e?a.slides.eq(e).before(f):a.container.append(f);a.update(e,"add");a.slides=d(c.selector+":not(.clone)",a);a.setup();c.added(a)};a.removeSlide=function(b){var e=isNaN(b)?a.slides.index(d(b)):b;a.count-=1;a.last=a.count-1;isNaN(b)?d(b,a.slides).remove():l&&m?a.slides.eq(a.last).remove():a.slides.eq(b).remove();a.doMath();a.update(e,"remove");a.slides=d(c.selector+":not(.clone)",a);a.setup();c.removed(a)};f.init()};d.flexslider.defaults=
 {namespace:"flex-",selector:".slides > li",animation:"fade",easing:"swing",direction:"horizontal",reverse:!1,animationLoop:!0,smoothHeight:!1,startAt:0,slideshow:!0,slideshowSpeed:7E3,animationSpeed:600,initDelay:0,randomize:!1,pauseOnAction:!0,pauseOnHover:!1,useCSS:!0,touch:!0,video:!1,controlNav:!0,directionNav:!0,prevText:"Previous",nextText:"Next",keyboard:!0,multipleKeyboard:!1,mousewheel:!1,pausePlay:!1,pauseText:"Pause",playText:"Play",controlsContainer:"",manualControls:"",sync:"",asNavFor:"",
 itemWidth:0,itemMargin:0,minItems:0,maxItems:0,move:0,start:function(){},before:function(){},after:function(){},end:function(){},added:function(){},removed:function(){}};d.fn.flexslider=function(i){void 0===i&&(i={});if("object"===typeof i)return this.each(function(){var a=d(this),c=a.find(i.selector?i.selector:".slides > li");1===c.length?(c.fadeIn(400),i.start&&i.start(a)):void 0===a.data("flexslider")&&new d.flexslider(this,i)});var k=d(this).data("flexslider");switch(i){case "play":k.play();break;
 case "pause":k.pause();break;case "next":k.flexAnimate(k.getTarget("next"),!0);break;case "prev":case "previous":k.flexAnimate(k.getTarget("prev"),!0);break;default:"number"===typeof i&&k.flexAnimate(i,!0)}}})(jQuery);



/* Stripe
 * https://checkout.stripe.com/v2/checkout.js
 */
(function() {

  this.StripeCheckout = {};

  StripeCheckout.load = function() {
    var _ref;
    return (_ref = StripeCheckout.App).load.apply(_ref, arguments);
  };

  StripeCheckout.open = function() {
    var _ref;
    return (_ref = StripeCheckout.App).open.apply(_ref, arguments);
  };

  StripeCheckout.setHost = function() {
    var _ref;
    return (_ref = StripeCheckout.App).setHost.apply(_ref, arguments);
  };

  this.StripeButton = StripeCheckout;

}).call(this);
// Create a JSON object only if one does not already exist. We create the
// methods in a closure to avoid creating global variables.

var JSON;
if (!JSON) {
    JSON = {};
}

(function () {
    'use strict';

    function f(n) {
        // Format integers to have at least two digits.
        return n < 10 ? '0' + n : n;
    }

    if (typeof Date.prototype.toJSON !== 'function') {

        Date.prototype.toJSON = function (key) {

            return isFinite(this.valueOf())
                ? this.getUTCFullYear()     + '-' +
                    f(this.getUTCMonth() + 1) + '-' +
                    f(this.getUTCDate())      + 'T' +
                    f(this.getUTCHours())     + ':' +
                    f(this.getUTCMinutes())   + ':' +
                    f(this.getUTCSeconds())   + 'Z'
                : null;
        };

        String.prototype.toJSON      =
            Number.prototype.toJSON  =
            Boolean.prototype.toJSON = function (key) {
                return this.valueOf();
            };
    }

    var cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        escapable = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        gap,
        indent,
        meta = {    // table of character substitutions
            '\b': '\\b',
            '\t': '\\t',
            '\n': '\\n',
            '\f': '\\f',
            '\r': '\\r',
            '"' : '\\"',
            '\\': '\\\\'
        },
        rep;


    function quote(string) {

// If the string contains no control characters, no quote characters, and no
// backslash characters, then we can safely slap some quotes around it.
// Otherwise we must also replace the offending characters with safe escape
// sequences.

        escapable.lastIndex = 0;
        return escapable.test(string) ? '"' + string.replace(escapable, function (a) {
            var c = meta[a];
            return typeof c === 'string'
                ? c
                : '\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
        }) + '"' : '"' + string + '"';
    }


    function str(key, holder) {

// Produce a string from holder[key].

        var i,          // The loop counter.
            k,          // The member key.
            v,          // The member value.
            length,
            mind = gap,
            partial,
            value = holder[key];

// If the value has a toJSON method, call it to obtain a replacement value.

        if (value && typeof value === 'object' &&
                typeof value.toJSON === 'function') {
            value = value.toJSON(key);
        }

// If we were called with a replacer function, then call the replacer to
// obtain a replacement value.

        if (typeof rep === 'function') {
            value = rep.call(holder, key, value);
        }

// What happens next depends on the value's type.

        switch (typeof value) {
        case 'string':
            return quote(value);

        case 'number':

// JSON numbers must be finite. Encode non-finite numbers as null.

            return isFinite(value) ? String(value) : 'null';

        case 'boolean':
        case 'null':

// If the value is a boolean or null, convert it to a string. Note:
// typeof null does not produce 'null'. The case is included here in
// the remote chance that this gets fixed someday.

            return String(value);

// If the type is 'object', we might be dealing with an object or an array or
// null.

        case 'object':

// Due to a specification blunder in ECMAScript, typeof null is 'object',
// so watch out for that case.

            if (!value) {
                return 'null';
            }

// Make an array to hold the partial results of stringifying this object value.

            gap += indent;
            partial = [];

// Is the value an array?

            if (Object.prototype.toString.apply(value) === '[object Array]') {

// The value is an array. Stringify every element. Use null as a placeholder
// for non-JSON values.

                length = value.length;
                for (i = 0; i < length; i += 1) {
                    partial[i] = str(i, value) || 'null';
                }

// Join all of the elements together, separated with commas, and wrap them in
// brackets.

                v = partial.length === 0
                    ? '[]'
                    : gap
                    ? '[\n' + gap + partial.join(',\n' + gap) + '\n' + mind + ']'
                    : '[' + partial.join(',') + ']';
                gap = mind;
                return v;
            }

// If the replacer is an array, use it to select the members to be stringified.

            if (rep && typeof rep === 'object') {
                length = rep.length;
                for (i = 0; i < length; i += 1) {
                    if (typeof rep[i] === 'string') {
                        k = rep[i];
                        v = str(k, value);
                        if (v) {
                            partial.push(quote(k) + (gap ? ': ' : ':') + v);
                        }
                    }
                }
            } else {

// Otherwise, iterate through all of the keys in the object.

                for (k in value) {
                    if (Object.prototype.hasOwnProperty.call(value, k)) {
                        v = str(k, value);
                        if (v) {
                            partial.push(quote(k) + (gap ? ': ' : ':') + v);
                        }
                    }
                }
            }

// Join all of the member texts together, separated with commas,
// and wrap them in braces.

            v = partial.length === 0
                ? '{}'
                : gap
                ? '{\n' + gap + partial.join(',\n' + gap) + '\n' + mind + '}'
                : '{' + partial.join(',') + '}';
            gap = mind;
            return v;
        }
    }

// If the JSON object does not yet have a stringify method, give it one.

    if (typeof JSON.stringify !== 'function') {
        JSON.stringify = function (value, replacer, space) {

// The stringify method takes a value and an optional replacer, and an optional
// space parameter, and returns a JSON text. The replacer can be a function
// that can replace values, or an array of strings that will select the keys.
// A default replacer method can be provided. Use of the space parameter can
// produce text that is more easily readable.

            var i;
            gap = '';
            indent = '';

// If the space parameter is a number, make an indent string containing that
// many spaces.

            if (typeof space === 'number') {
                for (i = 0; i < space; i += 1) {
                    indent += ' ';
                }

// If the space parameter is a string, it will be used as the indent string.

            } else if (typeof space === 'string') {
                indent = space;
            }

// If there is a replacer, it must be a function or an array.
// Otherwise, throw an error.

            rep = replacer;
            if (replacer && typeof replacer !== 'function' &&
                    (typeof replacer !== 'object' ||
                    typeof replacer.length !== 'number')) {
                throw new Error('JSON.stringify');
            }

// Make a fake root object containing our value under the key of ''.
// Return the result of stringifying the value.

            return str('', {'': value});
        };
    }


// If the JSON object does not yet have a parse method, give it one.

    if (typeof JSON.parse !== 'function') {
        JSON.parse = function (text, reviver) {

// The parse method takes a text and an optional reviver function, and returns
// a JavaScript value if the text is a valid JSON text.

            var j;

            function walk(holder, key) {

// The walk method is used to recursively walk the resulting structure so
// that modifications can be made.

                var k, v, value = holder[key];
                if (value && typeof value === 'object') {
                    for (k in value) {
                        if (Object.prototype.hasOwnProperty.call(value, k)) {
                            v = walk(value, k);
                            if (v !== undefined) {
                                value[k] = v;
                            } else {
                                delete value[k];
                            }
                        }
                    }
                }
                return reviver.call(holder, key, value);
            }


// Parsing happens in four stages. In the first stage, we replace certain
// Unicode characters with escape sequences. JavaScript handles many characters
// incorrectly, either silently deleting them, or treating them as line endings.

            text = String(text);
            cx.lastIndex = 0;
            if (cx.test(text)) {
                text = text.replace(cx, function (a) {
                    return '\\u' +
                        ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
                });
            }

// In the second stage, we run the text against regular expressions that look
// for non-JSON patterns. We are especially concerned with '()' and 'new'
// because they can cause invocation, and '=' because it can cause mutation.
// But just to be safe, we want to reject all unexpected forms.

// We split the second stage into 4 regexp operations in order to work around
// crippling inefficiencies in IE's and Safari's regexp engines. First we
// replace the JSON backslash pairs with '@' (a non-JSON character). Second, we
// replace all simple value tokens with ']' characters. Third, we delete all
// open brackets that follow a colon or comma or that begin the text. Finally,
// we look to see that the remaining characters are only whitespace or ']' or
// ',' or ':' or '{' or '}'. If that is so, then the text is safe for eval.

            if (/^[\],:{}\s]*$/
                    .test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, '@')
                        .replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']')
                        .replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

// In the third stage we use the eval function to compile the text into a
// JavaScript structure. The '{' operator is subject to a syntactic ambiguity
// in JavaScript: it can begin a block or an object literal. We wrap the text
// in parens to eliminate the ambiguity.

                j = eval('(' + text + ')');

// In the optional fourth stage, we recursively walk the new structure, passing
// each name/value pair to a reviver function for possible transformation.

                return typeof reviver === 'function'
                    ? walk({'': j}, '')
                    : j;
            }

// If the text is not JSON parseable, then a SyntaxError is thrown.

            throw new SyntaxError('JSON.parse');
        };
    }
}());
(function() {
  var cacheBust, interval, lastHash, postMessage, re, receiveMessage;

  cacheBust = 1;

  interval = null;

  lastHash = null;

  re = /^#?\d+&/;

  postMessage = function(target, targetURL, message, targetOrigin) {
    if (targetOrigin == null) {
      targetOrigin = targetURL;
    }
    message = (+(new Date)) + (cacheBust++) + '&' + message;
    return target.location = targetURL.replace(/#.*$/, '') + '#' + message;
  };

  receiveMessage = function(callback, delay) {
    if (delay == null) {
      delay = 100;
    }
    interval && clearInterval(interval);
    return interval = setInterval(function() {
      var hash;
      hash = window.location.hash;
      if (hash !== lastHash && re.test(hash)) {
        window.location.hash = '';
        lastHash = hash;
        return callback({
          data: hash.replace(re, '')
        });
      }
    }, delay);
  };

  StripeCheckout.message = {
    postMessage: postMessage,
    receiveMessage: receiveMessage
  };

}).call(this);
(function() {
  var $, $$, addClass, append, attr, bind, css, escape, except, hasAttr, hasClass, host, insertAfter, insertBefore, parents, remove, resolve, text, trigger, unbind,
    __indexOf = [].indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; },
    __slice = [].slice;

  $ = function(sel) {
    return document.querySelectorAll(sel);
  };

  $$ = function(cls) {
    var el, reg, _i, _len, _ref, _results;
    if (typeof document.getElementsByClassName === 'function') {
      return document.getElementsByClassName(cls);
    } else if (typeof document.querySelectorAll === 'function') {
      return document.querySelectorAll("." + cls);
    } else {
      reg = new RegExp("(^|\\s)" + cls + "(\\s|$)");
      _ref = document.getElementsByTagName('*');
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        el = _ref[_i];
        if (reg.test(el.className)) {
          _results.push(el);
        }
      }
      return _results;
    }
  };

  attr = function(element, attr, value) {
    if (value != null) {
      return element.setAttribute(attr, value);
    } else {
      return element.getAttribute(attr);
    }
  };

  hasAttr = function(element, attr) {
    var node;
    if (typeof element.hasAttribute === 'function') {
      return element.hasAttribute(attr);
    } else {
      node = element.getAttributeNode(attr);
      return !!(node && (node.specified || node.nodeValue));
    }
  };

  bind = function(element, name, callback) {
    if (element.addEventListener) {
      return element.addEventListener(name, callback, false);
    } else {
      return element.attachEvent("on" + name, callback);
    }
  };

  unbind = function(element, name, callback) {
    if (element.removeEventListener) {
      return element.removeEventListener(name, callback, false);
    } else {
      return element.detachEvent("on" + name, callback);
    }
  };

  trigger = function(element, name, data, bubble) {
    if (data == null) {
      data = {};
    }
    if (bubble == null) {
      bubble = true;
    }
    if (window.jQuery) {
      return jQuery(element).trigger(name, data);
    }
  };

  addClass = function(element, name) {
    return element.className += ' ' + name;
  };

  hasClass = function(element, name) {
    return __indexOf.call(element.className.split(' '), name) >= 0;
  };

  css = function(element, css) {
    return element.style.cssText += ';' + css;
  };

  insertBefore = function(element, child) {
    return element.parentNode.insertBefore(child, element);
  };

  insertAfter = function(element, child) {
    return element.parentNode.insertBefore(child, element.nextSibling);
  };

  append = function(element, child) {
    return element.appendChild(child);
  };

  remove = function(element) {
    var _ref;
    return (_ref = element.parentNode) != null ? _ref.removeChild(element) : void 0;
  };

  parents = function(node) {
    var ancestors;
    ancestors = [];
    while ((node = node.parentNode) && node !== document && __indexOf.call(ancestors, node) < 0) {
      ancestors.push(node);
    }
    return ancestors;
  };

  host = function(url) {
    var parent, parser;
    parent = document.createElement('div');
    parent.innerHTML = "<a href=\"" + (escape(url)) + "\">x</a>";
    parser = parent.firstChild;
    return "" + parser.protocol + "//" + parser.host;
  };

  resolve = function(url) {
    var parser;
    parser = document.createElement('a');
    parser.href = url;
    return "" + parser.href;
  };

  escape = function(value) {
    return value && ('' + value).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  };

  text = function(element, value) {
    if ('innerText' in element) {
      element.innerText = value;
    } else {
      element.textContent = value;
    }
    return value;
  };

  except = function() {
    var k, keys, object, result, v;
    object = arguments[0], keys = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
    result = {};
    for (k in object) {
      v = object[k];
      if (__indexOf.call(keys, k) < 0) {
        result[k] = v;
      }
    }
    return result;
  };

  StripeCheckout.Utils = {
    $: $,
    $$: $$,
    attr: attr,
    hasAttr: hasAttr,
    bind: bind,
    unbind: unbind,
    trigger: trigger,
    addClass: addClass,
    hasClass: hasClass,
    css: css,
    insertBefore: insertBefore,
    insertAfter: insertAfter,
    append: append,
    remove: remove,
    parents: parents,
    host: host,
    resolve: resolve,
    escape: escape,
    text: text,
    except: except
  };

}).call(this);
(function() {
  var host,
    __slice = [].slice,
    __indexOf = [].indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; };

  host = StripeCheckout.Utils.host;

  StripeCheckout.RPC = {
    whitelist: ['frameReady', 'frameCallback'],
    getTarget: function() {
      throw new Error('override getTarget');
    },
    getHost: function() {
      throw new Error('override getHost');
    },
    rpcID: 0,
    invoke: function() {
      var args, frame, id, message, method;
      frame = arguments[0], method = arguments[1], args = 3 <= arguments.length ? __slice.call(arguments, 2) : [];
      id = ++this.rpcID;
      if (typeof args[args.length - 1] === 'function') {
        this.callbacks || (this.callbacks = {});
        this.callbacks[id] = args.pop();
      }
      message = JSON.stringify({
        method: method,
        args: args,
        id: id
      });
      return frame.postMessage(message, this.getHost());
    },
    invokeTarget: function() {
      var args;
      args = 1 <= arguments.length ? __slice.call(arguments, 0) : [];
      return this.invoke.apply(this, [this.getTarget()].concat(__slice.call(args)));
    },
    message: function(e) {
      var data, result, _name, _ref;
      if (host(e.origin) !== host(this.getHost())) {
        return;
      }
      if (!e.source || e.source !== this.getTarget()) {
        return;
      }
      data = JSON.parse(e.data);
      if (_ref = data.method, __indexOf.call(this.whitelist, _ref) < 0) {
        throw new Error("Method not allowed: " + data.method);
      }
      result = typeof this[_name = data.method] === "function" ? this[_name].apply(this, __slice.call(data.args).concat([e])) : void 0;
      if (data.method !== 'frameCallback') {
        return this.invoke(e.source, 'frameCallback', data.id, result);
      }
    },
    ready: function(fn) {
      var callbacks, cb, _i, _len, _results;
      this.readyQueue || (this.readyQueue = []);
      this.readyStatus || (this.readyStatus = false);
      if (typeof fn === 'function') {
        if (this.readyStatus) {
          return fn();
        } else {
          return this.readyQueue.push(fn);
        }
      } else {
        this.readyStatus = true;
        callbacks = this.readyQueue.slice(0);
        _results = [];
        for (_i = 0, _len = callbacks.length; _i < _len; _i++) {
          cb = callbacks[_i];
          _results.push(cb());
        }
        return _results;
      }
    },
    frameCallback: function(id, result) {
      var _base;
      if (!this.callbacks) {
        return;
      }
      if (typeof (_base = this.callbacks)[id] === "function") {
        _base[id](result);
      }
      delete this.callbacks[id];
      return true;
    },
    frameReady: function() {
      this.ready();
      return true;
    }
  };

}).call(this);
(function() {
  var $, $$, append, bind, css, host, remove, resolve, _ref,
    __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  _ref = StripeCheckout.Utils, $ = _ref.$, $$ = _ref.$$, bind = _ref.bind, css = _ref.css, append = _ref.append, remove = _ref.remove, host = _ref.host, resolve = _ref.resolve;

  StripeCheckout.App = (function() {

    App.load = function(options) {
      return App.instance || (App.instance = new App(options));
    };

    App.open = function(options) {
      App.instance || (App.instance = new App);
      App.instance.open(options);
      return App.instance;
    };

    App.setHost = function(host) {
      return App.prototype.defaults.host = host;
    };

    App.prototype.defaults = {
      path: '/',
      fallbackPath: '/fallback.html',
      host: 'https://checkout.stripe.com',
      address: false,
      amount: null,
      name: null,
      description: null,
      image: null,
      label: null,
      panelLabel: null,
      notrack: false
    };

    function App(options) {
      var _base;
      if (options == null) {
        options = {};
      }
      this.close = __bind(this.close, this);

      this.open = __bind(this.open, this);

      this.setOptions(options);
      if (StripeCheckout.App.Mobile.isEnabled()) {
        this.view = new StripeCheckout.App.Mobile(this.options);
      } else if (StripeCheckout.App.Fallback.isEnabled()) {
        this.view = new StripeCheckout.App.Fallback(this.options);
      } else {
        this.view = new StripeCheckout.App.Overlay(this.options);
      }
      if (typeof (_base = this.view).render === "function") {
        _base.render();
      }
    }

    App.prototype.open = function(options) {
      var _base;
      if (options == null) {
        options = {};
      }
      this.setOptions(options);
      return typeof (_base = this.view).open === "function" ? _base.open() : void 0;
    };

    App.prototype.close = function() {
      return this.view.close();
    };

    App.prototype.setOptions = function(options) {
      var key, value, _base, _ref1, _ref2, _ref3;
      if (options == null) {
        options = {};
      }
      this.options || (this.options = {});
      _ref1 = this.defaults;
      for (key in _ref1) {
        value = _ref1[key];
        if ((_ref2 = options[key]) == null) {
          options[key] = value;
        }
      }
      for (key in options) {
        value = options[key];
        this.options[key] = value;
      }
      if (this.options.image) {
        this.options.image = resolve(this.options.image);
      }
      if ((_ref3 = (_base = this.options).body) == null) {
        _base.body = document.body;
      }
      this.options.url = document.URL;
      return this.options.referrer = document.referrer;
    };

    return App;

  }).call(this);

}).call(this);
(function() {
  var except,
    __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  except = StripeCheckout.Utils.except;

  StripeCheckout.App.Fallback = (function() {

    Fallback.isEnabled = function() {
      return !('postMessage' in window);
    };

    function Fallback(options) {
      this.options = options != null ? options : {};
      this.setToken = __bind(this.setToken, this);

    }

    Fallback.prototype.open = function() {
      var message, options, url,
        _this = this;
      url = this.options.host + this.options.fallbackPath;
      options = except(this.options, 'body', 'script', 'document', 'token');
      message = JSON.stringify(options);
      this.frame = window.open(url, '_blank', 'width=400,height=400,location=yes,resizable=yes,scrollbars=yes');
      StripeCheckout.message.postMessage(this.frame, url, message);
      return StripeCheckout.message.receiveMessage(function(e) {
        return _this.setToken(JSON.parse(e.data));
      });
    };

    Fallback.prototype.close = function() {
      return this.frame.close();
    };

    Fallback.prototype.setToken = function(token) {
      var _base;
      if (typeof (_base = this.options).token === "function") {
        _base.token(token);
      }
      this.close();
      return true;
    };

    return Fallback;

  })();

}).call(this);
(function() {
  var $, $$, append, bind, css, except, host, remove, resolve, _ref,
    __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
    __slice = [].slice;

  _ref = StripeCheckout.Utils, $ = _ref.$, $$ = _ref.$$, bind = _ref.bind, css = _ref.css, append = _ref.append, remove = _ref.remove, host = _ref.host, resolve = _ref.resolve, except = _ref.except;

  StripeCheckout.App.Mobile = (function() {

    Mobile.include = function(module) {
      var key, value, _results;
      _results = [];
      for (key in module) {
        value = module[key];
        _results.push(this.prototype[key] = value);
      }
      return _results;
    };

    Mobile.include(StripeCheckout.RPC);

    Mobile.isEnabled = function() {
      var ua;
      ua = navigator.userAgent;
      if (/CriOS/.test(ua)) {
        return false;
      }
      return (screen.width <= 720) || (screen.height <= 720) || /Android/i.test(ua) || /iPhone/i.test(ua) || /iPad/i.test(ua);
    };

    function Mobile(options) {
      var _this = this;
      this.options = options != null ? options : {};
      this.setToken = __bind(this.setToken, this);

      bind(window, 'message', function() {
        var args;
        args = 1 <= arguments.length ? __slice.call(arguments, 0) : [];
        return _this.message.apply(_this, args);
      });
    }

    Mobile.prototype.open = function() {
      var _base, _ref1,
        _this = this;
      if ((_ref1 = this.frame) != null) {
        if (typeof _ref1.close === "function") {
          _ref1.close();
        }
      }
      this.readyStatus = false;
      this.ready(function() {
        var options;
        options = except(_this.options, 'body', 'script', 'document', 'token');
        return _this.invokeTarget('render', 'mobile', options);
      });
      this.frame = window.open(this.options.host + this.options.path);
      if (!this.frame) {
        return alert('Please disable your popup blocker.');
      } else if (typeof this.frame.postMessage !== 'function') {
        alert('Sorry, your browser is not supported.');
        return this.frame.close();
      } else {
        return typeof (_base = this.options).opened === "function" ? _base.opened() : void 0;
      }
    };

    Mobile.prototype.close = function() {
      var _ref1;
      window.focus();
      return (_ref1 = this.frame) != null ? typeof _ref1.close === "function" ? _ref1.close() : void 0 : void 0;
    };

    Mobile.prototype.getTarget = function() {
      return this.frame;
    };

    Mobile.prototype.getHost = function() {
      return this.options.host;
    };

    Mobile.prototype.whitelist = ['frameReady', 'frameCallback', 'setToken'];

    Mobile.prototype.setToken = function(token) {
      var _base;
      if (typeof (_base = this.options).token === "function") {
        _base.token(token);
      }
      this.close();
      return true;
    };

    return Mobile;

  })();

}).call(this);
(function() {
  var $, $$, append, bind, css, except, host, remove, resolve, _ref,
    __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
    __slice = [].slice;

  _ref = StripeCheckout.Utils, $ = _ref.$, $$ = _ref.$$, bind = _ref.bind, css = _ref.css, append = _ref.append, remove = _ref.remove, host = _ref.host, resolve = _ref.resolve, except = _ref.except;

  StripeCheckout.App.Overlay = (function() {

    Overlay.include = function(module) {
      var key, value, _results;
      _results = [];
      for (key in module) {
        value = module[key];
        _results.push(this.prototype[key] = value);
      }
      return _results;
    };

    Overlay.include(StripeCheckout.RPC);

    Overlay.prototype.iframeCSS = 'background: transparent;\nborder: 0px none transparent;\noverflow: hidden;\nvisibility: hidden;\nmargin: 0;\npadding: 0;\n-webkit-tap-highlight-color: transparent;\n-webkit-touch-callout: none;';

    Overlay.prototype.css = 'position: fixed;\nleft: 0;\ntop: 0;\nwidth: 100%;\nheight: 100%;\nz-index: 9999;\ndisplay: none;';

    function Overlay(options) {
      var _this = this;
      this.options = options != null ? options : {};
      this.toggleTabIndex = __bind(this.toggleTabIndex, this);

      this.renderFrame = __bind(this.renderFrame, this);

      this.closed = __bind(this.closed, this);

      this.setToken = __bind(this.setToken, this);

      this.overlayClosed = __bind(this.overlayClosed, this);

      this.getHost = __bind(this.getHost, this);

      this.getTarget = __bind(this.getTarget, this);

      this.close = __bind(this.close, this);

      this.open = __bind(this.open, this);

      this.render = __bind(this.render, this);

      bind(window, 'message', function() {
        var args;
        args = 1 <= arguments.length ? __slice.call(arguments, 0) : [];
        return _this.message.apply(_this, args);
      });
    }

    Overlay.prototype.render = function() {
      if (this.frame) {
        remove(this.frame);
      }
      this.frame = this.renderFrame();
      this.frame.className = 'stripe-app';
      css(this.frame, this.css);
      if (this.options.body) {
        return append(document.body, this.frame);
      }
    };

    Overlay.prototype.open = function() {
      var _base,
        _this = this;
      this.ready(function() {
        var options;
        options = except(_this.options, 'body', 'script', 'document', 'token');
        return _this.invokeTarget('render', 'overlay', options);
      });
      this.frame.style.display = 'block';
      this.ready(function() {
        return _this.invokeTarget('overlayOpen');
      });
      this.toggleTabIndex(false);
      return typeof (_base = this.options).opened === "function" ? _base.opened() : void 0;
    };

    Overlay.prototype.close = function() {
      var _this = this;
      return this.ready(function() {
        return _this.invokeTarget('close');
      });
    };

    Overlay.prototype.getTarget = function() {
      var _ref1;
      return (_ref1 = this.frame) != null ? _ref1.contentWindow : void 0;
    };

    Overlay.prototype.getHost = function() {
      return this.options.host;
    };

    Overlay.prototype.whitelist = ['frameReady', 'frameCallback', 'overlayClosed', 'setToken'];

    Overlay.prototype.overlayClosed = function() {
      this.closed();
      return true;
    };

    Overlay.prototype.setToken = function(token) {
      var _base;
      if (typeof (_base = this.options).token === "function") {
        _base.token(token);
      }
      this.close();
      return true;
    };

    Overlay.prototype.closed = function() {
      var _base;
      this.frame.style.display = 'none';
      this.toggleTabIndex(true);
      return typeof (_base = this.options).closed === "function" ? _base.closed() : void 0;
    };

    Overlay.prototype.renderFrame = function() {
      var iframe,
        _this = this;
      iframe = document.createElement('iframe');
      iframe.setAttribute('frameBorder', '0');
      iframe.setAttribute('allowtransparency', 'true');
      iframe.style.cssText = this.iframeCSS;
      bind(iframe, 'load', function() {
        return iframe.style.visibility = 'visible';
      });
      iframe.src = this.options.host + this.options.path;
      return iframe;
    };

    Overlay.prototype.toggleTabIndex = function(enabled) {
      var element, elements, index, _i, _len, _results;
      elements = $('button, input, select, textarea');
      _results = [];
      for (_i = 0, _len = elements.length; _i < _len; _i++) {
        element = elements[_i];
        if (enabled) {
          index = element.getAttribute('data-tabindex');
          element.tabIndex = index;
          _results.push(element.removeAttribute('data-tabindex'));
        } else {
          index = element.tabIndex;
          element.setAttribute('data-tabindex', index);
          _results.push(element.setAttribute('tabindex', -1));
        }
      }
      return _results;
    };

    return Overlay;

  })();

}).call(this);
(function() {
  var App, append, attr, bind, hasAttr, host, insertAfter, parents, remove, text, trigger, unbind, _ref,
    __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  _ref = StripeCheckout.Utils, bind = _ref.bind, unbind = _ref.unbind, trigger = _ref.trigger, append = _ref.append, text = _ref.text, parents = _ref.parents, host = _ref.host, remove = _ref.remove, insertAfter = _ref.insertAfter, attr = _ref.attr, hasAttr = _ref.hasAttr;

  App = StripeCheckout.App;

  StripeCheckout.Button = (function() {

    Button.prototype.defaults = {
      label: 'Pay with Card',
      host: 'https://button.stripe.com',
      cssPath: '/assets/inner/button.css',
      tokenName: 'stripeToken'
    };

    function Button(options) {
      var _base;
      if (options == null) {
        options = {};
      }
      this.setOptions = __bind(this.setOptions, this);

      this.parentHead = __bind(this.parentHead, this);

      this.parentForm = __bind(this.parentForm, this);

      this.token = __bind(this.token, this);

      this.open = __bind(this.open, this);

      this.submit = __bind(this.submit, this);

      this.append = __bind(this.append, this);

      this.render = __bind(this.render, this);

      this.setOptions(options);
      (_base = this.options).token || (_base.token = this.token);
      this.$el = document.createElement('button');
      this.$el.setAttribute('type', 'submit');
      this.$el.className = 'stripe-button-el';
      bind(this.$el, 'click', this.submit);
      bind(this.$el, 'touchstart', function() {});
      this.render();
    }

    Button.prototype.render = function() {
      this.$el.innerHTML = '';
      this.$el.style.visibility = 'hidden';
      this.$span = document.createElement('span');
      text(this.$span, this.options.label);
      this.$style = document.createElement('link');
      this.$style.setAttribute('type', 'text/css');
      this.$style.setAttribute('rel', 'stylesheet');
      this.$style.setAttribute('href', this.options.host + this.options.cssPath);
      return append(this.$el, this.$span);
    };

    Button.prototype.append = function() {
      var head,
        _this = this;
      if (this.options.script) {
        insertAfter(this.options.script, this.$el);
      }
      if (!this.options.fallback && (head = this.parentHead())) {
        append(head, this.$style);
      }
      if (this.$form = this.parentForm()) {
        unbind(this.$form, 'submit', this.submit);
        bind(this.$form, 'submit', this.submit);
      }
      return setTimeout(function() {
        return _this.$el.style.visibility = 'visible';
      }, 1000);
    };

    Button.prototype.disable = function() {
      return attr(this.$el, 'disabled', true);
    };

    Button.prototype.enable = function() {
      return this.$el.removeAttribute('disabled');
    };

    Button.prototype.isDisabled = function() {
      return hasAttr(this.$el, 'disabled');
    };

    Button.prototype.submit = function(e) {
      if (typeof e.preventDefault === "function") {
        e.preventDefault();
      }
      if (!this.isDisabled()) {
        this.open();
      }
      return false;
    };

    Button.prototype.open = function(options) {
      if (options == null) {
        options = {};
      }
      this.setOptions(options);
      return App.open(this.options);
    };

    Button.prototype.token = function(value) {
      var $input;
      if (this.options.script) {
        trigger(this.options.script, 'token', value);
      }
      if (this.$form) {
        $input = this.renderInput(value.id);
        append(this.$form, $input);
        this.$form.submit();
      }
      return this.disable();
    };

    Button.prototype.renderInput = function(value) {
      var input;
      input = document.createElement('input');
      input.type = 'hidden';
      input.name = this.options.tokenName;
      input.value = value;
      return input;
    };

    Button.prototype.parentForm = function() {
      var el, elements, _i, _len, _ref1;
      elements = parents(this.$el);
      for (_i = 0, _len = elements.length; _i < _len; _i++) {
        el = elements[_i];
        if (((_ref1 = el.tagName) != null ? _ref1.toLowerCase() : void 0) === 'form') {
          return el;
        }
      }
      return null;
    };

    Button.prototype.parentHead = function() {
      var _ref1, _ref2;
      return ((_ref1 = this.options.document) != null ? _ref1.head : void 0) || ((_ref2 = this.options.document) != null ? _ref2.getElementsByTagName('head')[0] : void 0) || this.options.document.body;
    };

    Button.prototype.setOptions = function(options) {
      var elementOptions, key, value, _base, _ref1, _ref2;
      if (options == null) {
        options = {};
      }
      this.options || (this.options = {});
      if (options.script) {
        elementOptions = this.elementOptions(options.script);
        for (key in elementOptions) {
          value = elementOptions[key];
          this.options[key] = value;
        }
      }
      for (key in options) {
        value = options[key];
        this.options[key] = value;
      }
      _ref1 = this.defaults;
      for (key in _ref1) {
        value = _ref1[key];
        if ((_ref2 = (_base = this.options)[key]) == null) {
          _base[key] = value;
        }
      }
      return this.options.fallback = this.isFallback();
    };

    Button.prototype.elementOptions = function(el) {
      return {
        key: attr(el, 'data-key'),
        host: host(el.src),
        amount: attr(el, 'data-amount'),
        name: attr(el, 'data-name'),
        description: attr(el, 'data-description'),
        image: attr(el, 'data-image'),
        label: attr(el, 'data-label'),
        panelLabel: attr(el, 'data-panel-label'),
        address: hasAttr(el, 'data-address'),
        notrack: hasAttr(el, 'data-notrack'),
        document: el.ownerDocument,
        body: el.ownerDocument.body
      };
    };

    Button.prototype.isFallback = function() {
      return !('postMessage' in window);
    };

    return Button;

  })();

}).call(this);
(function() {
  var $$, addClass, bind, hasClass, _ref;

  _ref = StripeCheckout.Utils, $$ = _ref.$$, hasClass = _ref.hasClass, addClass = _ref.addClass, bind = _ref.bind;

  bind(window, 'load', function() {
    return StripeCheckout.load();
  });

  (function() {
    var button, el, element;
    element = $$('stripe-button');
    element = (function() {
      var _i, _len, _results;
      _results = [];
      for (_i = 0, _len = element.length; _i < _len; _i++) {
        el = element[_i];
        if (!hasClass(el, 'active')) {
          _results.push(el);
        }
      }
      return _results;
    })();
    element = element[element.length - 1];
    if (!element) {
      return;
    }
    addClass(element, 'active');
    button = new StripeCheckout.Button({
      script: element
    });
    button.render();
    button.append();
    return StripeCheckout.setHost(button.options.host);
  })();

}).call(this);
