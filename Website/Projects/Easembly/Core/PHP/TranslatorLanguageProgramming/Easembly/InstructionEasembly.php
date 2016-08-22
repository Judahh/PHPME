<?php
include 'OperationEasembly.php';
include 'OperandEasembly.php';
/**
 * Created by PhpStorm.
 * User: Judah
 * Date: 11/16/15
 * Time: 08:29
 */
class InstructionEasembly extends OperationEasembly{
    private $command;
    private $arrayOperand;

    protected function __construct() {
        $this->arrayOperand=array();
    }

    private function multiexplode($delimiters,$string) {

        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }

    public static function InstructionEasemblyWithStringText($text){
        $instructionEasembly = new self();
        $index=0;
//        error_log ("Text:".$text);
        for(;$index<strlen($text);$index++){
            $char=substr($text,$index,1);
            if(strcmp($char, " ")==0||strcmp($char, "\n")==0||strcmp($char, "\t")==0||strcmp($char, "(")==0){
                $string=substr($text,0,$index);

                if($string==null||strlen($string)==0||strlen(trim($string))==0){

                }else {
                    $instructionEasembly->setCommand(trim($string));
                    break;
                }
            }
        }
        $string=trim($string);
//        error_log ("String:".$string."END");
        $newString=substr($text,$index,strlen($text)-$index);
        $exploded = $instructionEasembly->multiexplode(array(" ","\n","\t",",","(",")",";"),$newString);

        $tempArrayOperand=array();
        for($index=0;$index<count($exploded);$index++){
            $tempOperand=$exploded[$index];
            $tempOperand=trim($tempOperand);

            if(!($tempOperand==null||strlen($tempOperand)==0||strlen(trim($tempOperand))==0)){
                $newOperand=OperandEasembly::OperandEasemblyWithStringValue($tempOperand);
                array_push($tempArrayOperand, $newOperand);
            }
        }
        $instructionEasembly->setArrayOperand($tempArrayOperand);
        return $instructionEasembly;
    }

    /**
     * @return mixed
     */
    public function getCommand(){
        return $this->command;
    }

    private function getAssemblyOperand($index,$link,$fileAssembly,$linkType){
//        error_log ("OP");
        $operand=$this->getArrayOperandStringValueWithIndex($index);
        $file=$fileAssembly['operand'];
        for($index=0;$index<count($file);$index++){
            if(strcmp($file[$index]["Easembly"], $operand)==0){
                return $file[$index]["Assembly".$linkType];
            }
        }
        return null;
    }

    public function getAssembly($fileJSONAssembly){
        $stringAssemblyCommand="";
        $path="../../TranslatorLanguageProgramming/".$fileJSONAssembly.".JSON";

        $fileJSONAssembly = fopen($path, "r+");// or die("Unable to open file!");
        $contents = fread($fileJSONAssembly, filesize($path));
        fclose($fileJSONAssembly);;
        $file = json_decode($contents, true);

        $command=$file['command'];
        $rightComplexType = -1;
        $complex=false;
        for($index=0;$index<count($command);$index++){
            $temp=$command[$index];
//            error_log ("Name:".$temp['name']."END");
            if(strcmp($temp['name'], $this->command)==0){
                $complex=$temp['complex'];
                break;
            }
        }

        $bitIsFirst=$file['bit']['first'];
        $bitSeparator=$file['bit']['separator'];

        $command=$temp['variation'];
//        error_log ("START:");
        $stringAssemblyCommandPr="";
        for($index=0;$index<count($command);$index++){
            $numberOfOperands=$command[$index]['numberOfOperands'];
            $lastIndexBoolean=false;
            $lastIndex=0;
            if($numberOfOperands==count($this->getArrayOperand())){
                $assemblyCommand=$command[$index]['command'];
                for($index2=0;$index2<count($assemblyCommand);$index2++) {
                    $assemblyCommandCurrent = $assemblyCommand[$index2];
                    $name = $assemblyCommandCurrent['mnemonic'];
                    $complexType = $assemblyCommandCurrent['complexType'];

                    $stringAssemblyCommandTemp = null;
                    if ($rightComplexType == -1 || $rightComplexType == $complexType) {
                        $operand = $assemblyCommandCurrent['operand'];
                        $stringAssemblyCommandTemp = $stringAssemblyCommand . $name . "\t ";
                        for ($index3 = 0; $index3 < count($operand); $index3++) {
                            $isBit = $operand[$index3]['isBit'];
                            if ($isBit == null) {
                                $isBit = false;
                            }
                            $link = $operand[$index3]['link'];
                            if ($link == null) {
                                $link = $this->getArrayOperandStringValueWithIndex($index3);
                                if($lastIndexBoolean){
                                    $link = $this->getArrayOperandStringValueWithIndex($lastIndex);
                                }
                            }
                            $isLink = $operand[$index3]['isLink'];
                            if ($isLink == null) {
                                $isLink = false;
                            }
                            $linkType = $operand[$index3]['linkType'];
                            if ($linkType == null) {
                                $linkType = "";
                            }
                            $isAddress = $operand[$index3]['isAddress'];
                            if ($isAddress == null) {
                                $isAddress = false;
                            }

                            if($isBit!=$this->getArrayOperandBooleanIsBitWithIndex($index3)){
                                break;
                            }

//                            error_log ("link:".$link."_END");
//                            error_log ("isLink:".$isLink."_END");

                            if ($isLink) {

                                $assemblyOperand = $this->getAssemblyOperand($index3, $link, $file, $linkType);
                                if ($assemblyOperand != null) {
                                    if(($isAddress && $this->getArrayOperandBooleanIsAddressWithIndex($index3))||
                                        (!$isAddress && !$this->getArrayOperandBooleanIsAddressWithIndex($index3))){
                                        if($isAddress) {
//                                            error_log("name:" . $name . "_END");
                                            $pointer = $file['pointer'];
                                        }else{
                                            $pointer = $file['regular'];
                                        }
                                        $pointer=$pointer['register'];
                                        $assemblyOperand=$pointer['before'].$assemblyOperand.$pointer['after'];
                                    }else{

                                        $stringAssemblyCommandTemp = null;
                                        break;
                                    }
                                    if($isBit){
//                                        error_log ("BIT AND LINK:".$assemblyOperand."_END");
                                        if($bitIsFirst){
                                            $assemblyOperand=$this->getArrayOperandIntBitWithIndex($index3).$bitSeparator.$assemblyOperand;
                                        }else{
                                            $assemblyOperand=$assemblyOperand.$bitSeparator.$this->getArrayOperandIntBitWithIndex($index3);
                                        }
                                    }
                                    $stringAssemblyCommandTemp = $stringAssemblyCommandTemp . $assemblyOperand;
                                } else {
//                                    error_log ("NULL!");
                                    $stringAssemblyCommandTemp = null;
                                    break;
                                }
                            } else {
                                if(($isAddress && $this->getArrayOperandBooleanIsAddressWithIndex($index3) && !$this->getArrayOperandBooleanIsLabelWithIndex($index3))||
                                    (!$isAddress && !$this->getArrayOperandBooleanIsAddressWithIndex($index3) && !$this->getArrayOperandBooleanIsLabelWithIndex($index3))){
                                    if($this->getArrayOperandBooleanIsNumberWithIndex($index3)){
                                        $number=$file['number'];
                                        switch($this->getArrayOperandIntNumberTypeWithIndex($index3)){
                                            case 1:
                                                $number=$number['decimal'];
                                                break;
                                            case 2:
                                                $number=$number['hexadecimal'];
                                                break;
                                            default:
                                                $number=$number['binary'];
                                                break;
                                        }
                                        $link=$number['before'].$link.$number['after'];
                                    }
                                    if($isAddress && $this->getArrayOperandBooleanIsAddressWithIndex($index3)) {
                                        $pointer = $file['pointer'];
                                    }else{
                                        $pointer = $file['regular'];
                                    }
                                    $pointer=$pointer['other'];

                                    $link=$pointer['before'].$link.$pointer['after'];
                                }else{
                                    if($this->getArrayOperandBooleanIsLabelWithIndex($index3)){

                                    }elseif($this->getArrayOperandBooleanIsNumberWithIndex($index3)){
                                        $number=$file['number'];
                                        switch($this->getArrayOperandIntNumberTypeWithIndex($index3)){
                                            case 1:
                                                $number=$number['decimal'];
                                            break;
                                            case 2:
                                                $number=$number['hexadecimal'];
                                            break;
                                            default:
                                                $number=$number['binary'];
                                            break;
                                        }
                                        $link=$number['before'].$link.$number['after'];
                                    }else{
                                        $stringAssemblyCommandTemp = null;
                                        break;
                                    }
                                }
                                if($isBit){
//                                    error_log ("BIT AND NO LINK:".$link."_END");
                                    if($bitIsFirst){
                                        $link=$this->getArrayOperandIntBitWithIndex($index3).$bitSeparator.$link;
                                    }else{
                                        $link=$link.$bitSeparator.$this->getArrayOperandIntBitWithIndex($index3);
                                    }
                                }
                                $stringAssemblyCommandTemp = $stringAssemblyCommandTemp . $link;
                            }

                            if ($index3 < count($operand) - 1) {
                                $stringAssemblyCommandTemp = $stringAssemblyCommandTemp . ",";
                            } else {
                                $stringAssemblyCommandTemp = $stringAssemblyCommandTemp . "\n";
                            }

                            $lastIndex=$index3;
                        }

                        if(count($operand)==0){
                            if(count($this->getArrayOperand())==0){
                                if(strcmp(substr($stringAssemblyCommandTemp, strlen($stringAssemblyCommandTemp)-1),"\n")!=0) {
                                    $stringAssemblyCommandTemp = $stringAssemblyCommandTemp . "\n";
                                }
                            }
                        }
                    }

                    if ($stringAssemblyCommandTemp != null && strcmp($stringAssemblyCommandTemp, "")!=0) {
                        $rightComplexType = $complexType;
                        if($stringAssemblyCommandPr != null && strcmp($stringAssemblyCommandPr, "")!=0 && $complex){
                            $stringAssemblyCommandPr = $stringAssemblyCommandPr.$stringAssemblyCommandTemp; //. "\n";
                        }else{
                            $stringAssemblyCommandPr = $stringAssemblyCommandTemp;
                        }

                    }else{
                        if($rightComplexType == $complexType){//bug
                            $rightComplexType=-1;
                            if($stringAssemblyCommandPr != null && strcmp($stringAssemblyCommandPr, "")!=0 && $complex) {
                                $stringAssemblyCommandPr = $stringAssemblyCommandPr.$stringAssemblyCommand;
                            }else{
                                $stringAssemblyCommandPr = $stringAssemblyCommand;
                            }
                        }
                    }
//                    if($complex && $index2 == count($assemblyCommand)-1){
//                        $stringAssemblyCommandPr=$stringAssemblyCommandPr."\n";
//                    }

                }
                if($complex || $stringAssemblyCommandPr == null || strcmp($stringAssemblyCommandPr, "")==0) {
                    $lastIndexBoolean=true;
                }else{
                    break;
                }
            }

        }
        $stringAssemblyCommand=$stringAssemblyCommandPr;
        return $stringAssemblyCommand;
    }

    /**
     * @param mixed $command
     */
    public function setCommand($command){
        $this->command = $command;
    }

    /**
     * @return mixed
     */
    public function getArrayOperand(){
        return $this->arrayOperand;
    }

    /**
     * @return mixed
     */
    public function getArrayOperandWithIndex($index){
        return $this->arrayOperand[$index];
    }

    public function debug_string_backtrace() {
        ob_start();
        debug_print_backtrace();
        $trace = ob_get_contents();
        ob_end_clean();

        // Remove first item from backtrace as it's this function which
        // is redundant.
        $trace = preg_replace ('/^#0\s+' . __FUNCTION__ . "[^\n]*\n/", '', $trace, 1);

        // Renumber backtrace items.
        $trace = preg_replace ('/^#(\d+)/me', '\'#\' . ($1 - 1)', $trace);

        return $trace;
    }

    /**
     * @return mixed
     */
    public function getArrayOperandStringValueWithIndex($index){
//        error_log ($this->debug_string_backtrace());
//        error_log ("OperandString:".$this->arrayOperand[$index]->getStringValue()."_END");
        return $this->arrayOperand[$index]->getStringValue();
    }

    /**
     * @return mixed
     */
    public function getArrayOperandBooleanIsAddressWithIndex($index){
        return $this->arrayOperand[$index]->getBooleanIsAddress();
    }

    public function getArrayOperandBooleanIsNumberWithIndex($index){
        return $this->arrayOperand[$index]->getBooleanIsNumber();
    }

    public function getArrayOperandBooleanIsLabelWithIndex($index){
        return $this->arrayOperand[$index]->getBooleanIsLabel();
    }

    public function getArrayOperandBooleanIsBitWithIndex($index){
        return $this->arrayOperand[$index]->getBooleanIsBit();
    }

    public function getArrayOperandIntBitWithIndex($index){
        return $this->arrayOperand[$index]->getIntBit();
    }

    public function getArrayOperandIntNumberTypeWithIndex($index){
        return $this->arrayOperand[$index]->getIntNumberType();
    }

    /**
     * @param mixed $arrayOperand
     */
    public function setArrayOperand($arrayOperand){
        $this->arrayOperand = $arrayOperand;
    }

}