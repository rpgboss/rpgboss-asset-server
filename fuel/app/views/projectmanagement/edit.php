<div class="col-md-12 proj-area">
    <div class="box notfixed gap">
        <div class="line smallmargin"></div>
        <span class="icon-star"></span> <a href="<?php print $currentProject->GetProjectLink(); ?>">See the Project!</a>
        <div class="line smallmargin"></div>
<form enctype="multipart/form-data" class="form-horizontal" method="post" role="form" action="/projectmanagement/<?php print $currentProject->id ?>/update">
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">Name</label>
        <div class="col-sm-10">
            <input type="text" name="name" value="<?php if($currentProject!=null){print $currentProject->name;} ?>" class="form-control" id="inputEmail3" placeholder="The Package Title">
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">Version</label>
        <div class="col-sm-10">
            <input type="text" name="version" value="<?php if($currentProject!=null){print $currentProject->version;} ?>" class="form-control" id="inputEmail3" placeholder="Your project version">
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">Download Link</label>
        <div class="col-sm-10">
            <input type="text" name="url" value="<?php if($currentProject!=null){print $currentProject->downloadlink;} ?>" class="form-control" id="inputEmail3" placeholder="Your project download link">
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">Category</label>
        <div class="col-sm-10">
            <select class="form-control" name="category_id" id="">
                <?php foreach($categories as $category): ?>
                    <option <?php if($currentProject!=null && $currentProject->category_id==$category->id){print 'selected="selected"';} ?> value="<?php print $category->id ?>"><?php print $category->name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">Big Image</label>
        <div class="col-sm-10 package-images">
            <?php if($currentProject!=null && $currentProject->bigpicture != ''): ?>
                <img src="/uploads2/<?php print $currentProject->getMainImage(true); ?>" alt=""/>
            <?php endif; ?>
            <input type="file" name="bigimage">
            <?php if(intval(\Fuel\Core\Input::get('error'))): ?>
                <div class="notice error">
                    Only png or jpg files can be uploaded.
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">Images</label>
        <div class="col-sm-10 package-images">
            <?php if($currentProject!=null && $currentProject->pictures != ''): ?>
                <ul id="sortable">
                    <?php foreach($currentProject->GetImages() as $picture): ?>
                        <?php if($picture!=''): ?>
                            <li>
                                <div data-file="<?php print $picture ?>" class="package-image" style="background:url(/uploads2/<?php print str_replace('re_','re_th_',$picture) ?>);background-repeat:no-repeat;">
                                    <a href="/projectmanagement/removeimage/<?php print $picture ?>/<?php print $currentProject->id ?>">&times;</a>
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
            <textarea class="form-control" name="text" id="text" cols="30" rows="10"><?php if($currentProject!=null){print $currentProject->description;} ?></textarea>
        </div>
    </div>
    <input class="button full" type="submit" name="submit" value="Update">
</form>
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

    $( "#sortable" ).sortable({
        update: function( event, ui ) {
            var images = $( "#sortable .package-image" ),
                imageorder = [];
            images.each(function() {
                var file = $(this).attr('data-file');

                imageorder.push(file);
            });

            $.get('/projectmanagement/updateimageorder/<?php print $currentProject->id; ?>/'+btoa(JSON.stringify(imageorder)), function(result) {
                console.log(result);
            });
        }
    });

</script>