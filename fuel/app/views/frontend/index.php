<div class="col-xs-9">
    <h1>Latest Packages</h1>
    <br>
    <div class="notice">
        This is a testrun. There might be problems here and there. If you find something report it one the repository:
        <a href="https://github.com/rpgboss/rpgboss-asset-server/issues">Klick me</a>
    </div>
    <br>
    <?php foreach($categories as $cat): ?>
    <div class="category-list">
        <h2><?php print $cat->name ?></h2>
        <div class="line"></div>
        <div class="row">
            <?php $allpackages = $packages[$cat->slug];
                if(count($allpackages)!=0):
            ?>
            <?php foreach($allpackages as $pckg): ?>
            <?php print \Fuel\Core\View::forge('layout/package',array('package'=>$pckg, 'category'=>$cat)) ?>
            <?php endforeach; ?>
            <?php else: ?>
            <div class="notice">
                There are no packages in this category.
            </div>
            <?php endif; ?>
        </div>

    </div>
    <?php endforeach; ?>
</div>