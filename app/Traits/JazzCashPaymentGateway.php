<?php

namespace App\Traits;

use App\Appointment;
use App\AppointmentPayment;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

trait JazzCashPaymentGateway {

	private $sJcPostUrl;
    private $sJcPostUrlV2;
    private $sJcPostUrlCard;
    private $sJcStatusInquiryUrl;
    private $sJcMerchantID;
    private $sJcPassword;
    private $sJcReturnUrl;
    private $sJcVersion;
    private $sJcLanguage;
    private $sJcCurrency;
    private $sJcHashkey;


    public function __construct(){
        // Jazzcash set default variables
        $this->sJcPostUrl           = config('paymentgateway.JC_POST_URL');
        $this->sJcPostUrlV2         = config('paymentgateway.JC_POST_URL_V2');
        $this->sJcStatusInquiryUrl  = config('paymentgateway.JC_STATUS_INQUIRY_URL');
        $this->sJcMerchantID        = config('paymentgateway.JC_MERCHANTID');
        $this->sJcPassword          = config('paymentgateway.JC_PASSWORD');
        $this->sJcReturnUrl         = config('paymentgateway.JC_RETURNURL');
        $this->sJcVersion           = config('paymentgateway.JC_VERSION');
        $this->sJcLanguage          = config('paymentgateway.JC_LANGUAGE');
        $this->sJcCurrency          = config('paymentgateway.JC_CURRENCY');
        $this->sJcHashkey           = config('paymentgateway.JC_HASHKEY');
        $this->sJcPostUrlCard       = config('paymentgateway.JC_POST_URL_CARD');

        
    }

    /**
     * Amount will be returned in the ending 00, as JC by dault does not take decimal (dot) in value
     * instead it adds 00 at ending and treat it as decimal value
     */
    public function amountToDecimal($iAmount){
        return $iAmount*100; 
    }

    public function jcMwallet($request) 
    {

        $aData = array();		
        $aData['pp_Password']               = $this->sJcPassword;
        $aData['pp_MerchantID']             = $this->sJcMerchantID;
        $aData['pp_Language']               = $this->sJcLanguage;
        $aData['pp_TxnCurrency']            = $this->sJcCurrency;

        $aData['pp_Amount']                 = $this->amountToDecimal($request['pp_Amount']);
        $aData['pp_BillReference']          = $request['pp_BillReference'];
        $aData['pp_BankID']                 = isset($request['pp_BankID'])? $request['pp_BankID']:"" ;
        $aData['pp_Description']            = $request['pp_Description'];
        $aData['pp_ProductID']              = isset($request['pp_ProductID'])?$request['pp_ProductID']:"";
        $aData['pp_SubMerchantID']          = isset($request['pp_SubMerchantID'])?$request['pp_ProductID']:"";
        $aData['pp_TxnRefNo']               = $request['pp_TxnRefNo'];
        $aData['pp_TxnDateTime']            = date('YmdHis'); 
        $aData['pp_TxnExpiryDateTime']      = $request['pp_TxnExpiryDateTime']; 
        $aData['ppmpf_1']                   = isset($request['ppmpf_1'])?$request['ppmpf_1']:"";
        $aData['ppmpf_2']                   = isset($request['ppmpf_2'])?$request['ppmpf_2']:"";
        $aData['ppmpf_3']                   = isset($request['ppmpf_3'])?$request['ppmpf_3']:"";
        $aData['ppmpf_4']                   = isset($request['ppmpf_4'])?$request['ppmpf_4']:""; 
        $aData['ppmpf_5']                   = isset($request['ppmpf_5'])?$request['ppmpf_5']:""; 
        $aData['pp_MobileNumber']           = isset($request['pp_MobileNumber'])?$request['pp_MobileNumber']:""; 
        $aData['pp_CNIC']                   = isset($request['pp_CNIC'])?$request['pp_CNIC']:""; 
   
        $secureHash                         = $this->jcSecureHash($aData);
        $aData['pp_SecureHash']             = $secureHash; 
       
        LOG::info("JC MA pp_SecureHash Lister------------");
    	LOG::info(print_r($aData, true));
        //dump("Request Exec"); 
	    $response = $this->paymentCurl($this->sJcPostUrlV2,'POST',json_encode($aData));
	     
	    
        LOG::info(print_r($response, true));

	    $bIsHashMatched = $this->computeAndVerifyReturnHash($response);

        if($bIsHashMatched && $response['pp_ResponseCode'] == "000"){
           return array('STATUS' => 'OK', 'RETURN' => $response);
        }

        return array('STATUS' => 'BAD', 'RETURN' => $response);

    }





    public function jcOtc($request){      
       
        $aData = array();     

        $aData['pp_Password']               = $this->sJcPassword;
        $aData['pp_MerchantID']             = $this->sJcMerchantID;
        $aData['pp_Language']               = $this->sJcLanguage;
        $aData['pp_TxnCurrency']            = $this->sJcCurrency;
        $aData['pp_ReturnURL']              = $this->sJcReturnUrl;
        $aData['pp_Version']                = $this->sJcVersion;

        $aData['pp_Amount']                 = $this->amountToDecimal($request['pp_Amount']);
        
        //if(isset($request['pp_DiscountedAmount']))
        //$aData['pp_DiscountedAmount']       = $request['pp_DiscountedAmount'];
        
        $aData['pp_BillReference']          = $request['pp_BillReference'];
        $aData['pp_BankID']                 = isset($request['pp_BankID'])? $request['pp_BankID']:"" ;
        $aData['pp_Description']            = $request['pp_Description'];
        $aData['pp_ProductID']              = isset($request['pp_ProductID'])?$request['pp_ProductID']:"";
        $aData['pp_SubMerchantID']          = isset($request['pp_SubMerchantID'])?$request['pp_SubMerchantID']:"";
        $aData['pp_TxnType']                = $request['pp_TxnType'];
        $aData['pp_TxnRefNo']               = $request['pp_TxnRefNo'];
        $aData['pp_TxnDateTime']            = date('YmdHis'); 
        $aData['pp_TxnExpiryDateTime']      = $request['pp_TxnExpiryDateTime'];
        ////$aData['pp_DiscountBank']           = 'BNK01'; //pp_BankID

        $aData['ppmpf_1']                   = isset($request['ppmpf_1'])?$request['ppmpf_1']:"";
        $aData['ppmpf_2']                   = isset($request['ppmpf_2'])?$request['ppmpf_2']:"";
        $aData['ppmpf_3']                   = isset($request['ppmpf_3'])?$request['ppmpf_3']:"";
        $aData['ppmpf_4']                   = isset($request['ppmpf_4'])?$request['ppmpf_4']:""; 
        $aData['ppmpf_5']                   = isset($request['ppmpf_5'])?$request['ppmpf_5']:""; 

        $secureHash =$this->jcSecureHash($aData);
        $aData['pp_SecureHash']= $secureHash; 

        //dump($aData);

        $response = $this->paymentCurl($this->sJcPostUrl,'POST',json_encode($aData));
        
        //dd($response);

        LOG::info(print_r($response, true));

        $bIsHashMatched = $this->computeAndVerifyReturnHash($response);

        if($bIsHashMatched && $response['pp_ResponseCode'] == "124"){
           return array('STATUS' => 'OK', 'RETURN' => $response);
        }

        return array('STATUS' => 'BAD', 'RETURN' => $response);
     
    }  



    public function jc_card($request)
    {

        $aData = array(); 
        $postURL                        = $this->sJcPostUrlCard;
        $aData['pp_Password']           = $this->sJcPassword;
        $aData['pp_MerchantID']         = $this->sJcMerchantID;
        $aData['pp_Language']           = $this->sJcLanguage;
        $aData['pp_TxnCurrency']        = $this->sJcCurrency;
        $aData['pp_ReturnURL']          = $this->sJcReturnUrl;
        $aData['pp_Version']            = $this->sJcVersion;
        $aData['pp_TxnType']            = 'MPAY';
        

        $aData['pp_Amount']             = $this->amountToDecimal($request['pp_Amount']);
        $aData['pp_BillReference']      = $request['pp_BillReference'];
        $aData['pp_BankID']             = "TBANK" ;
        $aData['pp_Description']        = $request['pp_Description'];
        $aData['pp_ProductID']          = "RETL";
        $aData['pp_SubMerchantID']      = isset($request['pp_SubMerchantID']) ? $request['pp_ProductID']:"";
        $aData['pp_TxnRefNo']           = $request['pp_TxnRefNo'];
        $aData['pp_TxnDateTime']        = date('YmdHis'); 
        $aData['pp_TxnExpiryDateTime']  = $request['pp_TxnExpiryDateTime']; 
        $aData['ppmpf_1']               = isset($request['ppmpf_1'])?$request['ppmpf_1']:"";
        $aData['ppmpf_2']               = isset($request['ppmpf_2'])?$request['ppmpf_2']:"";
        $aData['ppmpf_3']               = isset($request['ppmpf_3'])?$request['ppmpf_3']:"";
        $aData['ppmpf_4']               = isset($request['ppmpf_4'])?$request['ppmpf_4']:""; 
        $aData['ppmpf_5']               = isset($request['ppmpf_5'])?$request['ppmpf_5']:""; 
       
        $secureHash                     = $this->jcSecureHash($aData);
        $aData['pp_SecureHash']         = $secureHash; 

        return array('attributes' => $aData, 'url' => $postURL);

        //return view("jazzcash",compact('aData','postURL'));
    }



    private function jcSecureHash($aHash){
       
       if(!empty($aHash)){
            ksort($aHash);
            $sConcateVariables=$this->sJcHashkey; 
            foreach($aHash as $key => $val){
                if(!empty($val))
                    $sConcateVariables .="&".$val;
                
            }
            $sSecureHash = hash_hmac('sha256', $sConcateVariables, $this->sJcHashkey); 
            $sSecureHash =strtoupper($sSecureHash);
            return $sSecureHash;
        }
        
        return false;
    }


    public function jcStatusInquiry($request){ 

        $aData = array(); 

        $aData['pp_Password']           = $this->sJcPassword;
        $aData['pp_MerchantID']         = $this->sJcMerchantID;
        $aData['pp_TxnRefNo']           = $request['pp_TxnRefNo'];

        $secureHash                     = $this->jcSecureHash($aData);
        $aData['pp_SecureHash']         = $secureHash; 
        //dump($aData);
        $aResponse = $this->paymentCurl($this->sJcStatusInquiryUrl,'POST',json_encode($aData));
        //dd($response);   
        return array('RETURN' => $aResponse);
    }  

    public function jcPostURL(Request $request){		
    	$aData = $request->all();
    }

    public function jc_listner(Request $request){		
    	$aData=$request->all();
    	dump($aData);
    	LOG::info("JC Lister------------");
    	LOG::info(print_r($request->all(), true));
    }


    private function paymentCurl($url, $method='GET',$aPost){
    
        LOG::info(print_r($aPost, true));

        try {

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_TIMEOUT => 99999,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                ),
                CURLOPT_POSTFIELDS => $aPost,
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                return ['status' => false, 'code' => 422, 'message' => $err];
            } 
            return json_decode($response, true);

        }catch(\Exception $oExp){
            dump("CURL EXception");
            dd($oExp);
            return $oExp->getMessage();
        }



    }


    /**
     * Compute and verify response of vendor side input
     */
    private function computeAndVerifyReturnHash($aInput)
    {	

    	//dump("HASH ** ".$aInput['pp_SecureHash']);

    	$aFieldsForHashCompute = array(
    		'pp_Amount', 'pp_AuthCode', 'pp_BillReference', 'pp_Language', 'pp_MerchantID', 'pp_ResponseCode', 'pp_ResponseMessage', 
            'pp_RetreivalReferenceNo', 'pp_SubMerchantID', 'pp_TxnCurrency', 'pp_TxnDateTime', 'pp_TxnRefNo', 'pp_MobileNumber', 
            'pp_CNIC', 'pp_DiscountedAmount', 'ppmpf_1', 'ppmpf_2', 'ppmpf_3', 'ppmpf_4', 'ppmpf_5' , 'pp_DiscountBank', 'pp_BankID'
    	);


        ksort($aInput);
        $sConcateVariables = $this->sJcHashkey; 
        foreach($aInput as $key => $val){
            //if(in_array($key, $aFieldsForHashCompute) && !empty($val)){
            if($key != 'pp_SecureHash' &&  !empty($val)){
                $sConcateVariables .= "&".$val;
            }
        }
        $sSecureHash = hash_hmac('sha256', $sConcateVariables, $this->sJcHashkey); 
        $sSecureHash = strtoupper($sSecureHash);
        //dd($sSecureHash);

        if(!isset($aInput['pp_SecureHash']))
            return false;

        if($sSecureHash == $aInput['pp_SecureHash'])
            return true;
        return false;

    }
}