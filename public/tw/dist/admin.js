/*! For license information please see admin.js.LICENSE.txt */
(()=>{var e,t,r={858:e=>{e.exports=function(e){if(Array.isArray(e))return e}},646:e=>{e.exports=function(e){if(Array.isArray(e)){for(var t=0,r=Array(e.length);t<e.length;t++)r[t]=e[t];return r}}},926:e=>{function t(e,t,r,n,o,i,a){try{var l=e[i](a),c=l.value}catch(e){return void r(e)}l.done?t(c):Promise.resolve(c).then(n,o)}e.exports=function(e){return function(){var r=this,n=arguments;return new Promise((function(o,i){var a=e.apply(r,n);function l(e){t(a,o,i,l,c,"next",e)}function c(e){t(a,o,i,l,c,"throw",e)}l(void 0)}))}}},154:e=>{function t(){return e.exports=t=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var r=arguments[t];for(var n in r)({}).hasOwnProperty.call(r,n)&&(e[n]=r[n])}return e},t.apply(this,arguments)}e.exports=t},860:e=>{e.exports=function(e){if(Symbol.iterator in Object(e)||"[object Arguments]"==={}.toString.call(e))return Array.from(e)}},884:e=>{e.exports=function(e,t){if(Symbol.iterator in Object(e)||"[object Arguments]"==={}.toString.call(e)){var r=[],n=!0,o=!1,i=void 0;try{for(var a,l=e[Symbol.iterator]();!(n=(a=l.next()).done)&&(r.push(a.value),!t||r.length!==t);n=!0);}catch(e){o=!0,i=e}finally{try{n||null==l.return||l.return()}finally{if(o)throw i}}return r}}},521:e=>{e.exports=function(){throw new TypeError("Invalid attempt to destructure non-iterable instance")}},206:e=>{e.exports=function(){throw new TypeError("Invalid attempt to spread non-iterable instance")}},38:(e,t,r)=>{var n=r(858),o=r(884),i=r(521);e.exports=function(e,t){return n(e)||o(e,t)||i()}},319:(e,t,r)=>{var n=r(646),o=r(860),i=r(206);e.exports=function(e){return n(e)||o(e)||i()}},553:e=>{var t=function(e){"use strict";var t,r=Object.prototype,n=r.hasOwnProperty,o="function"==typeof Symbol?Symbol:{},i=o.iterator||"@@iterator",a=o.asyncIterator||"@@asyncIterator",l=o.toStringTag||"@@toStringTag";function c(e,t,r,n){var o=t&&t.prototype instanceof d?t:d,i=Object.create(o.prototype),a=new L(n||[]);return i._invoke=function(e,t,r){var n=s;return function(o,i){if(n===f)throw Error("Generator is already running");if(n===m){if("throw"===o)throw i;return P()}for(r.method=o,r.arg=i;;){var a=r.delegate;if(a){var l=k(a,r);if(l){if(l===h)continue;return l}}if("next"===r.method)r.sent=r._sent=r.arg;else if("throw"===r.method){if(n===s)throw n=m,r.arg;r.dispatchException(r.arg)}else"return"===r.method&&r.abrupt("return",r.arg);n=f;var c=u(e,t,r);if("normal"===c.type){if(n=r.done?m:p,c.arg===h)continue;return{value:c.arg,done:r.done}}"throw"===c.type&&(n=m,r.method="throw",r.arg=c.arg)}}}(e,r,a),i}function u(e,t,r){try{return{type:"normal",arg:e.call(t,r)}}catch(e){return{type:"throw",arg:e}}}e.wrap=c;var s="suspendedStart",p="suspendedYield",f="executing",m="completed",h={};function d(){}function v(){}function w(){}var y={};y[i]=function(){return this};var g=Object.getPrototypeOf,x=g&&g(g(j([])));x&&x!==r&&n.call(x,i)&&(y=x);var b=w.prototype=d.prototype=Object.create(y);function E(e){["next","throw","return"].forEach((function(t){e[t]=function(e){return this._invoke(t,e)}}))}function _(e){function t(r,o,i,a){var l=u(e[r],e,o);if("throw"!==l.type){var c=l.arg,s=c.value;return s&&"object"==typeof s&&n.call(s,"__await")?Promise.resolve(s.__await).then((function(e){t("next",e,i,a)}),(function(e){t("throw",e,i,a)})):Promise.resolve(s).then((function(e){c.value=e,i(c)}),(function(e){return t("throw",e,i,a)}))}a(l.arg)}var r;this._invoke=function(e,n){function o(){return new Promise((function(r,o){t(e,n,r,o)}))}return r=r?r.then(o,o):o()}}function k(e,r){var n=e.iterator[r.method];if(n===t){if(r.delegate=null,"throw"===r.method){if(e.iterator.return&&(r.method="return",r.arg=t,k(e,r),"throw"===r.method))return h;r.method="throw",r.arg=new TypeError("The iterator does not provide a 'throw' method")}return h}var o=u(n,e.iterator,r.arg);if("throw"===o.type)return r.method="throw",r.arg=o.arg,r.delegate=null,h;var i=o.arg;return i?i.done?(r[e.resultName]=i.value,r.next=e.nextLoc,"return"!==r.method&&(r.method="next",r.arg=t),r.delegate=null,h):i:(r.method="throw",r.arg=new TypeError("iterator result is not an object"),r.delegate=null,h)}function C(e){var t={tryLoc:e[0]};1 in e&&(t.catchLoc=e[1]),2 in e&&(t.finallyLoc=e[2],t.afterLoc=e[3]),this.tryEntries.push(t)}function S(e){var t=e.completion||{};t.type="normal",delete t.arg,e.completion=t}function L(e){this.tryEntries=[{tryLoc:"root"}],e.forEach(C,this),this.reset(!0)}function j(e){if(e){var r=e[i];if(r)return r.call(e);if("function"==typeof e.next)return e;if(!isNaN(e.length)){var o=-1,a=function r(){for(;++o<e.length;)if(n.call(e,o))return r.value=e[o],r.done=!1,r;return r.value=t,r.done=!0,r};return a.next=a}}return{next:P}}function P(){return{value:t,done:!0}}return v.prototype=b.constructor=w,w.constructor=v,w[l]=v.displayName="GeneratorFunction",e.isGeneratorFunction=function(e){var t="function"==typeof e&&e.constructor;return!!t&&(t===v||"GeneratorFunction"===(t.displayName||t.name))},e.mark=function(e){return Object.setPrototypeOf?Object.setPrototypeOf(e,w):(e.__proto__=w,l in e||(e[l]="GeneratorFunction")),e.prototype=Object.create(b),e},e.awrap=function(e){return{__await:e}},E(_.prototype),_.prototype[a]=function(){return this},e.AsyncIterator=_,e.async=function(t,r,n,o){var i=new _(c(t,r,n,o));return e.isGeneratorFunction(r)?i:i.next().then((function(e){return e.done?e.value:i.next()}))},E(b),b[l]="Generator",b[i]=function(){return this},b.toString=function(){return"[object Generator]"},e.keys=function(e){var t=[];for(var r in e)t.push(r);return t.reverse(),function r(){for(;t.length;){var n=t.pop();if(n in e)return r.value=n,r.done=!1,r}return r.done=!0,r}},e.values=j,L.prototype={constructor:L,reset:function(e){if(this.prev=0,this.next=0,this.sent=this._sent=t,this.done=!1,this.delegate=null,this.method="next",this.arg=t,this.tryEntries.forEach(S),!e)for(var r in this)"t"===r.charAt(0)&&n.call(this,r)&&!isNaN(+r.slice(1))&&(this[r]=t)},stop:function(){this.done=!0;var e=this.tryEntries[0].completion;if("throw"===e.type)throw e.arg;return this.rval},dispatchException:function(e){if(this.done)throw e;var r=this;function o(n,o){return l.type="throw",l.arg=e,r.next=n,o&&(r.method="next",r.arg=t),!!o}for(var i=this.tryEntries.length-1;i>=0;--i){var a=this.tryEntries[i],l=a.completion;if("root"===a.tryLoc)return o("end");if(a.tryLoc<=this.prev){var c=n.call(a,"catchLoc"),u=n.call(a,"finallyLoc");if(c&&u){if(this.prev<a.catchLoc)return o(a.catchLoc,!0);if(this.prev<a.finallyLoc)return o(a.finallyLoc)}else if(c){if(this.prev<a.catchLoc)return o(a.catchLoc,!0)}else{if(!u)throw Error("try statement without catch or finally");if(this.prev<a.finallyLoc)return o(a.finallyLoc)}}}},abrupt:function(e,t){for(var r=this.tryEntries.length-1;r>=0;--r){var o=this.tryEntries[r];if(o.tryLoc<=this.prev&&n.call(o,"finallyLoc")&&this.prev<o.finallyLoc){var i=o;break}}i&&("break"===e||"continue"===e)&&i.tryLoc<=t&&t<=i.finallyLoc&&(i=null);var a=i?i.completion:{};return a.type=e,a.arg=t,i?(this.method="next",this.next=i.finallyLoc,h):this.complete(a)},complete:function(e,t){if("throw"===e.type)throw e.arg;return"break"===e.type||"continue"===e.type?this.next=e.arg:"return"===e.type?(this.rval=this.arg=e.arg,this.method="return",this.next="end"):"normal"===e.type&&t&&(this.next=t),h},finish:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var r=this.tryEntries[t];if(r.finallyLoc===e)return this.complete(r.completion,r.afterLoc),S(r),h}},catch:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var r=this.tryEntries[t];if(r.tryLoc===e){var n=r.completion;if("throw"===n.type){var o=n.arg;S(r)}return o}}throw Error("illegal catch attempt")},delegateYield:function(e,r,n){return this.delegate={iterator:j(e),resultName:r,nextLoc:n},"next"===this.method&&(this.arg=t),h}},e}(e.exports);try{regeneratorRuntime=t}catch(e){Function("r","regeneratorRuntime = r")(t)}},757:(e,t,r)=>{e.exports=r(553)},311:(e,t,r)=>{"use strict";r.d(t,{xl:()=>o,b8:()=>i,Uq:()=>a,Nq:()=>l});r(27);var n=void 0!==wp.element;function o(){return n?wp.element.createElement("svg",{role:"img",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 576 512",width:18},wp.element.createElement("path",{fill:"currentColor",d:"M208 32h-48a96 96 0 0 0-96 96v37.48a32.06 32.06 0 0 1-9.38 22.65L9.37 233.37a32 32 0 0 0 0 45.26l45.25 45.25A32 32 0 0 1 64 346.51V384a96 96 0 0 0 96 96h48a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16h-48a32 32 0 0 1-32-32v-37.48a96 96 0 0 0-28.13-67.89L77.25 256l22.63-22.63A96 96 0 0 0 128 165.48V128a32 32 0 0 1 32-32h48a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16zm358.63 201.37l-45.25-45.24a32.06 32.06 0 0 1-9.38-22.65V128a96 96 0 0 0-96-96h-48a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h48a32 32 0 0 1 32 32v37.47a96 96 0 0 0 28.13 67.91L498.75 256l-22.62 22.63A96 96 0 0 0 448 346.52V384a32 32 0 0 1-32 32h-48a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h48a96 96 0 0 0 96-96v-37.49a32 32 0 0 1 9.38-22.63l45.25-45.25a32 32 0 0 0 0-45.26z"})):null}function i(){return n?wp.element.createElement("svg",{role:"img",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 512 512",width:18},wp.element.createElement("path",{fill:"currentColor",d:"M464 32H48A48 48 0 000 80v352a48 48 0 0048 48h416a48 48 0 0048-48V80a48 48 0 00-48-48zM224 416H64V160h160v256zm224 0H288V160h160v256z"})):null}function a(){return n?wp.element.createElement("svg",{role:"img",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 576 512",width:18},wp.element.createElement("path",{fill:"currentColor",d:"M480 416v16c0 26.51-21.49 48-48 48H48c-26.51 0-48-21.49-48-48V176c0-26.51 21.49-48 48-48h16v208c0 44.112 35.888 80 80 80h336zm96-80V80c0-26.51-21.49-48-48-48H144c-26.51 0-48 21.49-48 48v256c0 26.51 21.49 48 48 48h384c26.51 0 48-21.49 48-48zM256 128c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-96 144l55.515-55.515c4.686-4.686 12.284-4.686 16.971 0L272 256l135.515-135.515c4.686-4.686 12.284-4.686 16.971 0L512 208v112H160v-48z"})):null}function l(){return n?wp.element.createElement("svg",{role:"img",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 512 512",width:18},wp.element.createElement("path",{fill:"currentColor",d:"M326.612 185.391c59.747 59.809 58.927 155.698.36 214.59-.11.12-.24.25-.36.37l-67.2 67.2c-59.27 59.27-155.699 59.262-214.96 0-59.27-59.26-59.27-155.7 0-214.96l37.106-37.106c9.84-9.84 26.786-3.3 27.294 10.606.648 17.722 3.826 35.527 9.69 52.721 1.986 5.822.567 12.262-3.783 16.612l-13.087 13.087c-28.026 28.026-28.905 73.66-1.155 101.96 28.024 28.579 74.086 28.749 102.325.51l67.2-67.19c28.191-28.191 28.073-73.757 0-101.83-3.701-3.694-7.429-6.564-10.341-8.569a16.037 16.037 0 0 1-6.947-12.606c-.396-10.567 3.348-21.456 11.698-29.806l21.054-21.055c5.521-5.521 14.182-6.199 20.584-1.731a152.482 152.482 0 0 1 20.522 17.197zM467.547 44.449c-59.261-59.262-155.69-59.27-214.96 0l-67.2 67.2c-.12.12-.25.25-.36.37-58.566 58.892-59.387 154.781.36 214.59a152.454 152.454 0 0 0 20.521 17.196c6.402 4.468 15.064 3.789 20.584-1.731l21.054-21.055c8.35-8.35 12.094-19.239 11.698-29.806a16.037 16.037 0 0 0-6.947-12.606c-2.912-2.005-6.64-4.875-10.341-8.569-28.073-28.073-28.191-73.639 0-101.83l67.2-67.19c28.239-28.239 74.3-28.069 102.325.51 27.75 28.3 26.872 73.934-1.155 101.96l-13.087 13.087c-4.35 4.35-5.769 10.79-3.783 16.612 5.864 17.194 9.042 34.999 9.69 52.721.509 13.906 17.454 20.446 27.294 10.606l37.106-37.106c59.271-59.259 59.271-155.699.001-214.959z"})):null}},804:e=>{"use strict";e.exports=self.React},196:e=>{"use strict";e.exports=self.ReactDOM},399:e=>{"use strict";e.exports=self.wp.blockEditor},997:e=>{"use strict";e.exports=self.wp.components},417:e=>{"use strict";e.exports=self.wp.compose},707:e=>{"use strict";e.exports=self.wp.data},649:e=>{"use strict";e.exports=self.wp.editPost},885:e=>{"use strict";e.exports=self.wp.editor},27:e=>{"use strict";e.exports=self.wp.element},799:e=>{"use strict";e.exports=self.wp.hooks},424:e=>{"use strict";e.exports=self.wp.htmlEntities},163:e=>{"use strict";e.exports=self.wp.i18n},200:e=>{"use strict";e.exports=self.wp.plugins}},n={};function o(e){var t=n[e];if(void 0!==t)return t.exports;var i=n[e]={exports:{}};return r[e](i,i.exports,o),i.exports}o.m=r,o.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return o.d(t,{a:t}),t},o.d=(e,t)=>{for(var r in t)o.o(t,r)&&!o.o(e,r)&&Object.defineProperty(e,r,{enumerable:!0,get:t[r]})},o.f={},o.e=e=>Promise.all(Object.keys(o.f).reduce(((t,r)=>(o.f[r](e,t),t)),[])),o.u=e=>e+".js",o.miniCssF=()=>{},o.o=(e,t)=>({}.hasOwnProperty.call(e,t)),e={},t="@bybas/typewriter:",o.l=(r,n,i)=>{if(e[r])e[r].push(n);else{var a,l;if(void 0!==i)for(var c=document.getElementsByTagName("script"),u=0;u<c.length;u++){var s=c[u];if(s.getAttribute("src")==r||s.getAttribute("data-webpack")==t+i){a=s;break}}a||(l=!0,(a=document.createElement("script")).charset="utf-8",a.timeout=120,o.nc&&a.setAttribute("nonce",o.nc),a.setAttribute("data-webpack",t+i),a.src=r),e[r]=[n];var p=(t,n)=>{a.onerror=a.onload=null,clearTimeout(f);var o=e[r];if(delete e[r],a.parentNode&&a.parentNode.removeChild(a),o&&o.forEach((e=>e(n))),t)return t(n)},f=setTimeout(p.bind(null,void 0,{type:"timeout",target:a}),12e4);a.onerror=p.bind(null,a.onerror),a.onload=p.bind(null,a.onload),l&&document.head.appendChild(a)}},o.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},o.p="/tw/dist/",(()=>{var e={328:0};o.f.j=(t,r)=>{var n=o.o(e,t)?e[t]:void 0;if(0!==n)if(n)r.push(n[2]);else{var i=new Promise(((r,o)=>n=e[t]=[r,o]));r.push(n[2]=i);var a=o.p+o.u(t),l=Error();o.l(a,(r=>{if(o.o(e,t)&&(0!==(n=e[t])&&(e[t]=void 0),n)){var i=r&&("load"===r.type?"missing":r.type),a=r&&r.target&&r.target.src;l.message="Loading chunk "+t+" failed.\n("+i+": "+a+")",l.name="ChunkLoadError",l.type=i,l.request=a,n[1](l)}}),"chunk-"+t,t)}};var t=(t,r)=>{var n,i,[a,l,c]=r,u=0;for(n in l)o.o(l,n)&&(o.m[n]=l[n]);for(c&&c(o),t&&t(r);u<a.length;u++)i=a[u],o.o(e,i)&&e[i]&&e[i][0](),e[a[u]]=0},r=self.webpackChunk_bybas_typewriter=self.webpackChunk_bybas_typewriter||[];r.forEach(t.bind(null,0)),r.push=t.bind(null,r.push.bind(r))})();var i={};(()=>{"use strict";o.r(i);var e=o(757),t=o.n(e),r=o(926),n=o.n(r),a=o(38),l=o.n(a),c=o(27),u=o(399);const s=self.wp.blocks;var p=o(997),f=o(163),m=o(311);function h(e,t){var r="undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(!r){if(Array.isArray(e)||(r=function(e,t){if(!e)return;if("string"==typeof e)return d(e,t);var r={}.toString.call(e).slice(8,-1);"Object"===r&&e.constructor&&(r=e.constructor.name);if("Map"===r||"Set"===r)return Array.from(e);if("Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r))return d(e,t)}(e))||t&&e&&"number"==typeof e.length){r&&(e=r);var n=0,o=function(){};return{s:o,n:function(){return n>=e.length?{done:!0}:{done:!1,value:e[n++]}},e:function(e){throw e},f:o}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var i,a=!0,l=!1;return{s:function(){r=r.call(e)},n:function(){var e=r.next();return a=e.done,e},e:function(e){l=!0,i=e},f:function(){try{a||null==r.return||r.return()}finally{if(l)throw i}}}}function d(e,t){(null==t||t>e.length)&&(t=e.length);for(var r=0,n=Array(t);r<t;r++)n[r]=e[r];return n}function v(){!function(){function e(e){if(arguments.length>1&&void 0!==arguments[1]&&arguments[1]&&(!e||""===e.trim()))return!0;try{var t=JSON.parse(e);if(Array.isArray(t)&&t.length>0){var r,n=h(t);try{for(n.s();!(r=n.n()).done;){if(!r.value["@type"])return!1}}catch(e){n.e(e)}finally{n.f()}}else if(!t["@type"])return!1;return!0}catch(e){return!1}}(0,s.registerBlockType)("tw/seo-json-ld",{title:(0,f.__)("Structured data","tw"),description:(0,f.__)("Allows you to add stuctured data to pages.","tw"),category:"tw-seo",icon:(0,m.xl)(),supports:{html:!1},attributes:{json:{type:"string"}},edit:function(t){var r=t.attributes,n=t.setAttributes,o=(0,c.useState)(!1),i=l()(o,2),a=i[0],s=i[1],h=(0,c.useState)(r.json||""),d=l()(h,2),v=d[0],w=d[1],y=(0,c.useMemo)((function(){return e(v,!0)}),[v]),g=(0,c.useMemo)((function(){return e(r.json||"")}),[r.json]),x=(0,c.useMemo)((function(){if(!g)return"";var e=JSON.parse(r.json);return Array.isArray(e)?e.map((function(e){return e["@type"]})).join(", "):e["@type"]}),[r.json]);return wp.element.createElement(c.Fragment,null,a&&wp.element.createElement(p.Modal,{focusOnMount:!0,shouldCloseOnClickOutside:!1,shouldCloseOnEsc:!0,title:(0,f.__)("Edit Structured data","tw"),onRequestClose:function(){s(!1),w(r.json)}},wp.element.createElement("div",{style:{width:540,maxWidth:"100%"}}),wp.element.createElement(p.TextareaControl,{help:(0,f.__)("Please do not edit this if you don't know what this does.","tw"),rows:21,style:{fontFamily:"monospace"},value:v,onChange:function(e){return w(e)}}),wp.element.createElement(p.Button,{disabled:!y,isPrimary:!0,label:(0,f.__)("Close"),onClick:function(){n({json:v}),s(!1)}},(0,f.__)("Save"))),wp.element.createElement("div",{className:"wp-block",style:{marginBottom:15}},wp.element.createElement(u.BlockControls,null),wp.element.createElement(p.Placeholder,{icon:(0,m.xl)(),label:g?"@type: ".concat(x):(0,f.__)("Add Structured data","tw"),instructions:(0,f.__)("Add Json-LD Structured data to the page. Structured data is used by Google to create rich snippiets in their search results.","tw")},wp.element.createElement(p.Button,{isSecondary:!0,onClick:function(){return s(!0)}},g?(0,f.__)("Edit"):(0,f.__)("Add")))))},save:function(e){var t=e.attributes;return t.json&&""!==t.json.trim()?wp.element.createElement("script",{type:"application/ld+json"},JSON.stringify(JSON.parse(t.json))):null}})}()}var w=o(154),y=o.n(w),g=o(319),x=o.n(g),b=o(707);function E(){!function(){var e="lg",t=function(t){e=t,window.dispatchEvent(new CustomEvent("tw_update_grid"))};function r(e){var t=arguments.length>1&&void 0!==arguments[1]&&arguments[1],r=["col-".concat(e.xs)];return(t||e.sm&&e.sm!==e.xs)&&r.push("sm:col-".concat(e.sm)),(t||e.md&&e.md!==e.sm)&&r.push("md:col-".concat(e.md)),(t||e.lg&&e.lg!==e.md)&&r.push("lg:col-".concat(e.lg)),(t||e.xl&&e.xl!==e.lg)&&r.push("xl:col-".concat(e.xl)),r}function n(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{},r=!(arguments.length>2&&void 0!==arguments[2])||arguments[2],n={};return n.xs=e.xs||6,n.sm=e.sm&&e.sm!==e.xs?e.sm:r?n.xs:null,n.md=e.md&&e.md!==e.sm?e.md:r?n.sm:null,n.lg=e.lg&&e.lg!==e.md?e.lg:r?n.md:null,n.xl=e.xl&&e.xl!==e.lg?e.xl:r?n.lg:null,Object.assign(n,t)}(0,s.registerBlockType)("tw/structure-column",{title:(0,f.__)("Column","tw"),description:(0,f.__)("This block is used as a column wrapper in Grid.","tw"),category:"tw-structure",icon:(0,m.b8)(),parent:["tw/structure-grid"],supports:{html:!1},attributes:{xs:{default:6,type:"integer"},sm:{default:null,type:"integer"},md:{default:null,type:"integer"},lg:{default:null,type:"integer"},xl:{default:null,type:"integer"}},edit:function(o){var i=o.attributes,a=o.setAttributes,s=(0,c.useState)(),m=l()(s,2)[1],h=n(i),d=(0,u.useBlockProps)({className:[i.className||null,"tw-block-column","preview-".concat(e)].concat(x()(r(h,!0))).filter((function(e){return!!e})).join(" ")});return(0,c.useEffect)((function(){var t=function(){return m({preview:e})};return window.addEventListener("tw_update_grid",t),function(){return window.removeEventListener("tw_update_grid",t)}})),wp.element.createElement(c.Fragment,null,wp.element.createElement(u.InspectorControls,null,wp.element.createElement(p.PanelBody,{title:(0,f.__)("Settings","tw")},wp.element.createElement(p.BaseControl,{label:(0,f.__)("Preview","tw")},wp.element.createElement("div",{style:{height:6}}),wp.element.createElement(p.ButtonGroup,null,wp.element.createElement(p.Button,{isPrimary:"xs"===e,isSecondary:"xs"!==e,onClick:function(){return t("xs")}},"XS"),wp.element.createElement(p.Button,{isPrimary:"sm"===e,isSecondary:"sm"!==e,onClick:function(){return t("sm")}},"SM"),wp.element.createElement(p.Button,{isPrimary:"md"===e,isSecondary:"md"!==e,onClick:function(){return t("md")}},"MD"),wp.element.createElement(p.Button,{isPrimary:"lg"===e,isSecondary:"lg"!==e,onClick:function(){return t("lg")}},"LG"),wp.element.createElement(p.Button,{isPrimary:"xl"===e,isSecondary:"xl"!==e,onClick:function(){return t("xl")}},"XL"))),wp.element.createElement(p.RangeControl,{max:12,min:1,value:h.xs,label:(0,f.__)("Column size","tw")+" (XS)",onChange:function(e){return a(n(i,{xs:e},!1))}}),wp.element.createElement(p.RangeControl,{max:12,min:1,value:h.sm,label:(0,f.__)("Column size","tw")+" (SM)",onChange:function(e){return a(n(i,{sm:e},!1))}}),wp.element.createElement(p.RangeControl,{max:12,min:1,value:h.md,label:(0,f.__)("Column size","tw")+" (MD)",onChange:function(e){return a(n(i,{md:e},!1))}}),wp.element.createElement(p.RangeControl,{max:12,min:1,value:h.lg,label:(0,f.__)("Column size","tw")+" (LG)",onChange:function(e){return a(n(i,{lg:e},!1))}}),wp.element.createElement(p.RangeControl,{max:12,min:1,value:h.xl,label:(0,f.__)("Column size","tw")+" (XL)",onChange:function(e){return a(n(i,{xl:e},!1))}}))),wp.element.createElement("div",d,wp.element.createElement(u.InnerBlocks,{orientation:"vertical",template:[["core/paragraph",{placeholder:"Placeholder"}]]})))},save:function(e){var t=e.attributes,n=u.useBlockProps.save({className:r(t).join(" ")});return wp.element.createElement("div",n,wp.element.createElement(u.InnerBlocks.Content,null))}}),(0,s.registerBlockType)("tw/structure-grid",{title:(0,f.__)("Grid","tw"),description:(0,f.__)("Create responsive grids.","tw"),category:"tw-structure",icon:(0,m.b8)(),supports:{html:!1},attributes:{},edit:function(e){var t=e.clientId,r=(0,b.useSelect)((function(e){return e("core/block-editor")}),[t]).getBlock(t),n=(0,u.useBlockProps)({className:"tw-block-grid"});return wp.element.createElement("div",y()({},n,{style:{"--grid-columns":r.innerBlocks.length}}),wp.element.createElement(u.BlockControls,null),wp.element.createElement(u.InnerBlocks,{allowedBlocks:["tw/structure-column"],orientation:"horizontal",template:[["tw/structure-column"],["tw/structure-column"]]}))},save:function(){var e=u.useBlockProps.save({className:"row"});return wp.element.createElement("div",e,wp.element.createElement(u.InnerBlocks.Content,null))}})}()}var _="@bybas/typewriter",k="1.0.0";function C(){return(C=n()(t().mark((function e(){var r,n,i,a,l,c;return t().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:if(r={},void 0===wp.element){e.next=10;break}return e.next=4,Promise.all([o.e(713),o.e(696)]).then(o.bind(o,696));case 4:n=e.sent,i=n.Gallery,a=n.MetaFields,l=n.PostThumbnail,c=n.Relation,r={Gallery:i,MetaFields:a,PostThumbnail:l,Relation:c};case 10:window.tw={feature:r,name:_,version:k};case 11:case"end":return e.stop()}}),e)})))).apply(this,arguments)}(function(){return C.apply(this,arguments)})().then(),void 0!==wp.blocks&&(v(),E())})();for(var a in i)this[a]=i[a];i.__esModule&&Object.defineProperty(this,"__esModule",{value:!0})})();