var system = system || {};

system.dataService = (function() {
    var onErrorMessage = "Houve um erro ao processar sua requisição.";

    var get = function(url, callback, onError) {
        return $.get(url, function(data) {
            if (callback != null)
                return callback(data);
            return null;
        }
        )
                .fail(
                function(error) {
                    if (onError != null)
                        return onError(error);
                }
        );
    };

    var getJson = function(url, callback, onError) {
        return $.getJSON(url, function(data) {
            if (callback != null)
                return callback(data);
            return null;
        }
        )
                .fail(
                function(error) {
                    if (onError != null)
                        return onError(error);
                }
        );
    };

    var post = function(url, data, callback, onError) {
        return $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: data,
            success: function(data, textStatus, jqXHR) {
                if (typeof(callback) == 'function')
                    return callback.apply(this, [data, textStatus, jqXHR]);
                return null;
            },
            error: function(errorData) {
                if (typeof(onError) == 'function')
                    return onError(errorData);
            }
        });
    };

    var postSemOverlay = function(url, data, callback, onError) {
        return $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: data,
            semOverlay: true,
            success: function(successData) {
                if (callback != null)
                    return callback(successData);
                return null;
            },
            error: function(errorData) {
                if (onError != null)
                    return onError(errorData);
            },
            beforeSend: function() {
                //$("#overlayLoading").hide();
            },
            complete: function() {
                //$("#overlayLoading").hide();
            }
        });
    };

    return {
        get: get,
        getJson: getJson,
        post: post,
        postSemOverlay: postSemOverlay
    };

})();