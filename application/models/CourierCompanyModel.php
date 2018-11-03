<?php

if (!defined('BASEPATH'))
    exit('No direct script allowed');

class CourierCompanyModel extends CI_Model {

    function get_user($q) {
        return $this->db->get_where('m_user', $q);
    }

    function getUser($mail, $phone) {
        try{
             $sql = "call hand2handservices.GETUSERDETAILS(@v_whereclause := 'PHONENO = $phone OR EMAIL = ''" . $mail . "''');";
        $query = $this->db->query($sql);
        $result = $query->result_array();
//        echo '<pre>'; print_r($result);exit;
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
        }catch(Exception $e){
            echo '<pre>'; print_r($e);exit;
        }
       
    }

    function createUser($params) {
//        print_r($params);exit;
//        $sql = "CALL hand2handservices.ADDUSER(?,?,?,?,?,?,?,?,?, ?, ?,?,?)";
//        $sql = "CALL hand2handservices.ADDUSER(@v_firstname:=?, @v_lastname:=?,@v_phoneno:=?,@v_phonenoverified:=?,@v_email:=?,@v_emailverified:=?,@v_loginpwd:=?,@v_acceptedtermsconditions:=?,@v_usertype:=?, @v_referencecode:=?, @v_gstn:=?,@v_couriercompanyid:=?,@v_output_userid);";



        $firstName = $params['FIRSTNAME'];
        $lastName = $params['LASTNAME'];
        $phoneNumber = $params['PHONENO'];
        $phoneVerified = $params['PHONENOVERIFIED'];
        $email = $params['EMAIL'];
        $emailVerified = $params['EMAILVERIFIED'];
        $loginPass = $params['LOGINPWD'];
        $acceptTerms = $params['ACCEPTEDTERMSCONDITIONS'];
        $userTypeId = $params['USERTYPEID'];
        $referenceCode = $params['REFERENCECODE'];
        $gstn = $params['GSTN'];

        $sql = "CALL hand2handservices.ADDUSER('" . $firstName . "', '" . $lastName . "','" . $phoneNumber . "','" . $phoneVerified . "','" . $email . "','" . $emailVerified . "','" . $loginPass . "','" . $acceptTerms . "', '" . $userTypeId . "', '" . $referenceCode . "','" . $gstn . "',@v_couriercompanyid:=null,@v_output_userid);";

//            $sql = "CALL hand2handservices.ADDUSER('".$this->db->escape($firstName)."', '".$this->db->escape($lastName)."','".$this->db->escape($phoneNumber)."','".$this->db->escape($phoneVerified)."','".$this->db->escape($email)."','".$this->db->escape($emailVerified)."','".$this->db->escape($loginPass)."','".$this->db->escape($acceptTerms)."', '".$this->db->escape($userTypeId)."', '".$this->db->escape($referenceCode)."','".$this->db->escape($gstn)."',@v_couriercompanyid:=null,@v_output_userid);";
//          $query = $this->db->query($sql,array($firstName , $lastName , $phoneNumber, $phoneVerified  , $email, $emailVerified  , $loginPass, $acceptTerms, $userTypeId, $referenceCode, $gstn ));
//          $query = $this->db->query($sql,array($firstName , $lastName , $phoneNumber, $phoneVerified  , $email, $emailVerified  , $loginPass, $acceptTerms, $userTypeId, $referenceCode, $gstn ));
        $query1 = $this->db->query($sql);
        $userID = $this->db->query("Select @v_output_userid as userid");
        $result = $userID->result_array();
        if (isset($result[0]['userid']) && $result[0]['userid'] == 0) {
            return array('status' => 0, 'userMsg' => 'Unknown error from database.');
        } else {
            return array('status' => 200, 'userMsg' => '', 'data' => $result);
        }


//            return $query->result_array(); 
    }

}
