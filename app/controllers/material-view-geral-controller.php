<?php
/**
 * MaterialViewGeralController
 */
class MaterialViewGeralController extends MainController
{

	/**
	 * $login_required - Se a página precisa de login
	 *
	 * @access public
	 */
	public $login_required = true;

	/**
	 * Carrega a pÃ¡gina "/views/material-view-geral/index.php"
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
        $modelo = $this->load_model('material-view-geral/material-view-geral-model');
		
		// Modelo adicional
		$modelo_2 = $this->load_model('material-view/material-view-model');
		
		// Parametros da função
		$parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : array();
		
		// Retira os underline do nome do material
		$material_nome = str_replace( '_', ' ', chk_array( $parametros, 1 ));
		
		// Page title
		$this->title = 'Dados Gerais ( '.$material_nome.' )';
				
		/** Carrega os arquivos do view **/
		
        require INCPATH_2 . '/header.php';
		
        require INCPATH_2 . '/menu.php';
		
        require VIWPATH . '/material-view-geral/material-view-geral-view.php';
		
        require INCPATH_2 . '/footer.php';
		
    }
	
}