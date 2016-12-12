<?php
/**
 * Classe para visualização de categorias
 *
 * @since 0.1
 */

class TipoequipamentoListModel
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
	 * Obtêm a lista de usuários
	 * 
	 * @since 0.1
	 * @access public
	 */
	public function get_tipoeqi_list() {
	
		// Simplesmente seleciona os dados na base de dados 
		$query = $this->db->query('SELECT * FROM `tipoequipamento` ORDER BY idtipoequipamento ASC');
		
		// Verifica se a consulta está OK
		if ( ! $query ) {
			return array();
		}
		// Preenche a tabela com os dados do usuário
		return $query->fetchAll();
	}
	
	 /**
	 * Verifica se determinada categoria ja foi utilizada em algum material
	 * 
	 * @param integer $ID O ID da categoria
	 * @return boolean true caso  categoria ja tenha sido utilizada ou false caso contrário
	 * @since 0.1
	 * @access public
	 */
	public function ver_usado_tipo( $ID ) {
		
		// Busca na tabela movimentacaoitens
		$query = $this->db->query ('SELECT * FROM `material` WHERE `tipoequipamento_idtipoequipamento` = ?', array( $ID ) );
		
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
	 * Apaga categorias
	 * 
	 * @since 0.1
	 * @access public
	 */
	public function del_tipoeqi ( $parametros = array() ) {
		
		// O ID da categoria
		$tipo_id = null;
		
		// Verifica se existe o parãmetro "del" na URL
		if ( chk_array( $parametros, 0 ) == 'del' ) {
			
			// Verifica se a categoria ja foi utilizada em algum material
			if( $this->ver_usado_tipo(chk_array( $parametros, 1 )) ){
				return;
			}

			// Mostra uma mensagem de confirmacao
			echo '<div class="warning"><p>Tem certeza que deseja deletar essa categoria ( ID = '.chk_array( $parametros, 1 ).' )?</p></div>';
			echo '<p align="center"><a href="' . $_SERVER['REQUEST_URI'] . '/confirma"><img src = "'. HOME_URI . '/style/_images/sim.gif" alt="Confirmar" title="Confirmar" /></a> | 
			<a href="' . HOME_URI . '/tipoequipamento-list"><img src = "'. HOME_URI . ' /style/_images/nao.gif" alt="Voltar" title="Voltar" /></a> </p>';
			
			// Verifica se o valor do parãmetro é um número
			if ( 
				is_numeric( chk_array( $parametros, 1 ) )
				&& chk_array( $parametros, 2 ) == 'confirma' 
			) {
				// Configura o ID do usuário a ser apagado
				$tipo_id = chk_array( $parametros, 1 );
			}
		}
		
		// Verifica se o ID não está vazio
		if ( !empty( $tipo_id ) ) {
		
			// O ID precisa ser inteiro
			$tipo_id = (int)$tipo_id;
			
			// Deleta o usuário
			$query = $this->db->delete('tipoequipamento', 'idtipoequipamento', $tipo_id);
			
			// Redireciona para o histórico de usuários
			echo '<meta http-equiv="Refresh" content="0; url=' . HOME_URI . '/tipoequipamento-list/">';
			echo '<script type="text/javascript">window.location.href = "' . HOME_URI . '/tipoequipamento-list/";</script>';
			return;
		}
	}
	
}