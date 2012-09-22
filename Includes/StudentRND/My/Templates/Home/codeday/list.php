<?php require(TEMPLATE_DIR . '/Home/header.php'); ?>

<div class="row">
    <div class="span12 box">
        <h1>CodeDay Events</h1>
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <td>Event</td>
                    <td>Tickets Sold</td>
                    <td>Actions</td>
                </tr>
            </thead>
            <?php foreach ($events as $event) : ?>
                <tr>
                    <td>CodeDay <?=$event->name?>, <?=date('M Y', $event->start_date)?></td>
                    <td><?=$event->tickets_sold?>/<?=$event->tickets_available?></td>
                    <td>
                        <a class="btn" href="<?=\CuteControllers\Router::get_link('/codeday/manage/index?eventID=' . $event->eventID)?>">
                            Edit Event Details
                        </a>
                        <?php if (\StudentRND\My\Models\User::current()->is_admin) : ?>
                            <a class="btn" href="<?=\CuteControllers\Router::get_link('/codeday/admin/update?eventID=' . $event->eventID)?>">
                                Edit Event Config
                            </a>
                        <?php endif ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php if (\StudentRND\My\Models\User::current()->is_admin) : ?>
            <a class="btn" href="<?=\CuteControllers\Router::get_link('/codeday/admin/create')?>">
                New Event
            </a>
        <?php endif ?>
    </div>
</div>

<?php require(TEMPLATE_DIR . '/Home/footer.php'); ?>
