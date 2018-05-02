<!DOCTYPE html>
<html>
<head>
    <title>Kruskontroll - Installation</title>
</head>
<body>

<h1> Kruskontroll / <small>Installation</small> </h1>


<style>
     
    html, body, form, fieldset {
        display: flex;
        flex-direction: column;
        font-size: 1.15rem;
    }

    form {
        margin: 0px 5px 0px 5px;
    }

    fieldset {
        margin: 20px 0px 20px 0px;
        border: none;
    }

    label {
        margin-top: 10px;
        color: #333333;
    }

    input {
        margin: 5px 0px 5px 0px;
        width: 100%;
        height: 25px;
        font-size: .9rem;
        padding: 0px 0px 0px 7px;
    }

    #form-button-id {
        background-color: #4CAF50; 
        color: white;
        text-align: center;
        display: inline-block;
        border: none;
        padding: 0px 30px;
        height: 40px
    }
    #form-button-id:hover {
        cursor: pointer;
    }

    .input-install{
        display:flex;
    }

</style>


<form id="form-install-id"
      class="form-install" 
      method="post"
      action="/install/install.php"
      autocomplete="on">


    <fieldset form="form-install-id">
        <legend>MySQL database setup</legend>

    <label for="input-db-name">Database name</label>
    <input id="input-db-name" 
           class="input-install"
           type="text" 
           name="db-name"
           data-placeholder="default: mvc"
           tabindex=0/>


    <label for="input-db-host">Host</label>
    <input id="input-db-host"
           class="input-install"
           type="url" 
           name="db-host"
           data-placeholder="default: http://127.0.0.1"
           tabindex=0/> 

    <label for="input-db-port">Port</label>
    <input id="input-db-port" 
           class="input-install"
           type="number" 
           name="db-port"
           min=1000
           max=99999
           data-placeholder="default: 3306"
           tabindex=0/>

    <label for="input-db-user">User</label>
    <input id="input-db-user"
           class="input-install"
           type="text"
           name="db-user"
           data-placeholder="default: root"
           tabindex=0/> 


    <label for="input-db-password">Password</label>
    <input id="input-db-password" 
           class="input-install"
           type="password" 
           name="db-password"
           data-placeholder="default: <no password>"
           tabindex=0/>

    </fieldset>

    

    <fieldset form="form-install-id">
        <legend>Admin user setup</legend>

    <label for="input-admin-name">Name</label>
    <input id="input-admin-name" 
           class="input-install"
           type="text" 
           name="admin-name"
           data-placeholder="default: admin"
           tabindex=0/> 

    <label for="input-admin-email">Email</label>
    <input id="input-admin-email" 
           class="input-install"
           type="email" 
           name="admin-email"
           data-placeholder="default: admin@kruskontroll.no"
           tabindex=0/> 

    <label for="input-admin-password">Password</label>
    <input id="input-admin-password" 
           class="input-install"
           type="password" 
           name="admin-password"
           data-placeholder="default: 1234"
           tabindex=0/> 

    </fieldset>


    <input id="form-button-id"
           type="submit"
           class="input-install"
           value="Submit"
           tabindex=0/>

</form>

</body>

<script>



window.addEventListener('load', e => {

    // Toggle placeholder on/off on focus/blur
    Array.from(document.getElementsByClassName('input-install')).forEach(el => {

        el.placeholder = el.getAttribute('data-placeholder')
        
        el.addEventListener('focus', e =>  
            e.target.placeholder = '' )

        el.addEventListener('blur', e => 
            e.target.placeholder = e.target.getAttribute('data-placeholder'))
    });
})



</script>
</html>




