  <?php
  session_start();
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
    <style>
      /* Estilos para o mini-card do usuário no header */
      .user-card { background: rgba(255,255,255,0.95); border-radius: 8px; padding: 8px 12px; display: flex; align-items: center; gap: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.06); }
      .user-name { font-weight: 600; color: #111827; }
      .user-email { font-size: 12px; color: #6b7280; line-height: 1; }
      .user-avatar { width: 40px; height: 40px; border-radius: 9999px; background: linear-gradient(135deg,#ef4444,#f97316); display:flex; align-items:center; justify-content:center; color:white; font-weight:700; }
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

      body.filtered {
        transition: filter 0.3s ease;
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
          <a href="index.php" class="text-blue-600" class="hover:text-blue-600">Sobre Nós</a>

          <?php if ($_SESSION['usuario']['cargo'] === null || $_SESSION['usuario']['cargo'] === 'Aluno'): ?>
            <a href="vestibulares.php" class="hover:text-blue-600">Vestibulares</a>
            <a href="bolsas.php" class="hover:text-blue-600">Bolsas</a>
            <a href="painelProfessor.php" class="hover:text-blue-600">Professores Referência</a>
            
          <?php elseif ($_SESSION['usuario']['cargo'] === 'Professor'): ?>
            <a href="painelProfessor.php" class="hover:text-blue-600">Gerenciar Atividades</a>
          <?php endif; ?>

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
    <div class="cabecalho fade-in">
    <div class="texto-cabecalho">
        
        <style>
      .cabecalho {
        background-image: url('img/nozezz.png');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        width: 100%;
        min-height: 80vh;
        display: flex;
        align-items: flex-end;
        justify-content: flex-end;
        padding: 30px;
        color: white;
        box-shadow: inset 0 7 500px rgba(0, 0, 0, 0.882);
        position: relative;
      }

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
      #form-logo{
        background-image: url('img/olho logo.png');
        background-size: cover;
        background-position: center;
      }
      #authModal{
        border-radius: 10px;
      }
  </style>
      </div>
    </div>

    <div id="notifyOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-45"></div>

    <div id="notifyModal"
        class="fixed left-1/2 top-1/3 transform -translate-x-1/2 w-[90vw] max-w-md bg-white rounded-lg shadow-2xl z-50 hidden">
      <div class="p-5">
        <div class="flex items-start gap-3">
          <div class="text-red-600 text-2xl">
            <i class="fas fa-exclamation-circle"></i>
          </div>
          <div class="flex-1">
            <h3 class="text-lg font-bold text-gray-900 mb-1">Acesso restrito</h3>
            <p class="text-sm text-gray-700 mb-4">
              Para acessar esta página é preciso entrar com sua conta. Faça login ou crie uma conta para continuar.
            </p>

            <div class="flex justify-end gap-3">
              <button id="notifyCancel" class="px-4 py-2 rounded-lg border hover:bg-gray-50">Cancelar</button>
              <button id="notifyContinue" class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">Continuar para login</button>
            </div>
          </div>
        </div>
      </div>
    </div>

      <div id="authOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40"></div>
      <div id="authModal" class="auth-modal fixed left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[92vw] max-w-[420px] bg-white z-50 hidden anim">
          <div class="flex items-center justify-between px-4 py-3 bg-red-600 rounded-t-lg text-white select-none">
          <div class="flex items-center space-x-3">
              <div id="form-logo"></div>
              <div id="form-desc">
              <div class="text-sm font-semibold">Visionários</div>
              <div class="text-xs opacity-90">Acesse sua conta</div>
              </div>
          </div>
          <div class="flex items-center gap-2">
              <button id="toggleFormBtn" title="Alternar Login / Cadastro" class="p-2 rounded-md hover:bg-red-700/80 anim">
              <i class="fas fa-exchange-alt"></i>
              </button>
              <button id="closeAuth" title="Fechar" class="p-2 rounded-md hover:bg-red-700/80 anim">
              <i class="fas fa-times"></i>
              </button>
          </div>
          </div>

          <!-- Conteúdo do modal -->
          <div class="p-6">
          <div class="flex gap-2 mb-4">
              <button id="tabLogin" class="flex-1 py-2 rounded-lg border border-red-600 text-red-600 font-semibold hover:bg-red-50">Entrar</button>
              <button id="tabRegister" class="flex-1 py-2 rounded-lg bg-red-600 text-white font-semibold hover:opacity-95">Cadastrar</button>
          </div>

          <div id="feedback" class="text-sm mb-3"></div>

          <form id="formLogin" action="login.php" method="POST">
              <label class="block text-sm font-medium text-gray-700">E-mail</label>
              <input id="loginEmail" type="email" required class="mt-1 mb-3 w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-red-200" placeholder="seu@exemplo.com">

              <label class="block text-sm font-medium text-gray-700">Senha</label>
              <div class="relative">
              <input id="loginPassword" type="password" required class="mt-1 mb-3 w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-red-200" placeholder="••••••">
              <button type="button" class="absolute right-2 top-2 text-gray-500" data-target="loginPassword" onclick="togglePassword(this)">
                  <i class="fa-regular fa-eye"></i>
              </button>
              </div>

              <div class="flex items-center justify-between mb-4">
              <label class="flex items-center gap-2 text-sm">
                  <input id="remember" type="checkbox" class="w-4 h-4">
                  <span>Manter conectado</span>
              </label>
              <a href="#" class="text-sm text-red-600 hover:underline">Esqueci a senha</a>
              </div>

              <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-lg font-semibold hover:bg-red-700">Entrar</button>
          </form>

          <!-- Formulário de Registro -->
          <form id="formRegister" class="hidden" action="register.php" method="POST">
              <label class="block text-sm font-medium text-gray-700">Nome completo</label>
              <input id="regName" type="text" required class="mt-1 mb-3 w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-red-200" placeholder="Seu nome">

              <label class="block text-sm font-medium text-gray-700">E-mail</label>
              <input id="regEmail" type="email" required class="mt-1 mb-3 w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-red-200" placeholder="seu@exemplo.com">

              <label class="block text-sm font-medium text-gray-700">Senha</label>
              <div class="relative">
              <input id="regPassword" type="password" minlength="6" required class="mt-1 mb-3 w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-red-200" placeholder="Mínimo 6 caracteres">
              <button type="button" class="absolute right-2 top-2 text-gray-500" data-target="regPassword" onclick="togglePassword(this)">
                  <i class="fa-regular fa-eye"></i>
              </button>
              </div>

              <label class="block text-sm font-medium text-gray-700">Confirmar senha</label>
              <input id="regPasswordConfirm" type="password" minlength="6" required class="mt-1 mb-4 w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-red-200" placeholder="Repita a senha">

              <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-lg font-semibold hover:bg-red-700">Cadastrar</button>
          </form>

          <p class="text-xs text-gray-500 mt-4">
              Esta aplicação conecta os formulários a um backend seguro.
          </p>
          </div>
      </div>

    <section class="bg-white py-16 px-6 md:px-12 lg:px-24 relative overflow-hidden fade-in">
    <!-- Detalhe decorativo -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-red-100 rounded-full -mr-32 -mt-32 opacity-30"></div>

    <div class="max-w-6xl mx-auto relative z-10">
      <h2 class="text-4xl md:text-5xl font-extrabold text-red-600 mb-8 border-b-4 border-red-600 inline-block tracking-tight">
        Sobre Nós
      </h2>
      <p class="text-gray-700 text-lg md:text-xl leading-relaxed mb-12 max-w-4xl">
        Somos uma instituição comprometida com a excelência no ensino, oferecendo oportunidades de aprendizado e crescimento para todos os nossos alunos. Nosso time é formado por profissionais renomados que acreditam no poder da educação para transformar vidas.
      </p>
      
      <div class="grid md:grid-cols-2 gap-10">
        <div class="bg-gradient-to-r from-red-50 to-white border-l-4 border-red-600 p-8 rounded-xl shadow-lg hover:shadow-xl transition duration-300">
          <h3 class="text-2xl font-semibold text-red-600 mb-4">Nossa Missão</h3>
          <p class="text-gray-700 leading-relaxed">
            Proporcionar ensino de qualidade com responsabilidade social, incentivando o pensamento crítico, a inovação e o desenvolvimento integral dos nossos alunos.
          </p>
        </div>

        <div class="bg-gradient-to-r from-red-50 to-white border-l-4 border-red-600 p-8 rounded-xl shadow-lg hover:shadow-xl transition duration-300">
          <h3 class="text-2xl font-semibold text-red-600 mb-4">Nossa Visão</h3>
          <p class="text-gray-700 leading-relaxed">
            Ser referência no cenário educacional, formando cidadãos preparados para os desafios do futuro, comprometidos com a ética e a transformação social.
          </p>
        </div>
      </div>
    </div>
  </section>

  <section class="bg-white py-16 px-6 md:px-12 lg:px-24 relative overflow-hidden slide-in-left">
    <div class="max-w-6xl mx-auto relative z-10">
      <div class="grid md:grid-cols-3 gap-10 text-center">
        <div class="p-8 rounded-xl shadow-lg bg-gradient-to-b from-red-50 to-white border-t-4 border-red-600 hover:shadow-2xl transition">
          <div class="text-red-600 text-5xl mb-4">
            <i class="fas fa-microchip"></i>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-3">Tecnologia de Ponta</h3>
          <p class="text-gray-700 leading-relaxed">
            Desenvolvemos soluções inovadoras que utilizam o que há de mais moderno no mercado, impulsionando negócios e transformando ideias em realidade.
          </p>
        </div>

        <div class="p-8 rounded-xl shadow-lg bg-gradient-to-b from-red-50 to-white border-t-4 border-red-600 hover:shadow-2xl transition">
          <div class="text-red-600 text-5xl mb-4">
            <i class="fas fa-eye"></i>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-3">Visão à Frente</h3>
          <p class="text-gray-700 leading-relaxed">
            Estamos sempre um passo à frente, antecipando tendências e oferecendo soluções tecnológicas que acompanham o ritmo acelerado do mundo moderno.
          </p>
        </div>

        <div class="p-8 rounded-xl shadow-lg bg-gradient-to-b from-red-50 to-white border-t-4 border-red-600 hover:shadow-2xl transition">
          <div class="text-red-600 text-5xl mb-4">
            <i class="fas fa-users-cog"></i>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-3">Parceria Estratégica</h3>
          <p class="text-gray-700 leading-relaxed">
            Atuamos lado a lado com nossos clientes, criando soluções personalizadas que impulsionam resultados e fortalecem negócios a longo prazo.
          </p>
        </div>
      </div>
    </div>
  </section>

  <section class="relative bg-black text-white py-20 px-6 md:px-12 lg:px-24 fade-in" style="background: url('img/preto.jpg') center/cover no-repeat;">
    <div class="absolute inset-0 bg-black bg-opacity-70"></div>
    <div class="relative z-10 max-w-4xl">
      <h2 class="text-3xl md:text-4xl font-extrabold mb-6 leading-snug">
        Garantimos soluções seguras e duráveis que preservam os produtos e atendem às demandas do mercado.
      </h2>
      <ul class="space-y-3 mb-8">
        <li class="flex items-center text-lg">
          <span class="text-red-500 mr-3"><i class="fas fa-microchip"></i></span> Tecnologia de ponta
        </li>
        <li class="flex items-center text-lg">
          <span class="text-red-500 mr-3"><i class="fas fa-check-circle"></i></span> Materiais de qualidade
        </li>
        <li class="flex items-center text-lg">
          <span class="text-red-500 mr-3"><i class="fas fa-users"></i></span> Equipe especializada
        </li>
      </ul>
      <a href="https://wa.me/SEUNUMERO" target="_blank" class="inline-block bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition">
        Entrar em contato
      </a>
    </div>
  </section>

  <section class="bg-white py-20 px-6 md:px-12 lg:px-24 slide-in-left">
    <!-- Espaço para a logo -->
    <div class="flex justify-center mb-12">
      <img src="img/olho logo.png" alt="Logo" class="h-30 w-auto">
    </div>

    <div class="grid md:grid-cols-2 gap-12 items-center">
      <!-- Texto -->
      <div>
        <h2 class="text-3xl md:text-4xl font-extrabold text-red-600 mb-6 leading-snug">
          Inovação e tecnologia para transformar o futuro
        </h2>
        <p class="text-gray-800 text-lg mb-6">
          A Visionários é uma empresa de tecnologia com visão à frente do seu tempo, dedicada a criar soluções modernas e eficientes que potencializam negócios e melhoram a vida das pessoas.
        </p>
        <div class="bg-red-50 border-l-4 border-red-600 p-6 rounded-lg shadow-sm mb-6">
          <h3 class="text-xl font-semibold text-red-600 mb-2"><i class="fas fa-bullseye mr-2"></i> Missão</h3>
          <p class="text-gray-800">Desenvolver tecnologias inovadoras que tragam resultados reais e sustentáveis para nossos clientes.</p>
        </div>
        <div class="bg-red-50 border-l-4 border-red-600 p-6 rounded-lg shadow-sm">
          <h3 class="text-xl font-semibold text-red-600 mb-2"><i class="fas fa-eye mr-2"></i> Visão</h3>
          <p class="text-gray-800">Ser referência global em soluções tecnológicas disruptivas, antecipando tendências e moldando o futuro.</p>
        </div>
      </div>

      <!-- Imagem -->
      <div>
        
        <img src="img/cerebro.jpg" alt="Equipe Visionários" class="rounded-xl shadow-lg">
      </div>
    </div>
  </section>
  <script src="login.js"></script>
  <script>
  (function() {
    try {
      const params = new URLSearchParams(window.location.search);
      if (!params.has('openLogin')) return; // nada a fazer

      // pega o redirect (pode ser null)
      const redirect = params.get('redirect');
      if (redirect) {
        try { sessionStorage.setItem('postLoginRedirect', redirect); }
        catch (e) { console.warn('sessionStorage não disponível:', e); }
      }

      // elementos do notify
      const notifyOverlay = document.getElementById('notifyOverlay');
      const notifyModal = document.getElementById('notifyModal');
      const btnCancel = document.getElementById('notifyCancel');
      const btnContinue = document.getElementById('notifyContinue');

      function showNotify() {
        if (notifyOverlay) notifyOverlay.classList.remove('hidden');
        if (notifyModal) notifyModal.classList.remove('hidden');
      }
      function hideNotify() {
        if (notifyOverlay) notifyOverlay.classList.add('hidden');
        if (notifyModal) notifyModal.classList.add('hidden');
      }

      // limpar query string (vai usar window.history.replaceState)
      function cleanURL() {
        if (window.history && window.history.replaceState) {
          const clean = window.location.pathname;
          window.history.replaceState({}, '', clean);
        }
      }

      // ação do botão "Cancelar"
      btnCancel.addEventListener('click', function() {
        hideNotify();
        // opcional: remover redirect salvo
        try { sessionStorage.removeItem('postLoginRedirect'); } catch(e){}
        cleanURL();
      });

      // ação do botão "Continuar para login"
      btnContinue.addEventListener('click', function() {
        hideNotify();
        // abre o modal de login assim que estiver disponível
        function openLoginWhenReady() {
          if (typeof openModal === 'function') {
            openModal();
            cleanURL();
          } else {
            setTimeout(openLoginWhenReady, 100);
          }
        }
        openLoginWhenReady();
      });

      // fechar ao clicar no overlay
      if (notifyOverlay) {
        notifyOverlay.addEventListener('click', function() {
          hideNotify();
          try { sessionStorage.removeItem('postLoginRedirect'); } catch(e){}
          cleanURL();
        });
      }

      // abrimos o notify assim que DOM estiver pronto (ou imediatamente)
      function start() {
        showNotify();
      }
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', start);
      } else {
        start();
      }
    } catch (err) {
      console.error('Erro ao processar openLogin with notify:', err);
    }
  })();
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
