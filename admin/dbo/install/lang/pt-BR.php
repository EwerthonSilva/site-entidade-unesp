<?

//general text
define(INSTALL_TITLE, 'Instalação');
define(INSTALL_DESCRIPTION, 'siga os passos :)');
define(STEP1, 'Passo 1');
define(STEP2, 'Passo 2');
define(STEP3, 'Passo 3');
define(STEP4, 'Passo 4');
define(WORD_NEXT, 'Prosseguir');
define(WORD_CHECK, 'Testar');
define(ERROR_PENDING_STEPS, 'Há configurações pendentes nos passos anteriores');
define(ERROR_NO_WRITE_PERMISSION_LIB_AND_DBO_FOLDER, "Não posso escrever nas pastas 'lib' e 'dbo'!");
define(ERROR_NO_WRITE_PERMISSION_LIB_FOLDER, "Não posso escrever na pasta 'lib'!");
define(ERROR_NO_WRITE_PERMISSION_DBO_FOLDER, "Não posso escrever na pasta 'dbo'!");
define(WORD_RETRY, 'Tentar novamente');
define(ERROR_IRREGULAR_ACCESS, 'Tentativa irregular de acesso à instalação do sistema. Consulte o desenvolvedor.');
define(VIEW_STATUS_REPORT, 'Visualizar o status de instalação');

//step 1 text
define(STEP1_INSTRUCTIONS, 'Configurações do banco de dados');
define(STEP1_DB_HOST, 'Host do mySQL');
define(STEP1_DB_USER, 'Usuário do mySQL');
define(STEP1_DB_PASS, 'Senha do mySQL');
define(STEP1_DB_BASE, 'Nome do banco de dados');
define(STEP1_CONNECTION_SUCCESSFUL, 'Conexão criada com sucesso!');
define(STEP1_CONNECTION_FAILED, 'Sem conexão com o banco.');
define(STEP1_CONNECTION_OK_BUT, 'Conexão OK, mas...');
define(STEP1_CANT_WRITE_DB_FILE, 'Não posso escrever no arquivo <b>\'lib/db.php\'</b>');

//step 2 text
define(STEP2_ERROR_NO_NAME, 'Preencha o nome');
define(STEP2_ERROR_NO_USERNAME, 'Preecha o nome de usuário');
define(STEP2_ERROR_NO_EMAIL, 'Preencha o e-mail');
define(STEP2_ERROR_NO_PASS, 'Escolha uma senha');
define(STEP2_ERROR_WRONG_PASS, 'As senhas digitadas não conferem');
define(STEP2_ADMIN_INFORMATION_H2, 'Informações do administrador');
define(STEP2_CREATE_USER, 'Criar Usuário');
define(STEP2_USER_SUCCESS, 'Acesso homologado!');

//step 3 text
define(STEP3_BASIC_SETTINGS, 'Configurações básicas');
define(STEP3_SYSTEM_NAME, 'Nome do sistema');
define(STEP3_SYSTEM_DESCRIPTION, 'Descrição do sistema');
define(STEP3_DBO_URL, 'URL da pasta \'dbo\'');
define(STEP3_DBO_PERMISSIONS, 'Módulo de permissões');
define(STEP3_ADVANCED_SETTINGS, 'Configurações avançadas');
define(STEP3_SUPER_ADMINS, 'Super-admins');
define(STEP3_FULL_PAGES, 'Páginas full');
define(STEP3_SAVE_SETTINGS, 'Salvar');
define(STEP3_ERROR_NO_SYSTEM_NAME, 'Digite o nome do sistema');
define(STEP3_ERROR_NO_SYSTEM_DESCRIPTION, 'Digite a descrição do sistema');
define(STEP3_ERROR_NO_DBO_URL, 'Digite a URL da pasta dbo');
define(STEP3_ERROR_NO_SUPER_ADMINS, 'Escolha ao menos 1 super-admin');
define(STEP3_ERROR_WRONG_DBO_URL, 'A URL da pasta dbo está errada');
define(STEP3_ERROR_NO_WRITE_PERMISSION_DEFINES, 'Arquivo \'lib/defines.php\' sem perm. de escrita');
define(STEP3_VALIDATED_AND_READY, 'Configurações salvas!');

//step 4 text
define(STEP4_SYSTEM_COLORS, 'Cores do sistema');
define(STEP4_COLOR_HEADER, 'Cabeçalho');
define(STEP4_COLOR_MENU, 'Menu');
define(STEP4_COLOR_DESCRIPTION, 'Descrição');
define(STEP4_COLOR_TITLE, 'Títulos');
define(STEP4_H1, 'Pessoas');
define(STEP4_H2, 'Pessoas Cadastradas');
define(STEP4_SAVE_COLORS, 'Atualizar esquema de cores');

//status report text
define(STATUS_REPORT_TITLE, 'Status da instalação');
define(STATUS_REPORT_DB_CONNECTION, 'Conexão com o banco de dados');
define(STATUS_REPORT_ADMINS, 'Usuário administrador');
define(STATUS_REPORT_DEFINES, 'Configurações do sistema');
define(STATUS_REPORT_COLORS, 'Cores do sistema');
define(STATUS_OK, 'Ok');
define(STATUS_FAIL, 'Falha');
define(STAUTS_REPORT_MESSAGE_FINISH_STEPS, 'Ops... Ainda existem pendências!');
define(STATUS_REPORT_CONTINUE_INSTALL, 'Continuar a instalação...');
define(STATUS_REPORT_FINISH_INSTALL, 'Sucesso! Encerre o instalador e leve-me ao sistema!');


?>