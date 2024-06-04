<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/testapi', 'APIController@test')->name('api.test');

Route::get('/getdistricts/{softtoken}/', 'APIController@getDistricts')->name('api.getdistrics');
Route::get('/getupazillas/{softtoken}/{district_id}', 'APIController@getUpazillas')->name('api.getupazillas');
Route::get('/gethospitals/{softtoken}/{hospital_type}/{district_id}', 'APIController@getHospitalsDistrict')->name('api.gethospitalsdistrict');
Route::get('/gethospitals/{softtoken}/{hospital_type}/{district_id}/{upazilla_id}', 'APIController@getHospitalsUpazilla')->name('api.gethospitalsupazilla');
Route::get('/getmedicaldepartments/{softtoken}', 'APIController@getMedicalDepartments')->name('api.getmedicaldepartments');
Route::get('/getmedicalsymptoms/{softtoken}', 'APIController@getMedicalSymptoms')->name('api.getmedicalsymptoms');
Route::get('/getdoctors/{softtoken}/{medicalitemid}/{datatype}/{district_id}', 'APIController@getDoctorsDistrict')->name('api.getdoctorsdistrict');
Route::get('/getdoctors/{softtoken}/{medicalitemid}/{datatype}/{district_id}/{upazilla_id}', 'APIController@getDoctorsUpazilla')->name('api.getdoctorsupazilla');
Route::get('/gethospitaldoctors/{softtoken}/{hospital_id}', 'APIController@getHospitalDoctors')->name('api.gethospitaldoctors');
Route::get('/getblooddonors/{softtoken}/{category}/{district_id}', 'APIController@getBloodDonorsDistrict')->name('api.getblooddonorsdistrict');
Route::get('/getblooddonors/{softtoken}/{category}/{district_id}/{upazilla_id}', 'APIController@getBloodDonorsUpazilla')->name('api.getblooddonorsupazilla');
Route::get('/getambulances/{softtoken}/{district_id}', 'APIController@getAmbulancesDistrict')->name('api.getambulancesdistrict');
Route::get('/getambulances/{softtoken}/{district_id}/{upazilla_id}', 'APIController@getAmbulancesUpazilla')->name('api.getambulancesupazilla');
Route::get('/geteshebas/{softtoken}', 'APIController@getEshebas')->name('api.geteshebas');
Route::get('/getadminofficers/{softtoken}/{district_id}', 'APIController@getAdminOfficers')->name('api.getadminofficers');
Route::get('/getpoliceofficers/{softtoken}/{district_id}', 'APIController@getPoliceOfficers')->name('api.getpoliceofficers');









Route::post('/generateotp', 'APIController@generateOTP')->name('api.generateotp');
Route::post('/loginorcreate', 'APIController@loginOrCreate')->name('api.loginorcreate');

Route::get('/checkuid/{softtoken}/{phonenumber}', 'APIController@checkUid')->name('api.checkuid');
Route::get('/checkpackagevalidity/{softtoken}/{phonenumber}', 'APIController@checkPackageValidity')->name('api.checkpackagevalidity');
Route::post('/adduser', 'APIController@addUser')->name('api.adduser');
Route::post('/addonesignaldata', 'APIController@addOneSignalData')->name('api.addonesignaldata');
Route::post('/updateuser', 'APIController@updateUser')->name('api.updateuser');
Route::post('/notification/single', 'APIController@sendSingleNotification')->name('api.sendsinglenotification');
Route::get('/notification/test', 'APIController@testNotification')->name('api.testnotification');

Route::get('/testcache', 'APIController@testCache')->name('api.testcache');
Route::get('/getcourses/{softtoken}/{coursetype}', 'APIController@getCourses')->name('api.getcourses');
Route::get('/getcourses/exams/{softtoken}/{id}', 'APIController@getCourseExams')->name('api.getcourses.exams');
Route::get('/getothercourses/exams/{softtoken}/{coursetype}', 'APIController@getOtherCourseExams')->name('api.getothercourses.exams');
Route::get('/getcourses/exam/{softtoken}/{id}/questions', 'APIController@getCourseExamQuestions')->name('api.getcourses.exam.questions');
Route::get('/gettopicwise/exam/{softtoken}/{id}/questions', 'APIController@getTopicExamQuestions')->name('api.gettopicwise.exam.questions');
Route::get('/gettopics/{softtoken}', 'APIController@getTopics')->name('api.gettopics');
Route::get('/getpackages/{softtoken}', 'APIController@getPackages')->name('api.getpackages');
Route::post('/payment/proceed', 'APIController@paymentProceed')->name('api.paymentproceed');

Route::post('/message/store', 'APIController@storeMessage')->name('api.storemessage');

Route::get('/getpaymenthistory/{softtoken}/{phonenumber}', 'APIController@getPaymentHistory')->name('api.getpaymenthistory');

Route::get('/getmaterials/{softtoken}', 'APIController@getMaterials')->name('api.getmaterials');
Route::get('/getmaterials/single/{softtoken}/{id}', 'APIController@getSingleMaterial')->name('api.getsinglematerial');

Route::post('/addexamresult', 'APIController@addExamResult')->name('api.addexamresult');
Route::get('/meritlist/{softtoken}/{course_id}/{exam_id}', 'APIController@getMeritList')->name('api.getmeritlist');
Route::post('/reportquestion', 'APIController@reportQuestion')->name('api.reportquestion');
Route::get('/getexamcategories/{softtoken}', 'APIController@getExamCategories')->name('api.getexamcategories');
Route::get('/getquestionbank/exams/{softtoken}/{getexamcategory}', 'APIController@getQBCatWise')->name('api.getquestionbank.exams');