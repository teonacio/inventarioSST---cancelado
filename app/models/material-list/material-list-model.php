<?php
/**
 * Classe para visualização de materiais pelo tombamento
 *
 * @since 0.1
 */

class MaterialListModel
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
	 * As mensagens de feedback para o usuário.
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
	 * Obtêm a lista de materiais
	 * 
	 * @since 0.1
	 * @access public
	 */
	public function get_mat_list() {
	
		// Simplesmente seleciona os dados na base de dados 
		$query = $this->db->query('SELECT * FROM `material` ORDER BY idmaterial DESC');
		
		// Verifica se a consulta está OK
		if ( ! $query ) {
			return array();
		}
		// Preenche a tabela com os dados do usuário
		return $query->fetchAll();
	}
	
	 /**
	 * Verifica se determinado material ja foi utilizado em alguma movimentacao
	 * 
	 * @param integer $ID O ID do material
	 * @return boolean true caso o material ja tenha sido utilizado ou false caso contrário
	 * @since 0.1
	 * @access public
	 */
	public function ver_usado_mat( $ID ) {
		
		// Busca na tabela movimentacaoitens
		$query = $this->db->query ('SELECT * FROM `movimentacaoitens` WHERE `material_idmaterial` = ?', array( $ID ) );
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Verifica se a consulta foi nula
		$fetch_verifica = $query->fetchAll();
		if ( !empty($fetch_verifica) ){
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Apaga materiais
	 * 
	 * @since 0.1
	 * @access public
	 */
	public function del_mat ( $parametros = array() ) {
		
		// O ID do material
		$mat_id = null;
		
		// Verifica se existe o parâmetro "del" na URL
		if ( chk_array( $parametros, 0 ) == 'del' ) {
			
			// Verifica se o material ja foi utilizado em alguma movimentacao
			if( $this->ver_usado_mat(chk_array( $parametros, 1 )) ){
				return;
			}

			// Mostra uma mensagem de confirmacao
			echo '<div class="warning"><p>Tem certeza que deseja deletar esse material ( ID = '.chk_array( $parametros, 1 ).' )?</p></div>';
			echo '<p align="center"><a href="' . $_SERVER['REQUEST_URI'] . '/confirma"><img src = "'. HOME_URI . '/style/_images/sim.gif" alt="Confirmar" title="Confirmar" /></a> | 
			<a href="' . HOME_URI . '/material-list"><img src = "'. HOME_URI . ' /style/_images/nao.gif" alt="Voltar" title="Voltar" /></a> </p>';
			
			// Verifica se o valor do parâmetro é um número
			if ( 
				is_numeric( chk_array( $parametros, 1 ) )
				&& chk_array( $parametros, 2 ) == 'confirma' 
			) {
				// Configura o ID do material a ser apagado
				$mat_id = chk_array( $parametros, 1 );
			}
		}
		
		// Verifica se o ID não está vazio
		if ( !empty( $mat_id ) ) {
		
			// O ID precisa ser inteiro
			$mat_id = (int)$mat_id;
			
			// Deleta o material
			$query = $this->db->delete( 'material', 'idmaterial', $mat_id );
			
			// Redireciona para o histórico de materiais
			echo '<meta http-equiv="Refresh" content="0; url=' . HOME_URI . '/material-list/">';
			echo '<script type="text/javascript">window.location.href = "' . HOME_URI . '/material-list/";</script>';
			return;
		}
	}
	
	/**
	 * Obtêm o nome do categoria do material
	 *
	 * @param integer $ID O ID da categoria
	 * @since 0.1
	 * @access public
	 */
	public function get_nome_tipoeqi( $ID ) {
		
		// Realiza a busca
		$query = $this->db->query ('SELECT tipo FROM `tipoequipamento` WHERE `idtipoequipamento` = ?', array( $ID ) );
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_tipoeqidata = $query->fetch();
		
		return $fetch_tipoeqidata;
	}
}