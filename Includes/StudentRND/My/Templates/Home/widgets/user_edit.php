<div class="userwidget">
    <div class="avatar-picker pull-left">
        <img src="<?=$user->avatar_url?>" style="width:256px;height:256px;" />
        <input type="hidden" name="avatar_url" value="<?=$user->avatar_url?>" />
    </div>
    <table class="table table-bordered pull-right" style="width: auto;">
        <tr>
            <td colspan="2">
                <input type="text" name="username" disabled="true" placeholder="user.name" value="<?=$user->username?>" />
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="text" name="email" placeholder="me@example.com" value="<?=$user->email?>" />
            </td>
        </tr>
        <tr>
            <td>
                <input type="text" name="first_name" placeholder="First Name" value="<?=$user->first_name?>" />
            </td>
            <td>
                <input type="text" name="last_name" placeholder="Last Name" value="<?=$user->last_name?>" />
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="input-prepend">
                    <span class="add-on">@</span><input name="twitter_id" type="text" placeholder="Twitter Username" value="<?=$user->twitter_id?>">
                </div>
                <div class="input-prepend">
                    <span class="add-on">in/</span><input name="linkedin_id" type="text" placeholder="LinkedIn URL" value="<?=$user->linkedin_id?>">
                </div>
                <?php if ($this->user->fb_id || (\StudentRND\My\Models\User::current()->is_admin && $this->user->userID !== \StudentRND\My\Models\User::current()->userID)) : ?>
                    <input disabled="true" type="text" value="http://facebook.com/<?=$this->user->fb_id?>">
                    <?php if ($this->user->userID === \StudentRND\My\Models\User::current()->userID) : ?>
                        <a href="<?=\CuteControllers\Router::get_link('/user/deoauth?service=fb')?>" class="btn btn-danger">Disconnect</a>
                    <?php endif; ?>
                <?php else : ?>
                    <a href="<?=\CuteControllers\Router::get_link('/user/oauth?service=fb')?>" class="btn btn-primary">Connect with Facebook</a>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="text" name="phone" placeholder="Phone Number" value="<?=$user->phone?>" />
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="text" name="address1" placeholder="Address Line 1" class="input-xxlarge" style="width:360px" value="<?=$user->address1?>"/><br />
                <input type="text" name="address2" placeholder="Address Line 2" class="input-xxlarge" style="width:360px" value="<?=$user->address2?>" /><br />
                <input type="text" name="city" placeholder="City" class="input-medium" value="<?=$user->city?>" />
                <input type="text" name="state" placeholder="State" class="input-small" value="<?=$user->state?>" />
                <input type="text" name="zip" placeholder="Zip" class="input-small" value="<?=$user->zip?>" />
            </td>
        </tr>
        <?php if(\StudentRND\My\Models\User::current()->is_admin) : ?>
            <tr>
                <td colspan="2">
                    <label class="checkbox">
                        Groups:
                        <select name="groups[]" multiple="multiple">
                            <?php $current_groups = $user->groupIDs; ?>
                            <?php include('group_options.php'); ?>
                        </select>
                    </label>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <label class="checkbox">
                        <input type="checkbox" name="password_reset_required" <?php if($user->password_reset_required) echo 'checked="true"'; ?> />
                        Password Reset Required
                    </label>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <label class="checkbox">
                        <input type="checkbox" name="studentrnd_email_enabled" <?php if($user->studentrnd_email_enabled) echo 'checked="true"'; ?> />
                        StudentRND Email
                    </label>
                </td>
            </tr>
            <?php if (\StudentRND\My\Models\User::current()->userID !== $user->userID) : ?>
                <tr>
                    <td colspan="2">
                        <label class="checkbox">
                            <input type="checkbox" name="is_admin" <?php if($user->is_admin) echo 'checked="true"'; ?> />
                            Admin
                        </label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <label class="checkbox">
                            <input type="checkbox" name="is_disabled" <?php if($user->is_disabled) echo 'checked="true"'; ?> />
                            Account Disabled
                        </label>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endif; ?>
        <tr>
            <td colspan="2">
                <?php if(\StudentRND\My\Models\User::current()->is_admin && \StudentRND\My\Models\User::current()->userID !== $user->userID) : ?>
                    <a href="<?=\CuteControllers\Router::get_link('/user/password?username=' . $user->username)?>" class="btn btn-inverse">Change Password</a>
                    <?php if (!$user->is_admin && !$user->is_disabled) : ?>
                        <a href="<?=\CuteControllers\Router::get_link('/user/impersonate?username=' . $user->username)?>" class="btn btn-warning">Impersonate</a>
                    <?php endif; ?>
                <?php else : ?>
                    <a href="<?=\CuteControllers\Router::get_link('/user/password')?>" class="btn btn-inverse">Change Password</a>
                <?php endif; ?>
                <?php if (\StudentRND\My\Models\User::current()->is_admin) : ?>
                    <a href="<?=\CuteControllers\Router::get_link('/user/rfids?username=' . $user->username)?>" class="btn btn-inverse">RFIDs</a>
                    <a href="<?=\CuteControllers\Router::get_link('/user/access_grants?username=' . $user->username)?>" class="btn btn-inverse">Physical Access</a>
                <?php endif; ?>
                <?php if (\StudentRND\My\Models\User::current()->is_admin || ($this->user->userID == \StudentRND\My\Models\User::current()->userID && count($this->user->applications) > 0)) : ?>
                    <a href="<?=\CuteControllers\Router::get_link('/user/applications?username=' . $user->username)?>" class="btn btn-inverse">Applications</a>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</div>
<hr style="visibility:hidden;clear:both" />
