<?php require(TEMPLATE_DIR . '/Home/header.php'); ?>

<div class="row">
    <div class="span12 box" style="text-align: center">
        <h1><?=$event->name?></h1>
    </div>
</div>

<div class="row">
    <div class="span5 box">
        <h2>Sponsors</h2>
        <ul>
            <?php foreach($event->sponsors as $sponsor) : ?>
                <li>
                    <a
                        href="<?=\CuteControllers\Router::link('/codeday/manage/sponsor/update?eventID=' . $event->eventID
                                . '&sponsorID=' . $sponsor->sponsorID)?>">
                        <?=$sponsor->name?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="<?=\CuteControllers\Router::link('/codeday/manage/sponsor/create?eventID=' . $event->eventID)?>" class="btn">
            Add Sponsor
        </a>
    </div>

    <div class="span5 box">
        <h2>Blocks</h2>
        <ul>
            <?php foreach($event->blocks as $block) : ?>
                <li>
                    <a
                        href="<?=\CuteControllers\Router::link('/codeday/manage/block/update?eventID=' . $event->eventID
                                . '&blockID=' . $block->blockID)?>">
                        <?=$block->title?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="<?=\CuteControllers\Router::link('/codeday/manage/block/create?eventID=' . $event->eventID)?>" class="btn">
            Create Block
        </a>
    </div>

    <div class="span5 box">
        <h2>Schedule</h2>
        <ul>
            <?php foreach($event->get_schedule('schedule') as $item) : ?>
                <li>
                    <a
                        href="<?=\CuteControllers\Router::link('/codeday/manage/schedule/update?eventID=' . $event->eventID
                                . '&scheduleID=' . $item->scheduleID)?>">
                        <?=$item->name?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="<?=\CuteControllers\Router::link('/codeday/manage/schedule/create?eventID=' . $event->eventID)?>" class="btn">
            Add Schedule Entry
        </a>
    </div>

    <div class="span5 box">
        <h2>Workshops</h2>
        <ul>
            <?php foreach($event->get_schedule('workshop') as $item) : ?>
                <li>
                    <a
                        href="<?=\CuteControllers\Router::link('/codeday/manage/schedule/update?eventID=' . $event->eventID
                                . '&scheduleID=' . $item->scheduleID)?>">
                        <?=$item->name?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="<?=\CuteControllers\Router::link('/codeday/manage/schedule/create?eventID=' . $event->eventID)?>" class="btn">
            Add Schedule Entry
        </a>
    </div>

    <div class="span5 box">
        <h2>FAQ</h2>
        <ul>
            <?php foreach($event->faqs as $faq) : ?>
                <li>
                    <a
                        href="<?=\CuteControllers\Router::link('/codeday/manage/faq/update?eventID=' . $event->eventID
                                . '&faqID=' . $faq->faqID)?>">
                        <?=$faq->question?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="<?=\CuteControllers\Router::link('/codeday/manage/faq/create?eventID=' . $event->eventID)?>" class="btn">
            Add Question
        </a>
    </div>
</div>

<?php require(TEMPLATE_DIR . '/Home/footer.php'); ?>
