<?php 
/**
 * Classe para inicio de movimentacoes - adicionar materiais a movimentacao
 *
 * @since 0.1
 */

class MovimentacaoPesquisaModel
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
	 * Este método busca no banco dados de materiais, sendo sua busca feita pelo que foi enviado via FORM (validado pela própria função)
	 *
	 * @since 0.1
	 * @access public
	 */
	public function validate_busca_form () {
		
		// Configura os dados do formulário
		$this->form_data = array();
		
		// Veriica se o usuario está adicionando algum material a movimentação
		if ( isset( $_POST['mat_add'] ) ) {
			return;
		}

		// Verifica se algo foi postado
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty ( $_POST )) {
			
			// Verifica se o usuario digitou algum valor para busca
			if( empty( $_POST['pesqID'] ) && empty( $_POST['tombID'] ) && empty( $_POST['nomeID'] ) ) {
				$this->form_msg = feedback( 'form_error', 'Por favor, preencha algum tipo de busca.' );
				return;
			}
		
			// Faz o loop dos dados do post
			foreach ( $_POST as $key => $value ) {
			
				// Configura os dados do post para a propriedade $form_data
				$this->form_data[$key] = $value;
				
			}
		
		} else {
			
			// Termina se nada foi enviado
			return;
			
		}
		
		//var_dump($this->form_data);
		
		// Verifica se a propriedade $form_data foi preenchida
		if( empty( $this->form_data ) ) {
			return;
		}
		
		// Arrays de busca (Note que eles nunca serão vazios)
		$busca = array();
		$busca_2 = array();
		
		// Verifica quais campos foram preenchidos
		$num = 1; $cont = 0;
		while( $num > $cont ) {
			
			if( !empty($this->form_data['pesqID']) ) {
				$busca[$cont] = 'idmaterial';
			}
			if( !empty($this->form_data['tombID']) ) {
				$busca[$cont] = 'tombamento';
			}
			if( !empty($this->form_data['nomeID']) ) {
				$busca[$cont] = 'descricao';
			}
			
			$cont++;
		}
		
		// Cria a linha de busca do DB, dependendo dos valores escolhidos para a busca
		$partial_query = 'SELECT * FROM material WHERE ';
		$num = count($busca);
		$cont = 1;
		while( $num >= $cont ) {
			
			$newcont = $cont - 1;
			
			if( $busca[$newcont] == 'idmaterial' ) {
				$partial_query .= $busca[$newcont].' = ?';
				$busca_2[$newcont] = $this->form_data['pesqID'];
			} else {
				
				if( $busca[$newcont] == 'tombamento' ) {
					$partial_query .= $busca[$newcont].' = ?';
					$busca_2[$newcont] = $this->form_data['tombID'];
				} else {
					
					if( $busca[$newcont] == 'descricao' ) {
						$partial_query .= $busca[$newcont].' = ?';
						$busca_2[$newcont] = $this->form_data['nomeID'];
					}
					
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
	 * Verifica se alguma movimentacao ja foi iniciada pelo usuario
	 *
	 * @since 0.1
	 * @access public
	 */
	public function ver_mov_ini() {
		
		// Realiza a busca
		$query = $this->db->query('SELECT * FROM movimentacaotemp WHERE `usuario_idusuario` = ?', array( $_SESSION['userdata']['idusuario'] ));
		
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
	 * Retorna o tombamento do material apartir do seu ID
	 *
	 * @since 0.1
	 * @access public
	 */
	 public function recup_tomb_mat ( $ID ) {
		 
		 // Realiza a busca
		$query = $this->db->query('SELECT * FROM material WHERE `idmaterial` = ?', array( $ID ));
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_tombmat = $query->fetch();
		
		return $fetch_tombmat['tombamento'];
	 }
	
	/**
	 * Adiciona os materiais selecionados na movimentação
	 *
	 * @since 0.1
	 * @access public
	 */
	public function add_mat_admin() {
		
		// Veriica se o usuario está adicionando algum material a movimentação
		if ( !isset( $_POST['mat_add'] ) ) {
			return;
		}
		
		// Insere os valores dos materiais a movimentacao
		$IDmat = array_keys( $_POST['mat_add'] );
		$num = count( $IDmat );
		$cont = 0;
		
		while( $num > $cont ) {
			
			// O ID do material
			$ID = $IDmat[$cont];
			
			// O Tombamento do material
			$tombamento = $this->recup_tomb_mat($ID);
			
			// Realiza a consulta para inserção dos materiais a movimentacao
			$query = $this->db->insert('movimentacaotemp', array(
				'data' => time() - 18000, // Fuso horário
				'material_idmaterial' => $ID,
				'material_tomb' => $tombamento,
				'usuario_idusuario' => $_SESSION['userdata']['idusuario'], 
			));
			
			// Verifica se a consulta está OK e configura a mensagem
			if ( ! $query ) {
				
				// Mensagem de erro
				$this->form_msg = feedback( 'form_error' );
				
				// Termina
				break;
				
				
			}
			
			$cont++;
			
		}
		
		// Redireciona para movimentacao-admin
		$login_uri  = HOME_URI . '/movimentacao-admin/';
		
		// Redireciona
		echo '<meta http-equiv="Refresh" content="0; url=' . $login_uri . '">';
		echo '<script type="text/javascript">window.location.href = "' . $login_uri . '";</script>';
	}
	
	/**
	 * Recupera os dados dos materiais da respectiva movimentacao
	 *
	 * @since 0.1
	 * @access public
	 */
	public function get_mat_mov_form () {
		
		// Recupera os materiais adicionados a movimentacao
		$query = $this->db->query('SELECT * FROM `movimentacaotemp` WHERE `usuario_idusuario` = ?', array( $_SESSION['userdata']['idusuario'] ));
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_movdata = $query->fetchAll();
		
		// Verifica se os dados da consulta estão vazios
		if ( empty( $fetch_movdata ) ) {
			return array();
		}
		
		return $fetch_movdata;
	}
	
	/**
	 * Verifica se o material ja foi adicionado a respectiva movimentacao
	 *
	 * @param integer $ID O ID do material
	 * @param array $array Array contendo os dados so materiais adicionados na movimentação do usuário
	 * @return integer 1 Caso o material tenha sido adicionado | 0 Caso contrário
	 * @since 0.1
	 * @access public
	 */
	public function ver_add_atmov ( $ID, $adic ) {
		
		// Realiza a busca
		$query = $this->db->query ('SELECT * FROM `movimentacaotemp` WHERE `material_idmaterial` = ?', array( $ID ) );
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_matdata = $query->fetchAll();
		
		// Verifica se o material foi adicionado
		if( !empty( $fetch_matdata ) ) {
			return 1;
		} else {
			return 0;
		}
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