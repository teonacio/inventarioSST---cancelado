<?php
/**
 * MovimentacaoViewController
 */
class MovimentacaoViewController extends MainController
{

	/**
	 * $login_required - Se a página precisa de login
	 *
	 * @access public
	 */
	public $login_required = true;

	/**
	 * Carrega a pÃ¡gina "/views/movimentacao-view/index.php"
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
        $modelo = $this->load_model('movimentacao-view/movimentacao-view-model');
		
		// Parametros da função
		$parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : array();
		
		// Recupera os dados gerais da movimentacao
		$lista_Geral = $modelo->get_view_form_movGeral( chk_array( $parametros, 1 ) );
		
		// Page title
		$this->title = 'Dados Gerais ( ID = '.$lista_Geral['idmovimentacaoGeral'].' )';
				
		/** Carrega os arquivos do view **/
		
        require INCPATH_2 . '/header.php';
		
        require INCPATH_2 . '/menu.php';
		
        require VIWPATH . '/movimentacao-view/movimentacao-view-view.php';
		
        require INCPATH_2 . '/footer.php';
		
    }
	
}