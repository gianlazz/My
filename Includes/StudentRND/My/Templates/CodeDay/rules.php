<?php include('header.php'); ?>
<div class="row">
    <div class="span12 box">
        <h1>Rules</h1>

        <h2>1. Programming Language, Tools, and Frameworks:</h2>
        <p>You may use any generally available programming language, tool, API, engine, or framework. This includes both open and closed source
        systems, both freely and commercially available.</p>
        <p>You may use languages, tools, or frameworks which you or your team developed, provided:
            <ul>
                <li>They were generally available for the week prior to the competition</li>
                <li>They are licensed in such a way that all teams can use them free-of-charge</li>
                <li>You made a good faith effort to make them available to other CodeDay teams. (e.g. available without having to guess the link)</li>
                <li>They are not engineered specifically for your team's project</li>
            </ul>
        </p>
        <p>(The purpose of this section is to prevent creating frameworks which provide the general functionality of your project ahead of time.)</p>

        <h2>2. Teams of 1-8 Attendees</h2>
        <p>Teams must be comprised of 1-8 people. No more than 8 people are allowed on a team without the permission of the organizers.</p>
        <p>All team members must have registered prior to their involvement in the competition.</p>

        <h2>3. <?=round(($this->current_codeday->end_date - $this->current_codeday->start_date) / (60*60))?> Hours</h2>
        <p>All work on the project must occur between the end of the kickoff and the beginning of the presentations.</p>
        <p>You can work on the concept for your project before the competition begins, including making notes, sketches, mockups, database and
            inheritance diagrams, and storyboards, however you can not produce any production-ready content before the kickoff. This includes
            PSDs, test cases, engines (except as noted in part 1), sounds, and music. <strong>Plan, don't create.</strong></p>

        <h2>4. Team Profile</h2>
        <p>Teams are required to create and maintain their team profile, which may include individual names, pictures and bios, screenshots, a name
            and description of the project, and a list of technologies used.</p>
        <p>This profile will be used in judging and on the website. You may be penalized for failing to fill it out.</p>

        <h2>5. Assignment of Rights</h2>
        <p>To participate in presentations, you must assign StudentRND rights to use pictures and videos of you and your product for promotional,
            informational, or other uses, for perpetuity, and fee-free.</p>
        <p>StudentRND does <strong>not</strong> require any rights to your code, or final binaries. Your team maintains these rights, although any
            assets, sources, or binaries you choose to provide to StudentRND at the end of the competition are provided under the same license terms
            specified above.</p>

        <h2>6. Paid Assets</h2>
        <p>Commercial assets are allowed so long as they are properly licensed (keeping in mind the terms of the previous section), and provided they
            were not created specifically for the purpose of this project.</p>
        <p>Commissioning contract work is forbidden.</p>

        <h2>7. Judging</h2>
        <p>Awards will be decided by judges who will be selected by the organizers. No teams are allowed into the area where the judging meeting
            takes place.</p>
        <p>The organizers may choose to factor audience opinion into judging, or provide special awards based on it.</p>
        <p>The decision of the judges is considered final.</p>
        <p>The specifics of judging will be made clear to you early into the competition.</p>

        <h2>8. Behavior</h2>
        <p>Attendees are expected to be respectful of the space and of the other attendees. Don't make a mess and clean up after yourself. Harassment,
            both physical and verbal, will not be tolerated.</p>
        <p>Alcohol and drug use are prohibited during the competition.</p>

        <h2>9. Other Rules</h2>
        <p>The organizers will interpret these rules in understanding of the spirit. They have the authority to disqualify or penalize any team for
            any reason.</p>
        <p>Disqualified teams may be required to leave the workspace, and will not receive refunds for ticket costs.</p>
    </div>
</div>
<?php include('footer.php'); ?>
