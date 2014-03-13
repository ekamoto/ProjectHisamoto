(function() {
    
    base = system.dataService;
    dataService = {};

    dataService.consulta = function(data, successCallback, errorCallback) {
        data = data || {};
        data.acao = 'carregarDadosBo';
        var url = 'ajax.php';
        return base.post(url, data, successCallback, errorCallback);
    };
    
    dataService.consulta2 = function(data, successCallback, errorCallback) {
        data = data || {};
        data.acao = 'carregarDadosBo';
        var url = 'ajax.php';
        return base.post(url, successCallback, errorCallback);
    };
    
    
//    $.each(json, function(key, coisaObjeto) {
//
//			var trArma = $('<tr />');
//			CoisaArma.tabela.append(trArma);
//			trArma.addClass('linhaArma').data('id-seq-arma', coisaObjeto.id_seq_arma);
//			trArma.data('tp_envolvido', coisaObjeto.tp_envolvido);
//			trArma.data('acao', coisaObjeto.acao);
//			var td1 = $('<td />');
//			trArma.append(td1);
//			td1.html(coisaObjeto.id_armac);
//
//			if (!$.isEmptyObject(coisaObjeto.busca)) {
//				td1.append(Coisa.criarBotaoRestricao(coisaObjeto.busca));
//			}
//
//			var td2 = $('<td />');
//			trArma.append(td2);
//			var txtTd2 = "";
//			if ($.trim(coisaObjeto.nm_marca_arma) !== '') {
//				txtTd2 += coisaObjeto.nm_marca_arma;
//			}
//			if (txtTd2 !== '' && $.trim(coisaObjeto.nm_mod_arma) !== '') {
//				txtTd2 += "/";
//			}
//			if ($.trim(coisaObjeto.nm_mod_arma) !== '') {
//				txtTd2 += coisaObjeto.nm_mod_arma;
//			}
//			td2.html(txtTd2);
//			var td3 = $('<td />');
//			trArma.append(td3);
//			td3.html(coisaObjeto.acao);
//			var td4 = $('<td />');
//			trArma.append(td4);
//			td4.html(coisaObjeto.tp_envolvido);
//			var td5 = $('<td />');
//			trArma.append(td5);
//			td5.addClass('colunaAcao');
//			td5.prop('colspan', 2);
//
//			var td5Div = $('<div />');
//			td5.append(td5Div);
//			td5Div.addClass('glyphicon').addClass('glyphicon-pencil').css({'cursor': 'pointer'});
//
//			td5Div.off('click').on('click', function(){
//				CoisaArma.idSeqArma 		= coisaObjeto.id_seq_arma;
//				CoisaArma.tp_env 			= coisaObjeto.tp_env;
//				CoisaArma.id_armac 			= coisaObjeto.id_armac;
//				CoisaArma.ac 				= coisaObjeto.ac;
//				CoisaArma.tp_envolvido 		= $(this).closest('tr.linhaArma').data('tp_envolvido');
//				CoisaArma.acao 				= $(this).closest('tr.linhaArma').data('acao');
//				CoisaArma.carregarModalEditarArma(CoisaArma.tp_env,CoisaArma.callbackCarregarModalEditarArma, null);
//			});
    
})();