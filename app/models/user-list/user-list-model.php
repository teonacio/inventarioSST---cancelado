<?php
/**
 * Classe para visualização de usuários
 *
 * @since 0.1
 */

class UserListModel
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
	public function get_user_list() {
	
		// Simplesmente seleciona os dados na base de dados 
		$query = $this->db->query('SELECT * FROM `usuario` ORDER BY idusuario DESC');
		
		// Verifica se a consulta está OK
		if ( ! $query ) {
			return array();
		}
		// Preenche a tabela com os dados do usuário
		return $query->fetchAll();
	}
	
	/**
	 * Apaga usuários
	 * 
	 * @since 0.1
	 * @access public
	 */
	public function del_user ( $parametros = array() ) {
		
		// O ID do usuário
		$user_id = null;
		
		// Verifica se o usuario que esta tentando executar a acao delete eh o usuario SST
		if( $_SESSION['userdata']['idusuario'] != 1 ){
			return;
		}
		
		// Verifica se existe o parãmetro "del" na URL
		if ( chk_array( $parametros, 0 ) == 'del' ) {

			// Mostra uma mensagem de confirmacao
			echo '
				<div class="warning_user_delete">
					<p>
						<img src = "'.HOME_URI.' . /style/_images/warning.png" />
						Tem certeza que deseja deletar esse usuario ( ID = '.chk_array( $parametros, 1 ).' )?<br>
						<b>NOTA: Ao deletar um usuário, TODAS as movimentações criadas por ele são automaticamente DELETADAS.</b>
					</p>
				</div>
			';
			
			echo '<p align="center"><a href="' . $_SERVER['REQUEST_URI'] . '/confirma"><img src = "'. HOME_URI . '/style/_images/sim.gif" alt="Confirmar" title="Confirmar" /></a> | 
			<a href="' . HOME_URI . '/user-list"><img src = "'. HOME_URI . ' /style/_images/nao.gif" alt="Voltar" title="Voltar" /></a> </p>';
			
			// Verifica se o valor do parãmetro é um número
			if ( 
				is_numeric( chk_array( $parametros, 1 ) )
				&& chk_array( $parametros, 2 ) == 'confirma' 
			) {
				// Configura o ID do usuário a ser apagado
				$user_id = chk_array( $parametros, 1 );
			}
		}
		
		// Verifica se o usuario SST esta tentando deletar a si mesmo
		if( $user_id == 1 ){
			return;
		}
		
		// Verifica se o ID não está vazio
		if ( !empty( $user_id ) ) {
		
			// O ID precisa ser inteiro
			$user_id = (int)$user_id;
			
			// Deleta todas as movimentacoes ligadas ao usuario
			$query = $this->db->query('SELECT * FROM `movimentacaogeral` WHERE `usuario_idusuario` = ?', array( $user_id ));
			$fetch_movdata = $query->fetchAll();
			if( $fetch_movdata ){
				$num = count($fetch_movdata);
				$cont = 0;
				while($num > $cont){
					$id_mov = $fetch_movdata[$cont]['idmovimentacaoGeral']; // ID da movimentacao no passo atual do loop
					
					// Deleta todos os materiais da movimentacao (tabela movimentacaoItens)
					$query = $this->db->delete('movimentacaoitens', 'movimentacaoGeral_idmovimentacaoGeral', $id_mov);
					
					// Deleta a movimentacao da tabela movimentacaoGeral
					$query = $this->db->delete('movimentacaogeral', 'idmovimentacaoGeral', $id_mov);
					
					$cont++;
				}
			}
			
			// Deleta o usuário
			$query = $this->db->delete('usuario', 'idusuario', $user_id);
			
			// Redireciona para o histórico de usuários
			echo '<meta http-equiv="Refresh" content="0; url=' . HOME_URI . '/user-list/">';
			echo '<script type="text/javascript">window.location.href = "' . HOME_URI . '/user-list/";</script>';
			return;
		}
	}
	
	/**
	 * Obtêm o nome do setor do usuario
	 *
	 * @param integer $ID O ID do setor
	 * @since 0.1
	 * @access public
	 */
	public function get_nome_setor( $ID ) {
		
		// Realiza a busca
		$query = $this->db->query ('SELECT nomesetor FROM `setor` WHERE `idsetor` = ?', array( $ID ) );
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_setordata = $query->fetch();
		
		return $fetch_setordata;
	}
}