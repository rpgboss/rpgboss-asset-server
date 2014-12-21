<div class="login-area">
    <h1>Forgot your Password?</h1>
    <?php if($error=="1"): ?>
        <div class="notice error">
            This isnt a correct formatted email adress.
        </div>
    <?php endif; ?>
    <?php if($error=="2"): ?>
        <div class="notice error">
            Theres no user with that email adress.
        </div>
    <?php endif; ?>
    <p>
        Type in your E-mail Adress.<br>
        We will send you an email to change your password.
    </p>
    <form method="post" class="form-horizontal" role="form" action="/forgot-password/attempt">
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10">
                <input name="email" type="email" value="<?php print Session::get_flash('email'); ?>" class="form-control" id="inputEmail3" placeholder="Email">
            </div>
        </div>
        <input class="button full" type="submit" name="submit" value="Reset ">
    </form>
</div>