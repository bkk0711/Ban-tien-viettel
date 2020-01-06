<?php
/*
||----------------------------------------------||
||  <name> Bùi Khôi </name>			||
|| 	<phone> 0869.20.851 </phone>		||
|| 	<mail> bkkhoi1800151@gmail.com </mail>	||
||  <facebook> facebook.com/bkk0711 </facebook>	||
||----------------------------------------------||
*/
if(!$_GET[taikhoan] and !$_GET[matkhau] and !$_GET[matkhauct] and !$_GET[sotien] and !$_GET[sdtnhan]){
    echo 'Thông Tin Không Đủ';
}
elseif($_GET[sotien] % 1000 != 0){
    echo 'Số tiền nạp phải là bội của 1000.';
}
else{
    login();
}
function login(){
    $url = "https://viettel.vn/dang-nhap";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    $result = curl_exec($ch);

    $token = explode('"', explode('vt_signin[_csrf_token]" value="', $result)[1])[0]; //-> Lấy token
    $cookie = explode(";", explode('Set-Cookie: ', $result)[1])[0]; //-> Lấy cookie
    $ch1 = curl_init();
    $username = $_GET[taikhoan];
    $password = $_GET[matkhau];
    $type ='mob';
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch1, CURLOPT_URL, $url);  
    curl_setopt($ch1, CURLOPT_POSTFIELDS, "vt_signin[_csrf_token]=$token&vt_signin[username]=$username&vt_signin[password]=$password&vt_signin[category]=$type"); //mob
    curl_setopt($ch1, CURLOPT_POST, 1);
    curl_setopt($ch1, CURLOPT_HEADER, 1);
    $headers = array();
    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
    $headers[] = 'Cookie: ' . $cookie;
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) coc_coc_browser/77.0.126 Chrome/71.0.3578.126 Safari/537.36';
    curl_setopt($ch1, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch1);
	
        $check_status = explode('</div>',explode('<div class="error_list">',$result)[1])[0];
        
       if (strlen($check_status) > 10) {
		echo $_check_status;
    }
	else {
        $cookie_log = explode(';', explode("Set-Cookie: ", $result)[1])[0]; //-> Lấy cookie đã đăng nhập
	
        trang_nap($cookie_log);
           }
}   
/*/ Hàm này lưu images captcha vào hệ thống /*/
/*/ Nếu chưa có folder thì tạo folder "thu" /*/
    function grab_image($url, $saveto, $cookie){
        if(!$url){
            echo 'Lỗi hệ thống,vui lòng thực hiện lại thao tác.';exit;
    }elseif(!$saveto){
      echo 'Lỗi hệ thống,vui lòng thực hiện lại thao tác.';exit;
    }
    elseif(!$cookie){
        echo 'Lỗi hệ thống,vui lòng thực hiện lại thao tác.';exit;
    }
        $saveto = "thu/" . $saveto;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        $headers = array();
        $headers[] = 'Cookie: ' . $cookie; //-> Set phiên vào recaptcha
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) coc_coc_browser/77.0.126 Chrome/71.0.3578.126 Safari/537.36';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $raw = curl_exec($ch);
        curl_close($ch);
        if (file_exists($saveto)) {
            unlink($saveto);
        }
        $fp = fopen($saveto, 'x');
        fwrite($fp, $raw);
        fclose($fp);
    }
     /*/ Function lấy phiên dữ liệu /*/
   function trang_nap($cookie_log){
        	
    $ch3 = curl_init();
    curl_setopt($ch3, CURLOPT_URL, "https://viettel.vn/myvt/chuyen-tien");
    curl_setopt($ch3, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch3, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch3, CURLOPT_HEADER, 1);
    $headers = array();
    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
    $headers[] = 'Cookie: ' . $cookie_log; //-> Gửi dữ liệu kèm cookie đã lấy ở trên
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) coc_coc_browser/77.0.126 Chrome/71.0.3578.126 Safari/537.36';
    curl_setopt($ch3, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch3);
    $token = explode('"', explode('<input type="hidden" name="TransferMoneyForm[_csrf_token]" value="', $result)[1])[0]; //-> Lấy cookie
    $captcha = "http://apivtp.vietteltelecom.vn/myviettel.php/gen-img-captcha?sid=".explode('"', explode('src="http://apivtp.vietteltelecom.vn/myviettel.php/gen-img-captcha?sid=', $result)[1])[0].''; //Lấy url recaptcha
                 
                 
                 
                 
                       	
    $ch9 = curl_init();
    curl_setopt($ch9, CURLOPT_URL, "$captcha");
    curl_setopt($ch9, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch9, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch9, CURLOPT_HEADER, 1);
    $headers = array();
    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
    $headers[] = 'Cookie: ' . $cookie_log; //-> Gửi dữ liệu kèm cookie đã lấy ở trên
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) coc_coc_browser/77.0.126 Chrome/71.0.3578.126 Safari/537.36';
    curl_setopt($ch9, CURLOPT_HTTPHEADER, $headers);
    $result9 = curl_exec($ch9);  
    $cookiecaptcha = explode(";", explode('Set-Cookie: ', $result9)[1])[0]; //-> Lấy cookie
                 
                 
                 
                 
    $thu = rand(100000000,999999999);
    grab_image($captcha, "$thu.jpg", $cookiecaptcha); //-> Gọi hàm lưu lại file images recaptcha
   
 $junoo = json_decode(file_get_contents("https://api.junoo.net/api/captcha.php?key=junoo&link=http://api.junoo.net/thu/".$thu.".jpg"),true);
     $captchas = $junoo[result];

 if(strlen($captchas) == 4){
    $url = "https://viettel.vn/myvt/chuyen-tien";
    $ch1 = curl_init();
    $phone =$_GET[sdtnhan];
    $money =  $_GET[sotien];
    $mkct = $_GET[matkhauct];
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch1, CURLOPT_URL, $url);  
    curl_setopt($ch1, CURLOPT_POSTFIELDS, "TransferMoneyForm%5B_csrf_token%5D=$token&TransferMoneyForm%5Bphone_number%5D=$phone&TransferMoneyForm%5Bmoney%5D=$money&TransferMoneyForm%5Bpasswd%5D=$mkct&TransferMoneyForm%5Bcaptcha%5D=$captchas&TransferMoneyForm%5BshowPopup%5D=1");
    curl_setopt($ch1, CURLOPT_POST, 1);
    curl_setopt($ch1, CURLOPT_HEADER, 1);
    $headers = array();
    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
    $headers[] = 'Cookie: ' . $cookie_log;
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) coc_coc_browser/77.0.126 Chrome/71.0.3578.126 Safari/537.36';
    curl_setopt($ch1, CURLOPT_HTTPHEADER, $headers);
    $result2 = curl_exec($ch1);
    $check_status = explode('"',explode('message":"',$result2)[1])[0];
    $check_status2 = '{"message":"'.$check_status.'"}';
    $check_status3 = json_decode($check_status2);
    
    $check_status4 = explode('}',explode('{',$result2)[1])[0];
    $check_status5 = '{'.$check_status4.'"}';
    $check_status6 = json_decode($check_status4);
  
 
  if(strpos($check_status3->message, "Truyền thiếu tham số") > -1){
      echo 'Lỗi !!  ';
	  exit;
  }
  elseif($check_status3->message == 'Bạn chưa nhập đủ thông tin mật khẩu hoặc captcha'){
      echo 'Lỗi !!  ';
	  exit;
  }
  
  elseif($check_status3->message == 'Mã bảo mật không chính xác'){
      echo 'Lỗi !! ';
	  exit;
  }

  elseif($check_status3->message == 'Thành công'){
      echo'Bắn tiền tài khoản chính thành công cho số điện thoại <b>'.$phone.'</b>';exit;
  }
  else{
    echo $check_status3->message;
	exit;
  }
  
 }else{
     echo 'Lỗi !! ';
	 exit;
 }
 

   }
