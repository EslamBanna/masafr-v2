<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use App\Service\MyFathoorahService;
use App\Traits\GeneralTrait;
use Auth;
class MyFatoorahControlller extends Controller
{
    use GeneralTrait;
    private $fathoorahService;
    public function __construct(MyFathoorahService $myFathoorahService)
    {
        $this->fathoorahService = $myFathoorahService;
    }
    public function pay(Request $request)
    {
        // return Auth::user();
        if(!$request->has('invoice_value')){
            return $this->returnError('300','fail');
        }
        // $actual_link = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        $paymentData = [
            'NotificationOption' => 'ALL', //'SMS', 'EML', or 'ALL'
            'InvoiceValue'       => $request->invoice_value,
            'CustomerName'       => Auth::user()->name,
            'DisplayCurrencyIso' => 'KWD',
            // 'MobileCountryCode'  => '+20',
            'CustomerMobile'     => Auth::user()->phone,
            'CustomerEmail'      => Auth::user()->email,
            'CallBackUrl'        => 'http://127.0.0.1/api/success-pay',
            'ErrorUrl'           => 'http://127.0.0.1/api/error-pay',
            //'Language'           => 'en', //or 'ar'
            //'CustomerReference'  => 'orderId',
            //'CustomerCivilId'    => 'CivilId',
            //'UserDefinedField'   => 'This could be string, number, or array',
            //'ExpiryDate'         => '', //The Invoice expires after 3 days by default. Use 'Y-m-d\TH:i:s' format in the 'Asia/Kuwait' time zone.
            //'SourceInfo'         => 'Pure PHP', //For example: (Laravel/Yii API Ver2.0 integration)
            //'CustomerAddress'    => $customerAddress,
            //'InvoiceItems'       => $invoiceItems,
        ];
        $pay_data =  $this->fathoorahService->sendPayment($paymentData);
        $type = (Auth::getDefaultDriver() == 'user-api' ? 0 : 1);
        PaymentTransaction::create([
            'invoice_id' => $pay_data['Data']['InvoiceId'],
            'user_id' => 25,
            'type' => $type
        ]);
        return $pay_data;
    }


    public function successPay(Request $request)
    {
        $data = [];
        $data['key'] = $request->paymentId;
        $data['keyType'] = 'paymentId';
        $check_status =  $this->fathoorahService->getPaymentStatus($data);
        if (!$check_status) {
            return $this->returnError('206', 'fail');
        }
        return $this->returnSuccessMessage('success');
    }

    public function errorPay()
    {
        return $this->returnError('205', 'fail');
    }
}
