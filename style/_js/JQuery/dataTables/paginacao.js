/* Aplicada a PASTA-list-view.php (Exceto materiais) */
$(document).ready(function() {
 $('#paginacao').dataTable({
	"bJQueryUI": true,
	"lengthMenu": [ [5, 10, 20, 50, 100, -1], [5, 10, 20, 50, 100, "Todos"] ],
    "sPaginationType": "full_numbers",
    "sDom": '<"H"Tlfr>t<"F"ip>',
	 "oLanguage": {
		 "sLengthMenu": "Mostrar _MENU_ registros por pagina",
		 "sZeroRecords": "Nenhum registro encontrado",
		 "sInfo": "Mostrando _START_ / _END_ de _TOTAL_ registro(s)",
		 "sInfoEmpty": "Mostrando 0 / 0 de 0 registros",
		 "sInfoFiltered": "(filtrado de _MAX_ registros)",
		 "sSearch": "Busca por ID: ",
		 "oPaginate": {
			 "sFirst": "Incio",
			 "sPrevious": "Anterior",
			 "sNext": "Proximo",
			 "sLast": "Ultimo"
			 }
		 },
		 "aaSorting": [[0, 'asc']],
 });
});

/* Aplicada a material-list-view.php */
$(document).ready(function() {
 $('#paginacao_material').dataTable({
	"bJQueryUI": true,
	"lengthMenu": [ [5, 10, 20, 50, 100, -1], [5, 10, 20, 50, 100, "Todos"] ],
    "sPaginationType": "full_numbers",
    "sDom": '<"H"Tlfr>t<"F"ip>',
	 "oLanguage": {
		 "sLengthMenu": "Mostrar _MENU_ registros por pagina",
		 "sZeroRecords": "Nenhum registro encontrado",
		 "sInfo": "Mostrando _START_ / _END_ de _TOTAL_ registro(s)",
		 "sInfoEmpty": "Mostrando 0 / 0 de 0 registros",
		 "sInfoFiltered": "(filtrado de _MAX_ registros)",
		 "sSearch": "Busca pelo tombamento: ",
		 "oPaginate": {
			 "sFirst": "Incio",
			 "sPrevious": "Anterior",
			 "sNext": "Proximo",
			 "sLast": "Ultimo"
			 }
		 },
		 "aaSorting": [[0, 'asc']],
 });
});

/* Aplicada a material-list-geral-view.php */
$(document).ready(function() {
 $('#paginacao_material_geral').dataTable({
	"bJQueryUI": true,
	"lengthMenu": [ [5, 10, 20, 50, 100, -1], [5, 10, 20, 50, 100, "Todos"] ],
    "sPaginationType": "full_numbers",
    "sDom": '<"H"Tlfr>t<"F"ip>',
	 "oLanguage": {
		 "sLengthMenu": "Mostrar _MENU_ registros por pagina",
		 "sZeroRecords": "Nenhum registro encontrado",
		 "sInfo": "Mostrando _START_ / _END_ de _TOTAL_ registro(s)",
		 "sInfoEmpty": "Mostrando 0 / 0 de 0 registros",
		 "sInfoFiltered": "(filtrado de _MAX_ registros)",
		 "sSearch": "Busca pelo nome: ",
		 "oPaginate": {
			 "sFirst": "Incio",
			 "sPrevious": "Anterior",
			 "sNext": "Proximo",
			 "sLast": "Ultimo"
			 }
		 },
		 "aaSorting": [[0, 'asc']],
 });
});

/* Aplicada a PASTA-busca-view.php, movimentacao-pesquisa-view.php e movimentacao-busca-view.php */
$(document).ready(function() {
 $('#paginacao_busca_view').dataTable({
	"bJQueryUI": true,
	"lengthMenu": [ [5, 10, 20, 50, 100, -1], [5, 10, 20, 50, 100, "Todos"] ],
    "sPaginationType": "full_numbers",
    "sDom": '<"H"Tlr>t<"F"ip>',
	 "oLanguage": {
		 "sLengthMenu": "Mostrar _MENU_ registros por pagina",
		 "sZeroRecords": "Nenhum registro encontrado",
		 "sInfo": "Mostrando _START_ / _END_ de _TOTAL_ registro(s)",
		 "sInfoEmpty": "Mostrando 0 / 0 de 0 registros",
		 "sInfoFiltered": "(filtrado de _MAX_ registros)",
		 "sSearch": "Busca por ID: ",
		 "oPaginate": {
			 "sFirst": "Incio",
			 "sPrevious": "Anterior",
			 "sNext": "Proximo",
			 "sLast": "Ultimo"
			 }
		 },
		 "aaSorting": [[0, 'asc']],
 });
});

/* Aplicada a movimentacao-admin-view.php */
$(document).ready(function() {
 $('#paginacao_admin').dataTable({
	"bJQueryUI": true,
	"lengthMenu": [ [-1], ["Todos"] ],
    "sPaginationType": "full_numbers",
    "sDom": '<"H"Tlr>t<"F"ip>',
	 "oLanguage": {
		 "sLengthMenu": "Mostrar _MENU_ registros por pagina",
		 "sZeroRecords": "Nenhum registro encontrado",
		 "sInfo": "Mostrando _START_ / _END_ de _TOTAL_ registro(s)",
		 "sInfoEmpty": "Mostrando 0 / 0 de 0 registros",
		 "sInfoFiltered": "(filtrado de _MAX_ registros)",
		 "sSearch": "Busca por ID: ",
		 "oPaginate": {
			 "sFirst": "Incio",
			 "sPrevious": "Anterior",
			 "sNext": "Proximo",
			 "sLast": "Ultimo"
			 }
		 },
		 "aaSorting": [[0, 'asc']],
 });
});