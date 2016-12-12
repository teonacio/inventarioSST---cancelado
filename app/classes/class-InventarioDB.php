<?php
/**
 * InventarioDB - Classe para gerenciamento da base de dados
 */
 
 // Carrega as configurações gerais do banco
 require_once SYSPATH. '/DBconfig.php';

class InventarioDB extends DBconfig
{
	/**
	 * @access public
	 * @param string $host     
	 * @param string $db_name
	 * @param string $password
	 * @param string $user
	 * @param string $charset
	 * @param string $debug
	 */
	 
	public function __construct( $host=null, $db_name=null, $password=null, $user=null, $charset=null, $debug=null ){
	
		// Configura as propriedades
		$this->host     =  $this->host;
		$this->db_name  =  $this->db_name;
		$this->password =  $this->password;
		$this->user     =  $this->user;
		$this->charset  =  $this->charset;
		$this->debug    =  $this->debug;
	
		// Conecta
		$this->connect();
		
	}
	
	/**
	 * Valor do ID adicionado pelo insert
	 *
	 * @public
	 * @access public
	 * @var integer
	 */
	public $last_id;
	
	/**
	 * Cria a conexão PDO
	 *
	 * @final
	 * @access protected
	 */
	final protected function connect() {
	
		/* Detalhes da conexão PDO */
		$pdo_details  = "mysql:host={$this->host};";
		$pdo_details .= "dbname={$this->db_name};";
		$pdo_details .= "charset={$this->charset};";
		 
		// Tenta conectar
		try {
		
			$this->pdo = new PDO($pdo_details, $this->user, $this->password);
			
			// Verifica se devemos debugar
			if ( $this->debug === true ) {
			
				// Configura o PDO ERROR MODE
				$this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
				
			}
		
		} catch (PDOException $e) {
			
			// Verifica se devemos debugar
			if ( $this->debug === true ) {
			
				// Mostra a mensagem de erro
				echo "Erro: " . $e->getMessage();
				
			}
			
			die();
		}
	}
	
	/**
	 * query - PDO
	 *
	 * @access public
	 * @return object|bool Retorna a consulta ou falso
	 */
	public function query( $stmt, $data_array = null ) {
		
		// Prepara e executa
		$query      = $this->pdo->prepare( $stmt );
		$check_exec = $query->execute( $data_array );
		
		// Verifica se a consulta aconteceu
		if ( $check_exec ) {
			
			// Retorna a consulta
			return $query;
			
		} else {
		
			// Configura o erro
			$error       = $query->errorInfo();
			$this->error = $error[2];
			
			// Retorna falso
			return false;
			
		}
	}
	
	/**
	 * query_strpos - PDO
	 * 
	 * Realiza a busca, e trata os valores de acordo com o array @limit
	 *
	 * @access public
	 * @param string $table O nome da tabela
	 * @param array $column As colunas da tabela a serem consultadas
	 * @param string $column_key A chave primaria da tabela
	 * @param array $limit Palavras de busca entre as strings
	 * @return array|null Retorna a consulta reformulada ou um array nulo
	 */
	public function query_strpos( $table, $column, $column_key, $limit ) {
		 
		 // Realiza a consulta
		 $stmt = 'SELECT * FROM '.$table;
		 $query = $this->query ( $stmt );
		 
		if ( ! $query ) {
			return array();
		} else {
			// Recupera os resultados da busca
			$fetchAll = $query->fetchAll();
			
			$ID = array(); // Armazena os ID's que passarem por todos os filtros
			$ID_temp = array(); // Armazena os ID's que passarem por cada um dos filtros, separando um array interno para cada filtro
			
			$num = count( $column ); // NOTA: count( $column ) == count( $limit )
			$cont = 0;
			while( $num > $cont ) { // Verifica, para cada filtro, se algum valor passa por ele
				
				$column_temp = $column[$cont]; // Filtro
				$limit_temp = semAcent( strtolower( $limit[$cont] ) ); // Limitador
				
				// Para cada filtro realizado, busca nos valores do banco alguma correspondencia
				$num_2 = count( $fetchAll );
				$cont_2 = 0;
				while( $num_2 > $cont_2 ) { // Array relacionado aos filtros passados como parâmetro
					
					// Retira letras maiusculas e simbolos do comparador
					$comparador = semAcent( strtolower( $fetchAll[$cont_2][$column_temp] ) );
					
					if ( strpos( $comparador, $limit_temp ) !== false ) { // Array relacionado a busca no banco
						
						// Salva o ID do registro que passar pelo filtro
						$ID_temp[$cont][$cont_2] = $fetchAll[$cont_2][$column_key];
					} else {
						
						// Salva a variável como nulo
						$ID_temp[$cont][$cont_2] = null;
					}
					
					$cont_2++;
				}
				
				$cont++;
			}
			
			if( isset($ID_temp[1]) ) { // Caso exista mais de um filtro
			
				// Analiza, de acordo com os ID's de cada filtro, quais ID's são comuns a todos os filtros
				$ID = call_user_func_array('array_intersect',$ID_temp);
			
				if( !empty($ID) ) { // Caso haja algum ID comum entre os filtros
				
					// Realiza nova consulta, agora com os ID's filtrados
					$num = count($ID);
					$cont = 0;
					$fetchAll_2 = array();
					while( $num > $cont ) {
					
						if( isset($ID[$cont]) ) {
							$stmt_2 = 'SELECT * FROM '.$table.' WHERE '.$column_key.' = ?';
							$data_array = array( $ID[$cont] );
							$query_2 = $this->query ( $stmt_2, $data_array );
							$fetch_new = $query_2->fetchAll();
					
							$num_2 = count( $fetch_new );
							$cont_2 = 0;
							while ( $num_2 > $cont_2 ) {
						
								array_push( $fetchAll_2, $fetch_new[$cont_2] );
						
								$cont_2++;
							}
						}
					
						$cont++;
					}
				
					// Retorna a consulta final
					return $fetchAll_2;
				}
				else {
					return null;
				}
			} else { // Caso exista apenas 1 filtro
				
				// Realiza nova consulta, agora com os ID's filtrados
				$num = count($ID_temp[0]);
				$cont = 0;
				$fetchAll_2 = array();
				while( $num > $cont ) {
					
					$stmt_2 = 'SELECT * FROM '.$table.' WHERE '.$column_key.' = ?';
					$data_array = array( $ID_temp[0][$cont] );
					$query_2 = $this->query ( $stmt_2, $data_array );
					$fetch_new = $query_2->fetchAll();
					
					$num_2 = count( $fetch_new );
					$cont_2 = 0;
					while ( $num_2 > $cont_2 ) {
						
						array_push( $fetchAll_2, $fetch_new[$cont_2] );
						
						$cont_2++;
					}
					
					$cont++;
				}
				
				// Retorna a consulta final
				return $fetchAll_2;
			}
		}
		
	}
	
	/**
	 * insert - PDO
	 *
	 * Insere os valores e tenta retornar o último id enviado
	 *
	 * @access public
	 * @param string $table O nome da tabela
	 * @param array ... Ilimitado número de arrays com chaves e valores
	 * @return object|bool|integer Retorna a consulta ou falso (Se possivel, retorna o ID da consulta inserida no banco)
	 */
	public function insert( $table ) {
		// Configura o array de colunas
		$cols = array();
		
		// Configura o valor inicial do modelo
		$place_holders = '(';
		
		// Configura o array de valores
		$values = array();
		
		// O $j will assegura que colunas serão configuradas apenas uma vez
		$j = 1;
		
		// Obtém os argumentos enviados
		$data = func_get_args();
		
		// É preciso enviar pelo menos um array de chaves e valores
		if ( ! isset( $data[1] ) || ! is_array( $data[1] ) ) {
			return;
		}
		
		// Faz um laço nos argumentos
		for ( $i = 1; $i < count( $data ); $i++ ) {
		
			// Obtém as chaves como colunas e valores como valores
			foreach ( $data[$i] as $col => $val ) {
			
				// A primeira volta do laço configura as colunas
				if ( $i === 1 ) {
					$cols[] = "`$col`";
				}
				
				if ( $j <> $i ) {
					// Configura os divisores
					$place_holders .= '), (';
				}
				
				// Configura os place holders do PDO
				$place_holders .= '?, ';
				
				// Configura os valores que vamos enviar
				$values[] = $val;
				
				$j = $i;
			}
			
			// Remove os caracteres extra dos place holders
			$place_holders = substr( $place_holders, 0, strlen( $place_holders ) - 2 );
		}
		
		// Separa as colunas por vírgula
		$cols = implode(', ', $cols);
		
		// Cria a declaração para enviar ao PDO
		$stmt = "INSERT INTO `$table` ( $cols ) VALUES $place_holders) ";
		
		// Insere os valores
		$insert = $this->query( $stmt, $values );
		
		// Verifica se a consulta foi realizada com sucesso
		if ( $insert ) {
			
			// Verifica se temos o último ID enviado
			if ( method_exists( $this->pdo, 'lastInsertId' ) 
				&& $this->pdo->lastInsertId() 
			) {
				// Configura o último ID
				$last_id = $this->pdo->lastInsertId();
				
				// Retorna o ID adicionado
				return $last_id;
			}
			
			// Retorna a consulta
			return $insert;
		}
		
		return;
	}
	
	/**
	 * Update - PDO
	 *
	 * Atualiza uma (ou mais) linhs(s) baseado em uma (ou mais) condição(oes)
	 *
	 * @access protected
	 * @param string $table Nome da tabela
	 * @param string $where_field | WHERE $where_field = $where_field_value
	 * @param string $where_field_value | WHERE $where_field = $where_field_value
	 * @param array $values Um array com os novos valores ( Chave do array -> Nome da coluna / Valor da respectiva chave -> Novo valor para a coluna )
	 * @return object|bool Retorna a consulta ou falso
	 */
	public function update( $table, $where_field, $where_field_value, $values ) {
		
		// Você tem que enviar todos os parâmetros
		if ( empty($table) || empty($where_field) || empty($where_field_value)  ) {
			return;
		}
		
		// Você precisa enviar um array com valores
		if ( ! is_array( $values ) ) {
			return;
		}
		
		// Começa a declaração
		$stmt = " UPDATE `$table` SET ";
		
		// Configura o array de valores
		$set = array();
		
		// Configura as colunas a atualizar
		foreach ( $values as $column => $value ) {
			$set[] = "`$column` = ?";
		}
		
		// Separa as colunas por vírgula
		$set = implode(', ', $set);
		
		 // Caso exista apenas 1 condição
		if( !is_array( $where_field ) && !is_array( $where_field_value ) ){
			
			// Configura a declaração do WHERE campo=valor
			$where = " WHERE `$where_field` = ? ";
		
			// Concatena a declaração
			$stmt .= $set . $where;
		
			// Configura o valor do campo que vamos buscar
			$values[] = $where_field_value;
		
			// Garante apenas números nas chaves do array
			$values = array_values($values);
				
			// Atualiza
			$update = $this->query( $stmt, $values );
		
			// Verifica se a consulta está OK
			if ( $update ) {
				// Retorna a consulta
				return $update;
			}
		
		} else { // Caso haja mais de uma condição
			
			// É necessário que ambos os parâmetros 2 e 3 sejam arrays
			if( !is_array( $where_field ) || !is_array( $where_field_value ) ){
				return;
			}
				
			// É necessário que os arrays de condição tenham o mesmo tamanho
			if( count( $where_field ) != count( $where_field_value ) ) {
				return;
			}
			
			$partial_query = " WHERE";
			$num = count( $where_field_value );
			$cont = 1;
			while( $num >= $cont ){
					
				$newcont = $cont - 1;
					
				if( $num == $cont ) {
					$partial_query .= " `$where_field[$newcont]` = ? ";
				} else {
					$partial_query .= " `$where_field[$newcont]` = ? AND";
				}
					
				$cont++;
			}
			
			// Concatena a declaração
			$stmt .= $set . $partial_query;
		
			// Garante apenas números nas chaves do array
			$values = array_values($values);
			
			$num = count( $where_field_value );
			$num2 = count( $values ) - 1;
			$cont = 1;
			while( $num >= $cont ){
				
				$newcont = $cont - 1;
				
				$values[ $num2 + $cont ] = $where_field_value[ $newcont ];
				
				$cont++;
			}
			
			// Atualiza
			$update = $this->query( $stmt, $values );
		
			// Verifica se a consulta está OK
			if ( $update ) {
				// Retorna a consulta
				return $update;
			}
		}
		
		return;
	}

	/**
	 * Delete - PDO
	 *
	 * Deleta uma (ou mais) linha(s) da tabela
	 *
	 * @access protected
	 * @param string $table Nome da tabela
	 * @param string|array $where_field WHERE $where_field = $where_field_value ( Caso seja para truncar a tabela, não será informado )
	 * @param string|array $where_field_value WHERE $where_field = $where_field_value ( Caso seja para truncar a tabela, não será informado )
	 * @return object|bool Retorna a consulta ou falso
	 */
	public function delete( $table, $where_field = null, $where_field_value = null ) {
		// É preciso informar o nome da tabela
		if ( empty($table) ) {
			return;
		}
		
		// Caso seja para truncar a tabela
		if( $where_field == null && $where_field_value == null ) {
			
			// Inicia a declaração
			$stmt = "TRUNCATE TABLE ?";
			
			// O nome da tabela a ser truncada
			$value = array( $table );
			
			// Trunca a tabela
			$deleteAll = $this->query( $stmt, $value );
			
			// Verifica se a consulta está OK
			if ( $deleteAll ) {
				// Retorna a consulta
				return $deleteAll;
				
			
			}
			
			return;
			
		} else { // Caso seja para deletar específicos resultados
		
			// Nessa parte, todos os parâmetros precisam ser informados
			if( $where_field === null || $where_field_value === null ){
				return;
			}
			
			if( !is_array( $where_field ) && !is_array( $where_field_value ) ){ // Caso exista apenas 1 condição
				
				// Inicia a declaração
				$stmt = " DELETE FROM `$table` ";
				
				// Configura a declaração WHERE campo=valor
				$where = " WHERE `$where_field` = ? ";
		
				// Concatena tudo
				$stmt .= $where;
		
				// O valor que vamos buscar para apagar
				$values = array( $where_field_value );

				// Apaga
				$delete = $this->query( $stmt, $values );
		
				// Verifica se a consulta está OK
				if ( $delete ) {
					// Retorna a consulta
					return $delete;
				}
		
				return;
				
			} else { // Caso exista mais de uma condição
			
				// É necessário que ambos os parâmetros 2 e 3 sejam arrays
				if( !is_array( $where_field ) || !is_array( $where_field_value ) ){
					return;
				}
				
				// É necessário que ambos os arrays tenham o mesmo tamanho
				if( count( $where_field ) != count( $where_field_value ) ) {
					return;
				}
				
				$partial_query = "DELETE FROM `$table` WHERE";
				$num = count( $where_field_value );
				$cont = 1;
				while( $num >= $cont ){
					
					$newcont = $cont - 1;
					
					if( $num == $cont ) {
						$partial_query .= " `$where_field[$newcont]` = ? ";
					} else {
						$partial_query .= " `$where_field[$newcont]` = ? AND";
					}
					
					$cont++;
				}
				
				// Apaga
				$delete = $this->query( $partial_query, $where_field_value );
				
				// Verifica se a consulta está OK
				if ( $delete ) {
					// Retorna a consulta
					return $delete;
				}
		
				return;
			}
		}
	}
	/**
	 * Quote - PDO
	 *
	 * Mais informações: http://php.net/manual/pt_BR/pdo.quote.php
	 *
	 * @access protected
	 * @param string $string
	 * @return string $retorno
	 */
	public function quote( $string ) {
		
		$retorno = $this->pdo->quote( $string );
		return $retorno;
		
	}
}