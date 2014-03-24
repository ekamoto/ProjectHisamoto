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
    this.getQtdAnotacoesNaoLidas = function(data) {
        this.noteDataService.getQtdAnotacoesNaoLidas({action: "getQtdAnotacoesNaoLidas"}, 
        function(json) {
            noteView.setQtdAnotacaoNaoLida(json.cont);
        });
    };
}