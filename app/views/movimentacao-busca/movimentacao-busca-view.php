<?php if ( ! defined('ABSPATH')) exit; ?>

<div class="camada_1">
<div class="camada_2">
<div class="camada_3">
<div class="camada_4_exec">

<?php
// Metodos
	$lista = $modelo->validate_busca_form();
	$lista_nomesetor = $modelo->get_all_nomesetor();
	$lista_nomestatus = $modelo->get_all_status();
	if( !empty( $lista ) ) { $lista_dados = $modelo->get_dados_mov_geral($lista); }
	
?>

<div class="border_blue">

	<div class="first_hint">
		<p id="first"><?php echo $this->title; ?></p>
		<p>Por favor, preencha os dados abaixo para realizar a busca:</p>
	</div>

	<form method="post">
		<div class="form_left">
		
			<br><div class="form-group">
				<label for="idmovimentacaoGeral">ID: </label>
				<input type="text" name="idmovimentacaoGeral" placeholder="ID da movimentacao" class="form-control" id="mov_busca_input_01" />
			</div>
			
			<div class="form-group" id="mov_busca_div_02">
				<label for="data1">Data: </label><br>
				<input type="text" name="data1" placeholder="De" class="form-control" id="calendario1" /><br>
				<input type="text" name="data2" placeholder="a" class="form-control" id="calendario2" />
			</div>
			
			<div class="form-group" id="mov_busca_div_03">
				<label for="periodo">Periodo: </label><br>
				<select name="periodo" class="select" id="mov_select">
						<option selected>------ Selecione um periodo abaixo ------</option>
						<option>Semanal</option>
						<option>Mensal</option>
						<option>Anual</option>
				</select>
			</div>
			
			<div class="form-group" id="mov_busca_input_04">
				<label for="setor_idsetor">Setores: </label><br>
				<select name="setor_idsetor" class="select" id="mov_select">
					<option selected>------ Selecione um setor abaixo ------</option>
					<?php foreach($lista_nomesetor as $fetch_nomesetor): ?>
								<option> <?php echo $fetch_nomesetor['nomesetor']; ?> </option>
					<?php endforeach; ?>
				</select>
			</div>
			
			<div class="form-group" id="mov_busca_input_05">
				<label for="status_idstatus">Tipo da movimentação: </label><br>
				<select name="status_idstatus" class="select" id="mov_select">
					<option selected>------ Selecione um tipo abaixo ------</option>
					<?php foreach($lista_nomestatus as $fetch_nomestatus): ?>
								<option> <?php echo $fetch_nomestatus['status']; ?> </option>
					<?php endforeach; ?>
				</select>
			</div>
			
			<div class="form-group" id="mov_busca_div_06">
				<label for="usuario_idusuario">Usuário: </label>
				<input type="text" name="usuario_idusuario" placeholder="ID / Nome completo do usuario" class="form-control" id="mov_busca_input_06" />
			</div>
			
			<div class="form-group" id="mov_busca_div_07">
				<label for="material_idmaterial">Material: </label>
				<input type="text" name="material_idmaterial" placeholder="ID / Tombamento / Nome completo do material" class="form-control" id="mov_busca_input_07" />
			</div>
			
			<input type="submit" value="Buscar" class="button">
		</div>
		
		<?php echo '<br>'.$modelo->form_msg;?>
	</form>
</div>

<div>

<?php if( isset($lista) AND is_array($lista) ): ?>

	<br><table id="paginacao_busca_view" class="dataTable" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th id="table_THstart">ID</th>
			<th>Data</th>
			<th>Usuário</th>
			<th>Ações</th>
		</tr>
	</thead>
			
	<tbody>
			
		<?php foreach ($lista_dados as $fetch_movdata): ?>

				<?php $usu = $modelo->get_nome_usu( $fetch_movdata['usuario_idusuario'] ); ?>
				<?php $data = data_time( 'd/m/Y H:i:s', $fetch_movdata['data'] ); ?>
		
				<tr>
				
					<td> <?php echo $fetch_movdata['idmovimentacaoGeral'] ?> </td>
					<td> <?php echo $data.' hs' ?> </td>
					<td> <?php echo $usu['nome'] ?> </td>
				
					<td> 
						<a href="<?php echo HOME_URI ?>/movimentacao-view/index/view/<?php echo $fetch_movdata['idmovimentacaoGeral'] ?>">
							<img src = <?php echo HOME_URI . '/style/_images/details.gif'; ?> alt="Visualizar" title="Visualizar" />&nbsp;
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