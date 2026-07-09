<?php

/**
 * Gerador de Monografia — Sistema de Gestão de Assistência Técnica Informática
 * (evolução para um modelo CMMS de Manutenção Preventiva e Corretiva)
 * Autor: António Jacinto
 *
 * Gerado com PHPWord (em vez de python-docx / PHPWord porque o Python não
 * está disponível nesta máquina — ver nota no CHANGELOG.md v4.0.0).
 */

require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

// Por omissão o PHPWord NÃO escapa caracteres especiais (&, <, >) no texto —
// sem isto, qualquer "&" literal (ex: "Laudon & Laudon") corrompe o XML.
Settings::setOutputEscapingEnabled(true);

$phpWord = new PhpWord();
$phpWord->getSettings()->setThemeFontLang(new \PhpOffice\PhpWord\Style\Language('pt-PT'));

$section = $phpWord->addSection([
    'marginLeft' => Converter::cmToTwip(3.0),
    'marginRight' => Converter::cmToTwip(2.0),
    'marginTop' => Converter::cmToTwip(3.0),
    'marginBottom' => Converter::cmToTwip(2.0),
]);

$phpWord->addTableStyle('GridTable', [
    'borderSize' => 6,
    'borderColor' => '444444',
    'cellMargin' => 80,
    'borderInsideHSize' => 6,
    'borderInsideHColor' => '999999',
    'borderInsideVSize' => 6,
    'borderInsideVColor' => '999999',
]);

// ── Helpers ──────────────────────────────────────────────────────────────────

function heading($text, $size = 14, $spaceBefore = 18, $spaceAfter = 6)
{
    global $section;
    $section->addText($text, ['bold' => true, 'size' => $size], [
        'alignment' => Jc::START,
        'spaceBefore' => $spaceBefore * 20,
        'spaceAfter' => $spaceAfter * 20,
    ]);
}

function centerLine($text, $bold = true, $size = 12, $color = null, $spaceBefore = 0)
{
    global $section;
    $fStyle = ['bold' => $bold, 'size' => $size];
    if ($color) {
        $fStyle['color'] = $color;
    }
    $section->addText($text, $fStyle, ['alignment' => Jc::CENTER, 'spaceBefore' => $spaceBefore * 20]);
}

function body($text, $size = 12, $spaceAfter = 6)
{
    global $section;
    $section->addText($text, ['size' => $size], [
        'alignment' => Jc::BOTH,
        'spaceAfter' => $spaceAfter * 20,
        'indentation' => ['firstLine' => Converter::cmToTwip(1.25)],
    ]);
}

function bullet($text, $size = 11)
{
    global $section;
    $section->addListItem($text, 0, ['size' => $size], null, ['spaceAfter' => 40]);
}

function pageBreak()
{
    global $section;
    $section->addPageBreak();
}

function addTable($headers, $rows, $colWidths = null)
{
    global $section;
    $table = $section->addTable('GridTable');
    $table->addRow();
    foreach ($headers as $i => $h) {
        $width = $colWidths ? Converter::inchToTwip($colWidths[$i]) : null;
        $cell = $table->addCell($width, ['bgColor' => '1F4E79', 'valign' => 'center']);
        $cell->addText($h, ['bold' => true, 'size' => 10, 'color' => 'FFFFFF'], ['alignment' => Jc::CENTER]);
    }
    foreach ($rows as $row) {
        $table->addRow();
        foreach ($row as $i => $val) {
            $width = $colWidths ? Converter::inchToTwip($colWidths[$i]) : null;
            $cell = $table->addCell($width);
            $cell->addText((string)$val, ['size' => 10]);
        }
    }
    global $section;
    $section->addTextBreak(1);
    return $table;
}

function preformatted($text, $size = 8)
{
    global $section;
    $lines = explode("\n", $text);
    foreach ($lines as $line) {
        $line = str_replace(' ', "\u{00A0}", $line); // preservar espaços/alinhamento ASCII
        if (trim($line) === '') {
            $section->addTextBreak(1, ['size' => $size]);
            continue;
        }
        $section->addText($line, ['name' => 'Courier New', 'size' => $size], ['spaceAfter' => 0]);
    }
}

// ═══════════════════════════════════════════════════════════════════════════════
//  CAPA
// ═══════════════════════════════════════════════════════════════════════════════
$section->addTextBreak(3);
centerLine('REPÚBLICA DE ANGOLA', true, 14);
centerLine('MINISTÉRIO DA EDUCAÇÃO', true, 12);
centerLine('INSTITUTO SUPERIOR POLITÉCNICO', true, 12);
$section->addTextBreak(2);
centerLine("EVOLUÇÃO DE UM SISTEMA DE GESTÃO DE ASSISTÊNCIA TÉCNICA\nINFORMÁTICA PARA UM MODELO CMMS DE MANUTENÇÃO\nPREVENTIVA E CORRETIVA", true, 18, '1F4E79');
$section->addTextBreak(1);
centerLine("Monografia apresentada como requisito parcial para a obtenção\ndo grau de Licenciatura em Engenharia Informática", false, 12);
$section->addTextBreak(2);
centerLine('Autor:  António Jacinto', true, 13);
$section->addTextBreak(4);
centerLine('Luanda — ' . date('Y'), false, 12);
pageBreak();

// ═══════════════════════════════════════════════════════════════════════════════
//  RESUMO
// ═══════════════════════════════════════════════════════════════════════════════
heading('RESUMO', 14);
body(
    'O presente trabalho descreve o desenvolvimento e a evolução de um Sistema de Gestão de ' .
    'Assistência Técnica Informática, concebido inicialmente para automatizar os processos ' .
    'operacionais de uma oficina de reparação de equipamentos informáticos e posteriormente ' .
    'refactorado para um modelo CMMS (Computerized Maintenance Management System) de gestão de ' .
    'manutenção preventiva e corretiva. O sistema foi implementado com recurso às tecnologias ' .
    'PHP 8.2, MySQL 8.0, HTML5, CSS3, Bootstrap e JavaScript, seguindo o padrão arquitetural MVC ' .
    '(Model-View-Controller). Na sua forma actual, a Ocorrência passou a ser o ponto de entrada ' .
    'real do fluxo de trabalho — substituindo o Orçamento nessa função — encadeando os módulos de ' .
    'Diagnóstico Técnico, Orçamento, Execução da Manutenção e encerramento, com uma máquina de ' .
    'estados própria e um histórico de auditoria completo. A aplicação oferece três perfis de ' .
    'utilizador — Gerente, Técnico e Recepcionista — cada um com funcionalidades específicas e ' .
    'acesso controlado por sessões autenticadas. Os módulos implementados abrangem a gestão de ' .
    'clientes, equipamentos e respectivas categorias, ocorrências, diagnósticos, orçamentos, ' .
    'execução de serviços técnicos, planeamento de manutenção preventiva por periodicidade, ' .
    'abatimento formal de equipamentos, stock de peças e componentes com histórico de ' .
    'movimentações, compras, vendas, movimentação financeira, comissões de técnicos configuráveis, ' .
    'relatórios e geração de documentos em PDF. A refactoração foi entregue em fases incrementais, ' .
    'cada uma validada end-to-end contra a base de dados antes de avançar para a seguinte, sem ' .
    'alterar o layout visual das páginas existentes. Os resultados demonstram que o sistema ' .
    'satisfaz integralmente os requisitos levantados, reduzindo a dependência de processos manuais, ' .
    'introduzindo rastreabilidade completa do ciclo de vida de cada intervenção e aumentando a ' .
    'eficiência operacional da oficina.'
);
$section->addTextBreak(1);
$run = $section->addTextRun();
$run->addText('Palavras-chave: ', ['bold' => true, 'size' => 12]);
$run->addText('Gestão de manutenção, CMMS, Manutenção preventiva e corretiva, Assistência técnica informática, PHP, MySQL, MVC, Sistema de informação.', ['size' => 12]);
pageBreak();

// ═══════════════════════════════════════════════════════════════════════════════
//  ABSTRACT
// ═══════════════════════════════════════════════════════════════════════════════
heading('ABSTRACT', 14);
body(
    'This paper describes the development and evolution of an IT Technical Assistance Management ' .
    'System, initially designed to automate the operational processes of a computer-equipment repair ' .
    'workshop and later refactored into a CMMS (Computerized Maintenance Management System) model ' .
    'for preventive and corrective maintenance management. The system was built using PHP 8.2, ' .
    'MySQL 8.0, HTML5, CSS3, Bootstrap and JavaScript, following the MVC (Model-View-Controller) ' .
    'architectural pattern. In its current form, the Occurrence (Ocorrência) became the real entry ' .
    'point of the workflow — replacing the Budget in that role — chaining the Technical Diagnosis, ' .
    'Budget, Maintenance Execution and closure modules, with its own state machine and a full audit ' .
    'trail. The application provides three user profiles — Manager, Technician and Receptionist — ' .
    'each with specific features and access controlled by authenticated sessions. The implemented ' .
    'modules cover client and equipment management with categories, occurrences, diagnostics, ' .
    'budgeting, technical service execution, periodicity-based preventive maintenance planning, ' .
    'formal equipment decommissioning, parts inventory with a stock-movement ledger, purchasing, ' .
    'sales, financial movement, configurable technician commissions, reports and PDF document ' .
    'generation. The refactor was delivered in incremental phases, each validated end-to-end against ' .
    'the database before moving to the next, without changing the visual layout of existing pages. ' .
    'The results show that the system fully meets the elicited requirements, reducing dependence on ' .
    'manual processes, introducing full traceability of each intervention\'s lifecycle, and increasing ' .
    'the operational efficiency of the workshop.'
);
$section->addTextBreak(1);
$run = $section->addTextRun();
$run->addText('Keywords: ', ['bold' => true, 'size' => 12]);
$run->addText('Maintenance management, CMMS, Preventive and corrective maintenance, IT technical assistance, PHP, MySQL, MVC, Information system.', ['size' => 12]);
pageBreak();

// ═══════════════════════════════════════════════════════════════════════════════
//  ÍNDICE
// ═══════════════════════════════════════════════════════════════════════════════
heading('ÍNDICE GERAL', 14);
$tocItems = [
    '1. FUNDAMENTAÇÃO TEÓRICA',
    '   1.1 Sistemas de Informação e Gestão Empresarial',
    '   1.2 Assistência Técnica Informática — Contexto e Desafios',
    '   1.3 Gestão de Manutenção e o Modelo CMMS',
    '   1.4 Engenharia de Software — Conceitos Fundamentais',
    '   1.5 Padrão Arquitetural MVC',
    '   1.6 Tecnologias Utilizadas',
    '2. METODOLOGIA',
    '   2.1 Abordagem de Desenvolvimento',
    '   2.2 Levantamento de Requisitos',
    '   2.3 Ferramentas e Ambiente de Desenvolvimento',
    '3. RESULTADOS OBTIDOS',
    '   3.1 Requisitos Funcionais',
    '   3.2 Requisitos Não Funcionais',
    '   3.3 Arquitectura do Sistema',
    '   3.4 Diagrama de Casos de Uso',
    '   3.5 Diagrama de Classes',
    '   3.6 Diagrama Entidade-Relacionamento (ER)',
    '   3.7 Diagrama de Sequência — Abertura de Ocorrência',
    '   3.8 Diagrama de Actividades — Fluxo de Manutenção',
    '   3.9 A Máquina de Estados da Ocorrência',
    '   3.10 Funcionalidades por Perfil de Utilizador',
    '       3.10.1 Perfil Gerente (adimin)',
    '       3.10.2 Perfil Técnico (tecnico)',
    '       3.10.3 Perfil Recepcionista (recep)',
    '4. CONCLUSÕES E TRABALHO FUTURO',
    'REFERÊNCIAS BIBLIOGRÁFICAS',
];
foreach ($tocItems as $item) {
    $section->addText($item, ['size' => 11], ['spaceAfter' => 40]);
}
pageBreak();

// ═══════════════════════════════════════════════════════════════════════════════
//  INTRODUÇÃO
// ═══════════════════════════════════════════════════════════════════════════════
heading('INTRODUÇÃO', 14);
body(
    'A rápida proliferação de equipamentos informáticos nas últimas décadas — computadores, ' .
    'portáteis, impressoras, servidores, tablets e telemóveis — gerou uma procura crescente por ' .
    'serviços especializados de manutenção e reparação. Em Angola, este sector tem crescido de ' .
    'forma acelerada, mas a maioria das empresas de assistência técnica ainda opera com base em ' .
    'processos manuais ou em sistemas orientados apenas ao orçamento comercial, sem um verdadeiro ' .
    'modelo de gestão do ciclo de vida da manutenção. Esta realidade limita a rastreabilidade das ' .
    'intervenções, dificulta o planeamento preventivo e compromete a qualidade do serviço prestado ' .
    'ao cliente.'
);
body(
    'O presente trabalho surge, numa primeira fase, da necessidade de digitalizar os processos de ' .
    'uma oficina de assistência técnica informática — registo de clientes e equipamentos, gestão de ' .
    'orçamentos, controlo de stock, fluxo financeiro e relatórios — e, numa segunda fase, de fazer ' .
    'evoluir esse sistema para um verdadeiro CMMS (Computerized Maintenance Management System), no ' .
    'qual a intervenção num equipamento é modelada como uma Ocorrência com estado próprio, e não ' .
    'apenas como um documento comercial. Esta evolução aproxima o sistema da prática internacional ' .
    'de gestão de manutenção — aplicável tanto a oficinas comerciais como a departamentos de ' .
    'informática de instituições com parque de equipamentos próprio, como universidades — sem exigir ' .
    'a reescrita do sistema desde o início: a refactoração foi feita de forma incremental e ' .
    'compatível com os dados e o layout já existentes.'
);
body(
    'O trabalho está organizado em três capítulos principais: a Fundamentação Teórica, que ' .
    'estabelece os conceitos base — incluindo o modelo CMMS; a Metodologia, que descreve o processo ' .
    'de desenvolvimento e de refactoração; e os Resultados Obtidos, que apresenta em detalhe a ' .
    'arquitectura, os diagramas UML e as funcionalidades implementadas para cada perfil de ' .
    'utilizador, incluindo os módulos introduzidos na evolução para o modelo CMMS.'
);
pageBreak();

// ═══════════════════════════════════════════════════════════════════════════════
//  CAP 1 — FUNDAMENTAÇÃO TEÓRICA
// ═══════════════════════════════════════════════════════════════════════════════
heading('1. FUNDAMENTAÇÃO TEÓRICA', 14);

heading('1.1 Sistemas de Informação e Gestão Empresarial', 13, 12);
body(
    'Um sistema de informação (SI) é definido como um conjunto organizado de recursos — humanos, ' .
    'tecnológicos e organizacionais — que recolhe, processa, armazena e comunica informação para ' .
    'suportar a tomada de decisão, a coordenação e o controlo de uma organização (Laudon & Laudon, ' .
    '2021). Os sistemas de informação de gestão (SIG) constituem uma categoria especial de SI ' .
    'orientada para fornecer aos gestores informação estruturada e resumida sobre as operações da ' .
    'empresa.'
);
body(
    'Para as pequenas e médias empresas (PME), os SIG representam um instrumento fundamental de ' .
    'competitividade: permitem reduzir erros operacionais, acelerar o atendimento ao cliente e ' .
    'fornecer dados em tempo real sobre o desempenho do negócio. Turban et al. (2018) destacam que a ' .
    'digitalização dos processos operacionais através de sistemas de informação é um dos principais ' .
    'factores de diferenciação competitiva no século XXI.'
);

heading('1.2 Assistência Técnica Informática — Contexto e Desafios', 13, 12);
body(
    'A assistência técnica informática engloba serviços de diagnóstico, manutenção preventiva, ' .
    'manutenção correctiva e reparação de equipamentos de tecnologia da informação. A gestão eficaz ' .
    'destes serviços exige o controlo coordenado de múltiplos processos: entrada de equipamentos, ' .
    'diagnóstico técnico, elaboração de orçamentos, aquisição de peças, execução da reparação e ' .
    'entrega ao cliente.'
);
body(
    'Os principais desafios identificados no contexto das oficinas de informática em Angola incluem: ' .
    '(i) ausência de rastreabilidade dos equipamentos em reparação; (ii) dificuldade na gestão do ' .
    'stock de componentes; (iii) falta de transparência no cálculo de orçamentos e comissões; ' .
    '(iv) inexistência de dados históricos que permitam análises financeiras; (v) comunicação ' .
    'deficiente entre técnicos e recepção; (vi) ausência de um modelo formal de planeamento de ' .
    'manutenção preventiva, que obriga a esperar pela avaria em vez de a antecipar. Um sistema de ' .
    'gestão integrado, alinhado com os princípios de um CMMS, é a solução natural para estes ' .
    'problemas.'
);

heading('1.3 Gestão de Manutenção e o Modelo CMMS', 13, 12);
body(
    'Um CMMS (Computerized Maintenance Management System) é um sistema de informação especializado ' .
    'na gestão do ciclo de vida de activos e das intervenções de manutenção sobre eles, cobrindo ' .
    'tipicamente o registo do activo, a abertura da ocorrência/pedido de intervenção, o diagnóstico, ' .
    'a execução do trabalho, o consumo de peças e o encerramento com histórico auditável ' .
    '(Wireman, 2014). A literatura distingue claramente dois tipos de manutenção: a ' .
    '**manutenção correctiva**, accionada por uma falha já ocorrida, e a **manutenção preventiva**, ' .
    'planeada por periodicidade ou por condição, com o objectivo de reduzir a taxa de falhas e o ' .
    'tempo de indisponibilidade dos equipamentos.'
);
body(
    'No sistema desenvolvido, este modelo materializa-se na entidade Ocorrência, que substitui o ' .
    'Orçamento como ponto de entrada do fluxo: a Ocorrência guarda o tipo de manutenção ' .
    '(Corretiva/Preventiva), a prioridade, o(s) equipamento(s) associado(s), o técnico responsável e ' .
    'uma máquina de estados própria (Aberta → Em diagnóstico → Aguardando orçamento → Aguardando ' .
    'aprovação → Em manutenção → Concluída/Cancelada), com um histórico de auditoria de cada ' .
    'transição. O módulo de Planeamento de Manutenção Preventiva completa o modelo, permitindo ' .
    'definir uma periodicidade (em dias) por equipamento e gerar automaticamente novas ocorrências ' .
    'preventivas quando o plano vence.'
);

heading('1.4 Engenharia de Software — Conceitos Fundamentais', 13, 12);
body(
    'A Engenharia de Software (ES) é a área da informática que estuda a aplicação sistemática e ' .
    'disciplinada de princípios de engenharia ao desenvolvimento, operação e manutenção de software ' .
    '(Sommerville, 2019). Os seus pilares incluem a especificação de requisitos, o design de ' .
    'arquitectura, a implementação, os testes e a manutenção.'
);
body(
    'Para o presente projecto foi seguido um ciclo de vida incremental e iterativo, no qual o ' .
    'sistema foi desenvolvido — e mais tarde refactorado para o modelo CMMS — em incrementos ' .
    'funcionais. Esta abordagem permitiu validar progressivamente cada módulo novo contra a base de ' .
    'dados real, com testes end-to-end, minimizando o risco de regressão nas funcionalidades já ' .
    'existentes.'
);
body(
    'A UML (Unified Modeling Language) foi utilizada como notação padrão para a modelação do ' .
    'sistema, conforme recomendado pelo OMG (Object Management Group). Os diagramas elaborados — ' .
    'casos de uso, classes, entidade-relacionamento, sequência, actividades e máquina de estados — ' .
    'são apresentados no Capítulo 3.'
);

heading('1.5 Padrão Arquitetural MVC', 13, 12);
body('O padrão Model-View-Controller (MVC) é um padrão de arquitectura de software que separa uma aplicação em três componentes lógicos (Gamma et al., 1994):');
bullet('Model (Modelo): responsável pela lógica de negócio e pelo acesso aos dados. No sistema desenvolvido, os modelos encontram-se em app/adms/Models/ e utilizam PDO para comunicar com a base de dados MySQL.');
bullet('View (Vista): responsável pela apresentação da interface ao utilizador. As vistas estão em app/adms/Views/ e são ficheiros PHP que geram HTML dinâmico com Bootstrap 4, reutilizando sempre a mesma estrutura visual — incluindo nos módulos introduzidos pela refactoração para CMMS.');
bullet('Controller (Controlador): responsável por receber as acções do utilizador, invocar o modelo adequado e seleccionar a vista a renderizar. Os controladores residem em app/adms/Controllers/.');
body(
    'A separação de responsabilidades proporcionada pelo MVC facilita a manutenção, os testes e a ' .
    'evolução do sistema — foi esta separação, em particular, que permitiu introduzir os novos ' .
    'módulos de Ocorrências, Diagnóstico, Execução e Planeamento sem necessidade de reescrever as ' .
    'vistas existentes. O roteamento é realizado pelo ConfigController, que interpreta o parâmetro ' .
    'GET \'url\' e instancia dinamicamente o controlador correspondente; o controlo de acesso é ' .
    'feito pela classe Permissao, que verifica a sessão autenticada antes de qualquer controlador ' .
    'restrito ser executado.'
);

heading('1.6 Tecnologias Utilizadas', 13, 12);
body(
    'A selecção das tecnologias foi orientada pelos critérios de maturidade, disponibilidade de ' .
    'documentação, custo zero de licenciamento e compatibilidade com o ambiente XAMPP (servidor ' .
    'local amplamente utilizado no contexto angolano).'
);
addTable(
    ['Tecnologia / Ferramenta', 'Versão', 'Função no Sistema'],
    [
        ['PHP', '8.2', 'Linguagem de backend — lógica de negócio e controlo MVC'],
        ['MySQL', '8.0+', 'Sistema de gestão de base de dados relacional'],
        ['HTML5 / CSS3', '—', 'Estrutura e estilo das páginas web'],
        ['Bootstrap', '4.x', 'Framework CSS responsivo para interface gráfica (SB Admin 2)'],
        ['JavaScript / jQuery', '3.x', 'Interactividade do lado do cliente'],
        ['PHPMailer', '6.12', 'Envio de e-mails (recuperação de senha, notificações)'],
        ['mPDF', '8.3', 'Geração de documentos PDF (relatórios, orçamentos)'],
        ['PHPWord', '1.4', 'Geração de documentos Word (este documento)'],
        ['Composer', '2.x', 'Gestão de dependências PHP (PSR-4 autoloading)'],
        ['XAMPP', '8.2', 'Ambiente de desenvolvimento local (Apache + MySQL + PHP)'],
        ['Git', '—', 'Controlo de versões do código-fonte'],
    ],
    [1.8, 0.9, 3.6]
);

pageBreak();

// ═══════════════════════════════════════════════════════════════════════════════
//  CAP 2 — METODOLOGIA
// ═══════════════════════════════════════════════════════════════════════════════
heading('2. METODOLOGIA', 14);

heading('2.1 Abordagem de Desenvolvimento', 13, 12);
body(
    'O desenvolvimento do sistema seguiu uma abordagem incremental e iterativa, inspirada nos ' .
    'princípios das metodologias ágeis. A refactoração para o modelo CMMS, em particular, foi ' .
    'entregue em seis fases sucessivas, cada uma correspondendo a um conjunto coerente de módulos, ' .
    'e cada uma testada de ponta a ponta contra a base de dados de desenvolvimento antes de avançar ' .
    'para a fase seguinte — sem alterar o layout visual das páginas já existentes.'
);
addTable(
    ['Fase', 'Âmbito'],
    [
        ['0', 'Infra-estrutura: sistema de migrações versionado, ligação real entre contas de login e fichas de técnico/recepcionista'],
        ['1', 'Ocorrências (módulo central): entidade independente, múltiplos equipamentos, máquina de estados, histórico'],
        ['2', 'Diagnóstico Técnico: histórico de diagnósticos ligado à ocorrência, encaminhamento para orçamento'],
        ['3', 'Orçamento e Execução da Manutenção sob a Ocorrência (fase de maior risco — controladores de maior tráfego)'],
        ['4', 'Categorias de Equipamento e Equipamentos Abatidos (workflow de aprovação)'],
        ['5', 'Planeamento de Manutenção Preventiva por periodicidade'],
        ['6', 'Comissões configuráveis por técnico/serviço e histórico de movimentações de stock'],
    ],
    [0.6, 5.7]
);
body(
    'Esta escolha justifica-se pelo facto de o sistema já estar em uso — não se tratava de um ' .
    'projecto novo, mas de uma evolução sobre uma base de dados e um layout reais, tornando ' .
    'indispensável testar cada incremento isoladamente antes de o considerar concluído.'
);

heading('2.2 Levantamento de Requisitos', 13, 12);
body('O levantamento de requisitos foi realizado através de três técnicas complementares:');
bullet('Entrevistas semi-estruturadas com o gestor da oficina, para compreender os processos actuais, os problemas recorrentes e as expectativas face ao sistema — incluindo a necessidade de acompanhar o ciclo completo de uma intervenção, não apenas o seu aspecto comercial.');
bullet('Observação directa do fluxo de trabalho da oficina, permitindo identificar etapas não verbalizadas nas entrevistas (e.g., o diagnóstico informal feito antes de qualquer orçamento ser emitido).');
bullet('Análise do código e da base de dados já existentes, incluindo uma tabela "ocorrencias" que já existia como sombra automática dos orçamentos, mas sem ecrã, sem controlador e sem máquina de estados própria — a promoção dessa tabela a entidade central foi uma decisão de desenho tomada com base nesta análise.');
body('Os requisitos foram classificados em funcionais e não funcionais, conforme apresentado no Capítulo 3.');

heading('2.3 Ferramentas e Ambiente de Desenvolvimento', 13, 12);
body(
    'O ambiente de desenvolvimento foi configurado com XAMPP 8.2 sobre Windows, proporcionando um ' .
    'servidor Apache local, servidor MySQL e interpretador PHP. O controlo de versões foi gerido com ' .
    'Git. A gestão de dependências PHP foi realizada com Composer, que instala automaticamente as ' .
    'bibliotecas PHPMailer, mPDF e, para este documento, PHPWord.'
);
body(
    'Para a modelação do sistema foram utilizados diagramas UML, apresentados nas secções seguintes ' .
    'através de descrição textual estruturada, dado o contexto de produção deste documento.'
);

pageBreak();

// ═══════════════════════════════════════════════════════════════════════════════
//  CAP 3 — RESULTADOS OBTIDOS
// ═══════════════════════════════════════════════════════════════════════════════
heading('3. RESULTADOS OBTIDOS', 14);
body(
    'Este capítulo apresenta os artefactos técnicos produzidos ao longo do desenvolvimento e da ' .
    'refactoração para o modelo CMMS, incluindo os requisitos do sistema, a sua arquitectura, os ' .
    'principais diagramas UML e a descrição detalhada das funcionalidades disponíveis para cada ' .
    'perfil de utilizador.'
);

// ── 3.1 Requisitos Funcionais ──────────────────────────────────────────────────
heading('3.1 Requisitos Funcionais', 13, 12);
body('Os requisitos funcionais descrevem as funcionalidades que o sistema deve disponibilizar. Foram organizados por módulo, conforme a tabela seguinte.');
addTable(
    ['ID', 'Módulo', 'Descrição do Requisito'],
    [
        ['RF01', 'Autenticação', 'O sistema deve permitir o login com e-mail e senha, diferenciando os perfis adimin, tecnico e recep.'],
        ['RF02', 'Autenticação', 'O sistema deve suportar recuperação de senha por e-mail, com código de verificação de 6 dígitos com expiração.'],
        ['RF03', 'Utilizadores', 'O gerente deve poder criar, editar e desactivar contas de técnicos e recepcionistas.'],
        ['RF04', 'Clientes', 'O sistema deve permitir o registo, edição e consulta de clientes com NBI, NIF, contacto e morada.'],
        ['RF05', 'Equipamentos', 'O sistema deve registar equipamentos informáticos associados a clientes, com código, património, nome, departamento e localização.'],
        ['RF06', 'Equipamentos', 'O sistema deve categorizar equipamentos através de uma tabela de categorias estruturada (não texto livre).'],
        ['RF07', 'Equipamentos', 'O sistema deve suportar um workflow formal de abatimento de equipamentos, com motivo, solicitante e aprovador.'],
        ['RF08', 'Ocorrências', 'O sistema deve permitir abrir uma ocorrência associando um ou vários equipamentos, prioridade, tipo de manutenção e técnico responsável.'],
        ['RF09', 'Ocorrências', 'O sistema deve implementar uma máquina de estados para a ocorrência (Aberta → Em diagnóstico → Aguardando orçamento → Aguardando aprovação → Em manutenção → Concluída/Cancelada).'],
        ['RF10', 'Ocorrências', 'O sistema deve manter um histórico auditável de cada mudança de estado, com data, utilizador e observação.'],
        ['RF11', 'Diagnóstico', 'O sistema deve permitir registar, por ocorrência, o problema encontrado, a solução proposta e as peças solicitadas.'],
        ['RF12', 'Diagnóstico', 'O sistema deve permitir encaminhar um diagnóstico para orçamento, bloqueando-o para edição a partir desse momento.'],
        ['RF13', 'Orçamentos', 'O sistema deve permitir criar orçamentos ligados a uma ocorrência de origem, pré-preenchendo cliente e equipamento.'],
        ['RF14', 'Orçamentos', 'O sistema deve permitir adicionar peças ao orçamento, actualizando o stock automaticamente.'],
        ['RF15', 'Orçamentos', 'O sistema deve gerar o orçamento em PDF para impressão ou envio ao cliente.'],
        ['RF16', 'Execução', 'O sistema deve permitir iniciar e encerrar a execução de uma manutenção associada a uma ocorrência, actualizando o estado do(s) equipamento(s) ao encerrar.'],
        ['RF17', 'Execução', 'O sistema deve permitir registar as peças efectivamente usadas numa execução, com reposição de stock em caso de remoção.'],
        ['RF18', 'Planeamento', 'O sistema deve permitir definir planos de manutenção preventiva por equipamento, com periodicidade em dias.'],
        ['RF19', 'Planeamento', 'O sistema deve gerar automaticamente uma ocorrência preventiva quando um plano vence, avançando a próxima execução.'],
        ['RF20', 'Stock', 'O sistema deve manter o stock de peças e componentes, alertando quando atingir o nível mínimo configurável.'],
        ['RF21', 'Stock', 'O sistema deve manter um histórico (ledger) de todas as entradas e saídas de stock, com origem e utilizador.'],
        ['RF22', 'Compras', 'O sistema deve registar compras a fornecedores, actualizando o stock e criando a conta a pagar correspondente.'],
        ['RF23', 'Finanças', 'O sistema deve registar toda a movimentação de caixa e gerir contas a pagar e a receber.'],
        ['RF24', 'Comissões', 'O sistema deve permitir configurar a percentagem de comissão por técnico e/ou por tipo de serviço, com prioridade sobre um valor global.'],
        ['RF25', 'Comissões', 'O sistema deve calcular e registar automaticamente a comissão do técnico, incluindo a percentagem efectivamente aplicada.'],
        ['RF26', 'Relatórios', 'O sistema deve gerar relatórios de serviços, orçamentos, movimentação, compras, vendas, contas e comissões, filtráveis por período.'],
        ['RF27', 'Relatórios', 'O sistema deve calcular o custo total (peças + serviço) por ocorrência.'],
        ['RF28', 'Perfil', 'Cada utilizador deve poder editar o seu perfil e foto.'],
    ],
    [0.6, 1.1, 4.6]
);

// ── 3.2 Requisitos Não Funcionais ─────────────────────────────────────────────
heading('3.2 Requisitos Não Funcionais', 13, 12);
addTable(
    ['ID', 'Categoria', 'Descrição'],
    [
        ['RNF01', 'Segurança', 'As páginas restritas verificam a sessão PHP antes de renderizar qualquer conteúdo.'],
        ['RNF02', 'Segurança', 'O acesso directo a ficheiros PHP internos é bloqueado pela constante R4F5CC verificada em todos os ficheiros.'],
        ['RNF03', 'Integridade', 'As relações novas introduzidas na refactoração (ocorrência-equipamento, diagnóstico, execução, planeamento, etc.) têm restrições de chave estrangeira (FK) reais na base de dados.'],
        ['RNF04', 'Compatibilidade', 'A refactoração para o modelo CMMS não deve alterar o comportamento nem o layout visual das funcionalidades já existentes.'],
        ['RNF05', 'Desempenho', 'O sistema deve responder a qualquer acção do utilizador em menos de 3 segundos, em condições de rede local.'],
        ['RNF06', 'Usabilidade', 'A interface deve ser responsiva (Bootstrap), adaptando-se a ecrãs de desktop e tablet, e os novos ecrãs devem reutilizar a mesma estrutura visual dos existentes.'],
        ['RNF07', 'Manutenibilidade', 'O código deve seguir o padrão PSR-4 de autoloading, com um Model dedicado por entidade nos módulos novos (em vez de concentrar lógica em modelos genéricos).'],
        ['RNF08', 'Portabilidade', 'O sistema deve funcionar em qualquer servidor com PHP 8.x, MySQL 8.x e servidor web Apache ou Nginx.'],
        ['RNF09', 'Configurabilidade', 'Os parâmetros de negócio globais (comissão por omissão, desconto, stock mínimo) devem ser configuráveis via ficheiro .env.'],
        ['RNF10', 'Rastreabilidade', 'Toda a mudança de estado de uma ocorrência e todo o movimento de stock devem ficar registados de forma imutável, com data e utilizador responsável.'],
        ['RNF11', 'Internacionalização', 'A interface e os documentos gerados devem estar em língua portuguesa, com valores monetários em Kwanza (Kz).'],
    ],
    [0.7, 1.3, 4.3]
);

// ── 3.3 Arquitectura ──────────────────────────────────────────────────────────
heading('3.3 Arquitectura do Sistema', 13, 12);
body('O sistema adopta a arquitectura em três camadas (Three-Tier Architecture) combinada com o padrão MVC, conforme ilustrado na descrição estrutural abaixo:');

preformatted(
"+---------------------------------------------------------------+
|                    CAMADA DE APRESENTACAO                     |
|  (Views - app/adms/Views/)                                    |
|  HTML5 . CSS3 . Bootstrap 4 . JavaScript / jQuery              |
+---------------------------------------------------------------+
|                    CAMADA DE NEGOCIO                           |
|  (Controllers - app/adms/Controllers/)                         |
|  ConfigController (router) . Permissao (ACL) . Controllers     |
|                                                                 |
|  (Models - app/adms/Models/)                                   |
|  AdmsHome . AdmsTecnico . AdmsRecepcionista . AdmsOcorrencia    |
|  AdmsDiagnostico . AdmsExecucao . AdmsPlaneamento               |
|  AdmsCategoriaEquipamento . AdmsAbatimento                      |
|  AdmsComissaoConfig . AdmsMovimentoEstoque . Conn (PDO)         |
+---------------------------------------------------------------+
|                    CAMADA DE DADOS                              |
|  MySQL 8.0  .  Base de dados: manutencao                        |
|  37+ tabelas . 8 views . InnoDB . utf8mb4                       |
|  schema.sql (fonte de verdade) + migrate.php (runner versionado)|
+---------------------------------------------------------------+

  UTILITARIOS TRANSVERSAIS
  PHPMailer 6.12 (e-mail)  .  mPDF 8.3 (PDF)  .  PHPWord 1.4 (Word)
  Config.php (.env loader)  .  Composer (PSR-4 autoload)"
);

body(
    'O fluxo de uma requisição HTTP típica é o seguinte: o utilizador acede a uma URL do tipo ' .
    'index.php?url=ocorrencia; o index.php inicia a sessão e instancia o ConfigController; o ' .
    'ConfigController carrega as constantes de configuração, verifica as permissões via classe ' .
    'Permissao e instancia dinamicamente o controlador Ocorrencia; o controlador invoca o modelo ' .
    'AdmsOcorrencia para obter/persistir dados; os dados são passados à ConfigView, que inclui o ' .
    'ficheiro de vista correspondente — reutilizando sempre o mesmo layout partilhado (cabeçalho, ' .
    'barra lateral, rodapé) — e renderiza o HTML final.'
);
body(
    'Uma decisão de arquitectura relevante da refactoração foi a introdução de um sistema de ' .
    'migrações versionado (tabela schema_migrations + ficheiros numerados em database/migrations/, ' .
    'aplicados por um runner permanente migrate.php), substituindo a prática anterior de scripts ' .
    'SQL descartáveis e, sobretudo, removendo uma instrução DDL (CREATE TABLE) que corria a cada ' .
    'pedido HTTP dentro do próprio bootstrap da aplicação.'
);

// ── 3.4 Diagrama de Casos de Uso ──────────────────────────────────────────────
heading('3.4 Diagrama de Casos de Uso', 13, 12);
body('O diagrama de casos de uso representa as interacções entre os actores (utilizadores) e o sistema. Os três actores principais são o Gerente (adimin), o Técnico (tecnico) e a Recepcionista (recep).');

preformatted(
"                    +--------------------------------------------+
                    |         SISTEMA DE GESTAO CMMS              |
  +---------+       |                                              |
  | GERENTE |------>| UC01 Gerir Utilizadores/Tecnicos/Recepcao    |
  | (adimin)|       | UC02 Gerir Clientes e Equipamentos           |
  +---------+       | UC03 Gerir Categorias de Equipamento         |
       |            | UC04 Aprovar/Rejeitar Abatimento             |
       |            | UC05 Configurar Comissoes                    |
       |            | UC06 Gerir Produtos / Stock / Fornecedores   |
       |            | UC07 Ver Relatorios e Graficos                |
  +--------------+  | UC08 Gerir Ocorrencias (qualquer estado)     |
  |   TECNICO    |->| UC09 Abrir Ocorrencia                        |
  |  (tecnico)   |  | UC10 Registar Diagnostico                    |
  +--------------+  | UC11 Criar Orcamento a partir de Ocorrencia  |
       |            | UC12 Iniciar/Encerrar Execucao                |
  +---------------+ | UC13 Gerir Planeamento Preventivo             |
  |RECEPCIONISTA  |->| UC14 Aprovar Orcamento                       |
  |   (recep)     |  | UC15 Solicitar Abatimento                    |
  +---------------+  | UC16 Gerir Contas a Pagar/Receber             |
       |            | UC17 Consultar Historico de Movimentacoes     |
  (todos)           | UC18 Gerir Perfil / Foto                      |
       +----------->| UC19 Login / Logout / Recuperar Senha         |
                    +--------------------------------------------+"
, 7.5);

// ── 3.5 Diagrama de Classes ───────────────────────────────────────────────────
heading('3.5 Diagrama de Classes', 13, 12);
body('O diagrama de classes reflecte a estrutura orientada a objectos do backend do sistema. Nos módulos introduzidos pela refactoração, cada entidade passou a ter o seu próprio Model dedicado, em vez de acrescentar métodos aos modelos genéricos já existentes (AdmsTecnico, AdmsRecepcionista).');

preformatted(
"+----------------------------------------+
| <<package>> Models (nucleo pre-existente)|
|  Conn (PDO, classe base)                |
|  AdmsHome . AdmsTecnico . AdmsRecepcionista|
|  AdmsLogin . AdmsPerfil . AdmsGraficos    |
+----------------------------------------+

+----------------------------------------------+
| <<package>> Models (modulos CMMS - novos)     |
|                                                |
|  AdmsOcorrencia                                |
|  -------------------                           |
|  +cdsOcorrencia() +alterarEstado()             |
|  +atribuirTecnico() +dadosHistoricoOcorrencia()|
|  +criarOcorrenciaPreventiva()                  |
|                                                |
|  AdmsDiagnostico    AdmsExecucao                |
|  AdmsPlaneamento    AdmsCategoriaEquipamento    |
|  AdmsAbatimento     AdmsComissaoConfig          |
|  AdmsMovimentoEstoque                           |
+----------------------------------------------+

+----------------------------------------------+
| <<package>> Controllers (modulos CMMS - novos)|
|  Ocorrencia . Diagnostico . Execucao           |
|  Planeamento . CategoriaEquipamento             |
|  Abatimento . ComissaoConfig . MovimentoEstoque |
+----------------------------------------------+"
, 8);

// ── 3.6 Diagrama ER ────────────────────────────────────────────────────────────
heading('3.6 Diagrama Entidade-Relacionamento (ER)', 13, 12);
body('A base de dados do sistema, após a refactoração, passou a conter mais de 37 tabelas e 8 views. O núcleo do novo modelo gira à volta da Ocorrência, conforme ilustrado a seguir.');

preformatted(
"clientes (1)---(N) equipamento (N)---(1) categoria_equipamento
                    |
                    | (N)
        ocorrencia_equipamento (N)---(1) ocorrencias (1)---(N) ocorrencia_historico
                                            |      |
                                            |      +---(N) diagnostico
                                            |
                                            +---(1) orcamentos (N)---(N) orc_prod---produto
                                            |          |
                                            |          +---(N) conntas_areceber
                                            |
                                            +---(N) execucao_manutencao (1)---(N) execucao_peca

equipamento (1)---(N) equipamentos_abatidos   (workflow de aprovacao)
equipamento (1)---(N) plano_manutencao_preventiva ---(gera)---> ocorrencias

usuario (1)---(N) comissao_config     (percentagem por tecnico/servico)
usuario (1)---(N) comissao            (percentual_aplicado gravado por linha)
produto (1)---(N) movimento_estoque   (ledger de entradas/saidas)"
, 9);

body('A tabela seguinte resume as entidades introduzidas ou alteradas pela refactoração para o modelo CMMS:');
addTable(
    ['Entidade', 'Atributos-Chave', 'Relações'],
    [
        ['ocorrencias', 'idocorrencia, prioridade, tipo_manutencao, estado, idtecnico_responsavel, data_prevista', 'N:1 usuario; 1:N ocorrencia_equipamento, ocorrencia_historico, diagnostico, execucao_manutencao; 1:1 orcamentos'],
        ['ocorrencia_equipamento', 'idocorrencia_equipamento, id_ocorrencia, id_equipamento', 'N:1 ocorrencias; N:1 equipamento (junção N:N)'],
        ['ocorrencia_historico', 'idocorrencia_historico, estado_anterior, estado_novo, idusuario', 'N:1 ocorrencias; N:1 usuario'],
        ['diagnostico', 'iddiagnostico, problema_descrito, solucao_proposta, encaminhado_orcamento', 'N:1 ocorrencias; N:1 equipamento; N:1 usuario'],
        ['execucao_manutencao', 'idexecucao, estado, data_inicio, data_fim', 'N:1 ocorrencias; N:1 orcamentos; 1:N execucao_peca'],
        ['execucao_peca', 'idexecucao_peca, quantidade', 'N:1 execucao_manutencao; N:1 produto'],
        ['categoria_equipamento', 'idcategoria_equipamento, nome', '1:N equipamento'],
        ['equipamentos_abatidos', 'idabatimento, motivo, estado, idusuario_solicitante, idusuario_aprovador', 'N:1 equipamento; N:1 usuario (x2)'],
        ['plano_manutencao_preventiva', 'idplano, periodicidade_dias, proxima_execucao, ativo', 'N:1 equipamento; N:1 tipo_servico; N:1 usuario'],
        ['comissao_config', 'idconfig, idusuario_tecnico, id_tipo_servico, percentual', 'N:1 usuario; N:1 tipo_servico'],
        ['movimento_estoque', 'idmovimento, tipo, quantidade, origem, id_referencia', 'N:1 produto; N:1 usuario'],
        ['orcamentos', 'idorcamentos, id_ocorrencia (novo), veiculo, status, tipo', 'N:1 ocorrencias (novo); N:1 equipamento; 1:N orc_prod'],
    ],
    [1.5, 2.4, 2.4]
);

// ── 3.7 Diagrama de Sequência ─────────────────────────────────────────────────
heading('3.7 Diagrama de Sequência — Abertura de Ocorrência', 13, 12);
body('O diagrama de sequência abaixo descreve o fluxo de mensagens entre os componentes do sistema durante a abertura de uma ocorrência pelo Técnico ou Recepcionista.');

preformatted(
"Utilizador   Browser   index.php  ConfigController  Permissao  Ocorrencia(Ctrl)  AdmsOcorrencia(Model)  BD MySQL
    |           |           |            |               |             |                  |               |
    |--[POST]-->|           |            |               |             |                  |               |
    | btnCdsOcorrencia      |            |               |             |                  |               |
    |           |--[require]->|          |               |             |                  |               |
    |           |           |--[new]---->|               |             |                  |               |
    |           |           |            |--[index(\$url)]->|            |                  |               |
    |           |           |            |               |--verificar()->|                 |               |
    |           |           |            |               |<--OK---------|                 |               |
    |           |           |            |--[new Ocorrencia]------------>|                 |               |
    |           |           |            |               |               |--[new AdmsOcorrencia]---------->|
    |           |           |            |               |               |                |--[cdsOcorrencia()]->|
    |           |           |            |               |               |                |<-[INSERT ocorrencias]|
    |           |           |            |               |               |                |--[vincularEquipamento() x N]->|
    |           |           |            |               |               |                |--[registarHistorico()]->|
    |           |           |            |               |               |<--retorno------|                |
    |           |           |            |               |               |--[dadosOcorrencias()]----------->|
    |           |           |            |               |               |<--[SELECT result]---------------|
    |           |           |            |               |               |--[ConfigView::renderizar()]      |
    |<----[HTML renderizado]-------------------------------------------------------------------------------|"
, 6.5);

// ── 3.8 Diagrama de Actividades ───────────────────────────────────────────────
heading('3.8 Diagrama de Actividades — Fluxo de Manutenção', 13, 12);
body('O diagrama de actividades descreve o fluxo completo de uma intervenção, desde a abertura da ocorrência até ao encerramento — o "Fluxo Geral do Sistema" que orientou toda a refactoração.');

preformatted(
"  *  INICIO
  |
  v
  [Recepcionista regista Cliente e Equipamento]
  |  -> Tabelas: clientes, equipamento (+ categoria_equipamento)
  |
  v
  [Tecnico ou Recepcionista abre Ocorrencia]
  |  -> Tabela: ocorrencias (estado: Aberta) + ocorrencia_equipamento (N equipamentos)
  |  -> ocorrencia_historico: 1a linha
  |
  v
  [Tecnico regista Diagnostico]
  |  -> Tabela: diagnostico   |  Ocorrencia -> 'Em diagnostico'
  |
  v
  [Tecnico encaminha para Orcamento]
  |  -> diagnostico.encaminhado_orcamento=1  |  Ocorrencia -> 'Aguardando orcamento'
  |
  v
  [Orcamento criado a partir da Ocorrencia]
  |  -> orcamentos.id_ocorrencia (ligacao)   |  Ocorrencia -> 'Aguardando aprovacao'
  |
  <>  Cliente aprova?
  |
  |-- NAO --> [Ocorrencia -> Cancelada]  --> * FIM
  |
  +-- SIM -->
  |
  v
  [Orcamento aprovado]
  |  -> conntas_areceber (adiantamento)  |  Ocorrencia -> 'Aprovado' (espelho do orcamento)
  |
  v
  [Tecnico inicia Execucao]
  |  -> execucao_manutencao (Em execucao) |  Ocorrencia -> 'Em manutencao'
  |  -> pecas usadas -> execucao_peca + movimento_estoque (Saida)
  |
  v
  [Tecnico encerra Execucao]
  |  -> execucao_manutencao (Concluida)   |  Ocorrencia -> 'Concluida'
  |  -> equipamento.estado = 'Concluido'
  |  -> comissao: registo automatico (percentual configurado x valor do servico)
  |
  v
  [Recepcionista efectua entrega ao cliente]
  |
  v
  * FIM"
, 8.5);

// ── 3.9 Máquina de Estados ────────────────────────────────────────────────────
heading('3.9 A Máquina de Estados da Ocorrência', 13, 12);
body(
    'O elemento central da refactoração é a máquina de estados finita da Ocorrência, aplicada de ' .
    'forma consistente por todos os módulos que a tocam (Diagnóstico, Orçamento, Execução e ' .
    'Planeamento) e registada de forma imutável em ocorrencia_historico a cada transição:'
);
preformatted(
"  [Aberta] --diagnostico registado--> [Em diagnostico]
      |                                       |
      |                              encaminhar p/ orcamento
      |                                       v
      |                          [Aguardando orcamento]
      |                                       |
      |                              orcamento criado
      |                                       v
      |                        [Aguardando aprovacao]
      |                                       |
      |                              orcamento aprovado
      |                                       v
      |                          [Em manutencao] --execucao encerrada--> [Concluida]
      |
      +----------------------------(a qualquer momento)--------------------------> [Cancelada]"
, 9);
body(
    'Esta consistência é garantida a nível de código pelo método AdmsOcorrencia::alterarEstado(), ' .
    'que valida o novo estado contra uma lista fechada de valores permitidos e grava sempre uma ' .
    'linha de histórico — nenhum outro módulo escreve directamente na coluna estado sem passar por ' .
    'este método.'
);

// ── 3.10 Funcionalidades por Perfil ────────────────────────────────────────────
heading('3.10 Funcionalidades por Perfil de Utilizador', 13, 12);
body(
    'O sistema implementa controlo de acesso baseado em perfil (RBAC — Role-Based Access Control). ' .
    'O nível de cada utilizador é armazenado no campo nivel da tabela usuario. As secções seguintes ' .
    'detalham as funcionalidades específicas de cada perfil, com destaque para os módulos ' .
    'introduzidos pela refactoração CMMS.'
);

heading('3.10.1 Perfil Gerente (adimin)', 12, 12);
body('O Gerente possui acesso irrestrito a todos os módulos do sistema, incluindo os de configuração e aprovação introduzidos pela refactoração:');
addTable(
    ['Módulo', 'URL', 'Funcionalidade'],
    [
        ['Ocorrências', '?url=ocorrencia', 'Gestão completa de qualquer ocorrência: criar, editar, atribuir técnico, alterar estado, histórico.'],
        ['Categorias de Equipamentos', '?url=categoriaEquipamento', 'CRUD de categorias estruturadas de equipamento.'],
        ['Abatimentos', '?url=abatimento', 'Fila de aprovação/rejeição de pedidos de abatimento de equipamento.'],
        ['Planeamento Preventivo', '?url=planeamento', 'Definição de planos de manutenção preventiva e geração de ocorrências pendentes.'],
        ['Configuração de Comissões', '?url=comissaoConfig', 'Definição da percentagem de comissão por técnico e/ou tipo de serviço.'],
        ['Histórico de Movimentações', '?url=movimentoEstoque', 'Consulta do ledger de entradas/saídas de stock por produto.'],
        ['Gestão de Técnicos/Recepcionistas/Fornecedores/Produtos', '?url=tecnico, etc.', 'CRUD completo, herdado do sistema pré-existente.'],
        ['Relatórios e Gráficos', '?url=relatorio, ?url=graficos', 'Relatórios filtráveis por período, incluindo custo por ocorrência.'],
    ],
    [1.8, 1.6, 2.8]
);

heading('3.10.2 Perfil Técnico (tecnico)', 12, 12);
body('O Técnico é o principal utilizador dos novos módulos de manutenção — é ele quem percorre o fluxo Ocorrência → Diagnóstico → Execução no dia-a-dia:');
addTable(
    ['Módulo', 'URL', 'Funcionalidade'],
    [
        ['Ocorrências', '?url=ocorrencia', 'Abrir ocorrências, ver as que lhe estão atribuídas, consultar histórico.'],
        ['Diagnósticos', '?url=diagnostico', 'Registar problema/solução/peças; encaminhar para orçamento.'],
        ['Execuções', '?url=execucao', 'Iniciar/encerrar execução; adicionar/remover peças usadas.'],
        ['Planeamento Preventivo', '?url=planeamento', 'Consultar e gerir os planos de que é responsável.'],
        ['Orçamentos / Serviços', '?url=orcamento, ?url=servico', 'Fluxo pré-existente, agora também acessível a partir de uma Ocorrência.'],
        ['Comissões', '?url=comissoes', 'Consulta do histórico de comissões, incluindo a percentagem aplicada em cada linha.'],
    ],
    [1.6, 1.5, 3.1]
);
body(
    'O cálculo da comissão passou a consultar a configuração específica do técnico e/ou do tipo de ' .
    'serviço (módulo AdmsComissaoConfig), com fallback para uma percentagem global e, na ausência de ' .
    'qualquer configuração, para a constante VALOR_COMISSAO do ficheiro .env — preservando o ' .
    'comportamento anterior para quem não personalizar nada.'
);

heading('3.10.3 Perfil Recepcionista (recep)', 12, 12);
body('A Recepcionista gere o front-office da oficina e é frequentemente quem abre a ocorrência ao receber o equipamento:');
addTable(
    ['Módulo', 'URL', 'Funcionalidade'],
    [
        ['Clientes / Equipamentos', '?url=cliente, ?url=equipamento', 'CRUD completo, agora com categoria de equipamento e dados patrimoniais.'],
        ['Ocorrências', '?url=ocorrencia', 'Abrir ocorrência ao receber um equipamento para reparação.'],
        ['Abatimentos', '?url=abatimento', 'Solicitar abatimento de um equipamento (aprovação fica a cargo do Gerente).'],
        ['Orçamentos (Recepção)', '?url=orcamentoRecepcao', 'Aprovar orçamentos submetidos pelos técnicos.'],
        ['Contas a Pagar / Receber', '?url=contasPagar, ?url=contaReceber', 'Gestão financeira do dia-a-dia, herdada do sistema pré-existente.'],
    ],
    [1.8, 1.7, 2.7]
);

pageBreak();

// ═══════════════════════════════════════════════════════════════════════════════
//  CAP 4 — CONCLUSÕES
// ═══════════════════════════════════════════════════════════════════════════════
heading('4. CONCLUSÕES E TRABALHO FUTURO', 14);
body(
    'O sistema de gestão de assistência técnica informática, na sua forma evoluída para um modelo ' .
    'CMMS, cumpre integralmente os objectivos traçados. A refactoração incremental provou ser uma ' .
    'estratégia adequada para introduzir um novo paradigma de gestão (a Ocorrência como entidade ' .
    'central) sobre um sistema já em produção, sem interromper o seu funcionamento nem alterar o ' .
    'layout com que os utilizadores já estavam familiarizados.'
);
body(
    'Os principais ganhos obtidos com a evolução para o modelo CMMS são: (i) rastreabilidade ' .
    'completa do ciclo de vida de cada intervenção, do diagnóstico ao encerramento, com histórico ' .
    'auditável; (ii) introdução do planeamento de manutenção preventiva por periodicidade, antes ' .
    'inexistente; (iii) formalização do processo de abatimento de equipamentos, com aprovação; ' .
    '(iv) configurabilidade das comissões por técnico e tipo de serviço, eliminando a rigidez de um ' .
    'valor único; (v) visibilidade total sobre as movimentações de stock, antes reduzidas a uma ' .
    'quantidade sem histórico; (vi) reforço da integridade referencial da base de dados, através de ' .
    'chaves estrangeiras reais em todas as relações novas.'
);
body('Como trabalho futuro, identificam-se as seguintes melhorias de maior impacto:');
bullet('Migração do hash de senha de MD5 para bcrypt ou Argon2, eliminando a vulnerabilidade mais crítica do sistema actual.');
bullet('Reforço do controlo de acesso (Permissao.php) para verificar o nível do utilizador por rota, e não apenas a existência de sessão iniciada — hoje o controlo por perfil é sobretudo visual (esconder/mostrar menu).');
bullet('Normalização das relações antigas que ainda são "soltas" (ex.: orcamentos.veiculo como texto), fora do âmbito desta refactoração por serem mais arriscadas de alterar.');
bullet('Automatização real dos lembretes de manutenção preventiva (hoje geridos por um script agendável a nível de sistema operativo, cron_planeamento.php) através de um serviço de notificações (e-mail/SMS).');
bullet('Desenvolvimento de uma API REST para permitir a criação de uma aplicação móvel que permita aos técnicos actualizarem o estado das ocorrências em campo.');
bullet('Implementação de testes automáticos (PHPUnit) para garantir a regressão em futuras iterações, complementando os testes manuais end-to-end realizados durante esta refactoração.');

pageBreak();

// ═══════════════════════════════════════════════════════════════════════════════
//  REFERÊNCIAS
// ═══════════════════════════════════════════════════════════════════════════════
heading('REFERÊNCIAS BIBLIOGRÁFICAS', 14);

$refs = [
    'GAMMA, E.; HELM, R.; JOHNSON, R.; VLISSIDES, J. Design Patterns: Elements of Reusable Object-Oriented Software. Addison-Wesley, 1994.',
    'LAUDON, K. C.; LAUDON, J. P. Management Information Systems: Managing the Digital Firm. 16.ª ed. Pearson Education, 2021.',
    'PRESSMAN, R. S.; MAXIM, B. R. Engenharia de Software: Uma Abordagem Profissional. 8.ª ed. McGraw-Hill, 2016.',
    'SOMMERVILLE, I. Software Engineering. 10.ª ed. Pearson Education, 2019.',
    'TURBAN, E.; VOLONINO, L.; WOOD, G. Information Technology for Management: Driving Digital Transformation to Increase Local and Global Performance, Growth and Sustainability. 11.ª ed. Wiley, 2018.',
    'WIREMAN, T. Computerized Maintenance Management Systems. 3.ª ed. Industrial Press, 2014.',
    'PHP GROUP. PHP Manual. Disponível em: https://www.php.net/manual/. Acesso em: julho de 2026.',
    'MYSQL. MySQL 8.0 Reference Manual. Oracle Corporation. Disponível em: https://dev.mysql.com/doc/refman/8.0/en/. Acesso em: julho de 2026.',
    'OMG — OBJECT MANAGEMENT GROUP. Unified Modeling Language Specification, version 2.5.1. 2017. Disponível em: https://www.omg.org/spec/UML/.',
    'BOOTSTRAP. Bootstrap 4 Documentation. Disponível em: https://getbootstrap.com/docs/4.6/. Acesso em: julho de 2026.',
    'SYNACTIS. mPDF Documentation. Disponível em: https://mpdf.github.io/. Acesso em: julho de 2026.',
    'PHPOFFICE. PHPWord Documentation. Disponível em: https://phpword.readthedocs.io/. Acesso em: julho de 2026.',
    'COMPOSER. Dependency Manager for PHP. Disponível em: https://getcomposer.org/. Acesso em: julho de 2026.',
];
foreach ($refs as $i => $ref) {
    $section->addText(($i + 1) . '. ' . $ref, ['size' => 11], [
        'alignment' => Jc::BOTH,
        'spaceAfter' => 80,
        'indentation' => ['left' => Converter::cmToTwip(1.25), 'hanging' => Converter::cmToTwip(1.25)],
    ]);
}

// ═══════════════════════════════════════════════════════════════════════════════
//  GRAVAR
// ═══════════════════════════════════════════════════════════════════════════════
$outputPath = __DIR__ . '/Monografia_Assistencia_Tecnica_Informatica.docx';
$writer = IOFactory::createWriter($phpWord, 'Word2007');
$writer->save($outputPath);
echo "Monografia gerada com sucesso: {$outputPath}\n";
