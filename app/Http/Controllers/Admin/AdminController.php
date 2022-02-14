<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminSendEmail;
use App\Models\Admin\Admin;
use App\Models\Admin\Advertising;
use App\Models\Admin\AdvertisingDay;
use App\Models\Admin\AdvertisingPlaces;
use App\Models\Admin\AdvertisingUser;
use App\Models\Admin\ApllicationSetting;
use App\Models\Admin\CategorieRequestSubsection;
use App\Models\Admin\CategorieTripSubsection;
use App\Models\Admin\Copon;
use App\Models\Admin\GiftCoponUser;
use App\Models\Admin\NotificationOrMailPerson;
use App\Models\Admin\RequestCategorie;
use App\Models\Admin\RollbackRequest;
use App\Models\Admin\RollbackRequestMoney;
use App\Models\Admin\TripCategorie;
use App\Models\Common\AdminNotificationOrEmail;
use App\Models\Common\Complain;
use App\Models\Common\Message;
use App\Models\Common\MessageObject;
use App\Models\Common\RequestTrip;
use App\Models\Common\UpdateQeueu;
use App\Models\Masafr\FatoorahList;
use App\Models\Masafr\Masafr;
use App\Models\Masafr\Trips;
use App\Models\Masafr\UpdateMasafarQueue;
use App\Models\User\CoponUser;
use App\Models\User\RequestService;
use App\Models\User\UpdateUserQueue;
use App\Models\User\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
// use Validator;
use Illuminate\Support\Facades\Auth;
// use DB;
use Illuminate\Support\Facades\DB;
use Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Nexmo\Laravel\Facade\Nexmo;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    use GeneralTrait;
    public function createAdmin(Request $request)
    {
        try {
            $rules = [
                'email' => 'required|email|unique:admins,email',
                'phone' => 'required|unique:admins,phone',
                'name' => 'required|min:4',
                'password' => 'required|min:4',
                'type' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $file_name = null;
            if ($request->hasFile('photo')) {
                $file_name  = $this->saveImage($request->photo, 'admins');
            }
            // return $file_name;
            $userID =  Admin::insertGetId([
                'email' => $request->email,
                'phone' => $request->phone,
                'name' => $request->name,
                'gender' => $request->gender,
                'password' => bcrypt($request->password),
                'type' => $request->type,
                'photo' => $file_name
            ]);
            return $this->returnData('admin id', $userID);
        } catch (\Exception $e) {
            return $this->returnError('E205', $e->getMessage());
        }
    }

    public function login(Request $request)
    {
        try {
            $loginType = 'phone';
            $rules = [
                'phone' => 'required|exists:admins,phone',
                'password' => 'required'
            ];
            if ($request->has('email')) {
                $loginType = 'email';
                $rules = [
                    'email' => 'required|email|exists:admins,email',
                    'password' => 'required'
                ];
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $cardintions = $request->only([$loginType, 'password']);
            $token = Auth::guard('admin-api')->attempt($cardintions);
            if (!$token) {
                return $this->returnError('E001', 'fail');
            }
            $admin = Auth::guard('admin-api')->user();
            $admin->token = $token;

            return $this->returnSuccessMessage($admin);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }
    public function mainPageInfo()
    {
        try {
            $masafrs_count = Masafr::count();
            $users_count = User::count();
            $masafr_foreigners = Masafr::where('nationality', '!=', 'سعودي')
                ->orWhere('nationality', null)
                ->count();
            $user_foreigners = User::where('nationality', '!=', 'سعودي')
                ->orWhere('nationality', null)
                ->count();
            $trust_masafrs = Masafr::where('trust', 1)->count();
            $trust_users = User::where('trust', 1)->count();
            $chat_rooms = Message::count();
            $trust_nationality = UpdateQeueu::where('update_type', '1')
                ->where('admin_response', '0')
                ->count();
            $request_services = RequestService::count();
            $trips = Trips::count();
            $finished_requests = RequestTrip::where('offer_status', '5')->count();
            $canceled_requests = RequestTrip::where('offer_status', '-1')->count();
            $complains = Complain::count();
            $unverified_users = User::where('is_verified', 0)->count();

            $data['masafrs_count'] = $masafrs_count;
            $data['masafrs_foreigners'] = $masafr_foreigners;
            $data['masafrs_trusts'] = $trust_masafrs;

            $data['users_count'] = $users_count;
            $data['users_foreigners'] = $user_foreigners;
            $data['users_trusts'] = +$trust_users;

            // $data['all_users'] = $masafrs_count + $users_count;
            $data['chat_rooms'] = $chat_rooms;
            $data['trust_nationality'] = $trust_nationality;
            $data['request_services'] = $request_services;
            $data['trips'] = $trips;
            $data['finished_requests'] = $finished_requests;
            $data['canceled_requests'] = $canceled_requests;
            $data['complains'] = $complains;
            $data['unverified_users'] = $unverified_users;
            $data['trusts_request'] = UpdateQeueu::where('admin_response', '0')
                ->where('update_type', '1')
                ->orWhere('update_type', '2')
                ->count();

            return $this->returnData('data', $data);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }
    public function sendNotificationsOrMails(Request $request)
    {
        try {
            DB::beginTransaction();
            $rules = [
                'type' => 'required',
                'subject' => 'required',
                'send_by' => 'required|numeric',
                'type_of_template' => 'required|numeric',
                'users' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $notification =  AdminNotificationOrEmail::insertGetId([
                'type' => $request->type,
                'subject' => $request->subject,
                'send_by' => $request->send_by,
                'type_of_template' => $request->type_of_template,
                'title' => $request->title
            ]);
            if ($request->type == 0) {
                //user
                foreach ($request->users as $user) {
                    NotificationOrMailPerson::create([
                        // 'type' => $request->type,
                        'notfication_or_mail_id' => $notification,
                        "person_id" => $user
                    ]);
                    if ($request->send_by == 2) {
                        $userS = User::find($user);
                        // return $userS;
                        if (!$userS) {
                            return $this->returnError('202', 'fail');
                        }
                        Mail::to($userS->email)->send(new AdminSendEmail($request->type_of_template, $request->subject, $userS->email));
                    }
                }
            } else if ($request->type == 1) {
                //user
                foreach ($request->users as $masafr) {
                    NotificationOrMailPerson::create([
                        // 'type' => $request->type,
                        'notfication_or_mail_id' => $notification,
                        "person_id" => $masafr
                    ]);
                    if ($request->send_by == 2) {
                        $masafr = Masafr::find($masafr);
                        if (!$masafr) {
                            return $this->returnError('202', 'fail');
                        }
                        Mail::to($masafr->email)->send(new AdminSendEmail($request->type_of_template, $request->subject, $masafr->email));
                    }
                }
            }
            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', 'fail');
        }
    }

    public function sendNotification(Request $request)
    {
        try {
            DB::beginTransaction();
            $rules = [
                'subject' => 'required',
                'send_by' => 'required|numeric',
                'person_id' => 'required|numeric',
                'type' => 'required|numeric'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $notification =  AdminNotificationOrEmail::insertGetId([
                'type' => $request->type,
                'type_of_template' => $request->type_of_template,
                'subject' => $request->subject,
                'send_by' => $request->send_by,
                'title' => $request->title
            ]);
            //user
            NotificationOrMailPerson::create([
                // 'type' => $request->type,
                'notfication_or_mail_id' => $notification,
                "person_id" => $request->person_id
            ]);



            if ($request->send_by == 2) {
                //email
                Mail::to($request->email)
                    ->send(new AdminSendEmail($request->type_of_template, $request->subject, $request->email));
            } else if ($request->send_by == 3) {
                // sms
                Nexmo::message()->send([
                    'to' => $request->to,
                    'from' => '+201287006309',
                    'text' => $request->subject
                ]);
            }
            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', $e->getMessage());
        }
    }

    public function createNewRequestCategorie(Request $request)
    {
        try {
            DB::beginTransaction();

            $rules = [
                'categorie_name' => 'required',
                'photo' => 'required',
                'title' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $file_name = null;
            if ($request->hasFile('photo')) {
                $file_name  = $this->saveImage($request->photo, 'main_request_categories');
            }

            $mainCategorieID = RequestCategorie::insertGetId([
                'categorie_name' => $request->categorie_name,
                'photo' => $file_name,
                'title' => $request->title,
                'only_saudi' => $request->only_saudi,
                'payment_method' => $request->payment_method,
                'have_insurance' => $request->have_insurance,
                'have_photo' => $request->have_photo,
                'two_places' => $request->two_places,
                'two_codes' => $request->two_codes,
                'active' => $request->active
            ]);

            if (isset($request->subsections)) {
                foreach ($request->subsections as $subsection) {
                    CategorieRequestSubsection::create([
                        'categorie_id' => $mainCategorieID,
                        'section_name' => $subsection
                    ]);
                }
            }
            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::commit();
            return $this->returnError('201', 'fail');
        }
    }

    public function createNewTripCategorie(Request $request)
    {
        try {
            DB::beginTransaction();

            $rules = [
                'categorie_name' => 'required',
                'photo' => 'required',
                'title' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $file_name = null;
            if ($request->hasFile('photo')) {
                $file_name  = $this->saveImage($request->photo, 'main_trip_categories');
            }

            $mainCategorieID = TripCategorie::insertGetId([
                'categorie_name' => $request->categorie_name,
                'photo' => $file_name,
                'title' => $request->title,
                'only_saudi' => $request->only_saudi,
                'special_dlivery' => $request->special_dlivery,
                'two_place' => $request->two_place,
                'weekly' => $request->weekly,
                'counter' => $request->counter,
                'active' => $request->active
            ]);

            if (isset($request->subsections) && is_array($request->subsections) || is_object($request->subsections)) {
                foreach ($request->subsections as $subsection) {
                    CategorieTripSubsection::create([
                        'categorie_id' => $mainCategorieID,
                        'section_name' => $subsection
                    ]);
                }
            }
            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::commit();
            return $this->returnError('201', $e->getMessage());
        }
    }


    public function createCopon(Request $request)
    {
        DB::beginTransaction();
        try {

            $rules = [
                'copon_name' => 'required',
                'copon_start_date' => 'required',
                'copon_end_date' => 'required',
                'amount' => 'required|numeric',
                'value' => 'required|numeric',
                'copon_type' => 'required|boolean'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $file_name_gift_picture = '';
            if ($request->hasFile('gift_picture')) {
                $file_name_gift_picture  = $this->saveImage($request->gift_picture, 'copons');
            }

            $file_name_attach = '';
            if ($request->hasFile('attach')) {
                $file_name_attach  = $this->saveImage($request->attach, 'copons');
            }
            $coponID =  Copon::insertGetId([
                'gift_picture' => $file_name_gift_picture,
                'copon_name' => $request->copon_name,
                'copon_start_date' => $request->copon_start_date,
                'copon_end_date' => $request->copon_end_date,
                'amount' => $request->amount ?? 0,
                'value' => $request->value ?? 0,
                'copon_type' => 1,
                'copon_full_amount_err' => $request->copon_full_amount_err,
                'not_exsist_copon_err' => $request->not_exsist_copon_err,
                'has_used_copon_before_err' => $request->has_used_copon_before_err,
                'copon_success' => $request->copon_success,
                'link' => $request->link,
                'attach' => $file_name_attach
            ]);

            if ($request->has('phones')) {
                foreach ($request->phones as $phone) {
                    GiftCoponUser::create([
                        'phone' => $phone,
                        'copon_id' => $coponID
                    ]);
                }
            }
            // }
            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', 'fail');
        }
    }

    public function getAllCopons(Request $request)
    {
        try {
            $copons = Copon::paginate($request->paginateCount);
            return $this->returnData('data', $copons);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function getCopon(Request $request)
    {
        try {
            if (!$request->has('copon_id')) {
                return $this->returnError('202', 'fail');
            }
            $copon = Copon::find($request->copon_id);
            if (!$copon) {
                return $this->returnError('203', 'fail');
            }
            return $this->returnData('data', $copon);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function updateCopon(Request $request)
    {
        try {
            if (!$request->has('copon_id')) {
                return $this->returnError('202', 'fail');
            }
            $copon = Copon::find($request->copon_id);
            if (!$copon) {
                return $this->returnError('203', 'fail');
            }
            $file_name_gift_picture = null;
            if ($request->hasFile('gift_picture')) {
                $file_name_gift_picture  = $this->saveImage($request->gift_picture, 'copons');
            }

            $file_name_attach = null;
            if ($request->hasFile('attach')) {
                $file_name_attach  = $this->saveImage($request->attach, 'copons');
            }
            $copon->update([
                'gift_picture' => $file_name_gift_picture ?? $copon->gift_picture,
                'copon_name' => $request->copon_name ?? $copon->copon_name,
                'copon_start_date' => $request->copon_start_date ?? $copon->copon_start_date,
                'copon_end_date' => $request->copon_end_date ?? $copon->copon_end_date,
                'amount' => $request->amount ?? $copon->amount,
                'value' => $request->value ?? $copon->value,
                'copon_type' => 1,
                'copon_full_amount_err' => $request->copon_full_amount_err ?? $copon->copon_full_amount_err,
                'not_exsist_copon_err' => $request->not_exsist_copon_err ?? $copon->not_exsist_copon_err,
                'has_used_copon_before_err' => $request->has_used_copon_before_err ?? $copon->has_used_copon_before_err,
                'copon_success' => $request->copon_success ?? $copon->copon_success,
                'link' => $request->link ?? $copon->link,
                'attach' => $file_name_attach ?? $copon->attach
            ]);
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function getAllCoponUser(Request $request)
    {
        try {
            $copon_users = CoponUser::with(['user' => function ($q) {
                $q->select('id', 'name', 'phone')->withCount('copons');
            }])
                ->where('copon_id', $request->copon_id)
                ->paginate($request->paginateCount);
            return $this->returnData('data', $copon_users);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function createAdvertisings(Request $request)
    {
        try {
            DB::beginTransaction();
            $file_name = null;
            if ($request->hasFile('image')) {
                $file_name  = $this->saveImage($request->image, 'advertisings');
            }
            $adversting = Advertising::insertGetId([
                'subject' => $request->subject,
                'link' => $request->link,
                'site_after_announcement' => $request->site_after_announcement,
                'appear_time' => $request->appear_time ?? 0,
                'start_date' => $request->start_date ?? "",
                'end_date' => $request->end_date ?? "",
                'daily_repeat' => $request->daily_repeat,
                'image' => $file_name,
                'animation_type' => $request->animation_type,
                'person_target' => $request->person_target,
                'all_days' => $request->all_days,
                'user_appear' => $request->user_appear,
                'masafr_appear' => $request->masafr_appear,
            ]);
            if ($request->person_target == 3) {
                if (isset($request->users) && count($request->users) > 0) {
                    foreach ($request->users as $user) {
                        AdvertisingUser::create([
                            'advertising_id' => $adversting,
                            'type' => 0,
                            'phone' => $user
                        ]);
                    }
                }
                if (isset($request->masafrs) && count($request->masafrs) > 0) {
                    foreach ($request->masafrs as $masafr) {
                        AdvertisingUser::create([
                            'advertising_id' => $adversting,
                            'type' => 1,
                            'phone' => $masafr
                        ]);
                    }
                }
            }

            if ($request->all_days == 0) {
                if (isset($request->days) && count($request->days) > 0) {
                    foreach ($request->days as $day) {
                        AdvertisingDay::create([
                            'advertising_id' => $adversting,
                            'day' => $day
                        ]);
                    }
                }
            }
            if (isset($request->user_places) && count($request->user_places) > 0) {
                foreach ($request->user_places as $place) {
                    AdvertisingPlaces::create([
                        'advertising_id' => $adversting,
                        'type' => 0,
                        'place' => $place
                    ]);
                }
            }

            if (isset($request->masafr_places) && count($request->masafr_places) > 0) {
                foreach ($request->masafr_places as $place) {
                    AdvertisingPlaces::create([
                        'advertising_id' => $adversting,
                        'type' => 1,
                        'place' => $place
                    ]);
                }
            }
            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', $e->getMessage());
        }
    }

    public function updateAdvertising($advertisingId, Request $request)
    {
        DB::beginTransaction();
        try {
            $advertising = Advertising::find($advertisingId);
            if (!$advertising) {
                return $this->returnError('202', 'this adversting not found');
            }
            foreach ($advertising->places as $place) {
                $place->delete();
            }

            foreach ($advertising->users as $user) {
                $user->delete();
            }

            foreach ($advertising->days as $day) {
                $day->delete();
            }
            $advertising->delete();

            $file_name = null;
            if ($request->hasFile('image')) {
                $file_name  = $this->saveImage($request->image, 'advertisings');
            }
            $adversting = Advertising::insertGetId([
                'subject' => $request->subject,
                'link' => $request->link,
                'site_after_announcement' => $request->site_after_announcement,
                'appear_time' => $request->appear_time ?? 0,
                'start_date' => $request->start_date ?? "",
                'end_date' => $request->end_date ?? "",
                'daily_repeat' => $request->daily_repeat,
                'image' => $file_name,
                'animation_type' => $request->animation_type,
                'person_target' => $request->person_target,
                'all_days' => $request->all_days,
                'user_appear' => $request->user_appear,
                'masafr_appear' => $request->masafr_appear,
            ]);
            if ($request->person_target == 3) {
                if (isset($request->users) && count($request->users) > 0) {
                    foreach ($request->users as $user) {
                        AdvertisingUser::create([
                            'advertising_id' => $adversting,
                            'type' => 0,
                            'phone' => $user
                        ]);
                    }
                }
                if (isset($request->masafrs) && count($request->masafrs) > 0) {
                    foreach ($request->masafrs as $masafr) {
                        AdvertisingUser::create([
                            'advertising_id' => $adversting,
                            'type' => 1,
                            'phone' => $masafr
                        ]);
                    }
                }
            }

            if ($request->all_days == 0) {
                if (isset($request->days) && count($request->days) > 0) {
                    foreach ($request->days as $day) {
                        AdvertisingDay::create([
                            'advertising_id' => $adversting,
                            'day' => $day
                        ]);
                    }
                }
            }
            if (isset($request->user_places) && count($request->user_places) > 0) {
                foreach ($request->user_places as $place) {
                    AdvertisingPlaces::create([
                        'advertising_id' => $adversting,
                        'type' => 0,
                        'place' => $place
                    ]);
                }
            }

            if (isset($request->masafr_places) && count($request->masafr_places) > 0) {
                foreach ($request->masafr_places as $place) {
                    AdvertisingPlaces::create([
                        'advertising_id' => $adversting,
                        'type' => 1,
                        'place' => $place
                    ]);
                }
            }

            DB::commit();
            return $this->returnSuccessMessage('updated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', $e->getMessage());
        }
    }

    public function getAllAdvertisings()
    {
        try {
            $advertisings =  Advertising::with('users')
                ->with('days')
                ->get();
            return $this->returnData('data', $advertisings);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function getAdvertising($advertisingId)
    {
        try {
            $advertising =  Advertising::with(['users', 'days'])->find($advertisingId);
            if (!$advertising) {
                return $this->returnError('202', 'there is no advertising');
            }
            return $this->returnData('data', $advertising);
        } catch (\Exception $e) {
            return $this->returnError('201', $e->getMessage());
        }
    }

    public function toggleAdvertising($advertisingId, Request $request)
    {
        try {
            $advertising =  Advertising::find($advertisingId);
            if (!$advertising) {
                return $this->returnError('202', 'there is no advertising');
            }
            if (!$request->has('active')) {
                return $this->returnError('203', 'the active field is required');
            }
            $actv = 0;
            if ($request->active == true) {
                $actv = 1;
            } elseif ($request->active == false) {
                $actv = 0;
            } else {
                return $this->returnError('204', 'you must send only true or false data');
            }
            $advertising->update([
                'active' => ($actv)
            ]);
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError('201', $e->getMessage());
        }
    }

    public function getPersonUpdateQueue(Request $request)
    {
        try {
            $rules = [
                'person_id' => 'required|required',
                'type' => 'required|boolean'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            if ($request->type == 0) {
                $user = User::find($request->person_id);
                if (!$user) {
                    return $this->returnError('202', 'fail');
                }
            } else if ($request->type == 1) {
                $masafr = Masafr::find($request->person_id);
                if (!$masafr) {
                    return $this->returnError('202', 'fail');
                }
            }
            $watingData = UpdateQeueu::where('person_id', $request->person_id)
                ->where('type', $request->type)
                ->where('admin_response', '0')
                ->latest()
                ->first();
            return $this->returnData('data', $watingData);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function responseUpdateQueue(Request $request)
    {
        try {
            $rules = [
                'person_id' => 'required',
                'type' => 'required|boolean',
                'accept' => 'required|boolean'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            ##########
            $notification =  AdminNotificationOrEmail::insertGetId([
                'type' => $request->type,
                'subject' => $request->reason ?? "",
                'send_by' => 0,
                'type_of_template' => 0,
                'title' => ($request->accept == 0 ? 'تم رفض التعديل' : 'تم قبول التعديل')
            ]);
            NotificationOrMailPerson::create([
                // 'type' => $request->type,
                'notfication_or_mail_id' => $notification,
                "person_id" => $request->person_id
            ]);

            $notification =  AdminNotificationOrEmail::insertGetId([
                'type' => $request->type,
                'subject' => $request->reason ?? "",
                'send_by' => 0,
                'type_of_template' => 0,
                'title' => ($request->trust == 0 ? 'تم رفض التوثيق' : 'تم قبول التوثيق')
            ]);
            NotificationOrMailPerson::create([
                // 'type' => $request->type,
                'notfication_or_mail_id' => $notification,
                "person_id" => $request->person_id
            ]);

            #############
            if ($request->type == 0) {
                $user = User::find($request->person_id);
                if (!$user) {
                    return $this->returnError('202', 'fail');
                }

                $watingData = null;
                if ($request->accept == 1) {
                    $watingData = UpdateQeueu::where('person_id', $request->person_id)
                        ->where('type', 0)
                        ->where('admin_response', '0')
                        ->latest()->first();

                    $photo_len = strlen((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/images/' . ($request->type == 0 ? 'users_id/' : 'masafr_id/'));
                    $slice = substr($watingData->id_photo, $photo_len);
                    if ($slice == null) {
                        $photo_len = strlen((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/images/' . ($request->type == 0 ? 'users_id/' : 'masafr_id/'));
                        $photo_len++;
                        $slice = substr($user->id_photo, $photo_len);
                    }
                    $user->update([
                        'name' => ($watingData->name == '' ? $user->name : $watingData->name),
                        'email' => ($watingData->email == '' ? $user->email : $watingData->email),
                        'password' => ($watingData->password == '' ? $user->password : $watingData->password),
                        'id_photo' => $slice  ?? '',
                        'gender' => ($watingData->gender == '' ? $user->gender : $watingData->gender),
                        'decision_maker' => Auth()->user()->id
                    ]);
                    UpdateQeueu::where('person_id', $request->person_id)
                        ->where('type', 0)
                        ->where('admin_response', '0')
                        ->latest()
                        ->first()
                        ->update([
                            'admin_response' => '1',
                            'reason' => $request->reason
                        ]);
                } else if ($request->accept == 0) {
                    UpdateQeueu::where('person_id', $request->person_id)
                        ->where('type', 0)
                        ->where('admin_response', '0')
                        ->latest()
                        ->first()
                        ->update([
                            'admin_response' => '-1',
                            'reason' => $request->reason
                        ]);
                }
                $user->update([
                    'trust' => $request->trust
                ]);
                // if ($request->trust == 1) {
                //     $user->update([
                //         'trust' => 1
                //     ]);
                // } else if ($request->trust == 0) {
                //     $user->update([
                //         'trust' => 0
                //     ]);
                // }
            } else if ($request->type == 1) {
                $masafr = Masafr::find($request->person_id);
                if (!$masafr) {
                    return $this->returnError('202', 'fail');
                }

                $watingData = null;
                if ($request->accept == 1) {
                    $watingData = UpdateQeueu::where('person_id', $request->person_id)
                        ->where('type', 1)
                        ->where('admin_response', '0')
                        ->latest()
                        ->first();

                    $photo_len = strlen((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/images/' . 'masafr_id/');
                    $slice = substr($watingData->id_photo, $photo_len);
                    if ($slice == null) {
                        $photo_len = strlen((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/images/' . 'masafr_id/');
                        $photo_len++;
                        $slice = substr($masafr->id_photo, $photo_len);
                    }
                    $masafr->update([
                        'name' => ($watingData->name == '' ? $masafr->name : $watingData->name),
                        'email' => ($watingData->email == '' ? $masafr->email : $watingData->email),
                        'password' => ($watingData->password  == '' ? $masafr->password : $watingData->password),
                        'gender' => ($watingData->gender == '' ? $masafr->gender : $watingData->gender),
                        'id_photo' => $slice ?? ''
                    ]);
                    UpdateQeueu::where('person_id', $request->person_id)
                        ->where('admin_response', '0')
                        ->where('type', 1)
                        ->latest()
                        ->first()
                        ->update([
                            'admin_response' => '1',
                            'reason' => $request->reason
                        ]);
                } else if ($request->accept == 0) {
                    UpdateQeueu::where('person_id', $request->person_id)
                        ->where('type', 1)
                        ->where('admin_response', '0')
                        ->latest()
                        ->first()
                        ->update([
                            'admin_response' => '-1',
                            'reason' => $request->reason
                        ]);
                }
                // UpdateUserQueue::where('user_id', $request->user_id)->latest()->first()->delete();
                // $watingData->delete;
                if ($request->trust == 1) {
                    $masafr->update([
                        'trust' => 1
                    ]);
                } else if ($request->trust == 0) {
                    $masafr->update([
                        'trust' => 0
                    ]);
                }
            }
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError('201', $e->getMessage());
        }
    }

    public function updateComplain(Request $request)
    {
        try {
            $rules = [
                'complain_id' => 'required|numeric'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $complain = Complain::find($request->complain_id);
            if (!$complain) {
                return $this->returnError('202', 'fail');
            }

            $complain->update([
                'solved' => $request->solved ?? $complain->solved,
                'user_negative' => $request->user_negative ?? $complain->user_negative,
                'masafr_negative' => $request->masafr_negative ?? $complain->masafr_negative,
                'status' => $request->status ?? $complain->status
            ]);

            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function getAllComplains(Request $request)
    {
        try {
            $complains = null;
            if ($request->has('filter')) {
                if ($request->filter == 0) {
                    // request_id
                    if (!$request->has('request_id')) {
                        return $this->returnError(206, 'fail');
                    }
                    $complains = Complain::with(['user' => function ($q) {
                        $q->select('id', 'name');
                    }])
                        ->with(['masafr'  => function ($q) {
                            $q->select('id', 'name');
                        }])
                        ->where('related_request_service', $request->request_id)
                        ->paginate($request->paginateCount);
                } else if ($request->filter == 1) {
                    // phone
                    if (!$request->has('phone')) {
                        return $this->returnError(206, 'fail');
                    }

                    $complains = Complain::whereHas('user', function ($q) use ($request) {
                        $q->where('phone', $request->phone);
                    })
                        ->with(['user' => function ($q) use ($request) {
                            $q->select('id', 'name')->where('phone', $request->phone);
                        }])
                        ->with(['masafr'  => function ($q) {
                            $q->select('id', 'name');
                        }])
                        ->paginate($request->paginateCount);
                } else if ($request->filter == 2) {
                    // complain_id
                    if (!$request->has('complain_id')) {
                        return $this->returnError(206, 'fail');
                    }
                    $complains = Complain::with(['user' => function ($q) {
                        $q->select('id', 'name');
                    }])
                        ->with(['masafr'  => function ($q) {
                            $q->select('id', 'name');
                        }])
                        ->where('id', $request->complain_id)
                        ->paginate($request->paginateCount);
                } else if ($request->filter == 3) {
                    // national_id_number
                    if (!$request->has('national_id_number')) {
                        return $this->returnError(206, 'fail');
                    }
                    $complains = Complain::whereHas('user', function ($q) use ($request) {
                        $q->where('national_id_number', $request->national_id_number);
                    })
                        ->with(['user' => function ($q) use ($request) {
                            $q->select('id', 'name')->where('national_id_number', $request->national_id_number);
                        }])
                        ->with(['masafr'  => function ($q) {
                            $q->select('id', 'name');
                        }])
                        ->paginate($request->paginateCount);
                }
            } else {
                $complains = Complain::with(['user' => function ($q) {
                    $q->select('id', 'name');
                }])
                    ->with(['masafr'  => function ($q) {
                        $q->select('id', 'name');
                    }])
                    ->paginate($request->paginateCount);
            }
            return $this->returnData('data', $complains);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function getAllChats(Request $request)
    {
        try {
            $chats = null;
            if ($request->has('filter')) {
                if ($request->filter == 0) {
                    //  رقم الطلب
                    if (!$request->has('request_id')) {
                        return $this->returnError('206', 'fail');
                    }

                    $chats = Message::whereHas('requestTrip.request', function ($q) use ($request) {
                        $q->where('id', $request->request_id);
                    })
                        ->with(['requestTrip', 'user', 'masafr', 'LastMessageObjects' => function ($q) {
                            $q->select('created_at', 'message_id');
                        }])
                        ->withCount('MessageNotSeen')
                        ->withCount('pronunciationStatements')
                        ->withCount('attachedChat')
                        ->get();
                } else if ($request->filter == 1) {
                    // رقم الرحلة
                    if (!$request->has('trip_id')) {
                        return $this->returnError('206', 'fail');
                    }


                    $chats = Message::whereHas('requestTrip.trip', function ($q) use ($request) {
                        $q->where('id', $request->trip_id);
                    })
                        ->with(['requestTrip', 'user', 'masafr', 'LastMessageObjects' => function ($q) {
                            $q->select('created_at', 'message_id');
                        }])
                        ->withCount('MessageNotSeen')
                        ->withCount('pronunciationStatements')
                        ->withCount('attachedChat')
                        ->get();
                } else if ($request->filter == 2) {
                    // رقم التلبفون
                    if (!$request->has('phone')) {
                        return $this->returnError('206', 'fail');
                    }
                    $chats = Message::whereHas('user', function ($q) use ($request) {
                        $q->where('phone', $request->phone);
                    })
                        ->with(['requestTrip', 'user', 'masafr', 'LastMessageObjects' => function ($q) {
                            $q->select('created_at', 'message_id');
                        }])
                        ->withCount('MessageNotSeen')
                        ->withCount('pronunciationStatements')
                        ->withCount('attachedChat')
                        ->get();
                } else if ($request->filter == 3) {
                    // من تاريخ الي تاريخ
                    if (!$request->has('from_date') || !$request->has('to_date')) {
                        return $this->returnError('206', 'fail');
                    }

                    $chats = Message::with(['requestTrip', 'user', 'masafr', 'LastMessageObjects' => function ($q) {
                        $q->select('created_at', 'message_id');
                    }])
                        ->withCount('MessageNotSeen')
                        ->withCount('pronunciationStatements')
                        ->withCount('attachedChat')
                        ->where('created_at', '>=', $request->from_date)
                        ->where('created_at', '<=', $request->to_date)
                        ->get();
                } else if ($request->filter == 4) {
                    // ملغي
                    $chats = Message::whereHas('requestTrip', function ($q) use ($request) {
                        $q->where('offer_status', '-1');
                    })
                        ->with(['requestTrip', 'user', 'masafr', 'LastMessageObjects' => function ($q) {
                            $q->select('created_at', 'message_id');
                        }])
                        ->withCount('MessageNotSeen')
                        ->withCount('pronunciationStatements')
                        ->withCount('attachedChat')
                        ->get();
                } else if ($request->filter == 5) {
                    // جاري التنفيذ

                    $chats = Message::whereHas('requestTrip', function ($q) use ($request) {
                        $q->where('offer_status', '4');
                    })
                        ->with(['requestTrip', 'user', 'masafr', 'LastMessageObjects' => function ($q) {
                            $q->select('created_at', 'message_id');
                        }])
                        ->withCount('MessageNotSeen')
                        ->withCount('pronunciationStatements')
                        ->withCount('attachedChat')
                        ->get();
                } else if ($request->filter == 6) {
                    // معلق

                    $chats = Message::whereHas('requestTrip', function ($q) use ($request) {
                        $q->where('offer_status', '2');
                    })
                        ->with(['requestTrip', 'user', 'masafr', 'LastMessageObjects' => function ($q) {
                            $q->select('created_at', 'message_id');
                        }])
                        ->withCount('MessageNotSeen')
                        ->withCount('pronunciationStatements')
                        ->withCount('attachedChat')
                        ->get();
                } else if ($request->filter == 7) {
                    //تم التنفيذ

                    $chats = Message::whereHas('requestTrip', function ($q) use ($request) {
                        $q->where('offer_status', '5');
                    })
                        ->with(['requestTrip', 'user', 'masafr', 'LastMessageObjects' => function ($q) {
                            $q->select('created_at', 'message_id');
                        }])
                        ->withCount('MessageNotSeen')
                        ->withCount('pronunciationStatements')
                        ->withCount('attachedChat')
                        ->get();
                } else if ($request->filter == 8) {
                    // منتهي


                    $chats = Message::whereHas('requestTrip.request', function ($q) use ($request) {
                        $q->where('max_day', '<', Carbon\Carbon::now());
                    })
                        ->with(['requestTrip', 'user', 'masafr', 'LastMessageObjects' => function ($q) {
                            $q->select('created_at', 'message_id');
                        }])
                        ->withCount('MessageNotSeen')
                        ->withCount('pronunciationStatements')
                        ->withCount('attachedChat')
                        ->get();
                } else {
                    $chats = Message::with(['requestTrip.request', 'user', 'masafr', 'LastMessageObjects' => function ($q) {
                        $q->select('created_at', 'message_id');
                    }])
                        ->withCount('MessageNotSeen')
                        ->withCount('pronunciationStatements')
                        ->withCount('attachedChat')
                        ->get();
                }
            } else {

                $chats = Message::with(['requestTrip', 'user', 'masafr', 'LastMessageObjects' => function ($q) {
                    $q->select('created_at', 'message_id');
                }])
                    ->withCount('MessageNotSeen')
                    ->withCount('pronunciationStatements')
                    ->withCount('attachedChat')
                    ->get();
            }
            return $this->returnData('data', $chats);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }


    public function getTrips(Request $request)
    {
        try {
            $trips = null;
            if ($request->has('filter')) {
                if ($request->filter == 0) {
                    // request_id
                    if (!$request->has('request_id')) {
                        return $this->returnError(206, 'fail');
                    }

                    $trips = Trips::whereHas('relatedRequests', function ($q) use ($request) {
                        $q->where('request_id', $request->request_id);
                    })
                        ->with(['relatedRequests' => function ($q) use ($request) {
                            $q->select('request_id', 'trip_id', 'chat_id', 'offer_status')->where('request_id', $request->request_id);
                        }])
                        ->with(['masafr' => function ($q) {
                            $q->with('chats.MessageNotSeen')->select('id', 'name');
                        }])
                        ->with(["tripCategory" => function ($q) {
                            $q->select("id", "categorie_name");
                        }])
                        ->select('id', 'description', 'from_place', 'to_place', 'type_of_trips', 'type_of_services', 'created_at', 'masafr_id')

                        ->paginate($request->paginateCount);
                } else if ($request->filter == 1) {
                    // trip_id
                    if (!$request->has('trip_id')) {
                        return $this->returnError(206, 'fail');
                    }
                    $trips = Trips::with(['relatedRequests' => function ($q) use ($request) {
                        $q->select('request_id', 'trip_id', 'chat_id', 'offer_status')->where('trip_id', $request->trip_id);
                    }])
                        ->with(['masafr' => function ($q) {
                            $q->with('chats.MessageNotSeen')->select('id', 'name');
                        }])
                        ->with(["tripCategory" => function ($q) {
                            $q->select("id", "categorie_name");
                        }])
                        ->where('id', $request->trip_id)
                        ->select('id', 'description', 'from_place', 'to_place', 'type_of_trips', 'type_of_services', 'created_at', 'masafr_id')
                        ->paginate($request->paginateCount);
                } else if ($request->filter == 2) {
                    // phone
                    if (!$request->has('phone')) {
                        return $this->returnError(206, 'fail');
                    }
                    $trips = Trips::whereHas('masafr', function ($q) use ($request) {
                        $q->where('phone', $request->phone);
                    })
                        ->with('relatedRequests.request.user')
                        ->with('masafr.chats.MessageNotSeen')
                        ->with(["tripCategory" => function ($q) {
                            $q->select("id", "categorie_name");
                        }])
                        ->select('id', 'description', 'from_place', 'to_place', 'type_of_trips', 'type_of_services', 'created_at', 'masafr_id')
                        ->paginate($request->paginateCount);
                } else if ($request->filter == 3) {
                    // from_place to_place
                    if (!$request->has('from_place') || !$request->has('to_place')) {
                        return $this->returnError(206, 'fail');
                    }
                    $trips = Trips::with('masafr.chats.MessageNotSeen')
                        ->with('relatedRequests')
                        ->with(["tripCategory" => function ($q) {
                            $q->select("id", "categorie_name");
                        }])
                        ->where('from_place', 'like', '%' . $request->from_place . '%')
                        ->where('to_place', 'like', '%' . $request->to_place . '%')
                        ->select('id', 'description', 'from_place', 'to_place', 'type_of_trips', 'type_of_services', 'created_at', 'masafr_id')
                        ->paginate($request->paginateCount);
                } else {
                    $trips = Trips::with('masafr.chats.MessageNotSeen')
                        ->with('relatedRequests')
                        ->with(["tripCategory" => function ($q) {
                            $q->select("id", "categorie_name");
                        }])
                        ->select('id', 'description', 'from_place', 'to_place', 'type_of_trips', 'type_of_services', 'created_at', 'masafr_id')
                        ->paginate($request->paginateCount);
                }
            } else {
                $trips = Trips::with(['masafr' => function ($q) {
                    $q->with('chats.MessageNotSeen')->select('id', 'name');
                }])
                    ->with(['relatedRequests' => function ($q) {
                        $q->select('request_id', 'trip_id', 'chat_id', 'offer_status');
                    }])
                    ->with(["tripCategory" => function ($q) {
                        $q->select("id", "categorie_name");
                    }])
                    ->select('id', 'description', 'from_place', 'to_place', 'type_of_trips', 'type_of_services', 'created_at', 'masafr_id')
                    ->paginate($request->paginateCount);
            }
            return $this->returnData('data', $trips);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function getRequests(Request $request)
    {
        try {
            $requests = null;
            if ($request->has('filter')) {
                if ($request->filter == 0) {
                    // with request_id
                    if (!$request->has('id')) {
                        return $this->returnError('206', 'fail');
                    }
                    $requests = RequestService::with(['user.Admin' => function ($q) {
                        $q->select('id', 'name');
                    }])
                        // ->with('trips')
                        ->with('requestTrip.trip.masafr')
                        ->where('id', $request->id)
                        ->paginate($request->paginateCount);
                } else if ($request->filter == 1) {
                    // user phone
                    if (!$request->has('phone')) {
                        return $this->returnError('206', 'fail');
                    }
                    $requests = RequestService::whereHas('user', function ($q) use ($request) {
                        $q->where('phone', $request->phone);
                    })
                        ->with(['user.Admin' => function ($q) {
                            $q->select('id', 'name');
                        }])
                        ->with('requestTrip.trip.masafr')
                        ->get();
                } else if ($request->filter == 2) {
                    // from place
                    if (!$request->has('from_place')) {
                        return $this->returnError('206', 'fail');
                    }
                    $requests = RequestService::with(['user.Admin' => function ($q) {
                        $q->select('id', 'name');
                    }])
                        ->with('requestTrip.trip.masafr')
                        ->where('from_place', 'like', '%' . $request->from_place . '%')
                        ->paginate($request->paginateCount);
                } else if ($request->filter == 3) {
                    // to place
                    if (!$request->has('to_place')) {
                        return $this->returnError('206', 'fail');
                    }
                    $requests = RequestService::with(['user.Admin' => function ($q) {
                        $q->select('id', 'name');
                    }])
                        ->with('requestTrip.trip.masafr')
                        ->where('to_place', 'like', '%' . $request->to_place . '%')
                        ->paginate($request->paginateCount);
                } else if ($request->filter == 4) {
                    // ملغي
                    $requests = RequestService::whereHas('requestTrip', function ($q) {
                        $q->where('offer_status', '=', '-1');
                    })
                        ->with(['user.Admin' => function ($q) {
                            $q->select('id', 'name');
                        }])
                        ->with('requestTrip', function ($q) {
                            $q->with('trip.masafr')->where('offer_status', '=', '-1');
                        })
                        ->paginate($request->paginateCount);
                } else if ($request->filter == 5) {
                    // جاري التاكيد

                    $requests = RequestService::whereHas('requestTrip', function ($q) {
                        $q->where('offer_status', '=', '3');
                    })
                        ->with(['user.Admin' => function ($q) {
                            $q->select('id', 'name');
                        }])
                        ->with('requestTrip', function ($q) {
                            $q->with('trip.masafr')->where('offer_status', '=', '3');
                        })
                        ->paginate($request->paginateCount);
                } else if ($request->filter == 6) {
                    // جاري التنفيذ
                    $requests = RequestService::whereHas('requestTrip', function ($q) {
                        $q->where('offer_status', '=', '4');
                    })
                        ->with(['user.Admin' => function ($q) {
                            $q->select('id', 'name');
                        }])
                        ->with('requestTrip', function ($q) {
                            $q->with('trip.masafr')->where('offer_status', '=', '4');
                        })
                        ->paginate($request->paginateCount);
                } else if ($request->filter == 7) {
                    // معلق

                    $requests = RequestService::whereHas('requestTrip', function ($q) {
                        $q->where('offer_status', '=', '2');
                    })
                        ->with(['user.Admin' => function ($q) {
                            $q->select('id', 'name');
                        }])
                        ->with('requestTrip', function ($q) {
                            $q->with('trip.masafr')->where('offer_status', '=', '2');
                        })
                        ->paginate($request->paginateCount);
                } else if ($request->filter == 8) {
                    // تم التنفيذ

                    $requests = RequestService::whereHas('requestTrip', function ($q) {
                        $q->where('offer_status', '=', '5');
                    })
                        ->with(['user.Admin' => function ($q) {
                            $q->select('id', 'name');
                        }])
                        ->with('requestTrip', function ($q) {
                            $q->with('trip.masafr')->where('offer_status', '=', '5');
                        })
                        ->paginate($request->paginateCount);
                } else if ($request->filter == 9) {
                    // منتهي

                    $requests = RequestService::whereHas('requestTrip', function ($q) {
                        $q->with('trip.masafr')->where('offer_status', '!=', '5');
                    })
                        ->with(['user.Admin' => function ($q) {
                            $q->select('id', 'name');
                        }])
                        ->with('requestTrip.trip.masafr')
                        ->where('max_day', '<', Carbon\Carbon::now())
                        ->paginate($request->paginateCount);
                } else {
                    $requests = RequestService::with(['user.Admin' => function ($q) {
                        $q->select('id', 'name');
                    }])
                        // ->with('trips')
                        ->with('requestTrip.trip.masafr')
                        ->paginate($request->paginateCount);
                }
            } else {
                // كل الطلبات
                $requests = RequestService::with(['user.Admin' => function ($q) {
                    $q->select('id', 'name');
                }])
                    // ->with('trips')
                    ->with('requestTrip.trip.masafr')
                    ->paginate($request->paginateCount);
            }
            return $this->returnData('data', $requests);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function getRequestResponsers(Request $request)
    {
        try {
            $rules = [
                'request_id' => 'required|numeric'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $request_trips = RequestTrip::with('trip.masafr')
                ->with(['fatoorahList' => function ($q) {
                    $q->with('fatoorah')->where('accepted', '1');
                }])
                ->with(['request' => function ($q) {
                    $q->select('insurance_value', 'id');
                }])
                ->with('message')
                ->where('request_id', $request->request_id)
                ->paginate($request->paginateCount);
            return $this->returnData('data', $request_trips);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function getUsers(Request $request)
    {
        try {
            $users = null;
            if ($request->has('filter')) {
                if ($request->filter == 0) {
                    // name
                    if (!$request->has('name')) {
                        return $this->returnError('206', 'fail');
                    }

                    $users = User::withCount(['complains' => function ($q) {
                        $q->where('user_negative', 1);
                    }])
                        ->withCount('requests')
                        ->withCount(['requestTripNotFinished' => function ($q) {
                            $q->where('offer_status', '!=', '5');
                        }])
                        ->with(['Admin' => function ($q) {
                            $q->select('id', 'name');
                        }])
                        ->where('name', 'like', '%' . $request->name . '%')
                        ->paginate($request->paginateCount);
                } else if ($request->filter == 1) {
                    // email
                    if (!$request->has('email')) {
                        return $this->returnError('206', 'fail');
                    }
                    $users = User::withCount(['complains' => function ($q) {
                        $q->where('user_negative', 1);
                    }])
                        ->withCount('requests')
                        ->withCount(['requestTripNotFinished' => function ($q) {
                            $q->where('offer_status', '!=', '5');
                        }])
                        ->with(['Admin' => function ($q) {
                            $q->select('id', 'name');
                        }])
                        ->where('email', 'like', '%' . $request->name . '%')
                        ->paginate($request->paginateCount);
                } else if ($request->filter == 2) {
                    // nationality
                    if (!$request->has('nationality')) {
                        return $this->returnError('206', 'fail');
                    }
                    $users = User::withCount(['complains' => function ($q) {
                        $q->where('user_negative', 1);
                    }])
                        ->withCount('requests')
                        ->withCount(['requestTripNotFinished' => function ($q) {
                            $q->where('offer_status', '!=', '5');
                        }])
                        ->with(['Admin' => function ($q) {
                            $q->select('id', 'name');
                        }])
                        ->where('nationality', 'like', '%' . $request->nationality . '%')
                        ->paginate($request->paginateCount);
                } else if ($request->filter == 3) {
                    // phone
                    if (!$request->has('phone')) {
                        return $this->returnError('206', 'fail');
                    }
                    $users = User::withCount(['complains' => function ($q) {
                        $q->where('user_negative', 1);
                    }])
                        ->withCount('requests')
                        ->withCount(['requestTripNotFinished' => function ($q) {
                            $q->where('offer_status', '!=', '5');
                        }])
                        ->with(['Admin' => function ($q) {
                            $q->select('id', 'name');
                        }])
                        ->where('phone', $request->phone)
                        ->paginate($request->paginateCount);
                } else if ($request->filter == 4) {
                    // national_id_number
                    if (!$request->has('national_id_number')) {
                        return $this->returnError('206', 'fail');
                    }
                    $users = User::withCount(['complains' => function ($q) {
                        $q->where('user_negative', 1);
                    }])
                        ->withCount('requests')
                        ->withCount(['requestTripNotFinished' => function ($q) {
                            $q->where('offer_status', '!=', '5');
                        }])
                        ->with(['Admin' => function ($q) {
                            $q->select('id', 'name');
                        }])
                        ->where('national_id_number', $request->national_id_number)
                        ->paginate($request->paginateCount);
                } else if ($request->filter == 5) {
                    // from date to date
                    if (!$request->has('from_date') || !$request->has('to_date')) {
                        return $this->returnError('206', 'fail');
                    }
                    $users = User::withCount(['complains' => function ($q) {
                        $q->where('user_negative', 1);
                    }])
                        ->withCount('requests')
                        ->withCount(['requestTripNotFinished' => function ($q) {
                            $q->where('offer_status', '!=', '5');
                        }])
                        ->with(['Admin' => function ($q) {
                            $q->select('id', 'name');
                        }])
                        ->where('created_at', '>=', $request->from_date)
                        ->where('created_at', '<=', $request->to_date)
                        ->paginate($request->paginateCount);
                } else {
                    $users = User::withCount(['complains' => function ($q) {
                        $q->where('user_negative', 1);
                    }])
                        ->withCount('requests')
                        ->withCount(['requestTripNotFinished' => function ($q) {
                            $q->where('offer_status', '!=', '5');
                        }])
                        ->with(['Admin' => function ($q) {
                            $q->select('id', 'name');
                        }])
                        ->paginate($request->paginateCount);
                }
            } else {
                $users = User::withCount(['complains' => function ($q) {
                    $q->where('user_negative', 1);
                }])
                    ->withCount('requests')
                    ->withCount(['requestTripNotFinished' => function ($q) {
                        $q->where('offer_status', '!=', '5');
                    }])
                    ->with(['Admin' => function ($q) {
                        $q->select('id', 'name');
                    }])
                    ->paginate($request->paginateCount);
            }
            return $this->returnData('data', $users);
        } catch (\Exception $e) {
            return $this->returnError('201', $e->getMessage());
        }
    }

    public function getUsersName()
    {
        try {
            $users = User::select('id', 'name', 'phone')->get();
            return $this->returnData('data', $users);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function searchUsersName($userName)
    {
        try {
            $users = User::select('id', 'name', 'phone')
                ->where('name', 'like', '%' . $userName . '%')
                ->get();
            return $this->returnData('data', $users);
        } catch (\Exception $e) {
            return $this->returnError('201', $e->getMessage());
        }
    }

    public function getMasafrsName()
    {
        try {
            $masafrs = Masafr::select('id', 'name', 'phone')->get();
            return $this->returnData('data', $masafrs);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function searchMasafrsName($masafrName)
    {
        try {
            $masafrs = Masafr::select('id', 'name', 'phone')
                ->where('name', 'like', '%' . $masafrName . '%')
                ->get();
            return $this->returnData('data', $masafrs);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function getAllFatoorahs(Request $request)
    {
        try {
            $fatoorahs = null;
            if ($request->has('filter')) {
                if ($request->filter == 0) {
                    // request_id
                    if (!$request->has('request_id')) {
                        return $this->returnError('206', 'fail');
                    }

                    $fatoorahs = FatoorahList::whereHas('requestTrip.request', function ($q) use ($request) {
                        $q->where('id', $request->request_id);
                    })
                        ->with(['requestTrip.request' => function ($q) use ($request) {
                            $q->where('id', $request->request_id);
                        }])
                        ->with('requestTrip.trip.masafr')
                        ->with('requestTrip.request.user')
                        ->get();
                } else if ($request->filter == 1) {
                    // trip_id
                    if (!$request->has('trip_id')) {
                        return $this->returnError('206', 'fail');
                    }
                    $fatoorahs = FatoorahList::whereHas('requestTrip.trip', function ($q) use ($request) {
                        $q->where('id', $request->trip_id);
                    })
                        ->with(['requestTrip.trip' => function ($q) use ($request) {
                            $q->where('id', $request->trip_id);
                        }])
                        ->with('requestTrip.trip.masafr')
                        ->with('requestTrip.request.user')
                        ->get();
                } else if ($request->filter == 2) {
                    // national_id_number
                    if (!$request->has('national_id_number')) {
                        return $this->returnError('206', 'fail');
                    }
                    $fatoorahs = FatoorahList::whereHas('requestTrip.request.user', function ($q) use ($request) {
                        $q->where('national_id_number', $request->national_id_number);
                    })
                        ->with(['requestTrip.request.user' => function ($q) use ($request) {
                            $q->where('national_id_number', $request->national_id_number);
                        }])
                        ->get();
                } else if ($request->filter == 3) {
                    // phone
                    if (!$request->has('phone')) {
                        return $this->returnError('206', 'fail');
                    }
                    $fatoorahs = FatoorahList::whereHas('requestTrip.request.user', function ($q) use ($request) {
                        $q->where('phone', $request->phone);
                    })
                        ->with(['requestTrip.request.user' => function ($q) use ($request) {
                            $q->where('phone', $request->phone);
                        }])
                        ->get();
                } else if ($request->filter == 4) {
                    // fatoorah_id

                    if (!$request->has('fatoorah_id')) {
                        return $this->returnError('206', 'fail');
                    }
                    $fatoorahs = FatoorahList::with('requestTrip.request.user')
                        ->with('requestTrip.trip.masafr')
                        ->where('id', $request->fatoorah_id)
                        ->get();
                }
            } else {
                $fatoorahs = FatoorahList::with('requestTrip.request.user')
                    ->with('requestTrip.trip.masafr')
                    ->get();
            }
            return $this->returnData('data', $fatoorahs);
        } catch (\Exception $e) {
            return $this->returnError('201', $e->getMessage());
        }
    }

    public function getTransactionsFiscalYear(Request $request)
    {
        try {
            if ($request->has('filter')) {
                if ($request->filter == 0) {
                    // request_id
                    if (!$request->has('request_id')) {
                        return $this->returnError('206', 'fail');
                    }
                    $fatoorahs = FatoorahList::whereHas('requestTrip', function ($q) use ($request) {
                        $q->where('request_id', $request->request_id);
                    })
                        ->with(['fatoorah' => function ($q) {
                            $q->select('id', 'value', 'fatoorah_list_id');
                        }])
                        ->with(['requestTrip' => function ($q) {
                            $q->with(['request' => function ($k) {
                                $k->with(['user' => function ($w) {
                                    $w->select('id', 'name');
                                }])->select('id', 'created_at', 'user_id');
                            }])
                                ->with(['trip' => function ($k) {
                                    $k->with(['masafr' => function ($w) {
                                        $w->select('id', 'name');
                                    }])->select('id', 'masafr_id');
                                }])

                                ->select('id', 'request_id', 'trip_id', 'payment_method', 'website_service', 'insurance_hold', 'discounts', 'offer_status');
                        }])
                        ->get();
                } else if ($request->filter == 1) {
                    // phone
                    if (!$request->has('phone')) {
                        return $this->returnError('206', 'fail');
                    }
                    $fatoorahs = FatoorahList::whereHas('requestTrip.request.user', function ($q) use ($request) {
                        $q->where('phone', $request->phone);
                    })
                        ->with(['fatoorah' => function ($q) {
                            $q->select('id', 'value', 'fatoorah_list_id');
                        }])->with(['requestTrip' => function ($q) {
                            $q->with(['request' => function ($k) {
                                $k->with(['user' => function ($w) {
                                    $w->select('id', 'name');
                                }])->select('id', 'created_at', 'user_id');
                            }])
                                ->with(['trip' => function ($k) {
                                    $k->with(['masafr' => function ($w) {
                                        $w->select('id', 'name');
                                    }])->select('id', 'masafr_id');
                                }])

                                ->select('id', 'request_id', 'trip_id', 'payment_method', 'website_service', 'insurance_hold', 'discounts', 'offer_status');
                        }])
                        ->get();
                } else if ($request->filter == 2) {
                    // national_id_number
                    if (!$request->has('national_id_number')) {
                        return $this->returnError('206', 'fail');
                    }
                    $fatoorahs = FatoorahList::whereHas('requestTrip.request.user', function ($q) use ($request) {
                        $q->where('national_id_number', $request->national_id_number);
                    })
                        ->with(['fatoorah' => function ($q) {
                            $q->select('id', 'value', 'fatoorah_list_id');
                        }])->with(['requestTrip' => function ($q) use ($request) {
                            $q->with(['request' => function ($k) use ($request) {
                                $k->with(['user' => function ($w) use ($request) {
                                    $w->select('id', 'name')->where('national_id_number', $request->national_id_number);
                                }])->select('id', 'created_at', 'user_id');
                            }])
                                ->with(['trip' => function ($k) {
                                    $k->with(['masafr' => function ($w) {
                                        $w->select('id', 'name');
                                    }])->select('id', 'masafr_id');
                                }])

                                ->select('id', 'request_id', 'trip_id', 'payment_method', 'website_service', 'insurance_hold', 'discounts', 'offer_status');
                        }])
                        ->get();
                } else if ($request->filter == 3) {
                    // from_date to_date
                    if (!$request->has('from_date') || !$request->has('to_date')) {
                        return $this->returnError('206', 'fail');
                    }
                    $fatoorahs = FatoorahList::with(['fatoorah' => function ($q) {
                        $q->select('id', 'value', 'fatoorah_list_id');
                    }])
                        ->with(['requestTrip' => function ($q) {
                            $q->with(['request' => function ($k) {
                                $k->with(['user' => function ($w) {
                                    $w->select('id', 'name');
                                }])->select('id', 'created_at', 'user_id');
                            }])
                                ->with(['trip' => function ($k) {
                                    $k->with(['masafr' => function ($w) {
                                        $w->select('id', 'name');
                                    }])->select('id', 'masafr_id');
                                }])

                                ->select('id', 'request_id', 'trip_id', 'payment_method', 'website_service', 'insurance_hold', 'discounts', 'offer_status');
                        }])
                        ->where('created_at', '>=', $request->from_date)
                        ->where('created_at', '<=', $request->to_date)
                        ->get();
                } else {
                    $fatoorahs = FatoorahList::with(['fatoorah' => function ($q) {
                        $q->select('id', 'value', 'fatoorah_list_id');
                    }])
                        ->with(['requestTrip' => function ($q) {
                            $q->with(['request' => function ($k) {
                                $k->with(['user' => function ($w) {
                                    $w->select('id', 'name');
                                }])->select('id', 'created_at', 'user_id');
                            }])
                                ->with(['trip' => function ($k) {
                                    $k->with(['masafr' => function ($w) {
                                        $w->select('id', 'name');
                                    }])->select('id', 'masafr_id');
                                }])

                                ->select('id', 'request_id', 'trip_id', 'payment_method', 'website_service', 'insurance_hold', 'discounts', 'offer_status');
                        }])
                        ->get();
                }
            } else {
                $fatoorahs = FatoorahList::with(['fatoorah' => function ($q) {
                    $q->select('id', 'value', 'fatoorah_list_id');
                }])
                    ->with(['requestTrip' => function ($q) {
                        $q->with(['request' => function ($k) {
                            $k->with(['user' => function ($w) {
                                $w->select('id', 'name');
                            }])->select('id', 'created_at', 'user_id');
                        }])
                            ->with(['trip' => function ($k) {
                                $k->with(['masafr' => function ($w) {
                                    $w->select('id', 'name');
                                }])->select('id', 'masafr_id');
                            }])

                            ->select('id', 'request_id', 'trip_id', 'payment_method', 'website_service', 'insurance_hold', 'discounts', 'offer_status');
                    }])
                    ->get();
            }
            return $this->returnData('data', $fatoorahs);
        } catch (\Exception $e) {
            return $this->returnError('201', $e->getMessage());
        }
    }

    public function getAllWindows(Request $request)
    {
        try {
            $windows = null;
            if ($request->has('filter')) {
                if ($request->filter == 0) {
                    // name
                    if (!$request->has('name')) {
                        return $this->returnError(206, 'fail');
                    }
                    $windows = AdminNotificationOrEmail::whereHas('persons.user', function ($q) use ($request) {
                        return  $q->select('id', 'name')
                            ->where('name', 'like', '%' . $request->name . '%');
                    })
                        ->with('persons.user', function ($q) use ($request) {
                            return  $q->select('id', 'name')
                                ->where('name', 'like', '%' . $request->name . '%');
                        })
                        ->orWhereHas('persons.masafr', function ($q) use ($request) {
                            return  $q->select('id', 'name')
                                ->where('name', 'like', '%' . $request->name . '%');
                        })
                        ->with('persons.masafr', function ($q) use ($request) {
                            return  $q->select('id', 'name')
                                ->where('name', 'like', '%' . $request->name . '%');
                        })
                        ->where('send_by', 1)
                        ->get();
                } else if ($request->filter == 1) {
                    // phone
                    if (!$request->has('phone')) {
                        return $this->returnError(206, 'fail');
                    }
                    $windows = AdminNotificationOrEmail::whereHas('persons.user', function ($q) use ($request) {
                        return  $q->select('id', 'name')
                            ->where('phone', $request->phone);
                    })
                        ->with('persons.user', function ($q) use ($request) {
                            return  $q->select('id', 'name')
                                ->where('phone', $request->phone);
                        })
                        ->orWhereHas('persons.masafr', function ($q) use ($request) {
                            return  $q->select('id', 'name')
                                ->where('phone', $request->phone);
                        })
                        ->with('persons.masafr', function ($q) use ($request) {
                            return  $q->select('id', 'name')
                                ->where('phone', $request->phone);
                        })
                        ->where('send_by', 1)
                        ->get();
                } else if ($request->filter == 2) {
                    // national_id_number
                    if (!$request->has('national_id_number')) {
                        return $this->returnError(206, 'fail');
                    }
                    $windows = AdminNotificationOrEmail::whereHas('persons.user', function ($q) use ($request) {
                        return  $q->select('id', 'name')
                            ->where('national_id_number', $request->national_id_number);
                    })
                        ->with('persons.user', function ($q) use ($request) {
                            return  $q->select('id', 'name')
                                ->where('national_id_number', $request->national_id_number);
                        })
                        ->orWhereHas('persons.masafr', function ($q) use ($request) {
                            return  $q->select('id', 'name')
                                ->where('national_id_number', $request->national_id_number);
                        })
                        ->with('persons.masafr', function ($q) use ($request) {
                            return  $q->select('id', 'name')
                                ->where('national_id_number', $request->national_id_number);
                        })
                        ->where('send_by', 1)
                        ->get();
                } else if ($request->filter == 3) {
                    // from date to date

                    if (!$request->has('from_date') || !$request->has('to_date')) {
                        return $this->returnError(206, 'fail');
                    }
                    $windows = AdminNotificationOrEmail::with(['persons.user' => function ($q) use ($request) {
                        $q->select('id', 'name');
                    }])
                        ->with(['persons.masafr' => function ($q) use ($request) {
                            $q->select('id', 'name');
                        }])
                        ->where('created_at', '>=', $request->from_date)
                        ->where('created_at', '<=', $request->to_date)
                        ->where('send_by', 1)
                        ->get();
                } else {
                    $windows = AdminNotificationOrEmail::with(['persons.user' => function ($q) {
                        $q->select('id', 'name');
                    }])
                        ->with(['persons.masafr' => function ($q) {
                            $q->select('id', 'name');
                        }])
                        ->where('send_by', 1)
                        ->get();
                }
            } else {
                $windows = AdminNotificationOrEmail::with(['persons.user' => function ($q) {
                    $q->select('id', 'name');
                }])
                    ->with(['persons.masafr' => function ($q) {
                        $q->select('id', 'name');
                    }])
                    ->where('send_by', 1)
                    ->get();
            }
            return $this->returnData('data', $windows);
        } catch (\Exception $e) {
            return $this->returnError('201', $e->getMessage());
        }
    }

    public function deleteWindows(Request $request)
    {
        DB::beginTransaction();
        try {
            if (!$request->has('window_id')) {
                return $this->returnError('202', 'the window_id is required');
            }
            if (!is_array($request->window_id)) {
                return $this->returnError('202', 'the window_id should be array');
            }
            foreach ($request->window_id as $windowId) {
                $window = AdminNotificationOrEmail::find($windowId);
                if (!$window) {
                    return $this->returnError('203', 'this window does not exist');
                }
                if ($window->send_by != 1) {
                    return $this->returnError('204', 'this is not a window');
                }
                foreach ($window->persons as $person) {
                    $person->delete();
                }
                $window->delete();
            }
            DB::commit();
            return $this->returnSuccessMessage('deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnError('201', $e->getMessage());
        }
    }

    public function getRollbackMoney(Request $request)
    {
        try {
            $rollbacks = RollbackRequestMoney::with('request.user')
                ->paginate($request->paginateCount);
            return $this->returnData('data', $rollbacks);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function getRollbackRequests(Request $request)
    {
        try {
            $rollbacks = null;
            if ($request->has('filter')) {

                if ($request->filter == 0) {
                    // request_id
                    if (!$request->has('request_id')) {
                        return $this->returnError(206, 'fail');
                    }
                    $rollbacks = RollbackRequest::with('user')
                        ->with('masafr')
                        ->where('request_id', $request->request_id)
                        ->paginate($request->paginateCount);
                } else if ($request->filter == 1) {
                    // phone
                    if (!$request->has('phone')) {
                        return $this->returnError(206, 'fail');
                    }
                    if ($request->phone == null) {
                        return $this->returnError(207, 'fail');
                    }
                    $rollbacks = RollbackRequest::whereHas('user', function ($q) use ($request) {
                        return $q->where('phone', $request->phone);
                    })
                        ->with('user', function ($q) use ($request) {
                            return $q->where('phone', $request->phone);
                        })
                        ->orWhereHas('masafr', function ($q) use ($request) {
                            return  $q->where('phone', $request->phone);
                        })
                        ->with('masafr')
                        ->paginate($request->paginateCount);
                } else if ($request->filter == 2) {
                    // national_id_number

                    if (!$request->has('national_id_number')) {
                        return $this->returnError(206, 'fail');
                    }
                    if ($request->national_id_number == null) {
                        return $this->returnError(207, 'fail');
                    }
                    $rollbacks = RollbackRequest::whereHas('user', function ($q) use ($request) {
                        return $q->where('national_id_number', $request->national_id_number);
                    })
                        ->with('user', function ($q) use ($request) {
                            return $q->where('national_id_number', $request->national_id_number);
                        })
                        ->orWhereHas('masafr', function ($q) use ($request) {
                            return  $q->where('national_id_number', $request->national_id_number);
                        })
                        ->with('masafr')
                        ->paginate($request->paginateCount);
                } else {
                    $rollbacks = RollbackRequest::with('user')
                        ->with('masafr')
                        ->paginate($request->paginateCount);
                }
            } else {
                $rollbacks = RollbackRequest::with('user')
                    ->with('masafr')
                    ->paginate($request->paginateCount);
            }
            return $this->returnData('data', $rollbacks);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function updateApplicationSettings(Request $request)
    {
        try {
            DB::beginTransaction();
            if ($request->has('settings') && isset($request->settings) && count($request->settings) > 0) {
                $i = 0;
                foreach ($request->settings as $setting_n) {
                    $setting_ID = $setting_n[$i]['id'];
                    $setting = ApllicationSetting::find($setting_ID);
                    if (!$setting) {
                        return $this->returnError('202', 'fail');
                    }
                    $setting->update([
                        'value' => $setting_n[$i]['value']
                    ]);
                }
            } else {
                DB::commit();
                return $this->returnError('202', 'fail');
            }
            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', $e->getMessage());
        }
    }

    public function getTrustedInfo(Request $request)
    {
        try {
            $persons = null;
            if ($request->has('filter')) {
                if ($request->filter == 0) {
                    // latest first
                    $persons = UpdateQeueu::latest()
                        ->where('admin_response', '0')
                        ->paginate($request->paginateCount);
                } else if ($request->filter == 1) {
                    // resfused
                    $persons = UpdateQeueu::with('admin')
                        ->where('admin_response', '-1')
                        ->paginate($request->paginateCount);
                } else if ($request->filter == 2) {
                    // not yet
                    $persons = UpdateQeueu::with('admin')
                        ->where('admin_response', '0')
                        ->paginate($request->paginateCount);
                } else if ($request->filter == 3) {
                    // nationality
                    if (!$request->has('nationality')) {
                        return $this->returnError('204', 'fail');
                    }
                    $persons = UpdateQeueu::with('admin')
                        ->where('nationality', 'like', '%' . $request->nationality . '%')
                        ->where('admin_response', '0')
                        ->paginate($request->paginateCount);
                } else if ($request->filter == 4) {
                    // phone
                    if (!$request->has('phone')) {
                        return $this->returnError('204', 'fail');
                    }
                    $persons = UpdateQeueu::with('admin')
                        ->where('phone', $request->phone)
                        ->where('admin_response', '0')
                        ->paginate($request->paginateCount);
                } else if ($request->filter == 5) {
                    // id_nationaltye
                    if (!$request->has('national_id_number')) {
                        return $this->returnError('204', 'fail');
                    }
                    $persons = UpdateQeueu::with('admin')
                        ->where('national_id_number', $request->national_id_number)
                        ->where('admin_response', '0')
                        ->paginate($request->paginateCount);
                } else {
                    $persons = UpdateQeueu::with('admin')
                        ->where('admin_response', '0')
                        ->paginate($request->paginateCount);
                }
            } else {
                $persons = UpdateQeueu::with('admin')
                    ->where('admin_response', '0')
                    ->paginate($request->paginateCount);
            }
            return $this->returnData('data', $persons);
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
            // $code =  rand(100000, 999999);
            $userID =  User::insertGetId([
                'email' => $request->email,
                'phone' => $request->phone,
                'name' => $request->name,
                'nationality' => $request->nationality,
                'gender' => $request->gender,
                'password' => bcrypt($request->password),
                'verification_code' => '-1',
                'photo' => $file_name,
                'country_code' => $request->country_code,
                'is_verified' => 1,
                'is_verified' => 1,
                'trust' => 1,
                'decision_maker' => auth()->user()->id
            ]);
            return $this->returnData('user id', $userID);
        } catch (\Exception $e) {
            return $this->returnError('E205', 'fail');
        }
    }

    public function getMasafrs(Request $request)
    {
        try {
            $masafrs = Masafr::paginate($request->paginateCount);
            return $this->returnData('data', $masafrs);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function getMasafrInfo(Request $request)
    {
        try {
            if (!$request->masafr_id) {
                return $this->returnError('202', 'fail');
            }
            $masafr = Masafr::find($request->masafr_id);
            return $this->returnData('data', $masafr);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function getUserInfo(Request $request)
    {
        try {
            if (!$request->user_id) {
                return $this->returnError('202', 'fail');
            }
            $user = User::find($request->user_id);
            return $this->returnData('data', $user);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function getAllMasafrTrips(Request $request)
    {
        try {
            if (!$request->has('masafr_id')) {
                return $this->returnError('202', 'fail');
            }
            $trips = Trips::with(['relatedRequests' => function ($q) {
                $q->select('id', 'trip_id', 'request_id');
            }])->where('masafr_id', $request->masafr_id)
                ->get();
            return $this->returnData('data', $trips);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function masafrs(Request $request)
    {
        try {
            $masafrs = Masafr::select('id', 'name', 'phone', 'email', 'gender', 'photo', 'national_id_number', 'nationality', 'balance', 'trust')
                ->paginate($request->paginateCount);
            return $this->returnData('data', $masafrs);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function masafrsForeigners(Request $request)
    {
        try {
            $masafrs = Masafr::select('id', 'name', 'phone', 'email', 'gender', 'photo', 'national_id_number', 'nationality', 'balance', 'trust')
                ->where('nationality', '!=', 'سعودي')
                ->orWhere('nationality', null)
                ->paginate($request->paginateCount);
            return $this->returnData('data', $masafrs);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    // CORS
    public function masafrsTrusts(Request $request)
    {
        try {
            $masafrs = Masafr::select('id', 'name', 'phone', 'email', 'gender', 'photo', 'national_id_number', 'nationality', 'balance', 'trust')
                ->where('trust', 1)
                ->paginate($request->paginateCount);
            return $this->returnData('data', $masafrs);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function usersForeigners(Request $request)
    {
        try {
            $users = User::withCount(['complains' => function ($q) {
                $q->where('user_negative', 1);
            }])
                ->withCount('requests')
                ->withCount(['requestTripNotFinished' => function ($q) {
                    $q->where('offer_status', '!=', '5');
                }])
                ->with(['Admin' => function ($q) {
                    $q->select('id', 'name');
                }])
                ->where('nationality', '!=', 'سعودي')
                ->orWhere('nationality', null)
                ->paginate($request->paginateCount);
            return $this->returnData('data', $users);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }


    public function usersTrusts(Request $request)
    {
        try {
            $users = User::withCount(['complains' => function ($q) {
                $q->where('user_negative', 1);
            }])
                ->withCount('requests')
                ->withCount(['requestTripNotFinished' => function ($q) {
                    $q->where('offer_status', '!=', '5');
                }])
                ->with(['Admin' => function ($q) {
                    $q->select('id', 'name');
                }])
                ->where('trust', 1)
                ->paginate($request->paginateCount);
            return $this->returnData('data', $users);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function updateUser(Request $request)
    {
        try {
            if (!$request->has('user_id')) {
                return $this->returnError('202', 'fail');
            }

            $user = User::find($request->user_id);
            if (!$user) {
                return $this->returnError('203', 'fail');
            }

            $photo = null;
            $id_photo = null;
            if ($request->hasFile('photo')) {
                $photo  = $this->saveImage($request->photo, 'users');
            }
            if ($request->hasFile('id_photo')) {
                $id_photo  = $this->saveImage($request->id_photo, 'users_id');
            }

            $photo_len = strlen((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/images/users/');
            $slice_photo = substr($user->photo, $photo_len);

            $photo_len = strlen((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/images/users_id/');
            $slice_id_photo = substr($user->id_photo, $photo_len);


            $user->update([
                'name' => $request->name ?? $user->name,
                'password' => bcrypt($request->password) ?? $user->password,
                'email' => $request->email ?? $user->email,
                'phone' => $request->phone ?? $user->phone,
                'national_id_number' => $request->national_id_number ?? $user->national_id_number,
                'nationality' => $request->nationality ?? $user->nationality,
                'gender' => $request->gender ?? $user->gender,
                'photo' => $photo ?? $slice_photo,
                'id_photo' => $id_photo ?? $slice_id_photo
            ]);

            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }


    public function updateMasafr(Request $request)
    {
        try {
            if (!$request->has('masafr_id')) {
                return $this->returnError('202', 'fail');
            }

            $masafr = Masafr::find($request->masafr_id);
            if (!$masafr) {
                return $this->returnError('203', 'fail');
            }
            $photo = null;
            $id_photo = null;
            if ($request->has('photo')) {
                $photo  = $this->saveImage($request->photo, 'masafrs');
            }
            if ($request->has('id_photo')) {
                $id_photo  = $this->saveImage($request->id_photo, 'masafrs_id');
            }

            $photo_len = strlen((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/images/masafrs/');
            $slice_photo = substr($masafr->photo, $photo_len);

            $photo_len = strlen((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/images/masafrs_id/');
            $slice_id_photo = substr($masafr->id_photo, $photo_len);


            $masafr->update([
                'name' => $request->name ?? $masafr->name,
                'password' => bcrypt($request->password) ?? $masafr->password,
                'email' => $request->email ?? $masafr->email,
                'phone' => $request->phone ?? $masafr->phone,
                'national_id_number' => $request->national_id_number ?? $masafr->national_id_number,
                'nationality' => $request->nationality ?? $masafr->nationality,
                'gender' => $request->gender ?? $masafr->gender,
                'photo' => $photo ?? $slice_photo,
                'id_photo' => $id_photo ?? $slice_id_photo
            ]);
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function deleteUser(Request $request)
    {
        DB::beginTransaction();
        try {
            if (!$request->has('user_id')) {
                return $this->returnError('202', 'fail');
            }
            foreach ($request->user_id as $userID) {
                $user = User::find($userID);
                if (!$user) {
                    return $this->returnError('203', 'fail');
                }
                foreach ($user->complains as $compalin) {
                    foreach ($compalin->complainList as $complainList) {
                        $complainList->delete();
                    }
                    $compalin->delete();
                }
                foreach ($user->requests as $requestM) {
                    foreach ($requestM->requestTrip as $requestTripm) {
                        $requestTripm->delete();
                    }

                    foreach ($requestM->notifications as $notification) {
                        $notification->delete();
                    }
                    $requestM->delete();
                }

                foreach ($user->comments as $comment) {
                    $comment->delete();
                }
                foreach ($user->messages as $message) {
                    foreach ($message->MessageObjects as $MessageObject) {
                        $MessageObject->delete();
                    }
                    foreach ($message->pronunciationStatements as $pronunciationStatement) {
                        $pronunciationStatement->delete();
                    }
                    $message->delete();
                }
                foreach ($user->rollbackRequests as $rollbackRequest) {
                    $rollbackRequest->delete();
                }
                foreach ($user->updateQueue as $updateQueu) {
                    $updateQueu->delete();
                }
                $user->delete();
            }
            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', 'fail');
        }
    }


    public function deleteMasafr(Request $request)
    {
        DB::beginTransaction();
        try {
            if (!$request->has('masafr_id')) {
                return $this->returnError('202', 'fail');
            }
            foreach ($request->masafr_id as $masafrID) {
                $masafr = Masafr::find($masafrID);
                if (!$masafr) {
                    return $this->returnError('203', 'fail');
                }
                foreach ($masafr->complains as $compalin) {
                    foreach ($compalin->complainList as $complainList) {
                        $complainList->delete();
                    }
                    $compalin->delete();
                }

                foreach ($masafr->trips as $trip) {
                    foreach ($trip->requestTrip as $requestTripm) {
                        $requestTripm->delete();
                    }

                    foreach ($trip->notifications as $notification) {
                        $notification->delete();
                    }
                    $trip->delete();
                }


                foreach ($masafr->comments as $comment) {
                    $comment->delete();
                }


                foreach ($masafr->chats as $message) {
                    foreach ($message->MessageObjects as $MessageObject) {
                        $MessageObject->delete();
                    }
                    foreach ($message->pronunciationStatements as $pronunciationStatement) {
                        $pronunciationStatement->delete();
                    }
                    $message->delete();
                }

                // foreach($masafr->rollbackRequests as $rollbackRequest){
                //     $rollbackRequest->delete();
                // }


                foreach ($masafr->updateQueue as $updateQueu) {
                    $updateQueu->delete();
                }
                $masafr->delete();
            }
            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', 'fail');
        }
    }

    public function deleteAdvertising(Request $request)
    {
        DB::beginTransaction();
        try {
            if (!$request->has('advertising_id')) {
                return $this->returnError('202', 'fail');
            }
            foreach ($request->advertising_id as $advertising) {
                $advertising =  Advertising::find($advertising);
                if (!$advertising) {
                    return $this->returnError('203', 'fail');
                }
                // places- users - days
                foreach ($advertising->places as $place) {
                    $place->delete();
                }

                foreach ($advertising->users as $user) {
                    $user->delete();
                }

                foreach ($advertising->days as $day) {
                    $day->delete();
                }
                $advertising->delete();
            }
            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', 'fail');
        }
    }

    public function deleteRequestService(Request $request)
    {
        DB::beginTransaction();
        try {
            if (!$request->has('request_service_id')) {
                return $this->returnError('202', 'fail');
            }
            foreach ($request->request_service_id as $requestServiceID) {
                $request_service = RequestService::find($requestServiceID);
                if (!$request_service) {
                    return $this->returnError('203', 'fail');
                }
                // delete complains 
                foreach ($request_service->complains as $complain) {
                    foreach ($complain->complainList as $complainListM) {
                        $complainListM->delete();
                    }
                    $complain->delete();
                }

                $request_service->delete();
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
        DB::beginTransaction();
        try {
            if (!$request->has('trip_id')) {
                return $this->returnError('202', 'fail');
            }
            foreach ($request->trip_id as $tripID) {
                $trip = Trips::find($tripID);
                if (!$trip) {
                    return $this->returnError('203', 'fail');
                }
                // delete complains 
                foreach ($trip->complains as $complain) {
                    foreach ($complain->complainList as $complainListM) {
                        $complainListM->delete();
                    }
                    $complain->delete();
                }
                $trip->delete();
            }
            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', 'fail');
        }
    }

    public function getAComplain(Request $request)
    {
        try {
            if (!$request->has('complain_id')) {
                return $this->returnError('202', 'fail');
            }

            $complain = Complain::with(['user' => function ($q) {
                $q->select('id', 'name', 'photo');
            }, 'masafr' => function ($q) {
                $q->select('id', 'name', 'photo');
            }, 'complainList'])->find($request->complain_id);
            if (!$complain) {
                return $this->returnError('203', 'fail');
            }
            return $this->returnData('data', $complain);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function deleteCopon(Request $request)
    {
        DB::beginTransaction();
        try {
            if (!$request->has('copon_id')) {
                return $this->returnError('202', 'fail');
            }
            foreach ($request->copon_id as $coponID) {
                $copon = Copon::find($coponID);
                if (!$copon) {
                    return $this->returnError('203', 'fail');
                }
                // users - gift
                foreach ($copon->CoponUsers as $user) {
                    $user->delete();
                }
                foreach ($copon->GiftUsers as $user) {
                    $user->delete();
                }
                $copon->delete();
            }
            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', 'fail');
        }
    }

    public function applicationSettings()
    {
        try {
            $application_settings = ApllicationSetting::get();
            return $this->returnData('data', $application_settings);
        } catch (\Exception $e) {
            return $this->returnError('201', $e->getMessage());
        }
    }

    public function userRequestStatistics($userId)
    {
        try {
            $all_my_requests = RequestService::where('user_id', $userId)->count();
            $my_active_requests = RequestService::where('user_id', $userId)
                ->where('max_day', '>', Carbon\Carbon::now())
                ->count();
            $not_active_requests = RequestService::where('user_id', $userId)
                ->where('max_day', '<', Carbon\Carbon::now())
                ->count();
            $request_processing = RequestService::whereHas('requestTrip', function ($q) {
                $q->where('offer_status', '4');
            })->where('user_id', $userId)
                ->count();
            $request_confirming = RequestService::whereHas('requestTrip', function ($q) {
                $q->where('offer_status', '3');
            })->where('user_id', $userId)
                ->count();
            $request_canceled = RequestService::whereHas('requestTrip', function ($q) {
                $q->where('offer_status', '-1');
            })->where('user_id', $userId)
                ->count();
            $request_done = RequestService::whereHas('requestTrip', function ($q) {
                $q->where('offer_status', '5');
            })->where('user_id', $userId)
                ->count();
            $request_hanging = RequestService::whereHas('requestTrip', function ($q) {
                $q->where('offer_status', '2');
            })->where('user_id', $userId)
                ->count();
            $data['all_my_requests'] = $all_my_requests;
            $data['my_active_requests'] = $my_active_requests;
            $data['not_active_requests'] = $not_active_requests;
            $data['request_processing'] = $request_processing;
            $data['request_confirming'] = $request_confirming;
            $data['request_canceled'] = $request_canceled;
            $data['request_done'] = $request_done;
            $data['request_hanging'] = $request_hanging;
            return $this->returnData('data', $data);
        } catch (\Exception $e) {
            return $this->returnError('201', $e->getMessage());
        }
    }

    public function masafrTripStatistics($masafrId)
    {
        try {
            $all_my_trips = Trips::where('masafr_id', $masafrId)->count();
            $my_active_trips = Trips::where('masafr_id', $masafrId)
                ->where('end_date', '>', Carbon\Carbon::now())
                ->count();
            $not_active_trips = Trips::where('masafr_id', $masafrId)
                ->where('end_date', '<', Carbon\Carbon::now())
                ->count();
            $trips_processing = Trips::whereHas('relatedRequests', function ($q) {
                $q->where('offer_status', '4');
            })->where('masafr_id', $masafrId)
                ->count();
            $trips_confirming = Trips::whereHas('relatedRequests', function ($q) {
                $q->where('offer_status', '3');
            })->where('masafr_id', $masafrId)
                ->count();
            $trips_canceled = Trips::whereHas('relatedRequests', function ($q) {
                $q->where('offer_status', '-1');
            })->where('masafr_id', $masafrId)
                ->count();
            $trips_done = Trips::whereHas('relatedRequests', function ($q) {
                $q->where('offer_status', '5');
            })->where('masafr_id', $masafrId)
                ->count();
            $trips_hanging = Trips::whereHas('relatedRequests', function ($q) {
                $q->where('offer_status', '2');
            })->where('masafr_id', $masafrId)
                ->count();
            $data['all_my_trips'] = $all_my_trips;
            $data['my_active_trips'] = $my_active_trips;
            $data['not_active_trips'] = $not_active_trips;
            $data['trips_processing'] = $trips_processing;
            $data['trips_confirming'] = $trips_confirming;
            $data['trips_canceled'] = $trips_canceled;
            $data['trips_done'] = $trips_done;
            $data['trips_hanging'] = $trips_hanging;
            return $this->returnData('data', $data);
        } catch (\Exception $e) {
            return $this->returnError('201', $e->getMessage());
        }
    }
}
