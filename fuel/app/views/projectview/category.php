<div class="col-xs-9">
    <h1><?php print $category->name ?></h1>
    <br><br>
    <?php if($packages!=null): ?>
        <?php foreach($packages as $package): ?>
            <?php print \Fuel\Core\View::forge('layout/project',array('package'=>$package, 'category'=>$package->category)) ?>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="notice">
            There are no packages in this category.
        </div>
    <?php endif; ?>
</div>