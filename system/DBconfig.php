<?php

/** Configurações gerais do banco **/

class DBconfig
{
	public $host = 'localhost'; // Host do banco.
	public $db_name = 'inventario'; // Nome do banco.
	public $user = 'root'; // Login do banco.
	public $password = ''; // Senha do banco.
	public $charset = 'utf8'; // Charset do banco.
	public $debug = true; // Debuga erros do codigo, caso haja. Mantenha em FALSE caso não esteja realizando manutenção.
}