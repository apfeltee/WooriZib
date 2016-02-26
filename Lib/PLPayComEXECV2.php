<?php

class PLPayComV2
{
    ////////////////////////////////////////
    //Constant.
    ////////////////////////////////////////
    var $PLPAY_BIN_PATH = "Lib/"; 
    var $PLPAY_BIN_CRYPT = "plaesgcm"; // 32bit : plaesgcm,  64bit: plaesgcm_x64
    var $PLPAY_FIELD_DELEMITER = "";
    var $PLPAY_VALUE_DELEMITER = "";
    var $PLPAY_TXMODE_TEST = "test";
    var $PLPAY_TXMODE_REAL = "real";
    var $PLPAY_SA001_PG_SVR = "pg2.payletter.com";
    var $PLPAY_SM001_PG_SVR = "pg3.payletter.com";
	var $PLPAY_TEST_PG_SVR  = "testpg.payletter.com";
    var $PLPAY_DEFAULT_PG_SVR_PORT = 8009;
    var $PLPAY_MOBILE_PG_SVR_PORT = 8010;
    var $PLPAY_WOWARS_PG_SVR_PORT = 8011;
    var $PLPAY_COUPON_PG_SVR_PORT = 8015;
    var $PLPAY_DEFAULT_TIMEOUT = 30;
    var $PLPAY_SUCCESS_CODE = "0000";
    var $PLPAY_SUCCESS = 0;
    var $PLPAY_DEFAULT_ENCVER = "100";
    var $PLPAY_BLANK_STR = "";
    var $PLPAY_BUFFER_SIZE = 20480;

    var $PLPAY_SOCKERR_201 = -201;
    var $PLPAY_SOCKERR_201_MSG = "Socket Open 에러";
    var $PLPAY_SOCKERR_220 = -220;
    var $PLPAY_SOCKERR_220_MSG = "send 에러(보내고자하는 길이보다 적게 전송)";
    var $PLPAY_SOCKERR_250 = -250;
    var $PLPAY_SOCKERR_250_MSG = "recv 에러";

    ////////////////////////////////////////
    //external properties.
    ////////////////////////////////////////
    //Get Property.
    var $ErrMsg;
    var $RequestParam;
    
    //Put Property.
    var $ClientID;
    var $HOST;
    var $PGName;
    var $ServiceName;
    var $Timeout;
    var $TxCmd;
    var $TxMode;
    var $UserID;
    var $UserIP;
    ////////////////////////////////////////
    //internal variables.
    ////////////////////////////////////////
    var $DataParam;
    var $hostip;
    var $hostport;
    var $Socket = null;
    var $ReceivedParam;
    var $ReceivedDataParam;
    var $IsEncrypted = "Y";
    var $KeyID;
	var $PVKey;
	var $KeyVer;

    //Constructor.
    function PLPayComV2()
    {
        //Nothing to do.
    }
    
	function SetKey($strPVKey)
	{
		$this->PVKey = $strPVKey;
	}

	function SetKeyID($strKeyID)
	{
		$this->KeyID = $strKeyID;
	}

	function SetKeyVer($strKeyVer)
	{
		$this->KeyVer = $strKeyVer;
	}

    function Decrypt()
    {
        $Command = "";
        $Result = "";
        $Command = $this->PLPAY_BIN_PATH.$this->PLPAY_BIN_CRYPT." d ".$this->PVKey." HEX ".$this->ReceivedDataParam;

		exec($Command,$arrResult);

		for($i=0;$i<count($arrResult);$i++)
		{
			if($i < count($arrResult)-1)
			{
				$Result = $Result.$arrResult[$i]."\n";
			}
			else
			{
				$Result = $Result.$arrResult[$i];
			}
		}

        return $Result;
    }
    
    function SetField($strFieldName, $strFieldValue)
    {
        $this->DataParam .= $this->PLPAY_FIELD_DELEMITER.$strFieldName.$this->PLPAY_VALUE_DELEMITER.$strFieldValue;
    }
    
    function StartAction()
    {
        $intRetVal = 0;
        $strParamLen = 0;
        
		//Make final Request Param.
        $this->SetField("userid",$this->UserID);
        $this->SetField("userip",$this->UserIP);
        $this->SetField("pgnm",$this->PGName);
        $this->SetField("svcnm",$this->ServiceName);
        $this->SetField("clientid",$this->ClientID);
		$this->SetField("plp", "2"); //PHP Platform Default : 2

        if ( $this->IsEncrypted == "Y")
        {
            $Command = "";
            $Result = "";
            $Command = $this->PLPAY_BIN_PATH.$this->PLPAY_BIN_CRYPT." e ".$this->PVKey." HEX '".$this->DataParam."'";
            $Result = exec($Command);
   
            if ($Result != "")
            {
                $this->DataParam = $Result;
            }
            else
            {
                $this->IsEncrypted = "N";   
                $this->KeyVer = "000";
            }
        }
        else
        {
            $this->KeyVer = "000";
        }

        $this->RequestParam = sprintf("%-16.16s%-4.4s%s%s%-16.16s%s", $this->TxCmd, $this->PLPAY_SUCCESS_CODE, $this->IsEncrypted, $this->KeyVer, $this->KeyID, $this->DataParam);
        $strParamLen = sprintf("%05d",strlen($this->RequestParam)+5);
        $this->RequestParam = sprintf("%s%s", $strParamLen, $this->RequestParam);

        $intRetVal = $this->OpenServer();

        if ( $intRetVal != $this->PLPAY_SUCCESS )
        {
            if (!$this->Socket)
                fclose($this->Socket);
                
            return($intRetVal);
        }
     
        $intRetVal = $this->SendRecvCommand();

        if(!$this->Socket)
            fclose($this->Socket);
        
        if ( $intRetVal != $this->PLPAY_SUCCESS )
        {
            return($intRetVal);
        }
        else
        {
            $this->ReceivedDataParam = substr($this->ReceivedParam, 45 );

            if ( $this->ReceivedParam[25] == 'Y' || $this->ReceivedParam[25] == 'y' )
            {
				$this->ReceivedDataParam = $this->Decrypt();
            }
        }
        
        $intRetVal = intval(substr($this->ReceivedParam,21,4));
        if ( $intRetVal != $this->PLPAY_SUCCESS )
        {
            $this->ErrMsg = $this->GetVal("errmsg");
        }
            
        return($intRetVal);

    }
 
    function OpenServer()
    {
        $intRetVal;
        
        if ($this->HOST != "")
        {
            $ipinfo = explode(":",$this->HOST); 
            $this->hostip   = $ipinfo[0];
            $this->hostport = $ipinfo[1];
        }
        else
        {
            if ( !strcasecmp($this->TxMode ,$this->PLPAY_TXMODE_TEST) )
            {
                $this->hostip = $this->PLPAY_TEST_PG_SVR;
                $this->hostport = $this->PLPAY_DEFAULT_PG_SVR_PORT;
            }
            else
            {
                if ( !strcasecmp($this->PGName, "wowcoin") )
                {
                    $this->hostip = $this->PLPAY_SM001_PG_SVR;
                    $this->hostport = $this->PLPAY_MOBILE_PG_SVR_PORT;
                }
                else if ( !strcasecmp($this->PGName, "wow_ars") || !strcasecmp($this->PGName, "wow_1588") )
                {
                    $this->hostip = $this->PLPAY_SM001_PG_SVR;
                    $this->hostport = $this->PLPAY_WOWARS_PG_SVR_PORT;
                }
                else if ( !strcasecmp($this->PGName, "phonebill") || !strcasecmp($this->PGName, "cyber_1588") || !strcasecmp($this->PGName, "cyber_pb") )
                {
                    $this->hostip = $this->PLPAY_SM001_PG_SVR;
                    $this->hostport = $this->PLPAY_DEFAULT_PG_SVR_PORT;
                }
                else if ( !strcasecmp($this->PGName, "culture") || !strcasecmp($this->PGName, "book") || !strcasecmp($this->PGName, "game") || !strcasecmp($this->PGName, "happymoney") || !strcasecmp($this->PGName, "oncash") || !strcasecmp($this->PGName, "teencash") || !strcasecmp($this->PGName, "cyber_cvs") )
                {
                    $this->hostip = $this->PLPAY_SA001_PG_SVR;
                    $this->hostport = $this->PLPAY_COUPON_PG_SVR_PORT;
                }
                else
                {
                    $this->hostip = $this->PLPAY_SA001_PG_SVR;
                    $this->hostport = $this->PLPAY_DEFAULT_PG_SVR_PORT;
                }
            }
        }
      
        $intRetVal = $this->OpenServerSub();

        if ( $intRetVal != $this->PLPAY_SUCCESS && !strcasecmp($this->hostip ,$this->PLPAY_TXMODE_TEST) )
        {
            if (!$this->Socket)
                fclose($this->Socket);
                
            if ( !strcasecmp($this->PGName, "wowcoin") )
            {
                $this->hostip = $this->PLPAY_SA001_PG_SVR;
                $this->hostport = $this->PLPAY_DEFAULT_PG_SVR_PORT;
            }
            else if ( !strcasecmp($this->PGName, "wow_ars") || !strcasecmp($this->PGName, "wow_1588") )
            {
                $this->hostip = $this->PLPAY_SA001_PG_SVR;
                $this->hostport = $this->PLPAY_DEFAULT_PG_SVR_PORT;
            }
            else if ( !strcasecmp($this->PGName, "phonebill") || !strcasecmp($this->PGName, "cyber_1588") || !strcasecmp($this->PGName, "cyber_pb") )
            {
                $this->hostip = $this->PLPAY_SA001_PG_SVR;
                $this->hostport = $this->PLPAY_DEFAULT_PG_SVR_PORT;
            }
            else if ( !strcasecmp($this->PGName, "culture") || !strcasecmp($this->PGName, "book") || !strcasecmp($this->PGName, "game") || !strcasecmp($this->PGName, "happymoney") || !strcasecmp($this->PGName, "oncash") || !strcasecmp($this->PGName, "teencash") || !strcasecmp($this->PGName, "cyber_cvs") )
            {
                $this->hostip = $this->PLPAY_SM001_PG_SVR;
                $this->hostport = $this->PLPAY_COUPON_PG_SVR_PORT;
            }
            else
            {
                $this->hostip = $this->PLPAY_SM001_PG_SVR;
                $this->hostport = $this->PLPAY_DEFAULT_PG_SVR_PORT;
            }

            $intRetVal = $this->OpenServerSub();
        }

        if ( $intRetVal != $this->PLPAY_SUCCESS )
        {
            $this->ErrMsg = $this->PLPAY_SOCKERR_201_MSG;
            return($this->PLPAY_SOCKERR_201);
        }
        
        return($this->PLPAY_SUCCESS);
    }   

    function OpenServerSub()
    {
        $ErrCode = $this->PLPAY_SUCCESS;
        $ErrMsg = "";
        $Timeout = $this->PLPAY_DEFAULT_TIMEOUT;
        
        if ( $this->Timeout > 0 )
            $Timeout = $this->Timeout;
        
        //$this->Socket = fsockopen($this->hostip, $this->hostport, &$ErrCode, &$ErrMsg, $Timeout); // version 5.0 이하
        $this->Socket = fsockopen($this->hostip, $this->hostport, $ErrCode, $ErrMsg, $Timeout); // version 5.0 이상. version 5.0 이상에서는 php ini 설정에서 allow_call_time_pass_reference ON 으로 켜져 있어야 합니다
        if(!$this->Socket)
        {
            $this->ErrMsg = $ErrMsg;
            return($ErrCode);
        }

        return($this->PLPAY_SUCCESS);
    }   


    function SendRecvCommand()
    {
        $intSendBytes = 0;
        
		$intSendBytes = fputs($this->Socket, $this->RequestParam, $this->PLPAY_BUFFER_SIZE);
        
        if ( $intSendBytes != strlen($this->RequestParam) )
        {
            $this->ErrMsg = $this->PLPAY_SOCKERR_220_MSG;
            return($this->PLPAY_SOCKERR_220);
        }

        $nTotalSize = fgets($this->Socket,6);
        if ( ($this->ReceivedParam = fgets($this->Socket, $nTotalSize-4)) == FALSE )
        {
            $this->ErrMsg = $this->PLPAY_SOCKERR_250_MSG;
            return($this->PLPAY_SOCKERR_250);
        }

        $this->ReceivedParam = $nTotalSize . $this->ReceivedParam;

        return($this->PLPAY_SUCCESS);
    }
    
    function GetVal($strFieldName)
    {
        $SearchStr = "";
        $strTmp1 = "";
        $strTmp2 = "";
        $find1pos = 0;
        $find2pos = 0;
        
        $SearchStr = $this->PLPAY_FIELD_DELEMITER.$strFieldName.$this->PLPAY_VALUE_DELEMITER;

        if ( ($strTmp1 = stristr($this->ReceivedDataParam, $SearchStr)) == FALSE )
        {
            return ($this->PLPAY_BLANK_STR);
        }
        else
        {
            $strTmp1 = substr($strTmp1,strlen($SearchStr));
            if ( ($strTmp2 = stristr($strTmp1, $this->PLPAY_FIELD_DELEMITER))== FALSE )
            {
                return(trim($strTmp1));
            }
            else
            {
                if ( strcasecmp($strTmp1,$strTmp2) )
                {
                    $find2pos = strlen($strTmp1)-strlen($strTmp2);
                    return(trim(substr($strTmp1,0,$find2pos)));
                }
                else
                    return("");
            }
        }
    }

    function EncryptString($str)
    {
        $Command = "";
        $Result = "";

        $Command = $this->PLPAY_BIN_PATH.$this->PLPAY_BIN_CRYPT." e ".$this->PVKey." HEX '".$str."'";
        $Result = exec($Command);

        return($Result);
    }

    function DecryptString($str)
    {
        $Command = "";
        $Result = "";
        $Command = $this->PLPAY_BIN_PATH.$this->PLPAY_BIN_CRYPT." d ".$this->PVKey." HEX ".$str;

        exec($Command,$arrResult);

        for($i=0;$i<count($arrResult);$i++)
        {
            if($i < count($arrResult)-1)
            {
                $Result = $Result.$arrResult[$i]."\n";
            }
            else
            {
                $Result = $Result.$arrResult[$i];
            }
        }

        return $Result;
    }
}

?>
