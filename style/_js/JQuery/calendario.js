$(function() {
	$( "#calendario1" ).datepicker({
		changeMonth: true, /** TRUE caso deseje modificar o mês **/
        changeYear: true, /** TRUE caso deseje modificar o ano **/
		numberOfMonths: 1, /** Número de meses a serem mostrados **/
		showOtherMonths: true, /** TRUE caso deseje mostrar dias de outros meses em um mesmo mês **/
        selectOtherMonths: false, /** TRUE caso deseje selecionar dias de outros meses em um mesmo mês **/
		
		dateFormat: 'dd/mm/yy',
		dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
		dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
		dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
		monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
		monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez']
	}); 
});

$(function() {
	$( "#calendario2" ).datepicker({
		changeMonth: true, /** TRUE caso deseje modificar o mês **/
        changeYear: true, /** TRUE caso deseje modificar o ano **/
		numberOfMonths: 1, /** Número de meses a serem mostrados **/
		showOtherMonths: true, /** TRUE caso deseje mostrar dias de outros meses em um mesmo mês **/
        selectOtherMonths: false, /** TRUE caso deseje selecionar dias de outros meses em um mesmo mês **/
		
		dateFormat: 'dd/mm/yy',
		dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
		dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
		dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
		monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
		monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez']
	}); 
});

/** Créditos: http://www.linhadecodigo.com.br/artigo/3604/calendario-em-jquery-criando-calendarios-com-datepicker.aspx **/