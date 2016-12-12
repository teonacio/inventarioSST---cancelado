<?php 
/**
 * Classe para visualizar dados de setores
 *
 * @since 0.1
 */

class SetorViewModel
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
	 * Obtêm os dados para view
	 *
	 * @since 0.1
	 * @access public
	 */
	public function get_view_form ( $idsetor = false ) {
	
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
			$this->form_msg = feedback( 'form_error', 'Setor nao existe.' );
			return;
		}
		
		return $fetch_setordata;
		
	}
	
	/**
	 * Obtêm os dados dos materiais do respectivo setor
	 *
	 * @param integer $ID O ID do setor
	 * @since 0.1
	 * @access public
	 */
	public function get_all_setitens( $ID ) {
		
		// Realiza a busca
		$query = $this->db->query ('SELECT * FROM `setoritens` WHERE `setor_idsetor` = ?', array( $ID ) );
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_setdata = $query->fetchAll();
		
		return $fetch_setdata;
	}
	
	/**
	 * Obtêm os dados do respectivo material
	 *
	 * @param integer $ID O ID do material
	 * @since 0.1
	 * @access public
	 */
	public function get_all_mat( $ID ) {
		
		// Realiza a busca
		$query = $this->db->query ('SELECT * FROM `material` WHERE `idmaterial` = ?', array( $ID ) );
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_matdata = $query->fetch();
		
		return $fetch_matdata;
	}
	
	/**
	 * Obtêm o nome do tipo do material a partir do seu ID
	 *
	 * @param integer $ID O ID do tipo do material
	 * @since 0.1
	 * @access public
	 */
	public function get_nome_tipoeqi( $ID ) {
		
		// Realiza a busca
		$query = $this->db->query ('SELECT * FROM `tipoequipamento` WHERE `idtipoequipamento` = ?', array( $ID ) );
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_tipoeqidata = $query->fetch();
		
		return $fetch_tipoeqidata['tipo'];
	}
	
}