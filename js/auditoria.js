$(document).ready(function(){
	$('.data-table').dataTable({
		'oLanguage':{
			'sSearch':'Pesquisar:',
			'sInfo': '<br/>Mostrando _START_ a _END_ de _TOTAL_ registros'
		},
		'sScrollY':'255px',
		'sScrollX':'95%',
		'sScrollXInner':'95%',
		'bPaginate':false,
		'aaSorting':[[2,'desc']]
	});
	$('dataTable_filter label').first().focus();

});
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
