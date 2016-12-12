<?php 
/**
 * Classe para editar dados dos setores
 *
 * @since 0.1
 */

class SetorEditModel
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
	 * Valida o formulário de envio para atualizar os dados do setor
	 *
	 * @since 0.1
	 * @access public
	 */
	public function validate_edit_form ( $idsetor ) {
		
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
			
			// Valida o codigo
			if( is_numeric($_POST['codigosetor']) ) {
				
				// Configura a mensagem
				$this->form_msg = feedback( 'form_error', 'Codigo incorreto.' );
					
				// Termina
				return;
				
			}
			
			// Valida o nome do setor
			if( is_numeric($_POST['nomesetor']) ) {
				
				// Configura a mensagem
				$this->form_msg = feedback( 'form_error', 'Nome do setor incorreto.' );
					
				// Termina
				return;
				
			}
		
		} else {
		
			// Termina se nada foi enviado
			return;
			
		}
		
		// Atualiza os dados
		$query = $this->db->update('setor', 'idsetor', $idsetor, array(
			'codigosetor' => chk_array( $this->form_data, 'codigosetor'), 
			'nomesetor' => chk_array( $this->form_data, 'nomesetor'), 
		));
			
		// Verifica se a consulta está OK e configura a mensagem
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
				
			// Termina
			return;
		} else {
			
			$this->form_msg = feedback( 'success', 'Dados do setor atualizados.' );
				
			// Redireciona
			$login_uri  = HOME_URI . '/setor-view/index/view/' . $idsetor;
			echo '<meta http-equiv="Refresh" content="0; url=' . $login_uri . '">';
			echo '<script type="text/javascript">window.location.href = "' . $login_uri . '";</script>';
		}
		
	}
	
	/**
	 * Obtêm os dados do formulário
	 *
	 * @since 0.1
	 * @access public
	 */
	public function get_edit_form ( $idsetor = false ) {
	
		// O ID do setor que vamos pesquisar
		$s_idsetor = false;
		
		// Verifica se você enviou algum ID para o método
		if ( ! empty( $idsetor ) ) {
			$s_idsetor = (int)$idsetor;
		}
		
		// Verifica se existe um ID do setor
		if ( empty( $s_idsetor ) ) {
			return;
		}
		
		// Verifica na base de dados
		$query = $this->db->query('SELECT * FROM `setor` WHERE `idsetor` = ?', array( $s_idsetor ));
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_setordata = $query->fetch();
		
		// Verifica se os dados da consulta estão vazios
		if ( empty( $fetch_setordata ) ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Configura os dados do formulário
		foreach ( $fetch_setordata as $key => $value ) {
			$this->form_data[$key] = $value;
		}
		
	}
}