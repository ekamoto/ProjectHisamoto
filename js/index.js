$(function() {
    noteController = new noteController();
    conta.getStatusContas();
    noteController.getQtdAnotacoesNaoLidas();
    
    $("#titulo").focus();
    $(".data").datepicker();
    $('.data').mask('99/99/9999');
    $('.telefone').mask('(99) 9999-9999');
    $(".editar_conta").on('click', function() {
        if ($(this).attr('id_conta')) {
            conta.editarConta($(this).attr('id_conta'));
        } else {
            sistema.alertMessage("Mensagem", "Falha ao selecionar conta!");
        }
        sistema.atualizarPagina();
    });
    $(".editar_conta_atrasada").on('click', function() {
        if ($(this).attr('id_conta')) {
            conta.editarContaAtrasada($(this).attr('id_conta'));
        } else {
            sistema.alertMessage("Mensagem", "Falha ao editar conta!");
        }
        sistema.atualizarPagina();
    });
    $(".deletar_conta").on('click', function() {
        if ($(this).attr('id_conta')) {
            if (confirm('Tem certeza que deseja deletar a conta: ' + $(this).attr('id_conta'))) {
                conta.deletarConta($(this).attr('id_conta'));
                $("#linha_" + $(this).attr('id_conta')).remove();
            }
        } else {
            sistema.alertMessage("Mensagem", "Falha ao deletar conta!");
        }
        sistema.atualizarPagina();
    });
    $(".deletar_conta_atrasada").on('click', function() {
        if ($(this).attr('id_conta')) {
            if (confirm('Tem certeza que deseja deletar a conta: ' + $(this).attr('id_conta'))) {
                conta.deletarConta($(this).attr('id_conta'));
                $("#linha_" + $(this).attr('id_conta') + "_atrasada").remove();
            }
        } else {
            sistema.alertMessage("Mensagem", "Falha ao selecionar conta!");
        }
        sistema.atualizarPagina();
    });
    $("#seleciona_todos").on('click', function() {
        if ($(this).is(":checked")) {
            $(".seleciona_conta").each(function() {
                this.checked = true;
            });
        } else {
            $(".seleciona_conta").each(function() {
                this.checked = false;
            });
        }
    });
    $(".pagar_tudo").on("click", function() {
        if ($(this).is(":checked")) {
            $(".conta_paga").each(function() {
                this.checked = true;
            });
        } else {
            $(".conta_paga").each(function() {
                this.checked = false;
            });
        }
    });
    $(".pagar_tudo_conta_atrasasa").on("click", function() {
        if ($(this).is(":checked")) {
            $(".conta_paga_atrasada").each(function() {
                this.checked = true;
            });
        } else {
            $(".conta_paga_atrasada").each(function() {
                this.checked = false;
            });
        }
    });
    $("#seleciona_todos_contas_atrasadas").on('click', function() {
        if ($(this).is(":checked")) {
            $(".seleciona_conta_atrasada").each(function() {
                this.checked = true;
            });
        } else {
            $(".seleciona_conta_atrasada").each(function() {
                this.checked = false;
            });
        }
    });
    $("#editar_todos").on('click', function() {
        if (confirm('Tem certeza que deseja editar todas as contas selecionadas?')) {
            $(".seleciona_conta").each(function() {
                if ($(this).is(":checked")) {
                    conta.editarConta($(this).val());
                }
            });
            sistema.atualizarPagina();
        }
    });
    $("#editar_todos_conta_atrasada").on('click', function() {
        if (confirm('Tem certeza que deseja editar todas as contas atrasadas selecionadas?')) {
            $(".seleciona_conta_atrasada").each(function() {
                if ($(this).is(":checked")) {
                    conta.editarContaAtrasada($(this).val());
                }
            });
            sistema.atualizarPagina();
        }
    });
    $("#deletar_todos").on('click', function() {
        if (confirm('Tem certeza que deseja deletar todas as contas selecionadas?')) {
            $(".seleciona_conta").each(function() {
                if ($(this).is(":checked")) {
                    conta.deletarConta($(this).val());
                    $("#linha_" + $(this).val()).remove();
                }
            });
        }
        sistema.atualizarPagina();
    });
    $("#deletar_todos_conta_atrasada").on('click', function() {
        if (confirm('Tem certeza que deseja deletar todas as contas atrasadas selecionadas?')) {
            $(".seleciona_conta_atrasada").each(function() {
                if ($(this).is(":checked")) {
                    conta.deletarConta($(this).val());
                    $("#linha_" + $(this).val()).remove();
                }
            });
        }
        sistema.atualizarPagina();
    });
    $("#id_cadastrar").on('click', function() {
        $(".nova_conta input:text").val("");
        $("#description").val('');
        $('#user_id, #tipo_conta, #enterprise_id').prop('selectedIndex', 0);
        sistema.abrirDialog('.nova_conta', 'Cadastro de conta', 450, 500);
    });
    $(".add_empresa").on('click', function() {
        $(".nova_empresa input:text").val("");
        $("#msg_cadastro_empresa").html('');
        sistema.abrirDialog('.nova_empresa', 'Cadastro de empresa', 450, 200);
    });
    $("#salvar_empresa").on('click', function() {
        var msg_erro = '';
        $("#msg_cadastro_empresa").html('');
        $(".dados_cad_empresa").each(function() {
            if (!$(this).val()) {
                msg_erro += $(this).attr('id') + "<br/>";
            }
        });
        if (msg_erro === '') {
            var dados = {
                action: 'cadastrar_empresa',
                titulo_empresa: $("#titulo_empresa").val(),
                descricao_empresa: $("#descricao_empresa").val()
            };
            empresa.add(dados);
            fecharDialog('.nova_empresa');
            location.href = 'home.php';
        } else {
            $("#msg_cadastro_empresa").html('<strong>Campos obrigat&oacute;rios:<br></strong>' + msg_erro);
        }
    });
    $("#salvar_usuario").on('click', function() {
        var msg_erro = '';
        $("#msg_cadastro_usuario").html('');
        $(".dados_cad_usuario").each(function() {
            if (!$(this).val()) {
                msg_erro += $(this).attr('id') + "<br/>";
            }
        });
        if (msg_erro === '') {
            var dados = {
                action: 'cadastrar_usuario',
                group_id: $("#group_id").val(),
                name: $("#name").val(),
                username: $("#username").val(),
                password: $("#password").val(),
                email: $("#email").val(),
                sex: $("#sex").val(),
                age: $("#age").val(),
                tel_cel: $("#tel_cel").val(),
                tel_resid: $("#tel_resid").val()
            };
            usuario.add(dados);
            fecharDialog('.novo_usuario');
            location.href = 'home.php';
        } else {
            $("#msg_cadastro_usuario").html('<strong>Campos obrigat&oacute;rios:<br></strong>' + msg_erro);
        }
    });
    $("#salvar_grupo").on('click', function() {
        var msg_erro = '';
        $("#msg_cadastro_grupo").html('');
        $(".dados_cad_grupo").each(function() {
            if (!$(this).val()) {
                msg_erro += $(this).attr('id') + "<br/>";
            }
        });
        if (msg_erro === '') {
            var dados = {
                action: 'cadastrar_grupo',
                title: $("#titulo_grupo").val(),
                description: $("#descricao_grupo").val()
            };
            grupo.add(dados);
            fecharDialog('.novo_grupo');
            location.href = 'home.php';
        } else {
            $("#msg_cadastro_grupo").html('<strong>Campos obrigat&oacute;rios:<br></strong>' + msg_erro);
        }
    });
    $("#add_conta").on('click', function() {
        var msg_erro = '';
        $("#msg_cadastro_conta").html('');
        $(".dados_cad_conta").each(function() {
            if (!$(this).val()) {
                msg_erro += $(this).attr('id') + "<br/>";
            }
        });
        if (msg_erro === '') {
            var pago = 0;
            if ($("#payment").is(":checked")) {
                pago = 1;
            }
            var modality = 1;
            $("input:radio[name=modality]").each(function() {
                //Verifica qual está selecionado
                if ($(this).is(":checked"))
                    modality = parseInt($(this).val());
            });

            var dados = {
                action: 'add_conta',
                user_id: $("#user_id").val(),
                type: $("#tipo_conta").val(),
                modality: modality,
                title: $("#title").val(),
                total_value: $("#total_value").val(),
                portion_value: limpaCampoFloat($("#portion_value").val()),
                qtd_portion: $("#qtd_portion").val(),
                qtd_portion_payment: $("#qtd_portion_payment").val(),
                date: $("#date").val(),
                description: $("#description").val(),
                expiration_day: $("#expiration_day").val(),
                payment: pago,
                enterprise_id: $("#enterprise_id").val()
            };
            conta.addConta(dados, function() {
                fecharDialog('.nova_conta');
                $("#form_filtro").submit();
            });
        } else {
            $("#msg_cadastro_conta").html('<strong>Campos obrigat&oacute;rios:<br></strong>' + msg_erro);
        }
    });
    $(".add_usuario").on('click', function() {
        $(".novo_usuario input:text").val("");
        $("#msg_cadastro_usuario").html("");
        $("#description, #password").val("");
        $('#group_id').prop('selectedIndex', 0);
        sistema.abrirDialog(".novo_usuario", "Cadastro de usu\u00e1rio", 450, 500);
    });
    $(".editar_usuario").on('click', function() {
        bloquearTela(true);
        $(".usuario input:text").val("");
        $("#msg_edit_usuario").html("");
        $("#description_edit").val("");
        $('#group_id_edit').prop('selectedIndex', 0);
        usuario.carregarDados(function() {
            bloquearTela(false);
            sistema.abrirDialog(".usuario", "Editar usu\u00e1rio", 450, 500);
        });
    });
    $("#edit_usuario").on('click', function() {
        var msg_erro = '';
        $("#msg_edit_usuario").html('');
        $(".dados_edit_usuario").each(function() {
            if (!$(this).val()) {
                msg_erro += $(this).attr('id') + "<br/>";
            }
        });
        if (msg_erro === '') {
            bloquearTela(true);
            var dados = {
                action: 'editar_usuario',
                group_id: $("#group_id_edit").val(),
                password_edit: $.trim($("#password_edit").val()),
                name: $("#name_edit").val(),
                username: $("#username_edit").val(),
                email: $("#email_edit").val(),
                sex: $("#sex_edit").val(),
                age: $("#age_edit").val(),
                tel_cel: $("#tel_cel_edit").val(),
                tel_resid: $("#tel_resid_edit").val(),
                salary: limpaCampoFloat($("#salario_edit").val())
            };
            usuario.edit(dados, function() {
                bloquearTela(false);
            });
            fecharDialog('.usuario');
            location.href = 'home.php';
        } else {
            $("#msg_edit_usuario").html('<strong>Campos obrigat&oacute;rios:<br></strong>' + msg_erro);
        }
    });
    $(".add_grupo").on('click', function() {
        $(".novo_grupo input:text").val("");
        $("#msg_cadastro_grupo").html("");
        $("#descricao_grupo, #titulo_grupo").val("");
        sistema.abrirDialog(".novo_grupo", "Cadastro de grupo", 450, 200);
    });
    $("#qtd_portion, #portion_value").on("blur", function() {
        var qtd_portion = $("#qtd_portion").val();
        var portion_value = limpaCampoFloat($("#portion_value").val());
        if (qtd_portion && portion_value) {
            $("#total_value").val(qtd_portion * portion_value);
        }
    });
    $("#tipo_conta").on("change", function() {
        // 1-Parcelado, 2-Fixo, 3-Normal
        var tipo = $(this).val();
        if (parseInt(tipo, 10) === 1) {
            $("#qtd_portion, #portion_value").val('');
            $(".quantidade_parcela, .valor_parcela").show();
            $("#total_value").attr('readonly', 'readonly');
        } else {
            $("#qtd_portion, #portion_value").val('');
            $("#qtd_portion_payment").val('0');
            $("#qtd_portion").val("1");
            $("#portion_value").val('  --  ');
            $(".quantidade_parcela, .valor_parcela, .qtd_parc_pagas").hide();
            $("#total_value").attr('readonly', false);
        }
    });
    $("#consultar_conta").on("click", function() {
        $("#content").hide();
    });
    $("#content, .titulos_aba").show();
    $(".deslogar").on("click", function() {
        location.href = "index.php";
    });
    $(".add_note").on("click", function() {
        sistema.abrirDialog(".nova_anotacao", "Cadastro de Anotação", 550, 400);
    });
    $(".deletar_note").on("click", function() {
        var idNote = $(this).attr("id_note");
        if (confirm("Tem certeza que deseja deletar a anotação " + idNote + "?")) {
            note.deletarNote(idNote, function(){
                sistema.atualizarPagina();
            });
        }
    });
    $(".editar_note").on("click", function() {
        bloquearTela(true);
        var idNote = $(this).attr("id_note");
        noteController.carregarDados({action:"carregarDados", idNote:idNote},
        function() {
            sistema.abrirDialog(".ed_anotacao", "Editar Anotação", 550, 400);
            bloquearTela(false);
        });
    });
    $("#edit_anotacao").on("click", function() {
        noteController.editar({
            action: "editarNote",
            descricao_anotacao: $("#edit_descricao_anotacao").val(),
            data_anotacao: $("#edit_data_anotacao").val(),
            id: $("#edit_id_note").val()
        });
    });
    $("#gerar_gasto_padrao").on("click", function() {
        noteController.gerarGastoPadrao({
            action: "gerarGastoPadrao",
            descricao_anotacao: $("#edit_descricao_anotacao").val()
        });
    });
    $("#salvar_anotacao").on("click", function() {        
        var msg_erro = '';
        $("#msg_cadastro_anotacao").html('');
        $(".dados_cad_anotacao").each(function() {
            if (!$(this).val()) {
                msg_erro += $(this).attr('id') + "<br/>";
            }
        });
        if (msg_erro === '') {
            noteController.add({
                action: "cadastrarNote",
                descricao_anotacao: $("#descricao_anotacao").val(),
                data_anotacao: $("#data_anotacao").val()
            });    
        } else {
            $("#msg_cadastro_anotacao").html('<strong>Campos obrigat&oacute;rios:<br></strong>' + msg_erro);
        }
    });
    $(".lido").on("click", function() {
        if ($(this).is(":checked")) {
            note.lerNote($(this).attr("id_note"), function() {
                noteController.getQtdAnotacoesNaoLidas();
            });
        } else {
            note.notLerNote($(this).attr("id_note"), function() {
                noteController.getQtdAnotacoesNaoLidas();
            });
        }
    });
    $("#contas_atrasadas").on("click", function() {
        if (typeof($(this).attr("contas")) !== "undefined") {
            $(".mensagem_sistema").html($(this).attr("contas"));
            sistema.abrirDialog(".mensagem_sistema","Contas atrasadas", 500, 300);
        }
    });
    $(".link_conta_atrasada").on("click", function() {
        alert("asdf");
    });
    $("#anotacoes_nao_lidas").on("click", function() {
        noteController.getAnotacoesNaoLidas(
        {action:"listaAnotacoesNaoLidas"});
        sistema.abrirDialog(".mensagem_sistema","Mensagens não lidas", 500, 300);
    });
    $(".carregando").hide();
});