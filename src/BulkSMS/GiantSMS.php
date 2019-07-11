<?php

/********************************** Giant SMS ***********************************
 * *******************************************************************************
 * * Copyright (c) FingerGiant Ltd 2018. All rights reserved                    **
 * * Version       1.0                                                          **
 * * Email         manuelxtrem@fingergiant.com                                  **
 * * Web           http://giantsms.com/                                         **
 * * Web           http://fingergiant.com/                                      **
 * *******************************************************************************
 * ******************************************************************************/

namespace BulkSMS;

use \Curl\Curl;
use BulkSMS\Model\SMSResponse;

class GiantSMS {

	private $username;
	private $password;

    function __construct($apiUser, $apiSecret) {
        $this->username = $apiUser;
        $this->password = $apiSecret;
    }

	/**
	 * @return BulkSMS\Model\SMSResponse 
	 */
	public function send($text, $recipient, $sender) 
	{ 
		if(empty($text) || empty($recipient) || empty($sender)) {
			throw('You should specify the message, recipient and sender parameters');
		}

        $curl = new Curl();
        $curl->setBasicAuthentication($this->username, $this->password);
        $curl->post($this->buildUrl('send'), array(
            'from' => $sender,
            'to' => $recipient,
            'msg' => $text,
        ));

        if ($curl->error) {
            return new SMSResponse(['status' => 'false', 'message' => $curl->errorMessage]);
        } else {
            return new SMSResponse($curl->response);
        }
	} 
    
	/**
	 * @return BulkSMS\Model\SMSResponse 
	 */
	public function balance() 
	{
        $curl = new Curl();
        $curl->setBasicAuthentication($this->username, $this->password);
        $curl->get($this->buildUrl('balance'), array(
            'username' => $this->username,
            'password' => $this->password,
        ));

        if ($curl->error) {
            return new SMSResponse(['status' => 'false', 'message' => $curl->errorMessage]);
        } else {
            return new SMSResponse($curl->response);
        }
    }
    
    private function buildUrl($url) {
        return 'https://giantsms.com/servicer/api/v1/' . $url;
    }
}
