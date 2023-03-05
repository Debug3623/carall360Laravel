<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
//use App\Helpers\ClinicAll;

class PaymentGatewayController extends Controller
{
        //
        use \App\Traits\WebServicesDoc;

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
        private $sEpMaUrl;
        private $sEpOtcUrl;
        private $sEpStoreID;
        private $sEpCredentials;
        private $sEpTypeMA;
        private $sEpTypeOTC;
        private $sEpEWPAccount;
        private $sEpStatusInquiryUrl;

    public function __construct(){
        // Jazzcash set default variables
        $this->sJcPostUrl=config('paymentgateway.JC_POST_URL');
        $this->sJcPostUrlV2=config('paymentgateway.JC_POST_URL_V2');
        $this->sJcStatusInquiryUrl=config('paymentgateway.JC_STATUS_INQUIRY_URL');
        $this->sJcMerchantID=config('paymentgateway.JC_MERCHANTID');
        $this->sJcPassword=config('paymentgateway.JC_PASSWORD');
        $this->sJcReturnUrl=config('paymentgateway.JC_RETURNURL');
        $this->sJcVersion=config('paymentgateway.JC_VERSION');
        $this->sJcLanguage=config('paymentgateway.JC_LANGUAGE');
        $this->sJcCurrency=config('paymentgateway.JC_CURRENCY');
        $this->sJcHashkey=config('paymentgateway.JC_HASHKEY');
        $this->sJcPostUrlCard=config('paymentgateway.JC_POST_URL_CARD');

        // EasyPaisa set default variables
        $this->sEpMaUrl=config('paymentgateway.EP_MA_URL');
        $this->sEpOtcUrl=config('paymentgateway.EP_OTC_URL');
        $this->sEpStoreID=config('paymentgateway.EP_STORE_ID');
        $this->sEpCredentials=config('paymentgateway.EP_CREDENTIALS');
        $this->sEpTypeMA=config('paymentgateway.EP_MA');
        $this->sEpTypeOTC=config('paymentgateway.EP_OTC');
        $this->sEpEWPAccount=config('paymentgateway.EP_EWP_ACCOUNT');
        $this->sEpStatusInquiryUrl=config('paymentgateway.EP_STATUS_INQUIRY_URL');
    }
    public function jc_card(Request $request)
    {

        $aData = array(); 
        $postURL =$this->sJcPostUrlCard;
        $aData['pp_Password']= $this->sJcPassword;
        $aData['pp_MerchantID']= $this->sJcMerchantID;
        $aData['pp_Language']= $this->sJcLanguage;
        $aData['pp_TxnCurrency']= $this->sJcCurrency;
        $aData['pp_ReturnURL']= $this->sJcReturnUrl;
        $aData['pp_Version']= $this->sJcVersion;
        $aData['pp_TxnType']= 'MPAY';
        

        $aData['pp_Amount']= "1000";
        $aData['pp_BillReference']= "resfrence";
        $aData['pp_BankID']="TBANK" ;
        $aData['pp_Description']= "jazz cash card transaction description";
        $aData['pp_ProductID']= "RETL";
        $aData['pp_SubMerchantID']= ($request['pp_SubMerchantID']!="")?$request['pp_ProductID']:"";
        $aData['pp_TxnRefNo']= "T".date('YmdHis');
        $aData['pp_TxnDateTime']=date('YmdHis'); 
        $aData['pp_TxnExpiryDateTime']=date('YmdHis', strtotime('+1 Days')); 
        $aData['ppmpf_1']= ($request['ppmpf_1']!="")?$request['ppmpf_1']:"1";
        $aData['ppmpf_2']= ($request['ppmpf_2']!="")?$request['ppmpf_2']:"2";
        $aData['ppmpf_3']= ($request['ppmpf_3']!="")?$request['ppmpf_3']:"3";
        $aData['ppmpf_4']= ($request['ppmpf_4']!="")?$request['ppmpf_4']:"4"; 
        $aData['ppmpf_5']= ($request['ppmpf_5']!="")?$request['ppmpf_5']:"5"; 
       
        $secureHash =$this->jcSecureHash($aData);
        $aData['pp_SecureHash']= $secureHash; 
        //dd($aData); 
        //$response = $this->paymentCurl($this->sJcPostUrlV2,'POST',json_encode($aData));
         
         //dd($response);

     //    $aData = array();      
     //    $aData['pp_Password']= $this->sJcPassword;
     //    $aData['pp_MerchantID']= $this->sJcMerchantID;
     //    $aData['pp_Language']= $this->sJcLanguage;
     //    $aData['pp_TxnCurrency']= $this->sJcCurrency;
     //    $aData['pp_ReturnURL']= $this->sJcReturnUrl;
     //    $aData['pp_Version']= $this->sJcVersion;


    	// $MerchantID ="MC11838"; //Your Merchant from transaction Credentials 
	    // $Password   ="c721svvvh1"; //Your Password from transaction Credentials 
	    // $ReturnURL  ="https://www.clinicall.hospitallcare.com/api/jazzcash"; //Your Return URL 
	    // $HashKey    ="t3xczx5hed";//Your HashKey from transaction Credentials 
	    // $PostURL = "https://sandbox.jazzcash.com.pk/CustomerPortal/transactionmanagement/merchantform"; 	    
	    // $Amount = 200; //Last two digits will be considered as Decimal 
	    // $BillReference = "123456"; 
	    // $Description = "Thank you for using Jazz Cash"; 
	    // $Language = "EN"; 
	    // $TxnCurrency = "PKR"; 
	    // $TxnDateTime = date('YmdHis') ; 
	    // $TxnExpiryDateTime = date('YmdHis', strtotime('+8 Days')); 
	    // $TxnRefNumber = "T".date('YmdHis'); 
	    // $TxnType = ""; 
	    // $Version = '1.1'; 
	    // $SubMerchantID = ""; 
	    // $DiscountedAmount = ""; 
	    // $DiscountedBank = ""; 
	    // $ppmpf_1=""; 
	    // $ppmpf_2=""; 
	    // $ppmpf_3=""; 
	    // $ppmpf_4=""; 
	    // $ppmpf_5=""; 

	    // $HashArray=[$Amount,$BillReference,$Description,$DiscountedAmount,$DiscountedBank,$Language,$MerchantID,$Password,$ReturnURL,$TxnCurrency,$TxnDateTime,$TxnExpiryDateTime,$TxnRefNumber,$TxnType,$Version,$ppmpf_1,$ppmpf_2,$ppmpf_3,$ppmpf_4,$ppmpf_5]; 

	    // $SortedArray=$HashKey; 
	    // for ($i = 0; $i < count($HashArray); $i++) { 
	    // if($HashArray[$i] != 'undefined' AND $HashArray[$i]!= null AND $HashArray[$i]!="" ) 
	    // { 

	    // 	$SortedArray .="&".$HashArray[$i]; 
	    // }  
	    //  } 
	    // 	$Securehash = hash_hmac('sha256', $SortedArray, $HashKey); 

	    // return view('jazzcash')->with(compact('HashArray','PostURL','Amount','BillReference','Description','DiscountedAmount','DiscountedBank','Language','MerchantID','Password','ReturnURL','TxnCurrency','TxnDateTime','TxnExpiryDateTime','TxnRefNumber','TxnType','Version','ppmpf_1','ppmpf_2','ppmpf_3','ppmpf_4','ppmpf_5','Securehash','SubMerchantID'));  
       // dump($aData);
        return view("jazzcash",compact('aData','postURL'));
        //return view('jazzcash')->with($aData);      
    }

    public function jcMwallet(Request $request)
    {
        dump('jcMwallet');
       //dump($input);
        dd($request);

        $aData = array();		
        $aData['pp_Password']= $this->sJcPassword;
        $aData['pp_MerchantID']= $this->sJcMerchantID;
        $aData['pp_Language']= $this->sJcLanguage;
        $aData['pp_TxnCurrency']= $this->sJcCurrency;

        $aData['pp_Amount']= $request['pp_Amount'];
        $aData['pp_BillReference']= $request['pp_BillReference'];
        $aData['pp_BankID']= ($request['pp_BankID']!="")? $request['pp_BankID']:"" ;
        $aData['pp_Description']= $request['pp_Description'];
        $aData['pp_ProductID']= ($request['pp_ProductID']!="")?$request['pp_ProductID']:"";
        $aData['pp_SubMerchantID']= ($request['pp_SubMerchantID']!="")?$request['pp_ProductID']:"";
        $aData['pp_TxnRefNo']= $request['pp_TxnRefNo'];//"T".date('YmdHis');
        $aData['pp_TxnDateTime']=date('YmdHis'); 
        $aData['pp_TxnExpiryDateTime']=date('YmdHis', strtotime('+1 Days')); 
        $aData['ppmpf_1']= ($request['ppmpf_1']!="")?$request['ppmpf_1']:"";
        $aData['ppmpf_2']= ($request['ppmpf_2']!="")?$request['ppmpf_2']:"";
        $aData['ppmpf_3']= ($request['ppmpf_3']!="")?$request['ppmpf_3']:"";
        $aData['ppmpf_4']= ($request['ppmpf_4']!="")?$request['ppmpf_4']:""; 
        $aData['ppmpf_5']= ($request['ppmpf_5']!="")?$request['ppmpf_5']:""; 
        $aData['pp_MobileNumber']= ($request['pp_MobileNumber']!="")?$request['pp_MobileNumber']:""; 
        $aData['pp_CNIC']= ($request['pp_CNIC']!="")?$request['pp_CNIC']:""; 
   
        $secureHash =$this->jcSecureHash($aData);
        $aData['pp_SecureHash']= $secureHash; 
        dump("Request Exec"); 
	    $response = $this->paymentCurl($this->sJcPostUrlV2,'POST',json_encode($aData));
	     
	    dd($response);

    }
    public function jcOtc(Request $request){      
       
        $aData = array();     

        $aData['pp_Password']= $this->sJcPassword;
        $aData['pp_MerchantID']= $this->sJcMerchantID;
        $aData['pp_Language']= $this->sJcLanguage;
        $aData['pp_TxnCurrency']= $this->sJcCurrency;
        $aData['pp_ReturnURL']= $this->sJcReturnUrl;
        $aData['pp_Version']= $this->sJcVersion;

        $aData['pp_Amount']= $request['pp_Amount'];
        $aData['pp_BillReference']= $request['pp_BillReference'];
        $aData['pp_BankID']= ($request['pp_BankID']!="")? $request['pp_BankID']:"" ;
        $aData['pp_Description']= $request['pp_Description'];
        $aData['pp_ProductID']= ($request['pp_ProductID']!="")?$request['pp_ProductID']:"";
        $aData['pp_SubMerchantID']= ($request['pp_SubMerchantID']!="")?$request['pp_ProductID']:"";
        $aData['pp_TxnType']= $request['pp_TxnType'];
        $aData['pp_TxnRefNo']= "T".date('YmdHis');
        $aData['pp_TxnDateTime']=date('YmdHis'); 
        $aData['pp_DiscountBank'] = '';
        $aData['pp_TxnExpiryDateTime']=date('YmdHis', strtotime('+5 Days')); 
        $aData['ppmpf_1']= ($request['ppmpf_1']!="")?$request['ppmpf_1']:"";
        $aData['ppmpf_2']= ($request['ppmpf_2']!="")?$request['ppmpf_2']:"";
        $aData['ppmpf_3']= ($request['ppmpf_3']!="")?$request['ppmpf_3']:"";
        $aData['ppmpf_4']= ($request['ppmpf_4']!="")?$request['ppmpf_4']:""; 
        $aData['ppmpf_5']= ($request['ppmpf_5']!="")?$request['ppmpf_5']:""; 

        $secureHash =$this->jcSecureHash($aData);
        $aData['pp_SecureHash']= $secureHash; 
        $response = $this->paymentCurl($this->sJcPostUrl,'POST',json_encode($aData));
        
        dd($response);
     
    }  

    public function jcStatusInquiry(Request $request){ 

        $aData = array(); 

        $aData['pp_Password']= $this->sJcPassword;
        $aData['pp_MerchantID']= $this->sJcMerchantID;
        $aData['pp_TxnRefNo']= $request['pp_TxnRefNo'];
        $secureHash =$this->jcSecureHash($aData);
        $aData['pp_SecureHash']= $secureHash; 
       
        $response = $this->paymentCurl($this->sJcStatusInquiryUrl,'POST',json_encode($aData));
        dd($response);   
    }  

    private function jcSecureHash($aHash){
       
       if(!empty($aHash)){
            ksort($aHash);
            $sConcateVariables=$this->sJcHashkey; 
            foreach($aHash as $key => $val){
                if(!empty($val)){
                        $sConcateVariables .="&".$val;
                }
            }
            $sSecureHash = hash_hmac('sha256', $sConcateVariables, $this->sJcHashkey); 
            $sSecureHash =strtoupper($sSecureHash);
            return $sSecureHash;
        }
        
        return false;
    }

    public function jcPostURL(Request $request){		
    	$aData=$request->all();
    }
    public function jc_listner(Request $request){		
    	$aData=$request->all();
    	dump($aData);
    	LOG::info("JC Lister------------");
    	LOG::info(print_r($request->all(), true));
    }

    public function epMAPayment(Request $request){

        $aData =array(); 
        $aData['storeId'] = $this->sEpStoreID;
        $aData['transactionType'] = $this->sEpTypeMA;

        $aData['orderId'] = $request['orderId'];
        $aData['transactionAmount'] = $request['transactionAmount'];
        $aData['mobileAccountNo'] = $request['mobileAccountNo'];   
        $aData['emailAddress'] = $request['emailAddress'];         
             
        $aData = json_encode($aData);
        //dd($aData);
        $response = $this->EpPaymentCurl($aData,$this->sEpMaUrl);     

        $buffer = json_encode($response);
        dd($buffer);
    }

      public function epOTCPayment(Request $request){

        $aData =array(); 
        $aData['storeId'] = $this->sEpStoreID;
        $aData['transactionType'] = $this->sEpTypeOTC;

        $aData['orderId'] = $request['orderId'];
        $aData['transactionAmount'] = $request['transactionAmount'];
        $aData['msisdn'] = $request['msisdn'];   
        $aData['emailAddress'] = $request['emailAddress'];  
        $aData['tokenExpiry'] = date('Ymd His',strtotime('+5 Days'));                
        $aData = json_encode($aData);
    
        //dd($aData);
        $response = $this->EpPaymentCurl($aData,$this->sEpOtcUrl);     

        $buffer = json_encode($response);
        dd($buffer);
    }
    public function epStatusInquiry(Request $request){

        $aData =array(); 
        $aData['storeId'] = $this->sEpStoreID;
        $aData['accountNum'] = $this->sEpEWPAccount;
        $aData['orderId'] = $request['orderId'];                   
        $aData = json_encode($aData);
    
       // dd($aData);
        $response = $this->EpPaymentCurl($aData,$this->sEpStatusInquiryUrl);     

        $buffer = json_encode($response);
        dd($buffer);
    }
    
    
    private function paymentCurl($url, $method='GET',$aPost){
    	$curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 3000,
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
        return json_decode($response);
    }
    private function EpPaymentCurl($data,$url){

    $curl_handle=curl_init();
        curl_setopt($curl_handle,CURLOPT_URL,$url);
        curl_setopt($curl_handle,CURLOPT_CUSTOMREQUEST,"POST");
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array(
            'Content-Type:application/json',
            'Credentials: Y2xpbmljYWxsOmVlZjQ5MmMxMWFlYzFmNDA3MjZlNjdiOTA3ZjllZjlm',
           // 'Credentials: SG9zcGl0QUxMOmZjMWY4Zjg3Yzc5NTQwYzhiY2UyMTdhMmE5MTI1MjEw',
        ));
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS,$data);
        curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
        curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
        
        $buffer = curl_exec($curl_handle);
        
        curl_close($curl_handle);

        $response = json_decode($buffer);
    return $response;
}

}
