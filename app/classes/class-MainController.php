<?php
/**
 * MainController - Todos os controllers deverão estender essa classe
 */
class MainController extends UserLogin
{

	/**
	 * $db - Conexão com a base de dados via PDO
	 *
	 * @access public
	 */
	public $db;

	/**
	 * $title - Título das páginas 
	 *
	 * @access public
	 */
	public $title;
	
	/**
	 * $logo - Logotipo
	 *
	 * @access public
	 */
	public $logo;

	/**
	 * $login_required - Caso a página necessite de login
	 *
	 * @access public
	 */
	public $login_required = false;

	/**
	 * $parametros
	 *
	 * @access public
	 */
	public $parametros = array();
	
	/**
	 * Construtor da classe
	 *
	 * @access public
	 */
	public function __construct ( $parametros = array() ) {
	
		// Instancia do DB
		$this->db = new InventarioDB();
		
		// Parâmetros
		$this->parametros = $parametros;
		
		// Verifica o login
		$this->check_userlogin();
		
	}
	
	/**
	 * Load model - Carrega os modelos presentes na pasta /models/.
	 *
	 * @access public
	 */
	public function load_model( $model_name = false ) {
	
		// Um arquivo deverá ser enviado
		if ( ! $model_name ) return;
		
		// Garante que o nome do modelo tenha letras minúsculas
		$model_name =  strtolower( $model_name );
		
		// Inclui o arquivo
		$model_path = MODPATH . '/' . $model_name . '.php';
		
		// Verifica se o arquivo existe
		if ( file_exists( $model_path ) ) {
		
			// Inclui o arquivo
			require_once $model_path;
			
			// Remove os caminhos do arquivo (se tiver algum)
			$model_name = explode('/', $model_name);
			
			// Pega só o nome final do caminho
			$model_name = end( $model_name );
			
			// Remove caracteres inválidos do nome do arquivo
			$model_name = preg_replace( '/[^a-zA-Z0-9]/is', '', $model_name );
			
			// Verifica se a classe existe
			if ( class_exists( $model_name ) ) {
			
				// Retorna um objeto da classe
				return new $model_name( $this->db, $this );
			
			}
			
			return;
			
		}
		
	}

}