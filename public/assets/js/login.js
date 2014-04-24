var apiSecretKey = 'ABC123';

$('#login').on('click', function(e){
    e.preventDefault();
    login();
});

$('#logout').on('click', function(e){
    e.preventDefault();
    logout();
});

$('#sendRequest').on('click', function(e){
    e.preventDefault();
    testRequest();
});

var login = function() {
    var e = $('#email').val();
    var p = $('#password').val();
    $.ajax({
        type: "POST",
        url: "/api/users/login",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        data: JSON.stringify({email: e, password: p}),
        success: function (data) {
            alert('Bienvenu: ' + data.prenom );
            document.location.href = data.start ;
        },
        error: function (errorMessage) {
            alert('status: ' + errorMessage.status + ', message: ' + errorMessage.responseText);
        }
    });
};

var logout = function() {
    $.ajax({
        type: "POST",
        url: "/api/users/logout",
        success: function (data) {
            alert('Bye: ' + data.prenom );
            document.location.href = data.start ;
        },
        error: function (errorMessage) {
            alert('status: ' + errorMessage.status + ', message: ' + errorMessage.responseText);
        }
    });
};

var encrypt = function(){
    var crendentials = JSON.stringify({"email": $('#email').val(), "password": $('#password').val()});
    var rsa = new RSAKey();
    rsa.setPublic(localStorage.PUBLICKEY, localStorage.EXPO);
    var crypted = rsa.encrypt(crendentials);
    return crypted;
};

var testRequest = function() {
    var data = {test: 'test'}
    var timestamp = getMicrotime(true).toString();
    $.ajax({
        type: "GET",
        url: "/api/users/test",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        beforeSend: function (request) {
            request.setRequestHeader('X-MICROTIME', timestamp);
            request.setRequestHeader('X-HASH', getHMAC(readCookie('PUBLIC-KEY'), timestamp));
        },
        data: JSON.stringify(data),
        success: function (data) {
            alert(data.message);
        },
        error: function (errorMessage) {
           if(errorMessage.status == 401)
               alert('Access denied');
        }
    });
};

var readCookie =  function (name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
};

var getHMAC = function(key, timestamp) {
    var hash = CryptoJS.HmacSHA1(key+timestamp, apiSecretKey);
    return hash.toString();
};

var getMicrotime = function (get_as_float) {
    var now = new Date().getTime() / 1000;
    var s = parseInt(now, 10);
    return (get_as_float) ? now : (Math.round((now - s) * 1000) / 1000) + ' ' + s;
};
