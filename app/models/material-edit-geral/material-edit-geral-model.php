<?php 
/**
 * Classe para editar detalhes gerais dos materiais
 *
 * @since 0.1
 */

class MaterialEditGeralModel
{

	/**
	 * $form_data - Os dados do formul�rio de envio.
	 *
	 * @access public
	 */	
	public $form_data;

	/**
	 * $form_msg - Mensagens de feedback para o usu�rio.
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
	 * Obt�m os dados do formul�rio
	 *
	 * @since 0.1
	 * @access public
	 */
	public function get_edit_form ( $descricao_material = false ) {
	
		// O ID de usu�rio que vamos pesquisar
		$s_descricao_material = false;
		
		// Verifica se voc� enviou algum ID para o m�todo
		if ( ! empty( $descricao_material ) ) {
			$s_descricao_material = str_replace( "_", " ", $descricao_material );
		}
		
		// Verifica se existe um ID do material
		if ( empty( $s_descricao_material ) ) {
			return;
		}
		
		// Verifica na base de dados
		$query = $this->db->query('SELECT * FROM `material_geral` WHERE `descricao` = ?', array( $s_descricao_material ));
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Obt�m os dados da consulta
		$fetch_matdata = $query->fetch();
		
		// Verifica se os dados da consulta est�o vazios
		if ( empty( $fetch_matdata ) ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Configura os dados do formul�rio
		foreach ( $fetch_matdata as $key => $value ) {
			$this->form_data[$key] = $value;
		}
		
	}
	
	/**
	 * Valida o formul�rio de envio para atualizar os dados do material
	 *
	 * @since 0.1
	 * @access public
	 */
	public function validate_edit_form ( $descricao_material ) {
		
		// Verifica se o nome do material foi enviado
		if( empty( $descricao_material ) ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		$s_descricao_material = str_replace( "_", " ", $descricao_material );
		
		// Verifica se algo foi postado
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty ( $_POST ) ) {
			
			$v_descricao_material = $_POST['detalhes'];
		
			if ( empty( $_POST['detalhes'] ) ) {
				$v_descricao_material = null;
			}
		
		} else {
		
			// Termina se nada foi enviado
			return;
			
		}
		
		// Configura a informa��o do textarea
		if( !empty($v_descricao_material) )
			$detalhes_textarea = "".$this->db->quote( strip_tags( $v_descricao_material ) )."";
		else
			$detalhes_textarea = $v_descricao_material;
		
		
		// Verifica o tamanho da string (m�ximo: 1000 caracteres)
		if ( strlen(chk_array( $this->form_data, 'detalhes'))  > 1000) {
			$this->form_msg = feedback( 'form_error', 'Respeite o n�mero m�ximo de caracteres para os detalhes do material (1000).' );
			return;
		}

		// Atualiza os dados na tabela 'material_geral'
		$query = $this->db->update('material_geral', 'descricao', $s_descricao_material, array(
			"detalhes_material" => $detalhes_textarea,
		));
			
		// Verifica se a consulta est� OK e configura a mensagem
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
				
			// Termina
			return;
		} else {
				
			$this->form_msg = feedback( 'success', 'Dados do material atualizados.' );
					
			// Redireciona
			$login_uri  = HOME_URI . '/material-view-geral/index/view/' . $descricao_material;
			echo '<meta http-equiv="Refresh" content="0; url=' . $login_uri . '">';
			echo '<script type="text/javascript">window.location.href = "' . $login_uri . '";</script>';		
				
		}
		
	}
	
	 /**
	 * Recupera os detalhes do material e os prepara para visualiza��o
	 *
	 * @param string $string_detalhes Conte�do da coluna 'detalhes_material' da tabela 'material'
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