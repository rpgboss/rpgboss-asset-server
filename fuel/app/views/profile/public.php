<div class="row">
    <div class="col-xs-12">
        <div class="box notfixed gap">
            <h4>User: <?php print $user->profile()->displayed_name ?></h4>
            <div class="line smallmargin"></div>
            <div class="row">
                <div class="col-xs-6">
                    <h4>Published Assets</h4>
                    <?php if(count($packages)==0): ?>
                        <div class="col-xs-12">
                            <div class="notice">
                                This user has not published a package yet.
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach($packages as $package): ?>
                            <?php print \Fuel\Core\View::forge('layout/package',array('package'=>$package, 'category'=>$package->category)) ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="col-xs-6">
                    <h4>Published Projects</h4>
                    <?php if(count($projects)==0): ?>
                        <div class="col-xs-12">
                            <div class="notice">
                                This user has not published a project yet.
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach($projects as $project): ?>
                            <?php print \Fuel\Core\View::forge('layout/project',array('package'=>$project, 'category'=>$project->category)) ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>