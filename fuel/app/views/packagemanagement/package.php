<div class="pm-area">
    <div class="col-md-4">
        <div class="box notfixed gap">
            <h1>Options</h1>
            <a href="/packagemanagement" class="button full">Submit a package</a>
        </div>
        <div class="box notfixed gap">
            <h1>Your Packages</h1>
            <?php if(count($userpackages)==0): ?>
            <div class="notice">
                You have no packages created yet.
            </div>
            <?php else: ?>
            <ul>
                <?php foreach($userpackages as $package): ?>
                 <li>
                     <span title="<?php print ($package->verified==1) ? 'Verified by admin' : 'Unverified' ?>" class="icon-status <?php print ($package->verified==1) ? 'success' : 'error' ?>"></span>
                     &nbsp;&nbsp;
                     <a class="<?php print ($currentPackage!=null && $currentPackage->id==$package->id) ? 'active' : '' ?>" href="/packagemanagement/<?php print $package->id ?>"><?php print $package->name ?></a>
                 </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-8">
        <div class="box notfixed gap">
            <?php if($currentPackage!=null): ?>
            <h1>Edit a Package</h1>
            <?php else : ?>
            <h1>Submit a Package</h1>
            <?php endif; ?>

            <?php if($currentPackage!=null): ?>
            <?php if($currentPackage->verified==2): ?>
            <div class="notice error">
                This Package was checked by the admin and was rejected due to reason:
            </div>
            <br>
            <div class="notice">
                <h1>Reason for rejection:</h1>
                <div class="line"></div>
                <p><?php print html_entity_decode($currentPackage->rejection_text) ?></p>
            </div>
            <a href="/packagemanagement/<?php print $currentPackage->id ?>/requestapproval" class="button full">Request another approval</a>
            <?php endif; ?>
            <?php if($currentPackage->verified==1): ?>
            <div class="notice success">
                This Package was checked by the admin and will be listed at the store.
            </div>
            <?php endif; ?>
            <?php if($currentPackage->verified==0): ?>
            <div class="notice error">
                This Package wasnt checked by the admin yet.
            </div>
            <?php endif; ?>
            <br>
                <?php if($currentPackage->verified==1): ?>
                    <div class="line smallmargin"></div>
                    <span class="icon-star"></span> <a href="<?php print $currentPackage->getStoreLink(); ?>">See the package in the store!</a>
                    <div class="line smallmargin"></div>
                <?php endif; ?>
            <?php endif; ?>
            <br/>
            <form enctype="multipart/form-data" class="form-horizontal" method="post" role="form" action="<?php if($currentPackage==null) { print '/packagemanagement/submit'; } else { print '/packagemanagement/edit/'.$currentPackage->id; }?>">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" value="<?php if($currentPackage!=null){print $currentPackage->name;} ?>" class="form-control" id="inputEmail3" placeholder="The Package Title">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Version</label>
                    <div class="col-sm-10">
                        <input type="text" name="version" value="<?php if($currentPackage!=null){print $currentPackage->version;} ?>" class="form-control" id="inputEmail3" placeholder="Your package version">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Download Link</label>
                    <div class="col-sm-10">
                        <input type="text" name="url" value="<?php if($currentPackage!=null){print $currentPackage->url;} ?>" class="form-control" id="inputEmail3" placeholder="Your package link">
                        <div id="urlcheck"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">License</label>
                    <div class="col-sm-10">
                        <select name="license" id="">
                            <option <?php if($currentPackage!=null && $currentPackage->license==0){print 'selected="selected"';} ?> value="0">Free (Creative Commons License Public Domain)</option>
                            <option <?php if($currentPackage!=null && $currentPackage->license==1){print 'selected="selected"';} ?> value="1">Free with attribution (Creative Commons License 4.0)</option>
                            <option <?php if($currentPackage!=null && $currentPackage->license==2){print 'selected="selected"';} ?> value="2">Free with attribution and remix with same license (Creative Commons License 4.0, Share-Alike)</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Category</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="category_id" id="">
                            <?php foreach($categories as $category): ?>
                            <option <?php if($currentPackage!=null && $currentPackage->category_id==$category->id){print 'selected="selected"';} ?> value="<?php print $category->id ?>"><?php print $category->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Images</label>
                    <div class="col-sm-10 package-images">
                        <?php if($currentPackage!=null && $currentPackage->pictures != ''): ?>
                        <ul id="sortable">
                            <?php foreach($currentPackage->GetImages() as $picture): ?>
                                <?php if($picture!=''): ?>
                                    <li>
                                        <div data-file="<?php print $picture ?>" class="package-image" style="background:url(/uploads/<?php print str_replace('re_','re_th_',$picture) ?>);background-repeat:no-repeat;">
                                            <a href="/packagemanagement/removeimage/<?php print $picture ?>/<?php print $currentPackage->id ?>">&times;</a>
                                        </div>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                        <input type="file" name="image">
                        <?php if(intval(\Fuel\Core\Input::get('error'))): ?>
                        <div class="notice error">
                            Only png or jpg files can be uploaded.
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10">
                        <span>You have <strong><span id="charsleft">0 / 512</span></strong> of characters left.</span>
                        <textarea class="form-control" name="text" id="text" cols="30" rows="10"><?php if($currentPackage!=null){print $currentPackage->description;} ?></textarea>
                    </div>
                </div>
                <input class="button full" type="submit" name="submit" value="<?php if($currentPackage!=null){print'Update';}else{print'Create';} ?>">
            </form>
        </div>
        <?php if($currentPackage!=null): ?>
        <div class="box notfixed gap dangerzone">
            <h5>Dangerzone</h5>
            <a class="button" href="/packagemanagement/<?php print $currentPackage->id ?>/delete">Delete this Package</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<script src="<?php print \Fuel\Core\Uri::create("assets/js/ckeditor/ckeditor.js"); ?>"></script>
<script>

function UpdateTextLength() {
    var current = CKEDITOR.instances['text'].getData().length,
        maxlength = 1024;

    document.getElementById('charsleft').innerHTML = (maxlength-current) + " / " + maxlength;
}

CKEDITOR.replace( 'text' );
CKEDITOR.instances['text'].on('key', UpdateTextLength);
CKEDITOR.instances['text'].on('paste', UpdateTextLength);
CKEDITOR.instances['text'].on('keypress', UpdateTextLength);
CKEDITOR.instances['text'].on('blur', UpdateTextLength);
CKEDITOR.instances['text'].on('change', UpdateTextLength);
UpdateTextLength();

<?php if($currentPackage!=null): ?>

    $( "#sortable" ).sortable({
        update: function( event, ui ) {
            var images = $( "#sortable .package-image" ),
                imageorder = [];
            images.each(function() {
                var file = $(this).attr('data-file');

                imageorder.push(file);
            });

            $.get('/packagemanagement/updateimageorder/<?php print $currentPackage->id; ?>/'+btoa(JSON.stringify(imageorder)), function(result) {
                console.log(result);
            });
        }
    });

    $.get('/api/v1/checkpackagedownload/<?php print $currentPackage->id ?>', function(data) {
        var check = $('#urlcheck').addClass('notice');
        if(data==1) {
            check.addClass('success');
            check.text("This package can be downloaded directly into the editor.");
        } else {
            check.addClass('error');
            check.text("This package can not be downloaded directly into the editor.");
        }
    });

<?php endif; ?>
</script>