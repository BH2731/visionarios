<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['usuario'])) {
    $current = $_SERVER['REQUEST_URI'];
    header('Location: index.php?openLogin');
    exit();
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Improved animation classes with smoother transitions */
    .fade-in {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .fade-in.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .slide-in-left {
        opacity: 0;
        transform: translateX(-50px);
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .slide-in-left.visible {
        opacity: 1;
        transform: translateX(0);
    }

    .slide-in-right {
        opacity: 0;
        transform: translateX(50px);
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .slide-in-right.visible {
        opacity: 1;
        transform: translateX(0);
    }

    /* Cleaner hero section with better contrast */
    .hero {
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        background: linear-gradient(135deg, #ffffff 0%, #f1f3f4 50%, #e8eaed 100%);
        overflow: hidden;
    }

    .hero-content {
        text-align: center;
        z-index: 2;
        max-width: 800px;
        padding: 0 2rem;
    }

    .hero h1 {
        font-family: 'Playfair Display', serif;
        font-size: 4.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        background: linear-gradient(135deg, #dc2626 0%, #1f2937 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -0.02em;
    }

    .hero p {
        font-size: 1.25rem;
        margin-bottom: 2rem;
        color: #6b7280;
        font-weight: 400;
    }

    /* Real 3D floating cube with proper perspective and shadows */
    .floating-cube {
        position: absolute;
        width: 80px;
        height: 80px;
        top: 20%;
        right: 15%;
        transform-style: preserve-3d;
        animation: float3d 8s ease-in-out infinite;
    }

    .cube-face {
        position: absolute;
        width: 80px;
        height: 80px;
        border: 2px solid #dc2626;
        background: rgba(220, 38, 38, 0.1);
        backdrop-filter: blur(10px);
    }

    .cube-face.front { transform: rotateY(0deg) translateZ(40px); }
    .cube-face.back { transform: rotateY(180deg) translateZ(40px); }
    .cube-face.right { transform: rotateY(90deg) translateZ(40px); }
    .cube-face.left { transform: rotateY(-90deg) translateZ(40px); }
    .cube-face.top { transform: rotateX(90deg) translateZ(40px); }
    .cube-face.bottom { transform: rotateX(-90deg) translateZ(40px); }

    @keyframes float3d {
        0%, 100% { 
            transform: translateY(0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg);
        }
        25% { 
            transform: translateY(-20px) rotateX(90deg) rotateY(90deg) rotateZ(45deg);
        }
        50% { 
            transform: translateY(-10px) rotateX(180deg) rotateY(180deg) rotateZ(90deg);
        }
        75% { 
            transform: translateY(-25px) rotateX(270deg) rotateY(270deg) rotateZ(135deg);
        }
    }

    /* Clean section styling with better spacing */
    .professors-section {
        padding: 6rem 2rem;
        max-width: 1400px;
        margin: 0 auto;
        background: #ffffff;
    }

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

    .professors-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
        gap: 3rem;
        margin-bottom: 4rem;
    }

    /* Modern card design with real 3D effects and clean styling */
    .professor-card {
        background: #ffffff;
        border-radius: 24px;
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #f3f4f6;
        transform: perspective(1000px) rotateX(0deg) rotateY(0deg);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .professor-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #dc2626, #ef4444);
        transform: scaleX(0);
        transition: transform 0.4s ease;
    }

    .professor-card:hover::before {
        transform: scaleX(1);
    }

    .professor-card:hover {
        transform: perspective(1000px) rotateX(5deg) rotateY(5deg) translateY(-8px);
        box-shadow: 0 20px 40px rgba(220, 38, 38, 0.15);
    }

    .professor-image {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, #dc2626, #ef4444);
        margin: 0 auto 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
        font-weight: 600;
        box-shadow: 0 8px 25px rgba(220, 38, 38, 0.3);
    }

    .professor-name {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #1f2937;
        text-align: center;
    }

    .professor-subject {
        text-align: center;
        color: #dc2626;
        font-weight: 500;
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .professor-description {
        color: #6b7280;
        line-height: 1.7;
        font-size: 0.95rem;
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

    /* Clean advice section with modern cards */
    .advice-section {
        padding: 6rem 2rem;
        max-width: 1200px;
        margin: 0 auto;
        background: #f9fafb;
    }

    .advice-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2.5rem;
    }

    .advice-card {
        background: #ffffff;
        padding: 2.5rem;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #f3f4f6;
        transform: perspective(1000px) rotateX(0deg);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .advice-card:hover {
        transform: perspective(1000px) rotateX(3deg) translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
    }

    .advice-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #dc2626, #ef4444);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
        box-shadow: 0 8px 25px rgba(220, 38, 38, 0.25);
    }

    .advice-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #1f2937;
    }

    .advice-text {
        color: #6b7280;
        line-height: 1.7;
        font-size: 0.95rem;
    }

    /* Improved responsive design */
    @media (max-width: 768px) {
        .hero h1 {
            font-size: 3rem;
        }
        
        .hero p {
            font-size: 1.1rem;
        }
        
        .section-title {
            font-size: 2.5rem;
        }
        
        .professors-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        
        .floating-cube {
            display: none;
        }

        .professors-section,
        .advice-section {
            padding: 4rem 1rem;
        }

        .calendar-container {
            padding: 2rem;
        }
    }

    @media (max-width: 480px) {
        .hero h1 {
            font-size: 2.5rem;
        }
        
        .professor-card,
        .advice-card {
            padding: 2rem;
        }
    }
    .user-card { background: rgba(255,255,255,0.95); border-radius: 8px; padding: 8px 12px; display: flex; align-items: center; gap: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.06); }
    .user-name { font-weight: 600; color: #111827; }
    .user-email { font-size: 12px; color: #6b7280; line-height: 1; }
    .user-avatar { width: 40px; height: 40px; border-radius: 9999px; background: linear-gradient(135deg,#ef4444,#f97316); display:flex; align-items:center; justify-content:center; color:white; font-weight:700; }
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
        <a href="vestibulares.php" class="hover:text-blue-600">Vestibulares</a>
        <a href="bolsas.php" class="hover:text-blue-600">Bolsas</a>
        <a href="professores.php" class="text-blue-600" class="hover:text-blue-600">Professores Referência</a>
        <a href="aluno.php" class="hover:text-blue-600">Página do Aluno</a>
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
<body>
    <!-- Hero Section -->
    <section class="hero">
        <!-- Real 3D cube with proper faces -->
        <div class="floating-cube">
            <div class="cube-face front"></div>
            <div class="cube-face back"></div>
            <div class="cube-face right"></div>
            <div class="cube-face left"></div>
            <div class="cube-face top"></div>
            <div class="cube-face bottom"></div>
        </div>
        <div class="hero-content fade-in">
            <h1>Professores Referências</h1>
            <p>Inspiração e conhecimento que transformam vidas através da excelência educacional</p>
        </div>
    </section>

    <!-- Seção de Professores -->
    <section class="professors-section">
        <h2 class="section-title fade-in">Nossos Mestres</h2>
        
        <div class="professors-grid">
            <div class="professor-card slide-in-left">
                <div class="professor-image">MA</div>
                <h3 class="professor-name">Prof. Maria Almeida</h3>
                <p class="professor-subject">Matemática Avançada</p>
                <p class="professor-description">
                    Doutora em Matemática pela USP com 20 anos de experiência. Revolucionou o ensino de cálculo com métodos inovadores que tornaram conceitos complexos acessíveis, inspirando centenas de estudantes a descobrir a beleza da matemática.
                </p>
            </div>

            <div class="professor-card slide-in-right">
                <div class="professor-image">JS</div>
                <h3 class="professor-name">Prof. João Silva</h3>
                <p class="professor-subject">Física Quântica</p>
                <p class="professor-description">
                    Pesquisador renomado em física quântica e autor de 15 livros científicos. Sua paixão contagiante pelo ensino inspira estudantes a explorar os mistérios do universo através da ciência, tornando o impossível compreensível.
                </p>
            </div>

            <div class="professor-card slide-in-left">
                <div class="professor-image">AC</div>
                <h3 class="professor-name">Prof. Ana Costa</h3>
                <p class="professor-subject">Literatura Brasileira</p>
                <p class="professor-description">
                    Especialista em literatura contemporânea que formou gerações de escritores e críticos literários. Sua abordagem única conecta os clássicos brasileiros com a realidade atual, despertando o amor pela leitura em seus alunos.
                </p>
            </div>

          
        </div>
    </section>

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

    <!-- Seção de Conselhos -->
    <section class="advice-section">
        <h2 class="section-title fade-in">Conselhos para o Sucesso</h2>
        
        <div class="advice-grid">
            <div class="advice-card fade-in">
                <div class="advice-icon">📚</div>
                <h3 class="advice-title">Estude com Consistência</h3>
                <p class="advice-text">
                    O segredo do aprendizado não está na quantidade de horas, mas na regularidade. Dedique 30 minutos diários aos estudos - essa consistência é mais eficaz que longas sessões esporádicas.
                </p>
            </div>

            <div class="advice-card fade-in">
                <div class="advice-icon">🎯</div>
                <h3 class="advice-title">Defina Objetivos Claros</h3>
                <p class="advice-text">
                    Estabeleça metas específicas e mensuráveis para seus estudos. Saber exatamente onde você quer chegar é o primeiro passo para traçar o caminho mais eficiente para o sucesso acadêmico.
                </p>
            </div>

            <div class="advice-card fade-in">
                <div class="advice-icon">🤝</div>
                <h3 class="advice-title">Colabore e Compartilhe</h3>
                <p class="advice-text">
                    O conhecimento cresce quando é compartilhado. Forme grupos de estudo, tire dúvidas com colegas e ensine o que aprendeu - explicar conceitos para outros fortalece seu próprio entendimento.
                </p>
            </div>

            <div class="advice-card fade-in">
                <div class="advice-icon">💡</div>
                <h3 class="advice-title">Mantenha a Curiosidade</h3>
                <p class="advice-text">
                    Faça perguntas, questione conceitos e explore além do currículo obrigatório. A curiosidade genuína é o combustível da descoberta e da inovação - ela transforma estudantes em pesquisadores.
                </p>
            </div>

            <div class="advice-card fade-in">
                <div class="advice-icon">⚡</div>
                <h3 class="advice-title">Pratique a Resiliência</h3>
                <p class="advice-text">
                    Erros e dificuldades fazem parte natural do processo de aprendizado. Cada obstáculo é uma oportunidade de crescimento. Persista com determinação e celebre cada pequena vitória no caminho.
                </p>
            </div>

            <div class="advice-card fade-in">
                <div class="advice-icon">🌟</div>
                <h3 class="advice-title">Acredite no seu Potencial</h3>
                <p class="advice-text">
                    Você é capaz de muito mais do que imagina. Confie no processo de aprendizado, valorize seu progresso diário e nunca subestime sua capacidade de superar desafios e alcançar seus objetivos.
                </p>
            </div>
        </div>
    </section>

    <script>
        // Animações de scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        // Observar todos os elementos com classes de animação
        document.querySelectorAll('.fade-in, .slide-in-left, .slide-in-right').forEach(el => {
            observer.observe(el);
        });

        // Calendário
        let currentDate = new Date();
        const months = [
            'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
            'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
        ];

        // Atividades exemplo
        const activities = {
            '2024-01-15': 'Prova de Matemática',
            '2024-01-22': 'Seminário de Física',
            '2024-01-28': 'Entrega de Projeto'
        };

        function generateCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            
            document.getElementById('currentMonth').textContent = `${months[month]} ${year}`;
            
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            
            const calendarGrid = document.getElementById('calendarGrid');
            calendarGrid.innerHTML = '';
            
            // Dias da semana
            const weekDays = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
            weekDays.forEach(day => {
                const dayElement = document.createElement('div');
                dayElement.textContent = day;
                dayElement.style.fontWeight = 'bold';
                dayElement.style.textAlign = 'center';
                dayElement.style.padding = '0.5rem';
                calendarGrid.appendChild(dayElement);
            });
            
            // Espaços vazios antes do primeiro dia
            for (let i = 0; i < firstDay; i++) {
                const emptyDay = document.createElement('div');
                calendarGrid.appendChild(emptyDay);
            }
            
            // Dias do mês
            for (let day = 1; day <= daysInMonth; day++) {
                const dayElement = document.createElement('div');
                dayElement.className = 'calendar-day';
                dayElement.textContent = day;
                
                const dateKey = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                if (activities[dateKey]) {
                    dayElement.classList.add('has-activity');
                    dayElement.title = activities[dateKey];
                }
                
                dayElement.addEventListener('click', () => {
                    const activity = prompt('Adicionar atividade para este dia:');
                    if (activity) {
                        activities[dateKey] = activity;
                        dayElement.classList.add('has-activity');
                        dayElement.title = activity;
                    }
                });
                
                calendarGrid.appendChild(dayElement);
            }
        }

        function previousMonth() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            generateCalendar();
        }

        function nextMonth() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            generateCalendar();
        }

        // Inicializar calendário
        generateCalendar();

        // Efeito de paralaxe suave
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallax = document.querySelector('.floating-cube');
            if (parallax) {
                parallax.style.transform = `translateY(${scrolled * 0.5}px) rotateX(${scrolled * 0.1}deg) rotateY(${scrolled * 0.1}deg)`;
            }
        });
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