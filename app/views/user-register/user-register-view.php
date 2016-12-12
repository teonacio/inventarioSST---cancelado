<?php if ( ! defined('ABSPATH')) exit; ?>

<div class="camada_1">
<div class="camada_2">
<div class="camada_3">
<div class="camada_4">

<?php
// Metodos
	$modelo->validate_register_form();
	$lista = $modelo->get_list_setor();
?>

	<div class="first_hint">
		<p id="first"><?php echo $this->title; ?> </p>
		<p>Por favor, preencha os dados abaixo para inserir o novo usuário no sistema: </p>
	</div>

<div>
	<form method="post">
		<div class="form_left">
			<br><div class="form-group">
				<label for="nome">Nome: </label>
				<input type="text" name="nome" class="form-control" id="user_login_input_1" />
			</div>
			<div class="form-group">
				<label for="login">Login: </label>
				<input type="text" name="login" class="form-control" id="user_login_input_2" />
			</div>
			<div class="form-group">
				<label for="email">E-mail: </label>
				<input type="text" name="email" class="form-control" id="user_login_input_3" />
			</div>
			<div class="form-group">
				<label for="Setor">Setor: </label><br>
					<select name="setor" class="select" />
						<option selected>------ Selecione um setor abaixo ------</option>
							<?php foreach($lista as $fetch_lista): ?>
									<option> <?php echo $fetch_lista['nomesetor']; ?> </option>
							<?php endforeach; ?>
					</select>
			</div>
			<div class="form-group">
				<label for="senha">Senha: </label>
				<input type="password" name="senha" class="form-control" id="user_login_input_4" />
			</div>
			<div class="form-group" id="user_login_div_5">
				<label for="confirma_senha">Confirmar Senha: </label>
				<input type="password" name="confirma_senha" class="form-control" id="user_login_input_5" />
			</div>
			
			<input type="submit" value="Salvar" class="button" />
		</div>
		
		<?php echo '<br>'.$modelo->form_msg;?>
	</form>
</div>

</div>
</div>
</div>
</div>
