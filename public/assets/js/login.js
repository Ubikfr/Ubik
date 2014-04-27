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
    var url = '/api/test'
    var data = {'var1': $('#var1').val(), 'var2': $('#var2').val(), 'var3': $('#var3').val(), 'var4': $('#var4').val()};
    secureRequest(url, data);
});

var login = function() {
    sessionStorage.setItem('EMAIL', $('#email').val());
    $.ajax({
        type: "POST",
        url: "/api/users/login",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        data: JSON.stringify({blob: crypt_credentials()}),
        success: function (data) {
            alert('Bienvenu: ' + data.prenom );
            sessionStoreUser(data);
            document.location.href = data.start ;
        },
        error: function (errorMessage) {
            if(errorMessage.status == 401)
               alert('Accès refusé');
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
            sessionStorage.clear();
        },
        error: function (errorMessage) {
            alert('status: ' + errorMessage.status + ', message: ' + errorMessage.responseText);
        }
    });
};

var crypt_credentials = function(){
    sessionStorage.setItem('MOD',$('#mod').val());
    sessionStorage.setItem('EXPO',$('#exp').val());
    var crendentials = JSON.stringify({"email": $('#email').val(),
                            "password": $('#password').val(),
                            "token":genToken()});
    var rsa = new RSAKey();
    rsa.setPublic(sessionStorage.MOD, sessionStorage.EXPO);
    var crypted = rsa.encrypt(crendentials);
    return crypted;
};

var crypt_plaintext = function(plaintext){
    var rsa = new RSAKey();
    rsa.setPublic(sessionStorage.MOD, sessionStorage.EXPO);
    var crypted = rsa.encrypt(plaintext);
    return crypted;
};

var sessionStoreUser = function(user){
    sessionStorage.setItem('ID', user.id);
    sessionStorage.setItem('NOM', user.nom);
    sessionStorage.setItem('PRENOM', user.prenom);
    return true;
};

var secureRequest = function(url, data) {
    var datastring = JSON.stringify(data)
    var timestamp = getMicrotime(true).toString();
    $.ajax({
        type: "POST",
        url: url,
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        beforeSend: function (request) {
            request.setRequestHeader('X-MICROTIME', timestamp);
            request.setRequestHeader('X-HMAC', getHMAC(url, datastring, timestamp));
            request.setRequestHeader('X-INFO', crypt_plaintext(sessionStorage.EMAIL+'$'+document.URL));
        },
        data: datastring,
        success: function (data) {
            alert('Succès, le seveur dit: ' + data.text);
        },
        error: function (errorMessage) {
            if(errorMessage.status == 401)
               alert('Accès refusé');
        }
    });
};

var getHMAC = function(url, data, time) {
    var hash = CryptoJS.HmacSHA256(url+data+time, sessionStorage.TOKEN);
    return hash.toString();
};

var getMicrotime = function (get_as_float) {
    var now = new Date().getTime() / 1000;
    var s = parseInt(now, 10);
    return (get_as_float) ? now : (Math.round((now - s) * 1000) / 1000) + ' ' + s;
};

var genToken = function() {
    var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz$£%@#_-*!";
    var string_length = 64;
    var token = '';
    for (var i=0; i<string_length; i++) {
        var rnum = Math.floor(Math.random() * chars.length);
        token += chars.substring(rnum,rnum+1);
    }
    sessionStorage.setItem('TOKEN',token);
    return token;
};
