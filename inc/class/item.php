<?php
/** 
  * Class that describe an item.
  * @author Czarek Nakamoto (Cyjan) mrcyjanek@pm.me
  * @example "forDevs/example/inc/class/item.example.php" Example usage of item class
*/

/**
  * item class.
  * @package inc\class
  */
class item {

    /** $this->db, we use this to communicate with database. */
    private $db;
    /**
      * Function stored in database (nvm)
      */
    private $magic;
    /**
      * Represent database's ID.
      */
    public $ID;
    /**
      * Item name, should be used like this: getString("SHOP->ITEM->NAME->".$item->ID,'EN',0,$item->name).
      */
    public $name;
    /**
      * Item type, should be used like this: getString($item->type).
      */
    public $type;
    /**
      * Item's attack points.
      */
    public $attack;
    /**
      * Item's defense points.
      */
    public $defense;
    /**
      * Item's speed points.
      */
    public $speed;
    /**
      * Item's weight.
      */
    public $weight;
    /**
      * 0 - can't buy or 1 can.
      */
    public $can_be_bought;
    /**
      * 0 - can't equip, 1 can equip.
      */
    public $can_eq;
    /**
      * Brief description about an item, should be used like: getString("SHOP->ITEM->ABOUT->".$item->ID,'EN',0,$item->about)
      */
    public $about;
    /**
      * In which shop can you find this item? Default: castle (or different for item's from quests)
      */
    public $inshop;
    /**
      * From which level you can buy/use this item
      */
    public $minlvl;
    /**
      * Emoji for this item
      */
    public $emoji;
    /**
      * Price of this item ($user->balance)
      */
    public $price;
    /**
      * Item's tag, common, uncommon, rare etc... should be used like: getString($item->tag)
      */
    public $tag;
    /**
      * How many items like this can one user own?
      */
    public $own_limit;
    /**
      * How many items like this will be in game?
      * @todo Not yet implemented.
      */
    public $capacity;
    /**
      * It's here for historical reasons and will be @deprecated
      */
    public $token;
    /**
     * Change item represented by varible.
     * @param int $itemid ID from database
     * @return void
    */
    public function __construct($itemid) {
        // Step 1, declare database
        global $db;
        $this->db = $db;
        $this->setItem($itemid);
    }
    /**
     * Change item represented by varible.
     * @param int $itemid ID from database
     * @return void
    */
    private function setItem($itemid) {
        $q = $this->db->prepare("SELECT * FROM `shop_items` WHERE ID=:id");
        $q->bindParam(":id",$itemid);
        $q->execute();
        $q = $q->fetchAll();
        if ($q == []) {
            throw new Exception("404|Item not found");
        }
        $q = $q[0];
        $this->magic = $q['magic'];
        $this->ID = $q['ID'];
        $this->name = $q['name'];
        $this->type = $q['type'];
        $this->attack = $q['attack'];
        $this->defense = $q['defense'];
        $this->speed = $q['speed'];
        $this->weight = $q['weight'];
        $this->can_be_bought = $q['can_be_bought'];
        $this->can_eq = $q['can_eq'];
        $this->about = $q['about'];
        $this->inshop = $q['inshop'];
        $this->minlvl = $q['minlvl'];
        $this->emoji = $q['emoji'];
        $this->price = $q['price'];
        $this->tag = $q['tag'];
        $this->own_limit = $q['own_limit'];
        $this->capacity = $q['capacity'];
        $this->token = $q['token'];
    }
    /**
     * Return number of how many x items does $userid have.
     * @param $userid -> ID of user, ($user->ID)
     * @return int number of items, or 0 in case of error.
    */
    public function userCapacity($userid) {
        $c = $this->db->prepare("SELECT `capacity` FROM `user_items` WHERE owned_by=:userid AND in_shop_id=:id");
        $c->bindParam(":userid", $userid);
        $c->bindParam(":id",$this->ID);
        $c->execute();
        if ($c == []) {
            return 0;
        }
        return $c[0]['capacity'];
    }
}