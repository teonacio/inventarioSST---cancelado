<?php
/**
 * Classe para visualizacao dos dados de uma especifica movimentacao
 *
 * @since 0.1
 */

class MovimentacaoAdminModel
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
		
		return $fetch_movdata;
	}
	
	/**
	 * Altera algum valor em relação a um (ou mais) material (is)
	 *
	 * @since 0.1
	 * @access public
	 */
	public function alt_mat_mov_form () {
		
		// Recupera os valores dos materiais salvos anteriormente
		$query = $this->get_mat_mov_form();
		
		// Salva os valores dos ID's dos materiais salvos anteriormente
		$num = count($query);
		$cont = 0;
		$array_ID = array();
		while( $num > $cont ) {
			
			$array_ID[$cont] = $query[$cont]['material_idmaterial'];
			
			$cont++;
		}
		
		// Array para armazenar os valores do banco
		$atual_query = array( 'setor_atual' => array(), 'setor_novo' => array(), 'tipo_mov' => array() );
		
		// Array para armazenar os valores da $_POST
		$new_query = array( 'setor_atual' => array(), 'setor_novo' => array(), 'tipo_mov' => array() );
		
		// Organiza os valores da query, preenchendo os arrays $atual_query e $new_query
		$num = count( $array_ID );
		$cont = 0;
		while( $num > $cont ){
			
			// Salva o ID do material
			$IDmat = $array_ID[$cont];
			
			// $atual_query
			$atual_query['setor_atual'][$IDmat] = $query[$cont]['setor_antigo'];
			$atual_query['setor_novo'][$IDmat] = $query[$cont]['setor_novo'];
			$atual_query['tipo_mov'][$IDmat] = $query[$cont]['status_idstatus'];
			
			// $new_query - setor_atual
			if( !isset($_POST['setor_atual'][$IDmat]) ||
			isset($_POST['setor_atual'][$IDmat]) && $_POST['setor_atual'][$IDmat] == '---------- Selecione um setor ---------' ){
				
				$new_query['setor_atual'][$IDmat] = null;
			} else{
				
				// Recupera o ID do setor
				$IDsetor = $this->get_id_setor( $_POST['setor_atual'][$IDmat] );
				
				$new_query['setor_atual'][$IDmat] = $IDsetor;
			}
			
			// $new_query - setor_novo
			if( !isset($_POST['setor_novo'][$IDmat]) ||
			isset($_POST['setor_novo'][$IDmat]) && $_POST['setor_novo'][$IDmat] == '---------- Selecione um setor ---------' ){
				
				$new_query['setor_novo'][$IDmat] = null;
			} else{
				
				// Recupera o ID do setor
				$IDsetor = $this->get_id_setor( $_POST['setor_novo'][$IDmat] );
				
				$new_query['setor_novo'][$IDmat] = $IDsetor;
			}
			
			// $new_query - tipo_mov 
			if( !isset($_POST['tipo_mov'][$IDmat]) || 
			isset($_POST['tipo_mov'][$IDmat]) && $_POST['tipo_mov'][$IDmat] == '----- Selecione -----' ){
				
				$new_query['tipo_mov'][$IDmat] = null;
			} else{
				
				// Recupera o ID do status
				$IDstatus = $this->get_id_status( $_POST['tipo_mov'][$IDmat] );
				
				$new_query['tipo_mov'][$IDmat] = $IDstatus;
			}
			
			$cont++;
		}
		
		// Compara os valores dos arrays e, caso seja diferente, armazena o novo valor referente ao material no banco
		$num = count( $array_ID );
		$cont = 0;
		while( $num > $cont ){
			
			// Salva o ID do material
			$IDmat = $array_ID[$cont];
			
			// setor_atual
			if( $atual_query['setor_atual'][$IDmat] != $new_query['setor_atual'][$IDmat] ) {
				
				// Salva o novo valor no banco
				$fields = array( 'usuario_idusuario', 'material_idmaterial' );
				$values = array( $_SESSION['userdata']['idusuario'], $IDmat );
		
				$query = $this->db->update('movimentacaotemp', $fields, $values, array(
					'setor_antigo' => $new_query['setor_atual'][$IDmat],
				));
			}
			
			// setor_novo
			if( $atual_query['setor_novo'][$IDmat] != $new_query['setor_novo'][$IDmat] ) {
				
				// Salva o novo valor no banco
				$fields = array( 'usuario_idusuario', 'material_idmaterial' );
				$values = array( $_SESSION['userdata']['idusuario'], $IDmat );
		
				$query = $this->db->update('movimentacaotemp', $fields, $values, array(
					'setor_novo' => $new_query['setor_novo'][$IDmat],
				));
			}
			
			// tipo_mov
			if( $atual_query['tipo_mov'][$IDmat] != $new_query['tipo_mov'][$IDmat] ) {
				
				// Salva o novo valor no banco
				$fields = array( 'usuario_idusuario', 'material_idmaterial' );
				$values = array( $_SESSION['userdata']['idusuario'], $IDmat );
		
				$query = $this->db->update('movimentacaotemp', $fields, $values, array(
					'status_idstatus' => $new_query['tipo_mov'][$IDmat],
				));
			}
			
			$cont++;
		}
	
	}
	
	/**
	 * Deleta os materiais
	 *
	 * @since 0.1
	 * @access public
	 */
	public function del_mat_mov_form () {
		
		// Verifica se o usuario quis alterar algo
		if( !isset( $_POST['alterar'] ) ) {
			return;
		}
		
		// Verifica se o usuario selecionou algum material para deletar
		if( !isset( $_POST['del_mat'] ) ) {
			return;
		}
		
		// Atualiza os dados dos materiais
		$this->alt_mat_mov_form();
		
		// Salva os ID's dos materiais a serem excluidos da movimentacao
		$IDmat = array_keys( $_POST['del_mat'] );
		
		$num = count($IDmat);
		$cont = 0;
		while( $num > $cont ){
			
			// Forma os arrays para deletar
			$fields = array( 'usuario_idusuario', 'material_idmaterial' );
			$values = array( $_SESSION['userdata']['idusuario'], $IDmat[$cont] );
			
			// Deleta
			$query = $this->db->delete( 'movimentacaotemp', $fields, $values );
			
			$cont++;
		}
		
		// Atualiza a movimentacao-admin
		$login_uri  = HOME_URI . '/movimentacao-admin/';
		echo '<meta http-equiv="Refresh" content="0; url=' . $login_uri . '">';
		echo '<script type="text/javascript">window.location.href = "' . $login_uri . '";</script>';
		
	}
	
	/**
	 * Cancela a movimentacao
	 *
	 * @since 0.1
	 * @access public
	 */
	public function cancel_mov() {
		
		// Verifica se o usuario quis cancelar a movimentação
		if( !isset( $_POST['cancelar'] ) ) {
			return;
		}
		
		// Salva o ID do usuario
		$IDusu = $_SESSION['userdata']['idusuario'];
		
		// Cancelar a movimentação
		$query = $this->db->delete( 'movimentacaotemp', 'usuario_idusuario', $IDusu );
		
		// Redireciona para a movimentacao-pesquisa
		$login_uri  = HOME_URI . '/movimentacao-pesquisa/index/cancelado';
		
		echo '<meta http-equiv="Refresh" content="0; url=' . $login_uri . '">';
		echo '<script type="text/javascript">window.location.href = "' . $login_uri . '";</script>';
		
	}
	
	/**
	 * Atualiza os valores dos materiais nos setores e nos dados dos materiais
	 *
	 * @param array $inserir_query Array contendo os valores dos materiais da movimentação
	 * @since 0.1
	 * @access public
	 */
	public function setor_material_atualiza ( $inserir_query ) {
		
		// Altera os dados na setor_itens (verificando os dados da movimentação e os dados da tabela)
		$id_mat = array_keys( $inserir_query['tipo_mov'] );
		$num = count($id_mat);
		$cont = 0;
		while( $num > $cont ) {
			
			$idMAT = $id_mat[$cont];
			$setorANT = $inserir_query['setor_antigo'][$idMAT]; // Atual setor do material
			$setorNOV = $inserir_query['setor_novo'][$idMAT]; // Novo setor do material
			$status = $inserir_query['tipo_mov'][$idMAT]; // Tipo da movimentacao ( 1 - Instalação / 2 - Recolhimento / 3 - Transferência )
			
			// Recupera os dados do material do banco
			$query_mat = $this->db->query('SELECT * FROM `material` WHERE `idmaterial` = ?', array( $idMAT ));
			$fetch_matdata = $query_mat->fetch(); $nomeMAT = $fetch_matdata['descricao'];
			
			$query_mat_geral = $this->db->query('SELECT * FROM `material_geral` WHERE `descricao` = ?', array( $nomeMAT ));
			$fetch_matdata_geral = $query_mat->fetch();
			
			// NOTA: Assuma +1 porque essas variáveis só serão usada em casos de instalação ou recolhimento.
			$quant_t_instal = $fetch_matdata_geral['quant_total_instalada'] + 1; // Quantidade total de instalações do material
			$quant_t_recolh = $fetch_matdata_geral['quant_total_recolhida'] + 1; // Quantidade total de recolhimentos do material
			
			if( $status == 1 ) {
				
				// Insere o material no banco
				$query_setorINS = $this->db->insert('setoritens',array(
					'setor_idsetor' => $setorNOV,
					'material_idmaterial' => $idMAT,
				));
				
				// Atualiza os dados na tabela material
				$query = $this->db->update('material', 'idmaterial', $idMAT, array(
					'data_instalacao' => time() - 10800, // Fuso horário
				));
					
				// Atualiza os dados na tabela material_geral
				$query = $this->db->update('material_geral', 'descricao', $idMAT, array(
					'data_ult_instalacao' => time() - 10800, // Fuso horário
					'quant_total_instalada' => $quant_t_instal,
				));
				
			} else {
				
				if( $status == 2 ) { // NOTA: Assuma que o material existe no setor onde ele será removido
					
					// Remove o material do banco
					$query = $this->db->delete( 'setoritens', 'material_idmaterial', $idMAT );
					
					// Atualiza os dados na tabela material
					$query = $this->db->update('material', 'idmaterial', $idMAT, array(
						'data_recolhimento' => time() - 10800, // Fuso horário	
					));
					
					// Atualiza os dados na tabela material_geral
					$query = $this->db->update('material_geral', 'descricao', $idMAT, array(
						'data_ult_recolhimento' => time() - 10800, // Fuso horário
						'quant_total_recolhida' => $quant_t_recolh,
					));
					
				} else { // $status == 3
					
					// Atualiza os dados na tabela setoritens
					$query = $this->db->update('setoritens', 'material_idmaterial', $idMAT, array(
						'setor_idsetor' => $setorNOV,
					));
					
				}
				
			}
			
			$cont++;
		}
	}
	
	/**
	 * Verifica se algum dos materiais pedidos para recolhimento ou transferência realmente existem no antigo setor indicado
	 *
	 * @param array $material_id Array contendo os id's dos materiais
	 * @param array $dados_array Array contendo os dados dos materiais
	 * @return True caso todos os materiais existam em seus respectivos setores | False caso contrário
	 * @since 0.1
	 * @access public
	 */
	 public function verifica_material_antigo_setor( $material_id, $dados_array ) {
		 
		 // Flag de verificação
		 $flag_verif = true;
		 
		 $num = count($material_id); $cont = 0;
		 while ( $num > $cont ) {
			 
			 // ID do material
			 $idMAT = $material_id[$cont];
			 
			 // ID do setor a ser consultado
			 $idSET = $dados_array['setor_antigo'][$idMAT];
			 
			 // O tipo da movimentação => NOTA: Essa função NÃO deve ser aplicada em casos de instalação.
			 $tipoMOV = $dados_array['tipo_mov'][$idMAT];
			 
			 if( $tipoMOV != 1 ) {
				 
				// Realiza a consulta
				$query = $this->db->query('SELECT * FROM `setoritens` WHERE `material_idmaterial` = ? AND `setor_idsetor` = ?', array( $idMAT, $idSET ));
			 
				// Verifica a consulta
				if( !$query ) {
					$flag_verif = false;
					break;
				}
			 
				// Número de linhas afetadas pela query
				$n_r_aff = $query->rowCount();
			 
				if( $n_r_aff == 0 ) {
					$flag_verif = false;
					break;
				}
			 
			 }
			 
			 $cont++;
		 }
		 
		 return $flag_verif;
		 
	 }
	 
	 /**
	 * Verifica se algum dos materiais pedidos para instalação já não foi instalado em algum outro setor
	 *
	 * @param array $material_id Array contendo os id's dos materiais
	 * @param array $dados_array Array contendo os dados dos materiais
	 * @return $mat_instalados Array contendo os nomes e tombamentos dos materiais já instalados (Caso ão haja nenhum, o array retornará vazio)
	 * @since 0.1
	 * @access public
	 */
	 public function verifica_material_instalacao( $material_id, $dados_array ) {
		 
		 // Arrays para os materiais já instalados
		 $mat_instalados = array(); $mat_nome_instalados = array(); $mat_tomb_instalados = array();
		 
		 $num = count($material_id); $cont = 0;
		 while( $num > $cont ) {
			 
			 // ID do material
			 $idMAT = $material_id[$cont];
			 
			 // Realiza a consulta
			 $query = $this->db->query('SELECT * FROM `setoritens` WHERE `material_idmaterial` = ?', array( $idMAT ));
			 
			 // Verifica a consulta
			 if( !$query ) {
				$mat_nome_instalados = array(); $mat_tomb_instalados = array();
				break;
			 }
			 
			 // Número de linhas afetadas pela query
			 $busca_mat_set = $query->fetchAll();
			 
			 if( !empty($busca_mat_set) ) {
				// Busca no banco o nome do material
				$query_mat = $this->db->query('SELECT * FROM `material` WHERE `idmaterial` = ?', array( $idMAT ));
				
				if( !$query_mat ) {
					$mat_nome_instalados = array(); $mat_tomb_instalados = array();
					break;
				}
				
				$busca_mat_des = $query_mat->fetch();
				$mat_tomb_instalados[$cont] = $busca_mat_des['tombamento'];
				$mat_nome_instalados[$cont] = $busca_mat_des['descricao'];
			 }
			 
			 $cont++;
		 }
		 
		 if( !empty($mat_nome_instalados) AND !empty($mat_tomb_instalados) ) {
			 $mat_instalados = array('tombamento' => $mat_tomb_instalados, 'descricao' => $mat_nome_instalados);
		 }
		 
		 return $mat_instalados;
	 }
	
	/**
	 * Conclui a movimentacao
	 *
	 * @since 0.1
	 * @access public
	 */
	public function concluic_mov() {
		
		// Verifica se o usuario quis conclur a movimentação
		if( !isset( $_POST['concluir'] ) ) {
			return;
		}
		
		// Atualiza qualquer valor não atualizado sobre os materiais
		$this->alt_mat_mov_form();
		
		// Recupera todos os valores da movimentacao
		$query = $this->get_mat_mov_form();
		
		// Salva os valores dos ID's dos materiais da movimentação
		$num = count($query);
		$cont = 0;
		$array_ID = $array_tomb = $array_ID_instalar = $array_ID_recolher = $array_ID_transferir = array();
		while( $num > $cont ) {
			
			$array_ID[$cont] = $query[$cont]['material_idmaterial'];
			$array_tomb[$cont] = $query[$cont]['material_tomb'];
			
			if($query[$cont]['status_idstatus'] == 1) // Instalação
				$array_ID_instalar[$cont] = $query[$cont]['material_idmaterial'];
				
			if($query[$cont]['status_idstatus'] == 2) // Recolhimento
				$array_ID_recolher[$cont] = $query[$cont]['material_idmaterial'];
			
			if($query[$cont]['status_idstatus'] == 3) // Transferência
				$array_ID_transferir[$cont] = $query[$cont]['material_idmaterial'];
			
			$cont++;
		}
		
		// Array para armazenar os valores da query
		$inserir_query = array( 'setor_antigo' => array(), 'setor_novo' => array(), 'tipo_mov' => array() );
		
		// Flag para valores nulos
		$null_value = 0;
		
		// Organiza os valores da query
		$num = count( $array_ID );
		$cont = 0;
		while( $num > $cont ){
			
			// Salva o ID do material
			$IDmat = $array_ID[$cont];
			
			// $atual_query
			$inserir_query['setor_antigo'][$IDmat] = $query[$cont]['setor_antigo'];
			$inserir_query['setor_novo'][$IDmat] = $query[$cont]['setor_novo'];
			$inserir_query['tipo_mov'][$IDmat] = $query[$cont]['status_idstatus'];
			
			// Verifica se o usuario esqueceu de informar algum dado de algum material
			if
			(
				// Note que, caso seja instalacao, o setor antigo pode ser nulo.
				$query[$cont]['setor_antigo'] == null && $inserir_query['tipo_mov'][$IDmat] != 1 ||
				$query[$cont]['setor_novo'] == null ||
				$query[$cont]['status_idstatus'] == null
			) {
				$null_value = 1;
			}
			
			$cont++;
		
		}
		
		// Verifica se algum dos valores é nulo
		if( $null_value == 1 ) {
			$this->form_msg = feedback( 'form_error', 'Um (ou mais) valores referentes aos materiais nao foi informado ou esta inválido. Por favor corrija.');
			return;
		}
		
		// Verifica se, caso alguma das ações seja recolher e/ou transferir o material, se esse material existe no setor antigo informado
		$verifica_flag = $this->verifica_material_antigo_setor( $array_ID, $inserir_query );
		
		if( !$verifica_flag ) {
			$this->form_msg = feedback( 'form_error', 'Alguns dos materiais indicados para recolhimento e/ou transferência não existem no setor antigo indicado.');
			return;
		}
		
		// Verifica se algum dos materiais pedidos para instalação já não foi instalado
		$array_instalados = $this->verifica_material_instalacao( $array_ID_instalar, $inserir_query );
		
		if( !empty($array_instalados) ) {
			
			// String para os nomes dos materiais já instalados
			$string_instal = 'Os seguintes materiais dessa movimentação já foram instalados anteriormente:<br> ';
			
			$num = count($array_instalados['tombamento']); $cont = 0;
			while( $num > $cont ) {
				
				if( $cont == $num - 1 ) {
					$string_instal .= $array_instalados['tombamento'][$cont].' - '.$array_instalados['descricao'][$cont];
				} else {
					$string_instal .= $array_instalados['tombamento'][$cont].' - '.$array_instalados['descricao'][$cont].'<br>';
				}
				
				$cont++;
			}
			
			$this->form_msg = feedback( 'form_error', $string_instal);
			return;
		}
		
		// Salva a nova movimentacao
		$query = $this->db->insert('movimentacaogeral',array(
			'data' => time() - 18000,
			'usuario_idusuario' => $_SESSION['userdata']['idusuario'],
		));
		
		// Recupera o ID inserido pela $query
		$last_id = $query;

		// Salva os novos dados dos materiais da movimentação
		$num = count( $array_ID );
		$cont = 0;
		while( $num > $cont ){
			
			// Salva o ID do material
			$IDmat = $array_ID[$cont];
			
			$query2 = $this->db->insert('movimentacaoitens',array(
				'movimentacaoGeral_idmovimentacaoGeral' => $last_id,
				'setor_antigo' => $inserir_query['setor_antigo'][$IDmat],
				'setor_novo' => $inserir_query['setor_novo'][$IDmat],
				'material_idmaterial' => $array_ID[$cont],
				'material_tomb' => $array_tomb[$cont],
				'status_idstatus' => $inserir_query['tipo_mov'][$IDmat],
			));
			
			$cont++;
		}
		
		// Deleta todos os dados da movimentacaotemp (Em relação ao usuário)
		$query = $this->db->delete( 'movimentacaotemp', 'usuario_idusuario', $_SESSION['userdata']['idusuario'] );
		
		// Altera os dados na setor_itens e na tabela material (verificando os dados da movimentação e os dados da tabela)
		$alt_set_itn = $this->setor_material_atualiza ( $inserir_query );
		
		// Redireciona para a movimentacao gerada
		$login_uri  = HOME_URI . '/movimentacao-view/index/view/' . $last_id;
		echo '<meta http-equiv="Refresh" content="0; url=' . $login_uri . '">';
		echo '<script type="text/javascript">window.location.href = "' . $login_uri . '";</script>';
	}
	
	/**
	 * Redireciona para movimentacao-pesquisa.php
	 *
	 * @since 0.1
	 * @access public
	 */
	public function redirec_pesquisa() {
	
		// Verifica se o usuario quis pesquisar mais materiais
		if( !isset( $_POST['buscar'] ) ) {
			return;
		}
		
		// Atualiza os dados dos materiais
		$this->alt_mat_mov_form();

		// Redireciona
		$login_uri = HOME_URI . '/movimentacao-pesquisa/';
		echo '<meta http-equiv="Refresh" content="0; url=' . $login_uri . '">';
		echo '<script type="text/javascript">window.location.href = "' . $login_uri . '";</script>';
		
	}
	
	/**
	 * Obtêm os dados do material
	 *
	 * @param integer $ID O ID do material
	 * @since 0.1
	 * @access public
	 */
	public function get_all_mat( $ID ) {
		
		// Realiza a busca
		$query = $this->db->query ('SELECT * FROM `material` WHERE `idmaterial` = ?', array( $ID ) );
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_matdata = $query->fetch();
		
		return $fetch_matdata;
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
	
	/**
	 * Obtêm o ID do setor a partir do nome
	 *
	 * @param string $nome O nome do setor
	 * @since 0.1
	 * @access public
	 */
	public function get_id_setor( $nome ) {
		
		// Realiza a busca
		$query = $this->db->query ('SELECT idsetor FROM `setor` WHERE `nomesetor` = ?', array( $nome ) );
		
		// Verifica a consulta
		if ( ! $query ) {
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_setordata = $query->fetch();
		
		return $fetch_setordata['idsetor'];
	}
	
	/**
	 * Obtêm o nome do setor a partir do ID
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
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_setordata = $query->fetch();
		
		return $fetch_setordata['nomesetor'];
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
	 * Obtêm o ID do tipo da movimentacao a partir do nome
	 *
	 * @param string $status O status da movimentacao
	 * @since 0.1
	 * @access public
	 */
	public function get_id_status( $status ) {
		
		// Realiza a busca
		$query = $this->db->query ('SELECT idstatus FROM `status` WHERE `status` = ?', array( $status ) );
		
		// Verifica a consulta
		if ( ! $query ) {
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_statusdata = $query->fetch();
		
		return $fetch_statusdata['idstatus'];
	}
	
	/**
	 * Obtêm o nome do tipo da movimentacao a partir do ID
	 *
	 * @param string $IDstatus O ID do status da movimentacao
	 * @since 0.1
	 * @access public
	 */
	public function get_nome_status( $IDstatus ) {
		
		// Realiza a busca
		$query = $this->db->query ('SELECT status FROM `status` WHERE `idstatus` = ?', array( $IDstatus ) );
		
		// Verifica a consulta
		if ( ! $query ) {
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_statusdata = $query->fetch();
		
		return $fetch_statusdata['status'];
	}
	
	/**
	 * Obtêm os nomes dos tipos de movimentacao do banco
	 *
	 * @since 0.1
	 * @access public
	 */
	public function get_list_status() {
		
		// Realiza a busca
		$query = $this->db->query('SELECT status FROM status');
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_statusdata = $query->fetchAll();
		
		return $fetch_statusdata;
	}
	
}