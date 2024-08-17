import{_ as h}from"./layout-guest-5a4f6299.js";import{d as V,T as b,o as p,e as v,w as a,g as m,h as s,c as x,t as B,i as k,a as r,u as o,j as $,Z as C,k as E}from"./app-97dd6c0a.js";import{_ as I}from"./base-button.vue_vue_type_script_setup_true_lang-4a31ead5.js";import{_ as L}from"./input-password.vue_vue_type_script_setup_true_lang-a93f8bab.js";import{_ as N,a as P}from"./input-label.vue_vue_type_script_setup_true_lang-82236da6.js";import{_ as q}from"./input-text.vue_vue_type_script_setup_true_lang-1b53ec83.js";import"./logo-dc549b18.js";import"./_plugin-vue_export-helper-c27b6911.js";import"./icon-close-88e52529.js";import"./use-input-size-f8e233a3.js";const S={key:0,class:"mb-4 text-sm font-medium text-green-600"},T={class:"space-y-1"},j={class:"space-y-1"},F={class:"mt-8 flex w-full justify-center"},O=V({__name:"login",props:{canResetPassword:{type:Boolean},status:{}},setup(U){const e=b({email:"",password:"",remember:!1}),d=()=>{e.post(route("ltu.login.attempt"),{onFinish:()=>{e.reset("password")}})};return(n,t)=>{const c=C,i=N,_=q,u=P,f=L,g=I,w=E,y=h;return p(),v(y,null,{title:a(()=>[m(" Sign in to your account")]),default:a(()=>[s(c,{title:"Log in"}),n.status?(p(),x("div",S,B(n.status),1)):k("",!0),r("form",{class:"space-y-6",onSubmit:$(d,["prevent"])},[r("div",T,[s(i,{for:"email",value:"Email Address",class:"sr-only"}),s(_,{id:"email",modelValue:o(e).email,"onUpdate:modelValue":t[0]||(t[0]=l=>o(e).email=l),error:o(e).errors.email,type:"email",required:"",autofocus:"",placeholder:"Email address",autocomplete:"username",class:"bg-gray-50"},null,8,["modelValue","error"]),s(u,{message:o(e).errors.email},null,8,["message"])]),r("div",j,[s(i,{for:"password",value:"Password",class:"sr-only"}),s(f,{id:"password",modelValue:o(e).password,"onUpdate:modelValue":t[1]||(t[1]=l=>o(e).password=l),error:o(e).errors.password,required:"",autocomplete:"current-password",placeholder:"Password",class:"bg-gray-50"},null,8,["modelValue","error"]),s(u,{message:o(e).errors.password},null,8,["message"])]),s(g,{type:"submit",size:"lg",variant:"secondary","is-loading":o(e).processing,"full-width":""},{default:a(()=>[m(" Continue ")]),_:1},8,["is-loading"])],32),r("div",F,[s(w,{href:n.route("ltu.password.request"),class:"text-xs font-medium text-gray-500 hover:text-blue-500"},{default:a(()=>[m(" Forgot your password? ")]),_:1},8,["href"])])]),_:1})}}});export{O as default};
