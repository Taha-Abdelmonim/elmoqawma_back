<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['api', 'convert_locale']], function () {
	Route::post('/auth/login', "AuthController@login");
	Route::post('/auth/register', "AuthController@register");
	Route::post('/auth/socialLogin', "AuthController@handleCallback");
	Route::middleware('jwt_verify')->group(function () {
		Route::group(['prefix' => 'auth'], function ($router) {
			Route::post("/student/{type}", "AuthController@student");
//			Route::post('/userData', "AuthController@userData");
			Route::post('/update', "AuthController@updateUser");
			Route::post('/updateImage', "AuthController@updateUserImage");
			Route::post('/refresh', "AuthController@refresh");
			Route::post('/logout', "AuthController@logout");
		});
		Route::post('/readNotification/{id}', "AppController@readNotification");
		Route::post('/notificationsUser', "AuthController@notificationsUser");
		Route::post('/order/{type}/payBank', "AppController@payBank");

		Route::post('/walletPay/{code}', "AppController@walletPay");
		Route::post('/sendMessage', "SupportController@sendMessage");
		Route::post('/commentPost', "PostController@commentPost");
		Route::post('/commentCourse', "CourseController@commentCourse");
		Route::post('/getQuestions', "CourseController@getQuestions");
		Route::post('/userExams', "CourseController@userExams");
		Route::post('/userShowAnswers', "CourseController@userShowAnswers");
		Route::post('/registerUserExam', "ExamController@registerUserExam");
		Route::post('/sendReview', "ReviewController@sendReview");
		Route::post('/markAsRead', function () {
			auth()->user()->unreadNotifications->markAsRead();
			return returnData("", "تم قرائة جميع اﻹشعارات");
		})->name('mark_all');
	});
	Route::post('/getReviewsSlider', "ReviewController@getReviewsSlider");
	Route::get('/getAsks', "AppController@getAsks");
	Route::controller("OffersController")->group(function () {
		Route::get('/getOffers', 'getOffers');
		Route::get('/getOffer/{id}', 'getOffer');
	});

	Route::controller("PostController")->group(function () {

		Route::get('/getPosts', 'getPosts');
		Route::post('/getPostsSlider', 'getPostsSlider');
		Route::post('/searchPosts', 'searchPosts');
		Route::get('/filterPosts', 'filterPosts');
		Route::post('/post/{slug}', 'getPost');
		Route::post('/posts/moreWatched', 'moreWatched');
	});
	Route::controller("TeacherController")->group(function () {
		Route::get('/home', 'home');
		Route::get('/courses', 'courses');
		Route::get('/teachers', 'teachers');
		Route::get('/sections', 'sections');
		Route::get('/subjects', 'subjects');
		Route::get('/teacher/{id}', 'teacher');
		Route::get('/section/{id}', 'section');
		Route::get('/subject/{id}', 'subject');
	});
	Route::controller("CourseController")->group(function () {
		Route::post('/saveLectureView', 'saveLectureView');
		Route::get('/getCourses', 'getCourses');
//		Route::get('/getCoursesSlider', 'getCoursesSlider');
		Route::get('/mainCourses', 'mainCourses');
		Route::post('/searchCourses', 'searchCourses');
		Route::get('/filterCourses', 'filterCourses');
		Route::get('/course/{id}', 'getCourse');
	});
	Route::controller("WorkController")->group(function () {
		Route::get('/getWorks', "getWorks");
		Route::get('/getWork/{id}', "getWork");
	});
	Route::controller("AppController")->group(function () {
		Route::get("/app", "app");
	});


	// login with facebook google http://127.0.0.1:8000/api/auth/google/redirect
	// Route::get('/auth/{service}/redirect', [AuthController::class, 'handleGoogleRedirect'])->middleware('web');
});
  
