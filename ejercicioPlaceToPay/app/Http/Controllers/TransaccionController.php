<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

/**
 * Description of TransaccionController
 *
 * @author jacobo.mendez
 */
class TransaccionController extends Controller
{
    
    private $url;
    private $identificador;
    private $tranKey;
    private $seed;
    private $hashString;

    public function __construct()
    {
        $this->url=env('PTP_URL');
        $this->identificador=env('PTP_ID');
        $this->tranKey=env('PTP_TRANKEY');
        $this->seed = date('c');
        $this->hashString=sha1($this->seed.$this->tranKey,false);    
    }

    public function obtener_bancos()
    {
        // Conectamos al servicio para obtener los bancos siempre y cuando no estén cacheados
        if (!\Cache::has('bancos')) { 

$xml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:pse="https://api.placetopay.com/soap/pse">
   <soapenv:Header/>
   <soapenv:Body>
      <pse:getBankList>
         <auth>
            <login>'.$this->identificador.'</login>
            <tranKey>'.$this->hashString.'</tranKey>
            <seed>'.$this->seed.'</seed>
            <additional></additional>
         </auth>
      </pse:getBankList>
   </soapenv:Body>
</soapenv:Envelope>';

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $this->url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            
            // converting
            $response = curl_exec($ch); 
	    curl_close($ch);
            
            if ($response !== false && strpos($response,"<getBankListResult>") !== false) {
                //convert xml string into an object
                $response=substr($response,strpos($response,"<getBankListResult>"));
                $response=substr($response,0,strlen($response)-strlen(substr($response,strpos($response,"</ns1:getBankListResponse>"))));

                $xml = simplexml_load_string($response);

                $json = json_encode($xml);
                $responseArray = json_decode($json,true);

                $arrBancos=$responseArray['item'];
            }
            else {
                $arrBancos="";
            }
            
            \Cache::put('bancos', $arrBancos, 1440); // Se cachea la info de los bancos durante 24 horas
        }
        
        return \Cache::get('bancos');
    }
    
    public function hacer_transaccion (array $datos) {
        
        $bankInterface=array("PERSONAS"=>"0","EMPRESAS"=>"1");

$xml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:pse="https://api.placetopay.com/soap/pse">
   <soapenv:Header/>
   <soapenv:Body>
      <pse:createTransaction>
         <auth>
            <login>'.$this->identificador.'</login>
            <tranKey>'.$this->hashString.'</tranKey>
            <seed>'.$this->seed.'</seed>
            <additional></additional>
         </auth>
         <transaction>
            <bankCode>'.$datos["bankCode"].'</bankCode>
            <bankInterface>'.$bankInterface[$datos["bankInterface"]].'</bankInterface>
            <returnURL>'.$datos["url"].'</returnURL>
            <reference>'.$datos["reference"].'</reference>
            <description>'.$datos["description"].'</description>
            <language>ES</language>
            <currency>'.$datos["currency"].'</currency>
            <totalAmount>'.$datos["totalAmount"].'</totalAmount>
            <taxAmount>0</taxAmount>
            <devolutionBase>0</devolutionBase>
            <tipAmount>0</tipAmount>
            <payer>
               <documentType>'.$datos["documentType"].'</documentType>
               <document>'.$datos["document"].'</document>
               <firstName>'.$datos["firstName"].'</firstName>
               <lastName>'.$datos["lastName"].'</lastName>
               <company>'.$datos["company"].'</company>
               <emailAddress>'.$datos["emailAddress"].'</emailAddress>
               <address>'.$datos["address"].'</address>
               <city>'.$datos["city"].'</city>
               <province>'.$datos["province"].'</province>
               <country>'.$datos["country"].'</country>
               <phone>'.$datos["phone"].'</phone>
               <mobile>'.$datos["mobile"].'</mobile>
               <postalCode>'.$datos["postalCode"].'</postalCode>
            </payer>
            <buyer>
               <documentType>'.$datos["documentType"].'</documentType>
               <document>'.$datos["document"].'</document>
               <firstName>'.$datos["firstName"].'</firstName>
               <lastName>'.$datos["lastName"].'</lastName>
               <company>'.$datos["company"].'</company>
               <emailAddress>'.$datos["emailAddress"].'</emailAddress>
               <address>'.$datos["address"].'</address>
               <city>'.$datos["city"].'</city>
               <province>'.$datos["province"].'</province>
               <country>'.$datos["country"].'</country>
               <phone>'.$datos["phone"].'</phone>
               <mobile>'.$datos["mobile"].'</mobile>
               <postalCode>'.$datos["postalCode"].'</postalCode>
            </buyer>
            <shipping>
               <documentType>'.$datos["documentType"].'</documentType>
               <document>'.$datos["document"].'</document>
               <firstName>'.$datos["firstName"].'</firstName>
               <lastName>'.$datos["lastName"].'</lastName>
               <company>'.$datos["company"].'</company>
               <emailAddress>'.$datos["emailAddress"].'</emailAddress>
               <address>'.$datos["address"].'</address>
               <city>'.$datos["city"].'</city>
               <province>'.$datos["province"].'</province>
               <country>'.$datos["country"].'</country>
               <phone>'.$datos["phone"].'</phone>
               <mobile>'.$datos["mobile"].'</mobile>
               <postalCode>'.$datos["postalCode"].'</postalCode>
            </shipping>
            <ipAddress>'.$datos["ip"].'</ipAddress>
            <userAgent>'.$datos["userAgent"].'</userAgent>
            <additionalData>
            </additionalData>
         </transaction>
      </pse:createTransaction>
   </soapenv:Body>
</soapenv:Envelope>';


            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $this->url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            
            // converting
            $response = curl_exec($ch); 
	    curl_close($ch);
            
            /*echo '<h2>XML</h2><pre>' .$xml_post_string."</pre>";
		echo '<h2>Request</h2><pre>' . htmlspecialchars($xml_post_string, ENT_QUOTES) . '</pre>';
	        echo '<h2>Response</h2><pre>' . htmlspecialchars($response, ENT_QUOTES) . '</pre>';
            exit;*/
            
            if ($response !== false && strpos($response,"<createTransactionResult>") !== false) {
                //convert xml string into an object
                $response=substr($response,strpos($response,"<createTransactionResult>"));
                $response=substr($response,0,strlen($response)-strlen(substr($response,strpos($response,"</ns1:createTransactionResponse>"))));

                $xml = simplexml_load_string($response);

                $json = json_encode($xml);
                $responseArray = json_decode($json,true);

                $arrTransaction=$responseArray;
                
                //var_dump($arrTransaction);exit;
            }
            else {
                $arrTransaction="";
            }
            
        return $arrTransaction;
    }
    
    public function obtener_estado($id)
    {

$xml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:pse="https://api.placetopay.com/soap/pse">
   <soapenv:Header/>
   <soapenv:Body>
      <pse:getTransactionInformation>
         <auth>
            <login>'.$this->identificador.'</login>
            <tranKey>'.$this->hashString.'</tranKey>
            <seed>'.$this->seed.'</seed>
            <additional></additional>
         </auth>
         <transactionID>'.$id.'</transactionID>
      </pse:getTransactionInformation>
   </soapenv:Body>
</soapenv:Envelope>';

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $this->url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            
            // converting
            $response = curl_exec($ch); 
	    curl_close($ch);
            
            if ($response !== false && strpos($response,"<getTransactionInformationResult>") !== false) {
                //convert xml string into an object
                $response=substr($response,strpos($response,"<getTransactionInformationResult>"));
                $response=substr($response,0,strlen($response)-strlen(substr($response,strpos($response,"</ns1:getTransactionInformationResponse>"))));

                $xml = simplexml_load_string($response);

                $json = json_encode($xml);
                $responseArray = json_decode($json,true);

                $arrTransaccionInfo=$responseArray;
            }
            else {
                $arrTransaccionInfo="";
            }
            
        return $arrTransaccionInfo;
    }
    
    public function formulario_entrada()
    {
        // Obtenemos los bancos 
        $bancos=$this->obtener_bancos();
        
        return view('formulario_transaccion', ['bancos' => $bancos]);
    }
    
    public function formulario_entrada_form(Request $request)
    {
        if ($request->isMethod("post")) {
            
            //Validamos los datos
            $rules=array(
                "bankCode"=>'required|integer',
                "bankInterface"=>'required',
                "reference"=>'required|max:32',
                "description"=>'required|max:255',
                "currency"=>'required',
                "totalAmount"=>'required|integer',
                "documentType"=>'required',
                "document"=>'required|max:12',
                "firstName"=>'required|max:60',
                "lastName"=>'required|max:60',
                "company"=>'required|max:60',
                "emailAddress"=>'required|email',
                "address"=>'required|max:100',
                "city"=>'required|max:50',
                "province"=>'required|max:50',
                "country"=>'required',
                "phone"=>'required|numeric',
                "mobile"=>'required|numeric',
                "postalCode"=>'required|digits:5'
            );

            $this->validate($request, $rules);
            
            $arrForm = $request->all();
            
            $arrForm["url"]='http://'.$request->getHttpHost().'/transaccion/listar';
            $arrForm["ip"]=$request->ip();
            $arrForm["userAgent"]=$request->userAgent();
            
            // hacemos la petición de transacción
            $transaccion=$this->hacer_transaccion($arrForm);
            
            $arrTransacciones=\Cache::get('transacciones');
            $arrTransacciones[]=$transaccion;
            \Cache::put('transacciones', $arrTransacciones, 30);
            
            return redirect()->to($transaccion["bankURL"]);
            
            /*for ($i=0;$i<count($arrTransacciones);$i++){
                $arrTransacciones[$i]["estado"]=$this->obtener_estado($arrTransacciones[$i]["transactionID"]);
            }*/
        }
        else {
            $arrTransacciones="";
        }
        
        return view('lista_transacciones', ['transacciones' => $arrTransacciones]);
        
    }
    
    public function listar_transacciones()
    {
        if (\Cache::has('transacciones')) {
            $arrTransacciones=\Cache::get('transacciones');
            
            // Conectamos al servicio para obtener el estado de las transacciones
            for ($i=0;$i<count($arrTransacciones);$i++){
                $arrTransacciones[$i]["estado"]=$this->obtener_estado($arrTransacciones[$i]["transactionID"]);
            }
        }
        else {
            $arrTransacciones="";
        }
        return view('lista_transacciones', ['transacciones' => $arrTransacciones]);
    }
}
