<?php

namespace App\Traits;

use App\Appointment;
use App\AppointmentPayment;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

trait EasypaisaPaymentGateway {


    use \App\Traits\WebServicesDoc;

    private $sEpMaUrl;
    private $sEpOtcUrl;
    private $sEpStoreID;
    private $sEpCredentials;
    private $sEpTypeMA;
    private $sEpTypeOTC;
    private $sEpEWPAccount;
    private $sEpStatusInquiryUrl;

    public function __construct__(){
        // EasyPaisa set default variables
    	$this->sEpMaUrl                 = config('paymentgateway.EP_MA_URL');
        $this->sEpOtcUrl                = config('paymentgateway.EP_OTC_URL');
        $this->sEpStoreID               = config('paymentgateway.EP_STORE_ID');
        $this->sEpCredentials           = config('paymentgateway.EP_CREDENTIALS');
        $this->sEpTypeMA                = config('paymentgateway.EP_MA');
        $this->sEpTypeOTC               = config('paymentgateway.EP_OTC');
        $this->sEpEWPAccount            = config('paymentgateway.EP_EWP_ACCOUNT');
        $this->sEpStatusInquiryUrl      = config('paymentgateway.EP_STATUS_INQUIRY_URL');
    }
	

    public function epMAPayment($request){

	   $this->__construct__();

        $aData =array(); 
        $aData['storeId']           = $this->sEpStoreID;
        $aData['transactionType']   = $this->sEpTypeMA;

        $aData['orderId']           = $request['orderId'];
        $aData['transactionAmount'] = $request['transactionAmount'];
        $aData['mobileAccountNo']   = $request['mobileAccountNo'];   
        $aData['emailAddress']      = $request['emailAddress'];  

        $aData['optional2']         = $request['optional2'];       

        LOG::info(print_r($aData, true));
             
        $aData = json_encode($aData);
        //dd($aData);

        $response = $this->EpPaymentCurl($aData,$this->sEpMaUrl);     
        //dd($response);
        LOG::info(print_r($response, true));

        if($response['responseCode'] == "000"){
            return array('STATUS' => 'OK', 'RETURN' => $response);
        }

        return array('STATUS' => 'BAD', 'RETURN' => $response);
    }

    public function epOTCPayment($request){
        
        $this->__construct__();    
        $aData = array(); 
        $aData['storeId']           = $this->sEpStoreID;
        $aData['transactionType']   = $this->sEpTypeOTC;

        $aData['orderId']           = $request['orderId'];
        $aData['transactionAmount'] = $request['transactionAmount'];
        $aData['msisdn']            = $request['msisdn'];   
        $aData['emailAddress']      = $request['emailAddress'];  
        $aData['tokenExpiry']       = $request['ExpiryDateTime'];

        $aData['optional2']         = $request['optional2'];

        $aData = json_encode($aData);
    
        //dd($aData);
        $response = $this->EpPaymentCurl($aData,$this->sEpOtcUrl);     

        LOG::info(print_r($response, true));
        //dump($response);
        
        if($response['responseCode'] == "000"){
            return array('STATUS' => 'OK', 'RETURN' => $response);
        }

        return array('STATUS' => 'BAD', 'RETURN' => $response);
    }


    public function epStatusInquiry($request){

        $this->__construct__();

        $aData =array(); 
        $aData['storeId']       = $this->sEpStoreID;
        $aData['accountNum']    = $this->sEpEWPAccount;
        $aData['orderId']       = $request['orderId'];

        $aData = json_encode($aData);
    
        // dd($aData);
        $response = $this->EpPaymentCurl($aData,$this->sEpStatusInquiryUrl);     

        LOG::info(print_r($response, true));

        return $response;
    }
    
    

    private function EpPaymentCurl($data,$url){
	   //dump($url);

        LOG::info(print_r($data, true));
        
        $curl_handle=curl_init();
        curl_setopt($curl_handle,CURLOPT_URL,$url);
        curl_setopt($curl_handle,CURLOPT_CUSTOMREQUEST,"POST");
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array(
            'Content-Type:application/json',
            'Credentials: '.$this->sEpCredentials,
        ));
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS,$data);
        curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,999999);
        
        curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);

        /////curl_setopt($curl_handle,CURLOPT_TIMEOUT,999999);

        
        $buffer = curl_exec($curl_handle);

        $httpCode = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
        //dump($buffer); 
	    //dump(curl_error($curl_handle));

        $err = curl_error($curl_handle);
        LOG::info("CURL Error  ".$err);

        curl_close($curl_handle);


        $response = json_decode($buffer, true);
        return $response;
    }

}
