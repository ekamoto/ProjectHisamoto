var noteView = {};
noteView.setMessage = function(json) {
    sistema.alertMessage("Mensagem", json.msg, "success");
};
noteView.setDadosEdit = function(json) {
    $("#edit_id_note").val(json.id);
    $("#edit_descricao_anotacao").val(json.description);
    $("#edit_data_anotacao").val(Util.ajustaDataPortugues(json.date_note));
};
noteView.setQtdAnotacaoNaoLida = function(msg) {
    var cont = parseInt(msg, 10);
    if(cont === 0) {
        $("#anotacoes_nao_lidas").css("color", "green");
        $("#anotacoes_nao_lidas").html("N\u00e3o existem anotações pendentes!");
    } else {
        $("#anotacoes_nao_lidas").css("color", "red");
        $("#anotacoes_nao_lidas").css("cursor", "pointer");
        $("#anotacoes_nao_lidas").attr("contas", cont);
        $("#anotacoes_nao_lidas").html(cont + " anotação(ões) não lida(s)");
    }
};