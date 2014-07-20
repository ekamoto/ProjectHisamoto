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
        checkbox.on("click", function(){
           if (!$(this).is(":checked")) {
                $.ajax({
                    url:"controller/noteController.php",
                    type:"POST",
                    data:{action:"not_ler_note", id_note:$(this).attr("id_note")},
                    dataType:"JSON",
                    success: function(json) {
                        sistema.alertMessage("Mensagem", json.msg, "error");
                        $("#msg").html('Msg: ' + json.msg);
                    }
                });
           } else {
                 $.ajax({
                    url:"controller/noteController.php",
                    type:"POST",
                    data:{"action":"ler_note", id_note:$(this).attr("id_note")},
                    dataType:"JSON",
                    success: function(json) {
                        $("#msg").html('Msg: ' + json.msg);
                        sistema.alertMessage("Mensagem", json.msg, "success");
                    }
                });
           }
           noteController.getQtdAnotacoesNaoLidas();
        });
        legend.append(Util.ajustaDataPortugues(note.date_note));
        legend.css("font-weight", "bold");
        fieldset.append(legend);
        fieldset.append(checkbox);
        var span = $("<span/>");
        span.html("Lido");
        span.css("font-weight", "bold");
        fieldset.append(span);
        fieldset.append("<pre>"+note.description+"</pre>");
        $(".mensagem_sistema").append(fieldset);
    });
};