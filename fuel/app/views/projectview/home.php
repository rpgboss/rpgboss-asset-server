<div class="col-xs-9">
    <h1>Latest Projects</h1>
    <br>
    <div class="notice">
        You can add your own project by using the <a href="/projectmanagement"><span class="icon-game"></span> projectmanagement</a> when you are logged in.
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
                    <?php print \Fuel\Core\View::forge('layout/project',array('package'=>$pckg, 'category'=>$cat)) ?>
                <?php endforeach; ?>
                <?php else: ?>
                    <div class="notice">
                        There are no projects in this category.
                    </div>
                <?php endif; ?>
            </div>

        </div>
    <?php endforeach; ?>
</div>