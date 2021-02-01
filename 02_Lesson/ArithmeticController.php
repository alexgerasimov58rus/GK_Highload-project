<?php

namespace App\Http\Controllers;

use App\Models\ArithmeticStatistic;
use App\Models\Models\Implementation;
use App\Services\ArithmeticService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class ArithmeticController extends Controller
{
    public function init(Request $request){
        $log = new Logger('monolog');
        $log->pushHandler(new StreamHandler(__DIR__.'/../../../storage/logs/my.log', Logger::DEBUG));
        $log->debug("ArithmeticController::init");

        $memoryStart = memory_get_usage();
        $log->debug("Memory start: " . $memoryStart);

        $service = new ArithmeticService();
        $memoryCreateArithmeticService = memory_get_usage();
        $log->debug("Memory on Create ArithmeticService: " . ($memoryCreateArithmeticService - $memoryStart));

        $imp = $service->initImplementation();
        $memoryInitArithmeticService = memory_get_usage();
        $log->debug("Memory on Init ArithmeticService: " . ($memoryInitArithmeticService - $memoryCreateArithmeticService));

        $obj = json_decode($imp->getAttribute('data'));
        $memoryJsonDecode = memory_get_usage();
        $log->debug("Memory on create json: " . ($memoryJsonDecode - $memoryInitArithmeticService));

        $memoryEnd = memory_get_usage();
        $log->debug("Memory end: " . $memoryEnd);

        return response()->json([
            'idImplement' => $imp->getAttribute('id'),
            'currLevel' => $obj->currLevel,
            'currTask' => $obj->currTask,
            'nextTask' => $obj->nextTask,
            'secForTask' => $obj->currTaskDuration,
            'counter' => $service->getTrainingCounterFromBD()
        ]);
    }

    public function next(Request $request){
        $answer = $request->get('answer');
        $idImplement = $request->get('idImplement');

        $imp = (new ArithmeticService())->calcDataForNextStep($idImplement, $answer);
        $obj = json_decode($imp->getAttribute('data'));

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

    public function finish(Request $request){
        $idImplement = $request->get('idImplement');

        $imp = (new ArithmeticService())->getImplementationById($idImplement);
        $obj = json_decode($imp->getAttribute('data'));

        $statistic = new ArithmeticStatistic();

        $statistic->fill([
                'user_id'=> Auth::id(),
                'right' => $obj->rightAnswers,
                'wrong' => $obj->wrongAnswers,
                'currLevel' => $obj->currLevel
            ]
        );

        $statistic->save();

        return response()->json([
            'right' => $obj->rightAnswers,
            'wrong' => $obj->wrongAnswers
        ]);
    }
}
