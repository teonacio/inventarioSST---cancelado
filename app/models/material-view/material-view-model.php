<?php 
/**
 * Classe para visualizar dados dos materiais
 *
 * @since 0.1
 */

class MaterialViewModel
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
	public function get_view_form ( $idmaterial = false ) {
	
		// O ID do material que vamos pesquisar
		$s_idmaterial = false;
		
		// Verifica se você enviou algum ID para o método
		if ( ! empty( $idmaterial ) ) {
			$s_idmaterial = (int)$idmaterial;
		}
		
		// Verifica se existe um ID do material
		if ( empty( $s_idmaterial ) ) {
			return;
		}
		
		// Verifica na base de dados
		$query = $this->db->query('SELECT * FROM `material` WHERE `idmaterial` = ?', array( $s_idmaterial ));
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error', 'Material não existe.' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_matdata = $query->fetch();
		
		// Verifica se os dados da consulta estão vazios
		if ( empty( $fetch_matdata ) ) {
			$this->form_msg = feedback( 'form_error', 'Material não existe.' );
			return;
		}
		
		return $fetch_matdata;
		
	}
	
	/**
	 * Obtêm o nome do setor a partir de seu ID
	 *
	 * @param integer $ID ID do setor
	 * @since 0.1
	 * @access public
	 */
	public function get_setor_nome( $ID ) {
		
		if( $ID == 0 ) { // Não existe setor que possua esse material instalado (Nunca foi instalado OU já foi recolhido)
			return 'Inexistente';
		} else {
		
			// Realiza a busca
			$query = $this->db->query('SELECT nomesetor FROM `setor` WHERE `idsetor` = ?', array( $ID ));
		
			// Verifica a consulta
			if ( ! $query ) {
				$this->form_msg = feedback( 'form_error' );
				return;
			}
		
			// Obtêm os dados da consulta
			$fetch_nomesetor = $query->fetch();
		
			return $fetch_nomesetor;
		}
	}
	
	/**
	 * Obtêm o nome do status do material a partir de seus dados no banco
	 *
	 * @param integer $dados Array contendo os dados do banco para o material
	 * @since 0.1
	 * @access public
	 */
	public function get_status_material( $dados ) {
		
		// IF setor instalado == 0 AND empty(data_recolhimento) => Não existem movimentações
		// IF setor_instalado == 0 AND !empty(data_recolhimento) => Material recolhido
		// IF setor_instalado != 0 => Material instalado
		
		if( $dados['setor_instalado'] == 0 ) {
			if( empty($dados['data_recolhimento']) )
				return 'Inexistente';
			else
				return 'Recolhido';
		} else {
			return 'Instalado';
		}
	}
	
	/**
	 * Obtêm o nome da categoria a partir de seu ID
	 *
	 * @param integer $ID ID da categoria
	 * @since 0.1
	 * @access public
	 */
	public function get_tipoeqi_id( $ID ) {
		
		// Realiza a busca
		$query = $this->db->query('SELECT tipo FROM `tipoequipamento` WHERE `idtipoequipamento` = ?', array( $ID ));
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_tipoeqidata = $query->fetch();
		
		return $fetch_tipoeqidata;
	}
	
	 /**
	 * Verifica se o material já foi alterado e, se foi, retorna a data e o usuário que o alterou
	 *
	 * @param integer $data_alteracao A data da última alteração feita
	 * @param integer $id_usuario O ID do usuário que realizou a última alteração
	 * @since 0.1
	 * @access public
	 */
	public function verifica_ultima_alteracao( $data_alteracao, $id_usuario ) {
		
		// Retorno
		$ultima_alteracao = null;
		
		if( empty($data_alteracao) AND $id_usuario == 0 ) { // Material nunca foi alterado
			$ultima_alteracao = 'Inexistente';
		} else {
			
			// Recupera a data
			$data_ultima_alteracao = data_time( 'd/m/Y H:i', $data_alteracao );
			
			// Recupera o nome do usuário que realizou a alteração
			$query = $this->db->query('SELECT nome FROM `usuario` WHERE `idusuario` = ?', array( $id_usuario ));
			
			// Verifica a consulta
			if ( ! $query ) {
				$this->form_msg = feedback( 'form_error' );
				return $ultima_alteracao;
			}
			
			$fetch_usuario_nome = $query->fetch();
			$usuario_ultima_alteracao = $fetch_usuario_nome[0];
			
			$ultima_alteracao = $data_ultima_alteracao.' - '.$usuario_ultima_alteracao;
		}
		
		return $ultima_alteracao;
		
	}
	
	/**
	 * Recupera os detalhes do material e os prepara para visualização
	 *
	 * @param string $string_detalhes Conteúdo da coluna 'detalhes_material' da tabela 'material'
	 * @return string $string_preparada A mesma string escapada
	 * @since 0.1
	 * @access public
	 */
	 public function recupera_detalhes_material( $string_detalhes ){
		 
		 // Retorno
		 $string_preparada = str_replace("'", "", $string_detalhes);
		 $string_preparada = str_replace('\r\n','<br>', $string_preparada);
		 
		 return $string_preparada;
	 }
	 
}