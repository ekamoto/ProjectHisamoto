function noteController() {
    this.noteDataService = new noteDataService();
    this.add = function(data) {
        this.noteDataService.add(data, 
        function(json) {
            noteView.setMessage(json);
            sistema.atualizarPagina();
            sistema.fecharDialog(".nova_anotacao");
        });
    };
    this.carregarDados = function(data, callback) {
        this.noteDataService.carregarDados(data, 
        function(json) {
            noteView.setDadosEdit(json);
            callback.apply(this);
        });
    };
    this.editar = function(data) {
        this.noteDataService.aditar(data, 
        function(json) {
            noteView.setMessage(json);
            sistema.atualizarPagina();
            sistema.fecharDialog(".ed_anotacao");
        });
    };
    this.gerarGastoPadrao = function(data) {
        
         var descricao_anotacao = data.descricao_anotacao;
          
         descricao_anotacao += "\nContas:\nLe: \nPri: \nTotal: \n\nConta Sistema:  \nCarro mês seguinte: 538,72 \nGasolina: 240,00 \nCarne+Verdura:150+150 = 300,00";
         $("#edit_descricao_anotacao").val(descricao_anotacao);
    };
    this.getQtdAnotacoesNaoLidas = function(data) {
        this.noteDataService.getQtdAnotacoesNaoLidas({action: "getQtdAnotacoesNaoLidas"}, 
        function(json) {
            noteView.setQtdAnotacaoNaoLida(json.cont);
        });
    };
    this.getAnotacoesNaoLidas = function(data) {
        this.noteDataService.getAnotacoesNaoLidas(data, 
        function(json) {
            noteView.setListaNotesNaoLidas(json);
        });
    };
}