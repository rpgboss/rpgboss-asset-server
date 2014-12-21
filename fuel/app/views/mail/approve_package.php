<html>
<head>

</head>
<body>
<h1>An admin accepted your package.</h1>
<p>Click on <a href="<?php print \Fuel\Core\Uri::create('c/'.$package->category->slug.'/'.$package->id.'/'.$package->slug) ?>">this link</a> to review it.</p>
</body>
</html>