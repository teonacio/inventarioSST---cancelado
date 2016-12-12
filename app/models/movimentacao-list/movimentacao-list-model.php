<?php
/**
 * Classe para visualização de movimentações
 *
 * @since 0.1
 */

class MovimentacaoListModel
{
	/**
	 * $form_data
	 *
	 * Os dados do formulário de envio.
	 *
	 * @access public
	 */	
	public $form_data;

	/**
	 * $form_msg
	 *
	 * As mensagens de feedback para o usuÃ¡rio.
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
	 * Construtor
	 * 
	 * Carrega  o DB.
	 *
	 * @since 0.1
	 * @access public
	 */
	public function __construct( $db = false ) {
		$this->db = $db;
	}
	
	/**
	 * Obtêm a lista de movimentações
	 * 
	 * @since 0.1
	 * @access public
	 */
	public function get_mov_list() {
	
		// Simplesmente seleciona os dados na base de dados 
		$query = $this->db->query('SELECT * FROM `movimentacaogeral` ORDER BY idmovimentacaogeral DESC');
		
		// Verifica se a consulta está OK
		if ( ! $query ) {
			return array();
		}
		// Preenche a tabela com os dados do usuário
		return $query->fetchAll();
	}
	
	/**
	 * Obtêm o nome do usuário
	 *
	 * @param integer $ID O ID do usuário
	 * @since 0.1
	 * @access public
	 */
	public function get_nome_usu( $ID ) {
		
		// Realiza a busca
		$query = $this->db->query ('SELECT nome FROM `usuario` WHERE `idusuario` = ?', array( $ID ) );
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_tipousu = $query->fetch();
		
		return $fetch_tipousu;
	}
	
}