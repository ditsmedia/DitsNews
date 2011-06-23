<?php
$group = $modx->newObject('dnGroup', array(
    'name' => $scriptProperties['name'],
    'public' => (int) $scriptProperties['public']
));

// Return values
if ($group->save()) {
	return $modx->error->success('', $group);
} else {
	return $modx->error->failure('');
}