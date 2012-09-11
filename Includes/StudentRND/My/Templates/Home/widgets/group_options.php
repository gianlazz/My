<?php
    $available_groups = new \TinyDb\Collection('\StudentRND\My\Models\Group', \TinyDb\Sql::create()
                                                      ->select('*')
                                                      ->from(\StudentRND\My\Models\Group::$table_name));
    $available_groups->filter(function($group){
        return !$group->is_associated_with_plan;
    });
?>
<?php foreach ($available_groups as $group) : ?>
    <option value="<?=$group->groupID?>" <?php if (isset($current_groups) && in_array($group->groupID, $current_groups)) echo 'selected="true"'?>><?=$group->name;?></option>
<?php endforeach; ?>
