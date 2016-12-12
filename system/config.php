																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																				<?php
/**
 * Configuração geral
 */
 
// URL da home
// Caso altere a URL original, a nova constante do define deverá ficar: http://NOVA_URL, onde NOVA_URL sera a nova URL do sistema.
define( 'HOME_URI', 'http://localhost/inventario' );

// URL de login
// Caso altere a URL original, a nova constante do define deverá ficar: http://NOVA_URL/login, onde NOVA_URL sera a nova URL do sistema.
define( 'LOGIN_URI', 'http://localhost/inventario/login' );
																																																																																																																																																																																																																																																																																																																													
// Caminho para arquivos gerais
define( 'SYSPATH', ABSPATH . '/system' );

// Caminho para as aplicações do sistema
define( 'APPPATH', ABSPATH . '/app' );

// Caminho para as classes
define( 'CLSPATH', APPPATH . '/classes' );

// Caminho para as classes externas (PHPmailer, etc)
define( 'CLS_EXTPATH', CLSPATH . '/extensions' );

// Caminho para as controllers
define( 'CONPATH', APPPATH . '/controllers' );

// Caminho para as includes (mensagem 404 de erro)
define( 'INCPATH_1', APPPATH . '/includes' );

// Caminho para as models
define( 'MODPATH', APPPATH . '/models' );

// Caminho para as views
define( 'VIWPATH', APPPATH . '/views' );

// Caminho para as _includes = footer / header / menu (Nao confundir com includes)
define( 'INCPATH_2', VIWPATH . '/_includes' );

// Caminho para a view principal (Nao confundir com views)
define( 'HOMEPATH', VIWPATH . '/home' );

// Se você estiver desenvolvendo, modifique o valor para TRUE. Caso não esteja desenvolvendo, modifique o valor para FALSE.
define( 'DEBUG', true );

// Carrega o resto do sistema
require_once SYSPATH .'/loader.php';
?>