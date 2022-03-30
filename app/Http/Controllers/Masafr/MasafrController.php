<?php

namespace App\Http\Controllers\Masafr;

use App\Http\Controllers\Controller;
use App\Mail\VerficationMail;
use App\Models\Admin\ApllicationSetting;
use App\Models\Admin\NotificationOrMailPerson;
use App\Models\Admin\TripCategorie;
use App\Models\Common\AdminNotificationOrEmail;
use App\Models\Common\Complain;
use App\Models\Common\MessageObject;
use App\Models\Common\RequestTrip;
use App\Models\Common\UpdateQeueu;
use App\Models\Masafr\Fatoorah;
use App\Models\Masafr\FatoorahList;
use App\Models\Masafr\FreeService;
use App\Models\Masafr\FreeServicePlace;
use App\Models\Masafr\Masafr;
use App\Models\Masafr\TripDays;
use App\Models\Masafr\Trips;
use App\Models\Masafr\TripWays;
use App\Models\Masafr\UpdateMasafarQueue;
use App\Models\User\RequestService;
use App\Models\User\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
// use Validator;
use Auth;
use JWTAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Nexmo\Laravel\Facade\Nexmo;
use DateTime;
use Carbon;
use Illuminate\Support\Facades\Validator;

class MasafrController extends Controller
{

    use GeneralTrait;
    public function login(Request $request)
    {
        try {
            $loginType = 'phone';
            $rules = [
                'phone' => 'required|exists:masafr,phone',
                'password' => 'required'
            ];
            if ($request->has('email')) {
                $loginType = 'email';
                $rules = [
                    'email' => 'required|email|exists:masafr,email',
                    'password' => 'required'
                ];
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $cardintions = $request->only([$loginType, 'password']);
            $token = Auth::guard('masafr-api')->attempt($cardintions);
            if (!$token) {
                return $this->returnError('200', 'fail');
            }
            $masafr = Auth::guard('masafr-api')->user();
            $masafr->token = $token;
            return $this->returnSuccessMessage($masafr);
        } catch (\Exception $e) {
            return $this->returnError('201', $e->getMessage());
        }
    }

    public function getMasafrInfo()
    {
        try {
            $MasafrId = Auth()->user()->id;
            $masafr = auth()->user();
            $negativePoints = Complain::where('masafr_id', $MasafrId)
                ->where('masafr_negative', 1)
                ->get()->count();
            $complains = Complain::where('masafr_id', $MasafrId)->get()->count();
            $requests = Trips::where('masafr_id', $MasafrId)->get()->count();
            $masafr['negative_points_count'] = $negativePoints;
            $masafr['complains_count'] = $complains;
            $masafr['trip_count'] = $requests;
            return $this->returnData('data', $masafr);
        } catch (\Exception $e) {
            return $this->returnError('201', $e->getMessage());
        }
    }


    public function createMasafr(Request $request)
    {
        // return $request;
        try {
            $rules = [
                'email' => 'required|email|unique:masafr,email',
                'country_code' => 'required',
                'phone' => 'required|unique:masafr,phone',
                'name' => 'required|min:4',
                'gender' => 'required',
                'password' => 'required|min:4',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $file_name = null;
            if ($request->hasFile('photo')) {
                $file_name  = $this->saveImage($request->photo, 'masafrs');
            }

            $code =  rand(100000, 999999);
            $masafrID =  Masafr::insertGetId([
                'email' => $request->email,
                'country_code' => $request->country_code,
                'phone' => $request->phone,
                'name' => $request->name,
                'gender' => $request->gender,
                'password' => bcrypt($request->password),
                'verification_code' => $code,
                'photo' => $file_name,
                'nationality' => $request->nationality,
                'last_send_verify_code' => new DateTime()
            ]);
            if ($request->country_code == '966') {
                //sms
                Nexmo::message()->send([
                    'to' => '+201210732005',
                    'from' => '+201287006309',
                    'text' => 'Your Verification Code In Application Tawsel M3a Mosafar Is ' . $code
                ]);
            } else {
                Mail::to($request->email)->send(new VerficationMail($code, $request->name, $request->email));
            }
            return $this->returnData('masafr id', $masafrID);
        } catch (\Exception $e) {
            return $this->returnError('200', $e->getMessage());
        }
    }

    public function addMasafrInfo(Request $request)
    {
        try {
            $masafr = Masafr::find($request->id);
            if (!$masafr) {
                return $this->returnError('202', 'fail');
            }

            $file_name_id_photo = null;
            $file_name_driving_license_photo = null;
            $file_name_car_image_east = null;
            $file_name_car_image_west = null;
            $file_name_car_image_north = null;

            if ($request->hasFile('id_photo')) {
                $file_name_id_photo  = $this->saveImage($request->id_photo, 'masafrs_id');
            }
            if ($request->hasFile('driving_license_photo')) {
                $file_name_driving_license_photo  = $this->saveImage($request->driving_license_photo, 'driving_licenses');
            }
            if ($request->hasFile('car_image_east')) {
                $file_name_car_image_east  = $this->saveImage($request->car_image_east, 'cars');
            }
            if ($request->hasFile('car_image_west')) {
                $file_name_car_image_west  = $this->saveImage($request->car_image_west, 'cars');
            }
            if ($request->hasFile('car_image_north')) {
                $file_name_car_image_north  = $this->saveImage($request->car_image_north, 'cars');
            }

            $masafr->update([
                'national_id_number' => $request->national_id_number,
                'nationality' => $request->nationality,
                'car_name' => $request->car_name,
                'car_model' => $request->car_model,
                'car_number' => $request->car_number,
                'id_photo' => $file_name_id_photo,
                'driving_license_photo' => $file_name_driving_license_photo,
                'car_image_east' => $file_name_car_image_east,
                'car_image_west' => $file_name_car_image_west,
                'car_image_north' => $file_name_car_image_north,
                'nationality' => $request->nationality
            ]);
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError('200', 'fail');
        }
    }

    public function createTrip(Request $request)
    {
        // return $request;
        try {
            DB::beginTransaction();
            $rules = [
                'type_of_trip' => 'required',
                //'type_of_services' => 'required|numeric',
                'from_place' => 'required',
                // 'description' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $tripID = Trips::insertGetId([
                'masafr_id' => Auth::user()->id,
                'type_of_trips' => $request->type_of_trip,
                'type_of_services' => $request->type_of_services ?? -1,
                'only_women' => $request->only_women ?? 0,
                'from_place' => $request->from_place,
                'from_longitude' => $request->from_longitude,
                'from_latitude' => $request->from_latitude,
                'to_place' => $request->to_place,
                'to_longitude' => $request->to_longitude,
                'to_latitude' => $request->to_latitude,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'description' => $request->description,
            ]);
            $trip_wayss = explode("|", $request->trip_ways);
            if (isset($request->trip_ways) && count($trip_wayss) > 0) {

                foreach ($trip_wayss as $way) {
                    $wayy = explode("!", $way);
                    TripWays::create([
                        'trip_id' => $tripID,
                        'place' => $wayy[0],
                        // 'longitude' => $wayy['longitude'] ?? null,
                        // 'latitude' => $wayy['latitude'] ?? null,
                        'time' => $wayy[1] ?? null
                    ]);
                }
            }
            $trip_dayss = explode("|", $request->trip_days);
            if (isset($request->trip_days) && count($trip_dayss) > 0) {
                foreach ($trip_dayss as $day) {
                    TripDays::create([
                        'trip_id' => $tripID,
                        'trip_day' => $day
                    ]);
                }
            }
            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('200', $e->getMessage());
        }
    }

    public function updateTrip(Request $request)
    {
        try {
            DB::beginTransaction();
            $trip = Trips::find($request->id);
            if (!$trip) {
                return $this->returnError('202', 'fail');
            }

            $trip->update([
                'type_of_trips' => $request->type_of_trips ?? $trip->type_of_trips,
                'type_of_services' => $request->type_of_services ?? $trip->type_of_services,
                'only_women' => $request->only_women ?? $trip->only_women,
                'from_place' => $request->from_place ?? $trip->from_place,
                'from_longitude' => $request->from_longitude ?? $trip->from_longitude,
                'from_latitude' => $request->from_latitude ?? $trip->from_latitude,
                'to_place' => $request->to_place ?? $trip->to_place,
                'to_longitude' => $request->to_longitude ?? $trip->to_longitude,
                'to_latitude' => $request->to_latitude ?? $trip->to_latitude,
                'start_date' => $request->start_date ?? $trip->start_date,
                'end_date' => $request->end_date ?? $trip->end_date,
                'description' => $request->description ?? $trip->description,
                'active' => $request->active ?? $trip->active
            ]);

            if (isset($request->trip_ways) && count($request->trip_ways) > 0) {
                $trip->ways()->delete();
                foreach ($request->trip_ways as $way) {
                    TripWays::create([
                        'trip_id' => $trip->id,
                        'place' => $way['place'],
                        'longitude' => $way['longitude'] ?? null,
                        'latitude' => $way['latitude'] ?? null,
                        'time' => $way['time'] ?? null
                    ]);
                }
            }

            if (isset($request->trip_days) && count($request->trip_days) > 0) {
                $trip->days()->delete();
                foreach ($request->trip_days as $day) {
                    TripDays::create([
                        'trip_id' => $trip->id,
                        'trip_day' => $day
                    ]);
                }
            }
            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', 'fail');
        }
    }

    public function deleteTrip(Request $request)
    {
        try {
            DB::beginTransaction();
            $trip = Trips::find($request->id);
            if (!$trip) {
                return $this->returnError('202', 'fail');
            }
            $trip->ways()->delete();
            $trip->days()->delete();
            $trip->messageRooms()->delete();
            $trip->delete();
            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', 'fail');
        }
    }

    public function createFatoorah(Request $request)
    {
        try {
            DB::beginTransaction();
            $rules = [
                // 'request_trip_id' => 'required|numeric',
                // 'fatoorahs' => 'required',
                'chat_ids' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $fatoorahListID = null;
            if (isset($request->fatoorahs) && count($request->fatoorahs) > 0) {
                // return 100;
                $requestTrip =  RequestTrip::where('chat_id', $request->chat_ids)
                    ->where('trip_id', '!=', -1)
                    ->where('receipt_code', 0)
                    ->where('delivery_code', 0)
                    // ->where('offer_status', '2')
                    ->latest()
                    ->first();
                if (!$requestTrip) {
                    return $this->returnError('204', 'لا يمكن انشاء فاتورة قبل عرض طلب');
                }
                if ($requestTrip->request->have_insurance == 1) {
                    if (Auth::user()->balance + ApllicationSetting::find(5)->value < $requestTrip->request->insurance_value) {
                        return $this->returnError('2030', 'you need balance');
                    } else {
                        Auth::user()->update([
                            'balance' => (Auth::user()->balance - $requestTrip->request->insurance_value)
                        ]);
                    }
                }
                // return 104;
                $fatoorahListID = FatoorahList::insertGetId([
                    'request_trip_id' => $requestTrip->id,
                    'accepted' => '0',
                    // 'insurance_fee' => $request->insurance_fee ?? 0,
                    // 'website_service' => $request->website_service ?? 0
                ]);
                $application_service = 0;
                foreach ($request->fatoorahs as $fatoorah) {
                    Fatoorah::create([
                        'fatoorah_list_id' => $fatoorahListID,
                        'subject' => $fatoorah['subject'],
                        'value' => $fatoorah['value'],
                        'is_fee_insurance' => 0
                    ]);
                    $application_service += $fatoorah['value'];
                }
                if ($requestTrip->request->have_insurance == 1) {
                    Fatoorah::create([
                        'fatoorah_list_id' => $fatoorahListID,
                        'subject' => 'رسوم طلب  التامين',
                        'value' => $requestTrip->request->insurance_value * (ApllicationSetting::find(3)->value / 100),
                        'is_fee_insurance' => 1
                    ]);
                    $application_service += $requestTrip->request->insurance_value * (ApllicationSetting::find(3)->value / 100);
                }
                $requestTrip->update([
                    'offer_status' => '3',
                    'website_service' => $application_service * (ApllicationSetting::find(2)->value / 100),
                    'insurance_hold' => $requestTrip->request->insurance_value ?? 0
                ]);
            }
            $msgSubject = 'المسافر أصدر فاتورة يمكنك الاطلاع عليها';
            MessageObject::create([
                'message_id' => $request->chat_ids,
                'sender_type' => 1,
                'subject' => $msgSubject,
                'private_msg' => '1',
                'code' => 101
            ]);

            $msgSubjectOutput['code'] = 1001;
            $msgSubjectOutput['private_msg'] = 1;
            $msgSubjectOutput['subject'] = $msgSubject;
            $msgSubjectOutput['fatoorah_id'] = $fatoorahListID;
            DB::commit();
            return $this->returnData('data', $msgSubjectOutput);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', $e->getMessage());
        }
    }

    public function updateFatoorah(Request $request)
    {
        try {
            DB::beginTransaction();
            $requestTrip =  RequestTrip::where('chat_id', $request->chat_id)
                ->where('trip_id', '!=', -1)
                ->where('receipt_code', 0)
                ->where('delivery_code', 0)
                ->where('offer_status', '3')
                ->latest()
                ->first();
            $dues = $requestTrip->insurance_hold + Auth::user()->balance;
            // return $dues;
            Auth::user()->update([
                'balance' => $dues
            ]);
            $fatoorahListID = null;
            if ($requestTrip->request->have_insurance == 1) {
                if (Auth::user()->balance + ApllicationSetting::find(5)->value < $requestTrip->request->insurance_value) {
                    return $this->returnError('2030', 'you need balance');
                } else {
                    Auth::user()->update([
                        'balance' => (Auth::user()->balance - $requestTrip->request->insurance_value)
                    ]);
                }
            }
            $FAtoorah = FatoorahList::where('request_trip_id', $requestTrip->id)
                ->latest()
                ->first();
            if (isset($request->fatoorahs) && count($request->fatoorahs) > 0) {
                $application_service = 0;
                $fatoorahListID = FatoorahList::insertGetId([
                    'request_trip_id' => $FAtoorah->request_trip_id,
                    // 'insurance_fee' => $request->insurance_fee ?? 0,
                    // 'website_service' => $request->website_service ?? 0
                ]);
                foreach ($request->fatoorahs as $fatoorah) {
                    Fatoorah::create([
                        'fatoorah_list_id' => $fatoorahListID,
                        'subject' => $fatoorah['subject'],
                        'value' => $fatoorah['value'],
                        'is_fee_insurance' => 0
                    ]);
                    $application_service += $fatoorah['value'];
                }
                if ($FAtoorah->requestTrip->request->have_insurance == 1) {
                    $application_service += $requestTrip->request->insurance_value * (ApllicationSetting::find(3)->value / 100);
                    Fatoorah::create([
                        'fatoorah_list_id' => $fatoorahListID,
                        'subject' => 'رسوم طلب  التامين',
                        'value' => $requestTrip->request->insurance_value * (ApllicationSetting::find(3)->value / 100),
                        'is_fee_insurance' => 1
                    ]);
                }
                $requestTrip->update([
                    'website_service' => $application_service * (ApllicationSetting::find(2)->value / 100),
                    'insurance_hold' => $requestTrip->request->insurance_value
                ]);
            }
            // $FAtoorah->update([
            //     'accepted'=> '-1'
            // ]);

            // $FAtoorah->fatoorah()->delete();
            // $FAtoorah->delete();
            $msgSubject = 'لقد عدل المسافر  على الفاتورة';
            MessageObject::create([
                'message_id' => $request->chat_id,
                'sender_type' => 1,
                'subject' => $msgSubject,
                'private_msg' => '1',
                'code' => 1003
            ]);
            DB::commit();
            $msgSubjectOutput['code'] = 1003;
            $msgSubjectOutput['private_msg'] = 1;
            $msgSubjectOutput['subject'] = $msgSubject;
            $msgSubjectOutput['fatoorah_id'] = $fatoorahListID; 
            return $this->returnData('data', $msgSubjectOutput);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', $e->getMessage());
        }
    }

    public function finishNegotiation(Request $request)
    {
        try {
            DB::beginTransaction();
            $request_trip =  RequestTrip::with(['fatoorahList' => function ($q) {
                $q->where('accepted', '1');
            }])
                ->where('chat_id', $request->message_id)
                // ->where('trip_id', '!=', -1)
                // ->where('receipt_code', '!=', 0)
                // ->where('delivery_code', '!=', 0)
                // ->where('offer_status', '4')
                ->latest()
                ->first();
            if ($request->delivery_code == $request_trip->delivery_code) {
                $request_trip->update([
                    'current_status' => 10,
                    'offer_status' => '5'
                ]);
                $fatoorahSum = 0;
                $fatoorahID =  $request_trip->fatoorahList[0]['id'];
                $fatoorahSum = Fatoorah::where('fatoorah_list_id', $fatoorahID)->sum('value');
                // return $fatoorahSum;
                // $type = (Auth::getDefaultDriver() == 'user-api' ? 0 : 1);

                // trans money
                // return $request_trip;
                Auth::user()->update([
                    'balance' => ((Auth::user()->balance + $request_trip->insurance_hold + $fatoorahSum) - $request_trip->website_service)
                ]);
                // return 99;
                $user =  User::find($request_trip->request->user);
                // return $user[0]->name;
                $user[0]->update([
                    'balance' => (($user[0]->balance - $fatoorahSum) + $request_trip->discounts)
                ]);
                // return 66;

                $msgSubject = 'مبروك  نجاح وإنجاز الطلب';
                MessageObject::create([
                    'message_id' => $request->message_id,
                    'sender_type' => 0,
                    'subject' => $msgSubject,
                    'private_msg' => '2',
                    'code' => 1010
                ]);

                MessageObject::create([
                    'message_id' => $request->message_id,
                    'sender_type' => 1,
                    'subject' => $msgSubject,
                    'private_msg' => '1',
                    'code' => 1010
                ]);

                $msgSubjectOutput['user_code'] = 1010;
                $msgSubjectOutput['masafr_code'] = 1010;
                $msgSubjectOutput['private_msg'] = 3;
                $messages['user_msg'] = $msgSubject;
                $messages['masafr_msg'] = $msgSubject;
                $msgSubjectOutput['subject'] = $messages;
                DB::commit();
                return $this->returnData('data', $msgSubjectOutput);
            }
            $type = (Auth::getDefaultDriver() == 'user-api' ? 0 : 1);
            $msgSubject = 'خطا برقم الكود  تحقق مره أخرى';
            MessageObject::create([
                'message_id' => $request->message_id,
                'sender_type' => $type,
                'subject' => $msgSubject,
                'private_msg' => '2',
                'code' => 1011
            ]);
            DB::commit();
            $msgSubjectOutput['code'] = 1011;
            $msgSubjectOutput['private_msg'] = 2;
            $msgSubjectOutput['subject'] = $msgSubject;
            return $this->returnData('data', $msgSubjectOutput);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', $e->getMessage());
        }
    }

    public function getRequestService(Request $request)
    {
        try {
            if (!$request->has('request_id')) {
                return $this->returnError('203', 'fail');
            }
            $userRequestService = RequestService::with('user')->find($request->request_id);
            if (!$userRequestService) {
                return $this->returnError('202', 'fail');
            }
            return $this->returnData('data', $userRequestService);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function getMyTrips(Request $request)
    {
        try {

            $my_trips = Trips::with(['tripCategory' => function ($q) {
                $q->select('id', 'categorie_name');
            }])
                ->where('masafr_id', Auth::user()->id)
                ->paginate($request->paginateCount);
            return $this->returnData('data', $my_trips);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function searchRequestService(Request $request)
    {
        try {
            if (!$request->has('type_of_trips') || !$request->has('from_place') || !$request->has('to_place')) {
                return $this->returnError('2011', 'fail');
            }
            $requestSerices = RequestService::where('type_of_trips', $request->type_of_trips)
                ->where('from_place', 'like', '%' . $request->from_place . '%')
                ->where('to_place', 'like', '%' . $request->to_place . '%')
                ->where('max_day', '>', Carbon\Carbon::now())
                ->where('active', 1)
                ->get();
            return $this->returnData('data', $requestSerices);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function createFreeService(Request $request)
    {
        // return $request;
        try {
            DB::beginTransaction();
            $rules = [
                'type' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $file_name_photo = null;
            if ($request->hasFile('photo')) {
                $file_name_photo  = $this->saveImage($request->photo, 'free_services');
            }

            $freeServiceID = FreeService::insertGetId([
                'masafr_id' => Auth::user()->id,
                'type' => $request->type,
                'photo' => $file_name_photo,
                'description' => $request->description
            ]);
            if ($request->has('places')) {
                foreach ($request['places'] as $place) {
                    FreeServicePlace::create([
                        'free_service_id' => $freeServiceID,
                        'place' => $place['place']
                    ]);
                }
            }
            // FreeServicePlace::insert($request['places']);
            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', 'fail');
        }
    }

    public function getMyFreeServices(Request $request)
    {
        try {
            $freeSerivce = FreeService::with('ways')->where('masafr_id', Auth()->user()->id)->get();
            return $this->returnData('data', $freeSerivce);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', 'fail');
        }
    }

    public function updateFreeService(Request $request)
    {
        try {
            DB::beginTransaction();
            $rules = [
                'id' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $freeSerivce = FreeService::find(intval($request->id));
            if (!$freeSerivce) {
                return $this->returnError('202', 'fail');
            }

            $file_name_photo = null;
            if ($request->hasFile('photo')) {
                $file_name_photo  = $this->saveImage($request->photo, 'free_services');
            }

            $freeSerivce->update([
                'type' => $request->type ?? $freeSerivce->type,
                'photo' => $file_name_photo ?? $freeSerivce->photo,
                'description' => $request->description ?? $freeSerivce->description
            ]);
            if (isset($request->places) && count($request->places) > 0) {
                $freeSerivce->ways()->delete();
                foreach ($request->places as $place) {
                    FreeServicePlace::create([
                        'free_service_id' => $freeSerivce->id,
                        'place' => $place
                    ]);
                }
            }
            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', 'fail');
        }
    }

    public function deleteFreeService(Request $request)
    {
        try {
            DB::beginTransaction();
            $rules = [
                'id' => 'required|numeric'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $freeSerivce = FreeService::find($request->id);
            if (!$freeSerivce) {
                return $this->returnError('202', 'fail');
            }
            $freeSerivce->ways()->delete();
            $freeSerivce->delete();
            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', 'fail');
        }
    }

    public function userInfo(Request $request)
    {
        try {
            $rules = [
                'id' => 'required|numeric'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $user = User::find($request->id);
            if (!$user) {
                return $this->returnError('202', 'fail');
            }

            return $this->returnData('data', $user);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }
}
