<?php if ( ! defined('ABSPATH')) exit; ?>

<div class="camada_1">
<div class="camada_2">
<div class="camada_3">
<div class="camada_4_exec">

<?php 
// Metodos
	$lista = $modelo->get_setor_list();
	$modelo->del_setor( $parametros );
?>

<div class="table_ID_warning">
	<center><?php echo $this->title; ?></center>
	<p><b>NOTA: </b>A <b>Busca por ID</b> abaixo refere-se a busca apenas pelo ID do setor.
					Para buscas mais específicas, consulte a opção <b>Busca</b> no menu <b>Setores</b>.</p>
</div>

<table id="paginacao" class="dataTable" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th id="table_THstart">ID</th>
			<th>Código</th>
			<th>Nome</th>
			<th>Ações</th>
		</tr>
	</thead>
			
	<tbody>
			
		<?php foreach ($lista as $fetch_setordata): ?>
		
			<tr>
			
				<!-- Verifica se o setor ja foi utilizado em alguma movimentacao -->
				<?php $verifica = $modelo->ver_usado_setor( $fetch_setordata['idsetor'] ); ?>
				
				<td> <?php echo $fetch_setordata['idsetor'] ?> </td>
				<td> <?php echo $fetch_setordata['codigosetor'] ?> </td>
				<td> <?php echo $fetch_setordata['nomesetor'] ?> </td>
				
				<td> 
					<a href="<?php echo HOME_URI ?>/setor-view/index/view/<?php echo $fetch_setordata['idsetor'] ?>">
						<img src = <?php echo HOME_URI . '/style/_images/details.gif'; ?> alt="Visualizar" title="Visualizar" />&nbsp;
					</a>
					<a href="<?php echo HOME_URI ?>/setor-edit/index/edit/<?php echo $fetch_setordata['idsetor'] ?>">
						<img src = <?php echo HOME_URI . '/style/_images/edit.gif'; ?> alt="Editar" title="Editar" />&nbsp;
					</a>
					<!-- Setores ja adicionados em alguma movimentacao nao podem ser deletados -->
					<?php if( $verifica == false ): ?>
						<a href="<?php echo HOME_URI ?>/setor-list/index/del/<?php echo $fetch_setordata['idsetor'] ?>">
							<img src = <?php echo HOME_URI . '/style/_images/delete.gif'; ?> alt="Deletar" title="Deletar" />
						</a>
					<?php endif; ?>
					
				</td>

			</tr>
			
		<?php endforeach;?>
			
	</tbody>
</table>

</div>
</div>
</div>
</div>