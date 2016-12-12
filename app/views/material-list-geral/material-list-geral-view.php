<?php if ( ! defined('ABSPATH')) exit; ?>

<div class="camada_1">
<div class="camada_2">
<div class="camada_3">
<div class="camada_4_exec">

<?php 
// Metodos
	$lista_nomes = $modelo->get_mat_nomes_list();
	$lista_categorias = $modelo->get_mat_categorias_list( $lista_nomes );
	
	$modelo->apaga_sem_correspondencia_tabela();
?>

<div class="table_ID_warning">
	<center><?php echo $this->title; ?></center>
	<p><b>NOTA: </b>O <b>Historico de Materiais por Nome</b> lista os materiais pelo seu Nome.
					Caso queira a listagem dos materiais via tombamento, consulte a opção <b>Materiais -> Histórico -> Por Tombamento</b>.</p>
	<p><b>NOTA 2: </b>A <b>Busca pelo Nome</b> abaixo refere-se a busca apenas pelo nome do material.
					Para buscas mais específicas, consulte a opção <b>Busca</b> no menu <b>Materiais</b>.</p>
</div>

	<table id="paginacao_material_geral" class="dataTable" cellspacing="0" width="100%">
		<thead>
			<tr>
				<?php if( empty($lista_nomes) ) { echo '<th id="table_THstart">Não existem materiais disponiveis no sistema.</th></tr></table>';} else { ?>
				<th>Descrição</th>
				<th>Categoria</th>
				<th>Ações</th>
			</tr>
		</thead>
			
		<tbody>
		
			<?php $num = count($lista_categorias); $cont = 0; while( $num > $cont ) { ?>
				
				<?php $replace_descricao = str_replace( " ", "_", $lista_nomes[$cont] ); ?>
			
				<tr>
				
					<td> <?php echo $lista_nomes[$cont] ?> </td>
					<td> <?php echo $lista_categorias[$cont] ?> </td>
				
					<td> 
						<?php $modificado_nome = str_replace( ' ', '_', $lista_nomes[$cont] ) ?>
						<a href="<?php echo HOME_URI ?>/material-view-geral/index/view/<?php echo $modificado_nome ?>">
							<img src = <?php echo HOME_URI . '/style/_images/details.gif'; ?> alt="Visualizar" title="Visualizar" />&nbsp;
						</a>
						<a href="<?php echo HOME_URI ?>/material-edit-geral/index/edit-geral/<?php echo $replace_descricao ?>">
							<img src = <?php echo HOME_URI . '/style/_images/edit.gif'; ?> alt="Mudar Detalhes" title="Mudar Detalhes" />&nbsp;
						</a>
					</td>

				</tr>
			
			<?php $cont++; } ?>
			
		</tbody>
		
				<?php } ?>
	
	</table>
	
</div>
</div>
</div>
</div>