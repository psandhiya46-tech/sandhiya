const $=id=>document.getElementById(id);
let selectedMaterial=null;

async function api(url, options={}){
  const r=await fetch(url,options); const d=await r.json().catch(()=>({success:false,message:'Invalid server response'}));
  if(!r.ok && d.message) throw new Error(d.message); return d;
}

function showAuth(type){
  $('loginForm').classList.toggle('hidden',type!=='login'); $('registerForm').classList.toggle('hidden',type!=='register');
  document.querySelectorAll('.tab').forEach((b,i)=>b.classList.toggle('active',(type==='login'?i===0:i===1)));
}

$('loginForm').onsubmit=async e=>{e.preventDefault(); const f=new FormData(e.target); try{const d=await api('api/auth.php?action=login',{method:'POST',body:f}); if(d.success) location.reload(); else $('authMsg').textContent=d.message;}catch(x){$('authMsg').textContent=x.message}};
$('registerForm').onsubmit=async e=>{e.preventDefault(); const f=new FormData(e.target); try{const d=await api('api/auth.php?action=register',{method:'POST',body:f}); $('authMsg').textContent=d.message; if(d.success){showAuth('login'); e.target.reset();}}catch(x){$('authMsg').textContent=x.message}};

async function boot(){try{const d=await api('api/auth.php?action=me'); if(d.success){$('authSection').classList.add('hidden');$('appSection').classList.remove('hidden');$('userBox').innerHTML=`<span class="user-name">Hi, ${escapeHtml(d.data.user.name)}</span> <button class="logout-small" onclick="logout()">Logout</button>`; loadMaterials();}}catch(e){}}

async function logout(){await api('api/auth.php?action=logout');location.reload()}
function showAddMaterial(){
 $('aiPanel').classList.add('hidden'); $('outputPanel').classList.add('hidden'); $('materialPanel').innerHTML=`<h2>➕ Add Study Material</h2><p>Paste notes or upload a simple .txt file.</p><textarea id="newTitle" placeholder="Material title"></textarea><textarea id="newContent" rows="12" placeholder="Paste your study material here..."></textarea><button class="primary" onclick="saveMaterial()">Save Material</button><hr><input id="txtFile" type="file" accept=".txt"><button class="ai-btn" onclick="uploadTxt()">Upload TXT</button>`;
}
async function saveMaterial(){const fd=new FormData();fd.append('title',$('newTitle').value);fd.append('content',$('newContent').value);const d=await api('api/materials.php?action=save',{method:'POST',body:fd});alert(d.message);if(d.success)loadMaterials()}
async function uploadTxt(){const file=$('txtFile').files[0];if(!file)return alert('Choose a .txt file.');const fd=new FormData();fd.append('file',file);const d=await api('api/materials.php?action=upload',{method:'POST',body:fd});alert(d.message);if(d.success)loadMaterials()}
async function loadMaterials(){
 $('aiPanel').classList.add('hidden');$('outputPanel').classList.add('hidden');const d=await api('api/materials.php?action=list');let html='<h2>📚 My Study Materials</h2><button class="primary" onclick="showAddMaterial()">+ Add Material</button>';
 if(!d.data.materials.length)html+='<p>No materials yet. Add your first study material.</p>';
 d.data.materials.forEach(m=>html+=`<div class="material"><div><b>${escapeHtml(m.title)}</b><p>${escapeHtml(m.preview||'')}</p></div><button onclick="selectMaterial(${m.id})">Use with AI</button></div>`);$('materialPanel').innerHTML=html;
}
async function selectMaterial(id){const d=await api('api/materials.php?action=get&id='+id);selectedMaterial=d.data.material;$('materialPanel').innerHTML=`<h2>📖 ${escapeHtml(selectedMaterial.title)}</h2><div class="output">${escapeHtml(selectedMaterial.content)}</div><button class="ai-btn" onclick="showAI()">🤖 Generate AI Content</button>`;showAI()}
function showAI(){if(!selectedMaterial){alert('Select a study material first.');return}$('aiPanel').classList.remove('hidden');$('aiPanel').innerHTML=`<h2>🤖 Gemini AI Tools</h2><p>Selected: <b>${escapeHtml(selectedMaterial.title)}</b></p><div class="ai-actions"><button class="ai-btn" onclick="generateAI('summary')">Summary</button><button class="ai-btn" onclick="generateAI('flashcards')">Flashcards</button><button class="ai-btn" onclick="generateAI('quiz')">Quiz</button><button class="ai-btn" onclick="generateAI('plan')">Study Plan</button></div>`}
async function generateAI(type){$('outputPanel').classList.remove('hidden');$('outputPanel').innerHTML='<h2>🤖 Generating...</h2><p>Please wait while Gemini creates your content.</p>';const fd=new FormData();fd.append('action',type);fd.append('material_id',selectedMaterial.id);try{const d=await api('api/ai.php',{method:'POST',body:fd});let content=d.data.content;if(type==='flashcards'||type==='quiz'){try{const json=JSON.parse(content);content='<div class="output">'+json.map((x,i)=>type==='flashcards'?`<p><b>${i+1}. ${escapeHtml(x.question)}</b><br>${escapeHtml(x.answer)}</p>`:`<p><b>${i+1}. ${escapeHtml(x.question)}</b><br>${x.options.map(o=>'• '+escapeHtml(o)).join('<br>')}<br><b>Answer:</b> ${escapeHtml(x.answer)}</p>`).join('<hr>')+'</div>'}catch(e){content='<div class="output">'+escapeHtml(content)+'</div>'}}else content='<div class="output">'+escapeHtml(content)+'</div>';$('outputPanel').innerHTML='<h2>✨ '+type.charAt(0).toUpperCase()+type.slice(1)+'</h2>'+content;$('outputPanel').scrollIntoView({behavior:'smooth'})}catch(e){$('outputPanel').innerHTML='<h2>AI Error</h2><p>'+escapeHtml(e.message)+'</p>'}}
function escapeHtml(v){return String(v??'').replace(/[&<>'"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[c]))}
boot();
