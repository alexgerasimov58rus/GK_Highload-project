public function initImplementation(){
        $task_curr = $this->getTask($this->getCurrentLevelFromBD());
        $task_next = $this->getTask($this->getCurrentLevelFromBD());

        $id = Auth::id() . "_" . time();

        $obj = (object) array(
            "id"=>$id,
            "currLevel"=>$this->getCurrentLevelFromBD(),
            "maxLevel"=>$this->getMaxLevelFromBD(),
            "nextTask"=>$task_next->task,
            "nextTaskResult"=>$task_next->result,
            "nextTaskDuration"=>$task_next->secondsToSolve,
            "currTask"=>$task_curr->task,
            "currTaskResult"=>$task_curr->result,
            "currTaskDuration"=>$task_curr->secondsToSolve,
            "rightAnswersInCurrLevel"=>0,
            "stepsInLevel"=>$this->getStepsToNextLevel(),
            "rightAnswers"=>0,
            "wrongAnswers"=>0,
            "prevTaskResult"=>0
        );

        $redis = new RedisCacheProvider();
        $redis->set($id, json_encode($obj));

        return $obj;
    }	

    public function getImplementationById($id){
        return (new RedisCacheProvider())->get($id);
    }

    public function calcDataForNextStep($idImplement, $answer){
        $imp = $this->getImplementationById($idImplement);
        $obj = json_decode($imp);
	...
    }