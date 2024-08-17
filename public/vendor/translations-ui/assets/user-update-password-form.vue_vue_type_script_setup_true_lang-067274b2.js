import{_ as v}from"./base-button.vue_vue_type_script_setup_true_lang-4a31ead5.js";import{_ as g,a as V}from"./input-label.vue_vue_type_script_setup_true_lang-82236da6.js";import{_ as x}from"./input-text.vue_vue_type_script_setup_true_lang-1b53ec83.js";import{d as I,l as i,T as P,o as m,c as _,a,h as r,u as e,w,g as h,i as S,J as k,j as B}from"./app-97dd6c0a.js";const C=a("header",null,[a("h2",{class:"text-lg font-medium text-gray-900"},"Update Password"),a("p",{class:"mt-1 text-sm text-gray-600"},"Ensure your account is using a long, random password to stay secure.")],-1),N={class:"space-y-1"},E={class:"space-y-1"},T={class:"space-y-1"},U={class:"flex items-center gap-4"},$={key:0,class:"text-sm text-gray-600"},M=I({__name:"user-update-password-form",setup(b){const u=i(null),l=i(null),s=P({current_password:"",password:"",password_confirmation:""}),f=()=>{s.put(route("ltu.profile.password.update"),{preserveScroll:!0,onSuccess:()=>{s.reset()},onError:()=>{var n,o;s.errors.password&&(s.reset("password","password_confirmation"),(n=u.value)==null||n.focus()),s.errors.current_password&&(s.reset("current_password"),(o=l.value)==null||o.focus())}})};return(n,o)=>{const d=g,p=x,c=V,y=v;return m(),_("section",null,[C,a("form",{class:"mt-6 space-y-6",onSubmit:B(f,["prevent"])},[a("div",N,[r(d,{for:"current_password",value:"Current Password"}),r(p,{id:"current_password",ref_key:"currentPasswordInput",ref:l,modelValue:e(s).current_password,"onUpdate:modelValue":o[0]||(o[0]=t=>e(s).current_password=t),error:e(s).errors.current_password,type:"password",autocomplete:"current-password"},null,8,["modelValue","error"]),r(c,{message:e(s).errors.current_password},null,8,["message"])]),a("div",E,[r(d,{for:"password",value:"New Password"}),r(p,{id:"password",ref_key:"passwordInput",ref:u,modelValue:e(s).password,"onUpdate:modelValue":o[1]||(o[1]=t=>e(s).password=t),error:e(s).errors.password,type:"password",autocomplete:"new-password"},null,8,["modelValue","error"]),r(c,{message:e(s).errors.password},null,8,["message"])]),a("div",T,[r(d,{for:"password_confirmation",value:"Confirm Password"}),r(p,{id:"password_confirmation",modelValue:e(s).password_confirmation,"onUpdate:modelValue":o[2]||(o[2]=t=>e(s).password_confirmation=t),type:"password",autocomplete:"new-password"},null,8,["modelValue"]),r(c,{message:e(s).errors.password_confirmation},null,8,["message"])]),a("div",U,[r(y,{type:"submit",size:"md",variant:"primary","is-loading":e(s).processing},{default:w(()=>[h(" Save ")]),_:1},8,["is-loading"]),r(k,{"enter-active-class":"transition ease-in-out","enter-from-class":"opacity-0","leave-active-class":"transition ease-in-out","leave-to-class":"opacity-0"},{default:w(()=>[e(s).recentlySuccessful?(m(),_("p",$,"Saved.")):S("",!0)]),_:1})])],32)])}}});export{M as _};
