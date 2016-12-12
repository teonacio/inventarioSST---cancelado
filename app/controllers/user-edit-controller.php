<?php
/**
 * UserEditController
 */
class UserEditController extends MainController
{

	/**
	 * $login_required - Se a página precisa de login
	 *
	 * @access public
	 */
	public $login_required = true;

	/**
	 * Carrega a pÃ¡gina "/views/user-edit/index.php"
	 */
    public function index() {
		// Page title
		$this->title = 'Editar dados do Usuário';
		
		// Verifica se o usuÃ¡rio estÃ¡ logado
		if ( ! $this->logged_in ) {
		
			// Se nÃ£o; garante o logout
			$this->logout();
			
			// Redireciona para a pÃ¡gina de login
			$this->goto_login();
			
			// Garante que o script nÃ£o vai passar daqui
			return;
		
		}
	
		// Parametros da função
		$parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : array();
	
		// Carrega o modelo para este view
        $modelo = $this->load_model('user-edit/user-edit-model');
		
		if( $_SESSION['userdata']['idusuario'] != 1 ) {
			
			/** Caso o usuario NAO seja SST: Carrega os arquivos de erro 404 **/
			load_404();
			
		} else {
			
			/** Caso o usuario SEJA SST: Carrega os arquivos do view **/
			require INCPATH_2 . '/header.php';
		
			require INCPATH_2 . '/menu.php';
		
			require VIWPATH . '/user-edit/user-edit-view.php';
		
			require INCPATH_2 . '/footer.php';
			
		}
		
        
		
    }
	
}