$(document).ready(function(){
	$('.data-table').dataTable({
		'oLanguage':{
			'sSearch':'Pesquisar:',
			'sInfo': 'Mostrando _START_ a _END_ de _TOTAL_ registros'
		},
		'sScrollY':'305px',
		'sScrollX':'95%',
		'sScrollXInner':'95%',
		'bPaginate':false,
		'aaSorting':[[0,'asc']]
	});
	$('dataTable_filter label').first().focus();

});
