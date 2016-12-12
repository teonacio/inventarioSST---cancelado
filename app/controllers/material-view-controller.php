<?php
/**
 * MaterialViewsController
 */
class MaterialViewController extends MainController
{

	/**
	 * $login_required - Se a página precisa de login
	 *
	 * @access public
	 */
	public $login_required = true;

	/**
	 * Carrega a pÃ¡gina "/views/material-view/index.php"
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
        $modelo = $this->load_model('material-view/material-view-model');
		
		// Carrega o modelo para verificação de utilização do material
		$modelo_2 = $this->load_model('material-list/material-list-model');
		
		// Parametros da função
		$parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : array();
		
		// Recupera os dados do material
		$lista = $modelo->get_view_form( chk_array( $parametros, 1 ) );
		
		// Page title
		$this->title = 'Dados Gerais ( '.$lista['tombamento'].' )';
				
		/** Carrega os arquivos do view **/
		
        require INCPATH_2 . '/header.php';
		
        require INCPATH_2 . '/menu.php';
		
        require VIWPATH . '/material-view/material-view-view.php';
		
        require INCPATH_2 . '/footer.php';
		
    }
	
}