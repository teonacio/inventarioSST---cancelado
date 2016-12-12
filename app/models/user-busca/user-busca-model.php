<?php 
/**
 * Classe para busca de usuários
 *
 * @since 0.1
 */

class UserBuscaModel
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
			
			// Se todos os campos estiverem vazios
			// Note que ele sempre irá retornar 'path' na $_REQUEST, mesmo que o usuario esteja entrando pela primeira vez na action.
			if( empty($_POST['idusuario']) && empty($_POST['nome']) && empty($_POST['login']) && $_POST['setor_idsetor'] == '------ Selecione um setor abaixo ------' ) {
				$this->form_msg = feedback( 'form_error', 'Por favor, preencha algum dos valores acima para realizarmos a busca.' );
				return;
			}
		
			// Faz o loop dos dados do post
			foreach ( $_POST as $key => $value ) {
			
				// Configura os dados do post para a propriedade $form_data
				$this->form_data[$key] = $value;
				
			}
			
			// Valida o ID do usuario
			if ( !empty($_POST['idusuario']) && !is_numeric($_POST['idusuario']) ) {
					
				// Configura a mensagem
				$this->form_msg = feedback( 'form_error', 'ID incorreto.' );
					
				// Termina
				return;
					
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
		$query_setor = $this->db->query('SELECT idsetor FROM setor WHERE `nomesetor` = ?', array( chk_array( $this->form_data, 'setor_idsetor')));
		
		// Verifica a consulta
		if ( ! $query_setor ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		$fetch_setorID = $query_setor->fetch();
		$IDset = $fetch_setorID['idsetor'];
		
		// Arrays de busca (Note que eles nunca serão vazios)
		$busca = array();
		$busca_2 = array();
		
		// Verifica quais campos foram preenchidos
		$num = 4; $cont = 0;
		while( $num > $cont ) {

			if( !empty($this->form_data['nome']) AND in_array('nome',$busca) == false ) {
				$busca[$cont] = 'nome';
			}
			if( !empty($this->form_data['login']) AND in_array('login',$busca) == false ) {
				$busca[$cont] = 'login';
			}
			if( $this->form_data['setor_idsetor'] != '------ Selecione um setor abaixo ------' AND in_array('setor_idsetor',$busca) == false ) {
				$busca[$cont] = 'setor_idsetor';
			}
			$cont++;
		}
		
		// Cria a linha de busca do DB, dependendo dos valores escolhidos para a busca
		$partial_query = 'SELECT * FROM usuario WHERE ';
		$num = count($busca);
		$cont = 1;
		while( $num >= $cont ) {
			
			$newcont = $cont - 1;
			
			if( $num == $cont ) {
				if($busca[$newcont] == 'setor_idsetor'){
					$partial_query .= $busca[$newcont].' = ?';
					$busca_2[$newcont] = $IDset;
				} else {
					$partial_query .= $busca[$newcont].' = ?';
					$busca_2[$newcont] = $this->form_data[$busca[$newcont]];
				}
			} else {
				if($busca[$newcont] == 'setor_idsetor') {
					$partial_query .= $busca[$newcont].' = ? AND ';
					$busca_2[$newcont] = $IDset;
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
			
			$busca2 = $this->db->query_strpos( 'usuario', $busca, 'idusuario', $busca_2 );
			
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
	 * Obtêm os nomes dos setores do banco
	 *
	 * @since 0.1
	 * @access public
	 */
	public function get_list_setor() {
		
		// Realiza a busca
		$query = $this->db->query('SELECT nomesetor FROM setor');
		
		// Verifica a consulta 
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_setordata = $query->fetchAll();
		
		return $fetch_setordata;
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