<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Queue extends CI_Controller {
    public function index()
    {
        $this->load->view('welcome_message');
    }

    /****************************
     * Daemon function
     * Called from commandline
    ****************************/
    public function daemon(){
        gc_enable();
        $this->load->model('queue_model');
        $cycles = 0;
        while(true){
            $queue = $this->queue_model->view_queue();
            $collection = $this->queue_model->choose_collection();
            while(true){
                if(!$queue->hasNext()){
                    $cycles++;
                    if($cycles % 3 == 0){
                        $this->sync_random_user();
                    }
                    if($cycles > 30){
                        $cycles = 0;
                        gc_collect_cycles();
                    }
                    sleep(10);
                    if($queue->dead()){
                        break;
                    }
                }
                else{
                    $newJob = $queue->getNext();
                    //execute your stuff here
                    $newJob = null;
                    unset($newJob);
                }
            }    
        }
    }
}

/* End of file welcome.php */
