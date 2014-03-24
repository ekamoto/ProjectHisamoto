Util = {};

// Selected pelo value e pelo texto do option
Util.setSelectByValue = function(eID, val) {
    var ele = document.getElementById(eID);
    for (var ii = 0; ii < ele.length; ii++) {
        if (ele.options[ii].value == val) { //Found!
            ele.options[ii].selected = true;
            return true;
        }
    }
    return false;
};

// Selected pelo texto do option
Util.setSelectByText = function(eID, text) {
    var ele = document.getElementById(eID);
    for (var ii = 0; ii < ele.length; ii++) {
        if (ele.options[ii].text == text) { //Found!
            ele.options[ii].selected = true;
            return true;
        }
    }
    return false;
};

// Cria option dentro de select: value, valor option
Util.criarOption = function(valor, html) {
    var option = $('<option />');
    option.val(valor);
    option.html(html);
    return option;
};

//Retorna valor entre as tags <option>'valor'</option>
Util.getValorTextOption = function(id) {
    var opts = document.getElementById(id);
    return opts.options[opts.selectedIndex].innerText;
};

// Retirar último caracter da string
Util.removeUltimoCaracString = function(string) {
    var result = string.substring(0, (string.length - 1));
    return result;
};

// Retorna o tipo do campo
Util.getTipo = function(val) {
    return typeof(val);
};

// Verifica se valor está na lista
// Ex: Util.inArray(1, [1,2,3,4]);
Util.inArray = function(needle, haystack) {
    var length = haystack.length;
    for (var i = 0; i < length; i++) {
        if (typeof haystack[i] === "object") {
            if (arrayCompare(haystack[i], needle))
                return true;
        } else {
            if (haystack[i] == needle)
                return true;
        }
    }
    return false;
};

// Adiciona mascara em conteúdo
Util.addMascaraString = function(valor, tipo) {
    if (tipo === 'CNPJ') {
        var qtd_carac_cnpj = valor.length;
        if (qtd_carac_cnpj >= 14) {
            var cnpj_formatado = '';
            for (var i = 0; i < qtd_carac_cnpj; i++) {
                if (i === 2 || i === 5) {
                    cnpj_formatado += '.';
                }
                if (i === 8) {
                    cnpj_formatado += '/';
                }
                if (i === 12) {
                    cnpj_formatado += '-';
                }
                cnpj_formatado += valor.charAt(i);
            }
            return cnpj_formatado;
        } else {
            return false;
        }
    } else if (tipo === 'CPF') {
        var qtd_carac_cpf = valor.length;
        if (qtd_carac_cpf === 11) {
            var cpf_formatado = '';
            for (var i = 0; i < qtd_carac_cpf; i++) {
                if (i === 3 || i === 6) {
                    cpf_formatado += '.';
                }
                if (i === 9) {
                    cpf_formatado += '-';
                }
                cpf_formatado += valor.charAt(i);
            }
            return cpf_formatado;
        }
    } else if (tipo === 'CEP') {
        var qtd_carac_cep = valor.length;
        if (qtd_carac_cep === 8) {
            var cep_formatado = '';
            for (var i = 0; i < qtd_carac_cep; i++) {
                if (i === 2) {
                    cep_formatado += '.';
                }
                if (i === 5) {
                    cep_formatado += '-';
                }
                cep_formatado += valor.charAt(i);
            }
            return cep_formatado;
        }
    } else if (tipo === 'FONE') {
        var qtd_carac_fone = valor.length;
        if (qtd_carac_fone === 10) {
            var fone_formatado = '';
            for (var i = 0; i < qtd_carac_fone; i++) {
                if (i === 0) {
                    fone_formatado += '(';
                }
                if (i === 2) {
                    fone_formatado += ') ';
                }
                if (i === 6) {
                    fone_formatado += '-';
                }
                fone_formatado += valor.charAt(i);
            }
            return fone_formatado;
        } else if (qtd_carac_fone === 8) {
            // se não tiver o ddd 
            var fone_formatado = '';
            for (var i = 0; i < qtd_carac_fone; i++) {
                if (i === 4) {
                    fone_formatado += '-';
                }
                fone_formatado += valor.charAt(i);
            }
            return fone_formatado;
        } else if (qtd_carac_fone === 11) {
            var fone_formatado = '';
            for (var i = 0; i < qtd_carac_fone; i++) {
                if (i === 0) {
                    fone_formatado += '(';
                }
                if (i === 2) {
                    fone_formatado += ') ';
                }
                if (i === 7) {
                    fone_formatado += '-';
                }
                fone_formatado += valor.charAt(i);
            }
            return fone_formatado;
        }
    }
    return false;
};

Util.replaceAll = function(string, token, newtoken) {
    while (string.indexOf(token) !== -1) {
        string = string.replace(token, newtoken);
    }
    return string;
};

Util.isUndefined = function(x) {
    return typeof x === "undefined";
};

Util.ajustaDataPortugues = function(data) {
    var dataRetorno = new Date(data.substr(0, 4), data.substr(5, 2) - 1, data.substr(8, 2), data.substr(11, 2), data.substr(14, 2), data.substr(17, 2));
    var dia = parseInt(dataRetorno.getDate(), 10);
    var mes = parseInt(dataRetorno.getMonth(), 10);
    if (dia < 10) {
        dia = "0" + dia;
    }
    mes++;
    if (mes < 10) {
        mes = "0" + mes;
    }
    return dia + "/" + mes + "/" + dataRetorno.getFullYear();
};

Util.dataIniMaiorDataFim = function(dtIni, dtFim) {
    var Compara01 = parseInt(dtIni.split("/")[2].toString() + dtIni.split("/")[1].toString() + dtIni.split("/")[0].toString());
    var Compara02 = parseInt(dtFim.split("/")[2].toString() + dtFim.split("/")[1].toString() + dtFim.split("/")[0].toString());
    if ((Compara01 < Compara02) || (Compara01 === Compara02)) {
        return false;
    } else {
        return true;
    }
};

Util.horaIniMaiorHoraFim = function(hrIni, hrFim) {
    var Compara01 = hrIni.split(":")[0].toString() + hrIni.split(":")[1].toString();
    var Compara02 = hrFim.split(":")[0].toString() + hrFim.split(":")[1].toString();
    Compara01 = parseInt(Compara01, 10);
    Compara02 = parseInt(Compara02, 10);
    if ((Compara01 < Compara02) || (Compara01 === Compara02)) {
        return false;
    } else {
        return true;
    }
};

Util.trim = function(x) {
    return x.replace(/^\s+|\s+$/gm, '');
};

Util.validaHora = function(hora) {
    var vetor = hora.split(":");
    var h = typeof(vetor[0]) !== "undefined" ? vetor[0] : 0;
    var m = typeof(vetor[1]) !== "undefined" ? vetor[1] : 0;
    var s = typeof(vetor[2]) !== "undefined" ? vetor[2] : 0;
    if (h > 23) {
        return false;
    }
    if (m > 59) {
        return false;
    }
    if (s > 59) {
        return false;
    }
    return true;
};

Util.gerarSelect = function(idDestino) {
    var params = new Array();
    params.push({id: 12, valor: "Leandro"});
    params.push({id: 13, valor: "Loco"});
    json = {id: 'testeId', name: 'testeName', options: params};
    var leng = json.options.length;
    //----------------------------------------------------
    var select_element = document.createElement("select");
    select_element.setAttribute("id", json.id);
    select_element.setAttribute("name", json.name);
    for (var i = 0; i < leng; i++) {
        var option_element = document.createElement("option");
        option_element.setAttribute("value", json.options[i]["id"]);
        option_element.appendChild(document.createTextNode(json.options[i]["valor"]));
        if (i === 1) {
            option_element.setAttribute("selected", "selected");
        }
        select_element.appendChild(option_element);
    }
    document.getElementById(idDestino).appendChild(select_element);
};

Util.gerarInput = function(idDestino) {
    var json = {
        id: "testeId",
        name: "testeName",
        type: "text",
        value: "input mágico"
    };
    var input = document.createElement("input");
    input.setAttribute("type", json.type);
    input.setAttribute("id", json.id);
    input.setAttribute("name", json.name);
    input.setAttribute("value", json.value);
    document.getElementById(idDestino).appendChild(input);
};