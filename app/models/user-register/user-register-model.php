<?php 
/**
 * Classe para registros de usuários
 *
 * @since 0.1
 */

class UserRegisterModel
{

	/**
	 * $form_data - Os dados do formulário de envio.
	 *
	 * @access public
	 */	
	public $form_data;

	/**
	 * $form_msg - Mensagens de feedback para o usuário.
	 *
	 * @access public
	 */	
	public $form_msg;

	/**
	 * $db - PDO
	 *
	 * @access public
	 */
	public $db;

	/**
	 * Construtor - Carrega  o DB.
	 * 
	 * @since 0.1
	 * @access public
	 */
	public function __construct( $db = false ) {
		$this->db = $db;
	}

	/**
	 * Valida o formulário de envio para registrar o novo usuario
	 *
	 * @since 0.1
	 * @access public
	 */
	public function validate_register_form () {
	
		// Configura os dados do formulário
		$this->form_data = array();
		
		// Verifica se algo foi postado
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty ( $_POST ) ) {
		
			// Faz o loop dos dados do post
			foreach ( $_POST as $key => $value ) {
			
				// Configura os dados do post para a propriedade $form_data
				$this->form_data[$key] = $value;
				
				// Caso haja algum campo em branco.
				if ( empty( $value ) ) {
					
					// Configura a mensagem
					$this->form_msg = feedback( 'form_error', 'Por favor, preencha os campos vazios.' );
					
					// Termina
					return;
					
				}
			
			}
			
			// Valida o email
			if ( strpos( $_POST['email'], '@' ) === false ) {
					
				// Configura a mensagem 
				$this->form_msg = feedback( 'form_error', 'Email incorreto.' );
					
				// Termina
				return;
					
			} else {
		
				// Valida o setor
				if ( $_POST['setor'] == '------ Selecione um setor abaixo ------' ) {
					
					// Configura a mensagem
					$this->form_msg = feedback( 'form_error', 'Por favor, selecione um setor.' );
					
					// Termina
					return;
					
				}
			}
		
		} else {
		
			// Termina se nada foi enviado
			return;
			
		}
		
		// Verifica se a propriedade $form_data foi preenchida
		if( empty( $this->form_data ) ) {
			return;
		}
		
		// Verifica se o nome do usuário já existe
		$db_check_nome = $this->db->query (
			'SELECT * FROM `usuario` WHERE `nome` = ?', 
			array( 
				chk_array( $this->form_data, 'nome')		
			) 
		);
		if ( ! $db_check_nome ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		} else {
			
			$fetch_nome = $db_check_nome->fetch();
			
			if ( !empty($fetch_nome) ) {
				$this->form_msg = feedback( 'form_error', 'Nome do usuario ja cadastrado no sistema.' );
				return;
			}
		}
		
		// Verifica se o login do usuario ja existe
		$db_check_nome = $this->db->query (
			'SELECT * FROM `usuario` WHERE `login` = ?', 
			array( 
				chk_array( $this->form_data, 'login')		
			) 
		);
		if ( ! $db_check_nome ) {
			
			$this->form_msg = feedback( 'form_error' );
			return;
		} else {
			
			$fetch_nome = $db_check_nome->fetch();
			
			if ( !empty($fetch_nome) ) {
				$this->form_msg = feedback( 'form_error', 'Login ja cadastrado no sistema.' );
				return;
			}
		}
		
		// Verifica se o email do usuario ja existe 
		$db_check_nome = $this->db->query (
			'SELECT * FROM `usuario` WHERE `email` = ?', 
			array( 
				chk_array( $this->form_data, 'email')		
			) 
		);
		if ( ! $db_check_nome ) {
			
			$this->form_msg = feedback( 'form_error' );
			return;
		} else {
			
			$fetch_nome = $db_check_nome->fetch();
			
			if ( !empty($fetch_nome) ) {
				$this->form_msg = feedback( 'form_error', 'Email ja cadastrado no sistema.' );
				return;
			}
		}
		
		// Recupera o ID do setor escolhido, assumindo que ja houve uma filtragem do valor
		$query_setor = $this->db->query('SELECT idsetor FROM setor WHERE `nomesetor` = ?', array( chk_array( $this->form_data, 'setor')));
		
		// Verifica a consulta
		if ( ! $query_setor ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		$fetch_setorID = $query_setor->fetch();
		
		// Cria o hash das senhas
		$password = md5 ( $this->form_data['senha'] );
		$password_confirm = md5 ( $this->form_data['confirma_senha'] );
		
		// Verifica se as senhas conferem
		if ( $password != $password_confirm ) {
			$this->form_msg = feedback( 'form_error', 'As senhas digitadas nao conferem.' );
			return;
		}
		
		// Realiza a consulta para inserção do novo usuário
		$query = $this->db->insert('usuario', array(
			'nome' => chk_array( $this->form_data, 'nome'), 
			'login' => chk_array( $this->form_data, 'login'), 
			'senha' => $password, 
			'email' => chk_array( $this->form_data, 'email'),
			'setor_idsetor' => $fetch_setorID['idsetor'],
		));
			
		// Verifica se a consulta está OK e configura a mensagem
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
				
			// Termina
			return;
		} else {
			
			$this->form_msg = feedback( 'success', 'Usuario cadastrado com sucesso.' );
			
			// Recupera o ID inserido pela $query
			$last_id = $query;
			
			// Redireciona
			$login_uri  = HOME_URI . '/user-view/index/view/' . $last_id;
			echo '<meta http-equiv="Refresh" content="0; url=' . $login_uri . '">';
			echo '<script type="text/javascript">window.location.href = "' . $login_uri . '";</script>';
		}
	}
	
	/**
	 * Obtém os nomes dos setores do banco
	 *
	 * @since 0.1
	 * @access public
	 */
	public function get_list_setor() {
		
		// Realiza a busca
		$query = $this->db->query('SELECT nomesetor FROM setor');
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'success' );
			return;
		}
		
		// Obtém os dados da consulta
		$fetch_setordata = $query->fetchAll();
		
		return $fetch_setordata;
	}
}