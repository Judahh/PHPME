<?php
//include 'OperationEasembly.php';
include 'InstructionEasembly.php';
/**
 * Created by PhpStorm.
 * User: Judah
 * Date: 11/16/15
 * Time: 08:30
 */
class DataDefinitionEasembly extends OperationEasembly{
    private $stringName;
    private $arrayOperation;//optional
    private $booleanEnd;

    protected function __construct($name) {
        $this->arrayOperation=array();
        $this->stringName=$name;
        $this->booleanEnd=false;
    }

    public static function DataDefinitionEasembly() {
        return new self("");
    }

    public static function DataDefinitionEasemblyWithStringName($name) {
        return new self(trim($name));
    }

    public function intAddToDefinitionWithIntIndexWithStringTextWithDataDefinitionEasembly($intIndex,$text){
//        $instructionBasicEnd="\n";
        $instructionEnd=";";
        $definitionBasicStart=":";
        $definitionStart="{";
        $definitionEnd="}";

        $arrayCommands=array();

        $initial=$intIndex;
        for($index=$initial;$index<strlen($text);$index++){
            $char=substr($text,$index,1);
            switch($char){
                case $definitionBasicStart:
                case $definitionStart:
//                    error_log ("DefinitionStart:");
//                    error_log ("Definition:".substr($text,$intIndex,$index-$intIndex)."_END");
                    $dataDefinitionEasemblyNew=DataDefinitionEasembly::DataDefinitionEasemblyWithStringName(substr($text,$intIndex,$index-$intIndex));
//                    if($index+1<strlen($text)) {
                        $index = $dataDefinitionEasemblyNew->intAddToDefinitionWithIntIndexWithStringTextWithDataDefinitionEasembly($index + 1, $text);
//                    }
//                    error_log ("Index:".$index."_END");
                    array_push($arrayCommands, $dataDefinitionEasemblyNew);
                    $index++;
                    $intIndex=$index;
                    break;

//                case $instructionBasicEnd:
                case $instructionEnd:
//                    error_log ("Text:".$text);
//                    error_log ("Start:".$intIndex);
//                    error_log ("Size:".$index);
                    $instructionBasic=substr($text,$intIndex,$index-$intIndex);
//                    error_log ("Result:".$instructionBasic);
                    $instruction=InstructionEasembly::InstructionEasemblyWithStringText($instructionBasic);
                    array_push($arrayCommands, $instruction);
                    $index++;
                    $intIndex=$index;
                    break;

                case $definitionEnd:
//                    $this->setArrayOperation($arrayCommands);
                    $this->booleanEnd=true;
                    return $index;
                    break;
            }
            $this->setArrayOperation($arrayCommands);
        }
        return $index;
    }

    /**
     * @return mixed
     */
    public function getArrayOperation()
    {
        return $this->arrayOperation;
    }

    public function getAssembly($fileJSONAssembly){
        if(strcmp($this->stringName,"")!=0){
            $stringAssemblyDataDefinition=$this->stringName.":\n";
        }
        for($index=0;$index<count($this->arrayOperation);$index++){
            $stringAssemblyDataDefinition=$stringAssemblyDataDefinition.$this->arrayOperation[$index]->getAssembly($fileJSONAssembly);
        }
        if($this->booleanEnd){
            $stringAssemblyCommand="";
            $path="../../TranslatorLanguageProgramming/".$fileJSONAssembly.".JSON";

            $fileJSONAssembly = fopen($path, "r+");// or die("Unable to open file!");
            $contents = fread($fileJSONAssembly, filesize($path));
            fclose($fileJSONAssembly);;
            $file = json_decode($contents, true);

            $command=$file['command'];
            for($index=0;$index<count($command);$index++){
                $temp=$command[$index];
                if(strcmp($temp['name'], "return")==0){
                    $command=$temp['variation'][0]['command'][0]['mnemonic'];
//                    error_log("COMMAND I:".$index);
//                    error_log("COMMAND:".$command);
                    $stringAssemblyDataDefinition=$stringAssemblyDataDefinition.$command." \n";
                    break;
                }
            }
        }
        return $stringAssemblyDataDefinition;//."size".count($this->arrayOperation);
    }

    /**
     * @param mixed $arrayOperation
     */
    public function setArrayOperation($arrayOperation)
    {
        $this->arrayOperation = $arrayOperation;
    }

    /**
     * @return mixed
     */
    public function getStringName()
    {
        return $this->stringName;
    }

    /**
     * @param mixed $stringName
     */
    public function setStringName($stringName)
    {
        $this->stringName = $stringName;
    }
}