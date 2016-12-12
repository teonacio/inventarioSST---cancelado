<?php 
/**
 * Classe para recuperacao de login
 *
 * @since 0.1
 */

class RecloginModel
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
	 * Valida o formulário de envio para recuperar o login do usuario
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
				
				// Caso haja algum campo em branco.
				if ( empty( $value ) ) {
					
					// Configura a mensagem
					$this->form_msg = feedback( 'form_error', 'Por favor, preencha os campos vazios.' );
					
					// Termina
					return;
					
				}
			
			}
			
			// Valida o email
			if ( strpos( $_POST['email'], '@' ) === false ) {
					
				// Configura a mensagem
				$this->form_msg = feedback( 'form_error', 'Email incorreto.' );
					
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
		
		// Verifica se o email do usuario existe no sistema
		$db_check_nome = $this->db->query (
			'SELECT * FROM `usuario` WHERE `email` = ?', 
			array( 
				chk_array( $this->form_data, 'email')	
			)
		);
		if ( ! $db_check_nome ) {
			
			$this->form_msg = feedback( 'form_error' );
			return;
		} else {
			
			$fetch_nome = $db_check_nome->fetch();
			
			if ( empty($fetch_nome) ) {
				$this->form_msg = feedback( 'form_error', 'O email digitado nao existe no sistema.' );
				return;
			}
		}
		
		// Salva o nome, login e email do usuario
		$nome = $fetch_nome['nome'];
		$login = $fetch_nome['login'];
		$email = chk_array( $this->form_data, 'email');
		
		// Conecta com a classe PHPmailer
		require_once CLS_EXTPATH . '/PHPmailer/class.phpmailer.php';
		
		// Inicia a classe PHPMailer
		$mail = new PHPMailer();

		// Define os dados do servidor e tipo de conexão
		$mail->IsSMTP(); // Define que a mensagem será SMTP
		$mail->Host = "newton.bczm.ufrn.br"; // Endereço do servidor SMTP
		// $mail->SMTPAuth = true; // Autenticação ( NÃO descomente essa linha! )
		$mail->SMTPDebug = 1;
		$mail->Username = 'sst@bczm.ufrn.br'; // Usuário do servidor SMTP
		$mail->Password = 'h3ll0w0rld'; // Senha da caixa postal utilizada

		// Define o remetente
		$mail->From = "sst@bczm.ufrn.br"; 
		$mail->FromName = "Inventario SST";

		// Define os destinatário(s)
		$mail->AddAddress($email, $nome);

		// Define os dados técnicos da Mensagem
		$mail->IsHTML(true); // Define que o e-mail será enviado como HTML
		$mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)
		
		$mail->Subject  = "".utf8_decode('Recuperação de Login').""; // Assunto da mensagem
		$mail->Body = "
		<!DOCTYPE HTML>
		<html lang='pt-br'>
		<head>
			<meta charset='UTF-8'>
			<title>".utf8_decode('Inventário SST')." - ".utf8_decode('Recuperação de Login')."</title>
		</head>
		<body>

		<h1 style='font-family:serif; font-size:15px;'>".utf8_decode('Olá').", ".utf8_decode($nome).";<h1><br>
		<p style='font-family:serif; font-size:15px;'>Foi solicitado para seu email a ".utf8_decode('recuperação')." de login para seu ".utf8_decode('usuário')." no ".utf8_decode('Inventário SST').":</p><br>
		<p style='font-family:serif; font-size:15px;'>Login: ".utf8_decode($login)."<p><br>
		<p style='font-family:serif; font-size:15px;'>Caso ".utf8_decode('não')." tenha pedido a ".utf8_decode('recuperação')." do seu login, por favor desconsidere essa mensagem.</p><br>
		
		<br><br><footer>
			<p align='center' style='color:#C0C0C0; font-family:serif; font-size:15px;'>Essa mensagem foi gerada automaticamente pelo sistema. Por favor ".utf8_decode('não')." responda.</p>
			<p align='center' style='color:#C0C0C0; font-family:serif; font-size:15px;'>".utf8_decode('Inventário SST')." - BCZM</p>
		</footer>
		
		</body>
		</html>";
		$mail->AltBody = "
		<!DOCTYPE HTML>
		<html lang='pt-br'>
		<head>
			<meta charset='UTF-8'>
			<title>".utf8_decode('Inventário SST')." - ".utf8_decode('Recuperação de Login')."</title>
		</head>
		<body>
		
		<h1 style='font-family:serif; font-size:15px;'>".utf8_decode('Olá').", ".utf8_decode($nome).";<h1><br>
		<p style='font-family:serif; font-size:15px;'>Foi solicitado para seu email a ".utf8_decode('recuperação')." de login para seu ".utf8_decode('usuário')." no ".utf8_decode('Inventário SST').":</p><br>
		<p style='font-family:serif; font-size:15px;'>Login: ".utf8_decode($login)."<p><br>
		<p style='font-family:serif; font-size:15px;'>Caso ".utf8_decode('não')." tenha pedido a ".utf8_decode('recuperação')." do seu login, por favor desconsidere essa mensagem.</p><br>
		
		<br><br><footer>
			<p align='center' style='color:#C0C0C0; font-family:serif; font-size:15px;'>Essa mensagem foi gerada automaticamente pelo sistema. Por favor ".utf8_decode('não')." responda.</p>
			<p align='center' style='color:#C0C0C0; font-family:serif; font-size:15px;'>".utf8_decode('Inventário SST')." - BCZM</p>
		</footer>
		
		</body>
		</html>";
		
		// Envio da Mensagem
		$enviar = $mail->Send();

		// Limpa os destinatários e os anexos
		$mail->ClearAllRecipients();
		$mail->ClearAttachments();
		
		if(!$enviar)
		{
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		else
		{
			$this->form_msg = feedback( 'success', 'Uma mensagem de recuperacao de login sera enviada em instantes para o e-mail informado.<br>
			Por favor, acesse o seu e-mail para conferir a mensagem e recuperar seu login.' );
			return;
		}
	}
	
}