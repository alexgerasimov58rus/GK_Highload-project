    public function initImplementation(){
        $imp = new Implementation();

        $task_curr = $this->getTask($this->getCurrentLevelFromBD());
        $task_next = $this->getTask($this->getCurrentLevelFromBD());

        $obj = (object) array(
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

        $imp->fill([
                'data'=>json_encode($obj)
            ]
        );

        if( $imp->save() === true){
            return $imp;        }

        return null;
    }	

    public function getImplementationById($id){
        return Implementation::query()->where('id', $id)->get()[0];
    }

    public function calcDataForNextStep($idImplement, $answer){
        $imp = $this->getImplementationById($idImplement);
        $obj = json_decode($imp->data);
    	...
    }