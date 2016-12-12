<?php 
/**
 * Classe para visualizar dados das movimentações
 *
 * @since 0.1
 */

class MovimentacaoViewModel
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
	 * Obtêm os dados para view da movimentação (Dados Gerais)
	 *
	 * @since 0.1
	 * @access public
	 */
	public function get_view_form_movGeral ( $idmov = false ) {
	
		// O ID de usuário que vamos pesquisar
		$s_idmov = false;
		
		// Verifica se você enviou algum ID para o método
		if ( ! empty( $idmov ) ) {
			$s_idmov = (int)$idmov;
		}
		
		// Verifica se existe um ID da movimentação
		if ( empty( $s_idmov ) ) {
			return;
		}
		
		// Verifica na base de dados
		$query = $this->db->query('SELECT * FROM `movimentacaogeral` WHERE `idmovimentacaogeral` = ?', array( $s_idmov ));
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error', 'Movimentacao nao existe.' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_movdata = $query->fetch();
		
		// Verifica se os dados da consulta estão vazios
		if ( empty( $fetch_movdata ) ) {
			$this->form_msg = feedback( 'form_error', 'Movimentacao não existe.' );
			return;
		}
		
		return $fetch_movdata;
		
	}
	
	/**
	 * Obtêm os dados para view da movimentacao (Dados dos Materiais)
	 *
	 * @since 0.1
	 * @access public
	 */
	public function get_view_form_movItens ( $idmov = false ) {
	
		// O ID de usuário que vamos pesquisar
		$s_idmov = false;
		
		// Verifica se você enviou algum ID para o método
		if ( ! empty( $idmov ) ) {
			$s_idmov = (int)$idmov;
		}
		
		// Verifica se existe um ID da movimentação
		if ( empty( $s_idmov ) ) {
			return;
		}
		
		// Verifica na base de dados
		$query = $this->db->query('SELECT * FROM `movimentacaoitens` WHERE `movimentacaoGeral_idmovimentacaoGeral` = ?', array( $s_idmov ));
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error', 'Movimentacao nao existe.' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_movdata = $query->fetchAll();
		
		// Verifica se os dados da consulta estão vazios
		if ( empty( $fetch_movdata ) ) {
			$this->form_msg = feedback( 'form_error', 'Movimentacao não existe.' );
			return;
		}
		
		return $fetch_movdata;
		
	}
	
	/**
	 * Obtêm o nome do usuário a partir de seu ID
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
		
		return $fetch_tipousu['nome'];
	}
	
	/**
	 * Obtêm o nome do material a partir de seu ID
	 *
	 * @param integer $ID O ID do material
	 * @since 0.1
	 * @access public
	 */
	public function get_nome_material( $ID ) {
		
		// Realiza a busca
		$query = $this->db->query ('SELECT descricao FROM `material` WHERE `idmaterial` = ?', array( $ID ) );
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_mat = $query->fetch();
		
		return $fetch_mat['descricao'];
	}
	
	/**
	 * Obtêm o nome do setor a partir de seu ID
	 *
	 * @param integer $ID O ID do usuário
	 * @since 0.1
	 * @access public
	 */
	public function get_nome_setor( $ID ) {
		
		// Caso o setor antigo seja nulo (EX: Instalacoes cujo setor antigo nao tenha sido informado.)
		if($ID == null){
			return 'INEXISTENTE';
		}
		
		// Realiza a busca
		$query = $this->db->query ('SELECT nomesetor FROM `setor` WHERE `idsetor` = ?', array( $ID ) );
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_setor = $query->fetch();
		
		return $fetch_setor['nomesetor'];
	}
	
	/**
	 * Obtêm o nome do status a partir de seu ID
	 *
	 * @param integer $ID O ID do status
	 * @since 0.1
	 * @access public
	 */
	public function get_nome_status( $ID ) {
		
		// Realiza a busca
		$query = $this->db->query ('SELECT status FROM `status` WHERE `idstatus` = ?', array( $ID ) );
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_status = $query->fetch();
		
		return $fetch_status['status'];
	}
}