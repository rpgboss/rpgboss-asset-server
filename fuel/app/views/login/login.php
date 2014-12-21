<div class="login-area">
    <h1>Login into the Asset Server</h1>
    <?php if(Input::get('error')=='1'): ?>
    <div class="notice error">
        Your login attempt was unsuccessful.
    </div>
    <br>
    <?php endif; ?>
    <?php if(Input::get('error')=='2'): ?>
        <div class="notice error">
            This account isnt activated yet.
        </div>
        <br>
    <?php endif; ?>
    <form class="form-horizontal" method="post" role="form" action="/login/attempt">
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Username</label>
            <div class="col-sm-10">
                <input type="text" name="username" class="form-control" id="inputEmail3" placeholder="Username">
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Password</label>
            <div class="col-sm-10">
                <input type="password" name="password" class="form-control" id="inputEmail3" placeholder="Password">
            </div>
        </div>
        <input class="button full" type="submit" name="submit" value="Login">
        <div class="link-area">
            <a href="/forgot-password">Forgot password?</a>
        </div>
    </form>
</div>