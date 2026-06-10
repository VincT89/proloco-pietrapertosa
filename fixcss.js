let fs = require('fs');
let css = fs.readFileSync('./client/app/globals.css', 'utf8');
css = css.replace(/font-size:clamp\(3rem,9\.8vw,8\.6rem\)/, 'font-size:clamp(3rem,8vw,8.6rem)');
css = css.replace(/font-size:clamp\(1\.8rem,4vw,2\.4rem\)/, 'font-size:clamp(1.8rem,3vw,2.4rem)');
css = css.replace(/\.f-big\{[^\}]+\}/, '.f-big{font-family:var(--font-cormorant),serif;font-size:clamp(1rem,6vw,6.5rem);line-height:.95;color:transparent;-webkit-text-stroke:1px rgba(217,170,99,.4);text-align:center;white-space:nowrap;user-select:none;margin-bottom:clamp(24px,4vw,50px);max-width:100%;overflow:hidden}');
css = css.replace(/footer\{padding:clamp[^\}]+\}/, 'footer{padding:clamp(40px,5vw,70px) clamp(20px,4.5vw,64px) 30px;border-top:1px solid var(--hair);overflow:hidden}');
css = css.replace(/body\.mo \.links\{[^\}]+\}/, 'body.mo .links{position:fixed;top:0;right:0;width:80%;max-width:320px;height:100svh;background:var(--ink);display:flex;flex-direction:column;justify-content:flex-start;padding:100px 30px 40px;transform:translateX(100%);transition:transform .6s var(--ease);border-left:1px solid rgba(244,239,229,.1);z-index:90;overflow-y:auto}');
css = css.replace(/body\.mo \.links \.lnk\{[^\}]+\}/, 'body.mo .links .lnk{font-size:1.4rem;padding:12px 0;width:100%;text-align:left;border-bottom:1px solid rgba(244,239,229,.05)}');
css = css.replace(/body\.mo\.menu-on \.links\{[^\}]+\}/, 'body.mo.menu-on .links{transform:translateX(0)}');
if(!css.includes('.event-desc p')) { css += '\n.event-desc p { margin: 0; padding: 0 40px; }\n'; }
fs.writeFileSync('./client/app/globals.css', css);
console.log('Fixed css');
