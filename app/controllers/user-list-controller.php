<?php
/**
 * UserListController
 */
class UserListController extends MainController
{

	/**
	 * $login_required - Se a página precisa de login
	 *
	 * @access public
	 */
	public $login_required = true;

	/**
	 * Carrega a página "/views/user-list/user-list-view.php"
	 */
    public function index() {
		// Page title
		$this->title = 'Historico de Usuarios';
		
		// Verifica se o usuário está logado
		if ( ! $this->logged_in ) {
		
			// Se não; garante o logout
			$this->logout();
			
			// Redireciona para a página de login
			$this->goto_login();
			
			// Garante que o script não vai passar daqui
			return;
		
		}
	
		// Parametros da função
		$parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : array();
	
		// Carrega o modelo para este view
        $modelo = $this->load_model('user-list/user-list-model');
		
		// Caso o usuário que queira deletar outros usuários NÃO seja SST
		if( chk_array( $parametros, 0 ) == 'del' AND $_SESSION['userdata']['idusuario'] != 1 ) {
			
			echo '<meta http-equiv="Refresh" content="0; url=' . HOME_URI . '/user-list/">';
			echo '<script type="text/javascript">window.location.href = "' . HOME_URI . '/user-list/";</script>';
			
			return;
		} else {
		
			/** Carrega os arquivos do view **/
		
			require INCPATH_2 . '/header.php';
		
			require INCPATH_2 . '/menu.php';
		
			require VIWPATH . '/user-list/user-list-view.php';
		
			require INCPATH_2 . '/footer.php';
		
		}
		
    }
	
}