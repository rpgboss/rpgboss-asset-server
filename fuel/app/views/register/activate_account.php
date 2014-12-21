<div class="box gap notfixed">
    <?php if($valid): ?>
    <div class="notice success">
        Your account have been activated successfully.
    </div>
    <br>
    <a class="button" href="/login">To the loginpage</a>

    <?php else: ?>
    <div class="notice error">
        Your activation key is invalid. <br>
    </div>
    <?php endif; ?>
</div>