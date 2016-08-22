<?php
include 'DataDefinitionEasembly.php';
/**
 * Created by PhpStorm.
 * User: Judah
 * Date: 11/16/15
 * Time: 08:15
 */
class CodeEasembly{
    private $dataDefinition;
//    private $text;

    protected function __construct() {
        $this->dataDefinition=DataDefinitionEasembly::DataDefinitionEasembly();
    }

    public static function CodeEasembly() {
        return new self();
    }

    private function stringRemoveComments($text){
        $stringBasicStart="\'";
        $stringStart="\"";
        $commentStart="/";

        for($index=0;$index<strlen($text);$index++){
            $char=substr($text,$index,1);
            switch($char){
                case $stringBasicStart:
                case $stringStart:
                    if($index+1<strlen($text)) {
                        for ($index = $index + 1; $index < strlen($text); $index++) {
                            $char2 = substr($text, $index, 1);
                            if (strcmp($char, $char2)) {
                                break;
                            }
                        }
                    }
                    break;
                case $commentStart:
                    $initial=$index;
                    if($index+1<strlen($text)) {
                        $char2 = substr($text, $index + 1, 1);
                        if (strcmp($char, $char2)) {
                            if($index+1<strlen($text)) {
                                for ($index = $index + 1; $index < strlen($text); $index++) {
                                    $char2 = substr($text, $index, 1);
                                    if (strcmp($char2, "\n")) {
                                        break;
                                    }
                                }
                                $text= substr($text, 0, $initial).substr($text, $index);
                                $index=$initial;
                            }
                        }
                    }

                    break;
            }
        }
        return $text;
    }

    public static function CodeEasemblyWithText($text) {
        $codeEasembly=new self();
        $text=$codeEasembly->stringRemoveComments($text);

//        $codeEasembly->text=$text;

        $codeEasembly->dataDefinition=$codeEasembly->getDataDefinition();

        $codeEasembly->dataDefinition->intAddToDefinitionWithIntIndexWithStringTextWithDataDefinitionEasembly(0,$text);
        return $codeEasembly;
    }

//    public function getText(){
//        return $this->text;
//    }

    public function getArrayOperation(){
        return $this->dataDefinition->getArrayOperation();
    }


    public function getAssembly($fileJSONAssembly){
        return $this->dataDefinition->getAssembly($fileJSONAssembly);
    }

    /**
     * @return mixed
     */
    public function getDataDefinition(){
        return $this->dataDefinition;
    }

    /**
     * @param mixed $arrayOperationEasembly
     */
    public function setArrayOperation($arrayOperationEasembly){
        $this->dataDefinition->setArrayOperation($arrayOperationEasembly);
    }
}