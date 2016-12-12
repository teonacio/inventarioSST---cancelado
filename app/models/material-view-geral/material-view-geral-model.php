<?php 
/**
 * Classe para visualizar dados dos materiais pelo nome
 *
 * @since 0.1
 */

class MaterialViewGeralModel
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
	 * Obtêm os dados para view da tabela 'materiai_geral'
	 *
	 * @since 0.1
	 * @access public
	 */
	 public function get_view_form ( $nomematerial = false ) {
	
		// O nome do material que vamos pesquisar
		$s_nomematerial = false;
		
		// Verifica se você enviou algum nome para o método
		if ( ! empty( $nomematerial ) ) {
			$s_nomematerial = str_replace( '_', ' ', $nomematerial);
		}
		
		// Verifica se existe um ID do material
		if ( empty( $s_nomematerial ) ) {
			return;
		}
		
		// Verifica na base de dados
		$query = $this->db->query('SELECT * FROM `material_geral` WHERE `descricao` = ?', array( $s_nomematerial ));
		
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
	 * Obtêm a categoria do material a partir do seu ID
	 *
	 * @param integer $idcateg O ID da categoria
	 * @since 0.1
	 * @access public
	 */
	 public function get_categ_mat ( $idcateg ) {
		 
		// Realiza a busca
		$query = $this->db->query('SELECT tipo FROM `tipoequipamento` WHERE `idtipoequipamento` = ?', array( $idcateg ));
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_nomecateg = $query->fetch();
		
		return $fetch_nomecateg['tipo'];
		 
	 }
	 
	 /** Obtêm a quantidade dos materiais instalados nos setores a partir do nome do material
	 * @param integer $nomemat O nome do material
	 * @since 0.1
	 * @access public
	 */
	 public function get_total_mat_instal ( $nomemat ) {
		 
		// Array para os ID's envolvendo o material em questão
		$id_array_mat = array();
		
		// Total de instalações
		$total_instal = 0;
		
		// Realiza a busca
		$query = $this->db->query('SELECT idmaterial FROM `material` WHERE `descricao` = ?', array( $nomemat ));
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return $id_array_mat;
		}
		
		// Obtêm os dados da consulta
		$fetch_idmat = $query->fetchAll();
		
		// Filtra apenas os ID's da consulta
		$num = count($fetch_idmat); $cont = 0;
		while ( $num > $cont ) {
			
			$id_array_mat[$cont] = $fetch_idmat[$cont]['idmaterial'];
			
			$cont++;
		}
		
		// Para cada id, consulta na tabela 'setores' a quantidade de instalações relacionadas a ele e acumula
		$num_2 = count($id_array_mat); $cont = 0;
		while( $num > $cont ) {
			
			// ID do material
			$id_mat_row = $id_array_mat[$cont];
			
			// Busca as quantidades
			$query = $this->db->query('SELECT material_idmaterial FROM `setoritens` WHERE `material_idmaterial` = ?', array( $id_mat_row ));
			
			// Verifica a consulta
			if ( ! $query ) {
				$this->form_msg = feedback( 'form_error' );
				$total_instal = 0;
				break;
			}
			
			// Soma
			$total_instal += $query->rowCount();
			
			$cont++;
		}
		
		return $total_instal;
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