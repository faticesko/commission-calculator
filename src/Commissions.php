<?php   
  //namespace Commissions;

  class Commissions {

    
    public function calculateCommissions($file) {

      $file_content = explode("\n", file_get_contents($file));

      $return = '';
      foreach ($file_content as $row){
        //convert file content to object
        $row = json_decode($row);

        //check bin
        $binResults = $this->getBinResult($row->bin);
       
        //if BinLookup response
        if($binResults){
            //check if isEu
            $isEu = $this->isEu($binResults->country->alpha2);

            //get rates
            $rate = $this->getConverRates($row->currency);
            
            //if curency in Euro
            if ($row->currency == 'EUR' or $rate == 0) {
                $amntFixed = $row->amount;
            }

            //if currency is not Euro
            if ($row->currency != 'EUR' or $rate > 0) {
               $amntFixed = $row->amount / $rate;
            }

        }else{
          //stop and break the loop if bin value are not correct
          $return = "Incorrect Bin value found. Please check your input.txt file and be sure that you provide correct BIN values \n";
          break;
        }

        $return.= $this->appendCommission($isEu, ceil($amntFixed))."\n";

      }

      return $return;

    }



    public function getBinResult($bin){

      $username = BIN_LOOKUP_AUTH_USERNAME;
      $password = BIN_LOOKUP_AUTH_PASSWORD;
       
      $context = stream_context_create(array(
          'http' => array(
              'header'  => "Authorization: Basic " . base64_encode("$username:$password")
          )
      ));

      //get bin lookup content
      $binResults = @file_get_contents(BIN_LOOKUP_URL.'/'.$bin, false, $context);
      if($binResults){
        //convert $data to object
        return $binReturn = json_decode($binResults);
      }else{
        return false;
      }

    }



    public function getConverRates($currency){

      $username = EXCHANGES_RATES_AUTH_USERNAME;
      $password = EXCHANGES_RATES_AUTH_PASSWORD;
       
      $context = stream_context_create(array(
          'http' => array(
              'header'  => "Authorization: Basic " . base64_encode("$username:$password")
          )
      ));

      $exchange_rates = @json_decode(file_get_contents(EXCHANGES_RATES_URL, false, $context), true);
      return $exchange_rates['rates'][$currency];
    }




    public function appendCommission($isEu, $amount){

      if($isEu == 'yes'){
        $amount = $amount * EU_ISSUED_COMMISSION_RATE;
      }else{
        $amount = $amount * NON_EU_ISSUED_COMMISSION_RATE;
      }

      return $amount;
    }




    public function isEu($c) {
      $result = false;
      switch($c) {
          case 'AT':
          case 'BE':
          case 'BG':
          case 'CY':
          case 'CZ':
          case 'DE':
          case 'DK':
          case 'EE':
          case 'ES':
          case 'FI':
          case 'FR':
          case 'GR':
          case 'HR':
          case 'HU':
          case 'IE':
          case 'IT':
          case 'LT':
          case 'LU':
          case 'LV':
          case 'MT':
          case 'NL':
          case 'PO':
          case 'PT':
          case 'RO':
          case 'SE':
          case 'SI':
          case 'SK':
              $result = 'yes';
              return $result;
          default:
              $result = 'no';
      }
      return $result;
    }
    
  }

?>