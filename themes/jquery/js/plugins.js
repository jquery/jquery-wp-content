
/*******************************************************************************/
/*	Application Base */
/*******************************************************************************/

var App = {};
App.url = "http://jquery.com/";
App.cache = {};

App.ajax = function(service, data, success, failure){
	$.ajax({
		type: "post",
	    url: App.url+"ajax/"+service, 
	    data: data,
	    dataType: "json",
		success: function (data) {
			App.publish("ajax_request_succes");
			success(data);
		},
		error: function (request, status, error) {
	    	App.publish("ajax_request_succes");
	    	failure(request, status, error);
	    }
	});
},
App.publish = function(topic, args){
    App.cache[topic] && $.each(App.cache[topic], function(){
        this.apply($, args || []);
    });
};
App.subscribe = function(topic, callback){
    if(!App.cache[topic]){
        App.cache[topic] = [];
    }
    App.cache[topic].push(callback);
    return [topic, callback];
};
App.unsubscribe = function(handle){
    var t = handle[0];
    App.cache[t] && $.each(App.cache[t], function(idx){
        if(this == handle[1]){
            App.cache[t].splice(idx, 1);
        }
    });
};

jQuery(function($){
	App.publish("init");
});

jQuery(document).unload(function($){
	App.publish("destroy");
});

/*******************************************************************************/
/*  You know how we do */
/*******************************************************************************/
if ( window.addEventListener ) {
  var kkeys = [], konami = "38,38,40,40,37,39,37,39,66,65";
  window.addEventListener("keydown", function(e){
          kkeys.push( e.keyCode );
          if ( kkeys.toString().indexOf( konami ) >= 0 )
                  window.location = "http://ejohn.org/apps/hero/";
  }, true);
}

/*******************************************************************************/
/*  Doug Neiner's Tooltip plugin */
/*******************************************************************************/

(function ( $ ) {
  $.fn.projectTooltip = function (tooltips) {
    return this.each(function () {
      $( this ).data('tooltip', 
        $( "#tooltip-template" )
          .tmpl( tooltips[ this.id ] )
          .css( 'visibility', 'hidden' )
          .appendTo( this )
          .hide()
          .css( 'visibility', 'visible' )
          .mouseenter( function () {
            $.doTimeout('tthide');
          })
      );
    });
  };
  
  $.fn.projectTooltip.options = {
    animate_in: 150,
    animate_out: 150,
    end_top: 25,
    start_top: 15
  };
  
  $.fn.projectTooltip.showing = false;
  
  $.fn.tooltipIn = function () {
    $.fn.projectTooltip.showing = true;
    return this.css({ top: $.fn.projectTooltip.options.start_top })
      .fadeIn( $.fn.projectTooltip.options.animate_in )
      .animate(
        { top: $.fn.projectTooltip.options.end_top },
        { duration: $.fn.projectTooltip.options.animate_in, queue: false }
      );
  };

  $.fn.tooltipOut = function () {
    $.fn.projectTooltip.showing = false;
    return this.css({ top: $.fn.projectTooltip.options.end_top })
      .fadeOut( $.fn.projectTooltip.options.animate_out )
      .animate(
        { top: $.fn.projectTooltip.options.end_top },
        { duration: $.fn.projectTooltip.options.animate_out, queue: false }
      );
  };
  
}(jQuery));

/*******************************************************************************/
/*
 * jQuery outside events - v1.1 - 3/16/2010
 * http://benalman.com/projects/jquery-outside-events-plugin/
 * 
 * Copyright (c) 2010 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 */
(function($,c,b){$.map("click dblclick mousemove mousedown mouseup mouseover mouseout change select submit keydown keypress keyup".split(" "),function(d){a(d)});a("focusin","focus"+b);a("focusout","blur"+b);$.addOutsideEvent=a;function a(g,e){e=e||g+b;var d=$(),h=g+"."+e+"-special-event";$.event.special[e]={setup:function(){d=d.add(this);if(d.length===1){$(c).bind(h,f)}},teardown:function(){d=d.not(this);if(d.length===0){$(c).unbind(h)}},add:function(i){var j=i.handler;i.handler=function(l,k){l.target=k;j.apply(this,arguments)}}};function f(i){$(d).each(function(){var j=$(this);if(this!==i.target&&!j.has(i.target).length){j.triggerHandler(e,[i.target])}})}}})(jQuery,document,"outside");


/*******************************************************************************/
/* 
 * respond.js - A small and fast polyfill for min/max-width CSS3 Media Queries
 * Copyright 2011, Scott Jehl, scottjehl.com
 * Dual licensed under the MIT or GPL Version 2 licenses. 
 * Usage: Check out the readme file or github.com/scottjehl/respond
 */
/*******************************************************************************/
(function(d,g){d.respond={};respond.update=function(){};respond.mediaQueriesSupported=g;if(g){return}var s=d.document,q=s.documentElement,h=[],j=[],o=[],n={},f=30,e=s.getElementsByTagName("head")[0]||q,b=e.getElementsByTagName("link"),a=function(){var y=s.styleSheets,u=y.length;for(var x=0;x<u;x++){var w=y[x],v=w.href;if(!!v&&!n[v]){if(!/^([a-zA-Z]+?:(\/\/)?(www\.)?)/.test(v)||v.replace(RegExp.$1,"").split("/")[0]===d.location.host){var t=v;m(v,function(z){l(z,t);n[t]=true})}else{n[v]=true}}}},l=function(B,t){var z=B.match(/@media ([^\{]+)\{([\S\s]+?)(?=\}\/\*\/mediaquery\*\/)/gmi),C=z&&z.length||0,t=t.substring(0,t.lastIndexOf("/"));if(t.length){t+="/"}for(var w=0;w<C;w++){var x=z[w].match(/@media ([^\{]+)\{([\S\s]+?)$/)&&RegExp.$1,u=x.split(","),A=u.length;j.push(RegExp.$2&&RegExp.$2.replace(/(url\()['"]?([^\/\)'"][^:\)'"]+)['"]?(\))/g,"$1"+t+"$2$3"));for(var v=0;v<A;v++){var y=u[v];h.push({media:y.match(/(only\s+)?([a-zA-Z]+)(\sand)?/)&&RegExp.$2,rules:j.length-1,minw:y.match(/\(min\-width:\s?(\s?[0-9]+)px\s?\)/)&&parseFloat(RegExp.$1),maxw:y.match(/\(max\-width:\s?(\s?[0-9]+)px\s?\)/)&&parseFloat(RegExp.$1)})}}i()},k,p,i=function(C){var t="clientWidth",v=q[t],B=s.compatMode==="CSS1Compat"&&v||s.body[t]||v,x={},A=s.createDocumentFragment(),z=b[b.length-1],u=(new Date()).getTime();if(C&&k&&u-k<f){clearTimeout(p);p=setTimeout(i,f);return}else{k=u}for(var w in h){var D=h[w];if(!D.minw&&!D.maxw||(!D.minw||D.minw&&B>=D.minw)&&(!D.maxw||D.maxw&&B<=D.maxw)){if(!x[D.media]){x[D.media]=[]}x[D.media].push(j[D.rules])}}for(var w in o){if(o[w]&&o[w].parentNode===e){e.removeChild(o[w])}}for(var w in x){var E=s.createElement("style"),y=x[w].join("\n");E.type="text/css";E.media=w;if(E.styleSheet){E.styleSheet.cssText=y}else{E.appendChild(s.createTextNode(y))}A.appendChild(E);o.push(E)}e.insertBefore(A,z.nextSibling)},m=function(t,v){var u=c();if(!u){return}u.open("GET",t,true);u.onreadystatechange=function(){if(u.readyState!=4||u.status!=200&&u.status!=304){return}v(u.responseText)};if(u.readyState==4){return}u.send()},c=(function(){var t=false,u=[function(){return new ActiveXObject("Microsoft.XMLHTTP")},function(){return new ActiveXObject("Msxml3.XMLHTTP")},function(){return new ActiveXObject("Msxml2.XMLHTTP")},function(){return new XMLHttpRequest()}],w=u.length;while(w--){try{t=u[w]()}catch(v){continue}break}return function(){return t}})();a();respond.update=a;function r(){i(true)}if(d.addEventListener){d.addEventListener("resize",r,false)}else{if(d.attachEvent){d.attachEvent("onresize",r)}}})(this,(function(f){var g=(function(k){var i=3,l=document.createElement("div"),j=l.getElementsByTagName("i");while(l.innerHTML="<!--[if gt IE "+(++i)+"]><i></i><![endif]-->",j[0]){}return i>4?i:k}());if(f.matchMedia||g&&g>=9){return true}if(g&&g<=8){return false}var e=f.document,a=e.documentElement,b=e.createElement("body"),h=e.createElement("div"),d=e.createElement("style"),c="@media only all { #qtest { position: absolute; } }";h.setAttribute("id","qtest");d.type="text/css";b.appendChild(h);if(d.styleSheet){d.styleSheet.cssText=c}else{d.appendChild(e.createTextNode(c))}a.insertBefore(b,a.firstChild);a.insertBefore(d,b);support=(f.getComputedStyle?f.getComputedStyle(h,null):h.currentStyle)["position"]=="absolute";a.removeChild(b);a.removeChild(d);return support})(this));

/*******************************************************************************/
/*
selectivizr v1.0.0 - (c) Keith Clark, freely distributable under the terms of the MIT license.
selectivizr.com
*/
/*******************************************************************************/

(function(x){function K(a){return a.replace(L,o).replace(M,function(b,e,c){b=c.split(",");c=0;for(var g=b.length;c<g;c++){var j=N(b[c].replace(O,o).replace(P,o))+t,f=[];b[c]=j.replace(Q,function(d,k,l,i,h){if(k){if(f.length>0){d=f;var u;h=j.substring(0,h).replace(R,n);if(h==n||h.charAt(h.length-1)==t)h+="*";try{u=v(h)}catch(da){}if(u){h=0;for(l=u.length;h<l;h++){i=u[h];for(var y=i.className,z=0,S=d.length;z<S;z++){var q=d[z];if(!RegExp("(^|\\s)"+q.className+"(\\s|$)").test(i.className))if(q.b&&(q.b===true||q.b(i)===true))y=A(y,q.className,true)}i.className=y}}f=[]}return k}else{if(k=l?T(l):!B||B.test(i)?{className:C(i),b:true}:null){f.push(k);return"."+k.className}return d}})}return e+b.join(",")})}function T(a){var b=true,e=C(a.slice(1)),c=a.substring(0,5)==":not(",g,j;if(c)a=a.slice(5,-1);var f=a.indexOf("(");if(f>-1)a=a.substring(0,f);if(a.charAt(0)==":")switch(a.slice(1)){case "root":b=function(d){return c?d!=D:d==D};break;case "target":if(p==8){b=function(d){function k(){var l=location.hash,i=l.slice(1);return c?l==""||d.id!=i:l!=""&&d.id==i}x.attachEvent("onhashchange",function(){r(d,e,k())});return k()};break}return false;case "checked":b=function(d){U.test(d.type)&&d.attachEvent("onpropertychange",function(){event.propertyName=="checked"&&r(d,e,d.checked!==c)});return d.checked!==c};break;case "disabled":c=!c;case "enabled":b=function(d){if(V.test(d.tagName)){d.attachEvent("onpropertychange",function(){event.propertyName=="$disabled"&&r(d,e,d.a===c)});w.push(d);d.a=d.disabled;return d.disabled===c}return a==":enabled"?c:!c};break;case "focus":g="focus";j="blur";case "hover":if(!g){g="mouseenter";j="mouseleave"}b=function(d){d.attachEvent("on"+(c?j:g),function(){r(d,e,true)});d.attachEvent("on"+(c?g:j),function(){r(d,e,false)});return c};break;default:if(!W.test(a))return false;break}return{className:e,b:b}}function C(a){return E+"-"+(p==6&&X?Y++:a.replace(Z,function(b){return b.charCodeAt(0)}))}function N(a){return a.replace(F,o).replace($,t)}function r(a,b,e){var c=a.className;b=A(c,b,e);if(b!=c){a.className=b;a.parentNode.className+=n}}function A(a,b,e){var c=RegExp("(^|\\s)"+b+"(\\s|$)"),g=c.test(a);return e?g?a:a+t+b:g?a.replace(c,o).replace(F,o):a}function G(a,b){if(/^https?:\/\//i.test(a))return b.substring(0,b.indexOf("/",8))==a.substring(0,a.indexOf("/",8))?a:null;if(a.charAt(0)=="/")return b.substring(0,b.indexOf("/",8))+a;var e=b.split("?")[0];if(a.charAt(0)!="?"&&e.charAt(e.length-1)!="/")e=e.substring(0,e.lastIndexOf("/")+1);return e+a}function H(a){if(a){s.open("GET",a,false);s.send();return(s.status==200?s.responseText:n).replace(aa,n).replace(ba,function(b,e,c){return H(G(c,a))})}return n}function ca(){var a,b;a=m.getElementsByTagName("BASE");for(var e=a.length>0?a[0].href:m.location.href,c=0;c<m.styleSheets.length;c++){b=m.styleSheets[c];if(b.href!=n)if(a=G(b.href,e))b.cssText=K(H(a))}w.length>0&&setInterval(function(){for(var g=0,j=w.length;g<j;g++){var f=w[g];if(f.disabled!==f.a)if(f.disabled){f.disabled=false;f.a=true;f.disabled=true}else f.a=f.disabled}},250)}if(!/*@cc_on!@*/true){var m=document,D=m.documentElement,s=function(){if(x.XMLHttpRequest)return new XMLHttpRequest;try{return new ActiveXObject("Microsoft.XMLHTTP")}catch(a){return null}}(),p=/MSIE ([\d])/.exec(navigator.userAgent)[1];if(!(m.compatMode!="CSS1Compat"||p<6||p>8||!s)){var I={NW:"*.Dom.select",DOMAssistant:"*.$",Prototype:"$$",YAHOO:"*.util.Selector.query",MooTools:"$$",Sizzle:"*",jQuery:"*",dojo:"*.query"},v,w=[],Y=0,X=true,E="slvzr",J=E+"DOMReady",aa=/(\/\*[^*]*\*+([^\/][^*]*\*+)*\/)\s*/g,ba=/@import\s*url\(\s*(["'])?(.*?)\1\s*\)[\w\W]*?;/g,W=/^:(empty|(first|last|only|nth(-last)?)-(child|of-type))$/,L=/:(:first-(?:line|letter))/g,M=/(^|})\s*([^\{]*?[\[:][^{]+)/g,Q=/([ +~>])|(:[a-z-]+(?:\(.*?\)+)?)|(\[.*?\])/g,R=/(:not\()?:(hover|enabled|disabled|focus|checked|target|active|visited|first-line|first-letter)\)?/g,Z=/[^\w-]/g,V=/^(INPUT|SELECT|TEXTAREA|BUTTON)$/,U=/^(checkbox|radio)$/,B=p==8?/[\$\^]=(['"])\1/:p==7?/[\$\^*]=(['"])\1/:null,O=/([(\[+~])\s+/g,P=/\s+([)\]+~])/g,$=/\s+/g,F=/^\s*((?:[\S\s]*\S)?)\s*$/,n="",t=" ",o="$1";m.write("<script id="+J+" defer src='//:'><\/script>");m.getElementById(J).onreadystatechange=function(){if(this.readyState=="complete"){a:{var a;for(var b in I)if(x[b]&&(a=eval(I[b].replace("*",b)))){v=a;break a}v=false}if(v){ca();this.parentNode.removeChild(this)}}}}}})(this);





