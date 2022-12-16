<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\FeaturesController;
use App\Http\Controllers\FeatureTicketController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\PlaneController;
use App\Http\Controllers\RestorePostController;
use App\Http\Controllers\SubRenewalsController;
use App\Http\Controllers\JobsPosterController;
use App\Http\Controllers\UserCvController;

// Api on user: by id, gender,count
// Api on job: by id, title, job-type, count,
// List down from supervisor to do on this.
// Ask him about subscriptions, continue or not.
// ==He will demo:

// Create api


Route::post('/register', [UserController::class, 'register']);
Route::post('/registerEmail/{email}', [MailController::class, 'registerEmail']);
Route::post('/mailToNotifyUserSub', [MailController::class, 'mailToNotifyUserSub']);

Route::apiResource('/user', UserController::class);
Route::apiResource('/renewal', SubRenewalsController::class);

Route::get('/count', [UserController::class, 'count']);
Route::get('/getUser/{id}', [UserController::class, 'getUserById']);
Route::put('/updateImg/{id}', [UserController::class, 'updateImg']);
Route::put('/changePassword/{id}', [UserController::class, 'changePassword']);
Route::post('/switchTo', [SubscribeController::class, 'switchTo']);
Route::put('/deActiveSub/{id}', [SubscribeController::class, 'deActiveSub']);


// transaction controller
Route::apiResource('/payment', TransactionController::class);

// user plane
Route::apiResource('/plane', PlaneController::class);

//get user img
Route::get("getUserImg/{id}", [UserController::class, 'getUserImg']);


Route::apiResource('/tickets', FeatureTicketController::class);
Route::get('/userSubInfo/{sub_id}', [FeatureTicketController::class, 'userSubInfo']);
Route::apiResource('/restorePost', RestorePostController::class);

Route::get('/restoreCharge', [FeatureTicketController::class, 'restoreCharge']);
Route::put('/setSubToExpired', [SubscribeController::class, 'setSubToExpired']);

// get all not expire jobs
Route::get('/setJobToExpired', [JobsPosterController::class, 'setJobToExpired']);
Route::get('/mail', [MailController::class, 'mailToNotifyUserSub']);

// user trail
Route::post('/trail', [SubscribeController::class, 'userTrail']);

// get all job title
Route::get('/jobTitle', [JobsPosterController::class, 'getAllJobsTitle']);
Route::get('/companyName', [JobsPosterController::class, 'getAllCompanyName']);
// get specific jobs
Route::get('/job/{id}', [JobsPosterController::class, 'getSpecificJobs']);

Route::get('/getAllUsers', [UserController::class, 'getAllUsers']);


// payment route==================

// user subscription
Route::apiResource('/subscription', SubscribeController::class);
Route::get('/UserJob/{id}', [UserController::class, 'getUserJob']);

// get user plan
Route::apiResource('/plan', PlaneController::class);

//========
//login vai google forma
Route::post('/loginViaGoogle', [LoginController::class, 'loginViaGoogle']);
// minus 1 user's charge after they posted job
Route::put('/minusCharge/{id}', [SubscribeController::class, 'minusCharge']);
Route::apiResource('/userPlane', UserPlaneController::class);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);

//get all balance transfer history
Route::get("/paymentHistory", [PaymentController::class, 'getAllBalanceTransfer']);

// CV
Route::apiResource("/cv", UserCvController::class);

// Job
Route::apiResource('/jobposter', JobsPosterController::class);
//features
Route::apiResource('/features', FeaturesController::class);
Route::get('/jobposterId/{id}', [JobsPosterController::class, 'getJobById']);

// update verify code
Route::put('/verifyCode', [UserController::class, 'verifyCode']);
Route::put('/resetPsw/{id}', [UserController::class, 'resetPassword']);
Route::get('/userBy/{email}', [UserController::class, 'getUserByEmail']);
Route::post('/sendCode/{email}', [MailController::class, 'sendVerifyCode']);
Route::put('/setUserToAdmine/{email}', [MailController::class, 'setUserToAdmine']);
Route::put('/toAdmine/{id}', [UserController::class, 'setUserToAdmine']);

Route::get("/hash/{amount}", [TransactionController::class, "getHash"]);
Route::get("/getTranID", [TransactionController::class, 'getTranID']);
Route::get("/timestamp", [TransactionController::class, 'getTimestamp']);

Route::apiResource('/transaction', TransactionController::class);

Route::post("/payDetails", [TransactionController::class, 'getPyamentResponse']);
Route::get("/getNewHash/{req_time}", [TransactionController::class, 'getNewHash']);
Route::post("/QRcode", [TransactionController::class, 'QRcode']);

// get only active status only
Route::get("current_scubscribe/{id}", [SubscribeController::class, 'user_current_scubscribe']);

// close job
Route::put("/closeJob/{id}", [JobsPosterController::class, 'closeJob']);
