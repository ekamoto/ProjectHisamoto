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
noteView.setListaNotesNaoLidas = function(listaNotes) {
    $(".mensagem_sistema").html("");
    $.each(listaNotes, function(key, note) {
        var fieldset = $("<fieldset/>");
        var legend = $("<legend>");
        var checkbox = $("<input type=\"checkbox\" class=\"lido\" id_note=\"" + note.id + "\"\>");
        legend.append(Util.ajustaDataPortugues(note.date_note));
        fieldset.append(legend);
        fieldset.append(checkbox);
        fieldset.append(note.description);
        $(".mensagem_sistema").append(fieldset);
    });
};