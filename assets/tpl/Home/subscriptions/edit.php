<?php include(TEMPLATE_DIR . '/Home/header.php') ?>
    <div class="row">
        <div class="span12 box">
            <h2>Plan</h2>
            <?php
                $user_plan = new \StudentRND\My\Models\Mappings\UserPlan(array(
                                                                            'userID' => $this->user->userID,
                                                                            'planID' => $plan->planID
                                                                          ));
            ?>
            <table class="table table-grid table-bordered table-condensed">
                <tr>
                    <td>Name</td>
                    <td><?=$plan->name?></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><?=$plan->description?></td>
                </tr>
                <tr>
                    <td>Member Since</td>
                    <td><?=date('F j, Y', $user_plan->created_at)?></td>
                </tr>
                <tr>
                    <td>Next Automatic Billing Date</td>
                    <?php if ($user_plan->is_cancelled) : ?>
                        <td>Cancelled</td>
                    <?php else : ?>
                        <td><?=date('F j, Y', $user_plan->next_billing_date)?> (<?=$user_plan->days_till_billing_date?> days)</td>
                    <?php endif; ?>
                </tr>
                <?php if (!$user_plan->is_cancelled) : ?>
                    <tr>
                        <td>Options</td>
                        <td>
                            <form action="<?=\CuteControllers\Router::link('/subscriptions/cancel')?>" method="post">
                                <input type="hidden" name="plan" value="<?=$this->request->request('plan')?>" />
                                <button type="submit" class="btn btn-warning" name="delete" value="1"><i class="icon-trash icon-white"></i> Cancel Subscription</button>
                            </form>
                        </td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>
<?php include(TEMPLATE_DIR . '/Home/footer.php') ?>
