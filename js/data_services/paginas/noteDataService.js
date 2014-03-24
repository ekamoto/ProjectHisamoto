function noteDataService() {
    this.url = "controller/noteController.php";
    this.add = function(data, successCallback, errorCallback) { 
        return base.post(this.url, data, function(json) {
            var params = new Array();
            params.push({ok:json.ok,msg:json.msg});
            successCallback.apply(this, params);
        }, errorCallback);
    };
    this.aditar = function(data, successCallback, errorCallback) {
        return base.post(this.url, data, function(json) {
            var params = new Array();
            params.push({ok:json.ok,msg:json.msg});
            successCallback.apply(this, params);
        }, errorCallback);
    };
    this.carregarDados = function(data, successCallback, errorCallback) {
        return base.post(this.url, data, function(json) {
                var params = new Array();
                params.push({id:json.id, date_note:json.date_note, description:json.description});
                successCallback.apply(this, params);
        }, errorCallback);
    };
    this.getQtdAnotacoesNaoLidas = function(data, successCallback, errorCallback) {
        return base.post(this.url, data, function(json) {
                var params = new Array();
                params.push({cont:json.cont});
                successCallback.apply(this, params);
        }, errorCallback);
    };
}