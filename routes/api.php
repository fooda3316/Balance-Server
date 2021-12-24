<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuyCont;
use App\Http\Controllers\ChargeCont;
use App\Http\Controllers\ImageCont;
use App\Http\Controllers\PhonesCont;
use App\Http\Controllers\PostCont;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileCont;
use App\Http\Controllers\SendBalanceCont;
use App\Http\Controllers\SendingShowCont;
use App\Http\Controllers\SendScratchCont;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\DownloadCont;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//Route::get('/welcome', function () {
//    $responseData['error'] = true;
//    $responseData['message'] = 'user has not been found';
//    return response()->json($responseData);
//});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get("allServices", [ServiceController::class, "index"]);


// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/displaySellHistory/{id}', [BuyCont::class, 'displaySellHistory']);
 //   Route::put('/saveChargeOp/{userId}', [ChargeCont::class, 'saveChargeOp']);

Route::post('/updatePhone', [PhonesCont::class, 'updatePhone']);
Route::get('/sentBalance/{id}', [SendingShowCont::class, 'sentBalance']);
Route::get('/receivedBalance/{id}', [SendingShowCont::class, 'receivedBalance']);
Route::delete('/destroyBalance/{id}', [SendingShowCont::class, 'destroyBalance']);

Route::get('/showProfile/{id}', [ProfileCont::class, 'showProfile']);
Route::delete('/destroyPhone/{id}', [PhonesCont::class, 'destroyPhone']);

// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::put('/saveChargeOp/{id}', [ChargeCont::class, 'saveChargeOp']);
    Route::put('/chargeOtherClint', [ChargeCont::class, 'chargeOtherClint']);
    Route::get('/displayChargeHistory/{id}', [ChargeCont::class, 'displayChargeHistory']);

    Route::get('/displayChargeOp/{id}', [ChargeCont::class, 'displayChargeOp']);

    Route::put('/buyBalance', [BuyCont::class, 'buyBalance']);
    Route::put('/buyScratch', [BuyCont::class, 'buyScratch']);
    Route::get('/getUserBalance/{id}', [BuyCont::class, 'getUserBalance']);

    Route::post("storeService", [ServiceController::class, "store"]);

    Route::put('/sendBalance', [SendBalanceCont::class, 'sendBalance']);
    Route::put('/sendScratch', [SendScratchCont::class, 'sendScratch']);
    Route::get('/lookForClint/{phone}', [SendBalanceCont::class, 'lookForClint']);



    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/uploadImage', [ImageCont::class, 'uploadImage']);
Route::get('downloadFile/{file}',[DownloadCont::class, 'downloadFile']);

});




