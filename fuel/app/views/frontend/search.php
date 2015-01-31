<div class="col-xs-9">
    <h1>Search for term: <?php print $term ?></h1>
    <br><br>
    <h4>Assets</h4>

    <div class="box notfixed gap row">
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

    <br/>
    <h4>Projects</h4>


    <div class="box notfixed gap row">
        <?php if($projects!=null): ?>
            <?php foreach($projects as $project): ?>
                <?php print \Fuel\Core\View::forge('layout/project',array('package'=>$project, 'category'=>$project->category)) ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="notice">
                No projects were found.
            </div>
        <?php endif; ?>
    </div>

</div>