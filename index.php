<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AI StudyBuddy</title>
<!-- Old stylesheet removed — all styling is now inline below.
     (You can delete assets/style.css or leave it; it is no longer linked.) -->
<style>
/* ==========================================================
   AI STUDYBUDDY · AURORA GLASS UI
   Same class names / IDs / handlers — visual layer only.
   ========================================================== */
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap");

:root{
  --bg:#060912;
  --ink:#e9eefb;
  --muted:#8e9bbd;
  --line:rgba(255,255,255,.09);
  --line-2:rgba(255,255,255,.18);
  --glass:linear-gradient(160deg,rgba(255,255,255,.075),rgba(255,255,255,.02));
  --blue:#5b8cff;
  --violet:#a855f7;
  --cyan:#22d3ee;
  --pink:#ec4899;
  --ok:#34d399;
  --bad:#ff6b81;
  --r-lg:24px;
  --r-md:16px;
  --r-sm:12px;
  --shadow:0 30px 70px -30px rgba(0,0,0,.9);
  --ease:cubic-bezier(.22,1,.36,1);
}

*,*::before,*::after{box-sizing:border-box}

html{color-scheme:dark;scroll-behavior:smooth}

body{
  margin:0;
  min-height:100vh;
  font-family:"Inter",system-ui,-apple-system,"Segoe UI",Roboto,sans-serif;
  font-size:16px;
  line-height:1.55;
  color:var(--ink);
  background:var(--bg);
  -webkit-font-smoothing:antialiased;
  overflow-x:hidden;
}

/* ---------- animated aurora backdrop ---------- */
body::before{
  content:"";
  position:fixed;
  inset:-30%;
  z-index:-2;
  background:
    radial-gradient(38% 45% at 16% 20%, rgba(91,140,255,.50), transparent 65%),
    radial-gradient(34% 40% at 84% 10%, rgba(168,85,247,.45), transparent 65%),
    radial-gradient(46% 46% at 72% 90%, rgba(34,211,238,.28), transparent 65%),
    radial-gradient(40% 40% at 6% 88%, rgba(236,72,153,.26), transparent 65%);
  filter:blur(70px);
  animation:aurora 24s ease-in-out infinite alternate;
}
body::after{
  content:"";
  position:fixed;inset:0;z-index:-1;pointer-events:none;
  background:radial-gradient(120% 90% at 50% -10%, transparent 38%, rgba(2,5,14,.82) 100%);
}

@keyframes aurora{
  0%  {transform:translate3d(0,0,0) scale(1)}
  50% {transform:translate3d(2.5%,-3%,0) scale(1.08)}
  100%{transform:translate3d(-3%,2.5%,0) scale(1.04)}
}
@keyframes rise{
  from{opacity:0;transform:translateY(20px)}
  to  {opacity:1;transform:none}
}

::selection{background:rgba(91,140,255,.4);color:#fff}

::-webkit-scrollbar{width:11px;height:11px}
::-webkit-scrollbar-track{background:transparent}
::-webkit-scrollbar-thumb{
  background:linear-gradient(var(--blue),var(--violet));
  border:3px solid var(--bg);
  border-radius:99px;
}

/* ==========================================================
   TOP BAR
   ========================================================== */
.topbar{
  position:sticky;top:0;z-index:50;
  display:flex;justify-content:space-between;align-items:center;gap:24px;
  padding:18px 6%;
  background:linear-gradient(120deg,rgba(10,16,34,.82),rgba(22,12,44,.72));
  backdrop-filter:blur(20px) saturate(170%);
  -webkit-backdrop-filter:blur(20px) saturate(170%);
  border-bottom:1px solid var(--line);
  box-shadow:0 18px 50px -30px rgba(0,0,0,1);
}
.topbar::after{
  content:"";
  position:absolute;left:0;right:0;bottom:-1px;height:1px;
  background:linear-gradient(90deg,transparent,var(--blue),var(--violet),var(--cyan),transparent);
  opacity:.75;
}
.topbar h1{
  margin:0;
  font-family:"Space Grotesk","Inter",sans-serif;
  font-size:clamp(1.25rem,2.4vw,1.7rem);
  letter-spacing:-.02em;
  background:linear-gradient(100deg,#fff 10%,#bcd0ff 45%,#e9d5ff 90%);
  -webkit-background-clip:text;background-clip:text;color:transparent;
}
.topbar p{
  margin:4px 0 0;
  font-size:.85rem;
  color:var(--muted);
}
#userBox{display:flex;align-items:center;gap:12px;font-size:.9rem}

/* ==========================================================
   LAYOUT
   ========================================================== */
.container{
  width:min(1140px,90%);
  margin:38px auto 80px;
}

.card{
  position:relative;
  background:var(--glass);
  border:1px solid var(--line);
  border-radius:var(--r-lg);
  padding:28px;
  box-shadow:var(--shadow);
  backdrop-filter:blur(22px) saturate(150%);
  -webkit-backdrop-filter:blur(22px) saturate(150%);
  animation:rise .65s var(--ease) both;
}
.card::before{
  content:"";
  position:absolute;inset:0;
  border-radius:inherit;
  padding:1px;
  background:linear-gradient(140deg,rgba(255,255,255,.38),transparent 38%,transparent 62%,rgba(168,85,247,.35));
  -webkit-mask:linear-gradient(#000 0 0) content-box,linear-gradient(#000 0 0);
  -webkit-mask-composite:xor;
          mask-composite:exclude;
  pointer-events:none;
}
.card h2{margin-top:0}
.card h3{
  margin:0 0 12px;
  font-family:"Space Grotesk","Inter",sans-serif;
  font-size:1.05rem;
  letter-spacing:-.01em;
  color:#cdd8f6;
}

.auth-grid{
  display:grid;
  grid-template-columns:1.35fr .85fr;
  gap:28px;
  align-items:start;
}

/* ==========================================================
   HERO
   ========================================================== */
.hero{
  position:relative;
  overflow:hidden;
  padding:46px 40px;
}
.hero::after{
  content:"";
  position:absolute;
  width:380px;height:380px;
  right:-140px;top:-160px;
  background:radial-gradient(circle,rgba(168,85,247,.55),transparent 70%);
  filter:blur(30px);
  pointer-events:none;
}
.hero h2{
  position:relative;
  margin:0 0 14px;
  font-family:"Space Grotesk","Inter",sans-serif;
  font-size:clamp(2rem,4vw,2.7rem);
  line-height:1.14;
  letter-spacing:-.03em;
  background:linear-gradient(100deg,#ffffff,#9fbaff 45%,#d8b4fe);
  -webkit-background-clip:text;background-clip:text;color:transparent;
}
.hero p{color:var(--muted);max-width:46ch;margin:0}

.feature-list{
  display:flex;flex-wrap:wrap;gap:10px;
  margin-top:28px;
  position:relative;
}
.feature-list span{
  padding:10px 16px;
  font-size:.85rem;
  font-weight:500;
  color:#cfd9f5;
  background:rgba(255,255,255,.05);
  border:1px solid var(--line);
  border-radius:99px;
  transition:transform .25s var(--ease),border-color .25s,background .25s,color .25s;
}
.feature-list span:hover{
  transform:translateY(-3px);
  color:#fff;
  background:rgba(91,140,255,.16);
  border-color:rgba(91,140,255,.55);
}

/* ==========================================================
   FORMS
   ========================================================== */
.auth-card input,
.content textarea{
  width:100%;
  padding:14px 16px;
  margin:7px 0;
  font:inherit;
  font-size:.95rem;
  color:var(--ink);
  background:rgba(6,10,22,.65);
  border:1px solid var(--line);
  border-radius:14px;
  outline:none;
  transition:border-color .25s,box-shadow .25s,background .25s;
}
.auth-card input::placeholder,
.content textarea::placeholder{color:#5f6c8c}

.auth-card input:focus,
.content textarea:focus{
  border-color:rgba(91,140,255,.75);
  background:rgba(9,15,32,.9);
  box-shadow:0 0 0 3px rgba(91,140,255,.22),0 0 40px -12px rgba(91,140,255,.9);
}

.content textarea{
  min-height:150px;
  resize:vertical;
  line-height:1.65;
}

/* ==========================================================
   BUTTONS
   ========================================================== */
.button,
.primary,
.sidebar button,
.ai-btn,
.material button,
.tab,
.logout-small{
  font:inherit;
  font-weight:600;
  border:0;
  cursor:pointer;
  transition:transform .2s var(--ease),box-shadow .25s,background .25s,color .25s,filter .25s;
}

.button,
.primary,
.ai-btn{
  border-radius:14px;
  padding:13px 20px;
}

.button{
  color:var(--ink);
  background:rgba(255,255,255,.06);
  border:1px solid var(--line-2);
}
.button:hover{background:rgba(255,255,255,.12);transform:translateY(-2px)}

.primary,
.ai-btn{
  position:relative;
  overflow:hidden;
  width:100%;
  margin-top:10px;
  color:#fff;
  background:linear-gradient(100deg,var(--blue),var(--violet) 55%,#7c3aed);
  background-size:180% 100%;
  box-shadow:0 16px 34px -14px rgba(91,140,255,.95),inset 0 1px 0 rgba(255,255,255,.28);
}
.primary:hover,
.ai-btn:hover{
  transform:translateY(-2px);
  background-position:100% 0;
  box-shadow:0 22px 44px -14px rgba(168,85,247,1),inset 0 1px 0 rgba(255,255,255,.35);
}
.primary:active,
.ai-btn:active{transform:translateY(0) scale(.985)}

.primary::after,
.ai-btn::after{
  content:"";
  position:absolute;top:0;left:-60%;
  width:45%;height:100%;
  background:linear-gradient(100deg,transparent,rgba(255,255,255,.35),transparent);
  transform:skewX(-18deg);
  transition:left .6s var(--ease);
}
.primary:hover::after,
.ai-btn:hover::after{left:120%}

.button:focus-visible,
.primary:focus-visible,
.ai-btn:focus-visible,
.tab:focus-visible,
.sidebar button:focus-visible,
.material button:focus-visible,
.logout-small:focus-visible{
  outline:2px solid var(--cyan);
  outline-offset:3px;
}

/* ==========================================================
   TABS
   ========================================================== */
.tabs{
  display:inline-flex;
  gap:6px;
  padding:6px;
  margin-bottom:18px;
  background:rgba(255,255,255,.05);
  border:1px solid var(--line);
  border-radius:16px;
}
.tab{
  padding:10px 22px;
  border-radius:11px;
  background:transparent;
  color:var(--muted);
  font-size:.9rem;
}
.tab:hover{color:#fff;background:rgba(255,255,255,.07)}
.tab.active{
  color:#fff;
  background:linear-gradient(100deg,var(--blue),var(--violet));
  box-shadow:0 10px 24px -10px rgba(91,140,255,.95);
}

/* ==========================================================
   MISC
   ========================================================== */
.hidden{display:none!important}

.message{
  min-height:22px;
  margin:6px 0 0;
  font-size:.88rem;
  color:var(--bad);
}
.success{
  color:var(--ok);
  font-weight:600;
}

/* ==========================================================
   DASHBOARD
   ========================================================== */
.dashboard-grid{
  display:grid;
  grid-template-columns:240px 1fr;
  gap:24px;
  align-items:start;
}

/* ---------- sidebar ---------- */
.sidebar{
  height:max-content;
  position:sticky;
  top:104px;
  padding:18px 14px;
  border-radius:22px;
  background:var(--glass);
  border:1px solid var(--line);
  backdrop-filter:blur(20px);
  -webkit-backdrop-filter:blur(20px);
  box-shadow:var(--shadow);
}
.sidebar h3{
  padding:0 8px;
  margin-bottom:14px;
}
.sidebar button{
  position:relative;
  display:block;
  width:100%;
  margin:6px 0;
  padding:12px 14px 12px 18px;
  text-align:left;
  font-size:.92rem;
  color:var(--muted);
  background:transparent;
  border-radius:12px;
}
.sidebar button::before{
  content:"";
  position:absolute;
  left:6px;top:50%;
  width:3px;height:0;
  border-radius:99px;
  background:linear-gradient(var(--cyan),var(--violet));
  transform:translateY(-50%);
  transition:height .3s var(--ease);
}
.sidebar button:hover{
  color:#fff;
  background:rgba(255,255,255,.07);
  transform:translateX(4px);
}
.sidebar button:hover::before{height:55%}

.sidebar .danger{color:#ffb3bf}
.sidebar .danger:hover{
  color:#fff;
  background:rgba(255,107,129,.16);
}
.sidebar .danger::before{background:linear-gradient(var(--bad),var(--pink))}

/* ---------- content column ---------- */
.content{display:grid;gap:24px;min-width:0}
.content .card{animation:rise .65s var(--ease) both}

/* ---------- materials ---------- */
.material{
  display:flex;
  justify-content:space-between;
  align-items:center;
  gap:14px;
  margin:12px 0;
  padding:18px 20px;
  border-radius:18px;
  background:rgba(255,255,255,.04);
  border:1px solid var(--line);
  border-left:3px solid rgba(91,140,255,.6);
  transition:transform .25s var(--ease),background .25s,border-color .25s,box-shadow .25s;
}
.material:hover{
  transform:translateY(-3px);
  background:rgba(255,255,255,.075);
  border-left-color:var(--cyan);
  box-shadow:0 22px 40px -24px rgba(34,211,238,.8);
}
.material button{
  flex-shrink:0;
  padding:10px 18px;
  border-radius:11px;
  color:#fff;
  background:linear-gradient(100deg,var(--blue),var(--violet));
  box-shadow:0 12px 26px -14px rgba(91,140,255,.95);
}
.material button:hover{transform:translateY(-2px);filter:brightness(1.12)}

/* ---------- AI actions ---------- */
.ai-actions{
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:12px;
  margin:18px 0 6px;
}

/* ---------- output ---------- */
.output{
  white-space:pre-wrap;
  line-height:1.75;
  font-size:.94rem;
  margin-top:18px;
  padding:22px;
  border-radius:18px;
  color:#d7e0f7;
  background:
    linear-gradient(rgba(4,8,20,.78),rgba(4,8,20,.78)),
    linear-gradient(120deg,rgba(91,140,255,.5),rgba(168,85,247,.5));
  border:1px solid var(--line-2);
  box-shadow:inset 0 1px 0 rgba(255,255,255,.06);
}

/* ---------- user chip ---------- */
.user-name{
  font-weight:700;
  background:linear-gradient(100deg,#fff,#b9cdff);
  -webkit-background-clip:text;background-clip:text;color:transparent;
}
.logout-small{
  padding:9px 16px;
  border-radius:99px;
  color:#fff;
  background:rgba(255,255,255,.08);
  border:1px solid var(--line-2);
  backdrop-filter:blur(10px);
  font-size:.85rem;
}
.logout-small:hover{
  background:rgba(255,107,129,.2);
  border-color:rgba(255,107,129,.6);
  transform:translateY(-2px);
}

/* ==========================================================
   RESPONSIVE
   ========================================================== */
@media (max-width:800px){
  .auth-grid,
  .dashboard-grid{grid-template-columns:1fr}

  .sidebar{position:static;top:auto}
  .ai-actions{grid-template-columns:1fr 1fr}

  .hero{padding:30px 24px}
  .hero h2{font-size:1.75rem}

  .card{padding:20px;border-radius:20px}
  .container{width:92%;margin:22px auto 56px}
  .topbar{padding:16px 5%}
  .material{flex-wrap:wrap}
}

@media (max-width:480px){
  .ai-actions{grid-template-columns:1fr}
  .tabs{display:flex;width:100%}
  .tab{flex:1;text-align:center;padding:10px 8px}
  .topbar{flex-wrap:wrap}
}

@media (prefers-reduced-motion:reduce){
  *,*::before,*::after{
    animation-duration:.001ms!important;
    animation-iteration-count:1!important;
    transition-duration:.001ms!important;
  }
}
</style>
</head>
<body>
<header class="topbar"><div><h1>🤖 AI StudyBuddy</h1><p>Learn smarter with Gemini AI</p></div><div id="userBox"></div></header>

<main class="container">
<section id="authSection" class="auth-grid">
  <div class="hero card"><h2>Your personal AI learning assistant</h2><p>Save your study material and use Gemini to create summaries, flashcards, quizzes and study plans.</p><div class="feature-list"><span>📚 Materials</span><span>🤖 Gemini AI</span><span>🧠 Flashcards</span><span>📝 Quizzes</span><span>📅 Study Plans</span></div></div>
  <div class="card auth-card">
    <div class="tabs"><button class="tab active" onclick="showAuth('login')">Login</button><button class="tab" onclick="showAuth('register')">Register</button></div>
    <form id="loginForm"><input name="email" type="email" placeholder="Email" required><input name="password" type="password" placeholder="Password" required><button class="primary">Login</button></form>
    <form id="registerForm" class="hidden"><input name="name" placeholder="Full name" required><input name="email" type="email" placeholder="Email" required><input name="password" type="password" placeholder="Password (6+ characters)" minlength="6" required><button class="primary">Create Account</button></form>
    <p id="authMsg" class="message"></p>
  </div>
</section>

<section id="appSection" class="hidden">
  <div class="dashboard-grid">
    <aside class="card sidebar">
      <h3>StudyBuddy</h3>
      <button onclick="loadMaterials()">📚 My Materials</button>
      <button onclick="showAddMaterial()">➕ Add Material</button>
      <button onclick="showAI()">🤖 AI Tools</button>
      <button class="danger" onclick="logout()">Logout</button>
    </aside>
    <section class="content">
      <div id="materialPanel" class="card"></div>
      <div id="aiPanel" class="card hidden"></div>
      <div id="outputPanel" class="card hidden"></div>
    </section>
  </div>
</section>
</main>
<script src="assets/app.js"></script>
</body>
</html>