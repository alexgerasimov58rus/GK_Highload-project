    public function next(Request $request){
        $log = $this->createLog("redis");
        $log->debug("function next");

        $answer = $request->get('answer');
        $idImplement = $request->get('idImplement');

        $timeStart = microtime(true);

        $obj = (new ArithmeticService())->calcDataForNextStep($idImplement, $answer);

        $execTime = microtime(true) - $timeStart;
        $log->debug("execution time: " . round($execTime*1000) . " ms");

        return response()->json([
            'currLevel' => $obj->currLevel,
            'currTask' => $obj->currTask,
            'nextTask' => $obj->nextTask,
            'secForTask' => $obj->currTaskDuration,
            'rightAnswersInCurrLevel' => $obj->rightAnswersInCurrLevel,
            'answer' => $obj->prevTaskResult,
            'right' => $obj->rightAnswers,
            'wrong' => $obj->wrongAnswers
        ]);
    }