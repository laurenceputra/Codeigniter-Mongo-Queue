<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Core_model extends CI_Model {
    public function connect_to_db(){
        $options = array('db' => MONGO_DB_NAME,
            'replicaSet' => MONGO_REPLICA_SET,
            'readPreference' => MongoClient::RP_SECONDARY_PREFERRED);
        $conn = new MongoClient($mongo_uri, $options);
        // $conn = new Mongo();
        return $conn->database;
    }
}
