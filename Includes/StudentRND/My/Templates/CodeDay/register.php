<?php include('header.php'); ?>
<div class="row">
    <div class="span12 block">
       <div style="width:100%; text-align:left;" >
            <iframe
                src="http://www.eventbrite.com/tickets-external?eid=<?=$this->current_codeday->eventbrite_id?>&amp;ref=etckt"
                frameborder="0"
                height="300"
                width="100%"
                vspace="0"
                hspace="0"
                marginheight="5"
                marginwidth="5"
                scrolling="auto"
                allowtransparency="true"></iframe>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>
