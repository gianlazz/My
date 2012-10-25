<?php foreach ($allgroups as $group) : ?>
    <option value="<?=$group->groupID?>" <?php if (isset($current_groups) && in_array($group->groupID, $current_groups)) echo 'selected="true"'?>><?=$group->name;?></option>
<?php endforeach; ?>
