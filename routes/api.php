<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Masafr\MasafrController;
use App\Http\Controllers\MyFatoorahControlller;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes test comment
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'common'], function () {
    Route::post('/customer-services', [Controller::class, 'CustomerService']);
    Route::get('/get-advertisings', [Controller::class, 'getAdvertisings']);

});



Route::group(['prefix' => 'user', 'middleware' => 'guest:user-api'], function () {
    Route::post('/all-trips', [Controller::class, 'getAllTrips']);
    Route::post('/create-account', [UserController::class, 'createUser']);
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/varify-account', [UserController::class, 'varifyAccount']);
    Route::post('/resend-verfiy-code', [UserController::class, 'resendVerfiyCode']);
});

Route::group(['prefix' => 'masafr', 'middleware' => 'guest:masafr-api'], function () {
    Route::post('/all-request-services', [Controller::class, 'getAllRequestServices']);
    Route::post('/create-account', [MasafrController::class, 'createMasafr']);
    Route::post('/login', [MasafrController::class, 'login']);
    Route::post('/add-masafr-info', [MasafrController::class, 'addMasafrInfo']);
    Route::post('/varify-account', [MasafrController::class, 'varifyAccount']);
    Route::post('/resend-verfiy-code', [MasafrController::class, 'resendVerfiyCode']);
});

Route::group(['prefix' => 'admin'], function () {
    Route::post('/login', [AdminController::class, 'login']);
});

// Route::group(['prefix' => 'auth/user'], function () {

Route::group(['prefix' => 'auth/user', 'middleware' => ['checkAuth:user-api', 'checkVerfication:user-api']], function () {
    Route::post('/get-user-info', [UserController::class, 'getUserInfo']);
    Route::post('/store-transaction', [UserController::class, 'storeTransaction']);
    Route::post('/get-transactions', [UserController::class, 'getTransactions']);
    Route::post('/make-comment', [UserController::class, 'makeComment']);
    Route::post('/get-comments', [UserController::class, 'getComments']);
    Route::post('/update-comment', [UserController::class, 'updateComment']);
    Route::post('/update-user-info', [UserController::class, 'updatePersonalInfo']);
    Route::post('/make-complain', [UserController::class, 'makeComplain']);
    Route::post('/get-complain-room', [UserController::class, 'getComplainsRoom']);
    Route::post('/get-complains-list', [UserController::class, 'getComplainsList']);
    Route::post('/get-notifications', [UserController::class, 'getNotifications']);
    Route::post('/delete-notification', [UserController::class, 'deleteNotification']);
    Route::post('/create-request-service', [UserController::class, 'createRequestService']);
    Route::post('/update-request-service', [UserController::class, 'updateRequestService']);
    Route::post('/delete-request-service', [UserController::class, 'deleteRequestService']);
    Route::post('/get-trip', [UserController::class, 'getTrip']);
    Route::post('/send-message', [UserController::class, 'sendMessage']);
    Route::post('/get-messages', [UserController::class, 'getMessages']);
    Route::post('/get-chat-rooms', [UserController::class, 'getChatRooms']);
    Route::post('/create-chat-rooms', [UserController::class, 'createChatRooms']);
    Route::post('/search-trips', [UserController::class, 'searchTrips']);
    Route::post('/all-free-services', [UserController::class, 'getAllFreeServices']);
    Route::post('/search-free-service', [UserController::class, 'searchFreeService']);
    Route::post('/get-all-main-request-categorires', [UserController::class, 'getAllMainRequestCategorires']);
    Route::post('/get-all-main-trip-categorires', [UserController::class, 'getAllMainTripCategorires']);
    Route::post('/negotiation', [UserController::class, 'negotiation']);
    Route::post('/get-fatoorah', [UserController::class, 'getFatoorah']);
    Route::post('/resopnse-to-fatoorah', [UserController::class, 'resopnseToFatoorah']);
    Route::post('/send-cancel-negotiation', [UserController::class, 'sendCancelNegotiation']);
    Route::post('/cancel-negotiation', [UserController::class, 'cancelNegotiation']);
    Route::post('/masafr-info', [UserController::class, 'masafrInfo']);
    Route::post('/pronunciation-statement', [UserController::class, 'pronunciationStatement']);
    Route::post('/update-balance', [UserController::class, 'updateBalance']);
    Route::post('/get-windows', [UserController::class, 'getWindows']);
    Route::post('/confirm-admin-window', [UserController::class, 'confirmAdminWindow']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/get-my-request-services', [UserController::class, 'getMyRequestServices']);
    Route::get('/get-advertisings', [UserController::class, 'getAdvertisings']);

    Route::get('/get-private-msgs/{chatId}', [UserController::class, 'getPrivateMsgs']);

    Route::post('/pay', [MyFatoorahControlller::class, 'pay']);
});

// Route::group(['prefix' => 'auth/masafr'], function () {

Route::group(['prefix' => 'auth/masafr', 'middleware' => ['checkAuth:masafr-api', 'checkVerfication:masafr-api']], function () {
    Route::post('/get-masafr-info', [MasafrController::class, 'getMasafrInfo']);
    Route::post('/update-masafr-info', [MasafrController::class, 'updatePersonalInfo']);
    Route::post('/store-transaction', [MasafrController::class, 'storeTransaction']);
    Route::post('/get-transactions', [MasafrController::class, 'getTransactions']);
    Route::post('/make-comment', [MasafrController::class, 'makeComment']);
    Route::post('/get-comments', [MasafrController::class, 'getComments']);
    Route::post('/update-comment', [MasafrController::class, 'updateComment']);
    Route::post('/make-complain', [MasafrController::class, 'makeComplain']);
    Route::post('/get-complain-room', [MasafrController::class, 'getComplainsRoom']);
    Route::post('/get-complains-list', [MasafrController::class, 'getComplainsList']);
    Route::post('/create-trip', [MasafrController::class, 'createTrip']);
    Route::post('/update-trip', [MasafrController::class, 'updateTrip']);
    Route::post('/delete-trip', [MasafrController::class, 'deleteTrip']);
    Route::post('/get-notifications', [MasafrController::class, 'getNotifications']);
    Route::post('/delete-notification', [MasafrController::class, 'deleteNotification']);
    Route::post('/get-request-service', [MasafrController::class, 'getRequestService']);
    Route::post('/send-message', [MasafrController::class, 'sendMessage']);
    Route::post('/get-messages', [MasafrController::class, 'getMessages']);
    Route::post('/create-chat-rooms', [MasafrController::class, 'createChatRooms']);
    Route::post('/get-chat-rooms', [MasafrController::class, 'getChatRooms']);
    Route::post('/create-free-service', [MasafrController::class, 'createFreeService']);
    Route::post('/get-my-free-services', [MasafrController::class, 'getMyFreeServices']);
    Route::post('/update-free-service', [MasafrController::class, 'updateFreeService']);
    Route::post('/delete-free-service', [MasafrController::class, 'deleteFreeService']);
    Route::post('/get-all-main-trip-categorires', [MasafrController::class, 'getAllMainTripCategorires']);
    Route::post('/get-all-main-request-categorires', [MasafrController::class, 'getAllMainRequestCategorires']);
    
    Route::post('/negotiation', [MasafrController::class, 'negotiation']);
    Route::post('/finish-negotiation', [MasafrController::class, 'finishNegotiation']);
    Route::post('/send-cancel-negotiation', [MasafrController::class, 'sendCancelNegotiation']);
    Route::post('/cancel-negotiation', [MasafrController::class, 'cancelNegotiation']);
    Route::post('/create-fatoorah', [MasafrController::class, 'createFatoorah']);
    Route::post('/update-fatoorah', [MasafrController::class, 'updateFatoorah']);
    Route::post('/user-info', [MasafrController::class, 'userInfo']);
    Route::post('/pronunciation-statement', [MasafrController::class, 'pronunciationStatement']);
    Route::post('/get-my-trips', [MasafrController::class, 'getMyTrips']);
    Route::post('/confirm-admin-window', [MasafrController::class, 'confirmAdminWindow']);
    Route::post('/get-windows', [UserController::class, 'getWindows']);
    Route::post('/search-request-service', [MasafrController::class, 'searchRequestService']);
    Route::post('/update-balance', [MasafrController::class, 'updateBalance']);
    Route::post('/logout', [MasafrController::class, 'logout']);
    Route::get('/get-advertisings', [MasafrController::class, 'getAdvertisings']);

    Route::get('/get-private-msgs/{chatId}', [UserController::class, 'getPrivateMsgs']);
    Route::post('/pay', [MyFatoorahControlller::class, 'pay']);
});



// Route::group(['prefix' => 'auth/admin'], function () {
Route::group(['prefix' => 'auth/admin', 'middleware' => ['checkAuth:admin-api']], function () {
    Route::post('/create-admin', [AdminController::class, 'createAdmin']);
    Route::post('/get-admin-info', [AdminController::class, 'me']);
    Route::post('/main-page-info', [AdminController::class, 'mainPageInfo']);
    Route::post('/create-new-request-categorie', [AdminController::class, 'createNewRequestCategorie']);
    Route::post('/create-new-trip-categorie', [AdminController::class, 'createNewTripCategorie']);
    Route::post('/create-copon', [AdminController::class, 'createCopon']);
    Route::post('/get-all-copons', [AdminController::class, 'getAllCopons']);
    Route::post('/get-copon', [AdminController::class, 'getCopon']);
    Route::post('/update-copon', [AdminController::class, 'updateCopon']);
    Route::post('/get-all-copon-user', [AdminController::class, 'getAllCoponUser']);
    Route::post('/delete-copon', [AdminController::class,'deleteCopon']);
    Route::post('/create-advertisings', [AdminController::class, 'createAdvertisings']);
    Route::post('/get-all-advertisings', [AdminController::class, 'getAllAdvertisings']);
    Route::get('/get-advertising/{advertisingId}', [AdminController::class, 'getAdvertising']);
    Route::put('/toggle-advertising/{advertisingId}', [AdminController::class, 'toggleAdvertising']);
    Route::put('/update-advertising/{advertisingId}', [AdminController::class, 'updateAdvertising']);
    Route::post('/delete-advertising', [AdminController::class,'deleteAdvertising']);
    Route::post('/get-person-update-queue', [AdminController::class, 'getPersonUpdateQueue']);
    Route::post('/response-update-queue', [AdminController::class, 'responseUpdateQueue']);
    Route::post('/update-complain', [AdminController::class, 'updateComplain']);
    Route::post('/get-all-complain', [AdminController::class, 'getAllComplains']);
    Route::post('/get-all-chats', [AdminController::class, 'getAllChats']);
    Route::post('/send-notifications-or-emails', [AdminController::class, 'sendNotificationsOrMails']);
    Route::post('/send-notification', [AdminController::class, 'sendNotification']);
    Route::post('/get-trips', [AdminController::class, 'getTrips']);
    Route::post('/get-requests', [AdminController::class, 'getRequests']);
    Route::post('/get-request-responsers', [AdminController::class, 'getRequestResponsers']);
    Route::post('/get-users', [AdminController::class, 'getUsers']);
    Route::post('/get-user-info', [AdminController::class, 'getUserInfo']);
    Route::get('/get-users-name', [AdminController::class, 'getUsersName']);
    Route::get('/search-users-name/{userName}', [AdminController::class, 'searchUsersName']);
    Route::get('/users-foreigners',[AdminController::class,'usersForeigners']);
    Route::get('/users-trusts',[AdminController::class,'usersTrusts']);
    Route::post('/update-user', [AdminController::class,'updateUser']);
    Route::post('/delete-user', [AdminController::class,'deleteUser']);
    Route::post('/get-all-fatoorahs', [AdminController::class, 'getAllFatoorahs']);
    Route::post('/get-transactions-for-fiscal-year', [AdminController::class, 'getTransactionsFiscalYear']);
    Route::post('/get-all-windows', [AdminController::class, 'getAllWindows']);
    Route::post('/delete-windows', [AdminController::class, 'deleteWindows']);
    Route::post('/get-rollbacks-money', [AdminController::class, 'getRollbackMoney']);
    Route::post('/get-rollbacks-requests', [AdminController::class, 'getRollbackRequests']);
    Route::post('/update-application-settings', [AdminController::class, 'updateApplicationSettings']);
    Route::post('/get-trusted-info', [AdminController::class, 'getTrustedInfo']);
    Route::post('/create-user', [AdminController::class, 'createUser']);
    Route::post('/logout', [AdminController::class, 'logout']);

    Route::post('/get-masafrs', [AdminController::class, 'getMasafrs']);
    Route::post('/get-masafr-info', [AdminController::class, 'getMasafrInfo']);
    Route::post('/get-all-masafr-trips', [AdminController::class, 'getAllMasafrTrips']);
    Route::post('/update-masafr', [AdminController::class,'updateMasafr']);


    // #######################
    Route::get('/masafrs',[AdminController::class,'masafrs']);
    Route::get('/masafrs-foreigners',[AdminController::class,'masafrsForeigners']);
    Route::get('/masafrs-trusts',[AdminController::class,'masafrsTrusts']);
    Route::get('/get-masafrs-name', [AdminController::class, 'getMasafrsName']);

    Route::get('/search-masafrs-name/{masafrName}', [AdminController::class, 'searchMasafrsName']);

    Route::post('/delete-masafr', [AdminController::class,'deleteMasafr']);

    Route::post('/delete-request-service', [AdminController::class,'deleteRequestService']);
    Route::post('/delete-trip', [AdminController::class,'deleteTrip']);

    Route::post('/get-a-complain', [AdminController::class,'getAComplain']);
    Route::get('/application-settings', [AdminController::class,'applicationSettings']);

    Route::get('/user-request-statistics/{userId}',[AdminController::class,'userRequestStatistics']);
    Route::get('/masafr-trip-statistics/{masafrId}',[AdminController::class,'masafrTripStatistics']);


});


Route::get('/error-pay', [MyFatoorahControlller::class, 'errorPay']);
Route::get('/success-pay', [MyFatoorahControlller::class, 'successPay']);
