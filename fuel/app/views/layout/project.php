<div class="col-md-12">
    <a class="project" href="/project/<?php print $package->id . '/' . $package->slug ?>">
        <div class="box notfixed gap">
            <div class="image-view">
                <?php if($package->bigpicture!=''): ?>
                    <img src="<?php print \Fuel\Core\Uri::create('uploads2/'.$package->GetMainImage()) ?>" alt="">
                <?php else: ?>
                    <h1><?php print $package->name; ?></h1>
                <?php endif; ?>
            </div>
        </div>
    </a>
</div>