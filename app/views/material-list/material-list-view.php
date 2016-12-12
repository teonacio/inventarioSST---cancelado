<?php if ( ! defined('ABSPATH')) exit; ?>

<div class="camada_1">
<div class="camada_2">
<div class="camada_3">
<div class="camada_4_exec">

<?php 
// Metodos
	$lista = $modelo->get_mat_list();
	$modelo->del_mat( $parametros );
?>

<div class="table_ID_warning">
	<center><?php echo $this->title; ?></center>
	<p><b>NOTA: </b>O <b>Historico de Materiais por Tombamento</b> lista os materiais pelo seu Tombamento.
					Caso queira a listagem dos materiais via nome, consulte a opção <b>Materiais -> Histórico -> Por Nome</b>.</p>
	<p><b>NOTA 2: </b>A <b>Busca por Tombamento</b> abaixo refere-se a busca apenas pelo tombamento do material.
					Para buscas mais específicas, consulte a opção <b>Busca</b> no menu <b>Materiais</b>.</p>
</div>

	<table id="paginacao_material" class="dataTable" cellspacing="0" width="100%">
		<thead>
			<tr>
				<?php if( empty($lista) ) { echo '<th id="table_THstart">Não existem materiais disponiveis no sistema.</th></tr></table>';} else { ?>
				<th>Tombamento</th>
				<th>Descrição</th>
				<th>Categoria</th>
				<th>Ações</th>
			</tr>
		</thead>
			
		<tbody>
		
			<?php foreach ($lista as $fetch_matdata): ?>

				<?php $tipoeqi = $modelo->get_nome_tipoeqi( $fetch_matdata['tipoequipamento_idtipoequipamento'] ); ?>
				
				<!-- Verifica se o material ja foi utilizado em alguma movimentacao -->
				<?php $verifica = $modelo->ver_usado_mat( $fetch_matdata['idmaterial'] ); ?>
		
				<tr>
				
					<td> <?php echo $fetch_matdata['tombamento'] ?> </td>
					<td> <?php echo $fetch_matdata['descricao'] ?> </td>
					<td> <?php echo $tipoeqi['tipo'] ?> </td>
				
					<td> 
						<a href="<?php echo HOME_URI ?>/material-view/index/view/<?php echo $fetch_matdata['idmaterial'] ?>">
							<img src = <?php echo HOME_URI . '/style/_images/details.gif'; ?> alt="Visualizar" title="Visualizar" />&nbsp;
						</a>
						<a href="<?php echo HOME_URI ?>/material-edit/index/edit/<?php echo $fetch_matdata['idmaterial'] ?>">
							<img src = <?php echo HOME_URI . '/style/_images/edit.gif'; ?> alt="Editar" title="Editar" />&nbsp;
						</a>
						<!-- Materiais ja adicionados em alguma movimentacao nao podem ser deletados -->
						<?php if( $verifica == false ): ?>
							<a href="<?php echo HOME_URI ?>/material-list/index/del/<?php echo $fetch_matdata['idmaterial'] ?>">
								<img src = <?php echo HOME_URI . '/style/_images/delete.gif'; ?> alt="Deletar" title="Deletar" />
							</a>
						<?php endif; ?>
					
					</td>

				</tr>
			
			<?php endforeach;?>
			
		</tbody>
		
				<?php } ?>
	
	</table>

</div>
</div>
</div>
</div>