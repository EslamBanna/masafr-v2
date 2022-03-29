<?php

namespace App\Traits;

use App\Mail\VerficationMail;
use App\Models\Admin\Advertising;
use App\Models\Admin\NotificationOrMailPerson;
use App\Models\Admin\RequestCategorie;
use App\Models\Admin\TripCategorie;
use App\Models\Common\AdminNotificationOrEmail;
use App\Models\Common\Windows;
use App\Models\Common\Comment;
use App\Models\Common\Complain;
use App\Models\Common\ComplainList;
use App\Models\Common\CustomerService;
use App\Models\Common\Message;
use App\Models\Common\MessageObject;
use App\Models\Common\Notification;
use App\Models\Common\PronunciationStatement;
use App\Models\Common\RequestTrip;
use App\Models\Common\Transaction;
use App\Models\Common\UpdateQeueu;
use App\Models\Masafr\Masafr;
use App\Models\Masafr\Trips;
use App\Models\User\RequestService;
use App\Models\User\User;
use Illuminate\Http\Request;
use Validator;
use Auth;
use DateTime;
use JWTAuth;
use DB;

use Carbon;
use Illuminate\Support\Facades\Mail;
use Nexmo\Laravel\Facade\Nexmo;

trait GeneralTrait
{

    public function generateVerficationCode(Request $request)
    {
        try {
            $rules = [
                'type' => 'required|boolean',
                'id' => 'required|numeric'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            // user
            if ($request->type == 0) {
                $user = User::find($request->id);
                if (!$user) {
                    return $this->returnError('202', 'fail');
                }

                $code = $user->verification_code;
                $name = $user->name;
                if ($user->country_code == "966") {
                    $phone = $user->phone;
                    //sms
                    Nexmo::message()->send([
                        'to' => '+201210732005',
                        'from' => '+201287006309',
                        'text' => 'Your Verification Code In Application Tawsel M3a Mosafar Is ' . $code
                    ]);
                } else {
                    $email = $user->email;
                    //email
                    Mail::to($email)->send(new VerficationMail($code, $name, $email));
                }

                //masafr
            } else if ($request->type == 1) {
                $masafr = Masafr::find($request->id);
                if (!$masafr) {
                    return $this->returnError('202', 'fail');
                }
                $name = $masafr->name;
                $code = $masafr->verification_code;
                if ($masafr->country_code == "966") {
                    //sms
                } else {
                    //email
                    $email = $masafr->email;
                    Mail::to($email)->send(new VerficationMail($code, $name, $email));
                }
            }
        } catch (\Exception $e) {
            return $this->returnError('200', 'fail');
        }
    }

    public function varifyAccount(Request $request)
    {
        try {
            $rules = [
                'type' => 'required|boolean',
                'id' => 'required|numeric',
                'code' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            if ($request->type == 0) {

                // user side

                $user = User::find($request->id);
                if (!$user) {
                    return $this->returnError('202', 'fail');
                }
                if ($user->active_try < 3) {
                    if ($user->verification_code == $request->code) {
                        $user->update([
                            'is_verified' => 1
                        ]);
                        return $this->returnSuccessMessage('success');
                    } else {
                        $mytime = Carbon\Carbon::now();
                        $wrong_active_try = $user->active_try + 1;
                        $user->update([
                            'active_try' => $wrong_active_try,
                            'last_try_verify' => $mytime
                        ]);
                        return $this->returnError('700', 'fail');
                    }
                } else {
                    $date1 = new DateTime($user->last_try_verify);
                    $date2 = new DateTime();
                    $diff = $date1->diff($date2);
                    $diffInYears   = $diff->h;
                    if ($diffInYears >= 12) {
                        $user->update([
                            'active_try' => 0
                        ]);
                        if ($user->verification_code == $request->code) {
                            $user->update([
                                'is_verified' => 1
                            ]);
                            return $this->returnSuccessMessage('success');
                        } else {
                            $mytime = Carbon\Carbon::now();
                            $wrong_active_try = $user->active_try + 1;
                            $user->update([
                                'active_try' => $wrong_active_try,
                                'last_try_verify' => $mytime
                            ]);
                            return $this->returnError('700', 'fail');
                        }
                    } else {
                        return $this->returnError('800', 'fail');
                    }
                }
            } else if ($request->type == 1) {
                // masafr

                $masafr = Masafr::find($request->id);
                if (!$masafr) {
                    return $this->returnError('202', 'fail');
                }
                if ($masafr->active_try < 3) {
                    if ($masafr->verification_code == $request->code) {
                        $masafr->update([
                            'is_verified' => 1
                        ]);
                        return $this->returnSuccessMessage('success');
                    } else {
                        $mytime = Carbon\Carbon::now();
                        $wrong_active_try = $masafr->active_try + 1;
                        $masafr->update([
                            'active_try' => $wrong_active_try,
                            'last_try_verify' => $mytime
                        ]);
                        return $this->returnError('700', 'fail');
                    }
                } else {
                    $date1 = new DateTime($masafr->last_try_verify);
                    $date2 = new DateTime();
                    $diff = $date1->diff($date2);
                    $diffInYears   = $diff->h;
                    if ($diffInYears >= 12) {
                        $masafr->update([
                            'active_try' => 0
                        ]);
                        if ($masafr->verification_code == $request->code) {
                            $masafr->update([
                                'is_verified' => 1
                            ]);
                            return $this->returnSuccessMessage('success');
                        } else {
                            $mytime = Carbon\Carbon::now();
                            $wrong_active_try = $masafr->active_try + 1;
                            $masafr->update([
                                'active_try' => $wrong_active_try,
                                'last_try_verify' => $mytime
                            ]);
                            return $this->returnError('700', 'fail');
                        }
                    } else {
                        return $this->returnError('800', 'fail');
                    }
                }
            }
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function resendVerfiyCode(Request $request)
    {
        try {
            $rules = [
                'id' => 'required|numeric',
                'type' => 'required|boolean'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            if ($request->type == 0) {
                $user = User::find($request->id);
                if (!$user) {
                    return $this->returnError('202', 'fail');
                }
                $date1 = new DateTime($user->last_send_verify_code);
                $date2 = new DateTime();
                $diff = $date1->diff($date2);
                // return $diff->i;
                if ($diff->i >= 2) {
                    if ($user->country_code == 966) {
                        Nexmo::message()->send([
                            'to' => '+201210732005',
                            'from' => '+201287006309',
                            'text' => 'Your Verification Code In Application Tawsel M3a Mosafar Is ' . $user->verification_code
                        ]);
                        return $this->returnSuccessMessage('success');
                    } else {
                        Mail::to($user->email)->send(new VerficationMail($user->verification_code, $user->name, $user->email));
                        return $this->returnSuccessMessage('success');
                    }
                } else {
                    return $this->returnError('203', 'fail');
                }
            } else if ($request->type == 1) {
                $masafr = Masafr::find($request->id);
                if (!$masafr) {
                    return $this->returnError('202', 'fail');
                }
                $date1 = new DateTime($masafr->last_send_verify_code);
                $date2 = new DateTime();
                $diff = $date1->diff($date2);
                // return $diff->i;
                if ($diff->i >= 2) {
                    if ($masafr->country_code == 966) {
                        Nexmo::message()->send([
                            'to' => '+201210732005',
                            'from' => '+201287006309',
                            'text' => 'Your Verification Code In Application Tawsel M3a Mosafar Is ' . $masafr->verification_code
                        ]);
                        return $this->returnSuccessMessage('success');
                    } else {
                        Mail::to($masafr->email)->send(new VerficationMail($masafr->verification_code, $masafr->name, $masafr->email));
                        return $this->returnSuccessMessage('success');
                    }
                } else {
                    return $this->returnError('203', 'fail');
                }
            }
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function getWindows(Request $request)
    {
        try {
            $type = (Auth::getDefaultDriver() == 'user-api' ? 0 : 1);
            $windows = AdminNotificationOrEmail::whereHas('persons', function ($q) {
                $q->where('person_id', Auth::user()->id)
                    ->where('showed', 0);
            })
                // ->with(['persons' => function ($q) {
                //     $q->where('person_id', Auth::user()->id)
                //         ->where('showed', 0);
                // }])
                ->where('type', $type)
                ->select('id', 'subject', 'title')
                ->get();
            return $this->returnData('data', $windows);
        } catch (\Exception $e) {
            return $this->returnError('201', $e->getMessage());
        }
    }
    public function confirmAdminWindow(Request $request)
    {
        try {
            $notifi_window = NotificationOrMailPerson::find($request->id);
            if (!$notifi_window) {
                return $this->returnError('202', 'fail');
            }
            $notifi_window->update([
                'showed' => 1,
                'seen_time' => Carbon\Carbon::now()
            ]);
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function updateBalance(Request $request)
    {
        try {
            if (!$request->has('balance')) {
                return $this->returnError('2011', 'fail');
            }
            Auth::user()->update([
                'balance' => (Auth::user()->balance + $request->balance)
            ]);
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function saveImage($photo, $folder)
    {
        $photo->store('/', $folder);
        $filename = $photo->hashName();
        // $path = 'images/' . $folder . '/' . $filename;
        return $filename;
    }

    public function CustomerService(Request $request)
    {
        try {
            $rules = [
                'email' => 'required|email',
                'name' => 'required',
                'title' => 'required',
                'body' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $file_name_attachment = null;
            if ($request->hasFile('attachment')) {
                $file_name_attachment  = $this->saveImage($request->attachment, 'customers_service');
            }
            CustomerService::create([
                'email' => $request->email,
                'name' => $request->name,
                'body' => $request->body,
                'title' => $request->title,
                'attachment' => $file_name_attachment
            ]);
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function storeTransaction(Request $request)
    {
        try {
            $type = (Auth::getDefaultDriver() == 'user-api' ? 0 : 1);
            Transaction::create([
                'type' => $type,
                'user_id' => Auth::user()->id,
                'subject' => $request->subject
            ]);
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function getTransactions(Request $request)
    {
        try {
            $type = (Auth::getDefaultDriver() == 'user-api' ? 0 : 1);
            $transactions = Transaction::where('type', '=', $type)
                ->where('user_id', Auth::user()->id)
                ->get();
            return $this->returnData('transactions', $transactions);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function makeComment(Request $request)
    {
        try {
            $type = (Auth::getDefaultDriver() == 'user-api' ? 0 : 1);
            if ($type == 0) {
                Comment::create([
                    'type' => $type,
                    'user_id' => Auth::user()->id,
                    'masafr_id' => $request->masafr_id,
                    'subject' => $request->subject,
                ]);
            } else if ($type == 1) {
                Comment::create([
                    'type' => $type,
                    'user_id' => $request->user_id,
                    'masafr_id' => Auth::user()->id,
                    'subject' => $request->subject,
                ]);
            }
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function getComments(Request $request)
    {
        try {
            if (!$request->has('type')) {
                return $this->returnError('202', 'fail');
            }
            if (!$request->has('pesron_id')) {
                return $this->returnError('202', 'fail');
            }
            if ($request->type == 0) {
                $user = User::find($request->pesron_id);
                if (!$user) {
                    return $this->returnError('202', 'fail');
                }
            } else if ($request->type == 1) {
                $masafr = Masafr::find($request->pesron_id);
                if (!$masafr) {
                    return $this->returnError('202', 'fail');
                }
            }
            if ($request->type == 0) {
                $comments = Comment::with('Masafr')->where('type', 0)
                    ->where('user_id', $request->pesron_id)
                    ->get();
                return $this->returnData('comments', $comments);
            }
            $comments = Comment::with('User')->where('type', 1)
                ->where('masafr_id', $request->pesron_id)
                ->get();
            return $this->returnData('comments', $comments);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function updateComment(Request $request)
    {
        try {
            $rules = [
                'id' => 'required|numeric',
                'subject' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $comment = Comment::find($request->id);
            if (!$comment) {
                return $this->returnError('202', 'fail');
            }
            $comment->update([
                'wait' => 1,
                'wait_subject' => $request->subject
            ]);
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function negotiation(Request $request)
    {
        try {
            DB::beginTransaction();
            if ($request->has('request_id') && $request->has('chat_id')) {
                $chat = Message::find($request->chat_id);
                if (!$chat) {
                    return $this->returnError('201', 'this chat room deos not exist');
                }
                RequestTrip::create([
                    'request_id' => $request->request_id,
                    'chat_id' => $request->chat_id,
                    'trip_id' => -1,
                    'offer_status' => '2'
                ]);

                //message to masafr
                // $type = (Auth::getDefaultDriver() == 'user-api' ? 0 : 1);
                $messageObj = 'العميل  طلب عرض سعر على طلبه ';
                MessageObject::create([
                    'message_id' => $request->chat_id,
                    'sender_type' => 0,
                    'subject' => $messageObj,
                    'private_msg' => '2',
                    'code' => 1000,
                    'user_seen' => 1
                ]);
                $msgSubjectOutput['code'] = 1000;
                $msgSubjectOutput['private_msg'] = 2;
                $msgSubjectOutput['subject'] = $messageObj;
                DB::commit();
                return $this->returnData('data', $msgSubjectOutput);
            } else if ($request->has('trip_id') && $request->has('chat_id')) {
                $requestTrip =  RequestTrip::where('chat_id', $request->chat_id)
                    ->where('trip_id', -1)
                    ->latest()
                    ->first();
                // return $requestTrip;
                $trip = Trips::find($request->trip_id);
                // return $trip;
                $request_service = RequestService::where('id', $requestTrip->request_id)
                    ->where('from_place', 'like', '%' . $trip->from_place . '%')
                    ->orWhere('to_place', 'like', '%' . $trip->to_place . '%')
                    ->get();
                if (count($request_service) == 0) {
                    return $this->returnError('204', 'هذة الرحلة لا تتوافق مع طلب العميل');
                }
                $requestTrip->update([
                    'trip_id' => $request->trip_id
                ]);
                //  1000 make change
            } else {
                return $this->returnError('203', 'fail');
            }
            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', $e->getMessage());
        }
    }


    // public function negotiation(Request $request)
    // {
    //     try {

    //         $rules = [
    //             'request_id' => 'required|numeric',
    //             'trip_id' => 'required|numeric'
    //         ];
    //         $validator = Validator::make($request->all(), $rules);
    //         if ($validator->fails()) {
    //             $code = $this->returnCodeAccordingToInput($validator);
    //             return $this->returnValidationError($code, $validator);
    //         }

    //         $negotiation = RequestTrip::where('request_id', $request->request_id)->where('trip_id', $request->trip_id)->get()->count();
    //         if ($negotiation == 0) {
    //             RequestTrip::create([
    //                 'request_id' => $request->request_id,
    //                 'trip_id' => $request->trip_id
    //             ]);
    //         }
    //         return $this->returnSuccessMessage('success');
    //     } catch (\Exception $e) {
    //         return $this->returnError('201', 'fail');
    //     }
    // }

    public function sendCancelNegotiation(Request $request)
    {
        try {
            DB::beginTransaction();
            $rules = [
                'message_id' => 'required|numeric'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            // $requestTrip =  RequestTrip::where('chat_id', $request->chat_id)
            //     // ->where('trip_id', '!=', -1)
            //     ->where('receipt_code', 0)
            //     ->where('delivery_code', 0)
            //     ->where('offer_status', '3')
            //     ->latest()
            //     ->first();

            // $requestTrip->update([
            //     'offer_status' => '5'
            // ]);

            $type = (Auth::getDefaultDriver() == 'user-api' ? 0 : 1);
            $messageObj_Fmsg = 'تم أرسال طلب الإلغاء ....  أنتظر  الرد';
            MessageObject::create([
                'message_id' => $request->message_id,
                'sender_type' => $type,
                'subject' => $messageObj_Fmsg,
                'private_msg' => ($type == 0 ? '2' : '1'),
                'code' => 1006
            ]);
            $messageObj_Smsg = 'طلب ' . ($type == 0 ? 'العميل' : 'المسافر') . ' الغاء الطلب  ';
            MessageObject::create([
                'message_id' => $request->message_id,
                'sender_type' => $type,
                'subject' => $messageObj_Smsg,
                'private_msg' => ($type == 0 ? '2' : '1'),
                'code' => 1007
            ]);

            // here
            $messages['user_msg'] = '';
            $messages['masafr_msg'] = '';
            $msgSubjectOutput['user_code'] = 0;
            $msgSubjectOutput['masafr_code'] = 0;
            if ($type == 0) {
                $messages['user_msg'] = $messageObj_Fmsg;
                $messages['masafr_msg'] = $messageObj_Smsg;
                $msgSubjectOutput['user_code'] = 1006;
                $msgSubjectOutput['masafr_code'] = 1007;
            } else if ($type == 1) {
                $messages['user_msg'] = $messageObj_Smsg;
                $messages['masafr_msg'] = $messageObj_Fmsg;
                $msgSubjectOutput['user_code'] = 1007;
                $msgSubjectOutput['masafr_code'] = 1006;
            }



            $msgSubjectOutput['private_msg'] = 3;
            $msgSubjectOutput['subject'] = $messages;
            DB::commit();
            return $this->returnData('data', $msgSubjectOutput);

            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', 'fail');
        }
    }

    public function cancelNegotiation(Request $request)
    {
        try {
            DB::beginTransaction();
            $rules = [
                'id' => 'required|numeric',
                'accept' => 'required|boolean'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $requestTrip = RequestTrip::find($request->id);
            if (!$requestTrip) {
                return $this->returnError('202', 'fail');
            }
            $type = (Auth::getDefaultDriver() == 'user-api' ? 0 : 1);
            if ($request->accept == 0) {

                $messageObj = 'تم رفض  طلب الإالغاء  تواصل مع الدعم الفني ';
                MessageObject::create([
                    'message_id' => $request->message_id,
                    'sender_type' => $type,
                    'subject' => $messageObj,
                    'private_msg' => ($type == 0 ? '2' : '1'),
                    'code' => 1008
                ]);
                $msgSubjectOutput['code'] = 1008;
                $msgSubjectOutput['private_msg'] = ($type == 0 ? 2 : 1);
                $msgSubjectOutput['subject'] = $messageObj;
                return $this->returnData('data', $msgSubjectOutput);
            } elseif ($request->accept == 1) {
                $requestTrip->update([
                    'current_status' => -1,
                    'offer_status' => '-1'
                ]);
                $type = (Auth::getDefaultDriver() == 'user-api' ? 0 : 1);
                $messageObj = 'تم الغاء الطلب ';
                MessageObject::create([
                    'message_id' => $request->message_id,
                    'sender_type' => 0,
                    'subject' => $messageObj,
                    'private_msg' => '1',
                    'code' => 1009
                ]);

                MessageObject::create([
                    'message_id' => $request->message_id,
                    'sender_type' => 1,
                    'subject' => $messageObj,
                    'private_msg' => '2',
                    'code' => 1010
                ]);

                $requestTrip = RequestTrip::where('chat_id', $request->message_id)
                    ->where('offer_status', '!=', '-1')
                    ->latest()
                    ->first();
                // here
                if ($requestTrip) {
                    // return 55;
                    $requestTrip->trip->masafr->update([
                        'balance' => $requestTrip->insurance_hold + $requestTrip->trip->masafr->balance,
                        // 'cancel_type' => ($type == 0 ? '1' : '2')
                    ]);
                }

                $msgSubjectOutput['user_code'] = 1009;
                $msgSubjectOutput['user_code'] = 1009;
                $msgSubjectOutput['private_msg'] = 3;
                $messages['user_msg'] = $messageObj;
                $messages['masafr_msg'] = $messageObj;
                $msgSubjectOutput['subject'] = $messages;
                return $this->returnData('data', $msgSubjectOutput);

                // $requestTrip->update([
                //     'offer_status' => '-1'
                // ]);


                // $dues = $requestTrip->insurance_hold + Auth::user()->balance;
                // // return $dues;
                // Auth::user()->update([
                //     'balance' => $dues
                // ]);

            }

            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', $e->getMessage());
        }
    }

    // not used######################
    public function updateNegotiation(Request $request)
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

            $requestTrip = RequestTrip::find($request->id);
            if (!$requestTrip) {
                return $this->returnError('202', 'fail');
            }

            $requestTrip->update([
                // 'selected' => $request->selected,
                'offer_status' => $request->offer_status,
                'payment_method' => $request->payment_method,
                'user_mark' => $request->user_mark,
                'user_feedback' => $request->user_feedback,
                'masafr_mark' => $request->masafr_mark,
                'masafr_feedback' => $request->masafr_feedback,
                'decision_maker' => $request->decision_maker,
                'reasons' => $request->reasons,
                'current_status' => $request->current_status
            ]);

            $type = (Auth::getDefaultDriver() == 'user-api' ? 0 : 1);
            MessageObject::create([
                'message_id' => $request->message_id,
                'sender_type' => $type,
                'subject' => $request->subject,
                'private_msg' => ($type == 0 ? '1' : '2'),
                'code' => 100
            ]);


            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', 'fail');
        }
    }

    public function makeComplain(Request $request)
    {
        try {
            DB::beginTransaction();
            $rules = [
                'subject' => 'required',
                'chat_id' => 'required'
                // 'related_trip' => 'required|numeric',
                // 'related_request_service' => 'required|numeric'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $requestTrip =  RequestTrip::where('chat_id', $request->chat_id)
                ->latest()
                ->first();
            $type = (Auth::getDefaultDriver() == 'user-api' ? 0 : 1);
            if ($type == 0) {
                $complain = Complain::where('user_id', Auth::user()->id)
                    ->where('masafr_id', $requestTrip->trip->masafr->id)
                    ->first();
                if (!$complain) {
                    $complainID = Complain::insertGetId([
                        'complainant' => 0,
                        'user_id' => Auth::user()->id,
                        'masafr_id' => $requestTrip->trip->masafr->id,
                        'related_trip' => $requestTrip->id,
                        'related_request_service' => $requestTrip->request->id,
                        'related_chat' => $request->chat_id,
                        'reason' => $request->reason ?? ''
                    ]);

                    $file_name_attach = null;
                    if ($request->hasFile('attach')) {
                        $file_name_attach  = $this->saveImage($request->attach, 'complains');
                    }
                    ComplainList::create([
                        'complain_id' => $complainID,
                        'type' => '0',
                        'subject' => $request->subject,
                        'attach' => $file_name_attach
                    ]);
                    // DB::commit();
                    // return $this->returnSuccessMessage('success');
                } else {
                    $file_name_attach = null;
                    if ($request->hasFile('attach')) {
                        $file_name_attach  = $this->saveImage($request->attach, 'complains');
                    }
                    ComplainList::create([
                        'complain_id' => $complain['id'],
                        'type' => '0',
                        'subject' => $request->subject,
                        'attach' => $file_name_attach
                    ]);
                }
                // DB::commit();
                // return $this->returnSuccessMessage('success');
            } else if ($type == 1) {
                $complain = Complain::where('user_id', $requestTrip->request->user->id)
                    ->where('masafr_id', Auth::user()->id)
                    ->first();
                if (!$complain) {
                    $complainID = Complain::insertGetId([
                        'complainant' => 1,
                        'user_id' =>  $requestTrip->request->user->id,
                        'masafr_id' => Auth::user()->id,
                        'related_trip' =>  $requestTrip->id,
                        'related_request_service' =>  $requestTrip->request->id,
                        'related_chat' => $request->chat_id,
                        'reason' => $request->reason
                    ]);

                    $file_name_attach = null;
                    if ($request->hasFile('attach')) {
                        $file_name_attach  = $this->saveImage($request->attach, 'complains');
                    }

                    ComplainList::create([
                        'complain_id' => $complainID,
                        'type' => '1',
                        'subject' => $request->subject,
                        'attach' => $file_name_attach
                    ]);
                    // DB::commit();
                    // return $this->returnSuccessMessage('success');
                } else {
                    $file_name_attach = null;
                    if ($request->hasFile('attach')) {
                        $file_name_attach  = $this->saveImage($request->attach, 'complains');
                    }
                    ComplainList::create([
                        'complain_id' => $complain['id'],
                        'type' => '1',
                        'subject' => $request->subject,
                        'attach' => $file_name_attach
                    ]);
                    // DB::commit();
                    // return $this->returnSuccessMessage('success');
                }
            }
            $type = (Auth::getDefaultDriver() == 'user-api' ? 0 : 1);
            $msgSubject = 'يوجد  شكوى نرجو الرد عليها ';
            MessageObject::create([
                'message_id' => $request->chat_id,
                'sender_type' => $type,
                'subject' => $msgSubject,
                'private_msg' => ($type == 0 ? '2' : '1'),
                'code' => 1012
            ]);
            $msgSubjectOutput['code'] = 1012;
            $msgSubjectOutput['private_msg'] = ($type == 0 ? 2 : 1);
            $msgSubjectOutput['subject'] = $msgSubject;
            DB::commit();
            return $this->returnData('data', $msgSubjectOutput);
            // return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', $e->getMessage());
        }
    }

    public function getComplainsRoom(Request $request)
    {
        try {

            $rules = [
                'user_id' => 'required|numeric',
                'masafr_id' => 'required|numeric',
                'related_chat' => "required"
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $user = User::find($request->user_id);
            if (!$user) {
                return $this->returnError('202', 'fail');
            }
            $masafr = Masafr::find($request->masafr_id);
            if (!$masafr) {
                return $this->returnError('202', 'fail');
            }
            $chat_id = Message::where('user_id', $request->user_id)->where('masafr_id', $request->masafr_id)->first();
            // $requestTrip =  RequestTrip::where('chat_id', $chat_id->id)
            //     ->where('offer_status', '!=', '5')
            //     ->latest()
            //     ->first();
            $complain = Complain::with('complainList')
                // ->with('chat')
                // ->with('chat.MessageObjects')
                ->where('user_id', $request->user_id)
                ->where('masafr_id', $request->masafr_id)
                ->where('related_chat', $request->related_chat)
                // ->where('related_trip', $requestTrip->trip_id)
                // ->where('related_request_service', $requestTrip->request_id)
                ->latest()
                ->first();
            $complain['main_subject'] = PronunciationStatement::where('chat_id', $chat_id->id)->latest()->first();
            return $this->returnData('data', $complain);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function getComplainsList(Request $request)
    {
        try {
            $complains = 0;
            $type = (Auth::getDefaultDriver() == 'user-api' ? 0 : 1);
            if ($type == 0) {
                $complains = Complain::with(['user' => function ($q) {
                    $q->select("id", "name", "photo");
                }])
                    ->with(['masafr' => function ($q) {
                        $q->select("id", "name", "photo");
                    }])
                    ->where('user_id', Auth::user()->id)->get();
            } else if ($type == 1) {
                $complains = Complain::with(['user' => function ($q) {
                    $q->select("id", "name", "photo");
                }])
                    ->with(['masafr' => function ($q) {
                        $q->select("id", "name", "photo");
                    }])
                    ->where('masafr_id', Auth::user()->id)->get();
            }
            return $this->returnData('data', $complains);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
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
                return $this->returnError('201', 'fail');
            }
            $complain->update([
                'status' => $request->status ?? $complain->status,
                'solved' => $request->solved ?? $complain->solved,
                'negative_point' => $request->negative_point ?? $complain->negative_point
            ]);
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function getPrivateMsgs($chatId)
    {
        try {
            $type = (Auth::getDefaultDriver() == 'user-api' ? '1' : '2');
            $chat = Message::find($chatId);
            if (!$chat) {
                return $this->returnError('201', 'this chat does not exist');
            }
            // user
            if ($type == '1') {
                if ($chat->user_id != Auth()->user()->id) {
                    return $this->returnError('201', 'this chat does not belongs to you');
                }
            } elseif ($type == '2') {
                if ($chat->masafr_id != Auth()->user()->id) {
                    return $this->returnError('201', 'this chat does not belongs to you');
                }
            }
            $private_messages = MessageObject::where('message_id', $chatId)
                ->where('private_msg', $type)
                ->get();
            return $this->returnData('data', $private_messages);
        } catch (\Exception $e) {
            return $this->returnError('201', $e->getMessage());
        }
    }

    public function getAllMainTripCategorires()
    {
        try {
            $mainCategories = TripCategorie::with('subsections')
                ->where('active', 1)
                ->get();
            return $this->returnData('data', $mainCategories);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function getAllMainRequestCategorires()
    {
        try {
            $mainCategories = RequestCategorie::with('subsections')
                ->where('active', 1)
                ->get();
            return $this->returnData('data', $mainCategories);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function getNotifications(Request $request)
    {
        try {
            $type = (Auth::getDefaultDriver() == 'user-api' ? 0 : 1);
            $notifications = AdminNotificationOrEmail::whereHas('persons', function ($q) {
                $q->where('person_id', Auth()->user()->id);
            })
                // ->with(['persons' => function ($q) {
                //     $q->where('person_id', Auth()->user()->id);
                // }])
                ->where('type', $type)
                ->where('send_by', 0)
                ->get();
            foreach ($notifications as $notification) {
                $notification->persons()->update([
                    'showed' => 1,
                    'seen_time' => new DateTime()
                ]);
            }

            return $this->returnData('data', $notifications);
        } catch (\Exception $e) {
            return $this->returnError('201', $e->getMessage());
        }
    }


    public function sendMessage(Request $request)
    {
        try {
            DB::beginTransaction();
            $type = (Auth::getDefaultDriver() == 'user-api' ? 0 : 1);
            // $rules = [
            //     'related_trip' => 'required|numeric|exists:trips,id',
            //     'related_request_service' => 'required|numeric|exists:request_services,id'
            // ];
            // $validator = Validator::make($request->all(), $rules);
            // if ($validator->fails()) {
            //     $code = $this->returnCodeAccordingToInput($validator);
            //     return $this->returnValidationError($code, $validator);
            // }

            $file_name_attach = null;
            if ($request->hasFile('attach')) {
                $file_name_attach  = $this->saveImage($request->attach, 'messages');
            }
            $messgs =  null;
            if ($type == 0) {
                $messgs =  Message::where('user_id', Auth::user()->id)->where('masafr_id', $request->masafr_id)->first();
            } else if ($type == 1) {
                $messgs =  Message::where('user_id', $request->user_id)->where('masafr_id', Auth::user()->id)->first();
            }
            $msgID = null;
            if (!$messgs) {
                if ($type == 0) {
                    $msgID = Message::insertGetId([
                        'user_id' => Auth::user()->id,
                        'masafr_id' => $request->masafr_id,
                        // 'related_trip' => $request->related_trip,
                        // 'related_request_service' => $request->related_request_service
                    ]);
                } else if ($type == 1) {
                    $msgID = Message::insertGetId([
                        'user_id' => $request->user_id,
                        'masafr_id' =>  Auth::user()->id,
                        // 'related_trip' => $request->related_trip,
                        // 'related_request_service' => $request->related_request_service
                    ]);
                }
            } else {
                $msgID = $messgs->id;
            }

            if ($type == 0) {
                MessageObject::create([
                    'message_id' => $msgID,
                    'sender_type' => 0,
                    'subject' => $request->subject,
                    'attach' => $request->attach,
                    'private_msg' => '0',
                    'code' => 0,
                    'user_seen' => 1,
                    'masafr_seen' => 0,
                ]);
            } else if ($type == 1) {
                MessageObject::create([
                    'message_id' => $msgID,
                    'sender_type' => 1,
                    'subject' => $request->subject,
                    'attach' => $request->attach,
                    'private_msg' => '0',
                    'code' => 0,
                    'user_seen' => 0,
                    'masafr_seen' => 1,
                ]);
            }

            // Message::create([
            //     'sender_type' => 1,
            //     'user_id' => $request->user_id,
            //     'masafr_id' => Auth::user()->id,
            //     'related_trip' => $request->related_trip,
            //     'related_request_service' => $request->related_request_service,
            //     'subject' => $request->subject,
            //     'attach' => $file_name_attach
            // ]);

            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', $e->getMessage());
        }
    }

    public function pronunciationStatement(Request $request)
    {
        try {
            $type = (Auth::getDefaultDriver() == 'user-api' ? 0 : 1);
            $rules = [
                'subject' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            PronunciationStatement::create([
                'sender_type' => $type,
                'subject' => $request->subject,
                'chat_id' => $request->chat_id
            ]);
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError('201', $e->getMessage());
        }
    }

    public function updatePersonalInfo(Request $request)
    {
        try {
            $file_name = null;
            $update_type = '0';
            if ($request->hasFile('id_photo')) {
                $file_name  = $this->saveImage($request->id_photo, 'users_id');
                $update_type = '1';
                if ($request->has('name')) {
                    $update_type = '2';
                }
            }

            $type = (Auth::getDefaultDriver() == 'user-api' ? 0 : 1);
            if ($request->has('phone') && $type == 0) {
                $user = User::where('phone', $request->phone)->get()->count();
                if ($user > 0) {
                    return $this->returnError('206', 'fail');
                }
            } else if ($request->has('phone') && $type == 1) {
                $masafr = Masafr::where('phone', $request->phone)->get()->count();
                if ($masafr > 0) {
                    return $this->returnError('206', 'fail');
                }
            }
            UpdateQeueu::create([
                'person_id' => Auth::user()->id,
                'type' =>  $type,
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'id_photo' => $file_name,
                'gender' => $request->gender,
                'national_id_number' => $request->national_id_number,
                'update_type' => $update_type,
                'nationality' => $request->nationality,
                'phone' => $request->phone,
            ]);
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError('200', $e->getMessage());
        }
    }


    public function getMessages(Request $request)
    {
        try {
            $type = (Auth::getDefaultDriver() == 'user-api' ? 0 : 1);
            if ($type == 0) {
                $messages = Message::with(['masafr' => function ($q) {
                    $q->select('id', 'name', 'photo', 'id');
                }])
                    ->with(['user' => function ($q) {
                        $q->select('id', 'name', 'photo');
                    }])
                    ->with(['MessageObjects' => function ($q) {
                        $q->where([
                            ['private_msg', '=', '0'],
                            ['code', '=', '0']
                        ])->orWhere([
                            ['private_msg', '=', '1'],
                            ['code', '!=', '0']
                        ]);
                    }])
                    ->where('user_id', Auth::user()->id)
                    ->where('masafr_id', $request->masafr_id)
                    ->get();
                MessageObject::where('message_id', $messages[0]['id'])
                    ->where('sender_type', 1)
                    ->update([
                        'user_seen' => 1
                    ]);
            } else if ($type == 1) {
                $messages = Message::with(['masafr' => function ($q) {
                    $q->select('id', 'name', 'photo', 'id');
                }])
                    ->with(['user' => function ($q) {
                        $q->select('id', 'name', 'photo');
                    }])
                    ->with(['MessageObjects' => function ($q) {
                        $q->where([
                            ['private_msg', '=', '0'],
                            ['code', '=', '0']
                        ])->orWhere([
                            ['private_msg', '=', '2'],
                            ['code', '!=', '0']
                        ]);
                    }])
                    ->where('user_id', $request->user_id)
                    ->where('masafr_id', Auth::user()->id)
                    ->get();
                MessageObject::where('message_id', $messages[0]['id'])
                    ->where('sender_type', 0)
                    ->update([
                        'masafr_seen' => 1
                    ]);
            }
            return $this->returnData('data', $messages);
        } catch (\Exception $e) {
            return $this->returnError('201', $e->getMessage());
        }
    }

    public function getChatRooms(Request $request)
    {
        try {
            $type = (Auth::getDefaultDriver() == 'user-api' ? 0 : 1);
            $chat_rooms = '';
            if ($type == 0) {
                // $chat_rooms = Message::where('user_id', Auth::user()->id)
                //     ->with(['masafr' => function ($q) {
                //         $q->select('id', 'name', 'photo');
                //     }])
                //     ->paginate($request->paginateCount);

                $chat_rooms = Message::where('user_id', Auth::user()->id)
                    // ->whereHas('masafr.trips', function ($q) {
                    //     $q->where('end_date', '>', Carbon\Carbon::now());
                    // })
                    ->whereHas('requestService', function ($q) {
                        $q->where('max_day', '>', Carbon\Carbon::now());
                    })
                    ->orWhereHas('trip', function ($q) {
                        $q->where('end_date', '>', Carbon\Carbon::now());
                    })
                    // ->orWhereHas('')
                    ->with(['masafr' => function ($q) {
                        $q->select('id', 'name', 'photo');
                    }])
                    // ->with(['trip.tripCategory' => function ($q) {
                    //     $q->select('id', 'categorie_name');
                    // }])

                    ->with(['trip' => function ($q) {
                        $q->select('id', 'type_of_trips')->with(['tripCategory' => function ($q) {
                            $q->select('id', 'categorie_name');
                        }]);
                    }])
                    ->with(['requestService' => function ($q) {
                        $q->select('id', 'type_of_trips')->with(['service' => function ($q) {
                            $q->select('id', 'categorie_name');
                        }]);
                    }])
                    ->paginate($request->paginateCount);
            } else if ($type == 1) {
                // $chat_rooms = Message::where('masafr_id', Auth::user()->id)
                //     ->with(['user' => function ($q) {
                //         $q->select('id', 'name', 'photo');
                //     }])
                //     ->paginate($request->paginateCount);

                $chat_rooms = Message::where('masafr_id', Auth::user()->id)
                    // ->whereHas('masafr.trips', function ($q) {
                    //     $q->where('end_date', '>', Carbon\Carbon::now());
                    // })
                    ->whereHas('trip', function ($q) {
                        $q->where('end_date', '>', Carbon\Carbon::now());
                    })
                    ->orWhereHas('requestService', function ($q) {
                        $q->where('max_day', '>', Carbon\Carbon::now());
                    })
                    ->with(['user' => function ($q) {
                        $q->select('id', 'name', 'photo');
                    }])
                    ->with(['trip' => function ($q) {
                        $q->select('id', 'type_of_trips')->with(['tripCategory' => function ($q) {
                            $q->select('id', 'categorie_name');
                        }]);
                    }])
                    ->with(['requestService' => function ($q) {
                        $q->select('id', 'type_of_trips')->with(['service' => function ($q) {
                            $q->select('id', 'categorie_name');
                        }]);
                    }])
                    ->paginate($request->paginateCount);
            }
            return $this->returnData('data', $chat_rooms);
        } catch (\Exception $e) {
            return $this->returnError('201', $e->getMessage());
        }
    }

    public function createChatRooms(Request $request)
    {
        try {
            if (!$request->user_id || !$request->masafr_id) {
                return $this->returnError('202', 'fail');
            }
            if ($request->has('related_trip')) {
                $chat_room_check = Message::where('user_id', $request->user_id)
                    ->where('masafr_id', $request->masafr_id)
                    ->where('related_trip', $request->related_trip)
                    ->first();
                if ($chat_room_check) {
                    $data['id'] =  $chat_room_check['id'];
                    // $data['flag'] = false;
                    // $requestTrip = RequestTrip::where('chat_id', $chat_room_check['id'])
                    //     ->where('offer_status', '!=', '5')->get();
                    // if (count($requestTrip) > 0) {
                    //     $data['flag'] = true;
                    // } else {
                    //     $data['flag'] = false;
                    // }

                    return $this->returnData('data', $chat_room_check['id']);
                }
            } else if ($request->has('related_request_service')) {
                $chat_room_check = Message::where('user_id', $request->user_id)
                    ->where('masafr_id', $request->masafr_id)
                    ->where('related_request_service', $request->related_request_service)
                    ->first();
                if ($chat_room_check) {
                    // $data['id'] =  $chat_room_check['id'];
                    // $data['flag'] = false;
                    // $requestTrip = RequestTrip::where('chat_id', $chat_room_check['id'])
                    //     ->where('offer_status', '!=', '5')->get();
                    // if (count($requestTrip) > 0) {
                    //     $data['flag'] = true;
                    // } else {
                    //     $data['flag'] = false;
                    // }

                    return $this->returnData('data', $chat_room_check['id']);
                }
            }

            $new_chat_room = Message::insertGetId([
                'user_id' => $request->user_id,
                'masafr_id' => $request->masafr_id,
                'related_trip' => $request->related_trip ?? -1,
                'related_request_service' => $request->related_request_service ?? -1
            ]);
            return $this->returnData('data', $new_chat_room);
        } catch (\Exception $e) {
            return $this->returnError('201', $e->getMessage());
        }
    }

    public function deleteNotification(Request $request)
    {
        try {
            DB::beginTransaction();
            $rules = [
                'notifications' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            foreach ($request->notifications as $notification) {
                NotificationOrMailPerson::where('id', $notification)->delete();
            }

            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', $e->getMessage());
        }
    }


    public function getAdvertisings()
    {
        try {
            $type = (Auth::getDefaultDriver() == 'user-api' ? 0 : 1);
            if ($type == 0) {
                $advertisings = Advertising::with(['days', 'places'])
                    ->where('end_date', '>', Carbon\Carbon::now())
                    ->where('start_date',  '<', Carbon\Carbon::now())
                    ->where('user_appear', 1)
                    ->get();
            } else if ($type == 1) {
                $advertisings = Advertising::with(['days', 'places'])
                    ->where('end_date', '>', Carbon\Carbon::now())
                    ->where('start_date',  '<', Carbon\Carbon::now())
                    ->where('masafr_appear', 1)
                    ->get();
            } else {
                $advertisings = Advertising::with(['days', 'places'])
                    ->where('end_date', '>', Carbon\Carbon::now())
                    ->where('start_date',  '<', Carbon\Carbon::now())
                    ->get();
            }
            return $this->returnData('data', $advertisings);
        } catch (\Exception $e) {
            return $this->returnError('201', $e->getMessage());
        }
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout(Request $request)
    {
        try {
            $token = $request->header('authToken');
            if ($token) {
                JWTAuth::setToken($token)->invalidate();
                return $this->returnSuccessMessage('success');
            } else {
                return $this->returnError('200', 'fail');
            }
        } catch (\Exception $e) {
            return $this->returnError('200', 'fail');
        }
    }

    public function returnError($errNum, $msg)
    {
        return response()->json([
            'status' => false,
            'errNum' => $errNum,
            'msg' => $msg
        ]);
    }


    public function returnSuccessMessage($msg = "", $errNum = "S000")
    {
        return [
            'status' => true,
            'errNum' => $errNum,
            'msg' => $msg
        ];
    }

    public function returnData($key, $value, $msg = "")
    {
        return response()->json([
            'status' => true,
            'errNum' => "S000",
            'msg' => $msg,
            $key => $value
        ]);
    }


    //////////////////
    public function returnValidationError($code, $validator)
    {
        return $this->returnError($code, $validator->errors()->first());
    }


    public function returnCodeAccordingToInput($validator)
    {
        $inputs = array_keys($validator->errors()->toArray());
        $code = $this->getErrorCode($inputs[0]);
        return $code;
    }

    public function getErrorCode($input)
    {
        if ($input == "name")
            return 'E0011';

        else if ($input == "password")
            return 'E002';

        else if ($input == "mobile")
            return 'E003';

        else if ($input == "id_number")
            return 'E004';

        else if ($input == "birth_date")
            return 'E005';

        else if ($input == "agreement")
            return 'E006';

        else if ($input == "email")
            return 'E007';

        else if ($input == "city_id")
            return 'E008';

        else if ($input == "insurance_company_id")
            return 'E009';

        else if ($input == "activation_code")
            return 'E010';

        else if ($input == "longitude")
            return 'E011';

        else if ($input == "latitude")
            return 'E012';

        else if ($input == "id")
            return 'E013';

        else if ($input == "promocode")
            return 'E014';

        else if ($input == "doctor_id")
            return 'E015';

        else if ($input == "payment_method" || $input == "payment_method_id")
            return 'E016';

        else if ($input == "day_date")
            return 'E017';

        else if ($input == "specification_id")
            return 'E018';

        else if ($input == "importance")
            return 'E019';

        else if ($input == "type")
            return 'E020';

        else if ($input == "message")
            return 'E021';

        else if ($input == "reservation_no")
            return 'E022';

        else if ($input == "reason")
            return 'E023';

        else if ($input == "branch_no")
            return 'E024';

        else if ($input == "name_en")
            return 'E025';

        else if ($input == "name_ar")
            return 'E026';

        else if ($input == "gender")
            return 'E027';

        else if ($input == "nickname_en")
            return 'E028';

        else if ($input == "nickname_ar")
            return 'E029';

        else if ($input == "rate")
            return 'E030';

        else if ($input == "price")
            return 'E031';

        else if ($input == "information_en")
            return 'E032';

        else if ($input == "information_ar")
            return 'E033';

        else if ($input == "street")
            return 'E034';

        else if ($input == "branch_id")
            return 'E035';

        else if ($input == "insurance_companies")
            return 'E036';

        else if ($input == "photo")
            return 'E037';

        else if ($input == "logo")
            return 'E038';

        else if ($input == "working_days")
            return 'E039';

        else if ($input == "insurance_companies")
            return 'E040';

        else if ($input == "reservation_period")
            return 'E041';

        else if ($input == "nationality_id")
            return 'E042';

        else if ($input == "commercial_no")
            return 'E043';

        else if ($input == "nickname_id")
            return 'E044';

        else if ($input == "reservation_id")
            return 'E045';

        else if ($input == "attachments")
            return 'E046';

        else if ($input == "summary")
            return 'E047';

        else if ($input == "user_id")
            return 'E048';

        else if ($input == "mobile_id")
            return 'E049';

        else if ($input == "paid")
            return 'E050';

        else if ($input == "use_insurance")
            return 'E051';

        else if ($input == "doctor_rate")
            return 'E052';

        else if ($input == "provider_rate")
            return 'E053';

        else if ($input == "message_id")
            return 'E054';

        else if ($input == "hide")
            return 'E055';

        else if ($input == "checkoutId")
            return 'E056';

        else
            return "";
    }
}
