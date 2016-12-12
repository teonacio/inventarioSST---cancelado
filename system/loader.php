<?php
if ( ! defined('ABSPATH')) exit;
 
// Inicia a sessão
session_start();

// Verifica o modo para debugar
if ( ! defined('DEBUG') || DEBUG === false ) {

	// Esconde todos os erros do sistema.
	error_reporting(0);
	ini_set("display_errors", 0); 
	
} else {

	// Mostra todos os erros do sistema.
	error_reporting(E_ALL);
	ini_set("display_errors", 1); 
	
}

// Funções globais
require_once APPPATH . '/_autoload.php';

// Carrega a aplicação
$inventario_mvc = new InventarioMVC();