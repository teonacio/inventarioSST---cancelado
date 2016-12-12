<?php 
/**
 * Classe para registros de setores
 *
 * @since 0.1
 */

class SetorRegisterModel
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
			if ( is_numeric(chk_array( $this->form_data, 'nomesetor')) ) {
					
				// Configura a mensagem
				$this->form_msg = feedback( 'form_error', 'Nome de setor incorreto.' );
					
				// Termina
				return;
					
			}
			// Valida o codigo
			if ( is_numeric(chk_array( $this->form_data, 'codigosetor')) ) {
					
				// Configura a mensagem
				$this->form_msg = feedback( 'form_error', 'Codigo incorreto.' );
					
				// Termina
				return;
					
			}
		
		} else {
		
			// Termina se nada foi enviado
			return;
			
		}
		
		// Verifica se a propriedade $form_data foi preenchida
		if( empty( $this->form_data ) ) {
			return;
		}
		
		// Verifica se o setor já existe
		$db_check_nome = $this->db->query (
			'SELECT * FROM `setor` WHERE `nomesetor` = ?', 
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
				$this->form_msg = feedback( 'form_error', 'Setor ja cadastrado no sistema.' );
				return;
			}
		}
		
		// Verifica se o codigo já existe
		$db_check_nome = $this->db->query (
			'SELECT * FROM `setor` WHERE `codigosetor` = ?', 
			array( 
				chk_array( $this->form_data, 'codigosetor')		
			) 
		);
		if ( ! $db_check_nome ) {
			
			$this->form_msg = feedback( 'form_error' );
			return;
		} else {
			
			$fetch_nome = $db_check_nome->fetch();
			
			if ( !empty($fetch_nome) ) {
				$this->form_msg = feedback( 'form_error', 'Setor ja cadastrado no sistema.' );
				return;
			}
		}
		
		// Realiza a consulta para inserção do novo setor
		$query = $this->db->insert('setor', array(
			'codigosetor' => chk_array( $this->form_data, 'codigosetor'), 
			'nomesetor' => chk_array( $this->form_data, 'nomesetor'), 
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
			$login_uri  = HOME_URI . '/setor-view/index/view/' . $last_id;
			echo '<meta http-equiv="Refresh" content="0; url=' . $login_uri . '">';
			echo '<script type="text/javascript">window.location.href = "' . $login_uri . '";</script>';
		}
	}
}