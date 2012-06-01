<?php foreach ($results as $key => $value): ?>
	<h3><?= $value->level ?></h3>
	<div><?= $value->time ?></div>
	<p><?= $value->body ?></p>
<?php endforeach ?>