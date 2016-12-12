<?php
/**
 * Classe para visualização de setores
 *
 * @since 0.1
 */

class SetorListModel
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
	 * Obtêm a lista de setores
	 * 
	 * @since 0.1
	 * @access public
	 */
	public function get_setor_list() {
	
		// Simplesmente seleciona os dados na base de dados 
		$query = $this->db->query('SELECT * FROM `setor` ORDER BY idsetor ASC');
		
		// Verifica se a consulta está OK
		if ( ! $query ) {
			return array();
		}
		// Preenche a tabela com os dados do usuário
		return $query->fetchAll();
	}
	
	/**
	 * Verifica se determinado setor ja foi utilizado em alguma movimentacao
	 * 
	 * @param integer $ID O ID do setor
	 * @return boolean true caso o setor ja tenha sido utilizado ou false caso contrário
	 * @since 0.1
	 * @access public
	 */
	public function ver_usado_setor( $ID ) {
		
		// Busca na tabela movimentacaoitens em relação a coluna setor_antigo
		$query = $this->db->query ('SELECT * FROM `movimentacaoitens` WHERE `setor_antigo` = ?', array( $ID ) );
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		$fetch_verifica = $query->fetchAll();
		
		// Busca na tabela movimentacaoitens em relação a coluna setor_novo
		$query_2 = $this->db->query ('SELECT * FROM `movimentacaoitens` WHERE `setor_novo` = ?', array( $ID ) );
		
		// Verifica a consulta
		if ( ! $query_2 ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		$fetch_verifica_2 = $query_2->fetchAll();
		
		// Verifica se ambas as consultas foram nulas
		if ( !empty($fetch_verifica) || !empty($fetch_verifica_2) ){
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Apaga setores
	 * 
	 * @since 0.1
	 * @access public
	 */
	public function del_setor ( $parametros = array() ) {
		
		// O ID do setor
		$setor_id = null;
		
		// Verifica se existe o parãmetro "del" na URL
		if ( chk_array( $parametros, 0 ) == 'del' ) {
			
			// Verifica se o setor ja foi utilizado em alguma movimentacao
			if( $this->ver_usado_setor(chk_array( $parametros, 1 )) ){
				return;
			}

			// Mostra uma mensagem de confirmacao
			echo '<div class="warning"><p>Tem certeza que deseja deletar esse setor ( ID = '.chk_array( $parametros, 1 ).' )?</p></div>';
			echo '<p align="center"><a href="' . $_SERVER['REQUEST_URI'] . '/confirma"><img src = "'. HOME_URI . '/style/_images/sim.gif" alt="Confirmar" title="Confirmar" /></a> | 
			<a href="' . HOME_URI . '/setor-list"><img src = "'. HOME_URI . ' /style/_images/nao.gif" alt="Voltar" title="Voltar" /></a> </p>';
			
			// Verifica se o valor do parãmetro é um número
			if ( 
				is_numeric( chk_array( $parametros, 1 ) )
				&& chk_array( $parametros, 2 ) == 'confirma' 
			) {
				// Configura o ID do setor a ser apagado
				$setor_id = chk_array( $parametros, 1 );
			}
		}
		
		// Verifica se o ID não está vazio
		if ( !empty( $setor_id ) ) {
		
			// O ID precisa ser inteiro
			$setor_id = (int)$setor_id;
			
			// Deleta o usuário
			$query = $this->db->delete('setor', 'idsetor', $setor_id);
			
			// Redireciona para o histórico de usuários
			echo '<meta http-equiv="Refresh" content="0; url=' . HOME_URI . '/setor-list/">';
			echo '<script type="text/javascript">window.location.href = "' . HOME_URI . '/setor-list/";</script>';
			return;
		}
	}
}