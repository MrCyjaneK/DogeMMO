<?php
/**
  * @ignore
  */
class shop_items {
    private $db;
    public $ID;

    public function __construct($ID = 1) {
        global $db;
        $this->db = $db;
        $this->ID = $ID;
    }
    // return all items
    public function all() {
        $shop_items = $this->db->prepare(
            "SELECT * FROM `shop_items`"
        );
        $shop_items->execute();
        return $shop_items->fetchAll();
    }
    public function __get($key) {
        $q = $this->db->prepare("SELECT * FROM `shop_items` WHERE ID=:id");
        $q->bindParam(":id",$this->ID);
        $q->execute();
        return $q->fetchAll()[0][$key];
    }
}