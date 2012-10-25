<?php include(TEMPLATE_DIR . '/Home/header.php') ?>
    <div class="row">
        <div class="span5 box">
            <h2>My Subscriptions</h2>
            <?php if(count($this->user->plans) === 0) : ?>
                <p>You have no subscriptions at the moment.</p>
            <?php else : ?>
                <ul>
                    <?php foreach($this->user->plans as $mapping) : ?>
                        <li>
                            <a href="<?=\CuteControllers\Router::link('/subscriptions/details?plan=' . $mapping->plan->planID)?>">
                                <?=$mapping->plan->name?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <div class="span5 box">
            <h2>Add a Plan</h2>
            <ul>
                <?php foreach ($allplans as $plan) : ?>
                    <?php if ($this->user->has_plan($plan)) continue; ?>
                    <li>
                        <a href="<?=\CuteControllers\Router::link('/subscriptions/details?plan=' . $plan->planID)?>">
                            <?=$plan->name?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php include(TEMPLATE_DIR . '/Home/footer.php') ?>
