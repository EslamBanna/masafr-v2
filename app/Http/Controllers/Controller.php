<?php

namespace App\Http\Controllers;

use App\Models\Masafr\Trips;
use App\Models\User\RequestService;
use App\Traits\GeneralTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Nexmo\Laravel\Facade\Nexmo;
use Carbon;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, GeneralTrait;

    public function getAllTrips(Request $request)
    {
        try {
            DB::beginTransaction();
            $trips = Trips::where('active', 1)
                ->with('masafr')
                ->with('ways')
                ->with('days')
                ->where('end_date', '>', Carbon\Carbon::now())
                ->orWhere('end_date', null)
                ->paginate($request->paginateCount);
            // $ended_trips = Trips::where('end_date', '<', Carbon\Carbon::now())
            //     ->get();
            // foreach ($ended_trips as $ended_trip) {
            //     $trip = Trips::find($ended_trip['id']);
            //     if (!$trip) {
            //         return $this->returnError('203', 'fail');
            //     }
            //     // delete complains 
            //     foreach ($trip->complains as $complain) {
            //         foreach ($complain->complainList as $complainListM) {
            //             $complainListM->delete();
            //         }
            //         $complain->delete();
            //     }
            //     $trip->messageRooms()->delete();
            //     $trip->delete();
            // }
            DB::commit();
            return $this->returnData('data', $trips);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', 'fail');
        }
    }

    public function getAllRequestServices(Request $request)
    {
        try {
            DB::beginTransaction();
            $trips = null;
            if ($request->has('saudi') && $request->saudi == 1) {
                $trips = RequestService::whereHas('user', function ($q) {
                    $q->where('nationality', 'like', '%' . 'سعودي' . '%');
                })
                    ->with('user')
                    ->with('service')
                    ->where('max_day', '>', Carbon\Carbon::now())
                    ->withCount('requestTrip as negotiation')
                    ->paginate($request->paginateCount);
            } else {
                $trips = RequestService::with('user')
                    ->with('service')
                    ->where('max_day', '>', Carbon\Carbon::now())
                    ->withCount('requestTrip as negotiation')
                    ->paginate($request->paginateCount);
            }
            // $ended_request_services = RequestService::where('max_day', '<', Carbon\Carbon::now())
            //     ->get();
            // foreach ($ended_request_services as $ended_request_service) {
            //     $request_service = RequestService::find($ended_request_service['id']);
            //     if (!$request_service) {
            //         return $this->returnError('203', 'fail');
            //     }
            //     // delete complains 
            //     foreach ($request_service->complains as $complain) {
            //         foreach ($complain->complainList as $complainListM) {
            //             $complainListM->delete();
            //         }
            //         $complain->delete();
            //     }
            //     $request_service->messages()->delete();
            //     $request_service->delete();
            // }
            DB::commit();
            return $this->returnData('data', $trips);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnError('201', 'fail');
        }
    }

    public function sendSms()
    {
        try {
            Nexmo::message()->send([
                'to' => '+201210732005',
                'from' => '+201287006309',
                'text' => 'my first message222'
            ]);
            echo 'sended';
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }
}
