<?php

namespace StudentRND\My\Models;

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
            return new self($_SESSION['userID']);
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

    protected $created_at;
    protected $modified_at;

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
                $this->password_reset_required = TRUE;
                $this->invalidate('password_reset_required');
                $this->update();
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
            $email_hash = md5($this->email);
            return "http://www.gravatar.com/avatar/$email_hash?s=256&d=" . urlencode($config['app']['default_avatar']);
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

    /**
     * Updates the password using new password format
     * @param  string $new_password The new password
     */
    public function __set_password($new_password)
    {
        if (strlen($new_password) < 5 || !preg_match('/[0-9\\\\\/\!@#\$%\^&\*\(\)\-_=+\{\};:,<\.>]/', $new_password)) {
            throw new \Exception('Password must be at least 5 characters and contain at least one symbol or number.');
        }
        $salt = hash('md5', time() . rand(0,1000000) . $new_password);
        $this->password = hash('whirlpool', $new_password . $salt) . '$' . $salt;
        $this->password_reset_required = FALSE;
        $this->invalidate('password');
        $this->invalidate('password_reset_required');
    }
}
