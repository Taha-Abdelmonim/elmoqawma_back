<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterUserExamRequest;
use App\Services\Api\ExamRegisterService;
use Illuminate\Support\Facades\Validator;

class ExamController extends Controller
{
    public function registerUserExam(RegisterUserExamRequest $request)
    {
        $user = auth()
            ->guard("api")
            ->user();
        if (empty($user)) {
            return returnError(401, trans("user_not_found"));
        }
        $validator = Validator::make($request->all(), $request->rules());
        if ($validator->fails()) {
            return returnError(422, json_decode($validator->errors()->toJson()));
        }
        return (new ExamRegisterService($request))->register();
    }
}
