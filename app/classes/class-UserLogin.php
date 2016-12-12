<?php
/**
 * UserLogin - Manipula os dados de usuários
 *
 */
class UserLogin
{
	/**
	 * Verifica se o usuário está logado - Assume TRUE caso esteja.
	 *
	 * @public
	 * @access public
	 * @var bol
	 */
	public $logged_in;
	
	/**
	 * Dados do usuário
	 *
	 * @public
	 * @access public
	 * @var array
	 */
	public $userdata;
	
	/**
	 * Mensagem de erro para o formulário de login
	 *
	 * @public
	 * @access public
	 * @var string
	 */
	public $login_error;
	
	/**
	 * Verifica o login,
	 * configura as propriedades $logged_in e $login_error
	 * e configura o array do usuário em $userdata.
	 */
	public function check_userlogin () {
	
		// Verifica se existe uma sessão com a chave userdata
		// Tem que ser um array e não pode ser HTTP POST
		if ( isset( $_SESSION['userdata'] )
			 && ! empty( $_SESSION['userdata'] )
			 && is_array( $_SESSION['userdata'] ) 
			 && ! isset( $_POST['userdata'] )
			) { 
			// Configura os dados do usuário
			$userdata = $_SESSION['userdata'];
			
			// Garante que não é HTTP POST
			$userdata['post'] = false;
			
		}
		
		// Verifica se existe um $_POST com a chave userdata
		// Tem que ser um array
		if ( isset( $_POST['userdata'] )
			 && ! empty( $_POST['userdata'] )
			 && is_array( $_POST['userdata'] ) 
			) {
			// Configura os dados do usuário
			$userdata = $_POST['userdata'];
			
			// Garante que é HTTP POST
			$userdata['post'] = true;
			
		}

		// Verifica se existe algum dado de usuário para conferir
		if ( ! isset( $userdata ) || ! is_array( $userdata ) ) {
		
			// Desconfigura qualquer sessão que possa existir sobre o usuário
			$this->logout();
		
			return;
		}

		// Passa os dados do post para uma variável
		if ( $userdata['post'] === true ) {
			$post = true;
		} else {
			$post = false;
		}
		
		// Remove a chave post do array userdata
		unset( $userdata['post'] );
		
		// Verifica se existe algo a conferir
		if ( empty( $userdata ) ) {
			$this->logged_in = false;
			$this->login_error = null;
		
			// Desconfigura qualquer sessão que possa existir sobre o usuário
			$this->logout(true);
		
			return;
		}
		
		// Extrai variáveis dos dados do usuário
		extract( $userdata );
		
		// Verifica se existe um usuário e senha
		if ( ! isset( $login ) || ! isset( $senha ) ) {
			$this->logged_in = false;
			$this->login_error = null;
		
			// Desconfigura qualquer sessão que possa existir sobre o usuário
			$this->logout(true);
		
			return;
		}
		
		// Verifica se o usuário existe na base de dados
		$query = $this->db->query( 
			'SELECT * FROM usuario WHERE login = ? LIMIT 1', 
			array( $login ) 
		);
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->logged_in = false;
			$this->login_error = feedback( 'form_error', 'Erro no sistema. Por favor contate o administrador.' );
		
			// Desconfigura qualquer sessão que possa existir sobre o usuário
			$this->logout(true);
		
			return;
		}
		
		// Obtém os dados da base de usuário 
		$fetch = $query->fetch(PDO::FETCH_ASSOC);
		
		// Obtém o ID do usuário
		$idusuario = (int) $fetch['idusuario'];
		
		// Verifica se o ID existe
		if ( empty( $idusuario ) ){
			$this->logged_in = false;
			$this->login_error = feedback( 'form_error', 'Usuario inexistente.' );
		
			// Desconfigura qualquer sessão que possa existir sobre o usuário
			$this->logout();
		
			return;
		}
		
		// Confere se a senha enviada pelo usuário bate com o hash do BD
		$user_hash_password = md5($senha);
		if ( $user_hash_password == $fetch['senha'] ) {
			
			// Envia os dados de usuário para a sessão
			$_SESSION['userdata'] = $fetch;
				
			// Atualiza a senha
			$_SESSION['userdata']['senha'] = $senha;
			
			// Cria um array para armazenar dados de materiais em movimentacoes ( Key-> IDmaterial / Value -> array() com dados )
			$_SESSION['userdata']['movimentacao'] = array();

			// Configura a propriedade dizendo que o usuário está logado
			$this->logged_in = true;
			
			// Configura os dados do usuário para $this->userdata
			$this->userdata = $_SESSION['userdata'];
			
		} else {
			// O usuário não está logado 
			$this->logged_in = false;
			
			// A senha não bateu
			$this->login_error = feedback( 'form_error', 'Senha incorreta.' );
		
			// Remove tudo
			$this->logout();
		
			return;
		}
	}
	
	/**
	 * Vai para a página de login
	 */
	public function goto_login() {
		// Verifica se a URL da HOME está configurada
		if ( defined( 'HOME_URI' ) ) {
			// Configura a URL de login
			$login_uri  = HOME_URI . '/login/';
			
			// A página em que o usuário estava
			$_SESSION['goto_url'] = urlencode( $_SERVER['REQUEST_URI'] );
			
			// Redireciona
			echo '<meta http-equiv="Refresh" content="0; url=' . $login_uri . '">';
			echo '<script type="text/javascript">window.location.href = "' . $login_uri . '";</script>';
			// header('location: ' . $login_uri);
		}
		
		return;
	}
	
	/**
	 * Logout
	 *
	 * Desconfigura tudo do usuario.
	 *
	 * @param bool $redirect Se verdadeiro, redireciona para a página de login
	 * @final
	 */
	public function logout( $redirect = false ) {
		// Remove todo conteúdo da $_SESSION['userdata']
		$_SESSION['userdata'] = array();
		
		// Only to make sure (it isn't really needed)
		unset( $_SESSION['userdata'] );
		
		if ( $redirect === true ) {
			// Redireciona o usuário para a página de login
			$this->goto_login();
		}
	}
	
	/**
	 * Envia para uma página qualquer
	 *
	 * @final
	 */
	final public function goto_page( $page_uri = null ) {
		if ( isset( $_GET['url'] ) && ! empty( $_GET['url'] ) && ! $page_uri ) {
			// Configura a URL
			$page_uri  = urldecode( $_GET['url'] );
		}
		
		if ( $page_uri ) { 
			// Redireciona
			echo '<meta http-equiv="Refresh" content="0; url=' . $page_uri . '">';
			echo '<script type="text/javascript">window.location.href = "' . $page_uri . '";</script>';
			//header('location: ' . $page_uri);
			return;
		}
	}
}