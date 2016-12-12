<?php 
/**
 * Classe para busca de movimentacoes
 *
 * @since 0.1
 */

class MovimentacaoBuscaModel
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
	 * Valida o formulário de busca e retorna do banco os ID's das movimentacoes correspondentes
	 * 
	 * @return array|null Array contendo os ID's das movimentacoes ou nulo
	 * @since 0.1
	 * @access public
	 */
	public function validate_busca_form () {
		
		// Verifica se tem alguma movimentacao no banco
		$query = $this->db->query('SELECT * FROM movimentacaogeral');
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		$fetch_tipodata = $query->fetchAll();
		if( empty($fetch_tipodata) ) {
			$this->form_msg = feedback( 'form_error', 'Nao existem movimentacoes no sistema.' );
			return;
		}
		
		// Configura os dados do formulário
		$this->form_data = array();
		
		// Verifica se algo foi postado
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty ( $_POST )) {
			
			// Se todos os campos estiverem vazios
			if( empty($_POST['idmovimentacaoGeral']) &&
				empty($_POST['data1']) &&
				empty($_POST['data2']) &&
				$_POST['periodo'] == '------ Selecione um periodo abaixo ------' &&
				$_POST['setor_idsetor'] == '------ Selecione um setor abaixo ------' && 
				$_POST['status_idstatus'] == '------ Selecione um tipo abaixo ------' &&
				empty($_POST['usuario_idusuario']) &&
				empty($_POST['material_idmaterial'])
				) {
					
				$this->form_msg = feedback( 'form_error', 'Por favor, preencha algum dos valores acima para realizarmos a busca.' );
				return;
			}
		
			// Faz o loop dos dados do post
			foreach ( $_POST as $key => $value ) {
			
				// Configura os dados do post para a propriedade $form_data
				$this->form_data[$key] = $value;
				
			}
			
			// Valida o idmovimentacaoGeral
			if ( !empty( $this->form_data['idmovimentacaoGeral'] ) && !is_numeric( $this->form_data['idmovimentacaoGeral'] ) ) {
				$this->form_msg = feedback( 'form_error', 'ID incorreto.' );
				return;	
			} else {
									
				// Valida a data 2
				if ( empty($this->form_data['data1']) && !empty($this->form_data['data2']) ) {
					$this->form_msg = feedback( 'form_error', 'Caso queira delimitar apenas uma data, por favor preencha a <b>primeira</b> data e deixe em branco a segunda.' );
					return;
				} else {
							
					// Valida o período
					if ( $this->form_data['periodo'] != '------ Selecione um periodo abaixo ------' && !empty($this->form_data['data1']) ||
						$this->form_data['periodo'] != '------ Selecione um periodo abaixo ------' && !empty($this->form_data['data2'])
					) {
						$this->form_msg = feedback( 'form_error', 'Os campos <b>Data</b> e <b>Periodo</b> nao podem ser selecionados ao mesmo tempo.' );
						return;
					}
				
				}
				
			}
		
		} else {
			
			// Termina se nada foi enviado
			return;
			
		}
		
		// Caso tenha sido informado duas datas, converte as duas para timestamp ( DIA / MÊS / ANO )
		if( !empty( $this->form_data['data1'] ) AND !empty( $this->form_data['data2'] ) ){
			$data_dados1 = explode( '/', $this->form_data['data1'] ); // [0] = dia / [1] = mês / [2] = ano
			$timestamp_ant = mktime( 0, 0, 0, $data_dados1[1], $data_dados1[0], $data_dados1[2] );
			
			$data_dados2 = explode( '/', $this->form_data['data2'] ); // [0] = dia / [1] = mês / [2] = ano
			$timestamp_dep = mktime( 0, 0, 0, $data_dados2[1], $data_dados2[0], $data_dados2[2] );
		} else {
			// Caso tenha sido informada uma data, converte-a para timestamp ( DIA / MÊS / ANO )
			if( !empty( $this->form_data['data1'] ) AND empty( $this->form_data['data2'] ) ){
				$data_dados1 = explode( '/', $this->form_data['data1'] ); // [0] = dia / [1] = mês / [2] = ano
				$timestamp_ant = mktime( 0, 0, 0, $data_dados1[1], $data_dados1[0], $data_dados1[2] );
			
				$data_dados2 = explode( '/', $this->form_data['data1'] ); // [0] = dia / [1] = mês / [2] = ano
				$timestamp_dep = mktime( 23, 59, 59, $data_dados2[1], $data_dados2[0], $data_dados2[2] );
			}	
		}
		
		// Recupera o idusuario, caso tenha sido selecionado
		if( !empty( $this->form_data['usuario_idusuario'] ) AND !is_numeric( $this->form_data['usuario_idusuario'] ) ) {
			// Realiza a busca
			$query = $this->db->query ('SELECT idusuario FROM `usuario` WHERE `nome` = ?', array( strtolower( $this->form_data['usuario_idusuario'] )  ) );
		
			// Verifica a consulta
			if ( ! $query ) {
				$this->form_msg = feedback( 'form_error' );
				return;
			}
		
			// Obtêm o ID
			$fetch_idusu = $query->fetch();
			if( !empty($fetch_idusu) )
				$this->form_data['usuario_idusuario'] = $fetch_idusu['idusuario'];
			else
				$this->form_data['usuario_idusuario'] = null;
		}
		
		// Recupera o idmaterial, caso tenha sido selecionado
		if( !empty( $this->form_data['material_idmaterial'] ) AND is_numeric( $this->form_data['material_idmaterial'] ) ) { // ID ou tombamento
			// Realiza a busca, admitindo que seja tombamento (caso a busca seja nula, significa idmaterial)
			$query = $this->db->query ('SELECT idmaterial FROM `material` WHERE `tombamento` = ?', array( $this->form_data['material_idmaterial'] ) );
		
			// Verifica a consulta
			if ( ! $query ) {
				$this->form_msg = feedback( 'form_error' );
				return;
			}
		
			// Obtêm o ID
			$fetch_idmat = $query->fetch();
			if( !empty($fetch_idusu) ) // Significa que o valor enviado era tombamento
				$this->form_data['material_idmaterial'] = $fetch_idmat['idmaterial'];
			
		} else {
			
			if( !empty( $this->form_data['material_idmaterial'] ) ){ // Nome
				// Realiza a busca
				$query_mat = $this->db->query ('SELECT idmaterial FROM `material` WHERE `descricao` = ?', array( strtolower( $this->form_data['material_idmaterial'] ) ) );
		
				// Verifica a consulta
				if ( ! $query_mat ) {
					$this->form_msg = feedback( 'form_error' );
					return;
				}
		
				// Obtêm o ID
				$fetch_idmat = $query_mat->fetch();
				if( !empty($fetch_idusu) )
					$this->form_data['material_idmaterial'] = $fetch_idmat['idmaterial'];
				else {
					$this->form_data['material_idmaterial'] = null;
				}
			}
		}
		
		// Recupera o ID do setor, caso tenha sido selecionado
		if( $_POST['setor_idsetor'] != '------ Selecione um setor abaixo ------' ) {
			// Realiza a busca
			$query_set = $this->db->query ('SELECT idsetor FROM `setor` WHERE `nomesetor` = ?', array( $this->form_data['setor_idsetor'] ));
			
			// Verifica a consulta
			if( ! $query_set ) {
				$this->form_msg = feedback( 'form_error' );
				return;
			}
			
			// Obtêm o ID
			// NOTA: Comos os nomes dos setores visualizados pelo usuário vem do banco, não é necessário realizar a verificação da consulta.
			$fetch_idset = $query_set->fetch();
			$this->form_data['setor_idsetor'] = $fetch_idset['idsetor'];
		}
		
		// Recupera o ID do tipo da movimentação, caso tenha sido selecionado
		if( $_POST['status_idstatus'] != '------ Selecione um tipo abaixo ------' ) {
			// Realiza a busca
			$query_status = $this->db->query ('SELECT idstatus FROM `status` WHERE `status` = ?', array( $this->form_data['status_idstatus'] ));
			
			// Verifica a consulta
			if( ! $query_status ) {
				$this->form_msg = feedback( 'form_error' );
				return;
			}
			
			// Obtêm o ID
			// NOTA: Comos os nomes dos tipos visualizados pelo usuário vem do banco, não é necessário realizar a verificação da consulta.
			$fetch_idstatus = $query_status->fetch();
			$this->form_data['status_idstatus'] = $fetch_idstatus['idstatus'];
			
		}
		
		// Arrays de busca para a movimentacaoGeral (Note que eles nunca serão vazios)
		$busca = array(); $busca_2 = array();
		
		// Arrays para consulta na tabela movimentacaoitens
		$busca_mi = array(); $busca_mi_itens = array();
		
		// Verifica quais campos foram preenchidos
		$num = 8; $cont = 0;
		while( $num > $cont ) {
			
			if( $this->form_data['periodo'] != '------ Selecione um periodo abaixo ------' AND !in_array('periodo',$busca) ) {
				$busca[$cont] = 'periodo';
			}
			if( !empty($this->form_data['idmovimentacaoGeral']) AND !in_array('idmovimentacaoGeral',$busca) ) {
				$busca[$cont] = 'idmovimentacaoGeral';
			}
			if( !empty($this->form_data['data1']) AND !in_array('data1',$busca) ) {
				$busca[$cont] = 'data1';
			}
			if( !empty($this->form_data['data2']) AND !in_array('data2',$busca) ) {
				$busca[$cont] = 'data2';
			}
			if( !empty($this->form_data['usuario_idusuario']) AND !in_array('usuario_idusuario',$busca) ) {
				$busca[$cont] = 'usuario_idusuario';
			}
			if( !empty($this->form_data['material_idmaterial']) AND !in_array('material_idmaterial',$busca_mi) ) {
				$busca_mi[$cont] = 'material_idmaterial';
			}
			if( $this->form_data['setor_idsetor'] != '------ Selecione um setor abaixo ------' AND !in_array('setor_idsetor',$busca_mi) ) {
				$busca_mi[$cont] = 'setor_idsetor';
			}
			if( $this->form_data['status_idstatus'] != '------ Selecione um tipo abaixo ------' AND !in_array('status_idstatus',$busca_mi) ) {
				$busca_mi[$cont] = 'status_idstatus';
			}
			$cont++;
		}

		// Caso tenha selecionado a busca por periodo, cria os periodos de datas em timestamp
		if( chk_array( $busca, 'periodo' ) >= 0 ) {
			if( $this->form_data['periodo'] == 'Semanal' ){
				$data_dep = time() - 18000; // Fuso horário.
				$data_ant = time() - 18000 - 604800;
			} else {
				if( $this->form_data['periodo'] == 'Mensal' ){
					$data_dep = time() - 18000; // Fuso horário.
					$data_ant = time() - 18000 - 2629743;
				} else {
					if( $this->form_data['periodo'] == 'Anual' ) {
						$data_dep = time() - 18000; // Fuso horário.
						$data_ant = time() - 18000 - 31556926;
					}
				}
			}
		}
		
		// Cria a linha de consulta a tabela movimentacaoGeral
		$partial_query = 'SELECT * FROM movimentacaogeral WHERE ';
		$num = count($busca);
		$cont = 1;
		while( $num >= $cont ) {
			
			$newcont = $cont - 1;	
			
			if( $num == $cont ) {
				if($busca[$newcont] != 'periodo' &&
					$busca[$newcont] != 'setor_idsetor' &&
					$busca[$newcont] != 'status_idstatus' &&
					$busca[$newcont] != 'data1' &&
					$busca[$newcont] != 'data2' &&
					$busca[$newcont] != 'material_idmaterial'
				){
					$partial_query .= $busca[$newcont].' = ?';
					$busca_2[$newcont] = $this->form_data[$busca[$newcont]];
				}
			} else {
				if($busca[$newcont] != 'periodo' &&
					$busca[$newcont] != 'setor_idsetor' &&
					$busca[$newcont] != 'status_idstatus' &&
					$busca[$newcont] != 'data1' &&
					$busca[$newcont] != 'data2' &&
					$busca[$newcont] != 'material_idmaterial'
				){
					$partial_query .= $busca[$newcont].' = ? AND ';
					$busca_2[$newcont] = $this->form_data[$busca[$newcont]];
				}
			}
			
			$cont++;
		}
		
		// Cria a linha de consulta a tabela movimentacaoItens
		$partial_query_mi = 'SELECT * FROM movimentacaoitens WHERE ';
		$num_mi = count($busca_mi);
		$cont_mi = 1;
		while( $num_mi >= $cont_mi ) {
			
			$newcont_mi = $cont_mi - 1;
			
			if( $num_mi == $cont_mi ) {
				// Caso a busca por material ou status tenha sido selecionada
				if($busca_mi[$newcont_mi] == 'material_idmaterial' || $busca_mi[$newcont_mi] == 'status_idstatus' ) {
					$partial_query_mi .= $busca_mi[$newcont_mi].' = ?';
					$busca_mi_itens[$newcont_mi] = $this->form_data[$busca_mi[$newcont_mi]];
				}
				
				// Caso a busca por setor tenha sido selecionada
				// NOTA: Como o setor antigo pode ser nulo, assuma que o setor a ser procurado eh o novo setor
				if($busca_mi[$newcont_mi] == 'setor_idsetor') {
					$partial_query_mi .= 'setor_novo = ?';
					$busca_mi_itens[$newcont_mi] = $this->form_data[$busca_mi[$newcont_mi]];
				}
			} else {
				// Caso a busca por material ou status tenha sido selecionada
				if($busca_mi[$newcont_mi] == 'material_idmaterial' || $busca_mi[$newcont_mi] == 'status_idstatus' ) {
					$partial_query_mi .= $busca_mi[$newcont_mi].' = ? AND ';
					$busca_mi_itens[$newcont_mi] = $this->form_data[$busca_mi[$newcont_mi]];
				}
				
				// Caso a busca por setor tenha sido selecionada
				// NOTA: Como o setor antigo pode ser nulo, assuma que o setor a ser procurado eh o novo setor
				if($busca_mi[$newcont_mi] == 'setor_idsetor') {
					$partial_query_mi .= 'setor_novo = ? AND ';
					$busca_mi_itens[$newcont_mi] = $this->form_data[$busca_mi[$newcont_mi]];
				}
			}
			
			$cont_mi++;
		}
		
		// Separa a partial_query de acordo com os espaços
		$array_query = explode( ' ', $partial_query  );
		$cont_array_query = count( $array_query );
		
		// Verifica se o usuario selecionou algum forma de busca via datas ( data1 OU data1 e data2 OU periodo )
		// Lembrando: Apenas 1 desses tipos podem ser selecionados por busca.
		if( in_array( 'periodo', $busca ) ){ // Busca via periodo fixo
			if( end($array_query) == '?' ){
				$partial_query .= ' AND data >= ? AND data <= ?';
				// Penúltimo (data anterior) , Último (data futura)
				array_push( $busca_2, $data_ant, $data_dep );
			} else {
				$partial_query .= 'data >= ? AND data <= ?';
				// Penúltimo (data anterior) , Último (data futura)
				array_push( $busca_2, $data_ant, $data_dep );
			}
		} else {
			if( in_array( 'data2', $busca ) ) { // Busca via duas datas selecionadas pelo usuário
				if( end($array_query) == '?' ){
					$partial_query .= ' AND data >= ? AND data <= ?';
					// Penúltimo (data anterior) , Último (data futura)
					array_push( $busca_2, $timestamp_ant, $timestamp_dep);
				} else {
					$partial_query .= 'data >= ? AND data <= ?';
					// Penúltimo (data anterior) , Último (data futura)
					array_push( $busca_2, $timestamp_ant, $timestamp_dep);
				}
			} else {
				if( in_array( 'data1', $busca ) ) { // Busca via data única selecionada pelo usuário (das 00:00:00hs até 23:59:59hs do mesmo dia)
					if( end($array_query) == '?' ){
						$partial_query .= ' AND data >= ? AND data <= ?';
						// Penúltimo (data anterior) , Último (data futura)
						array_push( $busca_2, $timestamp_ant, $timestamp_dep);
					} else {
						$partial_query .= 'data >= ? AND data <= ?';
						// Penúltimo (data anterior) , Último (data futura)
						array_push( $busca_2, $timestamp_ant, $timestamp_dep);
					}
				}
				
			}
			
		}
		
		// Reseta as chaves da $busca_2
		$keys_busca = array_keys( $busca_2 );
		$num_bsc = count($keys_busca);
		$cont_bsc = 0;
		$busca_2_reset = array();
		while ( $num_bsc > $cont_bsc ) {
			
			$busca_2_reset[$cont_bsc] = $busca_2[$keys_busca[$cont_bsc]];
			
			$cont_bsc++;
		}
		
		// Realiza a busca na tabela movimentacaogeral
		$fetchAll_mg = array();
		if( $partial_query != 'SELECT * FROM movimentacaogeral WHERE ' ) {
			// Realiza a busca na movimentacaogeral
			$query = $this->db->query( $partial_query, $busca_2_reset );
			if ( ! $query ) {
				$this->form_msg = feedback( 'form_error' );
				return;
			}
			$fetchAll_mg = $query->fetchAll();
		}
		
		// Realiza a busca na tabela movimentacaoitens
		$fetchAll_mi = array();
		if( $partial_query_mi != 'SELECT * FROM movimentacaoitens WHERE ' ) {
			$query = $this->db->query( $partial_query_mi, $busca_mi_itens );
			if ( ! $query ) {
				$this->form_msg = feedback( 'form_error' );
				return;
			}
			$fetchAll_mi = $query->fetchAll();
		}
		
		if( $partial_query != 'SELECT * FROM movimentacaogeral WHERE ' && $partial_query_mi != 'SELECT * FROM movimentacaoitens WHERE ' ) {
			
			// Consulta nas duas tabelas ao mesmo tempo - Verifica as duas consultas
			
			// Caso uma das consultas seja vazia
			if( empty($fetchAll_mg) || empty($fetchAll_mi) ) {
				$this->form_msg = feedback( 'form_error', 'Nao foram encontrados resultados para a busca com estes parametros.' );
				return;
			}
			
			// Recupera os ID's da consulta a tabela movimentacaogeral
			$num_mg = count( $fetchAll_mg );
			$cont_mg = 0;
			while( $num_mg > $cont_mg ) {
				$id_query_mg[$cont_mg] = $fetchAll_mg[$cont_mg]['idmovimentacaoGeral'];
				$cont_mg++;
			}
			
			// Recupera os ID's da consulta a tabela movimentacaoitens
			$num_mi = count( $fetchAll_mi );
			$cont_mi = 0;
			while( $num_mi > $cont_mi ) {
				$id_query_mi[$cont_mi] = $fetchAll_mi[$cont_mi]['movimentacaoGeral_idmovimentacaoGeral'];
				$cont_mi++;
			}
			
			// Remove ID's duplicados da consulta a tabela movimentacaoitens
			$id_query_mi = array_unique($id_query_mi);
			
			// Verifica se as duas consultas tem ID's em comum
			$id_query_final = null;
			$id_query_final = array_intersect( $id_query_mg, $id_query_mi );
			
			// Caso não haja ID's em comum
			if( empty($id_query_final) ) {
				$this->form_msg = feedback( 'form_error', 'Nao foram encontrados resultados para a busca com estes parametros.' );
				return;
			} else {
				
				// Reseta as chaves da $id_query_final
				$keys_id = array_keys( $id_query_final );
				$num_id = count($keys_id);
				$cont_id = 0;
				$id_query_final_reset = array();
				while ( $num_id > $cont_id ) {
			
					$id_query_final_reset[$cont_id] = $id_query_final[$keys_id[$cont_id]];
			
					$cont_id++;
				}
				
				return $id_query_final_reset;
			}
			
		} else {
			
			// Consulta em uma das tabelas - Verifica qual consulta foi feita
			
			// Verifica se apenas a consulta a tabela movimentacaogeral foi feita
			if( $partial_query != 'SELECT * FROM movimentacaogeral WHERE ' && $partial_query_mi == 'SELECT * FROM movimentacaoitens WHERE ' ) {
				
				// Verifica se a consulta foi nula
				if( empty($fetchAll_mg) ) {
					$this->form_msg = feedback( 'form_error', 'Nao foram encontrados resultados para a busca com estes parametros.' );
					return;
				}
				
				// Recupera os ID's da consulta
				$num_mg = count( $fetchAll_mg );
				$cont_mg = 0;
				while( $num_mg > $cont_mg ) {
					$id_query_mg[$cont_mg] = $fetchAll_mg[$cont_mg]['idmovimentacaoGeral'];
					$cont_mg++;
				}
				
				return $id_query_mg;
			}
			
			// Verifica se apenas a consulta a tabela movimentacaoitens foi feita
			if( $partial_query == 'SELECT * FROM movimentacaogeral WHERE ' && $partial_query_mi != 'SELECT * FROM movimentacaoitens WHERE ' ) {
				
				// Verifica se a consulta foi nula
				if( empty($fetchAll_mi) ) {
					$this->form_msg = feedback( 'form_error', 'Nao foram encontrados resultados para a busca com estes parametros.' );
					return;
				}
				
				// Recupera os ID's da consulta
				$num_mi = count( $fetchAll_mi );
				$cont_mi = 0;
				while( $num_mi > $cont_mi ) {
					$id_query_mi[$cont_mi] = $fetchAll_mi[$cont_mi]['movimentacaoGeral_idmovimentacaoGeral'];
					$cont_mi++;
				}
			
				// Remove ID's duplicados
				$id_query_mi = array_unique($id_query_mi);
				
				return $id_query_mi;
			}
		}
	
	}
	
	/**
	 * Realiza buscas a tabela movimentacaoitens
	 *
	 * @param array $id_mov_geral Array contendo os ID's das movimentacoes a serem consultadas na tabela.
	 * @return array $id_result Array contendo os ID's das movimentacoes ou array nulo caso contrário.
	 * @since 0.1
	 * @access public
	 */
	public function get_busca_movimentacao_itens( $id_mov_geral ) {
		
	}
	
	/**
	 * Obtêm os dados das movimentações informadas pelo usuário
	 *
	 * @param array $id_array Array contendo os ID's das movimentacoes a serem pesquisadas
	 * @since 0.1
	 * @access public
	 */
	public function get_dados_mov_geral( $id_array ) {
		
		// Array de retorno
		$dados = array();
		
		// Por padrão, reseta as chaves do parâmetro
		$keys_param = array_keys( $id_array );
		$num_res = count( $keys_param );
		$cont_res = 0;
		$id_array_res = array();
		while ( $num_res > $cont_res ) {
			
			$id_array_res[$cont_res] = $id_array[$keys_param[$cont_res]];
			
			$cont_res++;
		}
				
		$num = count( $id_array_res );
		$cont = 0;
		while ( $num > $cont ) {
			
			// ID do laço
			$ID = $id_array_res[$cont];
			
			// Realiza a busca
			$query = $this->db->query ('SELECT * FROM `movimentacaogeral` WHERE `idmovimentacaoGeral` = ?', array( $ID ) );
			
			// Verifica a consulta
			if ( ! $query ) {
				$this->form_msg = feedback( 'form_error' );
				
				// Por segurança, limpa os resultados anteriores
				$dados = array();
				
				break;
			}
			
			// Obtêm os dados da consulta
			$fetch_movger = $query->fetchAll();
			
			// Salva a consulta
			$dados[$cont] = $fetch_movger[0];
			
			$cont++;
		}
		
		return $dados;
		
	}
	
	/**
	 * Obtêm os nomes dos setores cadastrados
	 *
	 * @since 0.1
	 * @access public
	 */
	public function get_all_nomesetor() {
		
		// Realiza a busca
		$query = $this->db->query ('SELECT nomesetor FROM `setor`');
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_nomesetor = $query->fetchAll();
		
		return $fetch_nomesetor;
	}
	
	/**
	 * Obtêm os nomes dos tipos de movimentação
	 *
	 * @since 0.1
	 * @access public
	 */
	public function get_all_status() {
		
		// Realiza a busca
		$query = $this->db->query ('SELECT status FROM `status`');
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_status = $query->fetchAll();
		
		return $fetch_status;
	}
	
	/**
	 * Obtêm o nome do usuário a partir de seu ID
	 *
	 * @param integer $ID O ID do usuário
	 * @since 0.1
	 * @access public
	 */
	public function get_nome_usu( $ID ) {
		
		// Realiza a busca
		$query = $this->db->query ('SELECT nome FROM `usuario` WHERE `idusuario` = ?', array( $ID ) );
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_tipousu = $query->fetch();
		
		return $fetch_tipousu;
	}
}