<div class="col-xs-3">
    <div class="box notfixed gap">
        <h1>Search</h1>
        <form action="/search">
            <input type="text" name="term" placeholder="Fill in searchterm"/>
            <br/>
            <input class="button" type="submit" value="Search in database"/>
        </form>
    </div>
    <div class="box notfixed gap">
        <h1>Games</h1>
        <ul>
            <?php if($currentProjectCategory->slug=="home"): ?>
                <li><a class="active" href="/project/category/home">Home</a></li>
            <?php else: ?>
                <li><a href="/project/category/home">Home</a></li>
            <?php endif; ?>

            <?php foreach ($projectcategories as $projectcategory): ?>
                <li><a <?php print $projectcategory->slug==$currentProjectCategory->slug ? 'class="active"' : '' ?> href="/project/category/<?php print $projectcategory->slug ?>"><?php print $projectcategory->name ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="box notfixed gap">
        <h1>Ressources</h1>
        <ul>
            <?php if($currentCategory->slug==""): ?>
            <li><a class="active" href="/">Home</a></li>
            <?php else: ?>
            <li><a href="/">Home</a></li>
            <?php endif; ?>

            <?php foreach ($categories as $category): ?>
            <li><a <?php print $category->slug==$currentCategory->slug ? 'class="active"' : '' ?> href="/c/<?php print $category->slug ?>"><?php print $category->name ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="box notfixed gap">
        <h1>Sign Up</h1>
        <p>Register to be able to import directly into your editor.</p>
        <a href="/register" class="button full">Sign Up</a>
    </div>
</div>