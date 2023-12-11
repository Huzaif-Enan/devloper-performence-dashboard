/*! For license information please see 934.js.LICENSE.txt */
"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[934],{84468:(t,e,r)=>{r.d(e,{Z:()=>w});var n=r(67294),o=r(73935),i=r(93173),a=r(17965),l=r(50555),s=r(41248),c=r(9133),u=r(22928),d=r(27484),f=r.n(d),p=r(1988),h=r(96297),m=r(85893);function y(t){return y="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},y(t)}function v(t){return function(t){if(Array.isArray(t))return x(t)}(t)||function(t){if("undefined"!=typeof Symbol&&null!=t[Symbol.iterator]||null!=t["@@iterator"])return Array.from(t)}(t)||j(t)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function _(){_=function(){return e};var t,e={},r=Object.prototype,n=r.hasOwnProperty,o=Object.defineProperty||function(t,e,r){t[e]=r.value},i="function"==typeof Symbol?Symbol:{},a=i.iterator||"@@iterator",l=i.asyncIterator||"@@asyncIterator",s=i.toStringTag||"@@toStringTag";function c(t,e,r){return Object.defineProperty(t,e,{value:r,enumerable:!0,configurable:!0,writable:!0}),t[e]}try{c({},"")}catch(t){c=function(t,e,r){return t[e]=r}}function u(t,e,r,n){var i=e&&e.prototype instanceof g?e:g,a=Object.create(i.prototype),l=new T(n||[]);return o(a,"_invoke",{value:L(t,r,l)}),a}function d(t,e,r){try{return{type:"normal",arg:t.call(e,r)}}catch(t){return{type:"throw",arg:t}}}e.wrap=u;var f="suspendedStart",p="suspendedYield",h="executing",m="completed",v={};function g(){}function b(){}function j(){}var x={};c(x,a,(function(){return this}));var w=Object.getPrototypeOf,S=w&&w(w(D([])));S&&S!==r&&n.call(S,a)&&(x=S);var N=j.prototype=g.prototype=Object.create(x);function O(t){["next","throw","return"].forEach((function(e){c(t,e,(function(t){return this._invoke(e,t)}))}))}function E(t,e){function r(o,i,a,l){var s=d(t[o],t,i);if("throw"!==s.type){var c=s.arg,u=c.value;return u&&"object"==y(u)&&n.call(u,"__await")?e.resolve(u.__await).then((function(t){r("next",t,a,l)}),(function(t){r("throw",t,a,l)})):e.resolve(u).then((function(t){c.value=t,a(c)}),(function(t){return r("throw",t,a,l)}))}l(s.arg)}var i;o(this,"_invoke",{value:function(t,n){function o(){return new e((function(e,o){r(t,n,e,o)}))}return i=i?i.then(o,o):o()}})}function L(e,r,n){var o=f;return function(i,a){if(o===h)throw new Error("Generator is already running");if(o===m){if("throw"===i)throw a;return{value:t,done:!0}}for(n.method=i,n.arg=a;;){var l=n.delegate;if(l){var s=P(l,n);if(s){if(s===v)continue;return s}}if("next"===n.method)n.sent=n._sent=n.arg;else if("throw"===n.method){if(o===f)throw o=m,n.arg;n.dispatchException(n.arg)}else"return"===n.method&&n.abrupt("return",n.arg);o=h;var c=d(e,r,n);if("normal"===c.type){if(o=n.done?m:p,c.arg===v)continue;return{value:c.arg,done:n.done}}"throw"===c.type&&(o=m,n.method="throw",n.arg=c.arg)}}}function P(e,r){var n=r.method,o=e.iterator[n];if(o===t)return r.delegate=null,"throw"===n&&e.iterator.return&&(r.method="return",r.arg=t,P(e,r),"throw"===r.method)||"return"!==n&&(r.method="throw",r.arg=new TypeError("The iterator does not provide a '"+n+"' method")),v;var i=d(o,e.iterator,r.arg);if("throw"===i.type)return r.method="throw",r.arg=i.arg,r.delegate=null,v;var a=i.arg;return a?a.done?(r[e.resultName]=a.value,r.next=e.nextLoc,"return"!==r.method&&(r.method="next",r.arg=t),r.delegate=null,v):a:(r.method="throw",r.arg=new TypeError("iterator result is not an object"),r.delegate=null,v)}function k(t){var e={tryLoc:t[0]};1 in t&&(e.catchLoc=t[1]),2 in t&&(e.finallyLoc=t[2],e.afterLoc=t[3]),this.tryEntries.push(e)}function A(t){var e=t.completion||{};e.type="normal",delete e.arg,t.completion=e}function T(t){this.tryEntries=[{tryLoc:"root"}],t.forEach(k,this),this.reset(!0)}function D(e){if(e||""===e){var r=e[a];if(r)return r.call(e);if("function"==typeof e.next)return e;if(!isNaN(e.length)){var o=-1,i=function r(){for(;++o<e.length;)if(n.call(e,o))return r.value=e[o],r.done=!1,r;return r.value=t,r.done=!0,r};return i.next=i}}throw new TypeError(y(e)+" is not iterable")}return b.prototype=j,o(N,"constructor",{value:j,configurable:!0}),o(j,"constructor",{value:b,configurable:!0}),b.displayName=c(j,s,"GeneratorFunction"),e.isGeneratorFunction=function(t){var e="function"==typeof t&&t.constructor;return!!e&&(e===b||"GeneratorFunction"===(e.displayName||e.name))},e.mark=function(t){return Object.setPrototypeOf?Object.setPrototypeOf(t,j):(t.__proto__=j,c(t,s,"GeneratorFunction")),t.prototype=Object.create(N),t},e.awrap=function(t){return{__await:t}},O(E.prototype),c(E.prototype,l,(function(){return this})),e.AsyncIterator=E,e.async=function(t,r,n,o,i){void 0===i&&(i=Promise);var a=new E(u(t,r,n,o),i);return e.isGeneratorFunction(r)?a:a.next().then((function(t){return t.done?t.value:a.next()}))},O(N),c(N,s,"Generator"),c(N,a,(function(){return this})),c(N,"toString",(function(){return"[object Generator]"})),e.keys=function(t){var e=Object(t),r=[];for(var n in e)r.push(n);return r.reverse(),function t(){for(;r.length;){var n=r.pop();if(n in e)return t.value=n,t.done=!1,t}return t.done=!0,t}},e.values=D,T.prototype={constructor:T,reset:function(e){if(this.prev=0,this.next=0,this.sent=this._sent=t,this.done=!1,this.delegate=null,this.method="next",this.arg=t,this.tryEntries.forEach(A),!e)for(var r in this)"t"===r.charAt(0)&&n.call(this,r)&&!isNaN(+r.slice(1))&&(this[r]=t)},stop:function(){this.done=!0;var t=this.tryEntries[0].completion;if("throw"===t.type)throw t.arg;return this.rval},dispatchException:function(e){if(this.done)throw e;var r=this;function o(n,o){return l.type="throw",l.arg=e,r.next=n,o&&(r.method="next",r.arg=t),!!o}for(var i=this.tryEntries.length-1;i>=0;--i){var a=this.tryEntries[i],l=a.completion;if("root"===a.tryLoc)return o("end");if(a.tryLoc<=this.prev){var s=n.call(a,"catchLoc"),c=n.call(a,"finallyLoc");if(s&&c){if(this.prev<a.catchLoc)return o(a.catchLoc,!0);if(this.prev<a.finallyLoc)return o(a.finallyLoc)}else if(s){if(this.prev<a.catchLoc)return o(a.catchLoc,!0)}else{if(!c)throw new Error("try statement without catch or finally");if(this.prev<a.finallyLoc)return o(a.finallyLoc)}}}},abrupt:function(t,e){for(var r=this.tryEntries.length-1;r>=0;--r){var o=this.tryEntries[r];if(o.tryLoc<=this.prev&&n.call(o,"finallyLoc")&&this.prev<o.finallyLoc){var i=o;break}}i&&("break"===t||"continue"===t)&&i.tryLoc<=e&&e<=i.finallyLoc&&(i=null);var a=i?i.completion:{};return a.type=t,a.arg=e,i?(this.method="next",this.next=i.finallyLoc,v):this.complete(a)},complete:function(t,e){if("throw"===t.type)throw t.arg;return"break"===t.type||"continue"===t.type?this.next=t.arg:"return"===t.type?(this.rval=this.arg=t.arg,this.method="return",this.next="end"):"normal"===t.type&&e&&(this.next=e),v},finish:function(t){for(var e=this.tryEntries.length-1;e>=0;--e){var r=this.tryEntries[e];if(r.finallyLoc===t)return this.complete(r.completion,r.afterLoc),A(r),v}},catch:function(t){for(var e=this.tryEntries.length-1;e>=0;--e){var r=this.tryEntries[e];if(r.tryLoc===t){var n=r.completion;if("throw"===n.type){var o=n.arg;A(r)}return o}}throw new Error("illegal catch attempt")},delegateYield:function(e,r,n){return this.delegate={iterator:D(e),resultName:r,nextLoc:n},"next"===this.method&&(this.arg=t),v}},e}function g(t,e,r,n,o,i,a){try{var l=t[i](a),s=l.value}catch(t){return void r(t)}l.done?e(s):Promise.resolve(s).then(n,o)}function b(t,e){return function(t){if(Array.isArray(t))return t}(t)||function(t,e){var r=null==t?null:"undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(null!=r){var n,o,i,a,l=[],s=!0,c=!1;try{if(i=(r=r.call(t)).next,0===e){if(Object(r)!==r)return;s=!1}else for(;!(s=(n=i.call(r)).done)&&(l.push(n.value),l.length!==e);s=!0);}catch(t){c=!0,o=t}finally{try{if(!s&&null!=r.return&&(a=r.return(),Object(a)!==a))return}finally{if(c)throw o}}return l}}(t,e)||j(t,e)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function j(t,e){if(t){if("string"==typeof t)return x(t,e);var r=Object.prototype.toString.call(t).slice(8,-1);return"Object"===r&&t.constructor&&(r=t.constructor.name),"Map"===r||"Set"===r?Array.from(t):"Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r)?x(t,e):void 0}}function x(t,e){(null==e||e>t.length)&&(e=t.length);for(var r=0,n=new Array(e);r<e;r++)n[r]=t[r];return n}function w(t){var e,r=t.onFilter,d=(0,s.v9)((function(t){return t.users})).users,y=(0,s.I0)(),j=b(n.useState(null),2),x=j[0],w=j[1],S=b(n.useState(null),2),N=S[0],O=S[1],E=b(n.useState("in progress"),2),L=E[0],P=E[1],k=b(n.useState(null),2),A=k[0],T=k[1],D=b(n.useState(null),2),V=D[0],M=D[1],I=b(n.useState(null),2),F=I[0],G=I[1],Y=b(n.useState(null),2),Z=Y[0],q=Y[1],B=null===(e=window)||void 0===e||null===(e=e.Laravel)||void 0===e?void 0:e.user,C=[1,6,4,8].includes(Number(null==B?void 0:B.role_id)),U=b((0,l.oA)("",{skip:d.length}),2),W=U[0],H=U[1].isFetching,z=(0,p.tO)(""),$=z.data,Q=z.isFetching;n.useEffect((function(){var t;d.length||H||(t=_().mark((function t(){var e;return _().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,W().unwrap();case 2:(e=t.sent)&&y((0,c.H)(e));case 4:case"end":return t.stop()}}),t)})),function(){var e=this,r=arguments;return new Promise((function(n,o){var i=t.apply(e,r);function a(t){g(i,n,o,a,l,"next",t)}function l(t){g(i,n,o,a,l,"throw",t)}a(void 0)}))})()}),[]),n.useEffect((function(){null!=d&&d.length&&(C||T(null==B?void 0:B.id))}),[d]);var R,J=n.useMemo((function(){return A}),[A]),K=n.useMemo((function(){return F}),[F]),X=n.useMemo((function(){return V}),[V]),tt=n.useMemo((function(){return L}),[L]),et=n.useMemo((function(){return Z}),[Z]),rt=n.useMemo((function(){return x}),[x]),nt=n.useMemo((function(){return N}),[N]);n.useEffect((function(){rt&&N&&r({start_date:f()(rt).format("YYYY-MM-DD"),end_date:f()(nt).format("YYYY-MM-DD"),employee_id:J,pm_id:X,client_id:K,status:tt,project_id:et?et.id:null})}),[K,J,X,tt,et,rt,nt]);return R=(0,m.jsxs)("div",{className:"d-flex flex-wrap bg-white p-1",children:[(0,m.jsx)("div",{className:"border-right pr-1",children:(0,m.jsx)(i.Z,{startDate:x,setStartDate:w,endDate:N,setEndDate:O,onApply:function(t,e){}})}),(0,m.jsx)(a.Z,{title:"Employee",items:d?v(null==d?void 0:d.filter((function(t){return C?t.role_id&&4!==Number(t.role_id):t.id===B.id}))):[],selected:A?null==d?void 0:d.find((function(t){return Number(t.id)===A})):null,isLoading:H,onSelect:function(t,e){t.preventDefault(),T(e?e.id:null)},selectedAllButton:C}),(0,m.jsx)(a.Z,{title:"Project Manager",items:d?v(null==d?void 0:d.filter((function(t){return t.role_id&&4===Number(t.role_id)}))):[],selected:V?null==d?void 0:d.find((function(t){return Number(t.id)===V})):null,isLoading:H,onSelect:function(t,e){t.preventDefault(),M(e?e.id:null)}}),(0,m.jsx)(a.Z,{title:"Client",items:d?v(null==d?void 0:d.filter((function(t){return!t.role_id}))):[],selected:F?null==d?void 0:d.find((function(t){return Number(t.id)===F})):null,isLoading:H,onSelect:function(t,e){t.preventDefault(),G(e?e.id:null)}}),(0,m.jsx)(h.Z,{title:"Project",items:$?v($):[],isLoading:Q,selected:Z,onSelect:function(t,e){q(e||null)}}),(0,m.jsx)(u.Z,{title:"Status",items:["finished","canceled","in progress","partially finished","under review"],selected:L,isLoading:!1,onSelect:function(t){P(t||null)}})]}),R?o.createPortal(R,document.getElementById("timeLogTableFilterBar")):null}},44934:(t,e,r)=>{r.r(e),r.d(e,{default:()=>Z});var n=r(67294),o=r(26231),i=r(6334),a=r(96486),l=r.n(a),s=r(85199),c=(r(29400),r(94272)),u=r(57046),d=r(85893),f=function(){return(0,d.jsxs)("div",{className:"d-flex align-items-center",style:{gap:"10px"},children:[(0,d.jsx)(u.V,{className:"sp1-item-center border rounded-circle",width:"36px",height:"36px"}),(0,d.jsxs)("div",{className:"",children:[(0,d.jsx)("h6",{className:"mb-2 f-14",children:(0,d.jsx)(u.V,{width:"130px"})}),(0,d.jsx)("span",{className:"f-12 text-hover-underline",children:(0,d.jsx)(u.V,{height:"10px",width:"80px"})})]})]})};const p=function(){return(0,d.jsxs)(n.Fragment,{children:[(0,d.jsxs)("tr",{className:"sp1_tlr_tr",children:[(0,d.jsxs)("td",{className:"sp1_tlr_td",rowSpan:3,children:[" ",(0,d.jsx)(u.V,{width:"250px"})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",rowSpan:3,children:[" ",(0,d.jsx)(u.V,{width:"200px"})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",rowSpan:3,children:[" ",(0,d.jsx)(f,{})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",rowSpan:3,children:[" ",(0,d.jsx)(f,{})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(f,{})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(u.V,{})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(u.V,{})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(u.V,{})," "]})]}),l().times(2,(function(t){return(0,d.jsxs)("tr",{className:"sp1_tlr_tr",children:[(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(f,{})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(u.V,{})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(u.V,{})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(u.V,{})," "]})]},t)})),l().times(2,(function(t){return(0,d.jsxs)("tr",{className:"sp1_tlr_tr",children:[(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(u.V,{width:"180px"})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(u.V,{width:"200px"})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(f,{})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(f,{})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(f,{})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(u.V,{})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(u.V,{})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(u.V,{})," "]})]},t)})),(0,d.jsxs)("tr",{className:"sp1_tlr_tr",children:[(0,d.jsxs)("td",{className:"sp1_tlr_td",rowSpan:2,children:[" ",(0,d.jsx)(u.V,{width:"180px"})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",rowSpan:2,children:[" ",(0,d.jsx)(u.V,{width:"200px"})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",rowSpan:2,children:[" ",(0,d.jsx)(f,{})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",rowSpan:2,children:[" ",(0,d.jsx)(f,{})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(f,{})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(u.V,{})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(u.V,{})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(u.V,{})," "]})]}),l().times(1,(function(t){return(0,d.jsxs)("tr",{className:"sp1_tlr_tr",children:[(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(f,{})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(u.V,{})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(u.V,{})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(u.V,{})," "]})]},t)})),l().times(3,(function(t){return(0,d.jsxs)("tr",{className:"sp1_tlr_tr",children:[(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(u.V,{width:"180px"})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(u.V,{width:"200px"})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(f,{})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(f,{})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(f,{})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(u.V,{})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(u.V,{})," "]}),(0,d.jsxs)("td",{className:"sp1_tlr_td",children:[" ",(0,d.jsx)(u.V,{})," "]})]},t)}))]})};var h=r(96906),m=r(90584);function y(t){return y="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},y(t)}function v(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}function g(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?v(Object(r),!0).forEach((function(e){b(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):v(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}function b(t,e,r){return(e=function(t){var e=function(t,e){if("object"!==y(t)||null===t)return t;var r=t[Symbol.toPrimitive];if(void 0!==r){var n=r.call(t,e||"default");if("object"!==y(n))return n;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===e?String:Number)(t)}(t,"string");return"symbol"===y(e)?e:String(e)}(e))in t?Object.defineProperty(t,e,{value:r,enumerable:!0,configurable:!0,writable:!0}):t[e]=r,t}function j(t){return function(t){if(Array.isArray(t))return S(t)}(t)||function(t){if("undefined"!=typeof Symbol&&null!=t[Symbol.iterator]||null!=t["@@iterator"])return Array.from(t)}(t)||w(t)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function x(t,e){return function(t){if(Array.isArray(t))return t}(t)||function(t,e){var r=null==t?null:"undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(null!=r){var n,o,i,a,l=[],s=!0,c=!1;try{if(i=(r=r.call(t)).next,0===e){if(Object(r)!==r)return;s=!1}else for(;!(s=(n=i.call(r)).done)&&(l.push(n.value),l.length!==e);s=!0);}catch(t){c=!0,o=t}finally{try{if(!s&&null!=r.return&&(a=r.return(),Object(a)!==a))return}finally{if(c)throw o}}return l}}(t,e)||w(t,e)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function w(t,e){if(t){if("string"==typeof t)return S(t,e);var r=Object.prototype.toString.call(t).slice(8,-1);return"Object"===r&&t.constructor&&(r=t.constructor.name),"Map"===r||"Set"===r?Array.from(t):"Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r)?S(t,e):void 0}}function S(t,e){(null==e||e>t.length)&&(e=t.length);for(var r=0,n=new Array(e);r<e;r++)n[r]=t[r];return n}const N=function(t){var e=t.data,r=t.columns,o=void 0===r?[]:r,i=t.tableName,a=void 0===i?"data-table":i,l=(t.sortBy,t.height),s=void 0===l?"calc(100vh - 100px)":l,u=t.onPaginate,f=t.perpageData,y=t.handlePerPageData,v=t.totalEntry,b=t.currentPage,S=t.isLoading,N=x((0,n.useState)([]),2),O=N[0],E=N[1],L=x((0,m.Z)(a),2),P=L[0],k=L[1];(0,n.useEffect)((function(){if(null!=P&&P.columnOrders)E(null==P?void 0:P.columnOrders);else{var t=_.map(o,"id");E(j(t))}}),[]);var A=_.sortBy(o,(function(t){return _.indexOf(O,t.id)}));return(0,d.jsx)(n.Fragment,{children:(0,d.jsxs)("div",{className:"p-3",children:[(0,d.jsx)("div",{className:"position-relative sp1_tlr_tbl_wrapper",style:{height:s},children:(0,d.jsxs)("table",{className:"sp1_tlr_table",children:[(0,d.jsx)("thead",{className:"sp1_tlr_thead",children:(0,d.jsx)("tr",{className:"sp1_tlr_tr",children:_.map(A,(function(t){return(0,d.jsx)(h.Z,{className:"sp1_tlr_th",column:t,columns:A,onSort:function(){},onDrop:E,order:O,tableName:a,storeOnLocalStore:function(t){return k(g(g({},P),{},{columnOrders:t}))}},t.id)}))})}),(0,d.jsxs)("tbody",{className:"sp1_tlr_tbody",children:[!S&&function(t){var e=[];if(t){var r,o=function(t,e){var r="undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(!r){if(Array.isArray(t)||(r=w(t))||e&&t&&"number"==typeof t.length){r&&(t=r);var n=0,o=function(){};return{s:o,n:function(){return n>=t.length?{done:!0}:{done:!1,value:t[n++]}},e:function(t){throw t},f:o}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var i,a=!0,l=!1;return{s:function(){r=r.call(t)},n:function(){var t=r.next();return a=t.done,t},e:function(t){l=!0,i=t},f:function(){try{a||null==r.return||r.return()}finally{if(l)throw i}}}}(t);try{var i=function(){var t=x(r.value,2),o=(t[0],t[1]);null==o||o.map((function(t,r){var i=o.length===r+1?"sp1_tlr_td f-14 sp1_tlr_td_border":"sp1_tlr_td f-14";e.push((0,d.jsx)(n.Fragment,{children:(0,d.jsx)("tr",{className:"sp1_tlr_tr",children:_.map(A,(function(e){return e.group?0===r&&(0,d.jsx)(n.Fragment,{children:e.cell({row:t,rowSpan:_.size(o)})},e.id):(0,d.jsx)(n.Fragment,{children:e.cell({row:t,className:"".concat(i," sp1_drag_col_").concat(null==e?void 0:e.id)})},e.id)}))})},t.start_time))}))};for(o.s();!(r=o.n()).done;)i()}catch(t){o.e(t)}finally{o.f()}}return e}(e),S&&(0,d.jsx)(p,{})]})]})}),(0,d.jsx)(c.Z,{onPaginate:u,perpageData:f,totalEntry:v,currentPage:b,handlePerPageData:y})]})})};var O=r(27484),E=r.n(O),L=r(83225),P=r(22695),k=[{id:"task_id",header:"Task",className:"",group:!0,sorted:!1,cell:function(t){var e=t.row,r=t.col,n=t.rowSpan,o=void 0===n?1:n;return(0,d.jsx)("td",{className:"sp1_tlr_td sp1_tlr_td_border sp1_drag_col_".concat(null==r?void 0:r.id," sp1_tlr_td_marged ").concat(o?"sp1_tlr_td_hover-disable":""),rowSpan:o,children:(0,d.jsx)("a",{href:"/account/tasks/".concat(null==e?void 0:e.task_id),children:null==e?void 0:e.task_name})})}},{id:"project_id",header:"Project Name",className:"",group:!0,sorted:!1,sortAccessor:"project_id",cell:function(t){var e=t.row,r=t.col,n=t.rowSpan,o=void 0===n?1:n;return(0,d.jsx)("td",{className:"sp1_tlr_td sp1_tlr_td_border sp1_drag_col_".concat(null==r?void 0:r.id," sp1_tlr_td_marged ").concat(o?"sp1_tlr_td_hover-disable":""),rowSpan:o,children:(0,d.jsx)("a",{href:"/account/tasks/".concat(null==e?void 0:e.task_id),children:null==e?void 0:e.project_name})})}},{id:"pm_id",header:"Project Manager",className:"",group:!0,sorted:!1,sortAccessor:"pm_id",cell:function(t){var e=t.row,r=t.col,n=t.rowSpan,o=void 0===n?1:n;return(0,d.jsx)("td",{className:"sp1_tlr_td sp1_tlr_td_border sp1_drag_col_".concat(null==r?void 0:r.id," sp1_tlr_td_marged ").concat(o?"sp1_tlr_td_hover-disable":""),rowSpan:o,children:(0,d.jsx)(L.Z,{name:null==e?void 0:e.pm_name,profileUrl:"/account/employees/".concat(null==e?void 0:e.pm_id),image:null==e?void 0:e.pm_image,role:null==e?void 0:e.pm_roles,id:null==e?void 0:e.pm_id})})}},{id:"client_id",header:"Client Name",className:"",group:!0,sorted:!1,sortAccessor:"client_id",cell:function(t){var e=t.row,r=t.col,n=t.rowSpan,o=void 0===n?1:n;return(0,d.jsx)("td",{className:"sp1_tlr_td sp1_tlr_td_border  sp1_drag_col_".concat(null==r?void 0:r.id," sp1_tlr_td_marged ").concat(o?"sp1_tlr_td_hover-disable":""),rowSpan:o,children:(0,d.jsx)(L.Z,{name:null==e?void 0:e.client_name,profileUrl:"/account/clients/".concat(null==e?void 0:e.client_id),image:null==e?void 0:e.client_image,role:"Freelancer.com",roleLink:null==e?void 0:e.client_from,id:null==e?void 0:e.client_id})})}},{id:"employee_id",header:"Employee Name",className:"",sorted:!0,group:!1,sortAccessor:"",cell:function(t){var e=t.row,r=(t.col,t.className),n=t.rowSpan;return(0,d.jsx)("td",{className:r,rowSpan:n,children:(0,d.jsx)(L.Z,{name:null==e?void 0:e.employee_name,profileUrl:"/account/employees/".concat(null==e?void 0:e.employee_id),image:null==e?void 0:e.employee_image,role:null==e?void 0:e.employee_roles,id:null==e?void 0:e.employee_id})})}},{id:"start_time",header:"Start Time",className:"",sorted:!1,group:!1,cell:function(t){var e=t.row,r=t.className;return(0,d.jsxs)("td",{className:r,children:[E()(null==e?void 0:e.start_time).format("MMM DD, YYYY")," ",(0,d.jsx)("br",{}),"at ",E()(null==e?void 0:e.start_time).format("hh:mm A")]})}},{id:"end_time",header:"End Time",className:"",sorted:!1,group:!1,cell:function(t){var e=t.row,r=t.className;return(0,d.jsx)("td",{className:r,children:null!=e&&e.end_time?E()(null==e?void 0:e.end_time).format("MMM DD, YYYY [at] hh:mm A"):(0,d.jsx)("span",{className:"text-success",children:"Active"})})}},{id:"total_track_time",header:"Total Track Time",className:"",sorted:!1,group:!1,cell:function(t){var e=t.row,r=t.className;return(0,d.jsx)("td",{className:r,children:null!=e&&e.total_minutes?(0,P.r)(null==e?void 0:e.total_minutes):(0,d.jsxs)("span",{className:"text-danger",children:[(0,d.jsx)("i",{className:"fa-solid fa-chevron-left mr-1",style:{fontSize:"10px"}}),"1 min"]})})}}],A=r(84468),T=r(45e3);function D(t){return D="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},D(t)}function V(){V=function(){return e};var t,e={},r=Object.prototype,n=r.hasOwnProperty,o=Object.defineProperty||function(t,e,r){t[e]=r.value},i="function"==typeof Symbol?Symbol:{},a=i.iterator||"@@iterator",l=i.asyncIterator||"@@asyncIterator",s=i.toStringTag||"@@toStringTag";function c(t,e,r){return Object.defineProperty(t,e,{value:r,enumerable:!0,configurable:!0,writable:!0}),t[e]}try{c({},"")}catch(t){c=function(t,e,r){return t[e]=r}}function u(t,e,r,n){var i=e&&e.prototype instanceof v?e:v,a=Object.create(i.prototype),l=new k(n||[]);return o(a,"_invoke",{value:O(t,r,l)}),a}function d(t,e,r){try{return{type:"normal",arg:t.call(e,r)}}catch(t){return{type:"throw",arg:t}}}e.wrap=u;var f="suspendedStart",p="suspendedYield",h="executing",m="completed",y={};function v(){}function _(){}function g(){}var b={};c(b,a,(function(){return this}));var j=Object.getPrototypeOf,x=j&&j(j(A([])));x&&x!==r&&n.call(x,a)&&(b=x);var w=g.prototype=v.prototype=Object.create(b);function S(t){["next","throw","return"].forEach((function(e){c(t,e,(function(t){return this._invoke(e,t)}))}))}function N(t,e){function r(o,i,a,l){var s=d(t[o],t,i);if("throw"!==s.type){var c=s.arg,u=c.value;return u&&"object"==D(u)&&n.call(u,"__await")?e.resolve(u.__await).then((function(t){r("next",t,a,l)}),(function(t){r("throw",t,a,l)})):e.resolve(u).then((function(t){c.value=t,a(c)}),(function(t){return r("throw",t,a,l)}))}l(s.arg)}var i;o(this,"_invoke",{value:function(t,n){function o(){return new e((function(e,o){r(t,n,e,o)}))}return i=i?i.then(o,o):o()}})}function O(e,r,n){var o=f;return function(i,a){if(o===h)throw new Error("Generator is already running");if(o===m){if("throw"===i)throw a;return{value:t,done:!0}}for(n.method=i,n.arg=a;;){var l=n.delegate;if(l){var s=E(l,n);if(s){if(s===y)continue;return s}}if("next"===n.method)n.sent=n._sent=n.arg;else if("throw"===n.method){if(o===f)throw o=m,n.arg;n.dispatchException(n.arg)}else"return"===n.method&&n.abrupt("return",n.arg);o=h;var c=d(e,r,n);if("normal"===c.type){if(o=n.done?m:p,c.arg===y)continue;return{value:c.arg,done:n.done}}"throw"===c.type&&(o=m,n.method="throw",n.arg=c.arg)}}}function E(e,r){var n=r.method,o=e.iterator[n];if(o===t)return r.delegate=null,"throw"===n&&e.iterator.return&&(r.method="return",r.arg=t,E(e,r),"throw"===r.method)||"return"!==n&&(r.method="throw",r.arg=new TypeError("The iterator does not provide a '"+n+"' method")),y;var i=d(o,e.iterator,r.arg);if("throw"===i.type)return r.method="throw",r.arg=i.arg,r.delegate=null,y;var a=i.arg;return a?a.done?(r[e.resultName]=a.value,r.next=e.nextLoc,"return"!==r.method&&(r.method="next",r.arg=t),r.delegate=null,y):a:(r.method="throw",r.arg=new TypeError("iterator result is not an object"),r.delegate=null,y)}function L(t){var e={tryLoc:t[0]};1 in t&&(e.catchLoc=t[1]),2 in t&&(e.finallyLoc=t[2],e.afterLoc=t[3]),this.tryEntries.push(e)}function P(t){var e=t.completion||{};e.type="normal",delete e.arg,t.completion=e}function k(t){this.tryEntries=[{tryLoc:"root"}],t.forEach(L,this),this.reset(!0)}function A(e){if(e||""===e){var r=e[a];if(r)return r.call(e);if("function"==typeof e.next)return e;if(!isNaN(e.length)){var o=-1,i=function r(){for(;++o<e.length;)if(n.call(e,o))return r.value=e[o],r.done=!1,r;return r.value=t,r.done=!0,r};return i.next=i}}throw new TypeError(D(e)+" is not iterable")}return _.prototype=g,o(w,"constructor",{value:g,configurable:!0}),o(g,"constructor",{value:_,configurable:!0}),_.displayName=c(g,s,"GeneratorFunction"),e.isGeneratorFunction=function(t){var e="function"==typeof t&&t.constructor;return!!e&&(e===_||"GeneratorFunction"===(e.displayName||e.name))},e.mark=function(t){return Object.setPrototypeOf?Object.setPrototypeOf(t,g):(t.__proto__=g,c(t,s,"GeneratorFunction")),t.prototype=Object.create(w),t},e.awrap=function(t){return{__await:t}},S(N.prototype),c(N.prototype,l,(function(){return this})),e.AsyncIterator=N,e.async=function(t,r,n,o,i){void 0===i&&(i=Promise);var a=new N(u(t,r,n,o),i);return e.isGeneratorFunction(r)?a:a.next().then((function(t){return t.done?t.value:a.next()}))},S(w),c(w,s,"Generator"),c(w,a,(function(){return this})),c(w,"toString",(function(){return"[object Generator]"})),e.keys=function(t){var e=Object(t),r=[];for(var n in e)r.push(n);return r.reverse(),function t(){for(;r.length;){var n=r.pop();if(n in e)return t.value=n,t.done=!1,t}return t.done=!0,t}},e.values=A,k.prototype={constructor:k,reset:function(e){if(this.prev=0,this.next=0,this.sent=this._sent=t,this.done=!1,this.delegate=null,this.method="next",this.arg=t,this.tryEntries.forEach(P),!e)for(var r in this)"t"===r.charAt(0)&&n.call(this,r)&&!isNaN(+r.slice(1))&&(this[r]=t)},stop:function(){this.done=!0;var t=this.tryEntries[0].completion;if("throw"===t.type)throw t.arg;return this.rval},dispatchException:function(e){if(this.done)throw e;var r=this;function o(n,o){return l.type="throw",l.arg=e,r.next=n,o&&(r.method="next",r.arg=t),!!o}for(var i=this.tryEntries.length-1;i>=0;--i){var a=this.tryEntries[i],l=a.completion;if("root"===a.tryLoc)return o("end");if(a.tryLoc<=this.prev){var s=n.call(a,"catchLoc"),c=n.call(a,"finallyLoc");if(s&&c){if(this.prev<a.catchLoc)return o(a.catchLoc,!0);if(this.prev<a.finallyLoc)return o(a.finallyLoc)}else if(s){if(this.prev<a.catchLoc)return o(a.catchLoc,!0)}else{if(!c)throw new Error("try statement without catch or finally");if(this.prev<a.finallyLoc)return o(a.finallyLoc)}}}},abrupt:function(t,e){for(var r=this.tryEntries.length-1;r>=0;--r){var o=this.tryEntries[r];if(o.tryLoc<=this.prev&&n.call(o,"finallyLoc")&&this.prev<o.finallyLoc){var i=o;break}}i&&("break"===t||"continue"===t)&&i.tryLoc<=e&&e<=i.finallyLoc&&(i=null);var a=i?i.completion:{};return a.type=t,a.arg=e,i?(this.method="next",this.next=i.finallyLoc,y):this.complete(a)},complete:function(t,e){if("throw"===t.type)throw t.arg;return"break"===t.type||"continue"===t.type?this.next=t.arg:"return"===t.type?(this.rval=this.arg=t.arg,this.method="return",this.next="end"):"normal"===t.type&&e&&(this.next=e),y},finish:function(t){for(var e=this.tryEntries.length-1;e>=0;--e){var r=this.tryEntries[e];if(r.finallyLoc===t)return this.complete(r.completion,r.afterLoc),P(r),y}},catch:function(t){for(var e=this.tryEntries.length-1;e>=0;--e){var r=this.tryEntries[e];if(r.tryLoc===t){var n=r.completion;if("throw"===n.type){var o=n.arg;P(r)}return o}}throw new Error("illegal catch attempt")},delegateYield:function(e,r,n){return this.delegate={iterator:A(e),resultName:r,nextLoc:n},"next"===this.method&&(this.arg=t),y}},e}function M(t,e,r,n,o,i,a){try{var l=t[i](a),s=l.value}catch(t){return void r(t)}l.done?e(s):Promise.resolve(s).then(n,o)}function I(t){return function(t){if(Array.isArray(t))return Y(t)}(t)||function(t){if("undefined"!=typeof Symbol&&null!=t[Symbol.iterator]||null!=t["@@iterator"])return Array.from(t)}(t)||G(t)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function F(t,e){return function(t){if(Array.isArray(t))return t}(t)||function(t,e){var r=null==t?null:"undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(null!=r){var n,o,i,a,l=[],s=!0,c=!1;try{if(i=(r=r.call(t)).next,0===e){if(Object(r)!==r)return;s=!1}else for(;!(s=(n=i.call(r)).done)&&(l.push(n.value),l.length!==e);s=!0);}catch(t){c=!0,o=t}finally{try{if(!s&&null!=r.return&&(a=r.return(),Object(a)!==a))return}finally{if(c)throw o}}return l}}(t,e)||G(t,e)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function G(t,e){if(t){if("string"==typeof t)return Y(t,e);var r=Object.prototype.toString.call(t).slice(8,-1);return"Object"===r&&t.constructor&&(r=t.constructor.name),"Map"===r||"Set"===r?Array.from(t):"Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r)?Y(t,e):void 0}}function Y(t,e){(null==e||e>t.length)&&(e=t.length);for(var r=0,n=new Array(e);r<e;r++)n[r]=t[r];return n}const Z=function(){var t=F((0,n.useState)([]),2),e=t[0],r=t[1],l=F((0,n.useState)(10),2),c=l[0],u=l[1],f=F((0,n.useState)(1),2),p=f[0],h=f[1],m=F((0,n.useState)(null),2),y=m[0],v=m[1],g=F((0,n.useState)([]),2),b=(g[0],g[1],F((0,n.useState)(0),2)),j=b[0],x=b[1],w=F((0,n.useState)(null),2),S=w[0],O=w[1],E=F((0,o.P4)(),2),L=E[0],D=E[1].isLoading,G=function(t,e,r){var n=(0,i.V)(t,e,r),o=(0,a.groupBy)(n,"task_id"),l=Object.entries(o).sort((function(t,e){var r=F(t,1)[0];return F(e,1)[0]-r}));v(I(l))},Y=function(){var t,e=(t=V().mark((function t(e){return V().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return O(e),t.next=3,L(e).unwrap().then((function(t){h(1);var e=(0,a.orderBy)(null==t?void 0:t.data,["task_id"],["desc"]);G(e,1,c),r(e);var n=_.sumBy(e,(function(t){return Number(t.total_minutes)}));x(n)}));case 3:case"end":return t.stop()}}),t)})),function(){var e=this,r=arguments;return new Promise((function(n,o){var i=t.apply(e,r);function a(t){M(i,n,o,a,l,"next",t)}function l(t){M(i,n,o,a,l,"throw",t)}a(void 0)}))});return function(t){return e.apply(this,arguments)}}();return(0,d.jsxs)("div",{className:"sp1_tlr_container",children:[(0,d.jsx)(A.Z,{onFilter:Y}),(0,d.jsxs)("div",{className:"sp1_tlr_tbl_container",children:[(0,d.jsxs)("div",{className:"mb-2",children:[(0,d.jsx)(s.Z,{}),(0,d.jsx)(T.Z,{onClick:function(){Y(S)},children:"Refresh"})]}),(0,d.jsx)("div",{className:" w-100 d-flex flex-wrap justify-center align-items-center",style:{gap:"10px"},children:(0,d.jsxs)("span",{className:"mx-auto",children:["Total Tracked Time: ",(0,d.jsx)("strong",{children:(0,P.r)(j)})]})}),(0,d.jsx)(N,{data:y,columns:k,tableName:"task_timelog_report",onSort:function(t){var r=a.orderBy.apply(void 0,[e].concat(I(t)));G(r,p,c)},height:"calc(100vh - 370px)",onPaginate:function(t){h(t),G(e,t,c)},perpageData:c,handlePerPageData:function(t){u(t),G(e,p,t)},currentPage:p,totalEntry:e.length,isLoading:D})]})]})}},26231:(t,e,r)=>{function n(t){return n="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},n(t)}function o(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}function i(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?o(Object(r),!0).forEach((function(e){a(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):o(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}function a(t,e,r){return(e=function(t){var e=function(t,e){if("object"!==n(t)||null===t)return t;var r=t[Symbol.toPrimitive];if(void 0!==r){var o=r.call(t,e||"default");if("object"!==n(o))return o;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===e?String:Number)(t)}(t,"string");return"symbol"===n(e)?e:String(e)}(e))in t?Object.defineProperty(t,e,{value:r,enumerable:!0,configurable:!0,writable:!0}):t[e]=r,t}r.d(e,{OU:()=>s,P4:()=>c,Tf:()=>f,_6:()=>u,t4:()=>d});var l=r(37036).g.injectEndpoints({endpoints:function(t){return{getEmployeeWiseData:t.mutation({query:function(t){return{url:"/get-timelogs/employees",method:"POST",body:i(i({},t),{},{_token:document.querySelector("meta[name='csrf-token']").getAttribute("content")})}}}),getProjectWiseData:t.mutation({query:function(t){return{url:"/get-timelogs/projects",method:"POST",body:i(i({},t),{},{_token:document.querySelector("meta[name='csrf-token']").getAttribute("content")})}}}),getTaskWiseData:t.mutation({query:function(t){return{url:"/get-timelogs/tasks",method:"POST",body:i(i({},t),{},{_token:document.querySelector("meta[name='csrf-token']").getAttribute("content")})}}}),getTimeLogHistory:t.mutation({query:function(t){return{url:"/get-timelogs/time_log_history",method:"POST",body:i(i({},t),{},{_token:document.querySelector("meta[name='csrf-token']").getAttribute("content")})}}}),getTimeLogHistoryDetails:t.query({query:function(t){return"/account/tasks/developer-task-history/".concat(t)}})}}}),s=l.useGetEmployeeWiseDataMutation,c=l.useGetTaskWiseDataMutation,u=l.useGetProjectWiseDataMutation,d=l.useGetTimeLogHistoryMutation,f=l.useLazyGetTimeLogHistoryDetailsQuery}}]);
//# sourceMappingURL=934.js.map