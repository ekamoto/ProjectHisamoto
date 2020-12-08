var sistema = {};
sistema.hoje = new Date();
sistema.getDia = function() {
    return this.hoje.getDate();
};
sistema.getMes = function() {
    return this.hoje.getMonth();
};
sistema.getAno = function() {
    return this.hoje.getFullYear();
};
sistema.atualizarPagina = function() {

    bloquearTelaCustomizado(true, "Carregando Dados");

    setTimeout(function() { 

        $("#consultar_conta").trigger("click");
    }, 3000);
    
};
sistema.fecharDialog = function(class_dialog) {
    $('.dialog').dialog({
        autoOpen: false,
        width: 600
    });
    $(class_dialog).dialog('close');
};
sistema.abrirDialog = function(class_dialog, title, width, height) {
    $(class_dialog).dialog({
        autoOpen: false,
        width: width,
        height: height,
        title: title,
        modal: true
    });
    $(class_dialog).dialog('open');
};
sistema.alertMessage = function(titulo, mensagem, type) {
    if(type === "success") {
        toastr.success(mensagem, titulo);
        return;
    }
    if(type === "info") {
        toastr.info(mensagem, titulo);
        return;
    }
    if(type === "warning") {
        toastr.warning(mensagem, titulo);
        return;
    }
    if(type === "error") {
        toastr.error(mensagem, titulo);
        return;
    }
};
var conta = {};
conta.editarConta = function(id_conta) {
    var pago = 0;
    if ($("#pago_" + id_conta).is(":checked")) {
        pago = 1;
    }
    var dados = {
        action: 'editar_conta',
        id: id_conta,
        title: $("#title_" + id_conta).val(),
        qtd_portion: $("#qtd_portion_" + id_conta).val(),
        qtd_portion_payment: $("#qtd_portion_payment_" + id_conta).val(),
        total_value: limpaCampoFloat($("#total_value_" + id_conta).val()),
        portion_value: limpaCampoFloat($("#portion_value_" + id_conta).val()),
        data: $("#data_" + id_conta).val(),
        payment: pago,
        enterprise_id: $("#enterprise_id_list_" + id_conta).val()
    };
    var caminho_ajax = 'controller/contaController.php';
    $.ajax({
        url: caminho_ajax,
        global: true,
        type: "POST",
        data: dados,
        dataType: "json",
        async: false,
        success: function(ret) {
            $("#msg").html('Msg: [' + dados.id + ']' + dados.title + ' ' + ret.msg);
            sistema.alertMessage("Mensagem", dados.title + ' ' + ret.msg, "success");
        }
    });
};
conta.editarContaAtrasada = function(id_conta) {
    var pago = 0;
    if ($("#pago_atrasada_" + id_conta).is(":checked")) {
        pago = 1;
    }
    var dados = {
        action: 'editar_conta',
        id: id_conta,
        title: $("#title_atrasada_" + id_conta).val(),
        qtd_portion: $("#qtd_portion_atrasada_" + id_conta).val(),
        qtd_portion_payment: $("#qtd_portion_payment_atrasada_" + id_conta).val(),
        total_value: limpaCampoFloat($("#total_value_atrasada_" + id_conta).val()),
        portion_value: limpaCampoFloat($("#portion_value_atrasada_" + id_conta).val()),
        data: $("#data_atrasada_" + id_conta).val(),
        payment: pago,
        enterprise_id: $("#enterprise_id_list_atrasada_" + id_conta).val()
    };
    var caminho_ajax = 'controller/contaController.php';
    $.ajax({
        url: caminho_ajax,
        global: true,
        type: "POST",
        data: dados,
        dataType: "json",
        async: false,
        success: function(ret) {
            $("#msg").html('Msg: [' + dados.id + ']' + dados.title + ' ' + ret.msg);
            sistema.alertMessage("Mensagem", dados.title + ' ' + ret.msg, "success");
        }
    });
};
conta.deletarConta = function(id_conta) {
    var dados = {
        action: 'deletar_conta',
        title: $("#title_" + id_conta).val(),
        id: id_conta
    };
    $.ajax({
        url: 'controller/contaController.php',
        global: true,
        type: "POST",
        data: dados,
        dataType: "json",
        async: false,
        success: function(ret) {
            $("#msg").html('Msg: [' + dados.id + ']' + dados.title + ' ' + ret.msg);
            sistema.alertMessage("Mensagem", dados.title + ' ' + ret.msg, "success");
        }
    });
};
conta.addConta = function(dados, callback) {
    $.ajax({
        url: 'controller/contaController.php',
        global: true,
        type: "POST",
        data: dados,
        dataType: "json",
        async: false,
        success: function(ret) {
            $("#msg").html('Msg: ' + ret.msg);
            sistema.alertMessage("Mensagem", ret.msg, "success");
            callback.apply(this);
        }
    });
};
conta.getStatusContas = function() {
    var dados = {action: 'get_status_contas'};
    $.ajax({
        url: 'controller/contaController.php',
        global: true,
        type: "POST",
        data: dados,
        dataType: "json",
        async: false,
        success: function(ret) {
            $("#contas_atrasadas").html('');
            var cont = parseInt(ret.cont, 10);
            if(cont === 0) {
                $("#contas_atrasadas").css("color", "green");
                $("#contas_atrasadas").html("N\u00e3o existe conta atrasada");
            } else {
                $("#contas_atrasadas").css("color", "red");
                $("#contas_atrasadas").css("cursor", "pointer");
                $("#contas_atrasadas").attr("contas", ret.contas);
                $("#contas_atrasadas").html(ret.cont + " conta(s) atrasada(s)");
            }
        }
    });
};
var empresa = {};
empresa.add = function(dados) {
    $.ajax({
        url: 'controller/empresaController.php',
        global: true,
        type: "POST",
        data: dados,
        dataType: "json",
        async: false,
        success: function(ret) {
            sistema.alertMessage("Mensagem", ret.msg, "success");
            $("#msg").html('Msg: ' + ret.msg);
        }
    });
};
function abrirDialog(class_dialog, title, width, height) {
    $(class_dialog).dialog({
        autoOpen: false,
        width: width,
        height: height,
        title: title,
        modal: true
    });
    $(class_dialog).dialog('open');
}
function fecharDialog(class_dialog) {
    $('.dialog').dialog({
        autoOpen: false,
        width: 600
    });
    $(class_dialog).dialog('close');
}
function FormataReais(fld, milSep, decSep, e) {
    var sep = 0;
    var key = '';
    var i = j = 0;
    var len = len2 = 0;
    var strCheck = '0123456789';
    var aux = aux2 = '';
    var whichCode = (window.Event) ? e.which : e.keyCode;
    if (whichCode == 13 || whichCode == 8)
        return true;
    key = String.fromCharCode(whichCode);// Valor para o código da Chave   
    if (strCheck.indexOf(key) == -1)
        return false; // Chave inválida   
    len = fld.value.length;
    for (i = 0; i < len; i++)
        if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep))
            break;
    aux = '';
    for (; i < len; i++)
        if (strCheck.indexOf(fld.value.charAt(i)) != -1)
            aux += fld.value.charAt(i);
    aux += key;
    len = aux.length;
    if (len == 0)
        fld.value = '';
    if (len == 1)
        fld.value = '0' + decSep + '0' + aux;
    if (len == 2)
        fld.value = '0' + decSep + aux;
    if (len > 2) {
        aux2 = '';
        for (j = 0, i = len - 3; i >= 0; i--) {
            if (j == 3) {
                aux2 += milSep;
                j = 0;
            }
            aux2 += aux.charAt(i);
            j++;
        }
        fld.value = '';
        len2 = aux2.length;
        for (i = len2 - 1; i >= 0; i--)
            fld.value += aux2.charAt(i);
        fld.value += decSep + aux.substr(len - 2, len);
    }
    return false;
}
function replaceAll(string, token, newtoken) {
    while (string.indexOf(token) != -1) {
        string = string.replace(token, newtoken);
    }
    return string;
}
function limpaCampoFloat(valor) {
    valor = replaceAll(valor, '.', '');
    valor = valor.replace(',', '.');
    return valor;
}
function SomenteNumero(e) {
    var tecla = (window.event) ? event.keyCode : e.which;
    if ((tecla > 47 && tecla < 58)) {
        return true;
    } else {
        if (tecla == 8 || tecla == 0)
            return true;
        else
            return false;
    }
}
function bloquearTela(bool) {
    if (bool) {
        $.blockUI({
            css: {
                border: 'none',
                padding: '15px',
                backgroundColor: '#000',
                '-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
                opacity: .5,
                color: '#fff'
            },
            message: 'Aguarde...'
        });
    }
    else {
        $.unblockUI();
    }
}

function bloquearTelaCustomizado(bool, string) {
    if (bool) {
        $.blockUI({
            css: {
                border: 'none',
                padding: '15px',
                backgroundColor: '#000',
                '-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
                opacity: .5,
                color: '#fff'
            },
            message: string
        });
    }
    else {
        $.unblockUI();
    }
}

var usuario = {};
usuario.add = function(dados) {
    $.ajax({
        url: 'controller/usuarioController.php',
        global: true,
        type: "POST",
        data: dados,
        dataType: "json",
        async: false,
        success: function(ret) {
            sistema.alertMessage("Mensagem", ret.msg, "success");
            $("#msg").html('Msg: ' + ret.msg);
        }
    });
};
usuario.edit = function(dados, callback) {
    $.ajax({
        url: 'controller/usuarioController.php',
        global: true,
        type: "POST",
        data: dados,
        dataType: "json",
        async: false,
        success: function(ret) {
            sistema.alertMessage("Mensagem", ret.msg, "success");
            $("#msg").html('Msg: ' + ret.msg);
            callback.apply(this);
        }
    });
};
usuario.carregarDados = function(callback) {
    var dados = {'action': 'get_dados_usuario'};
    $.ajax({
        url: 'controller/usuarioController.php',
        global: true,
        type: "POST",
        data: dados,
        dataType: "json",
        async: false,
        success: function(ret) {
            Util.setSelectByValue("group_id_edit", ret.group_id);
            $("#name_edit").val(ret.name);
            $("#username_edit").val(ret.username);
            //$("#password_edit").val(ret.password);
            $("#email_edit").val(ret.email);
            Util.setSelectByValue("sex_edit", ret.sex);
            $("#age_edit").val(ret.age);
            $("#salario_edit").val(ret.salary);
            $("#tel_cel_edit").val(ret.tel_cel);
            $("#tel_resid_edit").val(ret.tel_resid);
            callback.apply(this);
        }
    });
};
var grupo = {};
grupo.add = function(dados) {
    $.ajax({
        url: 'controller/grupoController.php',
        global: true,
        type: "POST",
        data: dados,
        dataType: "json",
        async: false,
        success: function(ret) {
            $("#msg").html('Msg: ' + ret.msg);
            sistema.alertMessage("Mensagem", ret.msg, "success");
        }
    });
};
var note ={};
note.add = function(dados) {
    $.ajax({
        url:"controller/noteController.php",
        type:"POST",
        data:dados,
        dataType:"JSON",
        success: function(json) {
            $("#msg").html('Msg: ' + json.msg);
            sistema.alertMessage("Mensagem", json.msg, "success");
        }
    });
};
note.lerNote = function(id_note, callback) {
    $.ajax({
        url:"controller/noteController.php",
        type:"POST",
        data:{"action":"ler_note", id_note:id_note},
        dataType:"JSON",
        success: function(json) {
            $("#msg").html('Msg: ' + json.msg);
            sistema.alertMessage("Mensagem", json.msg, "success");
            callback.apply(this);
        }
    });
};
note.notLerNote = function(id_note, callback) {
    $.ajax({
        url:"controller/noteController.php",
        type:"POST",
        data:{action:"not_ler_note", id_note:id_note},
        dataType:"JSON",
        success: function(json) {
            sistema.alertMessage("Mensagem", json.msg, "error");
            $("#msg").html('Msg: ' + json.msg);
            callback.apply(this);
        }
    });
};
note.deletarNote = function(idNote, callback) {
    $.ajax({
       url:"controller/noteController.php",
       type:"POST",
       data:{action:"deletarNote", id_note:idNote},
       dataType:"JSON",
       success:function(json) {
           if(json.ok) {
               sistema.alertMessage("Mensagem", "Anotação deletada com sucesso!", "success");
           } else {
               sistema.alertMessage("Mensagem", "Falha ao deletar anotação!", "success");
           }
           callback.apply(this);
       }
    });
};