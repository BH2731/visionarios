<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['usuario'])) {
    $current = $_SERVER['REQUEST_URI'];
    header('Location: index.php?openLogin');
    exit();
}

if (!isset($_SESSION['usuario']['cargo']) || $_SESSION['usuario']['cargo'] !== 'Aluno') {
    $current = $_SERVER['REQUEST_URI'];
    header('Location: index.php');
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
  <style>
    /* Estilos para o mini-card do usuário no header */
    .user-card { background: rgba(255,255,255,0.95); border-radius: 8px; padding: 8px 12px; display: flex; align-items: center; gap: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.06); }
    .user-name { font-weight: 600; color: #111827; }
    .user-email { font-size: 12px; color: #6b7280; line-height: 1; }
    .user-avatar { width: 40px; height: 40px; border-radius: 9999px; background: linear-gradient(135deg,#ef4444,#f97316); display:flex; align-items:center; justify-content:center; color:white; font-weight:700; }
    .fade-in { opacity: 0; transform: translateY(30px); transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);}
    .fade-in.visible { opacity: 1; transform: translateY(0);}
    .slide-in-left { opacity: 0; transform: translateX(-50px); transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1); }
    .slide-in-left.visible { opacity: 1; transform: translateX(0); }
    .slide-in-right { opacity: 0; transform: translateX(50px); transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1); }
    .slide-in-right.visible { opacity: 1; transform: translateX(0); }
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
        <a href="bolsas.php" class="text-blue-600" class="hover:text-blue-600">Bolsas</a>
        <a href="painelProfessor.php" class="hover:text-blue-600">Professores Referência</a>
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

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bolsa de Estudos</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            /* mudando para fonte mais tecnológica e moderna */
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            line-height: 1.6;
            color: #000000;
            background-color: #ffffff;
            /* adicionando padrão de fundo tecnológico */
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(220, 38, 38, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(0, 0, 0, 0.05) 0%, transparent 50%);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Hero Section */
        .hero {
            /* gradiente mais tecnológico com parada intermediária */
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 30%, #dc2626 100%);
            color: white;
            padding: 100px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        /* adicionando elementos geométricos tecnológicos */
        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: linear-gradient(45deg, transparent 40%, rgba(220, 38, 38, 0.1) 50%, transparent 60%);
            transform: rotate(15deg);
            pointer-events: none;
        }

        .hero h1 {
            /* tipografia mais tecnológica e impactante */
            font-size: 3.5rem;
            margin-bottom: 20px;
            font-weight: 800;
            letter-spacing: -0.02em;
            position: relative;
            z-index: 1;
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 40px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
            font-weight: 300;
        }

        .cta-button {
            display: inline-block;
            /* botão mais tecnológico com bordas e efeitos */
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            padding: 18px 40px;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(220, 38, 38, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 1;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .cta-button:hover {
            background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%);
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(220, 38, 38, 0.4);
        }

        /* Main Content */
        .main-content {
            padding: 80px 0;
        }

        .section {
            margin-bottom: 80px;
        }

        .section h2 {
            /* títulos mais tecnológicos */
            font-size: 2.8rem;
            color: #000000;
            margin-bottom: 40px;
            text-align: center;
            position: relative;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .section h2::after {
            content: '';
            display: block;
            width: 100px;
            height: 3px;
            /* linha decorativa com gradiente */
            background: linear-gradient(90deg, #dc2626 0%, #000000 100%);
            margin: 20px auto;
            border-radius: 2px;
        }

        /* Bolsas Grid */
        .bolsas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .bolsa-card {
            /* cards mais tecnológicos com bordas e gradientes */
            background: linear-gradient(145deg, #ffffff 0%, #fafafa 100%);
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
            transition: all 0.4s ease;
            border: 1px solid rgba(220, 38, 38, 0.1);
            position: relative;
            overflow: hidden;
        }

        /* adicionando elemento decorativo nos cards */
        .bolsa-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #dc2626 0%, #000000 100%);
        }

        .bolsa-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            border-color: rgba(220, 38, 38, 0.3);
        }

        .bolsa-card h3 {
            /* títulos dos cards focados em faculdades */
            font-size: 1.6rem;
            color: #000000;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .bolsa-card .valor {
            font-size: 2rem;
            font-weight: 800;
            color: #dc2626;
            margin-bottom: 20px;
            /* adicionando efeito tecnológico no valor */
            text-shadow: 0 2px 4px rgba(220, 38, 38, 0.2);
        }

        .bolsa-card .descricao {
            color: #374151;
            margin-bottom: 25px;
            line-height: 1.7;
            font-weight: 400;
        }

        .bolsa-card .requisitos {
            /* fundo mais tecnológico */
            background: linear-gradient(145deg, #f8f9fa 0%, #f1f3f4 100%);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .bolsa-card .requisitos h4 {
            color: #000000;
            margin-bottom: 15px;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .bolsa-card .requisitos ul {
            list-style: none;
            padding-left: 0;
        }

        .bolsa-card .requisitos li {
            padding: 8px 0;
            position: relative;
            padding-left: 25px;
            font-weight: 400;
        }

        .bolsa-card .requisitos li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #dc2626;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .apply-btn {
            /* botões mais tecnológicos */
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .apply-btn:hover {
            background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        /* Processo de Inscrição */
        .processo {
            /* fundo mais tecnológico */
            background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
            padding: 60px 40px;
            border-radius: 25px;
            margin: 50px 0;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 40px;
            margin-top: 50px;
        }

        .step {
            text-align: center;
            padding: 30px 20px;
            position: relative;
        }

        .step-number {
            width: 70px;
            height: 70px;
            /* números mais tecnológicos */
            background: linear-gradient(135deg, #000000 0%, #dc2626 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            font-weight: 800;
            margin: 0 auto 25px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            border: 3px solid rgba(255, 255, 255, 0.1);
        }

        .step h3 {
            color: #000000;
            margin-bottom: 15px;
            font-size: 1.4rem;
            font-weight: 700;
        }

        .step p {
            color: #374151;
            line-height: 1.7;
            font-weight: 400;
        }

        /* FAQ Section */
        .faq {
            /* FAQ mais tecnológico */
            background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
            padding: 60px 40px;
            border-radius: 25px;
            margin: 50px 0;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .faq-item {
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            padding: 25px 0;
            transition: all 0.3s ease;
        }

        .faq-item:hover {
            background: rgba(220, 38, 38, 0.02);
            border-radius: 8px;
            padding: 25px 15px;
        }

        .faq-item:last-child {
            border-bottom: none;
        }

        .faq-question {
            font-weight: 700;
            color: #000000;
            margin-bottom: 12px;
            font-size: 1.2rem;
        }

        .faq-answer {
            color: #374151;
            line-height: 1.7;
            font-weight: 400;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1.1rem;
            }

            .section h2 {
                font-size: 2.2rem;
            }

            .bolsas-grid {
                grid-template-columns: 1fr;
            }

            .steps {
                grid-template-columns: 1fr;
            }

            .processo, .faq {
                padding: 40px 25px;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero fade-in">
        <div class="container">
            <!-- título focado em faculdades -->
            <h1>Bolsas Universitárias</h1>
            <p>Realize seu sonho de cursar uma faculdade com nossas bolsas de estudos</p>
            <a href="#bolsas" class="cta-button">Explorar Bolsas</a>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Bolsas Disponíveis -->
            <section id="bolsas" class="section fade-in">
                <!-- título focado em faculdades -->
                <h2>Bolsas para Faculdades</h2>
                <div class="bolsas-grid">
                    <div class="bolsa-card">
                        <!-- conteúdo focado em faculdades -->
                        <h3>Bolsa para Direito</h3>
                        <div class="valor">Até 70% de Desconto</div>
                        <p class="descricao">Cobertura parcial das mensalidades para cursos de graduação em universidades parceiras. Ideal para estudantes com excelente desempenho acadêmico.</p>
                        <div class="requisitos">
                            <h4>Requisitos:</h4>
                            <ul>
                                <li>Nota mínima 8.5 no ENEM</li>
                                <li>Renda familiar até 1.5 salários mínimos</li>
                                <li>Aprovação no vestibular da instituição</li>
                                <li>Entrevista de seleção</li>
                            </ul>
                        </div>
                        <button  class="apply-btn" link="https://www.educamaisbrasil.com.br/curso/direito">Candidatar-se</button>
                        
                    </div>

                    <div class="bolsa-card">
                        <h3>Bolsa Parcial de Gestão de Recursos Humanos</h3>
                        <div class="valor">Até 83% de Desconto</div>
                        <p class="descricao">Desconto significativo nas mensalidades para cursos de graduação, permitindo acesso ao ensino superior de qualidade.</p>
                        <div class="requisitos">
                            <h4>Requisitos:</h4>
                            <ul>
                                <li>Nota mínima 7.5 no ENEM</li>
                                <li>Renda familiar até 2 salários mínimos</li>
                                <li>Santo André (SP)</li>
                                <li>Carta de motivação acadêmica(opcional)</li>
                            </ul>
                        </div>
                         <button  class="apply-btn" link="https://www.educamaisbrasil.com.br/curso/gestao-de-recursos-humanos">Candidatar-se</button>
                    </div>

                    <br>
                    </div>

                    <div class="bolsa-card">
                        <h3>Bolsa de análise e desenvolvimento de sistemas</h3>
                        <div class="valor">60% de Desconto</div>
                        <p class="descricao">Bolsa especial para cursos de tecnologia, engenharia e inovação, preparando profissionais para o futuro digital.</p>
                        <div class="requisitos">
                            <h4>Requisitos:</h4>
                            <ul>
                                <li>Curso na área de tecnologia ou engenharia</li>
                                <li>Nota mínima 8.0 no ENEM</li>
                                <li>Portfolio de projetos tecnológicos</li>
                                <li>Conhecimento em programação (diferencial)</li>
                            </ul>
                        </div>
                        <button  class="apply-btn" link="https://www.educamaisbrasil.com.br/curso/analise-e-desenvolvimento-de-sistemas">Candidatar-se</button>
                    </div>
                </div>
            </section>

            <!-- Processo de Inscrição -->
            <section class="section slide-in-left">
                <div class="processo">
                    <h2>Processo de Candidatura</h2>
                    <div class="steps">
                        <div class="step">
                            <div class="step-number">1</div>
                            <h3>Documentação</h3>
                            <p>Reúna histórico escolar, comprovante de renda, resultado do ENEM, RG, CPF e documentos específicos da bolsa escolhida.</p>
                        </div>
                        <div class="step">
                            <div class="step-number">2</div>
                            <h3>Candidatura Online</h3>
                            <p>Preencha o formulário digital com suas informações acadêmicas e anexe todos os documentos necessários em formato PDF.</p>
                        </div>
                        <div class="step">
                            <div class="step-number">3</div>
                            <h3>Avaliação</h3>
                            <p>Nossa comissão acadêmica analisará sua candidatura considerando mérito, necessidade e adequação ao perfil da bolsa.</p>
                        </div>
                        <div class="step">
                            <div class="step-number">4</div>
                            <h3>Resultado</h3>
                            <p>Comunicação do resultado por email e portal do candidato em até 20 dias úteis após o encerramento das inscrições.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FAQ -->
            <section class="section fade-in">
                <div class="faq">
                    <h2>Perguntas Frequentes</h2>
                    <div class="faq-item">
                        <div class="faq-question">Quais universidades são parceiras do programa?</div>
                        <div class="faq-answer">Temos parcerias com mais de 50 universidades públicas e privadas em todo o país, incluindo instituições renomadas como USP, UNICAMP, PUC, Mackenzie e muitas outras.</div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question">Posso usar a bolsa para qualquer curso de graduação?</div>
                        <div class="faq-answer">Sim, nossas bolsas cobrem todos os cursos de graduação oferecidos pelas universidades parceiras, desde medicina até artes, passando por engenharia e tecnologia.</div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question">A bolsa cobre apenas as mensalidades?</div>
                        <div class="faq-answer">As bolsas cobrem as mensalidades do curso. Para bolsas integrais, também oferecemos auxílio para material didático e transporte, mediante comprovação de necessidade.</div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question">Preciso manter uma nota mínima para renovar a bolsa?</div>
                        <div class="faq-answer">Sim, é necessário manter média semestral mínima de 7.0 e frequência de pelo menos 85% para renovação automática da bolsa a cada semestre.</div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question">Posso transferir a bolsa se mudar de curso?</div>
                        <div class="faq-answer">A transferência é possível dentro da mesma universidade, sujeita à análise da coordenação e disponibilidade de vagas no curso de destino.</div>
                    </div>
                </div>
            </section>
        </div>
    </main>

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
