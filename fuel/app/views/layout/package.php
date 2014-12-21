<div class="col-md-4">
    <a class="package" href="/c/<?php print $category->slug . '/' . $package->id . '/' . $package->slug ?>">
        <div class="box gap">
            <h1><?php print $package->name; ?></h1>
            <div class="image-view">
                <?php if($package->pictures!=''): ?>
                <img src="<?php print \Fuel\Core\Uri::create('uploads/'.$package->GetMainImage()) ?>" alt="">
                <?php endif; ?>
            </div>
        </div>
    </a>
</div>