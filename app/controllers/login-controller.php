<?php
/**
 * LoginController
 */
class LoginController extends MainController
{

	/**
	 * Carrega a página "/views/login/index.php"
	 */
    public function index() {
		// Título da página
		$this->title = 'Inventario SST - Login';
		
		// Se o usuario estiver logado, redireciona para a pagina selecionada
		if ( $this->logged_in ) {
			
			// Seleciona a URL
			$login_uri  = HOME_URI . '/user-list/';
			
			// A página em que o usuário estava
			$_SESSION['goto_url'] = urlencode( $_SERVER['REQUEST_URI'] );
			
			// Redireciona
			echo '<meta http-equiv="Refresh" content="0; url=' . $login_uri . '">';
			echo '<script type="text/javascript">window.location.href = "' . $login_uri . '";</script>';
			// header('location: ' . $login_uri);
		
		}
		
		// Não apague a linha abaixo
		$modelo = 0;
		
		// Parametros da função
		$parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : array();
		
		/** Carrega os arquivos do view **/
		
        require INCPATH_2 . '/header.php';
		
        require INCPATH_2 . '/menu.php';
		
        require VIWPATH . '/login/login-view.php';
		
        require INCPATH_2 . '/footer.php';
		
    }
	
}