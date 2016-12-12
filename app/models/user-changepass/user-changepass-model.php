<?php 
/**
 * Classe para alterar senha do usuario
 *
 * @since 0.1
 */

class UserChangepassModel
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
	 * Valida o formulário de envio para atualizar a senha do usuario
	 *
	 * @since 0.1
	 * @access public
	 */
	public function validate_edit_form ( $idusuario ) {
		
		// Verifica se algo foi postado
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty ( $_POST ) ) {
		
			// Faz o loop dos dados do post
			foreach ( $_POST as $key => $value ) {
			
				// Configura os dados do post para a propriedade $form_data
				$this->form_data[$key] = $value;
				
				// Caso haja algum campo em branco.
				if ( empty( $value ) ) {
					
					// Configura a mensagem
					$this->form_msg = feedback( 'form_error', 'Por favor, preencha os campos vazios.' );
					
					// Termina
					return;
					
				}
			
			}
			
			// Verifica se a senha atual digitada e igual a salva pela session
			if ( chk_array( $this->form_data, 'atual_senha') != $_SESSION['userdata']['senha'] ) {
				
				// Configura a mensagem
				$this->form_msg = feedback( 'form_error', 'Senha atual incorreta.' );
					
				// Termina
				return;
			}
			
			// Verifica se a nova senha e igual a confirmacao
			if ( chk_array( $this->form_data, 'senha') != chk_array( $this->form_data, 'confirma_senha') ) {
				
				// Configura a mensagem
				$this->form_msg = feedback( 'form_error', 'Os campos NOVA SENHA e CONFIRMAR SENHA nao sao iguais.' );
					
				// Termina
				return;
			}
		
		} else {
		
			// Termina se nada foi enviado
			return;
			
		}
		
		// Atualiza a senha no banco
		$query = $this->db->update('usuario', 'idusuario', $idusuario, array(
			'senha' => md5(chk_array( $this->form_data, 'senha')),
		));
			
		// Verifica se a consulta está OK e configura a mensagem
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
				
			// Termina 
			return;
		} else {
			$this->form_msg = feedback( 'success', 'Senha atualizada com sucesso.' );
				
			// Termina
			return;
		}
		
	}
	
}