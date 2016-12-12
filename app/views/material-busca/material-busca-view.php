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
	$lista_tipoeqi = $modelo->get_list_tipoeqi();
?>

<div class="border_blue">

	<br><div>
		<form method="post">
			<div class="form_left">
				<div class="form-group" id="material_busca_div_2">
					<label for="tombamento">Tombamento: </label>
					<input type="text" name="tombamento"  placeholder="Tombamento do material" class="form-control" id="material_busca_input_2" />
				</div>
				<div class="form-group">
					<label for="descricao">Descrição: </label>
					<input type="text" name="descricao" placeholder="Descricao do material" class="form-control" id="material_busca_input_3" />
				</div>
				<div class="form-group">
					<label for="tipoequipamento_idtipoequipamento">Categoria: </label><br>
						<select name="tipoequipamento_idtipoequipamento" class="select" />
							<option selected>------ Selecione uma categoria abaixo ------</option>
							<?php foreach($lista_tipoeqi as $fetch_lista): ?>
									<option> <?php echo $fetch_lista['tipo']; ?> </option>
							<?php endforeach; ?>
					</select>
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
				<th>Tombamento</th>
				<th>Descrição</th>
				<th>Categoria</th>
				<th>Ações</th>
		</tr>
	</thead>
			
	<tbody>
			
		<?php foreach ($lista as $fetch_userdata): ?>
		
			<?php $tipoeqi = $modelo->get_nome_tipoeqi( $fetch_userdata['tipoequipamento_idtipoequipamento'] ); ?>

			<tr>
				
				<td> <?php echo $fetch_userdata['idmaterial'] ?> </td>	
				<td> <?php echo $fetch_userdata['tombamento'] ?> </td>
				<td> <?php echo $fetch_userdata['descricao'] ?> </td>
				<td> <?php echo $tipoeqi['tipo'] ?> </td>
				
				<td> 
					<a href="<?php echo HOME_URI ?>/material-view/index/view/<?php echo $fetch_userdata['idmaterial'] ?>">
						<img src = <?php echo HOME_URI . '/style/_images/details.gif'; ?> alt="Visualizar" title="Visualizar" />&nbsp;
					</a>
					<a href="<?php echo HOME_URI ?>/material-edit/index/edit/<?php echo $fetch_userdata['idmaterial'] ?>">
						<img src = <?php echo HOME_URI . '/style/_images/edit.gif'; ?> alt="Editar" title="Editar" />&nbsp;
					</a>
					<a href="<?php echo HOME_URI ?>/material-list/index/del/<?php echo $fetch_userdata['idmaterial'] ?>">
						<img src = <?php echo HOME_URI . '/style/_images/delete.gif'; ?> alt="Deletar" title="Deletar" />
					</a>
					
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