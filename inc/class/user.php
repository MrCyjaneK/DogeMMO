<?php
/** 
  * Class that describe an user.
  * @author Czarek Nakamoto (Cyjan) mrcyjanek@pm.me
  * @example "forDevs/example/inc/class/user.example.php" Example usage of user class
*/

/**
  * user class.
  */
class user {
    /**
      * We use this to access things in database
      */
    private $db;
    /**
      * Database user ID
      */
    public $ID;
    /**
      * Telegram user id, we are going to be telegram-intependant
      * @deprecated
      */
    public $TG_id;
    /**
      * User's language code
      * Default: 'EN'
      */
    public $langcode;
    /**
      * User's experience points
      */
    public $xp;
    /**
      * User's health points
      */
    public $hp;
    /**
      * User's level
      */
    public $lvl;
    /**
      * User's guild ID
      * @todo It's not done in web version, but kond of working in telegram bot.
      */
    public $in_guild_id;
    /**
      * User's team ID
      */
    public $teamid;
    /**
      * Who is user attacking in the big war?
      * If $teamid == $fightfor user will be defending.
      */
    public $fightfor;
    /**
      * 0 - can't fight, 1 - can fight.
      * @todo If user will be offline for 2+ days, set this to 0
      */
    public $canfight;
    /**
      * Game instance token
      * @deprecated This is here for historical reasons, and should not be used anymore.
      */
    public $token;
    /**
      * Inviter ID
      */
    public $inviter;
    /**
      * User's deposit address
      */
    public $depositaddress;
    /**
      * User's balance
      */
    public $balance;
    /**
      * User's username
      */
    public $username;

    /**
      * User's attack points
      */
    public $attack;
    /**
      * User's defense points
      */
    public $defense;
    /**
      * User's weight
      */
    public $weight;
    /**
      * User's speed
      */
    public $speed;

    /**
      * What version is used?
      *
      * 1 - Use telegram_id to get user
      * 2 - Use ID to get user
      * 3 - Use user ID
      *
      * @deprecated 1 - Use telegram_id to get user
      *
      */
    private $version;
    /**
      * List of allowed keys to be saved in updateDb and save function, set in __construct
      */
    private $allowed_keys;
    /**
      * Construct user object.
      *
      * @param int userid for version=1 it is telegram_id (depreacted) and for version=2 it's ID
      * @param int version 1 for telegram_id and 2 for ID
      */
    public function __construct($userid, $version=1) {
        // Step 1, declare database
        include getcwd() . '/inc/db.php';
        $this->db = $db;
        $this->version = $version;
        $this->setUser($userid);
        $this->setPower();
        $this->allowed_keys = "TG_id,username,langcode,xp,hp,in_guild_id,fightfor,balance";
    }

    /**
      * Fetch all items owned by user
      */
    public function items() {
        $items = $this->db->prepare(
            "SELECT * FROM `user_items` WHERE `owned_by`=:owned_by"
        );
        $items->bindParam(":owned_by", $this->ID);
        $items->execute();
        return $items->fetchAll();
    }
    /**
      * Update user object with user data
      *
      * @param int $userid $userid given in __construct
      */
    private function setUser($userid) {
        $version = $this->version;
        //echo $version;
        //echo $userid;
        $user = [];
        // Fetch user with TG_id equal to $userid
        if ($version == 1) {
            $query = $this->db->prepare("SELECT * FROM `users` WHERE TG_id=:TG_id");
            $query->bindParam(":TG_id",$userid);
            $query->execute();
        }
        // Fetch user with ID equal to $userid
        if ($version == 2 || $version == 3) {
            $query = $this->db->prepare("SELECT * FROM `users` WHERE ID=:ID");
            $query->bindParam(":ID",$userid);
            $query->execute();
        }
        $user = $query->fetchAll();
        if ($user == []) {
            $query = $this->db->prepare("SELECT * FROM `users` WHERE ID=:ID");
            $query->bindParam(":ID",$userid);
            $query->execute();
            $user = $query->fetchAll();
        }
        if ($user == []) {
            throw new Exception('404|User doesn\'t exist');
        }
        $user = $user[0];
        // Define all varibles...
        $this->ID = $user['ID'];
        $this->TG_id = $user['TG_id'];
        $this->username = $user['username'];
        // If $user['langcode'] is not empty use it, otherwise set to 'EN'
        $this->langcode = !empty($user['langcode']) ? strtoupper($user['langcode']) : 'EN';
        $this->xp = $user['xp'];
        $this->hp = $user['hp'];
        $this->lvl = $user['lvl'];
        $this->in_guild_id = $user['in_guild_id'];
        $this->teamid = $user['teamid'];
        $this->fightfor = $user['fightfor'];
        $this->canfight = $user['canfight'];
        $this->token = $user['token'];
        $this->inviter = $user['inviter'];
        $this->depositaddress = $user['depositaddress'];
        $this->balance = $user['balance'];
    }
    /**
      * update user objeect in database, prefered over sql queries in code...
      *
      * @param string $key - what to update, for example 'balance'. Keys are restricted to some of values, check for $allowed_keys in class.
      * @param $value - what it should be? For example 69.00000000
      *
      * @throws 'PDO Exception'
    */
    public function updateDb($key, $value) {
        $allowed_keys = explode(",", $this->allowed_keys);
        if (!in_array($key, $allowed_keys)) {
            return false;
        }
        $q = $this->db->prepare("UPDATE `users` SET `$key`=:value WHERE ID=:id");
        $q->bindParam(":value",$value);
        $q->bindParam(":id",$this->ID);
        $q->execute();
        //$this->setUser($this->ID, 2);
        return true;
    }
    /**
      *  Count user's attack, defense, weight and speed.
      */
    private function setPower() {
        $user_items = $this->db->prepare(
            "SELECT * FROM `user_items` WHERE owned_by=:owned_by AND in_eq=1"
        );
        $user_items->bindParam(":owned_by", $this->ID);
        $user_items->execute();
        $user_items = $user_items->fetchAll();
        $this->attack = 0;
        $this->defense = 0;
        $this->speed = 0;
        $this->weight = 0;
        foreach ($user_items as $item) {
            $iteminfo = new item($item['in_shop_id']);
            $this->attack += $iteminfo->attack * $item['capacity'];
            $this->defense += $iteminfo->defense * $item['capacity'];
            $this->speed += $iteminfo->speed * $item['capacity'];
            $this->weight += $iteminfo->weight * $item['capacity'];
        }
    }
    /**
      * Display notification in user's notification section.
      *
      * @todo it's not not working at all.
      *
      * @param string $title Title of message
      * @param mixed[] $message content of message, can be any type, it will be stringified.
      */
    public function pushNotification($title,$message) {

    }
    /**
      * Update user data in database, check example
      */
    public function save() {
        $allowed_keys = explode(",", $this->allowed_keys);
        foreach ($allowed_keys as $key => $value) {
            //echo "Saving $value = ".$this->$value;
            $this->updateDb($value,$this->$value);
        }
    }
}
