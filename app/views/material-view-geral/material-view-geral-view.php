<?php if ( ! defined('ABSPATH')) exit; ?>

<div class="camada_1">
<div class="camada_2">
<div class="camada_3">

<?php
// Metodos
	$lista_mat = $modelo->get_view_form( $material_nome );
	$categ_mat = $modelo->get_categ_mat( $lista_mat['tipoequipamento_idtipoequipamento'] );
	$total_instal = $modelo->get_total_mat_instal( $material_nome );
	
	$ultima_alteracao = $modelo_2->verifica_ultima_alteracao( $lista_mat['data_ultima_alteracao'], $lista_mat['usuario_ultima_alteracao'] );
?>

<div class="camada_4_view">

	<?php $d_u_i = data_time( 'd/m/Y H:i', $lista_mat['data_ult_instalacao'] ); ?>
	<?php $d_u_r = data_time( 'd/m/Y H:i', $lista_mat['data_ult_recolhimento'] ); ?>

	<table class="table_view">
		<caption><?php echo $this->title; ?></caption>
		<tbody>
			<tr>
				<td>
					<p> <b>Descrição:</b> <?php echo $material_nome ?> </p>
				</td>
				<td>
					<p> <b>Categoria:</b> <?php echo $categ_mat ?> </p>
				</td>
			</tr>
			<tr>
				<td>
					<p> <b>Data da Última Instalação:</b> <?php if(empty($lista_mat['data_ult_instalacao'])) echo 'Inexistente'; else echo $d_u_i; ?> </p>
				</td>
				<td>
					<p> <b>Data do Último Recolhimento:</b> <?php if(empty($lista_mat['data_ult_recolhimento'])) echo 'Inexistente'; else echo $d_u_r; ?> </p>
				</td>
			</tr>
			<tr>
				<td>
					<p> <b>Quantidade Total Instalada:</b> <?php echo $lista_mat['quant_total_instalada'] ?> </p>
				</td>
				<td>
					<p> <b>Quantidade Total Recolhida:</b> <?php echo $lista_mat['quant_total_recolhida'] ?> </p>
				</td>
			</tr>
			<tr>
				<td colspan=2>
					<p> <b>Quantidade Atual Instalada:</b> <?php echo $total_instal ?> </p>
				</td>
			</tr>
			<tr>
				<td colspan=2>
					<p> <b>Última Alteração:</b> <?php echo $ultima_alteracao; ?> </p>
				</td>
			</tr>
		</tbody>
	</table>

	<div class="material_details">
		<div id="material_details_center">
			<?php if( !empty($lista_mat['detalhes_material']) ): ?>
				<p><b>Detalhes:</b></p>	
					<?php $detalhes_material = $modelo->recupera_detalhes_material( $lista_mat['detalhes_material'] ); ?>	
				<p><?php echo $detalhes_material; ?></p>
			<?php endif; ?>
		</div>
	</div>
	
<div class="link_return">
	<b>Caso queira retornar ao histórico de materiais, clique no link abaixo:</b></br>
	<a href="<?php echo HOME_URI . '/material-list-geral'; ?>">
		<img src = <?php echo HOME_URI . '/style/_images/back.gif'; ?> alt="Retornar" title="Retornar" />
	</a>
</div>

</div>
</div>
</div>
</div>