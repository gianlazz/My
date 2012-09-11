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
            <?php endif; ?>
        <?php endif; ?>
    </table>
</div>
<hr style="visibility:hidden;clear:both" />
