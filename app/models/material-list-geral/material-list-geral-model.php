<?php
/**
 * Classe para visualização de materiais pelo nome
 *
 * @since 0.1
 */

class MaterialListGeralModel
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
	 * Obtêm a lista de materiais pelo nome
	 * 
	 * @since 0.1
	 * @access public
	 */
	public function get_mat_nomes_list() {
		
		// Array de retorno
		$material_nomes = array();
		
		// Seleciona os dados na base de dados 
		$query = $this->db->query('SELECT * FROM `material_geral` ORDER BY idmaterialgeral ASC');
		
		// Verifica se a consulta está OK
		if ( ! $query ) {
			return $material_nomes;
		}
		
		$dados_material = $query->fetchAll();
		
		// Verifica se a consulta está nula
		if( empty($dados_material) ) {
			return $material_nomes;
		}
		
		// Salva os nomes dos materiais
		$num = count($dados_material);
		$cont = 0;
		while ($num > $cont) {
			
			// Nome do material
			$material_nomes[$cont] = $dados_material[$cont]['descricao'];
			
			$cont++;
		}

		// Reseta o array
		$new_material_nomes = array(); $material_nomes_id = array_keys($material_nomes);
		$num = count($material_nomes); $cont = 0;
		while ( $num > $cont ) {
			
			$id = $material_nomes_id[$cont];
			
			$new_material_nomes[$cont] = $material_nomes[$id];
			
			$cont++;
		}
		
		return $new_material_nomes;
	}
	
	 /**
	 * Obtêm as categorias dos materiais listados
	 *
	 * @param integer $nomes_list Array contendo os nomes a serem pesquisados (Assuma que o array nunca é nulo)
	 * @since 0.1
	 * @access public
	 */
	public function get_mat_categorias_list( $nomes_list ) {
		
		// Array de retorno
		$material_categorias = array();
		
		$num = count($nomes_list);
		$cont = 0;
		while( $num > $cont ) {
			
			// Nome do material
			$nome_material = $nomes_list[$cont];
			
			// Considerando que todos os materiais com o mesmo nome possuem a mesma categoria, consulta apenas o primeiro valor da tabela
			$query = $this->db->query('SELECT tipoequipamento_idtipoequipamento FROM material WHERE descricao = ?', array( $nome_material ) );
			
			// Verifica se a consulta está OK
			if ( ! $query ) {
				$material_categorias = array();
				break;
			}
			
			// Recupera o ID da categoria
			$dados_material_fetch = $query->fetch(); $dados_material = $dados_material_fetch['tipoequipamento_idtipoequipamento'];
			
			// Recupera o nome da categoria
			$query_categoria = $query = $this->db->query('SELECT tipo FROM `tipoequipamento` WHERE idtipoequipamento = ?', array( $dados_material ) );
			
			// Verifica se a consulta está OK
			if ( ! $query ) {
				$material_categorias = array();
				break;
			}
			
			// Salva o nome da categoria
			$material_categorias_fetch = $query_categoria->fetch();
			$material_categorias[$cont] = $material_categorias_fetch['tipo'];
			$cont++;
		}
		
		return $material_categorias;
		
	}
	
	 /**
	 * Apaga todos os valores da 'material_geral' que não tenham correspondência na 'material'
	 *
	 * @since 0.1
	 * @access public
	 */
	 public function apaga_sem_correspondencia_tabela() {
		 
		 // Recupera os dados da tabela material_geral
		 $query = $this->db->query('SELECT descricao FROM material_geral');
			
		 // Verifica se a consulta está OK
		 if ( ! $query ) {
			 $material_categorias = array();
			 break;
		 }
			
		 // Recupera o ID da categoria
		 $fetch_material_geral = $query->fetchAll();
		 
		 $num = count($fetch_material_geral); $cont = 0;
		 while( $num > $cont ) {
			 
			 // Nome do material
			 $nome_mat = $fetch_material_geral[$cont]['descricao'];
			 
			 // Verifica se tem correspondencia na tabela 'material'
			 $query = $this->db->query('SELECT * FROM `material` WHERE descricao = ?', array( $nome_mat ) );
			 
			 // Verifica se a consulta está OK
			 if ( ! $query ) {
				 break;
			 }
			 
			 $num_ocorr = $query->rowCount();
			 
			 if( $num_ocorr == 0 ) { // O material da 'material_geral' não existe na 'material'
				 
				 // Deleta o material da tabela 'material_geral'
				 $query = $this->db->delete( 'material_geral', 'descricao', $nome_mat );
				 
			 }
			 
			 $cont++;
		 }
		 
	 }
}