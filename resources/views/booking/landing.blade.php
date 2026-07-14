<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Fit Urban — Train Different</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
<style>
  :root{
    --cream:#FAF3E1;
    --cream-2:#F5E7C6;
    --amber:#FA8112;
    --amber-dim:#c4630d;
    --ink:#222222;
    --ink-soft:#3a3a38;
    --line: rgba(34,34,34,0.12);
    --line-light: rgba(250,243,225,0.16);
  }
  *{margin:0;padding:0;box-sizing:border-box;}
  html{scroll-behavior:smooth;}
  body{
    background:var(--cream);
    color:var(--ink);
    font-family:'Work Sans', sans-serif;
    overflow-x:hidden;
  }
  h1,h2,h3,.display{
    font-family:'Anton', sans-serif;
    text-transform:uppercase;
    letter-spacing:0.5px;
    line-height:0.95;
  }
  .mono{
    font-family:'Space Mono', monospace;
    text-transform:uppercase;
    letter-spacing:2px;
    font-size:12px;
  }
  a{color:inherit; text-decoration:none;}
  img{display:block; max-width:100%;}
  .wrap{max-width:1180px; margin:0 auto; padding:0 32px;}

  /* ===== NAV ===== */
  nav{
    position:fixed; top:0; left:0; right:0; z-index:100;
    display:flex; align-items:center; justify-content:space-between;
    padding:22px 40px;
    transition:background .35s ease, padding .35s ease, box-shadow .35s ease;
  }
  nav.scrolled{
    background:rgba(34,34,34,0.92);
    backdrop-filter:blur(10px);
    padding:14px 40px;
    box-shadow:0 2px 24px rgba(0,0,0,0.25);
  }
  .logo{
    display:flex;
    align-items:center;
  }
  .logo img{height:34px; width:auto; display:block;}
  .nav-links{display:flex; gap:36px; align-items:center;}
  .nav-links a{
    color:var(--cream);
    font-size:13px;
    font-weight:600;
    text-transform:uppercase;
    letter-spacing:1px;
    position:relative;
    padding-bottom:4px;
  }
  .nav-links a::after{
    content:''; position:absolute; left:0; bottom:0; height:2px; width:0;
    background:var(--amber); transition:width .25s ease;
  }
  .nav-links a:hover::after{width:100%;}
  .nav-cta{
    background:var(--amber);
    color:var(--ink);
    padding:10px 22px;
    font-weight:700;
    font-size:13px;
    text-transform:uppercase;
    letter-spacing:1px;
    border-radius:2px;
  }
  .nav-toggle{display:none;}

  /* ===== HERO ===== */
  .hero{
    position:relative;
    height:100vh;
    min-height:680px;
    display:flex;
    align-items:flex-end;
    background:var(--ink);
    overflow:hidden;
  }
  .hero-img{
    position:absolute; inset:0; width:100%; height:100%; object-fit:cover;
    object-position:center 18%;
    opacity:0.62;
    transform:scale(1.06);
    animation:heroZoom 16s ease-out forwards;
  }
  @keyframes heroZoom{ from{transform:scale(1.12);} to{transform:scale(1);} }
  .hero::before{
    content:'';
    position:absolute; inset:0;
    background:linear-gradient(180deg, rgba(34,34,34,0.55) 0%, rgba(34,34,34,0.25) 35%, rgba(34,34,34,0.97) 100%);
    z-index:1;
  }
  .hero-content{
    position:relative; z-index:2;
    width:100%;
    padding:0 40px 80px;
    display:flex;
    flex-direction:column;
    gap:18px;
  }
  .hero-eyebrow{
    color:var(--amber);
    display:flex; align-items:center; gap:10px;
  }
  .hero-eyebrow::before{content:''; width:30px; height:2px; background:var(--amber); display:inline-block;}
  .hero h1{
    color:var(--cream);
    font-family:'Anton', sans-serif;
    font-size:clamp(40px, 7vw, 104px);
    font-style:normal;
    text-transform:uppercase;
    letter-spacing:1px;
    line-height:0.95;
  }
  .hero h1 em{font-style:normal; color:var(--amber);}
  .hero-sub{
    color:var(--cream-2);
    max-width:520px;
    font-size:17px;
    line-height:1.6;
    margin-top:6px;
  }
  .hero-actions{
    display:flex; gap:16px; margin-top:18px; flex-wrap:wrap;
  }
  .btn-primary{
    background:var(--amber);
    color:var(--ink);
    padding:16px 32px;
    font-weight:700;
    font-size:14px;
    text-transform:uppercase;
    letter-spacing:1px;
    border-radius:2px;
    border:2px solid var(--amber);
    transition:transform .2s ease;
    display:inline-block;
  }
  .btn-primary:hover{transform:translateY(-2px);}
  .btn-ghost{
    background:transparent;
    color:var(--cream);
    padding:16px 32px;
    font-weight:700;
    font-size:14px;
    text-transform:uppercase;
    letter-spacing:1px;
    border-radius:2px;
    border:2px solid var(--line-light);
    transition:border-color .2s ease, transform .2s ease;
    display:inline-block;
  }
  .btn-ghost:hover{border-color:var(--amber); transform:translateY(-2px);}
  .hero-stats{
    position:absolute; right:40px; bottom:80px; z-index:2;
    display:flex; gap:36px;
  }
  .hero-stat{text-align:right;}
  .hero-stat b{
    font-family:'Anton', sans-serif;
    font-size:36px;
    color:var(--cream);
    display:block;
  }
  .hero-stat span{
    color:var(--amber);
    font-size:11px;
    letter-spacing:1.5px;
    text-transform:uppercase;
  }
  .scroll-cue{
    position:absolute; left:40px; bottom:32px; z-index:2;
    color:var(--cream-2);
    font-size:11px;
    display:flex; align-items:center; gap:10px;
  }
  .scroll-cue .line{width:1px; height:36px; background:var(--line-light); position:relative; overflow:hidden;}
  .scroll-cue .line::after{
    content:''; position:absolute; top:-100%; left:0; width:100%; height:100%;
    background:var(--amber); animation:scrollLine 1.8s ease-in-out infinite;
  }
  @keyframes scrollLine{ 50%{top:0;} 100%{top:100%;} }

  /* ===== SECTION HEADER ===== */
  .section{padding:120px 0;}
  .section.tight{padding:90px 0;}
  .sec-head{max-width:640px; margin-bottom:64px;}
  .sec-head .mono{color:var(--amber-dim); margin-bottom:14px; display:block;}
  .sec-head h2{font-size:clamp(34px, 5vw, 56px);}
  .sec-head p{margin-top:18px; font-size:16px; line-height:1.7; color:var(--ink-soft); max-width:560px;}
  .dark{background:var(--ink); color:var(--cream);}
  .dark .sec-head p{color:#bdbcb8;}
  .dark .mono{color:var(--amber);}

  /* ===== DIFFERENCE / WHY US ===== */
  .diff-grid{
    display:grid;
    grid-template-columns:1.05fr 1fr;
    gap:64px;
    align-items:center;
  }
  .diff-visual{
    position:relative;
    border-radius:4px;
    overflow:hidden;
    aspect-ratio:4/5;
  }
  .diff-visual img{width:100%; height:100%; object-fit:cover;}
  .diff-tag{
    position:absolute; left:20px; bottom:20px;
    background:var(--amber); color:var(--ink);
    padding:10px 18px;
    font-family:'Anton', sans-serif;
    font-size:14px;
    text-transform:uppercase;
    letter-spacing:0.5px;
  }
  .compare-list{display:flex; flex-direction:column;}
  .compare-row{
    display:grid;
    grid-template-columns:1fr 28px 1fr;
    gap:18px;
    align-items:flex-start;
    padding:22px 0;
    border-bottom:1px solid var(--line);
  }
  .compare-row:first-child{padding-top:0;}
  .compare-col h4{
    font-size:11px;
    text-transform:uppercase;
    letter-spacing:1.5px;
    font-weight:700;
    margin-bottom:6px;
  }
  .compare-col.them h4{color:#8c8a83;}
  .compare-col.us h4{color:var(--amber-dim);}
  .compare-col p{font-size:14.5px; line-height:1.55; color:var(--ink-soft);}
  .compare-col.them p{text-decoration:line-through; text-decoration-color:rgba(34,34,34,0.25); color:#8c8a83;}
  .compare-mid{
    width:28px; height:28px; border-radius:50%;
    background:var(--ink); color:var(--amber);
    display:flex; align-items:center; justify-content:center;
    font-size:13px; font-weight:700; margin-top:2px;
  }

  /* ===== PROGRAMS ===== */
  .programs-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:2px;
    background:var(--ink);
  }
  .program-card{
    position:relative;
    background:var(--ink);
    aspect-ratio:3/4;
    overflow:hidden;
    cursor:pointer;
  }
  .program-card img{
    width:100%; height:100%; object-fit:cover;
    transition:transform .6s cubic-bezier(.2,.7,.3,1), opacity .4s ease;
    opacity:0.78;
  }
  .program-card:hover img{transform:scale(1.08); opacity:1;}
  .program-overlay{
    position:absolute; inset:0;
    background:linear-gradient(180deg, rgba(34,34,34,0) 35%, rgba(34,34,34,0.95) 100%);
    display:flex; flex-direction:column; justify-content:flex-end;
    padding:28px;
  }
  .program-num{
    font-family:'Space Mono', monospace;
    color:var(--amber);
    font-size:13px;
    margin-bottom:auto;
  }
  .program-card h3{
    color:var(--cream);
    font-size:30px;
    margin-bottom:10px;
  }
  .program-desc{
    color:#cfcdc5;
    font-size:14px;
    line-height:1.55;
    max-height:0;
    opacity:0;
    overflow:hidden;
    transition:max-height .4s ease, opacity .4s ease, margin-top .4s ease;
  }
  .program-card:hover .program-desc{max-height:140px; opacity:1; margin-top:4px;}
  .program-meta{
    display:flex; gap:14px; margin-top:14px;
    font-size:11px; letter-spacing:1px; text-transform:uppercase; color:var(--amber);
  }

  /* ===== EQUIPMENT (interactive tabs) ===== */
  .equip-tabs{
    display:flex; flex-wrap:wrap; gap:10px; margin-bottom:40px;
  }
  .equip-tab{
    padding:12px 22px;
    border:1px solid var(--line);
    background:transparent;
    color:var(--ink);
    font-family:'Work Sans', sans-serif;
    font-size:13px;
    font-weight:600;
    text-transform:uppercase;
    letter-spacing:0.8px;
    cursor:pointer;
    border-radius:2px;
    transition:background .2s ease, color .2s ease, border-color .2s ease;
  }
  .equip-tab:hover{border-color:var(--amber);}
  .equip-tab.active{
    background:var(--ink);
    color:var(--amber);
    border-color:var(--ink);
  }
  .equip-panels{position:relative;}
  .equip-panel{display:none;}
  .equip-panel.active{display:block; animation:fadeUp .45s ease;}
  @keyframes fadeUp{ from{opacity:0; transform:translateY(10px);} to{opacity:1; transform:translateY(0);} }
  .equip-cat{margin-bottom:30px;}
  .equip-cat h4{
    font-size:12px;
    letter-spacing:1.5px;
    text-transform:uppercase;
    color:var(--amber-dim);
    margin-bottom:14px;
    padding-bottom:8px;
    border-bottom:1px solid var(--line);
  }
  .equip-items{
    display:grid;
    grid-template-columns:repeat(auto-fill, minmax(220px, 1fr));
    gap:10px 24px;
  }
  .equip-item{
    font-size:14.5px;
    color:var(--ink-soft);
    padding:9px 0;
    border-bottom:1px dashed var(--line);
    display:flex;
    align-items:center;
    gap:10px;
  }
  .equip-item::before{content:'';width:5px;height:5px;background:var(--amber);flex-shrink:0;border-radius:50%;}

  /* ===== TRAINERS ===== */
  .trainers-grid{
    display:grid;
    grid-template-columns:repeat(5,1fr);
    gap:24px;
  }
  .trainer-card{
    position:relative;
    overflow:hidden;
    border-radius:4px;
    aspect-ratio:3/4.4;
    background:#111;
  }
  .trainer-card img{
    width:100%; height:100%; object-fit:cover;
    filter:grayscale(45%);
    transition:filter .4s ease, transform .5s ease;
  }
  .trainer-card:hover img{filter:grayscale(0%); transform:scale(1.04);}
  .trainer-info{
    position:absolute; left:0; right:0; bottom:0;
    padding:20px;
    background:linear-gradient(180deg, transparent, rgba(0,0,0,0.92));
  }
  .trainer-info b{
    display:block; color:var(--cream);
    font-family:'Anton', sans-serif;
    font-size:20px;
    letter-spacing:0.5px;
  }
  .trainer-info span{
    color:var(--amber);
    font-size:11.5px;
    text-transform:uppercase;
    letter-spacing:1px;
  }

  /* ===== VISIT STRIP ===== */
  .visit-strip{
    background:var(--ink);
    padding:46px 0;
    border-top:1px solid var(--line-light);
    border-bottom:1px solid var(--line-light);
  }
  .visit-grid{
    display:flex;
    align-items:center;
    justify-content:space-between;
    flex-wrap:wrap;
    gap:24px;
  }
  .visit-block span.mono{color:var(--amber); display:block; margin-bottom:8px;}
  .visit-block p{color:var(--cream); font-size:16px; line-height:1.5;}
  .btn-ghost-dark{
    background:transparent;
    color:var(--cream);
    padding:14px 26px;
    font-weight:700;
    font-size:13px;
    text-transform:uppercase;
    letter-spacing:1px;
    border-radius:2px;
    border:1px solid var(--amber);
    transition:background .2s ease, color .2s ease;
    white-space:nowrap;
  }
  .btn-ghost-dark:hover{background:var(--amber); color:var(--ink);}

  /* ===== CTA STRIP ===== */
  .cta-strip{
    background:var(--amber);
    padding:80px 0;
    text-align:center;
  }
  .cta-strip h2{
    font-size:clamp(32px,6vw,64px);
    color:var(--ink);
  }
  .cta-strip p{margin:16px auto 32px; max-width:480px; color:#5a3107; font-size:15px;}
  .cta-strip .btn-primary{background:var(--ink); border-color:var(--ink); color:var(--cream);}

  /* ===== CTA INNER LAYOUT ===== */
  .cta-strip{
    background:var(--amber);
    padding:0;
    overflow:hidden;
  }
  .cta-inner{
    display:grid;
    grid-template-columns:1fr 420px;
    align-items:stretch;
    max-width:1180px;
    margin:0 auto;
  }
  .cta-text{
    padding:80px 60px;
    display:flex;
    flex-direction:column;
    justify-content:center;
    gap:16px;
  }
  .cta-text h2{font-size:clamp(28px,5vw,54px); color:var(--ink);}
  .cta-text p{color:#5a3107; font-size:15px; max-width:400px;}
  .cta-btns{
    display:flex;
    gap:14px;
    align-items:center;
    flex-wrap:wrap;
  }
  .btn-directions{
    background:transparent;
    color:var(--ink);
    padding:16px 28px;
    font-family:'Work Sans',sans-serif;
    font-weight:700;
    font-size:14px;
    text-transform:uppercase;
    letter-spacing:1px;
    border-radius:2px;
    border:2px solid var(--ink);
    transition:background .2s ease, color .2s ease;
    white-space:nowrap;
  }
  .btn-directions:hover{background:var(--ink); color:var(--amber);}
  .cta-text .btn-primary{
    background:var(--ink);
    border-color:var(--ink);
    color:var(--cream);
    width:fit-content;
    cursor:pointer;
    font-family:'Work Sans',sans-serif;
    border:2px solid var(--ink);
    padding:16px 32px;
    font-weight:700;
    font-size:14px;
    text-transform:uppercase;
    letter-spacing:1px;
    border-radius:2px;
    transition:transform .2s ease, background .2s ease;
  }
  .cta-text .btn-primary:hover{transform:translateY(-2px); background:#111;}
  .cta-img-wrap{
    position:relative;
    overflow:hidden;
  }
  .cta-img-wrap img{
    width:100%; height:100%; object-fit:cover; object-position:center top;
    display:block;
    transition:transform .5s ease;
  }
  .cta-img-wrap:hover img{transform:scale(1.04);}

  /* ===== MODAL ===== */
  .modal-overlay{
    position:fixed; inset:0; z-index:999;
    background:rgba(20,18,14,0.78);
    backdrop-filter:blur(6px);
    display:flex; align-items:center; justify-content:center;
    padding:20px;
    opacity:0; pointer-events:none;
    transition:opacity .3s ease;
  }
  .modal-overlay.open{opacity:1; pointer-events:all;}
  .modal-box{
    background:var(--cream);
    border-radius:6px;
    overflow:hidden;
    display:grid;
    grid-template-columns:280px 1fr;
    width:100%;
    max-width:780px;
    max-height:90vh;
    transform:translateY(20px) scale(0.98);
    transition:transform .35s cubic-bezier(.2,.8,.3,1);
    position:relative;
  }
  .modal-overlay.open .modal-box{transform:translateY(0) scale(1);}
  .modal-close{
    position:absolute; top:14px; right:16px; z-index:10;
    background:rgba(34,34,34,0.55); color:#fff;
    border:none; width:30px; height:30px; border-radius:50%;
    font-size:14px; cursor:pointer; display:flex; align-items:center; justify-content:center;
    transition:background .2s;
  }
  .modal-close:hover{background:var(--ink);}
  .modal-img{
    position:relative; overflow:hidden;
  }
  .modal-img img{width:100%; height:100%; object-fit:cover; object-position:center top;}
  .modal-img-label{
    position:absolute; bottom:0; left:0; right:0;
    background:linear-gradient(transparent, rgba(0,0,0,0.85));
    color:#fff; padding:20px 16px 14px;
    font-size:12.5px; line-height:1.45;
    text-align:center;
  }
  .modal-form{
    padding:32px 28px;
    overflow-y:auto;
    display:flex; flex-direction:column; gap:14px;
  }
  .modal-head h3{
    font-family:'Anton', sans-serif;
    font-size:26px; text-transform:uppercase;
    margin:6px 0 8px;
  }
  .modal-head p{font-size:13.5px; color:#5a5a50; line-height:1.5;}
  .form-group{display:flex; flex-direction:column; gap:5px;}
  .form-group label{font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.8px; color:#444;}
  .form-group input,
  .form-group select{
    padding:11px 14px;
    border:1.5px solid #ddd9ce;
    border-radius:3px;
    font-family:'Work Sans', sans-serif;
    font-size:14px;
    background:#fff;
    color:var(--ink);
    outline:none;
    transition:border-color .2s;
  }
  .form-group input:focus,
  .form-group select:focus{border-color:var(--amber);}
  .walkin-hint{
    font-size:13px; color:#7a7a70; text-align:center;
    border-top:1px solid #e8e4da; padding-top:12px;
    line-height:1.6;
  }
  .walkin-hint a{color:var(--amber-dim); font-weight:700; text-decoration:none;}
  .walkin-hint a:hover{text-decoration:underline;}
  .walkin-hint small{font-size:11.5px; color:#aaa8a0;}
  .btn-submit{
    background:var(--amber);
    color:var(--ink);
    border:none;
    padding:14px;
    font-family:'Work Sans', sans-serif;
    font-size:14px;
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:1px;
    border-radius:3px;
    cursor:pointer;
    margin-top:4px;
    transition:background .2s, transform .2s;
  }
  .btn-submit:hover{background:var(--amber-dim); transform:translateY(-1px);}
  .form-success{
    display:none;
    background:#e8f5e9;
    border:1.5px solid #66bb6a;
    color:#2e7d32;
    padding:12px 16px;
    border-radius:3px;
    font-size:14px;
    font-weight:600;
    text-align:center;
  }
  .auth-tabs{
    display:flex;
    gap:4px;
    background:#efe8d6;
    border-radius:4px;
    padding:4px;
    margin-bottom:6px;
  }
  .auth-tab{
    flex:1;
    background:transparent;
    border:none;
    padding:10px 12px;
    font-family:'Work Sans', sans-serif;
    font-size:13px;
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:0.6px;
    color:#7a7a70;
    border-radius:3px;
    cursor:pointer;
    transition:background .2s ease, color .2s ease;
  }
  .auth-tab.active{
    background:var(--amber);
    color:var(--ink);
  }
  .auth-panel{
    display:none;
    flex-direction:column;
    gap:14px;
  }
  .auth-panel.active{display:flex;}
  .auth-panel form{display:flex; flex-direction:column; gap:14px;}
  .form-error{
    color:#c0392b;
    font-size:12.5px;
    font-weight:600;
  }
  .form-remember{
    display:flex;
    align-items:center;
    gap:8px;
    font-size:13px;
    color:#5a5a50;
    cursor:pointer;
  }
  .form-remember input{width:auto;}
  @media(max-width:700px){
    .modal-box{grid-template-columns:1fr;}
    .modal-img{height:180px;}
    .cta-inner{grid-template-columns:1fr;}
    .cta-img-wrap{height:260px;}
  }

  /* ===== FOOTER ===== */
  footer{background:var(--ink); color:var(--cream-2); padding:60px 0 28px;}
  .foot-grid{
    display:grid; grid-template-columns:1.4fr 1fr 1fr; gap:48px;
    padding-bottom:40px; border-bottom:1px solid var(--line-light);
  }
  .foot-logo{font-family:'Anton', sans-serif; font-size:26px; color:var(--cream); margin-bottom:14px;}
  .foot-logo span{color:var(--amber);}
  .foot-grid p{font-size:14px; line-height:1.6; max-width:280px; color:#a6a49d;}
  .foot-col h5{font-size:12px; letter-spacing:1.5px; text-transform:uppercase; color:var(--amber); margin-bottom:16px;}
  .foot-col a{display:block; font-size:14px; color:#cfcdc5; margin-bottom:10px;}
  .social-links{display:flex; gap:12px; align-items:center;}
  .social-icon{
    width:38px; height:38px;
    border-radius:50%;
    border:1.5px solid rgba(250,243,225,0.2);
    display:flex; align-items:center; justify-content:center;
    color:#cfcdc5;
    transition:background .2s ease, color .2s ease, border-color .2s ease;
  }
  .social-icon:hover{background:var(--amber); color:var(--ink); border-color:var(--amber);}
  .foot-bottom{
    display:flex; justify-content:space-between; padding-top:24px;
    font-size:12.5px; color:#8c8a83; flex-wrap:wrap; gap:10px;
  }

  /* ===== REVEAL ===== */
  .reveal{opacity:0; transform:translateY(28px); transition:opacity .7s ease, transform .7s ease;}
  .reveal.in{opacity:1; transform:translateY(0);}

  /* ===== RESPONSIVE ===== */
  @media(max-width:900px){
    .diff-grid{grid-template-columns:1fr;}
    .programs-grid{grid-template-columns:1fr;}
    .trainers-grid{grid-template-columns:repeat(3,1fr);}
    .foot-grid{grid-template-columns:1fr; gap:32px;}
    .hero-stats{display:none;}
  }
  @media(max-width:760px){
    .nav-links{
      position:fixed; top:0; right:-100%; height:100vh; width:78%;
      background:var(--ink); flex-direction:column; justify-content:center;
      gap:30px; transition:right .35s ease; z-index:99;
    }
    .nav-links.open{right:0;}
    .nav-toggle{
      display:block; background:none; border:none; color:var(--cream);
      font-size:26px; cursor:pointer; z-index:101;
    }
    .compare-row{grid-template-columns:1fr; gap:8px;}
    .compare-mid{display:none;}
  }
</style>
</head>
<body>

<nav id="nav">
  <a href="#" class="logo"><img src="{{ asset('images/fit_urban_logo_white.png') }}" alt="Fit Urban logo"></a>
  <div class="nav-links" id="navLinks">
    <a href="#difference">Why FU</a>
    <a href="#programs">Programs</a>
    <a href="#equipment">Equipment</a>
    <a href="#trainers">Trainers</a>
    @auth
      <a href="{{ route('dashboard') }}" class="nav-cta" style="display:inline-block;">Dashboard</a>
    @else
      <button class="nav-cta open-modal" style="cursor:pointer;border:none;">Sign In / Join</button>
    @endauth
  </div>
  <button class="nav-toggle" id="navToggle" aria-label="Toggle menu">☰</button>
</nav>

<section class="hero">
  <img class="hero-img" src="{{ asset('images/bg_urban.jpg') }}" alt="Fit Urban gym floor">
  <div class="hero-content">
    <div class="hero-eyebrow mono">Banay-Banay 1st, San Jose, Batangas</div>
    <h1>Every day is<br>another chance<br>to become <em>stronger.</em></h1>
    <p class="hero-sub">Fit Urban isn't a place you check into — it's a standard you train up to. Strength, Pilates and recovery under one roof, built for people who don't do average.</p>
    <div class="hero-actions">
      @auth
        <a href="{{ route('dashboard') }}" class="btn-primary">Go to dashboard</a>
      @else
        <button class="btn-primary open-modal">Get started</button>
      @endauth
      <a href="#programs" class="btn-ghost">See programs</a>
    </div>
  </div>
  <div class="hero-stats">
    <div class="hero-stat"><b>5</b><span>Coaches</span></div>
    <div class="hero-stat"><b>30+</b><span>Machines</span></div>
    <div class="hero-stat"><b>5–10</b><span>Open daily</span></div>
  </div>
  <div class="scroll-cue"><div class="line"></div>Scroll</div>
</section>

<section class="section" id="difference">
  <div class="wrap">
    <div class="sec-head reveal">
      <span class="mono">Why Fit Urban</span>
      <h2>Not your average gym down the block.</h2>
      <p>Most gyms hand you a key card and a rack of dumbbells, then leave you to figure it out. We built Fit Urban around the part everyone else skips — actual coaching, actual programming, and equipment that doesn't have a waitlist.</p>
    </div>

    <div class="diff-grid">
      <div class="diff-visual reveal">
        <img src="{{ asset('images/gymers.png') }}" alt="Fit Urban members - built different, train together">
        <div class="diff-tag">Built different. Train together.</div>
      </div>

      <div class="compare-list reveal">
        <div class="compare-row">
          <div class="compare-col them"><h4>Typical gym</h4><p>Sign up, get a tour, never see staff again</p></div>
          <div class="compare-mid">VS</div>
          <div class="compare-col us"><h4>Fit Urban</h4><p>Every member gets a coach check-in and a real plan</p></div>
        </div>
        <div class="compare-row">
          <div class="compare-col them"><h4>Typical gym</h4><p>One crowded floor, equipment always in use</p></div>
          <div class="compare-mid">VS</div>
          <div class="compare-col us"><h4>Fit Urban</h4><p>Dedicated Pilates studio, strength floor and open gym, separated by design</p></div>
        </div>
        <div class="compare-row">
          <div class="compare-col them"><h4>Typical gym</h4><p>Generic group classes for everyone</p></div>
          <div class="compare-mid">VS</div>
          <div class="compare-col us"><h4>Fit Urban</h4><p>Specialist coaches for strength, nutrition, performance and wellness</p></div>
        </div>
        <div class="compare-row">
          <div class="compare-col them"><h4>Typical gym</h4><p>Outdated, half-broken machines</p></div>
          <div class="compare-mid">VS</div>
          <div class="compare-col us"><h4>Fit Urban</h4><p>Full plate-loaded, selectorized and free-weight setup, maintained weekly</p></div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section dark" id="programs">
  <div class="wrap">
    <div class="sec-head reveal">
      <span class="mono">Programs</span>
      <h2>Pick your lane. Or run all three.</h2>
      <p>Three ways into Fit Urban — each one built around a different goal, a different pace, and a different kind of focus.</p>
    </div>
  </div>

  <div class="programs-grid reveal">
    <div class="program-card">
      <img src="{{ asset('images/pilates.png') }}" alt="Pilates reformer class at Fit Urban">
      <div class="program-overlay">
        <span class="program-num">01</span>
        <h3>Pilates</h3>
        <p class="program-desc">Reformer-led sessions for control, posture and core strength, coached in small groups so form never gets lost in the crowd.</p>
        <div class="program-meta"><span>Small group</span><span>Reformer studio</span></div>
      </div>
    </div>

    <div class="program-card">
      <img src="{{ asset('images/personal_trainer.png') }}" alt="Personal training session at Fit Urban">
      <div class="program-overlay">
        <span class="program-num">02</span>
        <h3>Personal training</h3>
        <p class="program-desc">One-on-one programming with a dedicated coach — built around your goal, your schedule, and your starting point, not a template.</p>
        <div class="program-meta"><span>1-on-1</span><span>Custom plan</span></div>
      </div>
    </div>

    <div class="program-card">
      <img src="{{ asset('images/open_gym_access.png') }}" alt="Open gym floor access at Fit Urban">
      <div class="program-overlay">
        <span class="program-num">03</span>
        <h3>Open gym access</h3>
        <p class="program-desc">Full run of the strength floor and cardio deck whenever you want it — no booking, no shared sets, just space to work.</p>
        <div class="program-meta"><span>Unlimited</span><span>Full floor</span></div>
      </div>
    </div>
  </div>
</section>

<section class="section" id="equipment">
  <div class="wrap">
    <div class="sec-head reveal">
      <span class="mono">Inside the gym</span>
      <h2>Equipment that matches the program.</h2>
      <p>Everything on the floor, organized the way you'll actually use it. Tap a category to see what's there.</p>
    </div>

    <div class="equip-tabs reveal" id="equipTabs">
      <button class="equip-tab active" data-tab="cardio">Cardio</button>
      <button class="equip-tab" data-tab="strength">Strength machines</button>
      <button class="equip-tab" data-tab="free">Free weights & benches</button>
      <button class="equip-tab" data-tab="functional">Functional & racks</button>
      <button class="equip-tab" data-tab="pilates">Pilates studio</button>
    </div>

    <div class="equip-panels reveal">
      <div class="equip-panel active" data-panel="cardio">
        <div class="equip-cat">
          <h4>Cardio equipment</h4>
          <div class="equip-items">
            <div class="equip-item">Treadmill</div>
            <div class="equip-item">Stair climber / StairMaster</div>
            <div class="equip-item">Curved manual treadmill</div>
            <div class="equip-item">Air runner</div>
            <div class="equip-item">Cardio console machines</div>
          </div>
        </div>
      </div>

      <div class="equip-panel" data-panel="strength">
        <div class="equip-cat">
          <h4>Plate-loaded machines</h4>
          <div class="equip-items">
            <div class="equip-item">Leg press machine</div>
            <div class="equip-item">Hack squat machine</div>
            <div class="equip-item">Incline chest press machine</div>
            <div class="equip-item">Shoulder press machine</div>
            <div class="equip-item">Row machine (plate loaded)</div>
          </div>
        </div>
        <div class="equip-cat">
          <h4>Selectorized machines</h4>
          <div class="equip-items">
            <div class="equip-item">Lat pulldown machine</div>
            <div class="equip-item">Cable pulley machine</div>
            <div class="equip-item">Functional trainer</div>
            <div class="equip-item">Leg extension machine</div>
            <div class="equip-item">Leg curl machine</div>
            <div class="equip-item">Pec deck / chest fly machine</div>
          </div>
        </div>
      </div>

      <div class="equip-panel" data-panel="free">
        <div class="equip-cat">
          <h4>Dumbbells & barbells</h4>
          <div class="equip-items">
            <div class="equip-item">1–10kg colored hex dumbbells</div>
            <div class="equip-item">12.5–35kg rubber dumbbells</div>
            <div class="equip-item">EZ curl bars</div>
            <div class="equip-item">Straight barbells</div>
            <div class="equip-item">Olympic plates</div>
          </div>
        </div>
        <div class="equip-cat">
          <h4>Benches</h4>
          <div class="equip-items">
            <div class="equip-item">Adjustable incline bench</div>
            <div class="equip-item">Flat bench</div>
          </div>
        </div>
      </div>

      <div class="equip-panel" data-panel="functional">
        <div class="equip-cat">
          <h4>Functional training</h4>
          <div class="equip-items">
            <div class="equip-item">Squat rack / power rack</div>
            <div class="equip-item">Functional training turf area</div>
            <div class="equip-item">Orange training cones</div>
          </div>
        </div>
        <div class="equip-cat">
          <h4>Racks, storage & accessories</h4>
          <div class="equip-items">
            <div class="equip-item">Dumbbell rack</div>
            <div class="equip-item">Barbell rack</div>
            <div class="equip-item">Weight plate storage rack</div>
            <div class="equip-item">Water bottle holder</div>
            <div class="equip-item">Mirrors</div>
            <div class="equip-item">Rubber gym flooring</div>
          </div>
        </div>
      </div>

      <div class="equip-panel" data-panel="pilates">
        <div class="equip-cat">
          <h4>Major equipment</h4>
          <div class="equip-items">
            <div class="equip-item">Pilates reformer</div>
            <div class="equip-item">Reformer with tower</div>
            <div class="equip-item">Cadillac / trapeze table</div>
            <div class="equip-item">Pilates chair (Wunda chair)</div>
            <div class="equip-item">Ladder barrel</div>
            <div class="equip-item">Spine corrector</div>
            <div class="equip-item">Arc barrel</div>
          </div>
        </div>
        <div class="equip-cat">
          <h4>Small equipment</h4>
          <div class="equip-items">
            <div class="equip-item">Pilates ring (magic circle)</div>
            <div class="equip-item">Resistance bands</div>
            <div class="equip-item">Mini resistance bands</div>
            <div class="equip-item">Stability ball</div>
            <div class="equip-item">Mini exercise ball</div>
            <div class="equip-item">Foam roller</div>
            <div class="equip-item">Yoga / Pilates mat</div>
            <div class="equip-item">Pilates blocks</div>
            <div class="equip-item">Stretch strap</div>
            <div class="equip-item">Light dumbbells & ankle weights</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section dark" id="trainers">
  <div class="wrap">
    <div class="sec-head reveal">
      <span class="mono">Your coaches</span>
      <h2>People who actually know your name.</h2>
      <p>Every member is paired with coaches who specialize in their goal — not a rotating cast of whoever's on shift.</p>
    </div>

    <div class="trainers-grid reveal">
      <div class="trainer-card">
        <img src="{{ asset('images/trainer_1.png') }}" alt="Kyle, Strength and Conditioning Coach">
        <div class="trainer-info"><b>Kyle</b><span>Strength & conditioning</span></div>
      </div>
      <div class="trainer-card">
        <img src="{{ asset('images/trainer_2.png') }}" alt="Andrea, Nutrition and Lifestyle Coach">
        <div class="trainer-info"><b>Andrea</b><span>Nutrition & lifestyle</span></div>
      </div>
      <div class="trainer-card">
        <img src="{{ asset('images/trainer_3.png') }}" alt="Miguel, Muscle Building Coach">
        <div class="trainer-info"><b>Miguel</b><span>Muscle building</span></div>
      </div>
      <div class="trainer-card">
        <img src="{{ asset('images/trainer_4.png') }}" alt="Josh, Performance Coach">
        <div class="trainer-info"><b>Josh</b><span>Performance coach</span></div>
      </div>
      <div class="trainer-card">
        <img src="{{ asset('images/trainer_5.png') }}" alt="Leah, Wellness Coach and Group Class Trainer">
        <div class="trainer-info"><b>Leah</b><span>Wellness & group class</span></div>
      </div>
    </div>
  </div>
</section>

<section class="visit-strip reveal">
  <div class="wrap visit-grid">
    <div class="visit-block">
      <span class="mono">Find us</span>
      <p>2nd Floor, Arada-Virtucio Bldg,<br>Banay-Banay 1st, San Jose, Batangas</p>
    </div>
    <div class="visit-block">
      <span class="mono">Hours</span>
      <p>Open daily, 5:00 AM – 10:00 PM</p>
    </div>
  </div>
</section>

<section class="cta-strip reveal" id="join">
  <div class="cta-inner">
    <div class="cta-text">
      <h2>Your first session is on us.</h2>
      <p>Walk in, meet a coach, try the floor or the reformer. No pressure, no contract talk — just see if it fits.</p>
      <div class="cta-btns">
        <a href="https://www.google.com/maps/search/?api=1&query=Arada-Virtucio+Bldg+Banay-Banay+1st+San+Jose+Batangas" target="_blank" class="btn-directions">Get directions</a>
        @auth
          <a href="{{ route('dashboard') }}" class="btn-primary">Go to dashboard</a>
        @else
          <button class="btn-primary open-modal">Sign up now</button>
        @endauth
      </div>
    </div>
    <div class="cta-img-wrap">
      <img src="{{ asset('images/service_staff.jpg') }}" alt="Fit Urban staff ready to assist you">
    </div>
  </div>
</section>

@guest
<!-- LOGIN / REGISTER MODAL -->
<div class="modal-overlay" id="authModal">
  <div class="modal-box">
    <button class="modal-close" id="modalClose" type="button">✕</button>
    <div class="modal-img">
      <img src="{{ asset('images/service_staff.jpg') }}" alt="Fit Urban member training">
      <div class="modal-img-label">Your training floor is waiting.</div>
    </div>
    <div class="modal-form">
      <div class="auth-tabs">
        <button class="auth-tab {{ $errors->register->any() ? '' : 'active' }}" data-tab="login" type="button">Log In</button>
        <button class="auth-tab {{ $errors->register->any() ? 'active' : '' }}" data-tab="register" type="button">Register</button>
      </div>

      <!-- LOGIN PANEL -->
      <div class="auth-panel {{ $errors->register->any() ? '' : 'active' }}" data-panel="login">
        <div class="modal-head">
          <span class="mono" style="color:var(--amber)">Welcome back</span>
          <h3>Log in to your account</h3>
          <p>Access your bookings, programs and training progress.</p>
        </div>
        @if (session('status'))
          <div class="form-success" style="display:block;">
            <span>✓</span> {{ session('status') }}
          </div>
        @endif
        <form method="POST" action="{{ route('login') }}">
          @csrf
          <div class="form-group">
            <label for="login-email">Email address</label>
            <input type="email" id="login-email" name="email" value="{{ old('email') }}" placeholder="juan@email.com" required autofocus>
            @error('email', 'login')
              <span class="form-error">{{ $message }}</span>
            @enderror
          </div>
          <div class="form-group">
            <label for="login-password">Password</label>
            <input type="password" id="login-password" name="password" placeholder="••••••••" required>
            @error('password', 'login')
              <span class="form-error">{{ $message }}</span>
            @enderror
          </div>
          <label class="form-remember">
            <input type="checkbox" name="remember"> Remember me
          </label>
          <button class="btn-submit" type="submit">Log in</button>
        </form>
        <div class="walkin-hint">
          Don't have an account? <a href="#" class="switch-tab" data-tab="register">Register here</a><br>
          <small>Open daily, 5:00 AM – 10:00 PM · 2nd Floor, Arada-Virtucio Bldg, San Jose, Batangas</small>
        </div>
      </div>

      <!-- REGISTER PANEL -->
      <div class="auth-panel {{ $errors->register->any() ? 'active' : '' }}" data-panel="register">
        <div class="modal-head">
          <span class="mono" style="color:var(--amber)">Join Fit Urban</span>
          <h3>Create your account</h3>
          <p>Sign up to book sessions, track programs and manage your membership.</p>
        </div>
        <form method="POST" action="{{ route('register') }}">
          @csrf
          <div class="form-group">
            <label for="reg-name">Full name</label>
            <input type="text" id="reg-name" name="name" value="{{ old('name') }}" placeholder="Juan dela Cruz" required>
            @error('name', 'register')
              <span class="form-error">{{ $message }}</span>
            @enderror
          </div>
          <div class="form-group">
            <label for="reg-email">Email address</label>
            <input type="email" id="reg-email" name="email" value="{{ old('email') }}" placeholder="juan@email.com" required>
            @error('email', 'register')
              <span class="form-error">{{ $message }}</span>
            @enderror
          </div>
          <div class="form-group">
            <label for="reg-password">Password</label>
            <input type="password" id="reg-password" name="password" placeholder="Create a password" required>
            @error('password', 'register')
              <span class="form-error">{{ $message }}</span>
            @enderror
          </div>
          <div class="form-group">
            <label for="reg-password-confirm">Confirm password</label>
            <input type="password" id="reg-password-confirm" name="password_confirmation" placeholder="Re-enter your password" required>
          </div>
          <button class="btn-submit" type="submit">Create account</button>
        </form>
        <div class="walkin-hint">
          Already have an account? <a href="#" class="switch-tab" data-tab="login">Log in</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endguest


<footer>
  <div class="wrap">
    <div class="foot-grid">
      <div>
        <div class="foot-logo"><img src="{{ asset('images/fit_urban_logo_white.png') }}" alt="Fit Urban logo" style="height:38px;width:auto;margin-bottom:14px;"></div>
        <p>A strength floor, a Pilates studio and a coaching team that pays attention — 2nd Floor, Arada-Virtucio Bldg, Banay-Banay 1st, San Jose, Batangas.</p>
        <p style="margin-top:10px; color:var(--amber);">Open daily, 5:00 AM – 10:00 PM</p>
        <div class="social-links" style="margin-top:18px;">
          <a href="https://www.facebook.com/fiturban" target="_blank" class="social-icon" aria-label="Facebook">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
              <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
            </svg>
          </a>
          <a href="https://www.instagram.com/fiturban" target="_blank" class="social-icon" aria-label="Instagram">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
              <circle cx="12" cy="12" r="4"/>
              <circle cx="17.5" cy="6.5" r="1.5" fill="currentColor" stroke="none"/>
            </svg>
          </a>
        </div>
      </div>
      <div class="foot-col">
        <h5>Programs</h5>
        <a href="#programs">Pilates</a>
        <a href="#programs">Personal training</a>
        <a href="#programs">Open gym access</a>
      </div>
      <div class="foot-col">
        <h5>Studio</h5>
        <a href="#difference">Why Fit Urban</a>
        <a href="#equipment">Equipment</a>
        <a href="#trainers">Coaches</a>
      </div>
    </div>
    <div class="foot-bottom">
      <span>© 2026 Fit Urban. All rights reserved.</span>
      <span>San Jose, Batangas, Philippines · 5AM–10PM daily</span>
    </div>
  </div>
</footer>

<script>
  const nav = document.getElementById('nav');
  window.addEventListener('scroll', () => {
    nav.classList.toggle('scrolled', window.scrollY > 60);
  });

  const navToggle = document.getElementById('navToggle');
  const navLinks = document.getElementById('navLinks');
  navToggle.addEventListener('click', () => navLinks.classList.toggle('open'));
  navLinks.querySelectorAll('a').forEach(a => a.addEventListener('click', () => navLinks.classList.remove('open')));

  const tabs = document.querySelectorAll('.equip-tab');
  const panels = document.querySelectorAll('.equip-panel');
  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      tabs.forEach(t => t.classList.remove('active'));
      panels.forEach(p => p.classList.remove('active'));
      tab.classList.add('active');
      document.querySelector('.equip-panel[data-panel="' + tab.dataset.tab + '"]').classList.add('active');
    });
  });

  @guest
  // Modal
  const modal = document.getElementById('authModal');
  const modalClose = document.getElementById('modalClose');

  function showAuthTab(tabName){
    document.querySelectorAll('.auth-tab').forEach(t => t.classList.toggle('active', t.dataset.tab === tabName));
    document.querySelectorAll('.auth-panel').forEach(p => p.classList.toggle('active', p.dataset.panel === tabName));
  }

  function openModal(){
    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  document.querySelectorAll('.auth-tab').forEach(tab => {
    tab.addEventListener('click', () => showAuthTab(tab.dataset.tab));
  });
  document.querySelectorAll('.switch-tab').forEach(link => {
    link.addEventListener('click', e => {
      e.preventDefault();
      showAuthTab(link.dataset.tab);
    });
  });

  document.querySelectorAll('.open-modal').forEach(btn => {
    btn.addEventListener('click', () => {
      showAuthTab('register');
      openModal();
    });
  });

  function closeModal(){
    modal.classList.remove('open');
    document.body.style.overflow = '';
  }
  modalClose.addEventListener('click', closeModal);
  modal.addEventListener('click', e => { if(e.target === modal) closeModal(); });
  document.addEventListener('keydown', e => { if(e.key === 'Escape') closeModal(); });

  @if ($errors->login->any() || $errors->register->any())
    openModal();
  @elseif (session('status'))
    showAuthTab('login');
    openModal();
  @endif
  @endguest

  const revealEls = document.querySelectorAll('.reveal');
  const io = new IntersectionObserver(entries => {
    entries.forEach(e => { if(e.isIntersecting){ e.target.classList.add('in'); io.unobserve(e.target);} });
  }, {threshold:0.15});
  revealEls.forEach(el => io.observe(el));
</script>

</body>
</html>
