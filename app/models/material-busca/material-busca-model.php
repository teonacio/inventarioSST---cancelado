<?php 
/**
 * Classe para busca de materiais
 *
 * @since 0.1
 */

class MaterialBuscaModel
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
	 * Valida o formulário de busca e retorna do banco os dados desejados.
	 * 
	 * Este método busca no banco dados de usuários, sendo sua busca feita pelo que foi enviado via FORM (validado pela própria função)
	 *
	 * @since 0.1
	 * @access public
	 */
	public function validate_busca_form () {
		
		// Configura os dados do formulário
		$this->form_data = array();
		
		// Verifica se algo foi postado
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty ( $_POST )) {
			
			// Verifica se tem algum material no banco
			$query = $this->db->query('SELECT * FROM material');
			if ( ! $query ) {
				$this->form_msg = feedback( 'form_error' );
				return;
			}
			$fetch_tipodata = $query->fetchAll();
			if( empty($fetch_tipodata) ) {
				$this->form_msg = feedback( 'form_error', 'Nao existem materiais no sistema.' );
				return;
			}
			
			// Se todos os campos estiverem vazios
			if( empty($_POST['idmaterial']) && empty($_POST['tombamento']) && empty($_POST['descricao']) && $_POST['tipoequipamento_idtipoequipamento'] == '------ Selecione uma categoria abaixo ------' ) {
				$this->form_msg = feedback( 'form_error', 'Por favor, preencha algum dos valores acima para realizarmos a busca.' );
				return;
			}
		
			// Faz o loop dos dados do post
			foreach ( $_POST as $key => $value ) {
			
				// Configura os dados do post para a propriedade $form_data
				$this->form_data[$key] = $value;
				
			}
			
			// Valida o idmaterial
			if ( !empty($_POST['idmaterial']) && !is_numeric($_POST['idmaterial']) ) {
					
				// Configura a mensagem
				$this->form_msg = feedback( 'form_error', 'ID incorreto.' );
					
				// Termina
				return;
					
			} else {
				
				// Valida o tombamento
				$tombamento = (int) $_POST['tombamento'];
				if ( !empty($tombamento) && !is_numeric($tombamento) OR !empty($tombamento) && $tombamento < 1000000000 ) {
					
					// Configura a mensagem
					$this->form_msg = feedback( 'form_error', 'Tombamento incorreto.' );
					
					// Termina
					return;
					
				}
				
			}
		
		} else {
			
			// Termina se nada foi enviado
			return;
			
		}
		
		// Verifica se a propriedade $form_data foi preenchida
		if( empty( $this->form_data ) ) {
			return;
		}
		
		// Recupera o ID do setor do usuario, assumindo que ja houve uma filtragem do valor
		$query_tipoeqi = $this->db->query('SELECT idtipoequipamento FROM tipoequipamento WHERE `tipo` = ?', array( chk_array( $this->form_data, 'tipoequipamento_idtipoequipamento')));
		
		// Verifica a consulta
		if ( ! $query_tipoeqi ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		$fetch_tipoeqiID = $query_tipoeqi->fetch();
		$IDtipo = $fetch_tipoeqiID['idtipoequipamento'];
		
		// Arrays de busca (Note que eles nunca serão vazios)
		$busca = array();
		$busca_2 = array();
		
		// Verifica quais campos foram preenchidos
		$num = 4; $cont = 0;
		while( $num > $cont ) {
			
			if( !empty($this->form_data['idmaterial']) AND in_array('idmaterial',$busca) == false ) {
				$busca[$cont] = 'idmaterial';
			}
			if( !empty($this->form_data['tombamento']) AND in_array('tombamento',$busca) == false ) {
				$busca[$cont] = 'tombamento';
			}
			if( !empty($this->form_data['descricao']) AND in_array('descricao',$busca) == false ) {
				$busca[$cont] = 'descricao';
			}
			if( $this->form_data['tipoequipamento_idtipoequipamento'] != '------ Selecione uma categoria abaixo ------' AND in_array('tipoequipamento_idtipoequipamento',$busca) == false ) {
				$busca[$cont] = 'tipoequipamento_idtipoequipamento';
			}
			$cont++;
		}
		
		// Cria a linha de busca do DB, dependendo dos valores escolhidos para a busca
		$partial_query = 'SELECT * FROM material WHERE ';
		$num = count($busca);
		$cont = 1;
		while( $num >= $cont ) {
			
			$newcont = $cont - 1;
			
			if( $num == $cont ) {
				if($busca[$newcont] == 'tipoequipamento_idtipoequipamento'){
					$partial_query .= $busca[$newcont].' = ?';
					$busca_2[$newcont] = $IDtipo;
				} else {
					$partial_query .= $busca[$newcont].' = ?';
					$busca_2[$newcont] = $this->form_data[$busca[$newcont]];
				}
			} else {
				if($busca[$newcont] == 'tipoequipamento_idtipoequipamento') {
					$partial_query .= $busca[$newcont].' = ? AND ';
					$busca_2[$newcont] = $IDtipo;
				} else {
					$partial_query .= $busca[$newcont].' = ? AND ';
					$busca_2[$newcont] = $this->form_data[$busca[$newcont]];
				}
			}
			
			$cont++;
		}
		
		// Realiza a busca
		$query = $this->db->query( $partial_query, $busca_2 );
		if ( ! $query ) {
			return array();
		}
		$fetchAll = $query->fetchAll();
		
		if( empty($fetchAll) ) { // Caso seja vazio, realiza a busca via query_strpos
			
			$busca2 = $this->db->query_strpos( 'material', $busca, 'idmaterial', $busca_2 );
			
			if( empty($busca2) ) { // Caso não haja resultados para as buscas realizadas 
			
				$this->form_msg = feedback( 'form_error', 'Nao foram encontrados resultados para a busca com estes parametros.' );
				return;
			} else {
				return $busca2;
			}
		} else {
			return $fetchAll;
		}
	}
	
	/**
	 * Obtêm os nomes das categorias dos materiais do banco
	 *
	 * @since 0.1
	 * @access public
	 */
	public function get_list_tipoeqi() {
		
		// Realiza a busca
		$query = $this->db->query('SELECT tipo FROM tipoequipamento');
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_tipodata = $query->fetchAll();
		
		return $fetch_tipodata;
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