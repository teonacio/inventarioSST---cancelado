<?php
/**
 * home - Controller de exemplo
 */
class HomeController extends MainController
{

	/**
	 * Carrega a página "/views/home/index.php"
	 */
    public function index() {
		// Título da página
		//$this->title = 'Página Inicial';
		
		// Parametros da função
		$parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : array();
		
		/** Redireciona para outras paginas, de forma que o usuario nunca acesse essa **/
		
		if( !$this->logged_in ) { /** Caso o usuario nao esteja logado, redireciona para o login **/
			
			// Configura a URL de login
			$login_uri  = HOME_URI . '/login/';
			
			// A página em que o usuário estava
			$_SESSION['goto_url'] = urlencode( $_SERVER['REQUEST_URI'] );
			
			// Redireciona
			echo '<meta http-equiv="Refresh" content="0; url=' . $login_uri . '">';
			echo '<script type="text/javascript">window.location.href = "' . $login_uri . '";</script>';
			// header('location: ' . $login_uri);
			
		} else { /** Caso o usuario esteja logado, redireciona para a pagina selecionada **/
			
			// Seleciona a URL
			$login_uri  = HOME_URI . '/user-list/';
			
			// A página em que o usuário estava
			$_SESSION['goto_url'] = urlencode( $_SERVER['REQUEST_URI'] );
			
			// Redireciona
			echo '<meta http-equiv="Refresh" content="0; url=' . $login_uri . '">';
			echo '<script type="text/javascript">window.location.href = "' . $login_uri . '";</script>';
			// header('location: ' . $login_uri);
		}
		
    }
	
}