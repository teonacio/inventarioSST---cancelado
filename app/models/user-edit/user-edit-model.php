<?php 
/**
 * Classe para editar dados de usuários
 *
 * @since 0.1
 */

class UserEditModel
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
	 * Valida o formulário de envio para atualizar os dados do usuario
	 *
	 * @since 0.1
	 * @access public
	 */
	public function validate_edit_form ( $idusuario ) {
		
		// Verifica se algo foi postado
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty ( $_POST ) ) {
		
			if ( $_POST['setor'] == '------ Selecione um setor abaixo ------' ) {
					
				// Configura a mensagem
				$this->form_msg = feedback( 'form_error', 'Por favor, preencha os campos vazios.' );
					
				// Termina
				return;		
			
			}
		
		} else {
		
			// Termina se nada foi enviado
			return;
			
		}
		
		// Recupera o id do setor do usuario
		$query = $this->db->query('SELECT idsetor FROM setor WHERE nomesetor = ?', array( $_POST['setor'] ));
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		$setor = $query->fetch();
		
		// Atualiza os dados
		$query = $this->db->update('usuario', 'idusuario', $idusuario, array(
			'setor_idsetor' => $setor['idsetor'],
		));
			
		// Verifica se a consulta está OK e configura a mensagem
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
				
			// Termina
			return;
		} else {
			
			$this->form_msg = feedback( 'success', 'Dados do usuario atualizados.' );
				
			// Redireciona
			$login_uri  = HOME_URI . '/user-view/index/view/' . $idusuario;
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
	public function get_edit_form ( $idusuario = false ) {
	
		// O ID de usuário que vamos pesquisar
		$s_idusuario = false;
		
		// Verifica se você enviou algum ID para o método
		if ( ! empty( $idusuario ) ) {
			$s_idusuario = (int)$idusuario;
		}
		
		// Verifica se existe um ID de usuário
		if ( empty( $s_idusuario ) ) {
			return;
		}
		
		// Verifica na base de dados
		$query = $this->db->query('SELECT * FROM `usuario` WHERE `idusuario` = ?', array( $s_idusuario ));
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_userdata = $query->fetch();
		
		// Verifica se os dados da consulta estão vazios
		if ( empty( $fetch_userdata ) ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Configura os dados do formulário
		foreach ( $fetch_userdata as $key => $value ) {
			$this->form_data[$key] = $value;
		}
		
		$this->form_data['senha'] = null;
		
		// Recupera o nome do setor do usuario
		$query = $this->db->query('SELECT nomesetor FROM setor WHERE idsetor = ?', array( $this->form_data['setor_idsetor'] ));
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Obtêm os dados da consulta
		$this->form_data['setor_idsetor'] = $query->fetch();
		
	}
	
	/**
	 * Obtêm os nomes dos setores do banco
	 *
	 * @since 0.1
	 * @access public
	 */
	public function get_list_setor() {
		
		// Realiza a busca
		$query = $this->db->query('SELECT nomesetor FROM setor');
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_setordata = $query->fetchAll();
		
		return $fetch_setordata;
	}
}