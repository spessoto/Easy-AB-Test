[English](#english) | [Português (Brasil)](#português-brasil) | [Español](#español)

---

# English

# Simple AB Test Redirect

**Contributors:** Caio Spessoto
**Tags:** a/b test, ab test, split test, marketing, optimization, wordpress, redirect, trigger url, conversion tracking, reports
**Requires at least:** 6.0.0
**Tested up to:** 6.8.1
**Stable tag:** 3.3.5
**License:** GPLv2 or later
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html

Advanced and secure plugin for multiple A/B tests with trigger URLs, redirection, detailed logs, conversion tracking, graphical reports, notifications, and auditing.

## Description

Simple AB Test Redirect is an advanced and secure WordPress plugin designed to empower you with robust A/B testing capabilities. Optimize your website by effortlessly creating and managing multiple A/B tests. This plugin goes beyond simple page variations, offering features like trigger URLs for precise test activation, redirection mechanisms, and comprehensive tracking. With detailed logs, conversion tracking, and graphical reports, you gain deep insights into user behavior and test performance. Built-in notifications keep you informed, while auditing features ensure transparency and control over your experiments. Simple AB Test Redirect makes data-driven website optimization accessible and effective.

### Key Features

*   **Multiple A/B tests:** Run several experiments simultaneously to optimize different aspects of your site.
*   **Test Scheduling:** Set optional Start and End dates for your A/B tests.
*   **Trigger URLs:** Specify precisely which page/URL will activate your A/B test.
*   **Redirection:** Seamlessly redirect users to different variations based on test conditions.
*   **Detailed logs:** Keep a comprehensive record of test activities, user interactions, and corrections.
*   **Conversion Tracking:** Define specific conversion links (e.g., a thank-you page) to measure the success of each variation.
*   **Graphical reports:** Visualize test performance with easy-to-understand charts and graphs.
*   **Notifications:** Receive email alerts for new accesses/conversions (rate-limited).
*   **Auditing:** Track admin actions (test creation/update/deletion) with logs stored in `wp-content/uploads/sabtr-audit-logs/`.
*   **Bot & Crawler Exclusion:** Helps keep your test data more accurate by attempting to filter out known bot traffic.
*   **Configurable Settings:** Manage email notifications, audit logging, bot exclusion, database log retention, and plugin data reset via **WordPress Admin > Settings > AB Test Config**.
*   **Easy to Use:** Designed with simplicity in mind, making advanced A/B testing accessible without needing to code.
*   **Adjustable Traffic Distribution:** Control the percentage of visitors directed to Page B (defaults to 50%).
*   **Developer Friendly:** Includes filters like `sabtr_match_query_string_for_tests` for advanced URL matching control.

## Installation

1.  Download the `simple-ab-test-redirect.zip` file (assuming this will be the plugin's packed filename).
2.  In your WordPress admin panel, navigate to **Plugins > Add New**.
3.  Click the **Upload Plugin** button at the top of the page.
4.  Choose the `simple-ab-test-redirect.zip` file you downloaded.
5.  Click **Install Now** and then **Activate Plugin**.

## How to Use (Getting Started)

Once the plugin is activated, you should find a "Simple AB Test Redirect" menu in your WordPress admin area for creating and managing tests, and a settings page.

### Setting up your first A/B Test:

1.  Navigate to the **Simple AB Test Redirect** section in your WordPress admin panel.
2.  Click on an option like **"Create New Test"** or **"Add Test"**.
3.  Fill in the test details:
    *   **Trigger URL:** Enter the full URL of the page you intend to test (e.g., `https://www.yoursite.com/your-page`). This is the URL visitors will access to activate the test.
    *   **Page A URL (Control):** Enter the full URL for the original version of your page (e.g., `https://www.yoursite.com/your-page-version-a`). This is your control.
    *   **Page B URL (Variation):** Enter the full URL for the variation page you want to test against Page A (e.g., `https://www.yoursite.com/your-page-version-b`).
    *   **Traffic to Page B (%):** Use the slider or input field to set the percentage of visitors who will be directed to Page B. The default is 50%.
    *   **Start Date (Optional):** Set a specific date for the test to begin. If left blank, the test will start as soon as it's published (and active according to its schedule).
    *   **End Date (Optional):** Set a specific date for the test to end. If left blank, the test will run indefinitely until manually stopped or if an end date is set later. The test will not run after this date.
    *   **Conversion Link:** Enter the full URL that signifies a successful conversion for this test. This could be a thank-you page after a form submission, a purchase confirmation page, etc. (e.g., `https://www.yoursite.com/thank-you`).
4.  Save your test configuration.

### Plugin Settings (Configuration)

Configure the plugin's behavior by navigating to **WordPress Admin > Settings > AB Test Config**.
Available options include:

*   **Email Notifications:** Enable or disable email alerts. When enabled, the plugin sends notifications for new accesses or conversions to the admin email. These are rate-limited to avoid flooding your inbox.
*   **Admin Audit Log:** Enable or disable the logging of administrative actions such as test creation, updates, or deletions. Audit logs are stored as `.log` files in the `wp-content/uploads/sabtr-audit-logs/` directory. Files older than 30 days are automatically deleted.
*   **Enable Bot/Crawler Exclusion:** When checked (default), the plugin attempts to identify and exclude known bots and web crawlers (based on their User-Agent strings) from being recorded in your A/B test statistics (access logs and counts). This helps in keeping your test data cleaner.
*   **Database Log Retention:** Set the number of days (from 7 to 365, default is 60) that access logs and conversion logs will be kept in the database. Older logs are automatically deleted by a daily cron job.
*   **Audit Log Management:**
    *   **Download Audit Log:** Download today's audit log file.
    *   **Clear All Audit Logs:** Permanently delete all audit log files from the server.
*   **Plugin Data Reset:**
    *   **Clear My Cookies:** Deletes plugin-specific cookies from your browser, which track the variant assigned to you and any conversions you've made. Useful for testing your A/B tests as if you were a new visitor.
    *   **Clear Notification Transients:** Clears stored notification flags in WordPress. This can help if you believe notifications are stuck or not sending correctly.
    *   Note: These actions do not delete any A/B tests or collected log data from the database or audit log files.

### Viewing Results:

1.  Go to the **Simple AB Test Redirect Dashboard** (likely within the "Simple AB Test Redirect" menu).
2.  Here, you will find data on the number of visits, conversions, and graphical reports for both Page A and Page B for your active tests. The report table also includes columns for 'Status' (e.g., Active, Scheduled, Expired), 'Scheduled Start', and 'Scheduled End' to help you track the active periods of your tests.

## Frequently Asked Questions (FAQ)

*   **Can I test more than two variations (e.g., A/B/C testing)?**
    *   Currently, Simple AB Test Redirect is designed for A/B testing, meaning one control (Page A) and one variation (Page B) per test. Support for multiple variations might be considered for future releases.
*   **How long should I run my A/B test?**
    *   The ideal duration depends on your website traffic. You need enough data to make a statistically significant decision. This could range from a few days to several weeks. Utilize the detailed logs and graphical reports to monitor progress.
*   **Does this plugin calculate statistical significance for A/B test results?**
    *   No, Simple AB Test Redirect provides the raw data for visits and conversions for each variant. For determining statistical significance, it's recommended to use this data with external statistical calculators or tools.
*   **What kind of pages can I test?**
    *   You can test any page on your WordPress site as long as you can provide distinct URLs for Page A, Page B, the trigger, and the conversion link.
*   **How does the plugin match Trigger and Conversion URLs? Can I control if query strings are included?**
    *   By default, the plugin normalizes URLs by comparing paths and ignoring query strings (e.g., `?utm_source=...`). For advanced use, developers can use the `sabtr_match_query_string_for_tests` filter to change this behavior and include query strings in the matching logic.
*   **What happens if a visitor somehow lands on a different variation page than the one they were assigned?**
    *   The plugin uses cookies to remember the variant assigned to a visitor for a specific test. If a visitor with an assigned variant lands on the trigger URL again, or even the URL of the *other* variant, the plugin will attempt to redirect them back to their originally assigned variant's URL. This "correction logic" helps maintain the integrity of the test groups. Accesses that required such correction are logged specially (e.g., "Corrected access to variant A").
*   **Where are logs stored and are they cleaned up?**
    *   Admin actions (audit logs) are logged to files in `wp-content/uploads/sabtr-audit-logs/` and are automatically cleaned up after 30 days. Test access and conversion data (visitor logs) are stored in custom database tables and are automatically cleaned up based on the retention period you set in **Settings > AB Test Config** (default is 60 days).
*   **Does this plugin affect site speed?**
    *   Simple AB Test Redirect is designed to be lightweight and efficient. The redirection and logging mechanisms are optimized for minimal performance impact. However, like any plugin, it adds some processing. It's always good practice to monitor site performance.

## Screenshots

*(It is highly recommended to add screenshots here once the plugin interface is finalized. Good screenshots would include:)*
*   *The A/B test setup screen showing new options like trigger URLs.*
*   *The results dashboard with graphical reports and detailed logs.*
*   *The "AB Test Config" settings page.*
*   *The notification or auditing interface, if applicable.*

## Changelog

*   **3.3.5**
    *   Added Enhanced Bot/Crawler Exclusion: Implemented a feature to filter out known bots and crawlers based on User-Agent strings.
    *   Added a setting in "Settings > AB Test Config" to enable/disable this feature (enabled by default).
*   **3.3.4**
    *   Added Test Scheduling: Users can now set optional Start and End dates for A/B tests.
    *   Tests will only run if the current date is within their scheduled period.
    *   Report page now displays "Status", "Scheduled Start", and "Scheduled End" for each test.
*   **3.3.3**
    *   Enhanced A/B test dashboard:
        *   Added new data columns: "Started", "Duration", "Uplift (B vs A)", "Last Activity".
        *   Implemented a "View Logs" button per test to show recent access/conversion logs in a modal.
        *   Changed the conversion chart to display "Conversion Rate Comparison" (rates % instead of counts).
        *   Linked test titles in the report table to their edit screens.
*   **3.3.2**
    *   Initial detailed README.md with multilingual support.
    *   Updated plugin name to Simple AB Test Redirect.
    *   Added new features: multiple tests, redirection, detailed logs, graphical reports, notifications, auditing, configurable settings, developer filters.
    *   Enhanced FAQ and usage instructions.

## Support

caio.spessoto@hotmail.com

# Português (Brasil)

# Simple AB Test Redirect

**Contributors:** Caio Spessoto
**Tags:** teste a/b, ab test, split test, marketing, otimização, wordpress, redirecionamento, url gatilho, rastreamento de conversão, relatórios
**Requires at least:** 6.0.0
**Tested up to:** 6.8.1
**Stable tag:** 3.3.5
**License:** GPLv2 ou posterior
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html

Plugin avançado e seguro para múltiplos testes A/B com URLs de gatilho, redirecionamento, logs detalhados, rastreamento de conversões, relatórios gráficos, notificações e auditoria.

## Descrição

Simple AB Test Redirect é um plugin WordPress avançado e seguro, projetado para capacitar você com robustas funcionalidades de teste A/B. Otimize seu site criando e gerenciando múltiplos testes A/B sem esforço. Este plugin vai além de simples variações de página, oferecendo recursos como URLs de gatilho para ativação precisa de testes, mecanismos de redirecionamento e rastreamento abrangente. Com logs detalhados, rastreamento de conversões e relatórios gráficos, você obtém insights profundos sobre o comportamento do usuário e o desempenho dos testes. Notificações integradas mantêm você informado, enquanto os recursos de auditoria garantem transparência e controle sobre seus experimentos. O Simple AB Test Redirect torna a otimização de sites baseada em dados acessível e eficaz.

### Principais Funcionalidades

*   **Múltiplos testes A/B:** Execute vários experimentos simultaneamente para otimizar diferentes aspectos do seu site.
*   **Agendamento de Testes:** Defina datas de Início e Término opcionais para seus testes A/B.
*   **URLs de Gatilho:** Especifique precisamente qual página/URL ativará seu teste A/B.
*   **Redirecionamento:** Redirecione usuários de forma transparente para diferentes variações com base nas condições do teste.
*   **Logs Detalhados:** Mantenha um registro abrangente das atividades de teste, interações do usuário e correções.
*   **Rastreamento de Conversão:** Defina links de conversão específicos (ex: uma página de agradecimento) para medir o sucesso de cada variação.
*   **Relatórios Gráficos:** Visualize o desempenho do teste com gráficos fáceis de entender.
*   **Notificações:** Receba alertas por e-mail para novos acessos/conversões (com limite de taxa).
*   **Auditoria:** Acompanhe as ações administrativas (criação/atualização/exclusão de testes) com logs armazenados em `wp-content/uploads/sabtr-audit-logs/`.
*   **Exclusão de Bots e Rastreadores:** Ajuda a manter os dados do seu teste mais precisos, tentando filtrar o tráfego de bots conhecidos.
*   **Configurações Ajustáveis:** Gerencie notificações por e-mail, logs de auditoria, exclusão de bots, retenção de logs do banco de dados e redefinição de dados do plugin via **Painel WordPress > Configurações > AB Test Config**.
*   **Fácil de Usar:** Projetado com a simplicidade em mente, tornando o teste A/B avançado acessível sem necessidade de codificar.
*   **Distribuição de Tráfego Ajustável:** Controle a porcentagem de visitantes direcionados para a Página B (o padrão é 50%).
*   **Amigável para Desenvolvedores:** Inclui filtros como `sabtr_match_query_string_for_tests` para controle avançado de correspondência de URL.

## Instalação

1.  Baixe o arquivo `simple-ab-test-redirect.zip` (assumindo que este será o nome do arquivo compactado do plugin).
2.  No seu painel administrativo do WordPress, navegue até **Plugins > Adicionar Novo**.
3.  Clique no botão **Fazer upload do plugin** no topo da página.
4.  Escolha o arquivo `simple-ab-test-redirect.zip` que você baixou.
5.  Clique em **Instalar agora** e depois em **Ativar Plugin**.

## Como Usar (Primeiros Passos)

Assim que o plugin for ativado, você deverá encontrar um menu "Simple AB Test Redirect" na sua área de administração do WordPress para criar e gerenciar testes, e uma página de configurações.

### Configurando seu primeiro Teste A/B:

1.  Navegue até a seção **Simple AB Test Redirect** no seu painel administrativo do WordPress.
2.  Clique em uma opção como **"Criar Novo Teste"** ou **"Adicionar Teste"**.
3.  Preencha os detalhes do teste:
    *   **URL Gatilho:** Insira a URL completa da página que você pretende testar (ex: `https://www.seusite.com/sua-pagina`). Esta é a URL que os visitantes acessarão para ativar o teste.
    *   **URL da Página A (Controle):** Insira a URL completa para a versão original da sua página (ex: `https://www.seusite.com/sua-pagina-versao-a`). Este é o seu controle.
    *   **URL da Página B (Variação):** Insira a URL completa para a página de variação que você quer testar contra a Página A (ex: `https://www.seusite.com/sua-pagina-versao-b`).
    *   **Tráfego para Página B (%):** Use o controle deslizante ou campo de entrada para definir a porcentagem de visitantes que serão direcionados para a Página B. O padrão é 50%.
    *   **Data de Início (Opcional):** Defina uma data específica para o início do teste. Se deixado em branco, o teste começará assim que for publicado (e ativo conforme sua programação).
    *   **Data de Término (Opcional):** Defina uma data específica para o término do teste. Se deixado em branco, o teste será executado indefinidamente até ser interrompido manualmente ou se uma data de término for definida posteriormente. O teste não será executado após esta data.
    *   **Link de Conversão:** Insira a URL completa que significa uma conversão bem-sucedida para este teste. Esta pode ser uma página de agradecimento após o envio de um formulário, uma página de confirmação de compra, etc. (ex: `https://www.seusite.com/obrigado`).
4.  Salve sua configuração de teste.

### Configurações do Plugin (Configuração)

Configure o comportamento do plugin navegando até **Painel WordPress > Configurações > AB Test Config**.
As opções disponíveis incluem:

*   **Notificações por Email:** Ative ou desative alertas por e-mail. Quando ativado, o plugin envia notificações de novos acessos ou conversões para o e-mail do administrador. Elas têm um limite de taxa para evitar sobrecarregar sua caixa de entrada.
*   **Log de Auditoria do Admin:** Ative ou desative o registro de ações administrativas, como criação, atualizações ou exclusões de testes. Os logs de auditoria são armazenados como arquivos `.log` no diretório `wp-content/uploads/sabtr-audit-logs/`. Arquivos com mais de 30 dias são excluídos automaticamente.
*   **Ativar Exclusão de Bots/Rastreadores:** Quando marcado (padrão), o plugin tenta identificar e excluir bots e rastreadores da web conhecidos (com base em suas strings de User-Agent) de serem registrados nas estatísticas do seu teste A/B (logs e contagens de acesso). Isso ajuda a manter os dados do seu teste mais limpos.
*   **Retenção de Logs do Banco de Dados:** Defina o número de dias (de 7 a 365, padrão é 60) que os logs de acesso e conversão serão mantidos no banco de dados. Logs mais antigos são excluídos automaticamente por uma tarefa cron diária.
*   **Gerenciamento de Logs de Auditoria:**
    *   **Baixar Log de Auditoria:** Baixe o arquivo de log de auditoria do dia atual.
    *   **Limpar Todos os Logs de Auditoria:** Exclua permanentemente todos os arquivos de log de auditoria do servidor.
*   **Redefinição de Dados do Plugin:**
    *   **Limpar Meus Cookies:** Exclui cookies específicos do plugin do seu navegador, que rastreiam a variante atribuída a você e quaisquer conversões que você fez. Útil para testar seus testes A/B como se você fosse um novo visitante.
    *   **Limpar Transientes de Notificação:** Limpa flags de notificação armazenadas no WordPress. Isso pode ajudar se você acreditar que as notificações estão presas ou não estão sendo enviadas corretamente.
    *   Nota: Essas ações não excluem nenhum teste A/B ou dados de log coletados do banco de dados ou arquivos de log de auditoria.

### Visualizando Resultados:

1.  Vá para o **Dashboard do Simple AB Test Redirect** (provavelmente dentro do menu "Simple AB Test Redirect").
2.  Aqui, você encontrará dados sobre o número de visitas, conversões e relatórios gráficos tanto para a Página A quanto para a Página B para seus testes ativos. A tabela de relatórios também inclui colunas para 'Status' (ex: Ativo, Agendado, Expirado), 'Início Agendado' e 'Fim Agendado' para ajudar a rastrear os períodos ativos de seus testes.

## Perguntas Frequentes (FAQ)

*   **Posso testar mais de duas variações (ex: teste A/B/C)?**
    *   Atualmente, o Simple AB Test Redirect é projetado para testes A/B, significando um controle (Página A) e uma variação (Página B) por teste. Suporte para múltiplas variações pode ser considerado para versões futuras.
*   **Por quanto tempo devo executar meu teste A/B?**
    *   A duração ideal depende do tráfego do seu site. Você precisa de dados suficientes para tomar uma decisão estatisticamente significativa. Isso pode variar de alguns dias a várias semanas. Utilize os logs detalhados e relatórios gráficos para monitorar o progresso.
*   **Este plugin calcula significância estatística para os resultados do teste A/B?**
    *   Não, o Simple AB Test Redirect fornece os dados brutos de visitas e conversões para cada variante. Para determinar a significância estatística, recomenda-se usar esses dados com calculadoras ou ferramentas estatísticas externas.
*   **Que tipo de páginas posso testar?**
    *   Você pode testar qualquer página em seu site WordPress, desde que possa fornecer URLs distintas para a Página A, Página B, o gatilho e o link de conversão.
*   **Como o plugin compara as URLs de Gatilho e Conversão? Posso controlar se as query strings são incluídas?**
    *   Por padrão, o plugin normaliza as URLs comparando os caminhos e ignorando as query strings (ex: `?utm_source=...`). Para uso avançado, desenvolvedores podem usar o filtro `sabtr_match_query_string_for_tests` para alterar esse comportamento e incluir query strings na lógica de correspondência.
*   **O que acontece se um visitante de alguma forma acessar uma página de variação diferente da que lhe foi atribuída?**
    *   O plugin usa cookies para lembrar a variante atribuída a um visitante para um teste específico. Se um visitante com uma variante atribuída acessar a URL de gatilho novamente, ou mesmo a URL da *outra* variante, o plugin tentará redirecioná-lo de volta para a URL da sua variante originalmente atribuída. Essa "lógica de correção" ajuda a manter a integridade dos grupos de teste. Acessos que exigiram tal correção são registrados especialmente (ex: "Acesso corrigido à variante A").
*   **Onde os logs são armazenados e eles são limpos?**
    *   Ações administrativas (logs de auditoria) são registradas em arquivos em `wp-content/uploads/sabtr-audit-logs/` e são limpas automaticamente após 30 dias. Dados de acesso e conversão de testes (logs de visitantes) são armazenados em tabelas de banco de dados personalizadas e são limpos automaticamente com base no período de retenção que você definir em **Configurações > AB Test Config** (o padrão é 60 dias).
*   **Este plugin afeta a velocidade do site?**
    *   O Simple AB Test Redirect é projetado para ser leve e eficiente. Os mecanismos de redirecionamento e log são otimizados para impacto mínimo no desempenho. No entanto, como qualquer plugin, ele adiciona algum processamento. É sempre uma boa prática monitorar o desempenho do site.

## Screenshots

*(É altamente recomendável adicionar screenshots aqui assim que a interface do plugin for finalizada. Boas screenshots incluiriam:)*
*   *A tela de configuração do teste A/B mostrando novas opções como URLs de gatilho.*
*   *O dashboard de resultados com relatórios gráficos e logs detalhados.*
*   *A página de configurações "AB Test Config".*
*   *A interface de notificação ou auditoria, se aplicável.*

## Changelog

*   **3.3.5**
    *   Adicionada Exclusão Aprimorada de Bots/Rastreadores: Implementado um recurso para filtrar bots e rastreadores conhecidos com base em strings de User-Agent.
    *   Adicionada uma configuração em "Configurações > Configuração AB Test" para ativar/desativar este recurso (ativado por padrão).
*   **3.3.4**
    *   Adicionado Agendamento de Testes: Usuários agora podem definir datas de Início e Término opcionais para os testes A/B.
    *   Os testes só serão executados se a data atual estiver dentro do período agendado.
    *   A página de relatórios agora exibe "Status", "Início Agendado" e "Fim Agendado" para cada teste.
*   **3.3.3**
    *   Painel de relatórios dos testes A/B aprimorado:
        *   Adicionadas novas colunas de dados: "Iniciado", "Duração", "Uplift (B vs A)", "Última Atividade".
        *   Implementado um botão "Ver Logs" por teste para exibir logs recentes de acesso/conversão em um modal.
        *   Alterado o gráfico de conversão para exibir "Comparação de Taxas de Conversão" (taxas % em vez de contagens).
        *   Títulos dos testes na tabela de relatórios agora são links para suas telas de edição.
*   **3.3.2**
    *   README.md inicial detalhado com suporte multilíngue.
    *   Nome do plugin atualizado para Simple AB Test Redirect.
    *   Adicionadas novas funcionalidades: múltiplos testes, redirecionamento, logs detalhados, relatórios gráficos, notificações, auditoria, configurações ajustáveis, filtros para desenvolvedores.
    *   Instruções de uso e FAQ aprimoradas.

## Suporte

caio.spessoto@hotmail.com

# Español

# Simple AB Test Redirect

**Contributors:** Caio Spessoto
**Tags:** prueba a/b, ab test, split test, marketing, optimización, wordpress, redirección, url de activación, seguimiento de conversiones, informes
**Requires at least:** 6.0.0
**Tested up to:** 6.8.1
**Stable tag:** 3.3.5
**License:** GPLv2 o posterior
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html

Plugin avanzado y seguro para múltiples pruebas A/B con URLs de activación, redirección, logs detallados, seguimiento de conversiones, informes gráficos, notificaciones y auditoría.

## Descripción

Simple AB Test Redirect es un plugin de WordPress avanzado y seguro, diseñado para ofrecerte sólidas capacidades de pruebas A/B. Optimiza tu sitio web creando y gestionando múltiples pruebas A/B sin esfuerzo. Este plugin va más allá de las simples variaciones de página, ofreciendo características como URLs de activación para activación precisa de pruebas, mecanismos de redirección y seguimiento exhaustivo. Con logs detallados, seguimiento de conversiones e informes gráficos, obtienes información valiosa sobre el comportamiento del usuario y el rendimiento de las pruebas. Las notificaciones integradas te mantienen informado, mientras que las funciones de auditoría garantizan la transparencia y el control sobre tus experimentos. Simple AB Test Redirect hace que la optimización de sitios web basada en datos sea accesible y eficaz.

### Características Clave

*   **Múltiples pruebas A/B:** Ejecuta varios experimentos simultáneamente para optimizar diferentes aspectos de tu sitio.
*   **Programación de Pruebas:** Establece fechas de Inicio y Finalización opcionales para tus pruebas A/B.
*   **URLs de Activación:** Especifica con precisión qué página/URL activará tu prueba A/B.
*   **Redirección:** Redirige a los usuarios de forma transparente a diferentes variaciones según las condiciones de la prueba.
*   **Logs Detallados:** Mantén un registro completo de las actividades de prueba, interacciones de los usuarios y correcciones.
*   **Seguimiento de Conversiones:** Define enlaces de conversión específicos (por ejemplo, una página de agradecimiento) para medir el éxito de cada variación.
*   **Informes Gráficos:** Visualiza el rendimiento de la prueba con gráficos fáciles de entender.
*   **Notificaciones:** Recibe alertas por correo electrónico para nuevos accesos/conversiones (con límite de tasa).
*   **Auditoría:** Realiza un seguimiento de las acciones administrativas (creación/actualización/eliminación de pruebas) con logs almacenados en `wp-content/uploads/sabtr-audit-logs/`.
*   **Exclusión de Bots y Rastreadores:** Ayuda a mantener los datos de sus pruebas más precisos al intentar filtrar el tráfico de bots conocidos.
*   **Ajustes Configurables:** Gestiona notificaciones por correo electrónico, registro de auditoría, exclusión de bots, retención de logs de base de datos y restablecimiento de datos del plugin a través de **Escritorio de WordPress > Ajustes > AB Test Config**.
*   **Fácil de Usar:** Diseñado pensando en la simplicidad, haciendo que las pruebas A/B avanzadas sean accesibles sin necesidad de programar.
*   **Distribución de Tráfico Ajustable:** Controla el porcentaje de visitantes dirigidos a la Página B (el valor predeterminado es 50%).
*   **Amigable para Desarrolladores:** Incluye filtros como `sabtr_match_query_string_for_tests` para control avanzado de coincidencia de URL.

## Instalación

1.  Descarga el archivo `simple-ab-test-redirect.zip` (asumiendo que este será el nombre del archivo empaquetado del plugin).
2.  En tu panel de administración de WordPress, navega a **Plugins > Añadir Nuevo**.
3.  Haz clic en el botón **Subir Plugin** en la parte superior de la página.
4.  Elige el archivo `simple-ab-test-redirect.zip` que descargaste.
5.  Haz clic en **Instalar Ahora** y luego en **Activar Plugin**.

## Cómo Usar (Primeros Pasos)

Una vez que el plugin esté activado, deberías encontrar un menú "Simple AB Test Redirect" en tu área de administración de WordPress para crear y gestionar pruebas, y una página de ajustes.

### Configurando tu primera Prueba A/B:

1.  Navega a la sección **Simple AB Test Redirect** en tu panel de administración de WordPress.
2.  Haz clic en una opción como **"Crear Nueva Prueba"** o **"Añadir Prueba"**.
3.  Completa los detalles de la prueba:
    *   **URL de Activación:** Ingresa la URL completa de la página que pretendes probar (por ejemplo, `https://www.tusitio.com/tu-pagina`). Esta es la URL a la que accederán los visitantes para activar la prueba.
    *   **URL de la Página A (Control):** Ingresa la URL completa de la versión original de tu página (por ejemplo, `https://www.tusitio.com/tu-pagina-version-a`). Este es tu control.
    *   **URL de la Página B (Variación):** Ingresa la URL completa de la página de variación que quieres probar contra la Página A (por ejemplo, `https://www.tusitio.com/tu-pagina-version-b`).
    *   **Tráfico a la Página B (%):** Usa el control deslizante o el campo de entrada para establecer el porcentaje de visitantes que serán dirigidos a la Página B. El valor predeterminado es 50%.
    *   **Fecha de Inicio (Opcional):** Establece una fecha específica para que comience la prueba. Si se deja en blanco, la prueba comenzará tan pronto como se publique (y esté activa según su programación).
    *   **Fecha de Finalización (Opcional):** Establece una fecha específica para que finalice la prueba. Si se deja en blanco, la prueba se ejecutará indefinidamente hasta que se detenga manualmente o si se establece una fecha de finalización más tarde. La prueba no se ejecutará después de esta fecha.
    *   **Enlace de Conversión:** Ingresa la URL completa que significa una conversión exitosa para esta prueba. Podría ser una página de agradecimiento después de enviar un formulario, una página de confirmación de compra, etc. (por ejemplo, `https://www.tusitio.com/gracias`).
4.  Guarda la configuración de tu prueba.

### Configuración del Plugin

Configura el comportamiento del plugin navegando a **Escritorio de WordPress > Ajustes > AB Test Config**.
Las opciones disponibles incluyen:

*   **Notificaciones por Correo Electrónico:** Habilita o deshabilita las alertas por correo electrónico. Cuando está habilitado, el plugin envía notificaciones de nuevos accesos o conversiones al correo electrónico del administrador. Estas tienen un límite de tasa para evitar inundar tu bandeja de entrada.
*   **Registro de Auditoría del Admin:** Habilita o deshabilita el registro de acciones administrativas como la creación, actualización o eliminación de pruebas. Los registros de auditoría se almacenan como archivos `.log` en el directorio `wp-content/uploads/sabtr-audit-logs/`. Los archivos con más de 30 días se eliminan automáticamente.
*   **Activar Exclusión de Bots/Rastreadores:** Cuando está marcado (predeterminado), el plugin intenta identificar y excluir bots y rastreadores web conocidos (basado en sus cadenas de User-Agent) de ser registrados en las estadísticas de su prueba A/B (registros y recuentos de acceso). Esto ayuda a mantener más limpios los datos de su prueba.
*   **Retención de Logs de la Base de Datos:** Establece el número de días (de 7 a 365, por defecto 60) que los logs de acceso y conversión se mantendrán en la base de datos. Los logs más antiguos se eliminan automáticamente mediante una tarea cron diaria.
*   **Gestión de Logs de Auditoría:**
    *   **Descargar Log de Auditoría:** Descarga el archivo de log de auditoría del día actual.
    *   **Limpiar Todos los Logs de Auditoría:** Elimina permanentemente todos los archivos de log de auditoría del servidor.
*   **Restablecimiento de Datos del Plugin:**
    *   **Limpiar Mis Cookies:** Elimina las cookies específicas del plugin de tu navegador, que rastrean la variante asignada a ti y cualquier conversión que hayas realizado. Útil para probar tus pruebas A/B como si fueras un nuevo visitante.
    *   **Limpiar Transitorios de Notificación:** Limpia los indicadores de notificación almacenados en WordPress. Esto puede ayudar si crees que las notificaciones están atascadas o no se envían correctamente.
    *   Nota: Estas acciones no eliminan ninguna prueba A/B ni datos de log recopilados de la base de datos o archivos de log de auditoría.

### Viendo Resultados:

1.  Ve al **Dashboard de Simple AB Test Redirect** (probablemente dentro del menú "Simple AB Test Redirect").
2.  Aquí encontrarás datos sobre el número de visitas, conversiones e informes gráficos tanto para la Página A como para la Página B para tus pruebas activas. La tabla de informes también incluye columnas para 'Estado' (ej: Activo, Programado, Expirado), 'Inicio Programado' y 'Final Programado' para ayudarte a rastrear los períodos activos de tus pruebas.

## Preguntas Frecuentes (FAQ)

*   **¿Puedo probar más de dos variaciones (por ejemplo, pruebas A/B/C)?**
    *   Actualmente, Simple AB Test Redirect está diseñado para pruebas A/B, lo que significa un control (Página A) y una variación (Página B) por prueba. El soporte para múltiples variaciones podría considerarse para futuras versiones.
*   **¿Cuánto tiempo debo ejecutar mi prueba A/B?**
    *   La duración ideal depende del tráfico de tu sitio web. Necesitas suficientes datos para tomar una decisión estadísticamente significativa. Esto podría variar desde unos pocos días hasta varias semanas. Utiliza los logs detallados y los informes gráficos para monitorear el progreso.
*   **¿Este plugin calcula la significancia estadística para los resultados de las pruebas A/B?**
    *   No, Simple AB Test Redirect proporciona los datos brutos de visitas y conversiones para cada variante. Para determinar la significancia estadística, se recomienda utilizar estos datos con calculadoras o herramientas estadísticas externas.
*   **¿Qué tipo de páginas puedo probar?**
    *   Puedes probar cualquier página en tu sitio de WordPress siempre que puedas proporcionar URLs distintas para la Página A, la Página B, la de activación y el enlace de conversión.
*   **¿Cómo compara el plugin las URLs de Activación y Conversión? ¿Puedo controlar si se incluyen las cadenas de consulta?**
    *   Por defecto, el plugin normaliza las URLs comparando las rutas e ignorando las cadenas de consulta (por ejemplo, `?utm_source=...`). Para uso avanzado, los desarrolladores pueden usar el filtro `sabtr_match_query_string_for_tests` para cambiar este comportamiento e incluir cadenas de consulta en la lógica de coincidencia.
*   **¿Qué sucede si un visitante de alguna manera llega a una página de variación diferente a la que se le asignó?**
    *   El plugin utiliza cookies para recordar la variante asignada a un visitante para una prueba específica. Si un visitante con una variante asignada llega nuevamente a la URL de activación, o incluso a la URL de la *otra* variante, el plugin intentará redirigirlo de nuevo a la URL de su variante originalmente asignada. Esta "lógica de corrección" ayuda a mantener la integridad de los grupos de prueba. Los accesos que requirieron tal corrección se registran especialmente (por ejemplo, "Acceso corregido a la variante A").
*   **¿Dónde se almacenan los logs y se limpian?**
    *   Las acciones administrativas (logs de auditoría) se registran en archivos en `wp-content/uploads/sabtr-audit-logs/` y se limpian automáticamente después de 30 días. Los datos de acceso y conversión de pruebas (logs de visitantes) se almacenan en tablas de base de datos personalizadas y se limpian automáticamente según el período de retención que establezcas en **Ajustes > AB Test Config** (el valor predeterminado es 60 días).
*   **¿Este plugin afecta la velocidad del sitio?**
    *   Simple AB Test Redirect está diseñado para ser ligero y eficiente. Los mecanismos de redirección y registro están optimizados para un impacto mínimo en el rendimiento. Sin embargo, como cualquier plugin, añade algo de procesamiento. Siempre es una buena práctica monitorear el rendimiento del sitio.

## Screenshots

*(Se recomienda encarecidamente añadir capturas de pantalla aquí una vez que la interfaz del plugin esté finalizada. Buenas capturas de pantalla incluirían:)*
*   *La pantalla de configuración de la prueba A/B mostrando nuevas opciones como URLs de activación.*
*   *El dashboard de resultados con informes gráficos y logs detallados.*
*   *La página de ajustes "AB Test Config".*
*   *La interfaz de notificación o auditoría, si corresponde.*

## Changelog

*   **3.3.5**
    *   Añadida Exclusión Mejorada de Bots/Rastreadores: Implementada una función para filtrar bots y rastreadores conocidos basándose en las cadenas de User-Agent.
    *   Añadida una configuración en "Ajustes > Configuración AB Test" para activar/desactivar esta función (activada por defecto).
*   **3.3.4**
    *   Añadida Programación de Pruebas: Los usuarios ahora pueden establecer fechas de Inicio y Finalización opcionales para las pruebas A/B.
    *   Las pruebas solo se ejecutarán si la fecha actual está dentro de su período programado.
    *   La página de informes ahora muestra "Estado", "Inicio Programado" y "Final Programado" para cada prueba.
*   **3.3.3**
    *   Panel de informes de pruebas A/B mejorado:
        *   Añadidas nuevas columnas de datos: "Iniciado", "Duración", "Uplift (B vs A)", "Última Actividad".
        *   Implementado un botón "Ver Registros" por prueba para mostrar registros recientes de acceso/conversión en un modal.
        *   Cambiado el gráfico de conversión para mostrar "Comparación de Tasas de Conversión" (tasas % en lugar de recuentos).
        *   Los títulos de las pruebas en la tabla de informes ahora enlazan a sus pantallas de edición.
*   **3.3.2**
    *   README.md inicial detallado con soporte multilingüe.
    *   Nombre del plugin actualizado a Simple AB Test Redirect.
    *   Añadidas nuevas características: múltiples pruebas, redirecionamento, logs detalhados, relatórios gráficos, notificações, auditoria, configurações ajustáveis, filtros para desenvolvedores.
    *   Instruções de uso e FAQ aprimoradas.

## Soporte

caio.spessoto@hotmail.com

[end of README.md]
