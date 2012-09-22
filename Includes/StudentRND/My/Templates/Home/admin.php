<?php include_once('header.php'); ?>
    <div class="row">
        <div class="span5 box">
            <h2>Invite User</h2>
            <form action="<?=\CuteControllers\Router::get_link('create_user');?>" method="post" class="form-horizontal">
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
                    <label class="control-label" for="groups">StudentRND Email</label>
                    <div class="controls">
                        <input type="checkbox" name="studentrnd_email_enabled" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="groups">Is Admin</label>
                    <div class="controls">
                        <input type="checkbox" name="is_admin" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="groups">Groups</label>
                    <div class="controls">
                        <select name="groups[]" multiple="multiple">
                            <?php include('widgets/group_options.php'); ?>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-green">Add and Send Email</button>
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

            <table class="table table-grid table-bordered table-condensed">
                <thead>
                    <tr>
                        <td>Name</td>
                        <td>Description</td>
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
                            <td><input type="checkbox" name="has_profile_badge" <?php if($group->has_profile_badge) echo 'checked="checked"'; ?> /></td>
                            <td><button type="submit" class="btn" name="update" value="1"><i class="icon-ok-circle"></i></button>
                                <button type="submit" class="btn" name="delete" value="1"><i class="icon-trash"></i></button></td>
                        </tr>
                    </form>
                <? endforeach; ?>
                <form action="<?=\CuteControllers\Router::get_link('/admin/create_group')?>" method="post">
                    <tr>
                        <td><input type="text" name="name" /></td>
                        <td><input type="text" name="description" /></td>
                        <td><input type="checkbox" name="has_profile_badge" /></td>
                        <td><input type="submit" value="Add" /></td>
                    </tr>
                </form>
            </table>
        </div>
        <div class="span12 box">
            <h2>Plans</h2>
            <table class="table table-grid table-bordered table-condensed">
                <thead>
                    <tr>
                        <td>Name</td>
                        <td>Description</td>
                        <td>Stripe ID</td>
                        <td style="width:100px">Amount</td>
                        <td>Interval</td>
                        <td>Action</td>
                    </tr>
                </thead>
                <?php foreach ($allplans as $plan) : ?>
                    <form action="<?=\CuteControllers\Router::get_link('/admin/update_plan')?>" method="post">
                        <input type="hidden" name="planID" value="<?=$plan->planID;?>" />
                        <tr>
                            <td><input type="text" name="name" value="<?=$plan->name?>" style="width: 150px"/></td>
                            <td><input type="text" name="description" value="<?=$plan->description?>" style="width: 200px"/></td>
                            <td><input type="text" name="stripe_id" value="<?=$plan->stripe_id?>" style="width: 200px"/></td>
                            <td class="input-prepend"><span class="add-on">$</span><input type="text" name="amount" value="<?=$plan->amount?>" style="width:40px"  /></td>
                            <td>
                                <select name="period" style="width:60px;">
                                    <option value="month" <?php if($plan->period == 'month') echo 'selected="true"' ?>>Monthly</option>
                                    <option value="year" <?php if($plan->period == 'year') echo 'selected="true"' ?>>Yearly</option>
                                </select>
                            </td>
                            <td><button type="submit" class="btn" name="update" value="1"><i class="icon-ok-circle"></i></button>
                                <button type="submit" class="btn" name="delete" value="1"><i class="icon-trash"></i></button></td>
                        </tr>
                    </form>
                <? endforeach; ?>
                <form action="<?=\CuteControllers\Router::get_link('/admin/create_plan')?>" method="post">
                    <tr>
                        <td><input type="text" name="name" style="width: 150px" /></td>
                        <td><input type="text" name="description" style="width: 200px" /></td>
                        <td><input type="text" name="stripe_id" style="width: 200px" /></td>
                        <td class="input-prepend"><span class="add-on">$</span><input type="text" name="amount" style="width:40px" /></td>
                        <td>
                            <select name="period" style="width:60px;">
                                <option value="month" selected="true">Monthly</option>
                                <option value="year">Yearly</option>
                            </select>
                        </td>
                        <td><input type="submit" value="Add" /></td>
                    </tr>
                </form>
            </table>
        </div>
    </div>
<?php include_once('footer.php'); ?>
