<?php 
/**
 * Classe para visualizar dados de usuários
 *
 * @since 0.1
 */

class UserViewModel
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
	 * Obtêm os dados para view
	 *
	 * @since 0.1
	 * @access public
	 */
	public function get_view_form ( $idusuario = false ) {
	
		// O ID de usuário que vamos pesquisar
		$s_idusuario = false;
		
		// Verifica se você enviou algum ID para o método
		if ( ! empty( $idusuario ) ) {
			$s_idusuario = (int)$idusuario;
		}
		
		// Verifica se existe um ID de usuário
		if ( empty( $s_idusuario ) ) {
			return;
		}
		
		// Verifica na base de dados
		$query = $this->db->query('SELECT * FROM `usuario` WHERE `idusuario` = ?', array( $s_idusuario ));
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_userdata = $query->fetch();
		
		// Verifica se os dados da consulta estão vazios
		if ( empty( $fetch_userdata ) ) {
			$this->form_msg = feedback( 'form_error', 'Usuario não existe.' );
			return;
		}
		
		return $fetch_userdata;
		
	}
	
	/**
	 * Obtêm o nome do setor a partir de seu ID
	 *
	 * @param integer $ID ID do setor
	 * @since 0.1
	 * @access public
	 */
	public function get_setor_id( $ID ) {
		
		// Realiza a busca
		$query = $this->db->query('SELECT nomesetor FROM `setor` WHERE `idsetor` = ?', array( $ID ));
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_setordata = $query->fetch();
		
		return $fetch_setordata;
	}
	
	/**
	* Gera uma string aleatoria, cujos valores serao definidos pelos parametros
	*
	* @param integer $tamanho O tamanho da string
	* @param boolean $maiusculas TRUE se a string tera letras maiusculas
	* @param boolean $numeros TRUE se a string terá números
	* @param boolean $simbolos TRUE TRUE se a string terá símbolos
	*
	* @param string $retorno A string aleatoria gerada
	*
	* @since 0.1
	* @access public
	*/
	public function aleatoriaSenha($tamanho, $maiusculas = true, $numeros = true, $simbolos = false)
	{
		// Caracteres de cada tipo
		$lmin = 'abcdefghijklmnopqrstuvwxyz';
		$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$num = '1234567890';
		$simb = '!@#$%*-';
		
		// Variáveis internas
		$retorno = '';
		$caracteres = '';

		// Agrupamos todos os caracteres que poderão ser utilizados
		$caracteres .= $lmin;
		if ($maiusculas) $caracteres .= $lmai;
			if ($numeros) $caracteres .= $num;
				if ($simbolos) $caracteres .= $simb;

		// Calculamos o total de caracteres possíveis
		$len = strlen($caracteres);
		
		for ($n = 1; $n <= $tamanho; $n++)
		{
			// Criamos um número aleatório de 1 até $len para pegar um dos caracteres
			$rand = mt_rand(1, $len);
			// Concatenamos um dos caracteres na variável $retorno
			$retorno .= $caracteres[$rand-1];
		}
		
		return $retorno;
	}
	
	/**
	 * Redefine a senha do usuario e envia um email para o usuario acessar o sistema com a nova senha
	 *
	 * @param array $params O parametro da pagina
	 * @param integer $ID OID do usuario
	 * @since 0.1
	 * @access public
	 */
	public function get_pass_rec( $params = null, $ID ) {
		
		// Redefine a senha
		$string = $this->aleatoriaSenha(6);
		$MD5string = md5( $string );
		
		// Recupera o ID do usuario
		$IDusu = $ID;
		
		// Verifica a redefinicao de senha foi usada
		if ( empty($params) ) {
			
			// Termina
			return;
			
		}
		
		// Verifica se o usuario logado nao e SST
		if ( $_SESSION['userdata']['idusuario'] != 1 ) {
						
			// Termina
			return;
			
		}
		
		// Verifica se o usuario a ser gerada a nova senha nao e SST
		if ( $IDusu == 1 ) {
						
			// Termina
			return;
			
		}
		
		// Atualiza a nova senha do usuario
		$query = $this->db->update('usuario', 'idusuario', $IDusu, array(
			'senha' => $MD5string,
		));
		
		// Recupera os dados do usuario
		$query = $this->db->query('SELECT * FROM `usuario` WHERE `idusuario` = ?', array( $IDusu ));
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = feedback( 'form_error' );
			return;
		}
		
		// Obtêm os dados da consulta
		$fetch_userdata = $query->fetch();
		
		// Salva os dados do usuario
		$nome = $fetch_userdata['nome'];
		$login = $fetch_userdata['login'];
		$email = $fetch_userdata['email'];
		
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
		
		$mail->Subject  = "".utf8_decode('Redefinição de Senha').""; // Assunto da mensagem
		$mail->Body = "
		<!DOCTYPE HTML>
		<html lang='pt-br'>
		<head>
			<meta charset='UTF-8'>
			<title>".utf8_decode('Inventário SST')." - ".utf8_decode('Redefinição de Senha')."</title>
		</head>
		<body>
		
		<h1 style='font-family:serif; font-size:15px;'>".utf8_decode('Olá').", ".utf8_decode($nome).";<h1><br>
		<p style='font-family:serif; font-size:15px;'>Foi solicitado ".utf8_decode('através')." do ".utf8_decode('usuário')." SST a ".utf8_decode('redefinição')." de senha para seu ".utf8_decode('usuário')." no ".utf8_decode('inventário SST')."</p><br>
		<p style='font-family:serif; font-size:15px;'>".utf8_decode('É')." sugerido que essa nova senha seja modificada durante seu ".utf8_decode('próximo')." login ao ".utf8_decode('inventário').", por ".utf8_decode('segurança').".</p><br>
		<p style='font-family:serif; font-size:15px;'>Login: ".utf8_decode($login)."<p>
		<p style='font-family:serif; font-size:15px;'>Nova senha: ".$string."<p><br>
		<p style='font-family:serif; font-size:15px;'>Caso ".utf8_decode('não')." tenha pedido a ".utf8_decode('redefinição')." de sua senha, por favor entre em contato com os os ".utf8_decode('usuários')." do ".utf8_decode('inventário')." para maiores ".utf8_decode('informações').".</p><br>
		
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
			<title>".utf8_decode('Inventário SST')." - ".utf8_decode('Redefinição de Senha')."</title>
		</head>
		<body>
			
		<h1 style='font-family:serif; font-size:15px;'>".utf8_decode('Olá').", ".utf8_decode($nome).";<h1><br>
		<p style='font-family:serif; font-size:15px;'>Foi solicitado ".utf8_decode('através')." do ".utf8_decode('usuário')." SST a ".utf8_decode('redefinição')." de senha para seu ".utf8_decode('usuário')." no ".utf8_decode('inventário SST')."</p><br>
		<p style='font-family:serif; font-size:15px;'>".utf8_decode('É')." sugerido que essa nova senha seja modificada durante seu ".utf8_decode('próximo')." login ao ".utf8_decode('inventário').", por ".utf8_decode('segurança').".</p><br>
		<p style='font-family:serif; font-size:15px;'>Login: ".utf8_decode($login)."<p>
		<p style='font-family:serif; font-size:15px;'>Nova senha: ".$string."<p><br>
		<p style='font-family:serif; font-size:15px;'>Caso ".utf8_decode('não')." tenha pedido a ".utf8_decode('redefinição')." de sua senha, por favor entre em contato com os os ".utf8_decode('usuários')." do ".utf8_decode('inventário')." para maiores ".utf8_decode('informações').".</p><br>
		
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
			//
			
			$this->form_msg = feedback( 'success', 'Senha redefinida com sucesso.<br>
			Uma mensagem de recuperacao de senha sera enviada em instantes para o e-mail do usuario contendo a senha gerada.' );
			return;
		}
	}
}