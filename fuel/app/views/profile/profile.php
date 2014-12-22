<div class="profile-area">
    <h1>Your Profile</h1>
    <p>Change your Settings to your liking.</p>
    <?php $message = intval(\Fuel\Core\Input::get('message')); ?>
    <?php if($message==1): ?>
    <div class="notice success">
        Your password was changed.
    </div>
    <br>
    <?php endif; ?>
    <?php if($message==2): ?>
    <div class="notice error">
        Your old password was wrong.
    </div>
    <br>
    <?php endif; ?>
    <?php if($message==3): ?>
    <div class="notice error">
        Your new password must atleast be 6 characters long.
    </div>
    <br>
    <?php endif; ?>
    <form class="form-horizontal" method="post" role="form" action="/profile/save">
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10">
                <div class="fixed"><?php print \Auth\Auth::get("email") ?></div>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Username</label>
            <div class="col-sm-10">
                <div class="fixed"><?php print \Auth\Auth::get("username") ?></div>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Display Name</label>
            <div class="col-sm-10">
                <div class="fixed"><?php print \Auth\Auth::get("displayed_name") ?></div>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Old Password</label>
            <div class="col-sm-10">
                <input type="password" name="old_password" class="form-control" id="inputEmail3" placeholder="Old Password">
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">New Password</label>
            <div class="col-sm-10">
                <input type="password" name="new_password" class="form-control" id="inputEmail3" placeholder="New Password">
            </div>
        </div>
        <input class="button full" type="submit" name="submit" value="Save">
    </form>
</div>