<?php
//    include ('../../../../../../Languages/MultilingualText.php');
    include ('../../../../../../Languages/CheckLanguage.php');
    include ('../../../../../../../Core/PHP/Persistence/FileSystem/FileTree.php');
    include ('../../../../../../../Core/PHP/facebook-sdk-v4-5.0-dev/JavaScriptFacebookLoginToPHP.php');

    $facebookId='error';

    if($userNode!=null) {
        $facebookId = $userNode->getId();
    }else{
    //    $facebookId='error';
    }
?>

<div id="DivIdGlass">
    <div id="DivIdPopUp">
        <div id="DivIdPopUpHeader">
            <div id="DivIdHalf">
                <div id="DivIdFullWidthFloat">
                    <div id="DivIdProjectTitle">
                        <div id="DivIdNeon">
                            <div id="DivIdText">
                                <div id="DivIdProjectTitleText">
                                    <div id="DivIdTitleTextSize">
                                        <div id="DivIdTextLanguages">
                                            <?php
                                                $multilingualText = MultilingualText::MultilingualText();
                                                echo $multilingualText->getMultilingualTextFromWindowFromPopUp($language, "FileTree", "openFile");
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="DivIdPopUpClose">
                <div id="DivIdNeon" onclick="popUpClose('DivIdPopUpBox')">
                    <div id="DivIdRedCircle"></div>
                </div>
            </div>
        </div>

        <div id="DivIdPopUpBody">
            <div id="DivIdPopUpBodyWrap">
                <?php
                    echo phpFileTree("../../../../../../../User/".$_GET["id"]."/", "javascript:env.editor.getSession().setValue('[link]'); newCodeTitleReceived('[fileName]')");
                ?>
                <div id="DivIdText">
                    <?php
                    $multilingualText = MultilingualText::MultilingualText();
                    echo $multilingualText->getMultilingualTextFromWindowFromPopUp($language, "FileTree", "examples");
                    ?>
                </div>
                <?php
                    echo phpFileTree("../../../../../../../User/Example/", "javascript:env.editor.getSession().setValue('[link]'); newCodeTitleReceived('[fileName]')");
                ?>
            </div>
        </div>

        <div id="DivIdPopUpFooter">
            <div id="DivIdHalf">
                <div id="DivIdPopUpCancel">
                    <button id="ButtonIdSimple" onclick="popUpClose('DivIdPopUpBox')">
                        <div id="DivIdNeon">
                            <div id="DivIdText">
                                <?php
                                    $multilingualText = MultilingualText::MultilingualText();
                                    echo $multilingualText->getMultilingualTextFromWindowFromPopUp($language, "FileTree", "close");
                                ?>
                            </div>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="DivIdFileTree"></div>