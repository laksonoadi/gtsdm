<?php
//By Wahyono
require_once GTFWConfiguration::GetValue( 'application', 'docroot') ."/main/lib/Mail/Mail/Mail.php";

class Email extends Database {
   protected $mSqlFile = 'module/email/business/email.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);   
      //
      $this->Status=GTFWConfiguration::GetValue( 'application', 'email_notifications');   
   }
   
   function kirimEmail($to,$cc=NULL,$bcc=NULL,$from,$subject, $body){
		if ($this->Status){
			$date=date('Y-m-d H:i:s');
      
	  
			$host = GTFWConfiguration::GetValue('application', 'smtp_host');
			$port = GTFWConfiguration::GetValue('application', 'smtp_port');
			$auth = GTFWConfiguration::GetValue('application', 'smtp_auth');
			$username = GTFWConfiguration::GetValue('application', 'smtp_username');
			$password = GTFWConfiguration::GetValue('application', 'smtp_password');
      
			$headers = array ('From' => $from,
				'To' => $to,
				'Cc' => $cc,
				'Bcc' => $bcc,
				'Subject' => $subject);
				$smtp = Mail::factory('SMTP',
                              array ('host' => $host,
                                'port' => $port,
								'auth' => $auth,
                                'username' => $username,
                                'password' => $password));
        
			//print_r($headers); print_r($body); exit();
        
            if (!empty($cc)&&!empty($bcc)){
                $mail = $smtp->send($to.", ".$cc.", ".$bcc, $headers, $body);
            }elseif (!empty($cc)){
                $mail = $smtp->send($to.", ".$cc, $headers, $body);
            }elseif (!empty($bcc)){
                $mail = $smtp->send($to.", ".$bcc, $headers, $body);
            }else{
                $mail = $smtp->send($to, $headers, $body);
            }
            
            if (PEAR::isError($mail)) {
              return "Pengiriman Email Notifications Gagal Karena ".$mail->getMessage();
            } else {
              $this->Execute($this->mSqlQueries['simpan_email'],array($from,$to,$cc,$bcc,$subject,$body));
              return "Pengiriman Email Notifications Berhasil.";
            }
        }
        
        return "";
   }
   
   function getBodyEmail($file,$arrData){
      $nama_file=GTFWConfiguration::GetValue( 'application', 'template_email_path').$file.'.txt';
      $data=fopen($nama_file,"r");
      $body='';
      
      $isi_data=htmlentities(fgets($data,10000));
      while (!feof($data)) {
          
    			$isi_data=htmlentities(fgets($data,10000));
    			for ($i=0; $i<sizeof($arrData); $i++){
    			   $isi_data=str_replace($arrData[$i]['replace'],$arrData[$i]['with'],$isi_data);
          }
          $body .= $isi_data;
    	}
    	fclose($data);
    	
      $footer = GTFWConfiguration::GetValue('application', 'email_footer');
    	
      return $body.$footer;
   }
   
   function getSubjectEmail($file){
      $nama_file=GTFWConfiguration::GetValue( 'application', 'template_email_path').$file.'.txt';
      $data=fopen($nama_file,"r");
    	$subject=htmlentities(fgets($data,10000));
    	fclose($data);
    	
      $presubject = GTFWConfiguration::GetValue('application', 'email_presubject');    	
    	
      return $presubject.' '.$subject;
   }
   
   function IndonesianDate($StrDate, $StrFormat)
	{
		$StrFormat = strtoupper($StrFormat);
		switch ($StrFormat)
		{
			case "MM-DD-YYYY" :	list($Month, $Day, $Year) = explode("-", $StrDate);
								break;
			case "DD-MM-YYYY" :	list($Day, $Month, $Year) = explode("-", $StrDate);
								break;
			case "YYYY-MM-DD" :	list($Year, $Month, $Day) = explode("-", $StrDate);
								break;
			case "MM/DD/YYYY" :	list($Month, $Day, $Year) = explode("/", $StrDate);
								break;
			case "DD/MM/YYYY" :	list($Day, $Month, $Year) = explode("/", $StrDate);
								break;
			case "YYYY/MM/DD" :	list($Year, $Month, $Day) = explode("/", $StrDate);
								break;
		}//End switch

		switch ($Month)
		{
			case "01" :	$StrResult = $Day." January ".$Year;
						break;
			case "02" :	$StrResult = $Day." February ".$Year;
						break;
			case "03" :	$StrResult = $Day." March ".$Year;
						break;
			case "04" :	$StrResult = $Day." April ".$Year;
						break;
			case "05" :	$StrResult = $Day." May ".$Year;
						break;
			case "06" :	$StrResult = $Day." June ".$Year;
						break;
			case "07" :	$StrResult = $Day." July ".$Year;
						break;
			case "08" :	$StrResult = $Day." August ".$Year;
						break;
			case "09" :	$StrResult = $Day." September ".$Year;
						break;
			case "10" :	$StrResult = $Day." October ".$Year;
						break;
			case "11" :	$StrResult = $Day." November ".$Year;
						break;
			case "12" :	$StrResult = $Day." December ".$Year;
						break;
		} //end switch
		return $StrResult;
	}
}
?>