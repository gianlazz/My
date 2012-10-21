<?php require(TEMPLATE_DIR . '/Home/header.php'); ?>
    <div class="row">
        <div class="span5 well">
            <h1>My Groups</h1>
            <ul class="groupslist">
                <?php foreach ($this->user->groups as $group) : ?>
                    <?php if ($group->type === 'secret' && !$this->user->is_admin) continue; ?>
                    <li class="in"><a href="<?=\CuteControllers\Router::get_link('/groups/page?group=' . $group->groupID)?>"><?=$group->name?></a></li>
                <?php endforeach; ?>
            </ul>
            <br class="clearfix" />
        </div>

        <div class="span6 box">
            <h1>All Groups</h1>
            <p>This is a list of all groups visible to you.</p>
            <?php if ($this->user->is_admin) : ?>
                <p>Since you are an admin, you will see private and secret groups, as well. These are not generally visible.</p>
            <?php endif; ?>
            <ul class="groupslist">
                <?php foreach ($visible_groups as $group) : ?>
                    <?php if ($group->type === 'secret' && !$this->user->is_admin) continue; ?>
                    <li class=
                    "<?php if ($this->user->has_group($group)) echo 'in';
                          else echo $group->type; ?>"
                    ><a href="<?=\CuteControllers\Router::get_link('/groups/page?group=' . $group->groupID)?>"><?=$group->name?></a></li>
                <?php endforeach; ?>
            </ul>
            <br class="clearfix" />
        </div>
    </div>
<?php require(TEMPLATE_DIR . '/Home/footer.php'); ?>
