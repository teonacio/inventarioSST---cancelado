<?php
/**
 * Verifica se a chave existe no array e se ela tem algum valor.
 *
 * @param array  $array O array
 * @param string $key   A chave do array
 * @return string|null  O valor da chave do array ou nulo
 */
function chk_array ( $array, $key ) {
	// Verifica se a chave existe no array
	if ( isset( $array[ $key ] ) && ! empty( $array[ $key ] ) ) {
		// Retorna o valor da chave
		return $array[ $key ];
	}
	
	// Retorna nulo por padrão
	return null;
}

/**
 * Remove qualquer acento existente em uma palavra, como acento agudo, circunflexo, etc...
 *
 * @param string $string String que se deve remover os acentos
 * @return string  A mesma string, com os acentos removidos
 */
function semAcent($string)
{
	$string2 = preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"), $string);
		
	return $string2;
}

/**
 * Gera uma data a partir do timestamp informado
 *
 * @param string $formato Formato da data
 * @param integer|string $timestamp A data a ser informada ( Caso esteja nulo, sera fornecida a data atual )
 */
function data_time( $formato, $timestamp = null )
{
	
	if($timestamp == null)
	{
		$timestamp = time() - 18000; // Fuso horário
		$data = date( $formato, $timestamp );
	} else {
		$data = date( $formato, $timestamp );
	}
		
	return $data;
}

/**
 * Configura as mensagens de erro
 *
 * @param string $class O tipo da mensagem
 * @param string $message A mensagem de feedback ( Caso o valor seja null, significa erro do sistema )
 */
function feedback( $class, $message = null )
{
	if( $class != 'form_error' AND $class != 'success' ){
		return;
	}
	
	if( $message == null ) { // Caso seja erro do sistema
		
		// O tipo da mensagem precisa ser de erro
		if( $class != 'form_error' ){ return; }
		
		// Configura o feedback
		$feedback = '<div class='.$class.'>
			<p style="margin-left:10px;">
				<img src = " '. HOME_URI .' /style/_images/nao.gif " />
				&nbsp&nbspErro do sistema. Por favor contate o administrador.
			</p>
		</div>';
		
	} else {
		
		// Configura o feedback
		if( $class == "form_error" ) {
			$feedback = '<div class='.$class.'>
				<p style="margin-left:10px;">
					<img src = " '. HOME_URI .' /style/_images/nao.gif " />
					&nbsp&nbsp'.$message.'
				</p>
			</div>';
		} else { // success
			$feedback = '<div class='.$class.'>
				<p style="margin-left:10px;">
					<img src = " '. HOME_URI .' /style/_images/sim.gif " />
					&nbsp&nbsp'.$message.'
				</p>
			</div>';
		}
		
	}
	
	return $feedback;
}

/**
 * Carrega a página de erro
 */

function load_404 () {
	
	/** Carrega os arquivos do view **/
	require INCPATH_2 . '/header.php';
		
    require INCPATH_2 . '/menu.php';
	
	require INCPATH_1 . '/404.php';
	
	require INCPATH_2 . '/footer.php';
	
	return;
}

/**
 * Função para carregar automaticamente todas as classes padrão
 * Nossas classes estão na pasta classes/ e seu nome deverá ser class-NomeDaClasse.php.
 * Exemplo: para a classe InventarioMVC, o arquivo vai chamar class-InventarioMVC.php
 */
function __autoload ( $class_name ) {
	$file = CLSPATH . '/class-' . $class_name . '.php';
	
	if ( ! file_exists( $file ) ) {
		load_404 ();
		return;
	}
	
	// Inclui o arquivo da classe
    require_once $file;
}