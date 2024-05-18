<?php

use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;


Route::middleware("auth")->group(function () {

	Route::get("/notifications", "HomeController@notifications")->name("notifications");
	Route::post("/send_notifications", "HomeController@sendNotifications")->name("send_notifications");
	Route::get("/", "HomeController@index")->name("dashboard");
	Route::controller("WhatsappController")->group(function () {
		Route::get("/whatsapp", "index")->name("whatsapp");
		Route::post("/whatsapp_import", "import")->name("whatsapp_import");
		Route::get("/whatsapp_delete/{id}", "delete")->name("whatsapp_delete");
	});
	Route::controller("UserController")->group(function () {
		Route::get("/getUsersApi", "getUsers")->name("users");
		Route::get("/users", "index")->name("users");
		Route::get("/user/create", "create")->name("user_create");
		Route::post("/user/store", "store")->name("user_save");
		Route::get("/user/edit/{id}", "edit")->name("user_edit");
		Route::post("/user/wallet/{id}", "wallet")->name("user_wallet");
		Route::get("/user/{id}", "show")->name("user_show");
		Route::post("/user/update/{id}", "update")->name("user_update");
		Route::get("/user/delete/{id}", "destroy")->name("user_delete");
		Route::post("/user/deleteAll", "destroyAll")->name("users_delete_all");
	});
	Route::controller("TeacherController")->group(function () {
		Route::get("/getTeachersApi", "getTeachers")->name("get_teacher");
		Route::get("/teachers", "index")->name("teachers");
		Route::get("/teacher/create", "create")->name("teacher_create");
		Route::post("/teacher/store", "store")->name("teacher_save");
		Route::get("/teacher/edit/{id}", "edit")->name("teacher_edit");
		Route::post("/teacher/update/{id}", "update")->name("teacher_update");
		Route::get("/teacher/delete/{id}", "destroy")->name("teacher_delete");
	});
	Route::controller("RoleController")->group(function () {
		Route::get("/getRolesApi", "getRoles")->name("getRolesApi");
		Route::get("/roles", "index")->name("roles");
		Route::get("/role/create", "create")->name("role_create");
		Route::post("/role/store", "store")->name("role_save");
		Route::get("/role/edit/{id}", "edit")->name("role_edit");
		Route::post("/role/update/{id}", "update")->name("role_update");
		Route::get("/role/delete/{id}", "destroy")->name("role_delete");
		Route::post("/role/deleteAll", "destroyAll")->name("roles_delete_all");
	});
	Route::controller("PermissionController")->group(function () {
		Route::get("/getPermissions", "getPermissions")->name("getPermissions");
		Route::get("/permissions", "index")->name("permissions");
		Route::get("/permission/create", "create")->name("permission_create");
		Route::post("/permission/store", "store")->name("permission_save");
		Route::get("/permission/edit/{id}", "edit")->name("permission_edit");
		Route::post("/permission/update/{id}", "update")->name("permission_update");
		Route::get("/permission/delete/{id}", "destroy")->name("permission_delete");
	});
	Route::controller("SectionController")->group(function () {
		Route::get("/getSections", "getSections")->name("getSections");
		Route::post("/getChildSection", "getChildSection")->name("sections_get_child");
		Route::get("/sections", "index")->name("sections");
		Route::get("/section/create", "create")->name("section_create");
		Route::post("/section/store", "store")->name("section_save");
		Route::get("/section/edit/{id}", "edit")->name("section_edit");
		Route::post("/section/update/{id}", "update")->name("section_update");
		Route::get("/section/delete/{id}", "destroy")->name("section_delete");
		Route::post("/section/deleteAll", "destroyAll")->name("sections_delete_all");
	});

	Route::controller("SubjectController")->group(function () {
		Route::get("/getSubjects", "getSubjects");
		Route::get("/subjects", "index")->name("subjects");
		Route::get("/subject/create", "create")->name("subject_create");
		Route::post("/subject/store", "store")->name("subject_save");
		Route::get("/subject/edit/{id}", "edit")->name("subject_edit");
		Route::post("/subject/update/{id}", "update")->name("subject_update");
		Route::get("/subject/delete/{id}", "destroy")->name("subject_delete");
	});
	Route::controller("CourseController")->group(function () {
		Route::get("/getCourseLectures/{course_id}", "getCourseLectures");
		Route::get("/getCourses", "getCourses")->name("getCourses");
		Route::post("/getChildCourse", "getChildCourse")->name("courses_get_child");
		Route::get("/courses", "index")->name("courses");
		Route::get("/course/create", "create")->name("course_create");
		Route::get("/course/{id}", "show")->name("course_show");
		Route::post("course/upload/video/{id}", "uploadVideo")->name("upload_video");
		Route::post("/course/store", "store")->name("course_save");
		Route::get("/course/edit/{id}", "edit")->name("course_edit");
		Route::post("/course/update/{id}", "update")->name("course_update");
		Route::get("/course/delete/{id}", "destroy")->name("course_delete");
	});
	Route::controller("AttachmentController")->prefix("attachments")->name("attachments.")->group(function () {
		Route::get("/create/{modal}/{modal_id}", "create")->name("create");
		Route::post("/store/{modal}/{modal_id}", "store")->name("store");
		Route::get("/{id}/delete", "destroy")->name("destroy");
	});
	Route::prefix("course/{course_id}")->group(function () {
		Route::controller("ChapterController")->group(function () {
			Route::get("/getChapters", "getChapters")->name("getChapters");
			Route::get("/chapters", "index")->name("chapters");
			Route::get("/chapter/create", "create")->name("chapter_create");
			Route::post("/chapter/store", "store")->name("chapter_save");
			Route::post("chapter/{id}", "show")->name("chapter_show");
			Route::get("/chapter/edit/{id}", "edit")->name("chapter_edit");
			Route::post("/chapter/update/{id}", "update")->name("chapter_update");
			Route::get("/chapter/delete/{id}", "destroy")->name("chapter_delete");
			Route::post("/chapters/deleteAll", "destroyAll")->name("chapters_delete_all");
		});
	});
	Route::get("/rooms", "LectureController@rooms")->name("rooms");
	Route::prefix("chapter/{chapter_id}")->group(function () {
		Route::controller("LectureController")->group(function () {
			Route::get("/getLectures", "getLectures")->name("getLectures");
			Route::get("/lectures", "index")->name("lectures");
			Route::get("/lecture/create", "create")->name("lecture_create");
			Route::post("/lecture/store", "store")->name("lecture_save");
			Route::get("lecture/{id}", "show")->name("lecture_show");
			Route::get("/lecture/edit/{id}", "edit")->name("lecture_edit");
			Route::post("/lecture/update/{id}", "update")->name("lecture_update");
			Route::get("/lecture/delete/{id}", "destroy")->name("lecture_delete");
			Route::post("/lectures/deleteAll", "destroyAll")->name("lectures_delete_all");
		});
	});
	Route::prefix("questions")->group(function () {
		Route::controller("QuestionController")->group(function () {
			Route::get("/getCoursesApi", "getCourses");
			Route::get("/courses", "courses")->name("questions_courses");
			Route::get("/course/{courseID}/lectures", "lectures")->name("course_lectures");
			Route::get("/course/{courseID}/lecture/{lectureID}", "index")->name("questions");
			Route::get("/course/{courseID}/lecture/{lectureID}/exportExel", "exportExel")->name("questions_export_exel");
			Route::get("/course/{courseID}/lecture/{lectureID}/bank/create", "bankCreate")->name("bank_questions_create");
			Route::post("/course/{courseID}/lecture/{lectureID}/bank/store", "bankSave")->name("bank_questions_save");
			Route::get("/course/{courseID}/lecture/{lectureID}/create", "create")->name("question_create");
			Route::post("/course/{courseID}/lecture/{lectureID}/store", "store")->name("question_save");
			Route::get("/course/{courseID}/lecture/{lectureID}/edit/{id}", "edit")->name("question_edit");
			Route::post("/course/{courseID}/lecture/{lectureID}/update/{id}", "update")->name("question_update");
			Route::get("/course/{courseID}/lecture/{lectureID}/delete/{id}", "destroy")->name("question_delete");
			Route::post("/course/{courseID}/lecture/{lectureID}/deleteAll", "destroyAll")->name("questions_delete_all");
		});
	});
	Route::prefix("bank_questions")->group(function () {
		Route::controller("BankQuestionController")->group(function () {
			Route::get("/categories", "categories")->name("bank_questions_categories");
			Route::post("categoryGetChild", "categoryGetChild")->name("category_get_child");
			Route::get("/{bank_category_id}", "index")->name("bank_questions");
			Route::post("/{bank_category_id}/questions_import", "QuestionsImport")->name("questions_import");
			Route::get("/{bank_category_id}/create", "create")->name("bank_question_create");
			Route::post("/{bank_category_id}/store", "store")->name("bank_question_save");
			Route::get("/{bank_category_id}/edit/{id}", "edit")->name("bank_question_edit");
			Route::post("/{bank_category_id}/update/{id}", "update")->name("bank_question_update");
			Route::get("/{bank_category_id}/delete/{id}", "destroy")->name("bank_question_delete");
			Route::post("/{bank_category_id}/deleteAll", "destroyAll")->name("bank_questions_delete_all");
		});
	});
	Route::controller("BankCategoryController")->prefix("bankCategories")->name("bank_categories_")->group(function () {
		Route::get("", "index")->name("index");
		Route::post("", "store")->name("save");
		Route::get("/create", "create")->name("create");
		Route::get("/{id}/edit", "edit")->name("edit");
		Route::put("/{id}", "update")->name("update");
		Route::get("/{id}/delete", "destroy")->name("destroy");
	});
	Route::prefix("certificates")->group(function () {
		Route::controller("CertificateController")->group(function () {
			Route::get("/courses", "courses")->name("certificates_courses");
			Route::get("/course/{courseID}", "index")->name("certificates");
			Route::get("/course/{courseID}/create", "create")->name("certificate_create");
			Route::post("/course/{courseID}/store", "store")->name("certificate_save");
			Route::get("/course/{courseID}/edit/{id}", "edit")->name("certificate_edit");
			Route::post("/course/{courseID}/update/{id}", "update")->name("certificate_update");
			Route::get("/course/{courseID}/delete/{id}", "destroy")->name("certificate_delete");
			Route::post("/course/{courseID}/deleteAll", "destroyAll")->name("certificates_delete_all");
			Route::post("/toggleStatus/{id}", "toggleStatus")->name("certificate_toggle_status");
		});
	});
	Route::prefix("exams")->group(function () {
		Route::controller("QuestionController")->group(function () {
			Route::get("/coursesExams", "coursesExams")->name("recorded_exams_courses");
			Route::get("/coursesExams/{courseID}/lectures", "coursesExamsLectures")->name("recorded_exams_lectures");
			Route::get("/coursesExams/{courseID}/lecture/{lectureID}", "coursesExamsUsers")->name("recorded_exams_users");
			Route::get("/coursesExams/{courseID}/lecture/{lectureID}/user/{userID}", "recordedExams")->name("recorded_exams");
			Route::get("/coursesExams/{courseID}/lecture/{lectureID}/user/{userID}/exam/{examID}", "recordedShow")->name("recorded_show");
		});
	});
	Route::controller("OfferController")->group(function () {
		Route::get("/getOffers", "getOffers")->name("getOffers");
		Route::get("/offers", "index")->name("offers");
		Route::get("/offer/create", "create")->name("offer_create");
		Route::get("offer/{id}", "show")->name("offer_show");
		Route::post("/offer/store", "store")->name("offer_save");
		Route::get("/offer/edit/{id}", "edit")->name("offer_edit");
		Route::post("/offer/update/{id}", "update")->name("offer_update");
		Route::get("/offer/delete/{id}", "destroy")->name("offer_delete");
	});

	Route::prefix("orders")->group(function () {
		Route::controller("OrderController")->group(function () {
			Route::get("/courses", "ordersCourses")->name("orders_courses");
			Route::get("/courses/lectures", "coursesLectures")->name("orders_courses_lectures");
			Route::get("/getLecture/{courseID}", "getLecture")->name("orders_get_lecture");
			Route::get("/course/{courseID}/lectures", "courseLectures")->name("orders_course_lectures");
			Route::get("/offers", "ordersOffers")->name("orders_offers");
			Route::post("/toggleStatus/{id}", "toggleStatus")->name("order_course_toggle_status");

			Route::get("/{type}", "index")->name("orders");
			Route::get("/{type}/create", "create")->name("order_create");
			Route::post("/{type}/store", "store")->name("order_save");
			Route::get("/{type}/edit/{id}", "edit")->name("order_edit");
			Route::post("/{type}/update/{id}", "update")->name("order_update");
			Route::get("/{type}/delete/{id}", "destroy")->name("order_delete");
			Route::post("/{type}/deleteAll", "destroyAll")->name("orders_delete_all");
		});
	});

	Route::controller("CouponController")->group(function () {
		Route::get("/getCoupons", "getCoupons")->name("get_coupons");
		Route::get("/coupons", "index")->name("coupons");
		Route::get("/coupon/create", "create")->name("coupon_create");
		Route::post("/coupon/store", "store")->name("coupon_save");
		Route::get("/coupon/edit/{id}", "edit")->name("coupon_edit");
		Route::get("/coupon/{id}", "show")->name("coupon_show");
		Route::post("/coupon/update/{id}", "update")->name("coupon_update");
		Route::get("/coupon/delete/{id}", "destroy")->name("coupon_delete");
		Route::post("/coupon/deleteAll", "destroyAll")->name("coupons_delete_all");
	});
	Route::get("/getComments/{type?}/{id?}", "CommentController@getComments")->name("getComments");
	Route::get("/comments/{type}", "CommentController@index")->name("comments_courses");
	Route::get("/comment/{id}", "CommentController@show")->name("comment_show");
	Route::get("/comment/delete/{id}", "CommentController@destroy")->name("comment_delete");
	Route::post("/comment/toggleStatus/{id}", "CommentController@toggleStatus")->name("comment_toggle_status");

	Route::controller("ReviewController")->group(function () {
		Route::get("/getReviews", "getReviews")->name("getReviews");
		Route::get("/reviews", "index")->name("reviews");
		Route::get("/review/create", "create")->name("review_create");
		Route::post("/review/store", "store")->name("review_save");
		Route::get("/review/{id}", "show")->name("review_show");
		Route::post("/review/toggleStatus/{id}", "toggleStatus")->name("review_toggle_status");
		Route::get("/review/edit/{id}", "edit")->name("review_edit");
		Route::post("/review/update/{id}", "update")->name("review_update");
		Route::get("/review/delete/{id}", "destroy")->name("review_delete");
	});

	Route::controller("SupportController")->group(function () {
		Route::get("/supports", "index")->name("supports");
		Route::get("/doneRead", "done_read")->name("support_done_read");
		Route::get("/unRead", "un_read")->name("support_un_read");
		Route::post("/doneContact/{id}", "doneContact")->name("done_contact");
		Route::get("/unContact", "un_contact")->name("support_un_contact");
		Route::post("/doneProblem/{id}", "doneProblem")->name("done_problem");
		Route::get("/doneProblem", "done_problem")->name("support_done_problem");
		Route::get("/unProblem", "un_problem")->name("support_un_problem");
		Route::get("/doneDeleted", "done_deleted")->name("support_done_deleted");
		Route::post("/getChat/{id}", "getChat")->name("get_chat");
		Route::post("/chatDelete/{id}", "chatDelete")->name("chat_delete");
		Route::get("/doneContact", "done_contact")->name("support_done_contact");
		Route::post("/forceDelete/{id}", "destroy")->name("support_force_delete");
		Route::post("/supportRestore/{id}", "restore")->name("support_restore");
	});
	Route::controller("CurrencyController")->group(function () {
		Route::get("/getCurrenciesApi", "getCurrencies")->name("get_currencies");
		Route::get("/currency", "index")->name("currency");
		Route::get("/currency/create", "create")->name("currency_create");
		Route::post("/currency/store", "store")->name("currency_save");
		Route::get("/currency/edit/{id}", "edit")->name("currency_edit");
		Route::post("/currency/update/{id}", "update")->name("currency_update");
		Route::get("/currency/delete/{id}", "destroy")->name("currency_delete");
		Route::post("/currency/deleteAll", "destroyAll")->name("currency_delete_all");
	});
	Route::controller("PaymentMethodController")->group(function () {
		Route::get("/getPaymentMethodsApi", "getPaymentMethods")->name("get_payment_method");
		Route::get("/payment_method", "index")->name("payment_method");
		Route::get("/payment_method/create", "create")->name("payment_method_create");
		Route::post("/payment_method/store", "store")->name("payment_method_save");
		Route::get("/payment_method/edit/{id}", "edit")->name("payment_method_edit");
		Route::post("/payment_method/update/{id}", "update")->name("payment_method_update");
		Route::get("/payment_method/delete/{id}", "destroy")->name("payment_method_delete");
		Route::post("/payment_method/deleteAll", "destroyAll")->name("payment_method_delete_all");
	});
	Route::controller("BankTransactionController")->group(function () {
		Route::post("/checkBankMoneyApi", "checkBankMoney")->name("check_bank_money");
		Route::post("/bank/transaction/store", "store")->name("bank_transaction_save");
		Route::get("/bank/transactions", "bankTransactions")->name("bank_transactions");
	});
	Route::controller("BankController")->group(function () {
		Route::get("/getBankApi", "getBank")->name("get_bank");
		Route::get("/bank", "index")->name("bank");
		Route::get("/bank/create", "create")->name("bank_create");
		Route::post("/bank/store", "store")->name("bank_save");
		Route::get("/bank/edit/{id}", "edit")->name("bank_edit");
		Route::post("/bank/update/{id}", "update")->name("bank_update");
		Route::get("/bank/delete/{id}", "destroy")->name("bank_delete");
		Route::post("/bank/deleteAll", "destroyAll")->name("bank_delete_all");
	});
	Route::controller("ExpensesController")->group(function () {
		Route::get("/getExpensesApi", "getExpenses")->name("get_expenses");
		Route::get("/expenses", "index")->name("expenses");
		Route::get("/expenses/create", "create")->name("expenses_create");
		Route::get("/expenses/details", "details")->name("expenses_details");
		Route::post("/expenses/store", "store")->name("expenses_save");
		Route::get("/expenses/edit/{id}", "edit")->name("expenses_edit");
		Route::post("/expenses/update/{id}", "update")->name("expenses_update");
		Route::get("/expenses/delete/{id}", "destroy")->name("expenses_delete");
		Route::post("/expenses/deleteAll", "destroyAll")->name("expenses_delete_all");
		Route::post("/expenses/pay_expenses", "pay_expenses")->name("pay_expenses");
	});
	Route::controller("AskController")->group(function () {
		Route::get("/asks", "index")->name("asks");
		Route::get("/ask/create", "create")->name("ask_create");
		Route::post("/ask/store", "store")->name("ask_save");
		Route::get("/ask/edit/{id}", "edit")->name("ask_edit");
		Route::get("/ask/{id}", "show")->name("ask_show");
		Route::post("/ask/update/{id}", "update")->name("ask_update");
		Route::get("/ask/delete/{id}", "destroy")->name("ask_delete");
		Route::post("/ask/deleteAll", "destroyAll")->name("asks_delete_all");
	});
	Route::controller("SettingController")->group(function () {
		Route::get("/getSettings", "getSettings")->name("getSettings");
		Route::get("/settings", "index")->name("settings");
		Route::get("/setting/backupDatabase", "backupDatabase")->name("setting_backup_database");
		Route::get("/setting/clearCash", "clearCash")->name("setting_clear_cash");
		Route::get("/setting/create", "create")->name("setting_create");
		Route::post("/setting/store", "store")->name("setting_save");
		Route::get("/setting/{id}", "show")->name("setting_show");
		Route::get("/setting/edit/{id}", "edit")->name("setting_edit");
		Route::post("/setting/update/{id}", "update")->name("setting_update");
		Route::get("/setting/delete/{id}", "destroy")->name("setting_delete");
	});
	Route::controller("TransactionController")->group(function () {
		Route::get("/transactions", "index")->name("transactions");
	});
	Route::controller("ReportsController")->prefix("reports")->name("reports_")->group(function () {
		Route::get("/trainingHours", "trainingHours")->name("training_hours");
		Route::get("/teachers", "teachers")->name("teachers");
		Route::get("/courses", "courses")->name("courses");
		Route::get("/lectures", "lectures")->name("lectures");
		Route::get("/offers", "offers")->name("offers");
		Route::get("/students", "students")->name("students");
	});
	Route::get("/markAsRead", function () {
		auth()
			->user()
			->unreadNotifications->markAsRead();
		return redirect()->back();
	})->name("mark_all");

	/* Route::get('/test', function () {
	$users = User::where('roles_name', '!=', null)->get();
}); */
});
//  language
Route::get("/languageConverter/{locale}", function ($locale) {
	if (!in_array($locale, ["en", "ar"])) {
		return abort(400);
	}
	session()->put("locale", $locale);
	//	app()->setLocale($locale);
	return redirect()->back();
})->name("languageConverter");
// authentication
Route::controller("Auth")
	->middleware("guest")
	->group(function () {
		Route::get("/login", "login")->name("login");
		Route::post("/login/checkLogin", "checkLogin")->name("check_login");
	});
Route::post("/logout", "Auth@logout")->name("logout");
