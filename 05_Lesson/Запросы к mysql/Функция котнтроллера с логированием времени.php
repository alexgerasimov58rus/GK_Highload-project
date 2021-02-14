public function next(Request $request){
        $log = $this->createLog("mysql");
        $log->debug("function next");

        $answer = $request->get('answer');
        $idImplement = $request->get('idImplement');

        $timeStart = microtime(true);

        $imp = (new ArithmeticService())->calcDataForNextStep($idImplement, $answer);
        $obj = json_decode($imp->getAttribute('data'));

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