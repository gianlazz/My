<?php include('header.php'); ?>
<div class="row">
    <div class="span12 box">
        <div class="span5">
            <table class="table table-striped">
                <h2> Event Schedule </h2>
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Event</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->current_codeday->get_schedule('schedule') as $schedule_entry) : ?>
                        <tr>
                            <td>
                                <?php if(date('i', $schedule_entry->date) == '00') : ?>
                                    <?=date('g A', $schedule_entry->date)?>
                                <?php else : ?>
                                    <?=date('g:i A', $schedule_entry->date)?>
                                <?php endif; ?>
                            </td>
                            <td><?=$schedule_entry->name?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="span6">
            <h2>Event Workshops</h2>
                <table class="table table-striped">
                <thead>
                    <tr><th>Time</th>       <th>Description</th>    <th>Presenter</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($this->current_codeday->event_workshops as $schedule_entry) : ?>
                        <tr>
                            <td>
                                <?php if(date('i', $schedule_entry->date) == '00') : ?>
                                    <?=date('g A', $schedule_entry->date)?>
                                <?php else : ?>
                                    <?=date('g:i A', $schedule_entry->date)?>
                                <?php endif; ?>
                            </td>
                            <td><?=$schedule_entry->name?></td>
                            <td><?=$schedule_entry->activity_lead?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>
