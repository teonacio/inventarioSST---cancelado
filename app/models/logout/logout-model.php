<?php 
/**
 * Classe para logout
 *
 * @since 0.1
 */

class LogoutModel
{

	/**
	 * $form_data - Os dados do formulário de envio.
	 *
	 * @access public
	 */	
	public $form_data;

	/**
	 * $form_msg - Mensagens de feedback para o usuário.
	 *
	 * @access public
	 */	
	public $form_msg;

	/**
	 * $db - PDO
	 *
	 * @access public
	 */
	public $db;

	/**
	 * Construtor - Carrega  o DB.
	 * 
	 * @since 0.1
	 * @access public
	 */
	public function __construct( $db = false ) {
		$this->db = $db;
	}
	
	/**
	 * Vai para a página de login
	 */
	public function goto_login() {
		// Verifica se a URL da HOME está configurada
		if ( defined( 'HOME_URI' ) ) {
			// Configura a URL de login
			$login_uri  = HOME_URI . '/login/';
			
			// A página em que o usuário estava
			$_SESSION['goto_url'] = urlencode( $_SERVER['REQUEST_URI'] );
			
			// Redireciona
			echo '<meta http-equiv="Refresh" content="0; url=' . $login_uri . '">';
			echo '<script type="text/javascript"> window.location.href = "' . $login_uri . '";</script>';
			//header('location: ' . $login_uri);
		}
		
		return;
	}
	
	/**
	 * Logout
	 *
	 * Desconfigura tudo do usuario.
	 *
	 * @param bool $redirect Se verdadeiro, redireciona para a página de login
	 * @final
	 */
	public function logout( $redirect = false ) {
		// Remove todo conteúdo da $_SESSION['userdata']
		$_SESSION['userdata'] = array();
		
		// Only to make sure (it isn't really needed)
		unset( $_SESSION['userdata'] );
		
		if ( $redirect === true ) {
			// Redireciona o usuário para a página de login
			$this->goto_login();
		}
	}
	
}