<div class="login-area">
    <h1>Register an Account</h1>
    <p>
        <strong>Have access to all Resources!</strong>
        <br>
        <br>
        When you register an Account, an email will be send to you.<br>
        You have to verify the account by clicking on an confirmation link in that email.
    </p>
    <form method="post" class="form-horizontal" role="form" action="/register/attempt">
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" id="emailInput" name="email" placeholder="Email">
                <div id="email_success" class="notice success">You can use this email.</div>
                <div id="email_error" class="notice error">This email is already taken.</div>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Username</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="usernameInput" name="username" placeholder="Username">
                <div id="username_success" class="notice success">You can use this username.</div>
                <div id="username_error" class="notice error">This username is already taken.</div>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Password</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="inputEmail3" name="password" placeholder="Password">
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Repeat password</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="inputEmail3" name="password_repeat" placeholder="Repeat password">
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Displayed Name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="displayNameInput" name="displayed_name" placeholder="Your name">
            </div>
        </div>
        <input class="button full" type="submit" name="submit" value="Register">
    </form>
    <?php if($error=="1"): ?>
    <br>
    <div class="notice error">
        Passwort mismatch or wrong email.
    </div>
    <?php endif; ?>
    <?php if($error=="2"): ?>
    <br>
    <div class="notice error">
        Username length must atleast be 6 chars.<br>
        Password length must atleast be 6 chars.<br>
        Repeat Password length must atleast be 6 chars.<br>
    </div>
    <?php endif; ?>
    <?php if($error=="3"): ?>
        <br>
        <div class="notice error">
            Email was already taken.
        </div>
    <?php endif; ?>
    <?php if($error=="4"): ?>
        <br>
        <div class="notice error">
            Username was already taken.
        </div>
    <?php endif; ?>
</div>

<style>
    #name_success,
    #name_error {
        display: none;
    }
    #email_success,
    #email_error {
        display: none;
    }
    #username_success,
    #username_error {
        display: none;
    }
</style>

<script src="<?php print \Fuel\Core\Uri::create('assets/js/register.js') ?>"></script>