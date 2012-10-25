<?php include('header.php'); ?>
<div class="row">
    <div class="span12 box" style="width: 900px">
        <h2 class="page-header">FAQ</h2>
        <p><em>If you have any additional questions, please post on our <a href="http://facebook.com/studentrnd">Facebook Page</a></em></p>
        <h3>What should I bring?</h3>
        <p>Bring anything that you think will make yourself productive. You'll probably need a laptop. Anything beyond that is up to you!</p>
        <h3>Will there be food?</h3>
        <p>Yep!</p>
        <h3>Can I go even if I don't know anything?</h3>
        <p>Of course! Part of the fun of a hackathon is figurning out things as you go along. We'll also have some great
            <a href="./schedule.html">workshops</a> that you can attend. Or just hang out and have fun!</p>
        <h3>Do I need a group to attend?</h3>
        <p>We'll be forming teams at the event, so you don't need to know anyone beforehand. Just show up! </p>
        <h3>Is it possible to make an app or game in such a short time?</h3>
        <p>Yes! We're constantly amazed by the results of CodeDay events! Check out <a href="http://www.youtube.com/watch?v=-uFSdUq1Gac">S.S. Halfling</a>, and <a href="http://www.youtube.com/watch?v=7G2RpJQ8yko">Running Blind</a>, both games led by students at Redmond HS.</p>
        <h3>Are there any more official rules?</h3>
        <p>Check out <a href="./rules.html">The Official Rules</a>.</p>
        <h3>How big are the teams?</h3>
        <p>We suggest 3-6 people per team. 8 is the absolute max. </p>
        <?php foreach ($this->current_codeday->faqs as $q) : ?>
            <h3><?=$q->question?></h3>
            <p><?=$q->answer?></p>
        <?php endforeach?>
        <style>.eml em:after {content:"contact\40studentrnd.org"}</style>
        <h3>How can I contact you?</h3>
        <p class="eml">Just email <em></em></p>
    </div>
</div>
<?php include('footer.php'); ?>
