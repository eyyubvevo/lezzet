import{_ as A}from"./icon-pencil-28d027f4.js";import{o as c,c as i,a as p,d as x,x as N,n as M,t as k,F as R,h as w,e as $,u as T,K as z,L,M as K,N as U,l as g,P as V,Q as D,b as P,E as b,R as G,A as q,S as H,m as j,v as F,U as W}from"./app-97dd6c0a.js";import{_ as O}from"./_plugin-vue_export-helper-c27b6911.js";function J(t,e){return c(),i("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor","aria-hidden":"true","data-slot":"icon"},[p("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M15.182 16.318A4.486 4.486 0 0 0 12.016 15a4.486 4.486 0 0 0-3.198 1.318M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Z"})])}const Y=["onClick"],Q=x({__name:"phrase-with-parameters",props:{copyable:{type:Boolean},phrase:{}},setup(t){function e(l){const s=document.getElementById("textArea"),a=s.scrollTop;let u=0;const r=s.selectionStart!=null?"ff":document.selection?"ie":!1;function h(){return r==="ie"?document.selection.createRange():document.createRange()}function n(d){if(r==="ie"){const m=h();m.moveStart("character",-s.value.length),m.moveStart("character",d),m.moveEnd("character",0),m.select()}else r==="ff"&&(s.selectionStart=d,s.selectionEnd=d,s.focus())}if(r==="ie"){s.focus();const d=h();d.moveStart("character",-s.value.length),u=d.text.length}else r==="ff"&&(u=s.selectionStart);const f=s.value.substring(0,u),y=s.value.substring(u,s.value.length);s.value=f+l+y,u=u+l.length,n(u),s.scrollTop=a}return(l,s)=>(c(!0),i(R,null,N(l.phrase,a=>(c(),i("div",{key:a.value,class:M(["flex items-center",{"text-gray-600":!a.parameter,"cursor-pointer":a.parameter&&l.copyable,"rounded bg-blue-50 px-1 py-px text-sm text-blue-600 hover:bg-blue-100":a.parameter}]),onClick:u=>l.copyable&&a.parameter&&e(a.value)},k(a.value),11,Y))),128))}}),X={class:"flex w-full flex-row divide-x"},ee={class:"mb-2 flex w-48 items-start px-4 py-3 md:w-80 xl:w-96"},te={class:"flex w-full max-w-max overflow-hidden truncate rounded-md border"},se=["textContent"],ae={class:"flex flex-1 items-center px-3 py-2 text-base"},ne={key:0,class:"flex flex-wrap items-center gap-1"},oe={key:1,class:"flex text-gray-600"},re=["href"],le=x({__name:"similar-phrases-item",props:{phrase:{}},setup(t){return(e,l)=>{var u,r,h,n;const s=Q,a=A;return c(),i("div",X,[p("div",ee,[p("div",te,[p("span",{class:"flex cursor-pointer bg-white px-1 py-px text-sm text-gray-700 hover:bg-blue-50",textContent:k(e.phrase.key)},null,8,se)])]),p("div",ae,[(u=e.phrase)!=null&&u.value_html.length&&((h=(r=e.phrase)==null?void 0:r.value_html[0])!=null&&h.value)?(c(),i("div",ne,[w(s,{phrase:e.phrase.value_html},null,8,["phrase"])])):(c(),i("div",oe,k(((n=e.phrase)==null?void 0:n.value)??"Not translated yet..."),1))]),p("a",{href:e.route("ltu.source_translation.edit",e.phrase.uuid),target:"_blank",class:"transition-color relative flex w-14 cursor-pointer items-center justify-center text-gray-400 duration-100 hover:bg-blue-100 hover:text-blue-600"},[w(a,{class:"inline-block size-5"})],8,re)])}}}),ue={key:0,class:"flex flex-col divide-y"},ce={key:1,class:"relative flex size-full min-h-[250px]"},ie={class:"absolute left-0 top-0 flex min-h-full w-full flex-col items-center justify-center backdrop-blur-sm"},de=p("span",{class:"mt-4 text-gray-500"},"No similar phrases found...",-1),Me=x({__name:"similar-phrases",props:{similarPhrases:{}},setup(t){return(e,l)=>{const s=le;return e.similarPhrases.data.length>0?(c(),i("div",ue,[(c(!0),i(R,null,N(e.similarPhrases.data,a=>(c(),$(s,{key:a.uuid,phrase:a},null,8,["phrase"]))),128))])):(c(),i("div",ce,[p("div",ie,[w(T(J),{class:"size-12 text-gray-200"}),de])]))}}}),pe={},he={xmlns:"http://www.w3.org/2000/svg",viewBox:"0 -960 960 960",fill:"currentColor"},fe=p("path",{d:"M560-131v-82q90-26 145-100t55-168q0-94-55-168T560-749v-82q124 28 202 125.5T840-481q0 127-78 224.5T560-131ZM120-360v-240h160l200-200v640L280-360H120Zm440 40v-322q47 22 73.5 66t26.5 96q0 51-26.5 94.5T560-320ZM400-606l-86 86H200v80h114l86 86v-252ZM300-480Z"},null,-1),me=[fe];function ve(t,e){return c(),i("svg",he,me)}const Re=O(pe,[["render",ve]]);function _e(t){return z()?(V(t),!0):!1}function _(t){return typeof t=="function"?t():T(t)}const ge=typeof window<"u"&&typeof document<"u";typeof WorkerGlobalScope<"u"&&globalThis instanceof WorkerGlobalScope;const xe=()=>{};function I(...t){if(t.length!==1)return L(...t);const e=t[0];return typeof e=="function"?K(U(()=>({get:e,set:xe}))):g(e)}const ye=ge?window:void 0;function be(){const t=g(!1);return G()&&q(()=>{t.value=!0}),t}function ke(t){const e=be();return P(()=>(e.value,!!t()))}function Te(t,e={}){const{pitch:l=1,rate:s=1,volume:a=1,window:u=ye}=e,r=u&&u.speechSynthesis,h=ke(()=>r),n=g(!1),f=g("init"),y=I(t||""),d=I(e.lang||"en-US"),m=D(void 0),Z=(o=!n.value)=>{n.value=o},S=o=>{o.lang=_(d),o.voice=_(e.voice)||null,o.pitch=_(l),o.rate=_(s),o.volume=a,o.onstart=()=>{n.value=!0,f.value="play"},o.onpause=()=>{n.value=!1,f.value="pause"},o.onresume=()=>{n.value=!0,f.value="play"},o.onend=()=>{n.value=!1,f.value="end"},o.onerror=B=>{m.value=B}},v=P(()=>{n.value=!1,f.value="init";const o=new SpeechSynthesisUtterance(y.value);return S(o),o}),C=()=>{r.cancel(),v&&r.speak(v.value)},E=()=>{r.cancel(),n.value=!1};return h.value&&(S(v.value),b(d,o=>{v.value&&!n.value&&(v.value.lang=o)}),e.voice&&b(e.voice,()=>{r.cancel()}),b(n,()=>{n.value?r.resume():r.pause()})),_e(()=>{n.value=!1}),{isSupported:h,isPlaying:n,status:f,utterance:v,error:m,stop:E,toggle:Z,speak:C}}function Pe(){const t={af:"af-ZA",am:"am-ET",ar:"ar-SA",az:"az-AZ",be:"be-BY",bg:"bg-BG",bn:"bn-BD",bs:"bs-BA",ca:"ca-ES",ceb:"ceb-PH",cs:"cs-CZ",cy:"cy-GB",da:"da-DK",de:"de-DE",el:"el-GR",en:"en-US",es:"es-ES",et:"et-EE",eu:"eu-ES",fa:"fa-IR",fi:"fi-FI",fil:"fil",fr:"fr-FR",ga:"ga-IE",gl:"gl-ES",gu:"gu-IN",ha:"ha-NG",he:"he-IL",hi:"hi-IN",hr:"hr-HR",ht:"ht-HT",hu:"hu-HU",hy:"hy-AM",id:"id-ID",ig:"ig-NG",is:"is-IS",it:"it-IT",ja:"ja-JP",jv:"jv-ID",ka:"ka-GE",kk:"kk-KZ",km:"km-KH",kn:"kn-IN",ko:"ko-KR",ku:"ku-TR",ky:"ky-KG",lo:"lo-LA",lt:"lt-LT",lv:"lv-LV",mk:"mk-MK",ml:"ml-IN",mn:"mn-MN",mr:"mr-IN",ms:"ms-MY",mt:"mt-MT",ne:"ne-NP",nl:"nl-NL",no:"nb-NO",pa:"pa-IN",pl:"pl-PL",ps:"ps-AF",pt:"pt-BR","pt-br":"pt-BR",ro:"ro-RO",ru:"ru-RU",sd:"sd-PK",si:"si-LK",sk:"sk-SK",sl:"sl-SI",so:"so-SO",sq:"sq-AL",sr:"sr-RS",st:"st-ZA",su:"su-ID",sv:"sv-SE",sw:"sw-TZ",ta:"ta-IN",te:"te-IN",tg:"tg-TJ",th:"th-TH",tr:"tr-TR",uk:"uk-UA",ur:"ur-PK",uz:"uz-UZ",vi:"vi-VN",xh:"xh-ZA",yo:"yo-NG",zh:"zh-CN","zh-tw":"zh-TW",zu:"zu-ZA"};function e(l){return t[l]||null}return{convertLanguageCode:e}}const we=["rows"],Ze=x({__name:"input-textarea",props:H({error:{},rows:{}},{modelValue:{}}),emits:["update:modelValue"],setup(t){const e=j(t,"modelValue");return(l,s)=>F((c(),i("textarea",{"onUpdate:modelValue":s[0]||(s[0]=a=>e.value=a),rows:l.rows??5,class:M(["w-full rounded-md border-gray-300 shadow-sm placeholder:text-sm placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500",{"border-red-300 text-red-900 placeholder:text-red-300 focus:border-red-500 focus:ring-red-500":l.error}])},null,10,we)),[[W,e.value]])}});export{Re as _,Pe as a,Q as b,Ze as c,Me as d,J as r,Te as u};
