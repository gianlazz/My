<?php include_once(TEMPLATE_DIR . '/Home/header.php'); ?>
    <div class="row">
        <div class="span12 box">
            <form method="post">
                <div class="userwidget">
                    <div class="avatar-picker pull-left">
                        <img src="<?=$this->user->avatar_url?>" style="width:256px;height:256px;" />
                        <input type="hidden" name="avatar_url" value="<?=$this->user->avatar_url?>" />
                    </div>
                    <table class="table table-bordered pull-right" style="width: auto;">
                        <tr>
                            <td colspan="2">
                                <input type="text" name="username" disabled="true" placeholder="user.name" value="<?=$this->user->username?>" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input type="text" name="email" placeholder="me@example.com" value="<?=$this->user->email?>" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" name="first_name" placeholder="First Name" value="<?=$this->user->first_name?>" />
                            </td>
                            <td>
                                <input type="text" name="last_name" placeholder="Last Name" value="<?=$this->user->last_name?>" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="input-prepend">
                                    <span class="add-on">@</span><input name="twitter_id" type="text" placeholder="Twitter Username" value="<?=$this->user->twitter_id?>">
                                </div>
                                <div class="input-prepend">
                                    <span class="add-on">in/</span><input name="linkedin_id" type="text" placeholder="LinkedIn URL" value="<?=$this->user->linkedin_id?>">
                                </div>
                                <?php if ($this->user->fb_id || (\StudentRND\My\Models\User::current()->is_admin && $this->user->userID !== \StudentRND\My\Models\User::current()->userID)) : ?>
                                    <input disabled="true" type="text" value="http://facebook.com/<?=$this->user->fb_id?>">
                                    <?php if ($this->user->userID === \StudentRND\My\Models\User::current()->userID) : ?>
                                        <a href="<?=\CuteControllers\Router::link('/user/oauth/break?service=fb')?>" class="btn btn-danger">Disconnect</a>
                                    <?php endif; ?>
                                <?php else : ?>
                                    <a href="<?=\CuteControllers\Router::link('/user/oauth?service=fb')?>" class="btn btn-primary">Connect with Facebook</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input type="text" name="phone" placeholder="Phone Number" value="<?=$this->user->phone?>" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input type="text" name="address1" placeholder="Address Line 1" class="input-xxlarge" style="width:360px" value="<?=$this->user->address1?>"/><br />
                                <input type="text" name="address2" placeholder="Address Line 2" class="input-xxlarge" style="width:360px" value="<?=$this->user->address2?>" /><br />
                                <input type="text" name="city" placeholder="City" class="input-medium" value="<?=$this->user->city?>" />
                                <input type="text" name="state" placeholder="State" class="input-small" value="<?=$this->user->state?>" />
                                <input type="text" name="zip" placeholder="Zip" class="input-small" value="<?=$this->user->zip?>" />
                            </td>
                        </tr>
                        <?php if(\StudentRND\My\Models\User::current()->is_admin) : ?>
                            <tr>
                                <td colspan="2">
                                    <label class="checkbox">
                                        Groups:
                                        <select name="groups[]" multiple="multiple">
                                            <?php $current_groups = $this->user->groupIDs; ?>
                                            <?php include(TEMPLATE_DIR . '/Home/widgets/group_options.php'); ?>
                                        </select>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <label class="checkbox">
                                        <input type="checkbox" name="password_reset_required" <?php if($this->user->password_reset_required) echo 'checked="true"'; ?> />
                                        Password Reset Required
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <label class="checkbox">
                                        <input type="checkbox" name="studentrnd_email_enabled" <?php if($this->user->studentrnd_email_enabled) echo 'checked="true"'; ?> />
                                        StudentRND Email
                                    </label>
                                </td>
                            </tr>
                            <?php if (\StudentRND\My\Models\User::current()->userID !== $this->user->userID) : ?>
                                <tr>
                                    <td colspan="2">
                                        <label class="checkbox">
                                            <input type="checkbox" name="is_admin" <?php if($this->user->is_admin) echo 'checked="true"'; ?> />
                                            Admin
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <label class="checkbox">
                                            <input type="checkbox" name="is_disabled" <?php if($this->user->is_disabled) echo 'checked="true"'; ?> />
                                            Account Disabled
                                        </label>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endif; ?>
                        <tr>
                            <td colspan="2">
                                <?php if(\StudentRND\My\Models\User::current()->is_admin && \StudentRND\My\Models\User::current()->userID !== $this->user->userID) : ?>
                                    <a href="<?=\CuteControllers\Router::link('/user/password?username=' . $this->user->username)?>" class="btn btn-inverse">Change Password</a>
                                <?php else : ?>
                                    <a href="<?=\CuteControllers\Router::link('/user/password')?>" class="btn btn-inverse">Change Password</a>
                                <?php endif; ?>
                                <?php if (\StudentRND\My\Models\User::current()->is_admin) : ?>
                                    <a href="<?=\CuteControllers\Router::link('/user/rfids?username=' . $this->user->username)?>" class="btn btn-inverse">RFIDs</a>
                                    <a href="<?=\CuteControllers\Router::link('/user/access_grants?username=' . $this->user->username)?>" class="btn btn-inverse">Physical Access</a>
                                    <?php if (!$this->user->is_disabled) : ?>
                                        <a href="<?=\CuteControllers\Router::link('/user/impersonate?username=' . $this->user->username)?>" class="btn btn-warning">Impersonate</a>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if (\StudentRND\My\Models\User::current()->is_admin || ($this->user->userID == \StudentRND\My\Models\User::current()->userID && count($this->user->applications) > 0)) : ?>
                                    <a href="<?=\CuteControllers\Router::link('/user/applications?username=' . $this->user->username)?>" class="btn btn-inverse">Applications</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <hr style="visibility:hidden;clear:both" />
                <input type="submit" class="btn btn-success" value="Update" />
            </form>
        </div>
    </div>
<?php include_once(TEMPLATE_DIR . '/Home/footer.php'); ?>
