<div class="col-xs-12">
    <h1>Search for term: <?php print $term ?></h1>
    <br><br>
    <?php if($packages!=null): ?>
        <?php foreach($packages as $package): ?>
            <?php print \Fuel\Core\View::forge('layout/package',array('package'=>$package, 'category'=>$package->category)) ?>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="notice">
            No packages were found.
        </div>
    <?php endif; ?>
</div>