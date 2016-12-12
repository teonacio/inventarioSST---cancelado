<?php if ( ! defined('ABSPATH')) exit; ?>

<div class="camada_1">
<div class="camada_2">
<div class="camada_3">

<?php
// Metodos
	$setor = $modelo->get_setor_id( $lista['setor_idsetor'] );
	$modelo->get_pass_rec( chk_array( $parametros, 2 ), chk_array( $parametros, 1 ) );
?>

<div class="camada_4_view">

	<table id="table_user_view">
		<caption><?php echo $this->title; ?></caption>
		<tbody>

			<tr>
				<td>
					<p><b>ID:</b> <?php echo $lista['idusuario']; ?></p>
				</td>
				<td>
					<p><b>Login:</b> <?php echo $lista['login']; ?></p>
				</td>
			</tr>
			<tr>
				<td>
					<p><b>Nome:</b> <?php echo $lista['nome']; ?></p>
				</td>
				<td>
					<p><b>Email:</b> <?php echo $lista['email']; ?></p>
				</td>
			</tr>
			<tr>
				<td>
					<p><b>Setor:</b> <?php echo $setor[0]; ?></p>
				</td>
			</tr>
			
		</tbody>
			
	</table>
	
	<div id="redefine_senha">
		<?php if($_SESSION['userdata']['idusuario'] == 1 AND $lista['idusuario'] != 1): // Caso o usuario seja SST ?>
		<p style="text-align: center;"><b>Redefinição de Senha:</b></p>
		<p style="text-indent: 50px;">*&nbsp;O Botao abaixo redefine a senha do usuario, gerando uma nova e a enviando via e-mail;</p>
		<p style="text-indent: 50px;">*&nbsp;A senha gerada e aleatoria, com 6 digitos, dentre eles maiúsculas/minúsculas e números;</p>
		<p style="text-align: center;">
			Redefinir Senha:<br>
			<a href="<?php echo HOME_URI ?>/user-view/index/view/<?php echo $lista['idusuario'] ?>/passrec">
				<img src = <?php echo HOME_URI . '/style/_images/pass_rec.gif'; ?> alt="Redefinir Senha" title="Redefinir Senha" />
			</a>
		</p>
		<?php endif; ?>
	</div>
	
	<?php echo '<br>'.$modelo->form_msg; ?>
	
<?php if( isset( $_SERVER['HTTP_REFERER'] ) ): ?>
<div class="link_return">
	<b>Caso queira retornar ao histórico de usuários, clique no link abaixo:</b></br>
	<a href="<?php echo HOME_URI . '/user-list'; ?>">
		<img src = <?php echo HOME_URI . '/style/_images/back.gif'; ?> alt="Retornar" title="Retornar" />
	</a>
</div>
<?php endif; ?>

</div>
</div>
</div>
</div>