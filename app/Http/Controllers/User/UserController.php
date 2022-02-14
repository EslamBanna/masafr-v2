<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
// use App\Mail\AdminSendEmail;
use App\Mail\VerficationMail;
use App\Models\Admin\Copon;
use App\Models\Admin\NotificationOrMailPerson;
use App\Models\Admin\RequestCategorie;
use App\Models\Common\AdminNotificationOrEmail;
use App\Models\Common\Complain;
use App\Models\Common\MessageObject;
use App\Models\Common\RequestTrip;
// use App\Models\Common\UpdateQeueu;
use App\Models\Masafr\FatoorahList;
use App\Models\Masafr\FreeService;
use App\Models\Masafr\Masafr;
use App\Models\Masafr\Trips;
use App\Models\User\CoponUser;
use App\Models\User\RequestService;
use App\Models\User\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
// use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use JWTAuth;
use Nexmo\Laravel\Facade\Nexmo;
use Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    use GeneralTrait;
    public function login(Request $request)
    {
        try {
            $loginType = 'phone';
            $rules = [
                'phone' => 'required|exists:users,phone',
                'password' => 'required'
            ];
            if ($request->has('email')) {
                $loginType = 'email';
                $rules = [
                    'email' => 'required|email|exists:users,email',
                    'password' => 'required'
                ];
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $cardintions = $request->only([$loginType, 'password']);
            $token = Auth::guard('user-api')->attempt($cardintions);
            if (!$token) {
                return $this->returnError('200', 'fail');
            }
            $user = Auth::guard('user-api')->user();
            $user->token = $token;

            // $windows = AdminNotificationOrEmail::whereHas('persons', function ($q) {
            //     $q->where('person_id', Auth::user()->id)
            //         ->where('showed', 0);
            // })
            //     ->with(['persons' => function ($q) {
            //         $q->where('person_id', Auth::user()->id)
            //             ->where('showed', 0);
            //     }])
            //     ->where('type', 0)
            //     ->get();
            // $user['windows'] = $windows;
            return $this->returnSuccessMessage($user);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function getUserInfo()
    {
        try {
            $userId = Auth()->user()->id;
            $user = auth()->user();
            $negativePoints = Complain::where('user_id', $userId)
                ->where('user_negative', 1)
                ->get()->count();
            $complains = Complain::where('user_id', $userId)->get()->count();
            $requests = RequestService::where('user_id', $userId)->get()->count();
            $user['negative_points_count'] = $negativePoints;
            $user['complains_count'] = $complains;
            $user['request_count'] = $requests;
            return $this->returnData('data', $user);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }



    public function createUser(Request $request)
    {
        try {
            $rules = [
                'email' => 'required|email|unique:users,email',
                'country_code' => 'required',
                'phone' => 'required|unique:users,phone',
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
                $file_name  = $this->saveImage($request->photo, 'users');
            }
            // return $file_name;
            $code =  rand(100000, 999999);
            $userID =  User::insertGetId([
                'email' => $request->email,
                'phone' => $request->phone,
                'name' => $request->name,
                'nationality' => $request->nationality,
                'gender' => $request->gender,
                'password' => bcrypt($request->password),
                'verification_code' => $code,
                'photo' => $file_name,
                'country_code' => $request->country_code,
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
            return $this->returnData('user id', $userID);
        } catch (\Exception $e) {
            return $this->returnError('E205', $e->getMessage());
        }
    }

    public function createRequestService(Request $request)
    {
        try {
            $rules = [
                'description' => "required",
                'type_of_trips' => 'required|numeric',
                // 'type_of_services' => 'required|numeric'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $file_name = null;
            if ($request->hasFile('photo')) {
                $file_name  = $this->saveImage($request->photo, 'request_services');
            }

            RequestService::create([
                'user_id' => Auth::user()->id,
                'type_of_trips' => $request->type_of_trips,
                'type_of_services' => $request->type_of_services ?? -1,
                'from_place' => $request->from_place,
                'from_longitude' => $request->from_longitude,
                'from_latitude' => $request->from_latitude,
                'to_place' => $request->to_place,
                'to_longitude' => $request->to_longitude,
                'to_latitude' => $request->to_latitude,
                'max_day' => $request->max_day,
                'delivery_to' => $request->delivery_to,
                'photo' => $file_name,
                'description' => $request->description,
                'only_women' => $request->only_women,
                'have_insurance' => $request->have_insurance,
                'insurance_value' => $request->insurance_value,
                'website_service' => $request->website_service,
                'number_of_passengers' => $request->number_of_passengers,
                'type_of_car' => $request->type_of_car
            ]);
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError('E205', $e->getMessage());
        }
    }

    public function updateRequestService(Request $request)
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

            $request_trips = RequestTrip::where('request_id', $request->id)->get();
            foreach ($request_trips as $request_trip) {
                if ($request_trip->offer_status == '4') {
                    return $this->returnError('2010', 'can not update after fatoorah');
                }
            }

            $file_name = null;
            if ($request->hasFile('photo')) {
                $file_name  = $this->saveImage($request->photo, 'request_services');
            }
            $request_service = RequestService::find($request->id);
            if (!$request_service) {
                return $this->returnError('202', 'fail');
            }


            // $photo_len = strlen((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/images/' . ($request->type == 0 ? 'users_id/' : 'masafr_id/'));
            // $slice = substr($watingData->id_photo, $photo_len);
            // if ($slice == null) {
            //     $photo_len = strlen((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/images/' . ($request->type == 0 ? 'users_id/' : 'masafr_id/'));
            //     $photo_len++;
            //     $slice = substr($user->id_photo, $photo_len);
            // }


            $photo_len = strlen((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/images/' . 'request_services');
            $slice = substr($request_service->photo, $photo_len);

            $request_service->update([
                'type_of_trips' => $request->type_of_trips ?? $request_service->type_of_trips,
                'type_of_services' => $request->type_of_services ?? $request_service->type_of_services,
                'from_place' => $request->from_place ?? $request_service->from_place,
                'from_longitude' => $request->from_longitude ?? $request_service->from_longitude,
                'from_latitude' => $request->from_latitude ?? $request_service->from_latitude,
                'to_place' => $request->to_place ?? $request_service->to_place,
                'to_longitude' => $request->to_longitude ?? $request_service->to_longitude,
                'to_latitude' => $request->to_latitude ?? $request_service->to_latitude,
                'max_day' => $request->max_day ?? $request_service->max_day,
                'delivery_to' => $request->delivery_to ?? $request_service->delivery_to,
                'photo' => $file_name ?? $slice,
                'description' => $request->description ?? $request_service->description,
                'only_women' => $request->only_women ?? $request_service->only_women,
                'have_insurance' => $request->have_insurance ?? $request_service->have_insurance,
                'insurance_value' => $request->insurance_value ?? $request_service->insurance_value,
                'website_service' => $request->website_service ?? $request_service->website_service,
                'number_of_passengers' => $request->number_of_passengers ?? $request_service->number_of_passengers,
                'type_of_car' => $request->type_of_car ?? $request_service->type_of_car
            ]);

            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError('200', 'fail');
        }
    }

    public function deleteRequestService(Request $request)
    {
        try {
            $rules = [
                'id' => "required|numeric",
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $requestService = RequestService::find($request->id);
            if (!$requestService) {
                return $this->returnError('202', 'fail');
            }

            $requestService->delete();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError('200', 'fail');
        }
    }
    public function getTrip(Request $request)
    {
        try {
            $trip = Trips::with('masafr')
                ->with('ways')
                ->with('days')
                ->find($request->id);
            if (!$trip) {
                return $this->returnError('202', 'fail');
            }
            return $this->returnData('data', $trip);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }
    public function getFatoorah(Request $request)
    {
        try {
            $rules = [
                'id' => "required|numeric"
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $fatoorah = FatoorahList::with('fatoorah')->where('id', $request->id)->get();
            return $this->returnData('data', $fatoorah);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function resopnseToFatoorah(Request $request)
    {
        try {
            DB::beginTransaction();
            $rules = [
                'fatoorah_list_id' => 'required|numeric',
                'accept' => 'required|boolean',
                'message_id' => 'required|numeric'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $fatoorahList = FatoorahList::find($request->fatoorah_list_id);
            if (!$fatoorahList) {
                return $this->returnError('202', 'fail');
            }
            // return $code_type = $fatoorahList->requestTrip->request->service['two_codes'];


            if ($request->accept == 0) {
                // refused
                $type = (Auth::getDefaultDriver() == 'user-api' ? 0 : 1);
                $msgSubject = 'رفض العميل الفاتورة يمكنك تعديلها';
                MessageObject::create([
                    'message_id' => $request->message_id,
                    'sender_type' => $type,
                    'subject' => $msgSubject,
                    'private_msg' => '2',
                    'code' => 1002
                ]);
                $fatoorahList->update([
                    'accepted' => '-1'
                ]);
                $dues = $fatoorahList->requestTrip->website_service + $fatoorahList->requestTrip->website_serviceinsurance_hold + $fatoorahList->requestTrip->trip->masafr->balance;

                $fatoorahList->requestTrip->trip->masafr->update([
                    'balance' => $dues
                ]);
                DB::commit();
                $msgSubjectOutput['code'] = 1002;
                $msgSubjectOutput['private_msg'] = 2;
                $msgSubjectOutput['subject'] = $msgSubject;
                return $this->returnData('data', $msgSubjectOutput);
            } else if ($request->accept == 1) {
                if ($request->has('copon')) {
                    $copon = Copon::where('copon_name', $request->copon)->first();
                    if (!$copon) {
                        return $this->returnError('2011', 'هذا الكوبون غير صحيح');
                    }
                    if ($copon->used < $copon->amount) {
                        $user_copon = CoponUser::where('user_id', Auth::user()->id)
                            ->where('copon_id', $copon->id)
                            ->get();
                        if (count($user_copon) > 0) {
                            return $this->returnError('2020', $copon->has_used_copon_before_err);
                        }
                        CoponUser::create([
                            'user_id' => Auth::user()->id,
                            'copon_id' => $copon->id,
                        ]);
                        $fatoorahList->requestTrip->update([
                            'discounts' => $copon->value
                        ]);
                        $used = $copon->used + 1;
                        $copon->update([
                            'used' => $used
                        ]);
                    } else {
                        return $this->returnError('2020', $copon->copon_full_amount_err);
                    }
                }
                // check two codes or one code
                // fatoorah_list -> request_trip -> request_services -> request_categories
                // return 684;

                // $two_code = $fatoorahList->requestTrip->request->two_codes;

                $two_code = $fatoorahList->requestTrip->request->service->two_codes;
                // return $two_code;
                $delivery_code =  rand(100000, 999999);
                $receipt_code = 0;
                // return 684;
                $msgSubject_masafr = '';
                if ($two_code == 1) {
                    $receipt_code =  rand(100000, 999999);
                    $msgSubject_masafr = ' تم تكليفك بالطلب طريقة الدفع ' . ($request->payment_method  == 0 ? 'الكتروني' : 'نقد') . '  نفذ المهمة و أستلم من العميل كود الإنجاز وأكد التنفيذ ' . $receipt_code;
                    MessageObject::create([
                        'message_id' => $request->message_id,
                        'sender_type' => 0,
                        'subject' => $msgSubject_masafr,
                        'private_msg' => '2',
                        'code' => 1004
                    ]);
                }
                $msgSubject_user = 'سلم هذا الكود للمسافر بعد التوصيل' . $delivery_code;
                MessageObject::create([
                    'message_id' => $request->message_id,
                    'sender_type' => 1,
                    'subject' => $msgSubject_user,
                    'private_msg' => '1',
                    'code' => 1005
                ]);

                $fatoorahList->update([
                    'accepted' => '1'
                ]);
                $fatoorahList->requestTrip->update([
                    // 'selected' => $request->selected,
                    'receipt_code' => $receipt_code,
                    'delivery_code' => $delivery_code,
                    'offer_status' => '4',
                    'payment_method' => $request->payment_method ?? 1,
                    'current_status' => 1
                ]);

                $messages['user_msg'] = $msgSubject_user;
                $messages['masafr_msg'] = $msgSubject_masafr;
                $msgSubjectOutput['user_code'] = 1005;
                $msgSubjectOutput['masafr_code'] = 1004;
                $msgSubjectOutput['private_msg'] = 3;
                $msgSubjectOutput['subject'] = $messages;
                DB::commit();
                return $this->returnData('data', $msgSubjectOutput);
            }
        } catch (\Exception $e) {
            return $this->returnError('201', $e->getMessage());
        }
    }
    public function searchTrips(Request $request)
    {
        try {
            if (!$request->has('type_of_trips') || !$request->has('from_place') || !$request->has('to_place')) {
                return $this->returnError('2011', 'fail');
            }
            $trips = Trips::where('type_of_trips', $request->type_of_trips)
                ->where('from_place', 'like', '%' . $request->from_place . '%')
                ->where('to_place', 'like', '%' . $request->to_place . '%')
                ->where('end_date', '>', Carbon\Carbon::now())
                ->where('active', 1)
                ->get();
            return $this->returnData('data', $trips);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function getAllFreeServices(Request $request)
    {
        try {
            $freeServices = FreeService::with('masafr')
                ->with('ways')
                ->paginate($request->paginateCount);
            return $this->returnData('data', $freeServices);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function searchFreeService(Request $request)
    {
        try {
            $freeServices = FreeService::with('masafr')
                ->with('ways')
                ->where('type', 'like', '%' . $request->type . '%')
                ->paginate($request->paginateCount);
            return $this->returnData('data', $freeServices);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }


    public function masafrInfo(Request $request)
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

            $masafr = Masafr::find($request->id);
            if (!$masafr) {
                return $this->returnError('202', 'fail');
            }

            return $this->returnData('data', $masafr);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function getMyRequestServices()
    {
        try {
            $request_service = RequestService::with('service')->where('user_id', Auth()->user()->id)->get();
            return $this->returnData('data', $request_service);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }
}
