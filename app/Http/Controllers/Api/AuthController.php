<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Offer;
use App\Models\Order;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth as AuthUser;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Traits\GlobalTrait;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
	use GlobalTrait;

	public function __construct()
	{
		//    $this->middleware('auth:api', ['except' => ['login', 'register']]);
		auth()->setDefaultDriver("api");
		//      $this->middleware('jwt.verify', ['except' => ['login', 'register']]);
	}

	public function student($type, Request $request)
	{
		try {
			//			user
			/*if ($user !== null) {
				$user->access_token = $request->bearerToken();
			}*/
			$user = auth()->guard('api')->user();
			if ($type === "courses") {
				//			courses
				$courseIDS = Order::where("user_id", $user->id)->where("status", 1)->get()->pluck("course_id");
				$courses = Course::whereIn("id", $courseIDS)->with(["subject", "section", "teacher", "currency", "comments", "attachments"])->get();
				foreach ($courses as $course) {
					$course->price = Order::where("user_id", $user->id)->where("course_id", $course->id)->first()->price;
				}
				return returnData($courses);
			} elseif ($type === "offers") {
				//			offers
				$offerIDS = Order::where("user_id", $user->id)->where("status", 1)->get()->pluck("offer_id");
				$offers = Offer::with(["currency"])->whereIn("id", $offerIDS)->get();
				foreach ($offers as $offer) {
					$offer->price = Order::where("user_id", $user->id)->where("offer_id", $offer->id)->first()->price;
				}
				return returnData($offers);
			} elseif ($type === "certificates") {
				//			certificates
				$certificates = Certificate::with(["course", "user"])->where("user_id", $user->id)->get();
				return returnData($certificates);
			} elseif ($type === "invoices") {
				//			invoices
				$OrderCourses = Order::with(["course" => ["subject"], "currency"])->where("user_id", $user->id)->whereNotNull("course_id")->whereNull("lecture_id")->get();
				foreach ($OrderCourses as $OrderCourse) {
					$OrderCourse->data_type = "course";
				}
				$OrderLectures = Order::with(["lecture", "currency"])->where("user_id", $user->id)->whereNotNull("course_id")->whereNotNull("lecture_id")->get();
				foreach ($OrderLectures as $OrderLecture) {
					$OrderLecture->data_type = "lecture";
				}
				$OfferOrders = Order::with(["offer", "currency"])->where("user_id", $user->id)->whereNotNull("offer_id")->get();
				foreach ($OfferOrders as $OfferOrder) {
					$OfferOrder->data_type = "offer";
				}
				$invoices = array_merge($OrderCourses->toArray(), $OrderLectures->toArray(), $OfferOrders->toArray());
				return returnData(collect($invoices));
			}
			return returnData("");
//			return returnData(["user" => $user, "courses" => $courses, "offers" => $offers, "certificates" => $certificates, "invoices" => collect($invoices)]);
		} catch (\Exception $ex) {
			return returnError($ex->getCode(), $ex->getMessage());
		}
	}

	public function notificationsUser()
	{
		try {
			if (empty(auth()->user())) {
				return returnError(401, trans("user_not_found"));
			}
			return returnData(auth()->user()->unreadNotifications, "");
		} catch (\Exception $ex) {
			return returnError($ex->getCode(), $ex->getMessage());
		}
	}

	public function updateUserImage(Request $request)
	{
		$validator = Validator::make(
			$request->all(),
			[
				"photo" => "mimes:jpeg,jpg,png,gif,webp,svg|required|max:10240",
			],
			[
				"photo.required" => trans("validation.photo_required"),
				"photo.mimes" => trans("validation.photo_required"),
				"photo.max" => trans("validation.photo_max"),
			]
		);
		if ($validator->fails()) {
			return returnError(422, json_decode($validator->errors()->toJson()));
		}
		$user = User::findorFail(auth()->user()->id);
		$path = $user->photo;
		if ($request->file("photo")) {
			$oldPath = public_path("images/" . $user->photo);
			if (is_file($oldPath)) {
				unlink($oldPath);
			}
			$path = $request->file("photo")->store("users", "public_image");
		}
		// return returnError(422, $user);
		$user->update([
			"photo" => $path,
		]);
		$user->access_token = $request->bearerToken();
		return response()->json([
			"status" => true,
			"message" => trans("validation.success_edit_photo"),
			"user" => $user,
		]);
	}


	public function login(Request $request)
	{
		try {
			$validator = Validator::make($request->all(), [
				"email" => "required|email",
				"password" => "required|string|min:6",
			]);

			if ($validator->fails()) {
				return returnError(422, trans("validation.password"));
			}

			$credentials = ["email" => $request->email, "password" => $request->password];
			if (!($token = auth()->attempt($credentials, 1))) {
				return returnError(422, trans("validation.password"));
			}
			$user = User::find(\auth()->user()->id);
			if ($user->access_token != null) {
				JWTAuth::setToken($user->access_token)->invalidate();
			}
			$user->access_token = $token;
			$user->save();
			//			return \response()->json($user->access_token);
			return $this->createNewToken($token, trans("validation.success_login"));
		} catch (\Exception $ex) {
			return returnError($ex->getCode(), $ex->getMessage());
		}
	}

	public function register(Request $request)
	{
		try {
			$validator = Validator::make(
				$request->all(),
				[
					"name_ar" => "required|string|between:2,100",
					"email" => [
						"required",
						"email",
						"max:100",
						Rule::unique('users')->whereNull('deleted_at'),
					],
					"password" => "required|min:6",
					"phone" => "required|numeric",
					"phone_parent" => "required|numeric",
					"birth" => "date",
					//					'roles_name' => 'required',
				],
				[
					"name_ar.required" => trans("validation.name_ar_required"),
					"name.string" => trans("validation.name_string"),
					"email.required" => trans("validation.email_required"),
					"email.email" => trans("validation.email_required"),
					"email.unique" => trans("validation.email_unique"),
					"phone.required" => trans("validation.phone_required"),
					"phone.numeric" => trans("validation.phone_numeric"),
					"phone_parent.required" => trans("validation.phone_parent_required"),
					"phone_parent.numeric" => trans("validation.phone_parent_numeric"),
					"password.required" => trans("validation.password_required"),
					"password.min" => trans("validation.password_min"),
					//					'roles_name.required' => trans('validation.roles_name_required'),
				]
			);
			if ($validator->fails()) {
				return returnError(422, json_decode($validator->errors()->toJson()));
			}
			$data = $request->all();
			$data["password"] = bcrypt($request->password);
			$data["name_en"] = $request->name_ar;
			User::create($data);
			$token = auth()->attempt(["email" => $request->email, "password" => $request->password], true);
			return $this->createNewToken($token, trans("validation.success_create_user"));
		} catch (\Exception $ex) {
			return returnError($ex->getCode(), $ex->getMessage());
		}
	}

	public function updateUser(Request $request)
	{
		if (empty(auth()->user())) {
			return returnError(401, trans("global.user_not_found"));
		}
		$user = User::findorFail(auth()->user()->id);
		$password = $user->password;
		try {
			if (!empty($request->password) and !empty($request->old_password) and !empty($request->password_confirmation)) {
				if (Hash::check($request->old_password, $user->password)) {
					$validatePassword = Validator::make(
						$request->all(),
						[
							"password" => "required|confirmed|min:6",
						],
						[
							"password.required" => trans("validation.password_required"),
							"password.min" => trans("validation.password_min"),
							"password.confirmed" => trans("validation.password_confirmed"),
						]
					);
					if ($validatePassword->fails()) {
						return returnError(422, json_decode($validatePassword->errors()->toJson()));
					}

					$password = Hash::make($request->password);
				} else {
					return returnError(422, json_decode(json_encode(["password" => [trans("validation.error_old_password")]])));
				}
			}

			$validator = Validator::make(
				$request->all(),
				[
					"name_ar" => "required|string|between:2,100",
					"name_en" => "required|string|between:2,100",
					"email" => "required|email|unique:users,email," . $user->id,
					"phone" => "required|numeric",
					"birth" => "date",
				],
				[
					"name_ar.required" => trans("validation.name_ar_required"),
					"name_en.required" => trans("validation.name_en"),
					"name.string" => trans("validation.name_string"),
					"email.required" => trans("validation.email_required"),
					"email.email" => trans("validation.email_email"),
					"email.unique" => trans("validation.email_unique"),
					"phone.required" => trans("validation.phone_required"),
					"phone.numeric" => trans("validation.phone_numeric"),
				]
			);

			// return returnError(422, $user->id);
			if ($validator->fails()) {
				return returnError(422, json_decode($validator->errors()->toJson()));
			}
			$user->update(array_merge($validator->validated(), ["password" => $password]));
			$user->access_token = $request->bearerToken();
			return response()->json([
				"status" => true,
				"message" => trans("validation.success_edit_user"),
				"user" => $user,
			]);
		} catch (\Exception $ex) {
			return returnError($ex->getCode(), $ex->getMessage());
		}
	}

	public function logout(Request $request)
	{
		//    $request->headers->set('auth-token', (string) $token, true);
		/* $token = $request->header('auth-token');
		 $request->headers->set('Authorization', 'Bearer ' . $token, true); */
		try {
			JWTAuth::setToken($request->bearerToken())->invalidate(); //logout
			auth()->logout();
			return returnData("", trans("validation.success_logout"));
		} catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
			return returnError("", trans("validation.any_error"));
		}
	}

	public function refresh(Request $request)
	{
		try {
			return $this->createNewToken(auth()->refresh(), trans("validation.success_refresh_user"));
		} catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
			return returnError("", trans("validation.any_error"));
		}
	}


	/*public function userData(Request $request)
	{
		try {
			$user = auth()->guard('api')->user();
			if ($user === null) {
				return returnError(401, trans("global.user_not_found"));
			}
			return $this->createNewToken($request->bearerToken(), trans("validation.user_register"));
		} catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
			return returnError("", trans("validation.any_error"));
		}
	}*/

	protected function createNewToken($token, $message)
	{
		$user = auth()->guard('api')->user();
		$user->access_token = $token;
		return response()->json([
			"status" => true,
			"message" => $message,
			"user" => $user,
		]);
	}

	public function getAuthenticatedUser()
	{
		try {
			if (!($user = JWTAuth::parseToken()->authenticate())) {
				return response()->json(["user_not_found"], 404);
			}
		} catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
			return response()->json(["token_expired"], $e->getStatusCode());
		} catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
			return response()->json(["token_invalid"], $e->getStatusCode());
		} catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
			return response()->json(["token_absent"], $e->getStatusCode());
		}

		return response()->json(compact("user"));
	}

	/* public function handleGoogleRedirect($service)
{
	return Socialite::driver($service)->redirect();
} */

	public function handleCallback(Request $request)
	{
		try {
			$userExisted = User::where("oauth_id", $request->id)
				->where("oauth_type", $request->oauth_type)
				->first();
			if ($userExisted) {
				$token = auth("api")->login($userExisted);
				return $this->createNewToken($token, trans("validation.success_login"));
			}
			$validator = Validator::make(
				$request->all(),
				[
					"name" => "required|string|between:2,100",
					"email" => "required|email|max:100|unique:users",
				],
				[
					"name.required" => trans("validation.name_required"),
					"name.string" => trans("validation.name_string"),
					"email.required" => trans("validation.email_required"),
					"email.email" => trans("validation.email_email"),
					"email.unique" => trans("validation.email_unique"),
				]
			);
			if ($validator->fails()) {
				return returnError(422, json_decode($validator->errors()->toJson()));
			}
			$newUser = User::create([
				"oauth_id" => $request->id,
				"oauth_type" => $request->oauth_type,
				"name" => $request->name,
				"email" => $request->email,
				"phone" => "0000000000",
				"photo" => $request->photo,
				"status" => 0,
				"password" => Hash::make($request->id),
			]);
			$token = auth("api")->login($newUser);
			return $this->createNewToken($token, trans("validation.success_login"));
		} catch (\Exception $ex) {
			return returnError($ex->getCode(), $ex->getMessage());
		}
	}
}
