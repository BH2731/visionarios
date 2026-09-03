<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['usuario'])) {
    $current = $_SERVER['REQUEST_URI'];
    if (strpos($current, '/') !== 0) {
        $current = '/';
    }

    header('Location: index.php?openLogin=1&redirect=' . urlencode($current));
    exit();
}

if (!isset($_SESSION['usuario']['cargo']) || $_SESSION['usuario']['cargo'] !== 'Aluno') {
    $current = $_SERVER['REQUEST_URI'];
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vestibulares</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="./img/olho logo.png"> 
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
    </style>
</head>
<body>
  <header class="bg-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
      <!-- Logo -->
      <div class="flex items-center space-x-3">
        <img src="img/download.png" alt="Logo" class="w-18 h-14">
      </div>

      <!-- Menu -->
      <nav class="hidden md:flex space-x-6 text-gray-700 font-medium">
        <a href="index.php" class="hover:text-blue-600">Sobre Nós</a>
        <a href="vestibulares.php" class="text-blue-600" class="hover:text-blue-600">Vestibulares</a>
        <a href="bolsas.php" class="hover:text-blue-600">Bolsas</a>
        <a href="painelProfessor.php" class="hover:text-blue-600">Professores Referência</a>
        <a href="aluno.php" class="hover:text-blue-600">Alunos Referência</a>
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

<section class="bg-white py-12 px-6 md:px-12 lg:px-24 fade-in">
  <h2 class="text-3xl font-bold text-red-600 mb-8 text-center">Provas e Gabaritos</h2>
  <div class="grid md:grid-cols-4 gap-6">
    <!-- Lista de anos -->
    <div class="bg-red-50 rounded-lg shadow p-4 space-y-3" id="year-list">
      <button class="w-full text-left bg-red-700 text-white py-2 px-3 rounded" data-year="2024">2024</button>
      <button class="w-full text-left hover:bg-red-100 py-2 px-3 rounded" data-year="2023">2023</button>
      <button class="w-full text-left hover:bg-red-100 py-2 px-3 rounded" data-year="2022">2022</button>
      <button class="w-full text-left hover:bg-red-100 py-2 px-3 rounded" data-year="2021">2021</button>
      <button class="w-full text-left hover:bg-red-100 py-2 px-3 rounded" data-year="2020">2020</button>
      <button class="w-full text-left hover:bg-red-100 py-2 px-3 rounded" data-year="2019">2019</button>
    </div>

    <!-- Conteúdo das provas -->
    <div class="md:col-span-3 bg-gray-50 rounded-lg shadow p-6" id="exam-content"></div>
     <div class="pt-4 space-y-2 border-t border-red-200 mt-4">
        <a href="https://www.gov.br/inep/pt-br/areas-de-atuacao/avaliacao-e-exames-educacionais/enem/provas-e-gabaritos" target="_blank" class="block w-full text-left bg-red-500 hover:bg-red-600 text-white py-2 px-3 rounded">Outros anos do ENEM</a>
        <a href="https://vestibular.fatec.sp.gov.br/provas-gabaritos/" target="_blank" class="block w-full text-left bg-red-500 hover:bg-red-600 text-white py-2 px-3 rounded">Outros anos da FATEC</a>
        <a href="https://www.vestibular.ita.br/provas.htm" target="_blank" class="block w-full text-left bg-red-500 hover:bg-red-600 text-white py-2 px-3 rounded">Outros anos do ITA</a>
      </div>
  </div>

   <!-- Explicação sobre vestibulares -->
  <div class="mt-16 bg-white border-t-4 border-red-600 rounded-lg shadow-lg p-8 space-y-6">
    <h3 class="text-2xl font-bold text-red-600">Por que os alunos do Sesi devem prestar vestibular?</h3>
    <p class="text-gray-700 leading-relaxed">O vestibular é uma porta para oportunidades que podem transformar vidas. Para jovens da periferia, ingressar em uma universidade significa ampliar horizontes, conquistar independência financeira e quebrar ciclos de desigualdade. É um caminho para mostrar seu potencial e alcançar objetivos que antes pareciam distantes.</p>

    <h4 class="text-xl font-semibold text-black">O que é o ENEM?</h4>
    <p class="text-gray-700 leading-relaxed">O Exame Nacional do Ensino Médio (ENEM) é a principal avaliação para ingresso em universidades públicas e privadas do Brasil. Ele também permite acesso a programas como SISU, ProUni e FIES.</p>

    <h4 class="text-xl font-semibold text-black">O que é a FATEC?</h4>
    <p class="text-gray-700 leading-relaxed">A Faculdade de Tecnologia (FATEC) oferece cursos superiores de tecnologia gratuitos e de alta qualidade, com foco em empregabilidade e inovação.</p>

    <h4 class="text-xl font-semibold text-black">O que é o ITA?</h4>
    <p class="text-gray-700 leading-relaxed">O Instituto Tecnológico de Aeronáutica (ITA) é uma das instituições mais renomadas do país, oferecendo formação de excelência na área de engenharia e com grande prestígio no mercado de trabalho.</p>

    <p class="text-gray-700 leading-relaxed">Independentemente do caminho escolhido, prestar vestibular é investir no seu futuro. Com dedicação e apoio, é possível conquistar uma vaga e mudar a sua história.</p>
  </div>
</section>

<script>
  const data = {
    "2024": {
      ENEM: { "Dia 1 (Caderno Azul)": { prova: 'https://download.inep.gov.br/enem/provas_e_gabaritos/2024_PV_impresso_D1_CD1.pdf', gabarito: 'https://download.inep.gov.br/enem/provas_e_gabaritos/2024_GB_impresso_D1_CD1.pdf' }, "Dia 2 (Caderno Azul)": { prova: 'https://download.inep.gov.br/enem/provas_e_gabaritos/2024_PV_impresso_D2_CD7.pdf', gabarito: 'https://download.inep.gov.br/enem/provas_e_gabaritos/2024_GB_impresso_D2_CD7.pdf' } },
      FATEC: { "Dia 1": { prova: 'https://fatweb.s3.amazonaws.com/vestibularfatec/gabarito/202415903/Prova.pdf?v=2.1', gabarito: 'https://fatweb.s3.amazonaws.com/vestibularfatec/gabarito/202415903/Gabarito.pdf?v=2.1' }, "Dia 2": { prova: 'https://fatweb.s3.amazonaws.com/vestibularfatec/gabarito/202426517/Prova.pdf?v=2.1', gabarito: 'https://fatweb.s3.amazonaws.com/vestibularfatec/gabarito/202426517/Gabarito_retificado.pdf?v=2.1' } },
      ITA: { "Fase 1": { prova: 'https://www.vestibular.ita.br/provas/2024_fase1.pdf', gabarito: 'https://www.vestibular.ita.br/provas/gabarito_2024.pdf' }, "Fase 2": { matematica: 'https://www.vestibular.ita.br/provas/matematica_2024_2f.pdf', fisica: 'https://www.vestibular.ita.br/provas/fisica_2024_2f.pdf', quimica: 'https://www.vestibular.ita.br/provas/quimica_2024_2f.pdf', redacao: 'https://www.vestibular.ita.br/provas/redacao_2024_2f.pdf' } }
    },
    "2023": {
      ENEM: { "Dia 1 (Caderno Azul)": { prova: 'https://download.inep.gov.br/enem/provas_e_gabaritos/2023_PV_impresso_D1_CD1.pdf', gabarito: 'https://download.inep.gov.br/enem/provas_e_gabaritos/2023_GB_impresso_D1_CD1.pdf' }, "Dia 2 (Caderno Azul)": { prova: 'https://download.inep.gov.br/enem/provas_e_gabaritos/2023_PV_impresso_D2_CD7.pdf', gabarito: 'https://download.inep.gov.br/enem/provas_e_gabaritos/2023_GB_impresso_D2_CD7.pdf' } },
      FATEC: { "Dia 1": { prova: 'https://fatweb.s3.amazonaws.com/vestibularfatec/gabarito/202315287/Prova.pdf?v=2.1', gabarito: 'https://fatweb.s3.amazonaws.com/vestibularfatec/gabarito/202315287/Gabarito.pdf?v=2.1' }, "Dia 2": { prova: 'https://fatweb.s3.amazonaws.com/vestibularfatec/gabarito/202327519/Prova.pdf?v=2.1', gabarito: 'https://fatweb.s3.amazonaws.com/vestibularfatec/gabarito/202327519/Gabarito.pdf?v=2.1' } },
      ITA: { "Fase 1": { prova: 'https://www.vestibular.ita.br/provas/2023_fase1.pdf', gabarito: 'https://www.vestibular.ita.br/provas/gabarito_2023.pdf' }, "Fase 2": { matematica: 'https://www.vestibular.ita.br/provas/matematica_2023_2f.pdf', fisica: 'https://www.vestibular.ita.br/provas/fisica_2023_2f.pdf', quimica: 'https://www.vestibular.ita.br/provas/quimica_2023_2f.pdf', redacao: 'https://www.vestibular.ita.br/provas/redacao_2023_2f.pdf' } }
    },
    "2022": {
      ENEM: { "Dia 1 (Caderno Azul)": { prova: 'https://download.inep.gov.br/enem/provas_e_gabaritos/2022_PV_impresso_D1_CD1.pdf', gabarito: 'https://download.inep.gov.br/enem/provas_e_gabaritos/2022_GB_impresso_D1_CD1.pdf' }, "Dia 2 (Caderno Azul)": { prova: 'https://download.inep.gov.br/enem/provas_e_gabaritos/2022_PV_impresso_D2_CD7.pdf', gabarito: 'https://download.inep.gov.br/enem/provas_e_gabaritos/2022_GB_impresso_D2_CD7.pdf' } },
      FATEC: {  "Dia 2": { prova: 'https://fatweb.s3.amazonaws.com/vestibularfatec/gabarito/202228712/Prova.pdf?v=2.1', gabarito: 'https://fatweb.s3.amazonaws.com/vestibularfatec/gabarito/202228712/Gabarito.pdf?v=2.1' } },
      ITA: { "Fase 1": { prova: 'https://www.vestibular.ita.br/provas/2022_fase1.pdf', gabarito: 'https://www.vestibular.ita.br/provas/gabarito_2022.pdf' }, "Fase 2": { matematica: 'https://www.vestibular.ita.br/provas/matematica_2022_2f.pdf', fisica: 'https://www.vestibular.ita.br/provas/fisica_2022_2f.pdf', quimica: '#', redacao: '#' } }
    },
   "2021": {
      ENEM: { "Dia 1 (Caderno Azul)": { prova: 'https://download.inep.gov.br/enem/provas_e_gabaritos/2021_PV_impresso_D1_CD1.pdf', gabarito: 'https://download.inep.gov.br/enem/provas_e_gabaritos/2021_GB_impresso_D1_CD1.pdf' }, "Dia 2 (Caderno Azul)": { prova: 'https://download.inep.gov.br/enem/provas_e_gabaritos/2021_PV_impresso_D2_CD7.pdf', gabarito: 'https://download.inep.gov.br/enem/provas_e_gabaritos/2021_GB_impresso_D2_CD7.pdf' } },
      ITA: { "Fase 1": { prova: 'https://www.vestibular.ita.br/provas/2021_fase1.pdf', gabarito: 'https://www.vestibular.ita.br/provas/gabarito_2021.pdf' }, "Fase 2": { matematica: 'https://www.vestibular.ita.br/provas/matematica_2021_2f.pdf', fisica: 'https://www.vestibular.ita.br/provas/fisica_2021_2f.pdf', quimica: 'https://www.vestibular.ita.br/provas/quimica_2021_2f.pdf', redacao: 'https://www.vestibular.ita.br/provas/redacao_2021_2f.pdf' } }
    },
    "2020": {
      ENEM: { "Dia 1 (Caderno Azul)": { prova: '#', gabarito: '#' }, "Dia 2 (Caderno Azul)": { prova: '#', gabarito: '#' } },
     
      ITA: { "Fase 1": { prova: '#', gabarito: '#' }, "Fase 2": { matematica: '#', fisica: '#', quimica: '#', redacao: '#' } }
    },
    "2019": {
      ENEM: { "Dia 1 (Caderno Azul)": { prova: 'https://download.inep.gov.br/educacao_basica/enem/provas/2019/2019_PV_impresso_D1_CD1.pdf', gabarito: 'https://download.inep.gov.br/educacao_basica/enem/gabaritos/2019/gabarito_1_dia_caderno_1_azul_aplicacao_regular.pdf' }, "Dia 2 (Caderno Azul)": { prova: 'https://download.inep.gov.br/educacao_basica/enem/provas/2019/2019_PV_impresso_D2_CD7.pdf', gabarito: 'https://download.inep.gov.br/educacao_basica/enem/gabaritos/2019/gabarito_2_dia_caderno_7_azul_aplicacao_regular.pdf' } },
      FATEC: { "Dia 1": { prova: 'https://fatweb.s3.amazonaws.com/vestibularfatec/gabarito/201916513/Prova.pdf?v=2.1', gabarito: 'https://fatweb.s3.amazonaws.com/vestibularfatec/gabarito/201916513/Gabarito.pdf?v=2.1' }, "Dia 2": { prova: 'https://fatweb.s3.amazonaws.com/vestibularfatec/gabarito/201928914/Prova.pdf?v=2.1', gabarito: 'https://fatweb.s3.amazonaws.com/vestibularfatec/gabarito/201928914/Gabarito.pdf?v=2.1' } },
      ITA: { "Fase 1": { prova: 'https://www.vestibular.ita.br/provas/2019_fase1.pdf', gabarito: 'https://www.vestibular.ita.br/provas/gabarito_2019.pdf' }, "Fase 2": { matematica: 'https://www.vestibular.ita.br/provas/matematica_2019_2f.pdf', fisica: 'https://www.vestibular.ita.br/provas/fisica_2019_2f.pdf', quimica: 'https://www.vestibular.ita.br/provas/quimica_2019_2f.pdf', redacao: 'https://www.vestibular.ita.br/provas/redacao_2019_2f.pdf' } }
    },
   
  };

  function loadExams(year) {
    const exams = data[year];
    if (!exams) return;
    let html = '';
    for (let vestibular in exams) {
      html += `<h3 class="text-xl font-semibold text-red-600 mt-6 mb-4">${vestibular}</h3><ul class="list-disc ml-5 space-y-2">`;
      for (let etapa in exams[vestibular]) {
        const etapaData = exams[vestibular][etapa];
        if (etapaData.prova) {
          html += `<li>${etapa}: <a href="${etapaData.prova}" class="text-red-500 hover:underline">Prova</a> | <a href="${etapaData.gabarito}" class="text-red-500 hover:underline">Gabarito</a></li>`;
        } else {
          html += `<li>${etapa}: <a href="${etapaData.matematica}" class="text-red-500 hover:underline">Matemática</a> | <a href="${etapaData.fisica}" class="text-red-500 hover:underline">Física</a> | <a href="${etapaData.quimica}" class="text-red-500 hover:underline">Química</a> | <a href="${etapaData.redacao}" class="text-red-500 hover:underline">Redação</a></li>`;
        }
      }
      html += '</ul>';
    }
    document.getElementById('exam-content').innerHTML = html;
  }

  document.querySelectorAll('#year-list button').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('#year-list button').forEach(b => b.classList.remove('bg-red-700', 'text-white'));
      btn.classList.add('bg-red-700', 'text-white');
      loadExams(btn.dataset.year);
    });
  });

  loadExams('2024');
</script>

<!-- SECTION - Como funciona -->
<section class="como-funciona flex items-center justify-between gap-10 py-10 bg-white slide-in-left">
  <!-- Imagem -->
  <div class="imagem relative w-1/2">
    <img src="img/pessoas.png" alt="Estudantes conversando" class="rounded-2xl border-4 border-red-600">
    <div class="absolute inset-0 rounded-2xl border-2 border-red-500" style="clip-path: inset(10px);"></div>
  </div>

  <!-- Texto -->
  <div class="texto w-1/2 text-black">
    <h2 class="text-3xl font-bold text-red-600 mb-4">Mas afinal, como funciona o vestibular?</h2>
    <p class="text-gray-700 mb-6">
      Para entender, considere os processos seletivos de cada instituição. Confira algumas etapas comuns na maioria dos vestibulares:
    </p>

    <ul class="space-y-5">
      <li class="flex items-start">
        <span class="w-3 h-3 mt-1 rounded-sm bg-red-600 mr-3"></span>
        <div>
          <strong class="text-black">Buscar o edital</strong>
          <p class="text-gray-700">O primeiro passo é encontrar o edital ou formulário de inscrição.</p>
        </div>
      </li>
      <li class="flex items-start">
        <span class="w-3 h-3 mt-1 rounded-sm bg-red-600 mr-3"></span>
        <div>
          <strong class="text-black">Inscrição</strong>
          <p class="text-gray-700">Preencha seus dados pessoais e o curso desejado. Muitas instituições oferecem provas em diferentes períodos do ano. Fique atento às taxas.</p>
        </div>
      </li>
      <li class="flex items-start">
        <span class="w-3 h-3 mt-1 rounded-sm bg-red-600 mr-3"></span>
        <div>
          <strong class="text-black">Verificar a prova</strong>
          <p class="text-gray-700">Verifique onde será a prova. Algumas instituições realizam presencialmente, enquanto outras oferecem provas online.</p>
        </div>
      </li>
      <li class="flex items-start">
        <span class="w-3 h-3 mt-1 rounded-sm bg-red-600 mr-3"></span>
        <div>
          <strong class="text-black">Aguardar o resultado</strong>
          <p class="text-gray-700">Após a prova, aguarde o resultado, que geralmente é disponibilizado na página da faculdade.</p>
        </div>
      </li>
    </ul>
  </div>
</section>

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