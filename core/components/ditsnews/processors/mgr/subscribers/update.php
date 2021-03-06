<?php
$subscriber = $modx->getObject('dnSubscriber', array('id' => $scriptProperties['id']));
if(empty($subscriber)) {
    return $modx->error->failure($modx->lexicon('ditsnews.subscribers.err.nf'));
}

//check for unique email address
$emailcount = $modx->getCount('dnSubscriber', array('id != '.$subscriber->get('id'), 'email' => $scriptProperties['email']));
if($emailcount > 0) {
    return $modx->error->failure($modx->lexicon('ditsnews.subscribers.err.ae'));
}

$subscriber->fromArray(array(
		'firstname' => $scriptProperties['firstname'],
        'lastname'  => $scriptProperties['lastname'],
        'company'   => $scriptProperties['company'],
        'email'     => $scriptProperties['email'],
        'active'    => (int)$scriptProperties['active']
));


if ($subscriber->save()) {
    //remove current groups
    $curGroups = $subscriber->getMany('Groups');
    foreach($curGroups as $curGroup){
        $curGroup->remove();
    }
    
    //add new groups
    $groups = $modx->getCollection('dnGroup');
    if( is_array($groups) ) {
        foreach($groups as $group) {
            $id = $group->get('id');
            if( $scriptProperties['groups_'.$id] ) {
                $newGroup = $modx->newObject('dnGroupSubscribers', array('group' => $id, 'subscriber' => $subscriber->get('id'))  );
                $newGroup->save();
            }
        }
    }

	return $modx->error->success('', $subscriber);
} else {
	return $modx->error->failure($modx->lexicon('ditsnews.subscribers.err.save'));
}