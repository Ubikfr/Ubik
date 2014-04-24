---
titre: Connexion
template: login
access: public
content: html
js:
  /assets/js/vendor/jsbn-1.4.min.js
  /assets/js/login.js
dao:
  spot: publickey
  class: Dao_RsaKey
  fct: publicKey
---
<form class="loginform">
    <input id="email" placeholder="E-MAIL:" type="text">
    <input id="password" placeholder="MOT DE PASSE:" type="password">
    <button type="submit" id="login" class="button">Login</button>
</form>
