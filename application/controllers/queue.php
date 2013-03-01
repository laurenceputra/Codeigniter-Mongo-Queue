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
                    if($newJob['status'] == 'C'){
                        //do nothing
                    }
                    else if($newJob['status'] == 'I' && time() - $newJob['last_update'] < 60){
                        //This part is to recover from errors in case daemon dies halfway, eg server reboot
                        //Change 60 to the amount of time to give each job.
                    }
                    else{
                        $newJob['last_update'] = time();
                        $newJob['status'] = 'I';
                        $collection->save($newJob);
                        //do job
                        /*************************
                        For this section, it would be better to fork the jobs to another process to reduce memory footprint.
                        To do it, simply use the command exec, with the unix command as the argument, i.e. exec(command)
                        Remember to use the absolute path for this
                        *************************/
                        if($newJob['action'] == 'Job1'){
                            //Change to the action that you have named
                            //exec('unix command');
                        }
                        else if($newJob['action'] == 'Job2'){
                            //Change to the action that you have named
                            //exec('unix command');
                        }
                        $newJob['last_update'] = time();
                        $newJob['status'] = 'C';
                        $collection->save($newJob);
                    }
                    $newJob = null;
                    unset($newJob);
                }
            }    
        }
    }
}

/* End of file welcome.php */
