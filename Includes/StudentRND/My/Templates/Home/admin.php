<?php include_once('header.php'); ?>
<?php

    $allgroups = new \TinyDb\Collection('\StudentRND\My\Models\Group', \TinyDb\Sql::create()
                                                      ->select('*')
                                                      ->from(\StudentRND\My\Models\Group::$table_name));

    $allplans = new \TinyDb\Collection('\StudentRND\My\Models\Plan', \TinyDb\Sql::create()
                                                      ->select('*')
                                                      ->from(\StudentRND\My\Models\Plan::$table_name));

    $allusers = new \TinyDb\Collection('\StudentRND\My\Models\User', \TinyDb\Sql::create()
                                                       ->select('*')
                                                       ->from(\StudentRND\My\Models\User::$table_name)
                                                      ->order_by('userID DESC'));
?>
    <div class="row">
        <div class="span5 box">
            <h2>Invite User</h2>
            <form action="<?=\CuteControllers\Router::get_link('adduser');?>" method="post" class="form-horizontal">
                <div class="control-group">
                    <label class="control-label" for="email">Email</label>
                    <div class="controls">
                        <input type="text" name="email" placeholder="Email">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="first_name">First Name</label>
                    <div class="controls">
                        <input type="text" name="first_name" placeholder="First Name">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="first_name">Last Name</label>
                    <div class="controls">
                        <input type="text" name="last_name" placeholder="Last Name">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="groups">Groups</label>
                    <div class="controls">
                        <select name="groups" multiple="multiple">
                            <?php include('widgets/group_options.php'); ?>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-green">Invite</button>
            </form>
        </div>
        <div class="span5 box">
            <h2>Existing Users</h2>
            <div class="user-selector">
                <?php foreach ($allusers as $user) : ?>
                    <?php include('widgets/user_preview.php'); ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="span12 box">
            <h2>Groups</h2>

            <table class="table table-grid">
                <thead>
                    <tr>
                        <td>Name</td>
                        <td>Description</td>
                        <td>Group Page</td>
                        <td>Profile Bagde</td>
                        <td>Action</td>
                    </tr>
                </thead>
                <?php foreach ($allgroups as $group) : ?>
                    <form action="<?=\CuteControllers\Router::get_link('/admin/update_group')?>" method="post">
                        <input type="hidden" name="groupID" value="<?=$group->groupID;?>" />
                        <tr>
                            <td><input type="text" name="name" value="<?=$group->name?>" /></td>
                            <td><input type="text" name="description" value="<?=$group->description?>" /></td>
                            <td><input type="checkbox" name="has_group_page" <?php if($group->has_group_page) echo 'checked="checked"'; ?> /></td>
                            <td><input type="checkbox" name="has_profile_badge" <?php if($group->has_profile_badge) echo 'checked="checked"'; ?> /></td>
                            <td><input type="submit" value="Update" /></td>
                        </tr>
                    </form>
                <? endforeach; ?>
                <form action="<?=\CuteControllers\Router::get_link('/admin/create_group')?>" method="post">
                    <tr>
                        <td><input type="text" name="name" /></td>
                        <td><input type="text" name="description" /></td>
                        <td><input type="checkbox" name="has_group_page" /></td>
                        <td><input type="checkbox" name="has_profile_badge" /></td>
                        <td><input type="submit" value="Add" /></td>
                    </tr>
                </form>
            </table>
        </div>
        <div class="span12 box">
            <h2>Plans</h2>
            <table class="table table-grid">
                <thead>
                    <tr>
                        <td>Name</td>
                        <td>Stripe ID</td>
                        <td>Amount</td>
                        <td>Period</td>
                        <td>Group</td>
                        <td>Action</td>
                    </tr>
                </thead>
                <?php foreach ($allplans as $plan) : ?>
                    <form action="<?=\CuteControllers\Router::get_link('/admin/update_plan')?>" method="post">
                        <input type="hidden" name="planID" value="<?=$plan->planID;?>" />
                        <tr>
                            <td><input type="text" name="name" value="<?=$plan->name?>" style="width: 150px"/></td>
                            <td><input type="text" name="stripe_id" value="<?=$plan->stripe_id?>" style="width: 150px"/></td>
                            <td class="input-prepend"><span class="add-on">$</span><input type="text" name="amount" value="<?=$plan->amount?>" style="width:40px"  /></td>
                            <td>
                                <select name="period" style="width:60px;">
                                    <option value="month" <?php if($plan->period == 'month') echo 'selected="true"' ?>>Monthly</option>
                                    <option value="3month" <?php if($plan->period == '3month') echo 'selected="true"' ?>>Every 3 Months</option>
                                    <option value="3month" <?php if($plan->period == '3month') echo 'selected="true"' ?>>Every 6 Months</option>
                                    <option value="year" <?php if($plan->period == 'year') echo 'selected="true"' ?>>Yearly</option>
                                </select>
                            </td>
                            <td>
                                <select name="groupID" style="width: 100px">
                                    <?php foreach ($allgroups as $group) : ?>
                                        <option value="<?=$group->groupID?>" <?php if ($plan->groupID == $group->groupID) echo 'selected="true"'; ?>><?=$group->name?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><input type="submit" value="Update" /></td>
                        </tr>
                    </form>
                <? endforeach; ?>
                <form action="<?=\CuteControllers\Router::get_link('/admin/create_plan')?>" method="post">
                    <tr>
                        <td><input type="text" name="name" style="width: 150px" /></td>
                        <td><input type="text" name="stripe_id" style="width: 150px" /></td>
                        <td class="input-prepend"><span class="add-on">$</span><input type="text" name="amount" style="width:40px" /></td>
                        <td>
                            <select name="period" style="width:60px;">
                                <option value="week">Weekly</option>
                                <option value="month" selected="true">Monthly</option>
                                <option value="year">Yearly</option>
                            </select>
                        </td>
                        <td>
                            <select name="groupID" style="width: 100px">
                                <?php foreach ($allgroups as $group) : ?>
                                    <option value="<?=$group->groupID?>"><?=$group->name?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><input type="submit" value="Add" /></td>
                    </tr>
                </form>
            </table>
        </div>
    </div>
<?php include_once('footer.php'); ?>