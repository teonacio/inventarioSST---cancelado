<?php 
/**
 * Classe para busca de setores
 *
 * @since 0.1
 */

class SetorBuscaModel
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
	 * Este método busca no banco dados dos setores, sendo sua busca feita pelo que foi enviado via FORM (validado pela própria função)
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
			if( empty($_POST['idsetor']) && empty($_POST['codigosetor']) && empty($_POST['nomesetor']) ) {
				$this->form_msg = feedback( 'form_error', 'Por favor, preencha algum dos valores acima para realizarmos a busca.' );
				return;
			}
		
			// Faz o loop dos dados do post
			foreach ( $_POST as $key => $value ) {
			
				// Configura os dados do post para a propriedade $form_data
				$this->form_data[$key] = $value;
				
			}
			
			// Valida o ID do setor 
			if ( !empty($_POST['idsetor']) && !is_numeric($_POST['idsetor']) ) {
					
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
		
		// Arrays de busca (Note que eles nunca serão vazios)
		$busca = array();
		$busca_2 = array();
		
		// Verifica quais campos foram preenchidos
		$num = 3; $cont = 0;
		while( $num > $cont ) {
			
			if( !empty($this->form_data['idsetor']) AND in_array('idsetor',$busca) == false ) {
				$busca[$cont] = 'idsetor';
			}
			if( !empty($this->form_data['codigosetor']) AND in_array('codigosetor',$busca) == false ) {
				$busca[$cont] = 'codigosetor';
			}
			if( !empty($this->form_data['nomesetor']) AND in_array('nomesetor',$busca) == false ) {
				$busca[$cont] = 'nomesetor';
			}
			$cont++;
		}
		
		// Cria a linha de busca do DB, dependendo dos valores escolhidos para a busca
		$partial_query = 'SELECT * FROM setor WHERE ';
		$num = count($busca);
		$cont = 1;
		while( $num >= $cont ) {
			
			$newcont = $cont - 1;
			
			if( $num == $cont ) {
				$partial_query .= $busca[$newcont].' = ?';
				$busca_2[$newcont] = $this->form_data[$busca[$newcont]];
			} else {
				$partial_query .= $busca[$newcont].' = ? AND ';
				$busca_2[$newcont] = $this->form_data[$busca[$newcont]];
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
			
			$busca2 = $this->db->query_strpos( 'setor', $busca, 'idsetor', $busca_2 );
			
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
}