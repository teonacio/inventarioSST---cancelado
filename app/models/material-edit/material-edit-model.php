<?php 
/**
 * Classe para editar dados de materiais
 *
 * @since 0.1
 */

class MaterialEditModel
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
	 * Obtêm os dados do formulário
	 *
	 * @since 0.1
	 * @access public
	 */
	public function get_edit_form ( $idmaterial = false ) {
	
		// O ID de usuário que vamos pesquisar
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
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_matdata = $query->fetch();
		
		// Verifica se os dados da consulta estão vazios
		if ( empty( $fetch_matdata ) ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Configura os dados do formulário
		foreach ( $fetch_matdata as $key => $value ) {
			$this->form_data[$key] = $value;
		}
		
		// Recupera o nome do setor do usuario
		$query = $this->db->query('SELECT tipo FROM tipoequipamento WHERE idtipoequipamento = ?', array( $this->form_data['tipoequipamento_idtipoequipamento'] ));
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Obtêm os dados da consulta
		$this->form_data['tipoequipamento_idtipoequipamento'] = $query->fetch();
		
	}
	
	/**
	 * Valida o formulário de envio para atualizar os dados do material
	 *
	 * @since 0.1
	 * @access public
	 */
	public function validate_edit_form ( $idmaterial ) {
		
		// Verifica se o ID do material foi enviado
		if( empty( $idmaterial ) ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Verifica se algo foi postado
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty ( $_POST ) ) {
			
			$s_detalhes = $_POST['detalhes'];
		
			if ( empty( $_POST['tombamento'] )  ||
				 empty( $_POST['descricao'] ) || 
				 $_POST['tipoeqi'] == '------ Selecione uma categoria abaixo ------'
				 ) {
					
				// Configura a mensagem
				$this->form_msg = feedback( 'form_error', 'Por favor, preencha os campos vazios.' );
					
				// Termina
				return;		
			
			}
			
			if( empty( $_POST['detalhes'] ) ) {
				$s_detalhes = null;
			}
		
		} else {
		
			// Termina se nada foi enviado
			return;
			
		}
		
		// Recupera o antigo nome do material
		$query = $this->db->query('SELECT descricao FROM material WHERE idmaterial = ?', array ( $idmaterial ));
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		$fetch_antigo_nome = $query->fetch(); $antigo_nome = $fetch_antigo_nome[0];
		
		// Recupera os dados de movimentacao(instalação/recolhimento/quantidade instalada atualmente/etc) do material (baseado no seu antigo nome)
		$query = $this->db->query('SELECT * FROM material_geral WHERE descricao = ?', array ( $antigo_nome ));
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		$dados_material_geral = $query->fetch();
		
		// Recupera o id da categoria do material
		$query = $this->db->query('SELECT idtipoequipamento FROM tipoequipamento WHERE tipo = ?', array( $_POST['tipoeqi'] ));
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		$setor = $query->fetch();
		
		// Caso o nome tenha sido alterado, verifica se esse nome já existe na tabela material_geral
		$query_verificamat = $this->db->query('SELECT * FROM material_geral WHERE `descricao` = ?', array( $_POST['descricao']) );
		
		// Verifica a consulta
		if ( !$query_verificamat ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		$flag_verificamat = $query_verificamat->rowCount();
		
		// Configura a informação do textarea
		if( !empty($s_detalhes) )
			$detalhes_textarea = "".$this->db->quote( strip_tags( $s_detalhes ) )."";
		else
			$detalhes_textarea = $s_detalhes;
		
		// Verifica o tamanho da string (máximo: 1000 caracteres)
		if ( strlen(chk_array( $this->form_data, 'detalhes'))  > 1000) {
			$this->form_msg = feedback( 'form_error', 'Respeite o número máximo de caracteres para os detalhes do material (1000).' );
			return;
		}

		// Atualiza os dados na tabela 'material'
		$query = $this->db->update('material', 'idmaterial', $idmaterial, array(
			'tombamento' => $_POST['tombamento'],
			'descricao' => $_POST['descricao'],
			'tipoequipamento_idtipoequipamento' => $setor['idtipoequipamento'],
			"detalhes_material" => $detalhes_textarea,
			'data_ultima_alteracao' => time() - 18000,
			'usuario_ultima_alteracao' => $_SESSION['userdata']['idusuario'],
		));
		
		// Atualiza os dados na tabela 'material_geral'
		$query = $this->db->update('material_geral', 'descricao', $antigo_nome, array(
			'data_ultima_alteracao' => time() - 18000,
			'usuario_ultima_alteracao' => $_SESSION['userdata']['idusuario'],
		));
			
		// Verifica se a consulta está OK e configura a mensagem
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
				
			// Termina success
			return;
		} else {
			
			// Recupera o ID inserido pela $query
			$last_id = $query;
			
			if( $flag_verificamat == 0 ) { // Caso o material (com o nome alterado) NÃO exista na material_geral
				
				// Insere o material (com o nome alterado) na tabela material_geral
				// Note que os dados na material_geral serão os mesmos do material no seu antigo nome
				$query = $this->db->insert('material_geral', array(
					'descricao' => $_POST['descricao'],
					'detalhes_material' => $detalhes_textarea, 
					'data_ult_instalacao' => $dados_material_geral['data_ult_instalacao'],
					'quant_total_instalada' => $dados_material_geral['quant_total_instalada'],
					'data_ult_recolhimento' => $dados_material_geral['data_ult_recolhimento'],
					'quant_total_recolhida' => $dados_material_geral['quant_total_recolhida'],
					'tipoequipamento_idtipoequipamento' => $setor['idtipoequipamento'],
					'data_ultima_alteracao' => time() - 18000,
					'usuario_ultima_alteracao' => $_SESSION['userdata']['idusuario'],
				));
				
				if ( ! $query ) {
					$this->form_msg = feedback( 'form_error' );
					return;
				} else {
					
					$this->form_msg = feedback( 'success', 'Dados do material atualizados.' );
					
					// Redireciona
					$login_uri  = HOME_URI . '/material-view/index/view/' . $idmaterial;
					echo '<meta http-equiv="Refresh" content="0; url=' . $login_uri . '">';
					echo '<script type="text/javascript">window.location.href = "' . $login_uri . '";</script>';
					
				}
				
			} else {
			
				$this->form_msg = feedback( 'success', 'Dados do material atualizados.' );
				
				// Redireciona
				$login_uri  = HOME_URI . '/material-view/index/view/' . $idmaterial;
				echo '<meta http-equiv="Refresh" content="0; url=' . $login_uri . '">';
				echo '<script type="text/javascript">window.location.href = "' . $login_uri . '";</script>';
			
			}
		}
		
	}
	
	/**
	 * Obtêm os nomes das categorias do banco
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
		$fetch_tipoeqidata = $query->fetchAll();
		
		return $fetch_tipoeqidata;
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
		 $string_preparada = str_replace('\r\n',"\r\n", $string_preparada);
		 return $string_preparada;
	 }
}