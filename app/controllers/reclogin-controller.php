<?php
/**
 * RecloginController
 */
class RecloginController extends MainController
{

	/**
	 * $login_required - Se a página precisa de login
	 *
	 * @access public
	 */
	public $login_required = false;

	/**
	 * Carrega a pÃ¡gina "/views/reclogin/index.php"
	 */
    public function index() {
		// Page title
		$this->title = 'Recuperação de login';
		
		// Verifica se o usuário NÃO está logado
		// Pressupõe que o usuário que não se lembre do login não consiga entrar no sistema.
		if ( $this->logged_in ) {
		
			// Se sim, garante o logout
			$this->logout();
			
			// Redireciona para a página de login
			$this->goto_login();
			
			// Garante que o script não vai passar daqui
			return;
		
		}
	
		// Parametros da função
		$parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : array();
	
		// Carrega o modelo para este view
        $modelo = $this->load_model('reclogin/reclogin-model');
				
		/** Carrega os arquivos do view **/
		
        require INCPATH_2 . '/header.php';
		
        require INCPATH_2 . '/menu.php';
		
        require VIWPATH . '/reclogin/reclogin-view.php';
		
        require INCPATH_2 . '/footer.php';
		
    }
	
}