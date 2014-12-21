<html>
<head>

</head>
<body>
<h1>An user request a package approval.</h1>
<h4><?php print $package->name; ?></h4>
<p>Click on <a href="<?php print \Fuel\Core\Uri::create('adminpanel/unapproved/lookat/'.$package->id) ?>">this link</a> to review it.</p>
</body>
</html>