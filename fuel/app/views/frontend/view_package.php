<div class="col-xs-9">
    <div class="line smallmargin"></div>
    <a class="back" href="<?php print $currentPackage->GetCategoryLink() ?>"> <span class="icon-back"></span> <?php print $currentPackage->category->name ?></a>
    <div class="line smallmargin"></div>
    <h1><?php print $currentPackage->name ?></h1>
    <h4>Version: <?php print $currentPackage->version ?></h4>
    <div class="images-area">
        <div class="row">
            <?php if( $currentPackage->pictures !=''): ?>
            <?php foreach($currentPackage->getImages() as $image): ?>
            <div class="image-view col-xs-4">
                <img src="<?php print \Fuel\Core\Uri::create('uploads/'.$image) ?>" alt="">
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <br><br>
    <p><?php print html_entity_decode( $currentPackage->description ) ?></p>
    <div class="line smallmargin"></div>
    <div class="row infosection">
        <div class="col-xs-6">
            <h4>This package is lincensed as</h4>

            <?php if($currentPackage->license==0): ?>
            <a target="_blank" href="http://creativecommons.org/publicdomain/zero/1.0/"> <span class="icon-license"></span> Free</a>
            <?php endif; ?>
            <?php if($currentPackage->license==1): ?>
            <a target="_blank" href="http://creativecommons.org/licenses/by/4.0/"> <span class="icon-license"></span> Free (author in credits)</a>
            <?php endif; ?>
            <?php if($currentPackage->license==2): ?>
            <a target="_blank" href="http://creativecommons.org/licenses/by-sa/4.0/"> <span class="icon-license"></span> Free (author in credits, remix same license)</a>
            <?php endif; ?>
        </div>
        <div class="col-xs-6">
            <h4>From user</h4>
            <a href="/user/<?php print $currentPackage->user->id ?>"> <span class="icon-profile"></span> <?php print $currentPackage->user->profile()->displayed_name ?></a>
        </div>
    </div>
    <div class="line smallmargin"></div>
    <?php if($isAuthed): ?>
    <a href="<?php print $currentPackage->url ?>" class="button full">Download</a>
    <?php else: ?>
    <a href="/login" class="button full">Login to download</a>
    <?php endif; ?>
    <div class="box notfixed gap comment-section">
        <h1>User Comments</h1>
        <?php if($currentPackage->comments!=null): ?>
        <?php foreach($currentPackage->comments as $comment): ?>
        <div class="comment">
            <h4><?php print $comment->user->profile()->displayed_name ?></h4>
            <p>
                <?php for($i=0; $i < 5; $i++): ?>
                    <?php if($comment->rating > $i ): ?>
                        <span class="icon-star active"></span>
                    <?php else: ?>
                        <span class="icon-star"></span>
                    <?php endif; ?>
                <?php endfor; ?>
            </p>
            <p><?php print html_entity_decode($comment->content) ?></p>
            <div class="line"></div>
            <span class="date"><?php print date('Y-m-d H:i',$comment->created_at) ?></span>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
            <div class="notice">There are no comments for this package yet.</div>
        <?php endif; ?>
    </div>
    <?php if($isAuthed): ?>
    <h3>Comment</h3>
    <form class="comment-section" method="post" action="/comment/add/<?php print $currentPackage->id ?>?redirect=<?php print \Fuel\Core\Uri::current() ?>">
        <span>Rate this package</span>
        <div class="stars-selection">
            <span data-value="1" class="icon-star"></span>
            <span data-value="2" class="icon-star"></span>
            <span data-value="3" class="icon-star"></span>
            <span data-value="4" class="icon-star"></span>
            <span data-value="5" class="icon-star"></span>
        </div>
        <input type="hidden" value="3" id="rating" name="rating">
        <br>
        <span>You have <strong><span id="charsleft">0 / 255</span></strong> of characters left.</span>
        <textarea name="text" id="" cols="30" rows="10"></textarea>
        <br>
        <input type="submit" class="button" value="Make a Comment">
    </form>
    <?php endif; ?>
</div>

<script src="<?php print \Fuel\Core\Uri::create('assets/js/ckeditor/ckeditor.js') ?>"></script>
<script>

function paintStars(value) {
    $('.stars-selection span').removeClass('active');
    for (var i = value - 1; i >= 0; i--) {
        $('.stars-selection span').eq(i).addClass('active');
    };
}

$('.stars-selection span').mouseover(function() {
    var value = $(this).attr('data-value');
    paintStars(value);
    $(this).addClass('active');
}).mouseout(function() {
    var originalVal = $('#rating').val();
    paintStars(originalVal);

}).click(function() {
    var value = $(this).attr('data-value');
    $('#rating').val(value);
    paintStars(value);
});
paintStars(3);

function UpdateTextLength() {
    var current = CKEDITOR.instances['text'].getData().length,
        maxlength = 255;

    document.getElementById('charsleft').innerHTML = (maxlength-current) + " / " + maxlength;
}

CKEDITOR.replace( 'text' );
CKEDITOR.instances['text'].on('key', UpdateTextLength);
CKEDITOR.instances['text'].on('paste', UpdateTextLength);
CKEDITOR.instances['text'].on('keypress', UpdateTextLength);
CKEDITOR.instances['text'].on('blur', UpdateTextLength);
CKEDITOR.instances['text'].on('change', UpdateTextLength);
UpdateTextLength();


</script>