<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*********************************
 * Queue Model
 * Queue generally has 3 status, 'W' for waiting, 'I' for in progress, 'C' for completed
 * Everytime the status is changed, last_update is updated with current time
 *
*********************************/
class Queue_model extends CI_Model {
    public function choose_collection(){
        $this->load-> model('core_model');
        return $this->core_model->connect_to_db()->queue;
    }

    /*********************************
     * Enqueues a job
     * When daemon view_queue, it is responsible for updating the status, as well as last_update
     * action is basically the action that the job is
     * id is the identifier of the job
     * data is any other data related to the job. can put in arrays
     * status is the current state of processing for the job
    *********************************/
    public function enqueue($action, $id, $data = ''){
        $job = array('action' => $action, 'id' => $id, 'data' => $data, 'status' => 'W', 'last_update' => time());
        $settings = array();
        $collection = $this->choose_collection();
        $collection->insert($job);
    }

    public function view_queue(){
        return $this->choose_collection()->find()->tailable();
    }
}