<div class="box notfixed crumb">
    <a href="/project/category/home">Games</a> &rang; <a href="/project/category/<?php print $project->category->slug ?>"><?php print $project->category->name ?></a> &rang; <?php print $project->name ?>
</div>
<div class="bigimage"></div>
<div class="row contentbox">
    <div class="col-xs-12">
        <div class="box notfixed gap">
            <h4>Description</h4>
            <?php print html_entity_decode($project->description); ?>
            <br/>
            <div class="row informations">
                <div class="col-xs-4">
                    <h4>Genre</h4>
                    <?php print $project->category->name; ?>
                </div>
                <div class="col-xs-4">
                    <h4>Version</h4>
                    <?php print $project->version ?>
                </div>
                <div class="col-xs-4">
                    <h4>From user</h4>
                    <a href="/user/<?php print $project->user->id ?>"> <span class="icon-profile"></span> <?php print $project->user->profile()->displayed_name ?></a>
                </div>
            </div>
            <h4>Screenshots</h4>
            <div class="images row">
            <?php foreach($project->GetImages(true) as $picture): ?>
                <div class="col-xs-2">
                    <a class="imagelink" rel="gal" href="/uploads2/<?php print $project->getImageFromThumb($picture) ?>"><img src="/uploads2/<?php print $picture ?>" alt=""/></a>
                </div>
            <?php endforeach; ?>
            </div>
            <br/>
            <?php if($project->downloadlink==''): ?>
                <div class="notice">Theres no download available for this game yet.</div>
            <?php else: ?>
                <a class="button" href="<?php print $project->downloadlink; ?>">Download the game</a>
            <?php endif; ?>
        </div>
    </div>
</div>
<style>
    .bigimage {
        background: url("/uploads2/<?php print $project->bigpicture ?>") top center no-repeat;
        height: 200px;
    }
</style>
<link rel="stylesheet" href="/assets/css/colorbox.css"/>
<script src="/assets/js/jquery.colorbox-min.js"></script>
<script>
    $('a.imagelink').colorbox({rel:'gal'});
</script>