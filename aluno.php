<?php
session_start();
require 'conexao.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['usuario'])) {
    $current = $_SERVER['REQUEST_URI'];
    header('Location: index.php?openLogin');
    exit();
}

$user = $_SESSION['usuario'];
$cargo = $user['cargo'];
$userId = $user['id'];

// Buscar todas as atividades
$stmt = $conn->query("SELECT a.*, u.nome as professor_nome FROM atividades a JOIN usuarios u ON a.professor_id = u.id");
$atividades = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Atividades do professor logado
$minhasAtividades = [];
if ($cargo === 'Professor') {
    $stmt = $conn->prepare("SELECT * FROM atividades WHERE professor_id = ?");
    $stmt->execute([$userId]);
    $minhasAtividades = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cabeçalho</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" type="image/png" href="./img/olho logo.png"> 
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .user-card { background: rgba(255,255,255,0.95); border-radius: 8px; padding: 8px 12px; display: flex; align-items: center; gap: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.06); }
    .user-name { font-weight: 600; color: #111827; }
    .user-email { font-size: 12px; color: #6b7280; line-height: 1; }
    .user-avatar { width: 40px; height: 40px; border-radius: 9999px; background: linear-gradient(135deg,#ef4444,#f97316); display:flex; align-items:center; justify-content:center; color:white; font-weight:700; }
    .fade-in { opacity: 0; transform: translateY(30px); transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);}
    .fade-in.visible { opacity: 1; transform: translateY(0);}
    .slide-in-left { opacity: 0; transform: translateX(-50px); transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1); }
    .slide-in-left.visible { opacity: 1; transform: translateX(0); }
    .slide-in-right { opacity: 0; transform: translateX(50px); transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1); }
        #colorFilter {
      display: flex;
      justify-content: center;
      align-items: center;
      width: 250px;
      color: black;
      background: rgba(255, 255, 255, 0.95);
      border-radius: 8px;
      font-family: sans-serif;
      box-shadow: 0 0 10px rgba(0,0,0,0.2);
      z-index: 10;
    }

    label, select {
      font-size: 14px;
      color: #333;
    }
    .slide-in-right.visible { opacity: 1; transform: translateX(0); }

    .section-title {
        text-align: center;
        font-family: 'Playfair Display', serif;
        font-size: 3.5rem;
        font-weight: 600;
        margin-bottom: 4rem;
        color: #1f2937;
        position: relative;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -15px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #dc2626, #ef4444);
        border-radius: 2px;
    }

    /* Modern calendar with clean design */
    .calendar-section {
        background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
        padding: 6rem 2rem;
        margin: 4rem 0;
    }

    .calendar-container {
        max-width: 900px;
        margin: 0 auto;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 24px;
        padding: 3rem;
        backdrop-filter: blur(20px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2.5rem;
    }

    .calendar-nav {
        background: #dc2626;
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }

    .calendar-nav:hover {
        background: #b91c1c;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
    }

    #currentMonth {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        color: #1f2937;
        font-weight: 600;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 0.5rem;
    }

    .calendar-day {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f9fafb;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        font-weight: 500;
        color: #374151;
    }

    .calendar-day:hover {
        background: #fef2f2;
        color: #dc2626;
        transform: scale(1.05);
    }

    .calendar-day.has-activity {
        background: #dc2626;
        color: white;
    }

    .calendar-day.has-activity::after {
        content: '●';
        position: absolute;
        bottom: 4px;
        right: 4px;
        font-size: 0.5rem;
    }

    /* Improved responsive design */
    @media (max-width: 768px) {
        .calendar-container {
            padding: 2rem;
        }
    }
  </style>
</head>
<body class="bg-gray-100">
  <header class="bg-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
      <!-- Logo -->
      <div class="flex items-center space-x-3">
        <img src="img/download.png" alt="Logo" class="w-18 h-14">
      </div>

      <!-- Menu -->
        <nav class="hidden md:flex space-x-6 text-gray-700 font-medium">
          <a href="index.php" class="hover:text-blue-600">Sobre Nós</a>

          <?php if ($_SESSION['usuario']['cargo'] === null || $_SESSION['usuario']['cargo'] === 'Aluno'): ?>
            <a href="vestibulares.php" class="hover:text-blue-600">Vestibulares</a>
            <a href="bolsas.php" class="hover:text-blue-600">Bolsas</a>
            <a href="painelProfessor.php" class="hover:text-blue-600">Professores Referência</a>
          <?php elseif ($_SESSION['usuario']['cargo'] === 'Professor'): ?>
            <a href="painelProfessor.php" class="hover:text-blue-600">Gerenciar Atividades</a>
          <?php endif; ?>

          <a href="aluno.php"  class="text-blue-600" class="hover:text-blue-600">Página do Aluno</a>
        </nav>

      <div class="flex items-center space-x-4">
        <?php if (isset($_SESSION['usuario']) && is_array($_SESSION['usuario'])): 
            $user = $_SESSION['usuario'];
            $nomeSafe = htmlspecialchars($user['nome'], ENT_QUOTES, 'UTF-8');
            $emailSafe = htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8');
            $iniciais = '';
            $parts = preg_split('/\s+/', trim($user['nome']));
            if ($parts) {
                $iniciais = strtoupper(substr($parts[0],0,1) . (isset($parts[1])?substr($parts[1],0,1):''));
            }
        ?>
          <div class="user-card">
            <div class="user-avatar"><?php echo $iniciais ?: 'U'; ?></div>
            <div class="text-left">
              <div class="user-name"><?php echo $nomeSafe; ?></div>
              <div class="user-email"><?php echo $emailSafe; ?></div>
            </div>
           <form action="logout.php" method="post" class="ml-3">
               <button type="submit" 
                  class="flex items-center gap-2 bg-red-600 text-white px-3 py-2 rounded-lg hover:bg-red-700 transition text-sm">
                  <i class="fas fa-sign-out-alt text-white"></i> Sair
                </button>
            </form>
          </div>
        <?php else: ?>
          <button id="openAuth" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Entrar</button>
        <?php endif; ?>
      </div>
    </div>
  </header>
  <div class="cabecalho">
  <div class="texto-cabecalho">
      
      <style>
    

     .destaque {
      color: #ff3c3c;
      font-weight: bold;
      text-shadow: 0 0 8px rgba(255, 60, 60, 0.6);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      letter-spacing: 1px;
    }
    .texto-cabecalho .meio {
      font-size: 3.5rem;
      font-weight: bold;
    }
</style>
    </div>
  </div>

<section class="bg-black py-16 px-6 md:px-12 lg:px-24 slide-in-left">
  <h2 class="text-3xl font-bold text-white mb-6 text-center">Alunos Referência</h2>
  <p class="text-gray-300 text-center mb-12 max-w-2xl mx-auto">
    Eles começaram como alunos dedicados e hoje são profissionais que inspiram. Conheça quem transformou esforço em conquista.
  </p>

  <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">
    <!-- Bernardo -->
    <div class="bg-gray-100 rounded-xl shadow-md hover:shadow-lg transition overflow-hidden">
      <img src="img/Bernardo.jpg" alt="Bernardo" class="w-full h-[36rem] object-cover">
      <div class="p-5 text-center">
        <h3 class="text-lg font-semibold text-gray-800 mb-1">Bernardo Henrique</h3>
        <p class="text-sm text-red-500 mb-1">Inovador em tecnologia educacional</p>
        <p class="text-sm text-gray-600">Criou uma plataforma gamificada que já ajudou milhares de alunos no ENEM.</p>
      </div>
    </div>

    <!-- Lara -->
    <div class="bg-gray-100 rounded-xl shadow-md hover:shadow-lg transition overflow-hidden">
      <img src="img/Lara.jpg" alt="Lara" class="w-full h-[36rem] object-cover">
      <div class="p-5 text-center">
        <h3 class="text-lg font-semibold text-gray-800 mb-1">Lara Cardoso</h3>
        <p class="text-sm text-red-500 mb-1">Especialista em Recursos Humanos</p>
        <p class="text-sm text-gray-600">Hoje atua como gestora de RH em uma multinacional.</p>
      </div>
    </div>

    <!-- Júlia -->
    <div class="bg-gray-100 rounded-xl shadow-md hover:shadow-lg transition overflow-hidden">
      <img src="img/Julia.jpg" alt="Júlia" class="w-full h-[36rem] object-cover">
      <div class="p-5 text-center">
        <h3 class="text-lg font-semibold text-gray-800 mb-1">Júlia Martins</h3>
        <p class="text-sm text-red-500 mb-1">Comentarista esportiva</p>
        <p class="text-sm text-gray-600">Comenta jogos em rede nacional com carisma e técnica refinada.</p>
      </div>
    </div>
   

    <!-- Letícia -->
    <div class="bg-gray-100 rounded-xl shadow-md hover:shadow-lg transition overflow-hidden">
      <img src="img/Leticia.png" alt="Letícia" class="w-full h-[36rem] object-cover">
      <div class="p-5 text-center">
        <h3 class="text-lg font-semibold text-gray-800 mb-1">Letícia Vito</h3>
        <p class="text-sm text-red-500 mb-1">Formada em Odontologia</p>
        <p class="text-sm text-gray-600">Hoje atende em sua própria clínica com excelência e empatia.</p>
      </div>
    </div>

    <!-- Fernanda -->
    <div class="bg-gray-100 rounded-xl shadow-md hover:shadow-lg transition overflow-hidden">
      <img src="img/Fernanda.jpg" alt="Fernanda" class="w-full h-[36rem] object-cover">
      <div class="p-5 text-center">
        <h3 class="text-lg font-semibold text-gray-800 mb-1">Fernanda Conejo</h3>
        <p class="text-sm text-red-500 mb-1">Psicóloga clínica e educacional</p>
        <p class="text-sm text-gray-600">Atua com jovens em fase pré-vestibular, promovendo acolhimento e escuta ativa.</p>
      </div>
    </div>

     <!-- Pietra -->
    <div class="bg-gray-100 rounded-xl shadow-md hover:shadow-lg transition overflow-hidden">
      <img src="img/pipiii.png" alt="Pietra" class="w-full h-[36rem] object-cover">
      <div class="p-5 text-center">
        <h3 class="text-lg font-semibold text-gray-800 mb-1">Pietra Fazani</h3>
        <p class="text-sm text-red-500 mb-1">Autora publicada aos 19 anos</p>
        <p class="text-sm text-gray-600">Lançou seu primeiro romance sobre juventude e seus fetiches literários.</p>
      </div>
    </div>
  </div>
</section>

<?php if ($_SESSION['usuario']['cargo'] === 'Aluno'): ?>
<section style="background-color: #7A1F2B; color: #fff; font-family: 'Inter', sans-serif; border-radius: 12px; max-width: 640px; margin: 3rem auto; padding: 2rem; text-align: center; box-shadow: 0 6px 16px rgba(0,0,0,0.15);">
  <h2 style="font-size: 1.75rem; font-weight: 600; margin-bottom: 0.75rem;">Ainda em dúvida sobre o seu caminho?</h2>
  <p style="font-size: 1rem; font-weight: 400; margin-bottom: 1.5rem;">Responda algumas perguntas e receba uma sugestão personalizada com base no seu perfil.</p>
  <a href="testevocal.html" style="background-color: #fff; color: #7A1F2B; font-weight: 600; font-size: 0.95rem; padding: 0.6rem 1.5rem; border-radius: 999px; text-decoration: none; transition: background 0.3s;">
    Fazer o teste
  </a>
</section>

<!-- SECTION — Foguinho / Streak (com rostinho, mãozinha e pezinho) -->
<section class="streak-fire fade-in" style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:48px 0;background:#fff;user-select:none">

  <!-- Fogo + partículas -->
  <div id="fireWrap" class="fire-wrap" style="position:relative">
    <!-- brilho externo -->
    <div id="glow" class="glow" style="position:absolute;inset:-32px;border-radius:999px;filter:blur(36px);opacity:.4;background:radial-gradient(circle,#ff3b30 0%,rgba(255,59,48,0) 60%);pointer-events:none"></div>

    <!-- Partículas -->
    <div class="sparks" aria-hidden="true" style="position:absolute;inset:0;pointer-events:none">
      <span></span><span></span><span></span><span></span><span></span>
    </div>

    <!-- Foguinho SVG -->
    <svg id="fireSVG" width="180" height="210" viewBox="0 0 120 150" xmlns="http://www.w3.org/2000/svg" class="drop">
      <defs>
        <!-- Cores por nível -->
        <linearGradient id="g-base" x1="0" y1="0" x2="0" y2="1">
          <stop offset="0%"  stop-color="#FF5A4F"/>
          <stop offset="100%" stop-color="#B00000"/>
        </linearGradient>
        <linearGradient id="g-10" x1="0" y1="0" x2="0" y2="1">
          <stop offset="0%"  stop-color="#FFD54F"/>
          <stop offset="100%" stop-color="#FF8F00"/>
        </linearGradient>
        <linearGradient id="g-50" x1="0" y1="0" x2="0" y2="1">
          <stop offset="0%"  stop-color="#56CCF2"/>
          <stop offset="100%" stop-color="#2F80ED"/>
        </linearGradient>
        <linearGradient id="g-100" x1="0" y1="0" x2="0" y2="1">
          <stop offset="0%"  stop-color="#E56CF3"/>
          <stop offset="100%" stop-color="#8E24AA"/>
        </linearGradient>

        <!-- brilho interno -->
        <radialGradient id="inner" cx="50%" cy="65%" r="60%">
          <stop offset="0%" stop-color="#FFFFFF" stop-opacity=".45"/>
          <stop offset="100%" stop-color="#FFFFFF" stop-opacity="0"/>
        </radialGradient>

        <!-- sombra suave -->
        <filter id="softShadow" x="-30%" y="-30%" width="160%" height="160%">
          <feDropShadow dx="0" dy="6" stdDeviation="6" flood-color="#000" flood-opacity=".22"/>
        </filter>
      </defs>

      <!-- Braçinhos / mãozinhas -->
      <g id="arms" stroke-linecap="round" fill="none" stroke="#111" stroke-width="5">
        <!-- esquerdo -->
        <path d="M32 100 q-14 -4 -18 6" />
        <!-- direito (vai acenar nos marcos) -->
        <path id="arm-right" d="M88 100 q14 -4 18 6" />
        <!-- pontas (mãozinhas) -->
        <circle cx="13" cy="106" r="3.5" fill="#111"/>
        <circle id="hand-right" cx="107" cy="106" r="3.5" fill="#111"/>
      </g>

      <!-- Corpo da chama -->
      <g class="flame wobble" filter="url(#softShadow)">
        <path id="flameOuter"
          d="M60 8
             C48 26,28 40,26 72
             C24 108,44 130,60 138
             C76 130,96 108,94 72
             C92 44,76 32,68 16
             C65 11,63 8,60 8 Z"
          fill="url(#g-base)"/>

        <!-- brilho interno -->
        <path
          d="M60 26
             C52 38,40 48,39 70
             C38 94,51 114,60 120
             C69 114,82 94,81 70
             C80 52,70 44,66 32
             C64 28,62 26,60 26 Z"
          fill="url(#inner)"/>
      </g>

      <!-- Rostinho (olhos, bochecha, boca) -->
      <g id="face" class="face-bob" transform="translate(0,0)">
        <!-- bochechas -->
        <ellipse cx="45" cy="96" rx="6.5" ry="4.2" fill="#ff9aa2" opacity=".55"/>
        <ellipse cx="75" cy="96" rx="6.5" ry="4.2" fill="#ff9aa2" opacity=".55"/>
        <!-- olhos -->
        <g class="eye">
          <ellipse cx="48" cy="92" rx="8.2" ry="7" fill="#fff"/>
          <ellipse id="pupilL" cx="49" cy="93" rx="3.2" ry="3.6" fill="#111"/>
          <rect class="blink" x="40" y="84" width="16" height="14" fill="#fff"/>
        </g>
        <g class="eye">
          <ellipse cx="72" cy="92" rx="8.2" ry="7" fill="#fff"/>
          <ellipse id="pupilR" cx="71" cy="93" rx="3.2" ry="3.6" fill="#111"/>
          <rect class="blink" x="64" y="84" width="16" height="14" fill="#fff"/>
        </g>
        <!-- boca -->
        <path id="mouth" d="M48 105 Q60 112 72 105" stroke="#111" stroke-width="4" fill="none" stroke-linecap="round"/>
      </g>
    </svg>
  </div>

  <!-- Texto (mantido) -->
  <h2 style="margin-top:24px;font-size:1.5rem;font-weight:800;color:#111">Sequência de estudos</h2>
  <p style="color:#4b5563;margin:6px 0 0">Mantenha sua chama acesa todos os dias 🔥</p>

  <!-- Contador -->
  <div style="margin-top:18px;display:flex;align-items:center;gap:12px;background:#000;color:#fff;padding:12px 20px;border-radius:999px;box-shadow:0 6px 16px rgba(0,0,0,.2)">
    <span id="streak-count" style="color:#ef4444;font-weight:800;font-size:1.875rem;line-height:1">0</span>
    <span style="font-size:1rem">dias seguidos</span>
  </div>
</section>

<style>
/* movimento de corpo da chama */
.wobble{ transform-origin:50% 85%; animation:wob 1.6s ease-in-out infinite alternate; }
@keyframes wob{ 0%{transform:rotate(-1deg) scale(1)} 50%{transform:rotate(1deg) scale(1.03)} 100%{transform:rotate(-.8deg) scale(.99)} }

/* rosto "respirando" levemente */
.face-bob{ animation:bob 2.1s ease-in-out infinite; transform-origin:50% 90%; }
@keyframes bob{ 0%,100%{transform:translateY(0)} 50%{transform:translateY(-2px)} }

/* piscar de olhos */
.blink{ animation:blink 4.5s infinite; transform-origin:50% 50%; }
@keyframes blink{ 0%,92%,100%{transform:scaleY(0)} 94%{transform:scaleY(1)} 96%{transform:scaleY(0)} }

/* partículas */
.sparks span{
  position:absolute; left:50%; bottom:10px; width:6px; height:6px; border-radius:999px; background:#ffb3a8;
  opacity:0; transform:translateX(-50%); animation:sparkUp 2.2s linear infinite;
}
.sparks span:nth-child(1){ left:46%; animation-delay:.1s}
.sparks span:nth-child(2){ left:54%; animation-delay:.5s}
.sparks span:nth-child(3){ left:42%; animation-delay:1s}
.sparks span:nth-child(4){ left:58%; animation-delay:1.4s}
.sparks span:nth-child(5){ left:50%; animation-delay:1.8s}
@keyframes sparkUp{ 0%{transform:translate(-50%,0) scale(.7);opacity:0} 10%{opacity:.7} 100%{transform:translate(-50%,-70px) scale(.3);opacity:0} }

/* upgrades por nível (cor + energia) */
.fire-wrap.level-10 #flameOuter{ fill:url(#g-10) }
.fire-wrap.level-10 .glow{ background:radial-gradient(circle,#ffb300 0%,rgba(255,179,0,0) 60%) }
.fire-wrap.level-10 .wobble{ animation-duration:1.35s }

.fire-wrap.level-50 #flameOuter{ fill:url(#g-50) }
.fire-wrap.level-50 .glow{ background:radial-gradient(circle,#56ccf2 0%,rgba(86,204,242,0) 60%) }
.fire-wrap.level-50 .wobble{ animation-duration:1.1s }
.fire-wrap.level-50 #arm-right, .fire-wrap.level-50 #hand-right{ animation:wave .9s ease-in-out infinite }

.fire-wrap.level-100 #flameOuter{ fill:url(#g-100) }
.fire-wrap.level-100 .glow{ background:radial-gradient(circle,#e56cf3 0%,rgba(229,108,243,0) 60%) }
.fire-wrap.level-100 .wobble{ animation:wob .85s cubic-bezier(.4,0,.2,1) infinite alternate }
.fire-wrap.level-100 #mouth{ d:path("M44 104 Q60 118 76 104") } /* sorriso maior */
.fire-wrap.level-100 #arm-right, .fire-wrap.level-100 #hand-right{ animation:wave .65s ease-in-out infinite }

@keyframes wave{ 0%{transform:rotate(0deg);transform-origin:100px 100px} 50%{transform:rotate(12deg)} 100%{transform:rotate(0deg)} }

/* responsivo */
@media (max-width:420px){ #fireSVG{ width:150px;height:185px } }
</style>

<script>
/* streak + níveis */
let streak = parseInt(localStorage.getItem("streak") || "0");
const $count = document.getElementById("streak-count");
const $wrap  = document.getElementById("fireWrap");

function paintLevel(){
  $wrap.classList.remove("level-10","level-50","level-100");
  if(streak >= 100) $wrap.classList.add("level-100");
  else if(streak >= 50) $wrap.classList.add("level-50");
  else if(streak >= 10) $wrap.classList.add("level-10");
}

function render(){
  $count.textContent = streak;
  paintLevel();
}

/* APIs públicas */
window.addStudyDay = function(){
  streak++;
  localStorage.setItem("streak", String(streak));
  render();
};
window.resetStreak = function(){
  streak = 0;
  localStorage.setItem("streak","0");
  render();
};

render();
</script>

<!-- Calendário de Atividades -->
<section class="calendar-section">
    <div class="calendar-container fade-in">
        <h2 class="section-title" style="color: #1f2937; margin-bottom: 2rem;">Calendário de Atividades</h2>
        
        <div class="calendar-header">
            <button class="calendar-nav" onclick="previousMonth()">‹ Anterior</button>
            <h3 id="currentMonth">Janeiro 2024</h3>
            <button class="calendar-nav" onclick="nextMonth()">Próximo ›</button>
        </div>
        
        <div class="calendar-grid" id="calendarGrid">
            <!-- Dias serão gerados pelo JavaScript -->
        </div>
    </div>
</section>
<?php endif; ?>

 <section class="bg-white text-black py-16 px-6 fade-in">
  <!-- Título e Introdução -->
  <div class="max-w-4xl mx-auto mb-12 text-center">
    <h2 class="text-4xl font-bold text-red-600 mb-4">Saúde Mental para Estudantes</h2>
    <p class="text-lg leading-relaxed">
      Para se tornar um profissional de destaque, não basta dominar técnicas ou acumular diplomas.  
      O verdadeiro diferencial está em saber cuidar de si — emocionalmente, mentalmente e humanamente.  
      Esta seleção de vídeos foi feita para acolher, fortalecer e inspirar quem está nessa jornada de crescimento.
    </p>
  </div>

  <!-- Grid de vídeos -->
  <div class="grid md:grid-cols-2 gap-10 max-w-5xl mx-auto">
    <!-- Vídeo 1 -->
    <div class="border border-red-600 rounded-lg p-4 shadow-md">
      <h3 class="text-xl font-semibold text-red-600 mb-2">Saúde mental tem jeito sim</h3>
      <iframe class="w-full h-64 rounded" src="https://www.youtube.com/embed/fEA04zSTpYM" frameborder="0" allowfullscreen></iframe>
    </div>

    <!-- Vídeo 2 -->
    <div class="border border-red-600 rounded-lg p-4 shadow-md">
      <h3 class="text-xl font-semibold text-red-600 mb-2">25 Mini-hábitos para saúde mental</h3>
      <iframe class="w-full h-64 rounded" src="https://www.youtube.com/embed/JtcnHBEi5GQ" frameborder="0" allowfullscreen></iframe>
    </div>

    <!-- Vídeo 3 -->
    <div class="border border-red-600 rounded-lg p-4 shadow-md">
      <h3 class="text-xl font-semibold text-red-600 mb-2">Diagnóstico e tratamento psicológico</h3>
      <iframe class="w-full h-64 rounded" src="https://www.youtube.com/embed/pMGCrAESw_U" frameborder="0" allowfullscreen></iframe>
    </div>

    <!-- Vídeo 4 -->
    <div class="border border-red-600 rounded-lg p-4 shadow-md">
      <h3 class="text-xl font-semibold text-red-600 mb-2">Quando buscar ajuda?</h3>
      <iframe class="w-full h-64 rounded" src="https://www.youtube.com/embed/xxJ9q9wbB1Q" frameborder="0" allowfullscreen></iframe>
    </div>

    <!-- Vídeo 5 -->
    <div class="border border-red-600 rounded-lg p-4 shadow-md">
      <h3 class="text-xl font-semibold text-red-600 mb-2">Tecnologia e saúde mental</h3>
      <iframe class="w-full h-64 rounded" src="https://www.youtube.com/embed/KKCucL0H3TY" frameborder="0" allowfullscreen></iframe>
    </div>

    <!-- Vídeo 6 -->
    <div class="border border-red-600 rounded-lg p-4 shadow-md">
      <h3 class="text-xl font-semibold text-red-600 mb-2">Como ajudar entes queridos</h3>
      <iframe class="w-full h-64 rounded" src="https://www.youtube.com/embed/qon88u3zPck" frameborder="0" allowfullscreen></iframe>
    </div>
  </div>
</section>


<section class="books-section fade-in" aria-labelledby="books-title">
    <h2 id="books-title">Nossos Livros Digitais</h2>

    <div class="books-viewport" id="booksViewport" role="region" aria-label="Carrossel de livros">
      <button class="books-btn prev" id="btnPrev" aria-label="Anterior">
        <i class="fas fa-chevron-left"></i>
      </button>

      <div class="carousel-track" id="carouselTrack">
        <div class="book-card">
          <img src="./livros/aguafunda.jpg" alt="Livro 1">
          <div>
            <h3>Livro 1</h3>
            <a class="btn-download" href="./livros/aguafunda.pdf" download>Baixar</a>
          </div>
        </div>

        <div class="book-card">
          <img src="./livros/avidanaoeutil.jpg" alt="Livro 2">
          <div>
            <h3>Livro 2</h3>
            <a class="btn-download" href="./livros/avidanaoeutil.pdf" download>Baixar</a>
          </div>
        </div>

        <div class="book-card">
          <img src="./livros/casaderamires.jpg" alt="Livro 3">
          <div>
            <h3>Livro 3</h3>
            <a class="btn-download" href="./livros/casaderamires.pdf" download>Baixar</a>
          </div>
        </div>

        <div class="book-card">
          <img src="./livros/casavelha.jpg" alt="Livro 4">
          <div>
            <h3>Livro 4</h3>
            <a class="btn-download" href="./livros/casavelha.pdf" download>Baixar</a>
          </div>
        </div>

        <div class="book-card">
          <img src="./livros/domcasmurro.jpg" alt="Livro 5">
          <div>
            <h3>Livro 5</h3>
            <a class="btn-download" href="./livros/domcasmurro.pdf" download>Baixar</a>
          </div>
        </div>

        <div class="book-card">
          <img src="./livros/iracema.jpg" alt="Livro 6">
          <div>
            <h3>Livro 6</h3>
            <a class="btn-download" href="./livros/iracema.pdf" download>Baixar</a>
          </div>
        </div>

        <div class="book-card">
          <img src="./livros/macunaima.jpg" alt="Livro 7">
          <div>
            <h3>Livro 7</h3>
            <a class="btn-download" href="./livros/macunaima.pdf" download>Baixar</a>
          </div>
        </div>

        <div class="book-card">
          <img src="./livros/mariliadedirceu.jpg" alt="Livro 8">
          <div>
            <h3>Livro 8</h3>
            <a class="btn-download" href="./livros/marilia de dirceu.pdf" download>Baixar</a>
          </div>
        </div>

        <div class="book-card">
          <img src="./livros/morangosmofados.jpg" alt="Livro 9">
          <div>
            <h3>Livro 9</h3>
            <a class="btn-download" href="./livros/morangosmofados.pdf" download>Baixar</a>
          </div>
        </div>

        <div class="book-card">
          <img src="./livros/olhosdagua.jpg" alt="Livro 10">
          <div>
            <h3>Livro 10</h3>
            <a class="btn-download" href="./livros/olohosdagua.pdf" download>Baixar</a>
          </div>
        </div>

        <div class="book-card">
          <img src="./livros/ocortiço.jpg" alt="Livro 11">
          <div>
            <h3>Livro 11</h3>
            <a class="btn-download" href="./livros/ocotiço.pdf" download>Baixar</a>
          </div>
        </div>

        <div class="book-card">
          <img src="./livros/quincasborba.jpg" alt="Livro 12">
          <div>
            <h3>Livro 12</h3>
            <a class="btn-download" href="./livros/Quincas_Borba.pdf" download>Baixar</a>
          </div>
        </div>
      </div>

      <button class="books-btn next" id="btnNext" aria-label="Próximo">
        <i class="fas fa-chevron-right"></i>
      </button>
    </div>
  </section>

    <script>
    (function () {
      const els = document.querySelectorAll('.fade-in, .slide-in-left, .slide-in-right');
      if (!('IntersectionObserver' in window)) {
        // fallback: mostrar tudo
        els.forEach(e => e.classList.add('visible'));
        return;
      }
      const io = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            io.unobserve(entry.target);
          }
        });
      }, { threshold: 0.12 });
      els.forEach(el => io.observe(el));
    })();

    (function () {
      const track = document.getElementById('carouselTrack');
      const btnPrev = document.getElementById('btnPrev');
      const btnNext = document.getElementById('btnNext');
      if (!track || !btnPrev || !btnNext) return;

      const cards = Array.from(track.children);
      let index = 0;

      function getVisibleCount() {
        const w = window.innerWidth;
        if (w <= 640) return 1;
        if (w <= 1024) return 2;
        return 3;
      }

      function updateButtons() {
        const visible = getVisibleCount();
        btnPrev.disabled = index <= 0;
        btnNext.disabled = index >= cards.length - visible;
      }

      function updateTrack() {
        // calcular deslocamento em px conforme largura do primeiro card + gap
        const firstCard = cards[0];
        const style = getComputedStyle(firstCard);
        const gap = 20; // conforme CSS
        const cardWidth = firstCard.getBoundingClientRect().width;
        const step = cardWidth + gap;
        const translateX = - index * step;
        track.style.transform = `translateX(${translateX}px)`;
        updateButtons();
      }

      // handlers
      btnNext.addEventListener('click', () => {
        const visible = getVisibleCount();
        if (index < cards.length - visible) index++;
        updateTrack();
      });
      btnPrev.addEventListener('click', () => {
        if (index > 0) index--;
        updateTrack();
      });

      // recomputar no resize
      let resizeTimer = null;
      window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
          // assegura que index não excede o limite após resize
          const visible = getVisibleCount();
          if (index > cards.length - visible) index = Math.max(0, cards.length - visible);
          updateTrack();
        }, 120);
      });

      // inicializa depois do carregamento das imagens (para ter medidas corretas)
      window.addEventListener('load', () => {
        // garantir que imagens carreguem para calcular largura
        setTimeout(updateTrack, 50);
      });
      // caso o DOM já tenha carregado
      setTimeout(updateTrack, 200);
    })();
  </script>

<style>
    .books-section { padding: 50px 20px; background: #fff; text-align: center; }
    .books-section h2 { font-size: 2rem; color: #000; margin-bottom: 22px; border-bottom: 3px solid #ff3c3c; display: inline-block; padding-bottom: 5px; }

    .books-viewport {
      position: relative;
      max-width: 1100px;
      margin: 0 auto;
      overflow: hidden;
      padding: 12px 40px; /* espaço para botões */
    }

    .carousel-track {
      display: flex;
      gap: 20px;
      transition: transform 450ms cubic-bezier(.22,.9,.33,1);
      will-change: transform;
      align-items: stretch;
    }

    /* cada card responsivo — em desktop mostramos 3 por viewport */
    .book-card {
      min-width: calc((100% / 3) - (20px * (2/3)));
      background: #000;
      color: #fff;
      border-radius: 10px;
      padding: 16px;
      box-shadow: 0 6px 16px rgba(0,0,0,0.12);
      display: flex;
      flex-direction: column;
      align-items: stretch;
      justify-content: space-between;
    }

    .book-card img { margin: 0 auto; width: 190px; height: 250px;  object-fit: cover; border-radius: 8px; margin-bottom: 12px; }
    .book-card h3 { margin: 0 0 8px; font-size: 1.05rem; color: #fff; }
    .btn-download { display:inline-block; padding: 10px 14px; background:#ff3c3c; color:#fff; border-radius:8px; font-weight:700; text-decoration:none; text-align:center; }

   .books-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 40px;
  height: 40px;
  border-radius: 999px;
  background: rgba(255,60,60,0.95);
  color: #fff;
  border: none;
  display:flex;
  align-items:center;
  justify-content:center;
  cursor:pointer;
  box-shadow: 0 6px 16px rgba(0,0,0,0.15);
  z-index: 30; /* garante que a seta fique acima do track/carousel */
}

.books-btn[disabled] { opacity: 0.45; cursor: not-allowed; }

.books-btn.prev {
  left: 8px;
  transform: none;    
}

.books-btn.next {
  right: 8px;
}

.carousel-track {
  z-index: 10;
  position: relative; 
}


@media (max-width: 640px) {
  .books-btn.prev { top: 8px; left: 6px; }
  .books-btn.next { right: 6px; }
}
</style>

<!-- Modal Lista -->
<div id="listModal" class="fixed inset-0 hidden bg-black bg-opacity-50 flex items-center justify-center">
  <div class="bg-white p-6 rounded-lg w-96">
    <h2 class="text-xl font-bold mb-4">Atividades do Dia</h2>
    <ul id="activityList" class="space-y-2 text-gray-700"></ul>
    <button onclick="closeListModal()" class="mt-4 bg-red-600 text-white px-3 py-2 rounded">Fechar</button>
  </div>
</div>

<!-- Modal Adicionar -->
<div id="addModal" class="fixed inset-0 hidden bg-black bg-opacity-50 flex items-center justify-center">
  <div class="bg-white p-6 rounded-lg w-96">
    <h2 class="text-xl font-bold mb-4">Adicionar Atividade</h2>
    <form action="salvar_atividade.php" method="POST" class="space-y-3">
      <input type="hidden" name="professor_id" value="<?php echo $userId; ?>">
      <label class="block">Data:
        <input type="date" name="data" class="w-full border rounded p-2" required>
      </label>
      <label class="block">Título:
        <input type="text" name="titulo" class="w-full border rounded p-2" required>
      </label>
      <label class="block">Descrição:
        <textarea name="descricao" class="w-full border rounded p-2" required></textarea>
      </label>
      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeAddModal()" class="bg-gray-400 px-3 py-2 rounded">Cancelar</button>
        <button type="submit" class="bg-green-600 text-white px-3 py-2 rounded">Salvar</button>
      </div>
    </form>
  </div>
</div>

<script>
let currentDate = new Date();
const months = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];

// Atividades vindas do PHP
const activities = <?php echo json_encode($atividades); ?>;

function generateCalendar(){
  const year = currentDate.getFullYear();
  const month = currentDate.getMonth();
  document.getElementById("currentMonth").textContent = months[month] + " " + year;

  const firstDay = new Date(year, month, 1).getDay();
  const daysInMonth = new Date(year, month+1, 0).getDate();
  const grid = document.getElementById("calendarGrid");
  grid.innerHTML = "";

  const weekDays = ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'];
  weekDays.forEach(d=>{
    const el=document.createElement("div");
    el.className="font-bold text-center";
    el.textContent=d;
    grid.appendChild(el);
  });

  for(let i=0;i<firstDay;i++){
    grid.appendChild(document.createElement("div"));
  }

  for(let d=1;d<=daysInMonth;d++){
    const el=document.createElement("div");
    el.className="calendar-day border p-2 text-center rounded cursor-pointer hover:bg-red-100";
    el.textContent=d;
    const dateKey=`${year}-${String(month+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
    const hasActs=activities.filter(a=>a.data===dateKey);
    if(hasActs.length>0){
      el.classList.add("bg-red-600","text-white");
    }
    el.onclick=()=>openListModal(dateKey, hasActs);
    grid.appendChild(el);
  }
}

function previousMonth(){currentDate.setMonth(currentDate.getMonth()-1);generateCalendar();}
function nextMonth(){currentDate.setMonth(currentDate.getMonth()+1);generateCalendar();}

function openListModal(date, acts){
  const list=document.getElementById("activityList");
  list.innerHTML="";
  if(acts.length===0){
    list.innerHTML="<li>Nenhuma atividade neste dia.</li>";
  }else{
    acts.forEach(a=>{
      list.innerHTML+=`<li><strong>${a.titulo}</strong> - ${a.descricao} <br><small>Prof: ${a.professor_nome}</small></li>`;
    });
  }
  document.getElementById("listModal").classList.remove("hidden");
}
function closeListModal(){document.getElementById("listModal").classList.add("hidden");}

function openAddModal(){document.getElementById("addModal").classList.remove("hidden");}
function closeAddModal(){document.getElementById("addModal").classList.add("hidden");}

generateCalendar();
</script>

<footer class="bg-black text-white py-10 px-6 md:px-12 lg:px-24">
  <div class="grid md:grid-cols-2 gap-8">
    <!-- Contato -->
    <div>
      <h3 class="text-xl font-bold text-red-500 mb-4">Entre em contato:</h3>
      <ul class="space-y-3">
        <li class="flex items-center">
          <i class="fas fa-phone-alt text-red-500 mr-3"></i>
          <span>+55 11 90000 9999</span>
        </li>
        <li class="flex items-center">
          <i class="fab fa-whatsapp text-red-500 mr-3"></i>
          <span>+55 11 99999 0000</span>
        </li>
        <li class="flex items-center">
          <i class="fas fa-envelope text-red-500 mr-3"></i>
          <span>comercial@visionarios.com.br</span>
        </li>
      </ul>
    </div>

    <!-- Redes sociais -->
    <div>
      <h3 class="text-xl font-bold text-red-500 mb-4">Confira nossas páginas:</h3>
      <ul class="space-y-3">
        <li class="flex items-center">
          <i class="fab fa-linkedin text-red-500 mr-3"></i>
          <a href="#" class="hover:underline">LinkedIn</a><!--Bernardo tem que colocar o link certo-->
        </li>
        <li class="flex items-center">
          <i class="fab fa-instagram text-red-500 mr-3"></i>
          <a href="#" class="hover:underline">Instagram</a> <!--Bernardo tem que colocar o link certo-->
        </li>
      </ul>
    </div>
  </div>

  <div class="mt-10 border-t border-red-500 pt-6 text-center text-sm text-gray-400">
    &copy; 2025 Visionários. Todos os direitos reservados.
  </div>

  <div id="colorFilter" class="mt-6">
    <label for="filterSelect" class="text-black mr-2">Filtro de cor:</label>
    <select id="filterSelect" onchange="applyFilter(this.value)" class="text-black rounded px-2 py-1">
      <option value="normal">Normal</option>
      <option value="daltonismo">Daltonismo</option>
      <option value="deuteranopia">Deuteranopia</option>
      <option value="tritanopia">Tritanopia</option>
      <option value="monochromacy">Monocromacia</option>
    </select>
  </div>
</footer>

<svg xmlns="http://www.w3.org/2000/svg" style="display:none">
  <filter id="deuteranopia">
    <feColorMatrix type="matrix" values="0.625,0.375,0,0,0 0.7,0.3,0,0,0 0,0.3,0.7,0,0 0,0,0,1,0"/>
  </filter>
  <filter id="protanopia">
   <feColorMatrix type="matrix" values="1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 1 0"/>
  </filter>
  <filter id="tritanopia">
    <feColorMatrix type="matrix" values="0.95,0.05,0,0,0 0,0.433,0.567,0,0 0,0.475,0.525,0,0 0,0,0,1,0"/>
  </filter>
  <filter id="daltonismo">
    <feColorMatrix type="matrix" values="0.6,0.4,0,0,0 0.6,0.4,0,0,0 0,0.3,0.7,0,0 0,0,0,1,0"/>
  </filter>
</svg>
<script src="script.js"></script>
</body>
</html>