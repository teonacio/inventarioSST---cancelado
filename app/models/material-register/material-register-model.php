<?php 
/**
 * Classe para registros de materiais
 *
 * @since 0.1
 */

class MaterialRegisterModel
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
	 * Valida o formulário de envio para registrar o novo material
	 *
	 * @since 0.1
	 * @access public
	 */
	public function validate_register_form () {
	
		// Configura os dados do formulário
		$this->form_data = array();
		
		// Verifica se algo foi postado
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty ( $_POST ) ) {
		
			// Faz o loop dos dados do post
			foreach ( $_POST as $key => $value ) {
			
				// Configura os dados do post para a propriedade $form_data
				$this->form_data[$key] = $value;
				$this->form_data['tombamento'] = (int) $this->form_data['tombamento'];
				
				// Caso haja algum campo em branco.
				if ( empty( $value ) ) {
					
					// Configura a mensagem
					$this->form_msg = feedback( 'form_error', 'Por favor, preencha os campos vazios.' );
					
					// Termina
					return;
					
				}
			
			}
			
			// Valida o tombamento
			$tombamento = (int) $_POST['tombamento'];
			if ( !$tombamento OR $tombamento < 1000000000 ) {
					
				// Configura a mensagem
				$this->form_msg = feedback( 'form_error', 'Tombamento incorreto.' );
					
				// Termina
				return;
					
			} else {
		
				// Valida a descricao
				if ( is_numeric($_POST['nome']) ) {
					
					// Configura a mensagem
					$this->form_msg = feedback( 'form_error', 'Descricao incorreta.' );
					
					// Termina
					return;
					
				} else {
					
					// Valida a categoria
					if ( $_POST['tipoeqi'] == '------ Selecione uma categoria abaixo ------' ) {
						
						// Configura a mensagem
						$this->form_msg = feedback( 'form_error', 'Por favor, selecione uma categoria.' );
					
						// Termina
						return;
						
					}
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
		
		// Verifica se o tombamento do material ja existe
		$db_check_nome = $this->db->query (
			'SELECT * FROM `material` WHERE `tombamento` = ?', 
			array( 
				chk_array( $this->form_data, 'tombamento')		
			) 
		);
		if ( ! $db_check_nome ) {
			
			$this->form_msg = feedback( 'form_error' );
			return;
		} else {
			
			$fetch_tomb = $db_check_nome->fetch();
			
			if ( !empty($fetch_tomb) ) {
				$this->form_msg = feedback( 'form_error', 'O tombamento informado já está cadastrado no sistema.' );
				return;
			}
		}
		
		// Recupera o ID da categoria escolhida, assumindo que ja houve uma filtragem do valor
		$query_tipoeqi = $this->db->query('SELECT idtipoequipamento FROM tipoequipamento WHERE `tipo` = ?', array( chk_array( $this->form_data, 'tipoeqi')));
		
		// Verifica a consulta
		if ( !$query_tipoeqi ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		$fetch_tipoeqiID = $query_tipoeqi->fetch();
		
		// Verifica se esse material já existe na tabela material_geral
		$query_verificamat = $this->db->query('SELECT * FROM material_geral WHERE `descricao` = ?', array( chk_array( $this->form_data, 'nome')));
		
		// Verifica a consulta
		if ( !$query_verificamat ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		$flag_verificamat = $query_verificamat->rowCount();
		
		// Configura a informação do textarea
		$detalhes_textarea = $this->db->quote( strip_tags( chk_array( $this->form_data, 'detalhes') ) );
		
		// Verifica o tamanho da string (máximo: 1000 caracteres)
		if ( strlen(chk_array( $this->form_data, 'detalhes'))  > 1000) {
			$this->form_msg = feedback( 'form_error', 'Respeite o número máximo de caracteres para os detalhes do material (1000).' );
			return;
		}

		// Insere o novo material na tabela material
		$query = $this->db->insert('material', array(
			'tombamento' => (int) chk_array( $this->form_data, 'tombamento'), 
			'descricao' => chk_array( $this->form_data, 'nome'),
			'detalhes_material' => $detalhes_textarea,
			'tipoequipamento_idtipoequipamento' => $fetch_tipoeqiID['idtipoequipamento'],
			'setor_instalado' => 0, // Acabou de ser inserido no banco, assuma que não existe nenhuma movimentação listada em relação a ele
			'usuario_ultima_alteracao' => 0, // Acabou de ser criado, nunca foi alterado
		));
			
		// Verifica se a consulta está OK e configura a mensagem
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		} else {
			
			// Recupera o ID inserido pela $query
			$last_id = $query;
			
			if( $flag_verificamat == 0 ) { // Caso o material NÃO exista na material_geral
				
				// Insere o novo material na tabela material_geral
				$query = $this->db->insert('material_geral', array(
					'descricao' => chk_array( $this->form_data, 'nome'), 
					'quant_total_instalada' => 0,
					'quant_total_recolhida' => 0,
					'tipoequipamento_idtipoequipamento' => $fetch_tipoeqiID['idtipoequipamento'],
				));
				
				if ( ! $query ) {
					$this->form_msg = feedback( 'form_error' );
					return;
				} else {
					
					$this->form_msg = feedback( 'success', 'Material cadastrado com sucesso.' );
					
					// Redireciona
					$login_uri  = HOME_URI . '/material-view/index/view/' . $last_id;
					echo '<meta http-equiv="Refresh" content="0; url=' . $login_uri . '">';
					echo '<script type="text/javascript">window.location.href = "' . $login_uri . '";</script>';
					
				}
				
			} else {
				
				$this->form_msg = feedback( 'success', 'Material cadastrado com sucesso.' );
			
				// Redireciona
				$login_uri  = HOME_URI . '/material-view/index/view/' . $last_id;
				echo '<meta http-equiv="Refresh" content="0; url=' . $login_uri . '">';
				echo '<script type="text/javascript">window.location.href = "' . $login_uri . '";</script>';
				
			}
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
			$this->form_msg = feedback( 'success' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_tipodata = $query->fetchAll();
		
		return $fetch_tipodata;
	}
}