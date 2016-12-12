<?php if ( ! defined('ABSPATH')) exit; ?>

<div class="camada_1">
<div class="camada_2">
<div class="camada_3">

<?php
// Metodos
	$status_material = $modelo->get_status_material( $lista );
	$setor_instalado = $modelo->get_setor_nome( $lista['setor_instalado'] );
	$tipoeqi = $modelo->get_tipoeqi_id( $lista['tipoequipamento_idtipoequipamento'] );
	$verifica = $modelo_2->ver_usado_mat( $lista['idmaterial'] );
	$ultima_alteracao = $modelo->verifica_ultima_alteracao( $lista['data_ultima_alteracao'], $lista['usuario_ultima_alteracao'] );
	
	$flag_setor_dados = 1;
?>

<div class="camada_4_view">

	<?php $d_i = data_time( 'd/m/Y H:i', $lista['data_instalacao'] ); ?>
	<?php $d_r = data_time( 'd/m/Y H:i', $lista['data_recolhimento'] ); ?>
	
	<?php if( $verifica == true ): ?>
		<div class='warning_exists'>
			<p style="margin-left:10px;">
				<img src = <?php echo HOME_URI . '/style/_images/warning.png'; ?> />
				&nbsp&nbspNOTA: Esse material não pode ser deletado por já ter sido cadastrado em uma (ou mais) movimentações.
			</p>
		</div><br>
	<?php endif; ?>

	<table class="table_view">
		<caption><?php echo $this->title; ?></caption>
		<tbody>
			<tr>
				<td>
					<p> <b>ID:</b> <?php echo $lista['idmaterial']; ?> </p>
				</td>
				<td>
					<p> <b>Tombamento:</b> <?php echo $lista['tombamento']; ?> </p>
				</td>
			</tr>
			<tr>
				<td>
					<p> <b>Descrição:</b> <?php echo $lista['descricao']; ?> </p>
				</td>
				<td>
					<p> <b>Categoria:</b> <?php echo $tipoeqi[0]; ?> </p>
				</td>
			</tr>
			<tr>
				<td>
					<p> <b>Data da Instalação:</b> <?php if(empty($lista['data_instalacao'])) echo 'Inexistente'; else echo $d_i.' hs'; ?> </p>
				</td>
				<td>
					<p> <b>Data do Recolhimento:</b> <?php if(empty($lista['data_recolhimento'])) echo 'Inexistente'; else echo $d_r.' hs'; ?> </p>
				</td>
			</tr>
			<tr>
				<td <?php if($setor_instalado == 'Inexistente') { $flag_setor_dados = 0; echo 'colspan = "2"';} ?> > 
					<p> <b>Status do Material:</b> <?php echo $status_material; ?> </p>
				</td>
				
				<?php if($flag_setor_dados == 1) { ?>
					<td>
						<p> <b>Setor Instalado:</b> <?php echo $setor_instalado; ?> </p>
					</td>
				<?php } ?>
			</tr>
			<tr>
				<td colspan = "2">
					<p> <b>Última alteração:</b> <?php echo $ultima_alteracao; ?> </p>
				</td>
			</tr>
		</tbody>
	</table>
	
	<div class="material_details">
		<div id="material_details_center">
			<?php if( !empty($lista['detalhes_material']) ): ?>
				<p><b>Detalhes:</b></p>	
					<?php $detalhes_material = $modelo->recupera_detalhes_material( $lista['detalhes_material'] ); ?>	
				<p><?php echo $detalhes_material; ?></p>
			<?php endif; ?>
		</div>
	</div>
	
<div class="link_return">
	<b>Caso queira retornar ao histórico de materiais, clique no link abaixo:</b></br>
	<a href="<?php echo HOME_URI . '/material-list'; ?>">
		<img src = <?php echo HOME_URI . '/style/_images/back.gif'; ?> alt="Retornar" title="Retornar" />
	</a>
</div>

</div>
</div>
</div>
</div>