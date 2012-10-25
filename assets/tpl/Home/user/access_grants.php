<?php include_once(TEMPLATE_DIR . '/Home/header.php'); ?>
    <div class="row">
        <?php if ($this->user->has_group(new \StudentRND\My\Models\Group(10))) : ?>
            <div class="alert alert-info span12">
                This user is a member of the Key Access group. Any grants made here will have no effect, since the user has 24/7 access already.
            </div>
        <?php endif; ?>
        <div class="span12 box">
            <h1>Grants</h1>
            <table class="table">
                <thead>
                    <tr>
                        <td>Start Date</td>
                        <td>Start Time</td>
                        <td>End Date</td>
                        <td>End Time</td>
                        <td>Actions</td>
                    </tr>
                </thead>
                <?php foreach ($this->user->access_grants as $grant) : ?>
                    <tr>
                        <td><?=date('F j Y', $grant->start)?></td>
                        <td><?=date('h:i:s A', $grant->start)?></td>

                        <td><?=date('F j Y', $grant->end)?></td>
                        <td><?=date('h:i:s A', $grant->end)?></td>

                        <td>
                            <form method="post" action="<?=\CuteControllers\Router::link('/user/access_grants/delete?username=' . $this->user->username)?>">
                                <input type="hidden" name="grantID" value="<?=$grant->grantID?>" />
                                <input type="submit" value="delete" class="btn btn-danger" />
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="span12 box">
            <h2>New</h2>
            <form method="post" action="<?=\CuteControllers\Router::link('/user/access_grants/add?username=' . $this->user->username)?>">
                <?=$form->render()?>
                <input type="submit" value="Add" class="btn btn-primary" />
            </form>
        </div>
    </div>
<?php include_once(TEMPLATE_DIR . '/Home/footer.php'); ?>
