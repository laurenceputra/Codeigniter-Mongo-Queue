<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Core_model extends CI_Model {
    public function connect_to_db(){
        $options = array('db' => MONGO_DB_NAME,
            'replicaSet' => 'laurenceputra1',
            'readPreference' => MongoClient::RP_SECONDARY_PREFERRED);
        $conn = new MongoClient('mongodb://'.MONGO_USERNAME.':'.MONGO_PASSWORD.'@216.12.210.216:19904,216.12.199.124:22380', $options);
        // $conn = new Mongo();
        return $conn->instasyncer;
    }
}
