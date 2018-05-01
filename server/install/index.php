<!DOCTYPE html>
<html>
<head>
    <title>Kruskontroll - Install page</title>
</head>
<body>

<h1>Kruskontroll - Installation</h1>


<style>
    
    html, body,form, fieldset {
        display: flex;
        flex-direction: column;
    }

    .input-install{
        display:flex;
    }

</style>


<form id="form-install-id"
      class="form-install" 
      method="post"
      action="install.php"
      autocomplete="on">


    <fieldset form="form-install-id">
        <legend>MySQL database setup</legend>

    <label for="input-db-name">Database name</label>
    <input id="input-db-name" 
           class="input-install"
           type="text" 
           name="db-name"
           data-placeholder="default: kruskontroll"/>


    <label for="input-db-host">Host</label>
    <input id="input-db-host"
           class="input-install"
           type="text" 
           name="db-host"
           data-placeholder="default: 127.0.0.1"/> 

    <label for="input-db-port">Port</label>
    <input id="input-db-port" 
           class="input-install"
           type="number" 
           name="db-port"
           min=1000
           max=99999
           onfocus="this.placeholder = ''"
           onblur="this.placeholder = 'default: 3306'"
           data-placeholder="default: 3306"/>

    <label for="input-db-user">User</label>
    <input id="input-db-user"
           class="input-install"
           type="text"
           name="db-user"
           data-placeholder="default: root"/> 


    <label for="input-db-password">Password</label>
    <input id="input-db-password" 
           class="input-install"
           type="text" 
           name="db-password"
           data-placeholder="default: null"/>

    </fieldset>

    

    <fieldset form="form-install-id">
        <legend>Admin user setup</legend>

    <label for="input-admin-name">Name</label>
    <input id="input-admin-name" 
           class="input-install"
           type="text" 
           name="admin-name"
           data-placeholder="default: admin"/> 

    <label for="input-admin-email">Email</label>
    <input id="input-admin-email" 
           class="input-install"
           type="text" 
           name="admin-email"
           data-placeholder="default: admin@kruskontroll.no"/> 

    <label for="input-admin-password">Password</label>
    <input id="input-admin-password" 
           class="input-install"
           type="text" 
           name="admin-password"
           data-placeholder="default: 1234"/> 

    </fieldset>



    <button id="form-button-id"
            name="form-button"
            type="submit"
            class="button-install"
           >Submit</button>

</form>

</body>

<script>



window.addEventListener('load', e => {

    Array.from(document.getElementsByClassName('input-install')).forEach(el => {
        el.placeholder = el.getAttribute('data-placeholder')
        el.addEventListener('focus', e =>  e.target.placeholder = '' )
        el.addEventListener('blur', e => e.target.placeholder = e.target.getAttribute('data-placeholder'))
    });
})



</script>
</html>




