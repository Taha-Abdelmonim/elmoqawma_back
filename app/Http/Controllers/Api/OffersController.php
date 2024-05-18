<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AmountBank;
use App\Models\Certificate;
use App\Models\Chapter;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Comment;
use App\Models\DetailExam;
use App\Models\Files;
use App\Models\Lecture;
use App\Models\Order;
use App\Models\Offer;
use App\Models\Question;
use App\Models\Section;
use App\Models\UserAnswer;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

define("PAGINATE", 2);

class OffersController extends Controller
{
	public function getOffers()
	{
		try {
			$offers = Offer::with("currency")->get();
			return returnData($offers);
		} catch (\Exception $ex) {
			return returnError($ex->getCode(), $ex->getMessage());
		}
	}

	public function getOffer($id)
	{
		try {
			$offer = Offer::with(["courses" => ["currency", "teacher", "section"], "currency"])->find($id);
			$offer->subscribed = 0;
			$user = auth()->guard('api')->user();
			if ($user !== null) {
				$order = Order::where("offer_id", $id)->where("user_id", $user->id)->first();
				if (!empty($order)) {
					$offer->subscribed = $order->status;
				}
			}

			return returnData($offer);
		} catch (\Exception $ex) {
			return returnError($ex->getCode(), $ex->getMessage());
		}
	}
}
