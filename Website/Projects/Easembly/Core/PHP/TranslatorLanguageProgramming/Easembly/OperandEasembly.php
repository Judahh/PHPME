<?php

/**
 * Created by PhpStorm.
 * User: Judah
 * Date: 11/25/15
 * Time: 23:09
 */
class OperandEasembly{
    private $stringValue;
    private $intBit;
    private $booleanIsAddress;
    private $booleanIsLabel;
    private $booleanIsNumber;
    private $booleanIsBit;
    private $intNumberType;//0=bin,1=dec,2=hex


    protected function __construct($value,$isAddress,$isNumber,$isLabel,$isBit,$bit,$numberType) {
        $this->stringValue=$value;
        $this->booleanIsAddress=$isAddress;
        $this->booleanIsNumber=$isNumber;
        $this->booleanIsLabel=$isLabel;
        $this->booleanIsBit=$isBit;
        $this->intBit=$bit;
        $this->intNumberType=$numberType;
//        if($isAddress) {
//            error_log ("----------New----------");
//            error_log ("Value:".$value."_END");
//            error_log ("Add:".$isAddress."_END");
//            error_log ("Num:".$isNumber."_END");
//            error_log ("Lab:".$isLabel."_END");
//            error_log ("Type:".$numberType."_END");
//        }
    }

    public static function OperandEasemblyWithStringValue($value){
        $tempOperand=$value;
        $tempIsAddress=false;
        $tempIsNumber=false;
        $tempIsLabel=false;
        $tempNumberType=0;
        if(strcmp(substr($tempOperand, 0, 1),"@")==0){
            $tempOperand=substr($tempOperand, 1);
            $tempIsAddress=true;
        }
        if(strcmp(substr($tempOperand, 0, 3),"bin")==0){
            $tempOperand=substr($tempOperand, 3);
            $tempIsNumber=true;
            $tempNumberType=0;
        }
        if(strcmp(substr($tempOperand, 0, 3),"dec")==0){
            $tempOperand=substr($tempOperand, 3);
            $tempIsNumber=true;
            $tempNumberType=1;
        }
        if(strcmp(substr($tempOperand, 0, 3),"hex")==0){
            $tempOperand=substr($tempOperand, 3);
            $tempIsNumber=true;
            $tempNumberType=2;
        }
        $dotPosition=strpos($tempOperand,'.');
        if($dotPosition){
            $tempBit=intval(substr($tempOperand, $dotPosition+1));
            $tempOperand=substr($tempOperand, 0,$dotPosition);
            $tempIsBit=true;
            $tempNumberType=2;
        }
        if(!$tempIsNumber&&!$tempIsAddress){
            $tempIsLabel=true;
        }
        $operandEasembly = new self($tempOperand,$tempIsAddress,$tempIsNumber,$tempIsLabel,$tempIsBit,$tempBit,$tempNumberType);
        return $operandEasembly;
    }

    /**
     * @return mixed
     */
    public function getStringValue(){
        return $this->stringValue;
    }

    /**
     * @param mixed $stringValue
     */
    public function setStringValue($stringValue){
        $this->stringValue = $stringValue;
    }

    /**
     * @return mixed
     */
    public function getBooleanIsAddress(){
        return $this->booleanIsAddress;
    }

    /**
     * @param mixed $booleanIsAddress
     */
    public function setBooleanIsAddress($booleanIsAddress){
        $this->booleanIsAddress = $booleanIsAddress;
    }

    /**
     * @return mixed
     */
    public function getBooleanIsLabel()
    {
        return $this->booleanIsLabel;
    }

    /**
     * @param mixed $booleanIsLabel
     */
    public function setBooleanIsLabel($booleanIsLabel)
    {
        $this->booleanIsLabel = $booleanIsLabel;
    }

    /**
     * @return mixed
     */
    public function getBooleanIsNumber()
    {
        return $this->booleanIsNumber;
    }

    /**
     * @param mixed $booleanIsNumber
     */
    public function setBooleanIsNumber($booleanIsNumber)
    {
        $this->booleanIsNumber = $booleanIsNumber;
    }

    /**
     * @return mixed
     */
    public function getIntNumberType()
    {
        return $this->intNumberType;
    }

    /**
     * @param mixed $intNumberType
     */
    public function setIntNumberType($intNumberType)
    {
        $this->intNumberType = $intNumberType;
    }

    /**
     * @return mixed
     */
    public function getIntBit()
    {
        return $this->intBit;
    }

    /**
     * @param mixed $intBit
     */
    public function setIntBit($intBit)
    {
        $this->intBit = $intBit;
    }

    /**
     * @return mixed
     */
    public function getBooleanIsBit()
    {
        return $this->booleanIsBit;
    }

    /**
     * @param mixed $booleanIsBit
     */
    public function setBooleanIsBit($booleanIsBit)
    {
        $this->booleanIsBit = $booleanIsBit;
    }
}