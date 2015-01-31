<div class="pm-area">
    <div class="col-md-12">
        <div class="box notfixed gap">
            <form method="post" action="/projectmanagement/create">
                Name <input name="name" placeholder="Name of your project..." type="text"/> <input class="button" type="submit" value="Create"/>
            </form>
        </div>
        <div class="box notfixed gap">
            <h1>Your Projects</h1>
            <?php if(count($userprojects)==0): ?>
                <div class="notice">
                    You have no projects created yet.
                </div>
            <?php else: ?>
                <ul>
                    <?php foreach($userprojects as $project): ?>
                        <li>
                            <span title="<?php print ($project->verified==0) ? 'Verified by admin' : 'Unverified' ?>" class="icon-status <?php print ($project->verified==0) ? 'success' : 'error' ?>"></span>
                            &nbsp;&nbsp;
                            <a class="<?php print ($currentProject!=null && $currentProject->id==$project->id) ? 'active' : '' ?>" href="/projectmanagement/<?php print $project->id ?>"><?php print $project->name ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>