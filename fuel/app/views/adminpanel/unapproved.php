<div class="adminpanel-area">
    <div class="col-md-4">
        <?php print $adminpanel_leftcol; ?>
    </div>
    <div class="col-md-8">
        <div class="row box notfixed gap">
            <h1>Unapproved Packages</h1>
            <?php if(count($packages)!=0): ?>
            <?php foreach($packages as $package): ?>
            <div class="col-md-4">
                <div class="box package gap">
                    <h1><?php print $package->name ?></h1>
                    <p><?php print html_entity_decode($package->description); ?></p>
                    <a href="/adminpanel/unapproved/lookat/<?php print $package->id ?>" class="button full">Details</a>
                </div>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
                <div class="notice">
                    Nothing to review here.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>