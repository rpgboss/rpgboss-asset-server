<html>
<head>

</head>
<body>
<h1>An admin rejected your package.</h1>
<h4>Reason:</h4>
<p><?php print html_entity_decode($package->rejection_text); ?></p>
<p>Click on <a href="<?php print \Fuel\Core\Uri::create('packagemanagement/'.$package->id) ?>">this link</a> to review it.</p>
</body>
</html>