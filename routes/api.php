<?php

use App\Http\Controllers\Api\V1\AdApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AdvertiserApi;
use App\Http\Controllers\Api\V1\CategoryApi;
use App\Http\Controllers\Api\V1\TagApi;
use App\Models\Ad;
use Carbon\Carbon;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function () {






		    Route::apiResource("advertiser",AdvertiserApi::class, ["as" => "api.advertiser"]);
            Route::post("advertiser/multi_delete",[AdvertiserApi::class ,"multi_delete"]);

			Route::apiResource("category",CategoryApi::class, ["as" => "api.category"]);
            Route::post("category/multi_delete",[CategoryApi::class ,"multi_delete"]);


            Route::apiResource("ad",AdApi::class, ["as" => "api.ad"]);
            Route::post("ad/multi_delete",[AdApi::class ,"multi_delete"]);
            Route::post("ad/filters",[AdApi::class,'AdFilters']);


			Route::apiResource("tag",TagApi::class, ["as" => "api.tag"]);
            Route::post("tag/multi_delete",[TagApi::class ,"multi_delete"]);




});



