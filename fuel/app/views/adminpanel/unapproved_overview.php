<div class="adminpanel-area">
    <div class="col-md-4">
        <?php print $adminpanel_leftcol; ?>
    </div>
    <div class="col-md-8">
        <div class="row box notfixed gap">
            <h1><?php print $currentPackage->name ?></h1>
            <div class="description"><?php print $currentPackage->description ?></div>
            <div class="images-area">
                <div class="row">
                    <?php if($currentPackage->pictures!=''): ?>
                    <?php foreach($currentPackage->getImages() as $image): ?>
                    <div class="image-view col-xs-4">
                        <img src="<?php print \Fuel\Core\Uri::create('uploads/'.$image);?>" alt="">
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <a href="<?php print $currentPackage->url ?>" class="button full">Download Package</a>
        </div>
        <div class="row box notfixed gap">
            <div class="col-md-12">
                <a href="/adminpanel/unapproved/lookat/<?php print $currentPackage->id ?>/approve" class="button full success">Approve Package</a>
            </div>
        </div>
        <?php if( $currentPackage->rejection_text != ''): ?>
        <div class="row box notfixed gap">
            <h1>Package was rejected before due to reason:</h1>
            <p><?php print html_entity_decode($currentPackage->rejection_text) ?></p>
        </div>
        <?php endif; ?>
        <div class="row box notfixed gap">
            <div class="col-md-12">
                <form method="post" action="/adminpanel/unapproved/lookat/<?php print $currentPackage->id ?>/reject">
                    <h1>Reason for Rejection</h1>
                    <span>You have <strong><span id="charsleft">0 / 255</span></strong> of characters left.</span>
                    <textarea name="rejection_text" id="rejection_text" cols="30" rows="10"></textarea>
                    <input class="button full error" type="submit" value="Reject Package">
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<script src="<?php print \Fuel\Core\Uri::create('assets/js/ckeditor/ckeditor.js') ?>"></script>
<script>

function UpdateTextLength() {
    var current = CKEDITOR.instances['rejection_text'].getData().length,
        maxlength = 255;

    document.getElementById('charsleft').innerHTML = (maxlength-current) + " / " + maxlength;
}

CKEDITOR.replace( 'rejection_text' );
CKEDITOR.instances['rejection_text'].on('key', UpdateTextLength);
CKEDITOR.instances['rejection_text'].on('paste', UpdateTextLength);
CKEDITOR.instances['rejection_text'].on('keypress', UpdateTextLength);
CKEDITOR.instances['rejection_text'].on('blur', UpdateTextLength);
CKEDITOR.instances['rejection_text'].on('change', UpdateTextLength);
UpdateTextLength();
</script>