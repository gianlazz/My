<?php include(TEMPLATE_DIR . '/Home/header.php') ?>
    <div class="row">
        <div class="span12 box">
            <h2>Add a Plan</h2>
            <form class="form-horizontal" action="<?=\CuteControllers\Router::link('/subscriptions/subscribe')?>" method="post">
                <input type="hidden" name="plan" value="<?=$this->request->request('plan');?>" />
                <fieldset>
                    <div class="control-group">
                        <label class="control-label" for="input02">Amount</label>
                            <div class="controls">
                            <div class="input-prepend">
                                <span class="add-on">$</span>
                                <input type="text" disabled="true" class="input-small" value="<?=$plan->amount?>">
                                every <strong><?=$plan->period?> recurring</strong>.
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="input03">Full Name on Card</label>
                        <div class="controls">
                            <input type="text" name="card-name" class="input-xlarge">
                        </div>
                    </div>


                    <div class="control-group">
                        <label class="control-label" for="input04">Card Number</label>
                        <div class="controls">
                            <input type="text" name="card-number" class="input-medium" placeholder="5555555555555555">
                        </div>
                    </div>


                    <div class="control-group">
                        <label class="control-label" for="input05">CVC Number</label>
                        <div class="controls">
                            <input type="text" name="card-cvc" class="input-mini" placeholder="123">
                        </div>
                    </div>


                    <div class="control-group">
                        <label class="control-label" for="input06">Expiration Date (mm/yyyy)</label>
                        <div class="controls">
                            <input type="text" name="card-expiry-month" class="input-mini" placeholder="MM">
                            / <input type="text" name="card-expiry-year" class="input-mini" placeholder="YYYY">
                        </div>
                    </div>

                    <div class="control-group">
                        <div class="controls">
                            <button type="submit" id="submit-button" class="submit-button btn btn-primary">Start <?=ucfirst($plan->period)?>ly Plan</button>
                        </div>
                    </div>
                </fieldset>
          </form>
        </div>
    </div>
<?php include(TEMPLATE_DIR . '/Home/footer.php') ?>
