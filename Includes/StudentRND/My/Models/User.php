<?php

namespace StudentRND\My\Models;

use \StudentRND\My\Models;
use \StudentRND\My\Models\Mappings;

class User extends \TinyDb\Orm
{
    public static $table_name = 'users';
    public static $primary_key = 'userID';

    /**
     * Gets the current user, or redirects to login if none exists
     * @return User Current user
     */
    public static function current()
    {
        if (self::is_logged_in()) {
            $user = new self($_SESSION['userID']);
            if ($user->is_disabled) {
                unset($_SESSION['userID']);
                throw new \CuteControllers\HttpError(401);
            } else {
                return $user;
            }
        } else {
            throw new \CuteControllers\HttpError(401);
        }
    }

    /**
     * Checks if there is a user logged in
     * @return boolean True if the user is logged in, false otherwise
     */
    public static function is_logged_in()
    {
        return isset($_SESSION['userID']);
    }

    public static function is_impersonating()
    {
        return isset($_SESSION['real_userID']);
    }

    public function impersonate()
    {
        $_SESSION['real_userID'] = Models\User::current()->userID;
        $_SESSION['userID'] = $this->userID;
    }

    public static function deimpersonate()
    {
        $_SESSION['userID'] = $_SESSION['real_userID'];
        unset($_SESSION['real_userID']);
    }

    /**
     * Logs in as the current user
     */
    public function login()
    {
        $_SESSION['userID'] = $this->userID;
    }

    /**
     * The user's ID
     * @var int
     */
    protected $userID;

    /**
     * The username
     * @var string
     */
    protected $username;
    /**
     * The password, either md5() or salted whirlpool
     * @var string
     */
    protected $password;
    /**
     * Controls whether the user will be required to reset their password on login
     * @var boolean
     */
    protected $password_reset_required;

    /**
     * The user's first name
     * @var string
     */
    protected $first_name;
    /**
     * The user's last name
     * @var string
     */
    protected $last_name;
    /**
     * The user's non-StudentRND email
     * @var string
     */
    protected $email;

    protected $phone;
    protected $address1;
    protected $address2;
    protected $city;
    protected $state;
    protected $zip;

    protected $fb_id;
    protected $fb_access_token;

    protected $twitter_id;
    protected $linkedin_id;

    /**
     * The user's image
     * @var string
     */
    protected $avatar_url;

    /**
     * True if the user has a StudentRND email
     * @var boolean
     */
    protected $studentrnd_email_enabled;

    protected $is_admin;
    protected $is_disabled;

    protected $created_at;
    protected $modified_at;

    public static function create($username, $first_name, $last_name, $email, $password, $password_reset_required, $studentrnd_email_enabled, $is_admin)
    {
        return parent::create(array(
                              'username' => $username,
                              'first_name' => $first_name,
                              'last_name' => $last_name,
                              'email' => $email,
                              'password' => self::get_salted_password($password),
                              'password_reset_required' => $password_reset_required,
                              'studentrnd_email_enabled' => $studentrnd_email_enabled,
                              'is_admin' => $is_admin,
                              'is_disabled' => FALSE));
    }

    public function __get_plans()
    {
        return new \TinyDb\Collection('\StudentRND\My\Models\Mappings\UserPlan', \TinyDb\Sql::create()
                                             ->select('*')
                                             ->from(Mappings\UserPlan::$table_name)
                                             ->where('userID = ?', $this->userID));
    }

    public function __get_rfids()
    {
        return new \TinyDb\Collection('\StudentRND\My\Models\Rfid', \TinyDb\Sql::create()
                                             ->select('*')
                                             ->from(Models\Rfid::$table_name)
                                             ->where('userID = ?', $this->userID));
    }

    public function __get_access_grants()
    {
        return new \TinyDb\Collection('\StudentRND\My\Models\AccessGrant', \TinyDb\Sql::create()
                                             ->select('*')
                                             ->from(Models\AccessGrant::$table_name)
                                             ->where('userID = ? AND end > NOW()', $this->userID)
                                             ->order_by('start ASC'));
    }

    public function __get_applications()
    {
        return new \TinyDb\Collection('\StudentRND\My\Models\Application', \TinyDb\Sql::create()
                                             ->select('*')
                                             ->from(Models\Application::$table_name)
                                             ->where('userID = ?', $this->userID));
    }

    public function has_plan($plan)
    {
        return ($this->plans->find_one(function($new_plan) use($plan) {
            return $new_plan->planID == $plan->planID;
        }) !== NULL);
    }

    public function has_group(Models\Group $group)
    {
        foreach($this->groups as $my_group)
        {
            if ($my_group->groupID === $group->groupID) {
                return true;
            }
        }

        return false;
    }

    public function __get_groups()
    {
        $collection = new \TinyDb\Collection('\StudentRND\My\Models\Mappings\UserGroup', \TinyDb\Sql::create()
                                             ->select('*')
                                             ->from(Mappings\UserGroup::$table_name)
                                             ->where('userID = ?', $this->userID));

        return $collection->each(function($mapping)
        {
            return $mapping->group;
        });
    }

    public function __get_groupIDs()
    {
        $collection = new \TinyDb\Collection('\StudentRND\My\Models\Mappings\UserGroup', \TinyDb\Sql::create()
                                             ->select('*')
                                             ->from(Mappings\UserGroup::$table_name)
                                             ->where('userID = ?', $this->userID));

        return $collection->each(function($mapping)
        {
            return $mapping->groupID;
        });
    }

    public function __set_groupIDs($new_group_ids)
    {
        $current_group_ids = $this->groupIDs;

        // Calculate added groups
        foreach ($new_group_ids as $new_id)
        {
            if (!in_array($new_id, $current_group_ids)) {
                Mappings\UserGroup::create($this->userID, $new_id);
            }
        }

        // Calculate removed groups
        foreach ($current_group_ids as $current_id)
        {
            if (!in_array($current_id, $new_group_ids)) {
                $mapping = new Mappings\UserGroup(array('userID' => $this->userID, 'groupID' => $current_id));
                $mapping->delete();
            }
        }
    }

    /**
     * Gets the full name (first last).
     * @return string Full name
     */
    public function __get_name()
    {
        return $this->first_name . ' ' . $this->last_name;
    }


    /**
     * Checks if the passed password matches
     * @param  string  $password Password to check
     * @return boolean           True if it matches, false otherwise
     */
    public function validate_password($password)
    {
        // Check if the password is stored in the old format
        if (strpos($this->password, '$') === FALSE) {
            // Validate the oldstyle password, and if it matches, force an update
            if (md5($password) === $this->password) {
                try {
                    // Update to use the new format
                    $this->__set_password($password);
                    $this->invalidate('password');
                    $this->update();
                } catch (\Exception $ex) {
                    // Often we can't set the password because it doesn't meet new security requirements.
                    // If that's the case, we'll force the user to change it.
                    $this->password_reset_required = TRUE;
                    $this->invalidate('password_reset_required');
                    $this->update();
                }
                return TRUE;
            } else {
                return FALSE;
            }
        } else { // Looks like it's a newstyle password
            list($correct_password, $salt) = explode('$', $this->password);
            return (hash("whirlpool", $password . $salt) === $correct_password);
        }
    }

    /**
     * Gets the avatar URL, or a default if no avatar is set
     * @return string Avatar URL
     */
    public function __get_avatar_url()
    {
        global $config;
        if ($this->avatar_url == "") {
            if ($this->fb_id) {
                return "https://graph.facebook.com/{$this->fb_id}/picture?type=large";
            } else {
                $email_hash = md5($this->email);
                return "http://www.gravatar.com/avatar/$email_hash?s=256&d=" . urlencode($config['app']['default_avatar']);
            }
        } else {
            return $this->avatar_url;
        }
    }

    /**
     * Sets the avatar URL
     * @param  string $new New URL
     */
    public function __set_avatar_url($new)
    {
        // Set the URL to NULL if it's a gravatar
        $gravatar = 'http://www.gravatar.com/avatar/';
        if (strlen($new) >= strlen($gravatar) && substr($new, 0, strlen($gravatar)) === $gravatar) {
            $this->avatar_url = NULL;
        } else {
            $this->avatar_url = $new;
        }
        $this->invalidate('avatar_url');
    }

    private static function get_salted_password($password)
    {
        $salt = hash('md5', time() . rand(0,1000000) . $password);
        return hash('whirlpool', $password . $salt) . '$' . $salt;
    }

    /**
     * Updates the password using new password format
     * @param  string $new_password The new password
     */
    public function __set_password($new_password)
    {
        if (strlen($new_password) < 5 || !preg_match('/[0-9\\\\\/\!@#\$%\^&\*\(\)\-_=+\{\};:,<\.>]/', $new_password)) {
            throw new \Exception('Password must be at least 5 characters and contain at least one symbol or number.');
        }
        $this->password = self::get_salted_password($new_password);
        $this->password_reset_required = FALSE;
        $this->invalidate('password');
        $this->invalidate('password_reset_required');
    }
}
