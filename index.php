<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AI StudyBuddy</title>
<link rel="stylesheet" href="assets/style.css">
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
