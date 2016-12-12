<?php
/**
 * UserViewController
 */
class UserViewController extends MainController
{

	/**
	 * $login_required - Se a página precisa de login
	 *
	 * @access public
	 */
	public $login_required = true;

	/**
	 * Carrega a pÃ¡gina "/views/user-view/index.php"
	 */
    public function index() {
		
		// Verifica se o usuÃ¡rio estÃ¡ logado
		if ( ! $this->logged_in ) {
		
			// Se nÃ£o; garante o logout
			$this->logout();
			
			// Redireciona para a pÃ¡gina de login
			$this->goto_login();
			
			// Garante que o script nÃ£o vai passar daqui
			return;
		
		}
		
		// Carrega o modelo para este view
        $modelo = $this->load_model('user-view/user-view-model');
		
		// Parametros da função
		$parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : array();
		
		// Recupera os dados do usuario
		$lista = $modelo->get_view_form( chk_array( $parametros, 1 ) );
		
		// Page title
		$this->title = 'Dados Gerais ( '.$lista['login'].' )';
				
		/** Carrega os arquivos do view **/
		
        require INCPATH_2 . '/header.php';
		
        require INCPATH_2 . '/menu.php';
		
        require VIWPATH . '/user-view/user-view-view.php';
		
        require INCPATH_2 . '/footer.php';
		
    }
	
}