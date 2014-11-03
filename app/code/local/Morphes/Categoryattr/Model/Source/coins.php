<?php

defined('SYSPATH') or die('No direct script access.');

class Model_Coins extends ORM {

    protected $_table_name = 'coins';
    protected $_primary_key = 'id';
    
    public function __construct($id = NULL) {
        $this->translator = FrontHelper::getTranslation();
        parent::__construct($id);
    }
    function getCoins($id) {
        $data = array();
        $balance = 0;
        $coins = $this->where('by','=', $id)->find_all()->as_array();
        foreach($coins as $coin) {            
            $help_coin = $coin->as_array();
            if($help_coin['pm']=='plus') {
                $help_coin['balance'] = $help_coin['credit'] + $balance;
                $balance += $help_coin['credit'];
            } else {
                $help_coin['balance'] = $balance - $help_coin['credit'] ;
                $balance = $balance - $help_coin['credit'];
            }
            
            $data[] = $help_coin;
        }
        return $data;
    }
    
    function getBalance($id) {
        $coinsData = $this->getCoins($id);                        
        if(isset($coinsData[count($coinsData)-1])) {                         
            return $coinsData[count($coinsData)-1]['balance'];
        } else { 
            return 0;
        }
    }
    
    function saveGiftTransaction($from, $to, $data) {
        $username = ORM::factory('user', $to)->username;
        $result['statement'] = $this->translator['text25'].' '.$username;
        $result['by'] = $from;
        $result['pm'] = 'minus';
        if(ORM::factory('user', $from)->is_gold=='0') {        
            $result['credit'] = FrontHelper::getCostGift($data['id_gift']);
        } else {
            if(ORM::factory('user', $from)->gift_count<6) {
                $result['credit'] = '0';
            } else {
                $result['credit'] = FrontHelper::getCostGift($data['id_gift']);
            }   
        }   
        $result['time'] = time();
        $coins = ORM::factory('coins');
        $coins->values($result);
        return $coins->save();        
    }
    
    function addCoinsByAdmin($data) {
        $data['time'] = time();
        $username = $data['by'];
        $user = ORM::factory('User')->where('username','=', $username)->find();
        if($user->id) {

            $data['by'] = $user->id;            
            $this->values($data)->save();  
            return true;  
        } else {
            return false;
        }
        
        return;
    }
    
    function savePaymentTransaction($data, $id_user) {
        $this->credit = $data['coins'];
        $this->statement = $this->translator['text32'];
        $this->time = time();
        $this->pm = 'plus';
        $this->by = $id_user;
        $this->save();
        return true;
    }
    
    function saveGoldTransaction($id_user) {        
        $this->credit = FrontHelper::getCost('gold');
        $this->statement = FrontHelper::getCostDescription('gold');
        $this->time = time();
        $this->pm = 'minus';
        $this->by = $id_user;
        $this->save();
        return true;
    }
    
    function saveChatTransaction($id_user) {
        $this->credit = FrontHelper::getCost('chat');
        $this->statement = FrontHelper::getCostDescription('chat');
        $this->time = time();
        $this->pm = 'minus';
        $this->by = $id_user;
        $this->save();
        return true;
    }
}

