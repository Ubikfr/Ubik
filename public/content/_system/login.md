---
titre: Connexion
template: login
access: public
content: html
js:
  /assets/js/vendor/jsbn-1.4.min.js
  /assets/js/vendor/hmac-sha256.js
  /assets/js/login.js
widget:
  publickey
---
<form class="loginform">
    <input id="email" placeholder="E-MAIL:" type="text">
    <input id="password" placeholder="MOT DE PASSE:" type="password">
    <button type="submit" id="login" class="button">Login</button>
</form>
