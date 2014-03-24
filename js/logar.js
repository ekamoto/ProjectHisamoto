var log = {};
log.logar = function(dados) {
    $.ajax({
        url: 'controller/logarController.php',
        global: true,
        type: "POST",
        data: dados,
        dataType: "json",
        async: true,
        success: function(ret) {
            if (ret.ok == 1) {
                location.href = "home.php";
            } else {
                $("#msg_falha_logar").show();
            }
        }
    });
};
$("#user").focus();
$("#password, #user").keypress(function(e) {
    if (e.keyCode == 13) {
        var dados = {action: 'logar', user: $("#user").val(), password: $("#password").val()};
        log.logar(dados);
    }
});
$("#logar").on("click", function() {
    var dados = {action: 'logar', user: $("#user").val(), password: $("#password").val()};
    log.logar(dados);
});