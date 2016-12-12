<?php if ( ! defined('ABSPATH')) exit; ?>

<div class="camada_1">
<div class="camada_2">
<div class="camada_3">
<div class="camada_4_exec">

	<div class="first_hint">
		<p id="first"><?php echo $this->title; ?></p>
		<p>Por favor, preencha os dados abaixo para realizar a busca:</p>
	</div>

<?php
// Metodos
	$lista = $modelo->validate_busca_form();
	$lista_setor = $modelo->get_list_setor();
?>

<div class="border_blue">

	<br><div>
		<form method="post">
			<div class="form_left">
				<div class="form-group" id="user_busca_div_2">
					<label for="setor_idsetor" >Setor: </label><br>
					<select name="setor_idsetor" class="select" />
						<option selected>------ Selecione um setor abaixo ------</option>
						<?php foreach($lista_setor as $fetch_lista): ?>
							<option> <?php echo $fetch_lista['nomesetor']; ?> </option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="form-group">
					<label for="nome">Nome: </label>
					<input type="text" name="nome" class="form-control" id="user_busca_input_2" />
				</div>
				<div class="form-group">
					<label for="login">Login: </label>
					<input type="text" name="login" class="form-control" id="user_busca_input_3" />
				</div>
			
				<input type="submit" value="Buscar" class="button" />
			</div>
		
			<?php echo '<br>'.$modelo->form_msg;?>
		</form>
	</div>

</div>

<div>
<?php if( is_array($lista) ): ?>

	<br><table id="paginacao_busca_view" class="dataTable" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th>ID</th>
			<th>Nome</th>
			<th>Setor</th>
			<th>Ações</th>
		</tr>
	</thead>
			
	<tbody>
			
		<?php foreach ($lista as $fetch_userdata): ?>
		
			<?php $setor = $modelo->get_nome_setor( $fetch_userdata['setor_idsetor'] ); ?>

			<tr>
				
				<td> <?php echo $fetch_userdata['idusuario'] ?> </td>	
				<td> <?php echo $fetch_userdata['nome'] ?> </td>
				<td> <?php echo $setor['nomesetor'] ?> </td>
				
				<td> 
					<a href="<?php echo HOME_URI ?>/user-view/index/view/<?php echo $fetch_userdata['idusuario'] ?>">
						<img src = <?php echo HOME_URI . '/style/_images/details.gif'; ?> alt="Visualizar" title="Visualizar" />&nbsp;
					</a>
					
					<?php if($_SESSION['userdata']['idusuario'] == 1): // Caso o usuario seja SST ?>
						<a href="<?php echo HOME_URI ?>/user-edit/index/edit/<?php echo $fetch_userdata['idusuario'] ?>">
							<img src = <?php echo HOME_URI . '/style/_images/edit.gif'; ?> alt="Editar" title="Editar" />&nbsp;
						</a>
						<a href="<?php echo HOME_URI ?>/user-list/index/del/<?php echo $fetch_userdata['idusuario'] ?>">
							<img src = <?php echo HOME_URI . '/style/_images/delete.gif'; ?> alt="Deletar" title="Deletar" />
						</a>
					<?php endif; ?>
					
				</td>

			</tr>
			
		<?php endforeach;?>
			
	</tbody>
</table>

<?php endif; ?>
</div>

</div>
</div>
</div>
</div>