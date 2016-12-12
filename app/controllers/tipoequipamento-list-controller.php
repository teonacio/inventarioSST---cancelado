<?php
/**
 * TipoequipamentoListController
 */
class TipoequipamentoListController extends MainController
{

	/**
	 * $login_required - Se a página precisa de login
	 *
	 * @access public
	 */
	public $login_required = true;
	
	/**
	 * Paginação de tabelas
	 *
	 * @acess public
	**/
	public $paginacao;

	/**
	 * Carrega a página "/views/tipoequipamento-list/tipoequipamento-list-view.php"
	 */
    public function index() {
		// Page title
		$this->title = 'Historico de Categorias';
		
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
        $modelo = $this->load_model('tipoequipamento-list/tipoequipamento-list-model');
		
		/** Carrega os arquivos do view **/
		
		require INCPATH_2 . '/header.php';
		
		require INCPATH_2 . '/menu.php';
		
		require VIWPATH . '/tipoequipamento-list/tipoequipamento-list-view.php';
		
		require INCPATH_2 . '/footer.php';
		
    }
	
}