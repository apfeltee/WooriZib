<?php

class PLParamV2
{
    ////////////////////////////////////////
    //Constant.
    ////////////////////////////////////////
    //var $PLPARAM_BIN_PATH = "/home/hosting_users/dabangbs/www/Lib/"; //바이너리 파일의 서버 절대 경로
	var $PLPARAM_BIN_PATH = "Lib/"; //바이너리 파일의 서버 절대 경로
    var $PLPARAM_BIN_CRYPT = "plaesgcm"; // 32bit : plaesgcm,  64bit: plaesgcm_x64
    var $PLPARAM_FIELD_DELEMITER = "";
    var $PLPARAM_VALUE_DELEMITER = "";
	var $PLPARAM_ENCTEXT_SET_FIELD = "resultparam";
	var $PLPARAM_BLANK_STR = "";
    ////////////////////////////////////////
    //internal variables.
    ////////////////////////////////////////
    var $DataParam;
    var $ReceivedDataParam;
    var $KeyID;
	var $PVKey;
	var $KeyVer;


    //Constructor.
    function PLParamV2()
    {
        //SetParam for dummy.
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

    function Decrypt($strClientParam)
    {
		$arrCT = explode('|',$strClientParam);

		if(count($arrCT) == 4)
		{
			$strClientParam = $arrCT[3];
		}
		else if(count($arrCT) == 1)
		{
			$strClientParam = $arrCT[0];
		}
		else
		{
			$this->ReceivedDataParam = FALSE;	
			return;
		}

		$Command = "";
        $Result = "";
        $Command = $this->PLPARAM_BIN_PATH.$this->PLPARAM_BIN_CRYPT." d ".$this->PVKey." HEX ".$strClientParam;
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

        if($Result != "")
		{
            $this->ReceivedDataParam = $Result;
		}
        else
		{
            $this->ReceivedDataParam = FALSE;
		}
    }

	function Encrypt()
    {
        $Command = "";
		$Result = "";

		$Command = $this->PLPARAM_BIN_PATH.$this->PLPARAM_BIN_CRYPT." e ".$this->PVKey." HEX '".$this->DataParam."'";

		$Result = exec($Command);
	
		if($Result != "")
		{
			$this->DataParam = $this->KeyID."|".$this->KeyVer."|2|".$Result;
		}
		else
		{
			$this->DataParam = $Result;
		}
		
		return($this->DataParam);
    }
    
    function SetParam($strFieldName, $strFieldValue)
    {
        if($strFieldName==$this->PLPARAM_ENCTEXT_SET_FIELD)
			$this->Decrypt($strFieldValue);
		else
			$this->DataParam .= $this->PLPARAM_FIELD_DELEMITER.$strFieldName.$this->PLPARAM_VALUE_DELEMITER.$strFieldValue;
    }
    
    function GetParam($strFieldName)
    {
		$SearchStr = "";
        $strTmp1 = "";
        $strTmp2 = "";
        $find1pos = 0;
        $find2pos = 0;
        
        $SearchStr = $this->PLPARAM_FIELD_DELEMITER.$strFieldName.$this->PLPARAM_VALUE_DELEMITER;

        if ( ($strTmp1 = stristr($this->ReceivedDataParam, $SearchStr)) == FALSE )
        {
			return ($this->PLPARAM_BLANK_STR);
        }
        else
        {
            $strTmp1 = substr($strTmp1,strlen($SearchStr));
            
			if ( ($strTmp2 = stristr($strTmp1, $this->PLPARAM_FIELD_DELEMITER))== FALSE )
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

}

?>