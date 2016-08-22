function saveCode() {
    var filename = document.getElementById("DivIdCodeTitleText").innerHTML;
    var file = env.editor.getSession().getValue();
    var path;
    //TODO:  terminar
    var progressBarHolder = document.getElementById("DivIdProgressBarHolder");
    progressBarHolder.style.height = "10px";
    var percentComplete = event.loaded / event.total;
    var progressBar = document.getElementById("DivIdProgressBar");
    progressBar.style.width=percentComplete+"%";


    var select = document.getElementById('mode');
    var optionArray = select.options;
    var modeName = optionArray[select.selectedIndex].value;


    var data = [filename, file, false, modeName, facebookID];

    var newData=JSON.stringify(data);//data.serializeArray();

    //alert(newData);

    $.ajax({
        url: 'Core/PHP/Persistence/FileSystem/FileUpload.php',
        type: 'POST',
        data: {data : newData},
        dataType: 'json',
        success: function(data) {
            //if(typeof data.error === 'undefined') {
                var progressBarHolder = document.getElementById("DivIdProgressBarHolder");
                progressBarHolder.style.height="0px";
                console.log('HA!!!: ' + data.success);
            //} else {
                // Handle errors here

                //console.log('ERRORS: ' + data.error);
            //}
        },
        error: function(jqXHR, textStatus) {
            // Handle errors here
            console.log('ERRORS: ' + jqXHR + ' + ' + textStatus);
            // STOP LOADING SPINNER
        }
    });
}

function newCode() {
    getMultilingualTextFromWindowFromCommonWithCurrentLanguage("CodeEditor", "codeTitle", 8, 0);
}

function deleteFile() {
    var filename = document.getElementById("DivIdCodeTitleText").innerHTML;
    var path;
    //TODO:  terminar
    var progressBarHolder = document.getElementById("DivIdProgressBarHolder");
    progressBarHolder.style.height = "10px";
    var percentComplete = event.loaded / event.total;
    var progressBar = document.getElementById("DivIdProgressBar");
    progressBar.style.width=percentComplete+"%";

    var data = [filename, facebookID];

    var newData=JSON.stringify(data);//data.serializeArray();

    //alert(newData);

    $.ajax({
        url: 'Core/PHP/Persistence/FileSystem/FileDelete.php',
        type: 'POST',
        data: {data : newData},
        dataType: 'json',
        success: function(data) {
            //if(typeof data.error === 'undefined') {
                var progressBarHolder = document.getElementById("DivIdProgressBarHolder");
                progressBarHolder.style.height="0px";
                console.log('HA!!!: ' + data.success);
            //} else {
                // Handle errors here

                //console.log('ERRORS: ' + data.error);
            //}
        },
        error: function(jqXHR, textStatus) {
            // Handle errors here
            console.log('ERRORS: ' + jqXHR + ' + ' + textStatus);
            // STOP LOADING SPINNER
        }
    });
}

function downloadBinary() {
    var filename = document.getElementById("DivIdCodeTitleText").innerHTML;
    var file = env.editor.getSession().getValue();
    var path;
    //TODO:  terminar
    var progressBarHolder = document.getElementById("DivIdProgressBarHolder");
    progressBarHolder.style.height = "10px";
    var percentComplete = event.loaded / event.total;
    var progressBar = document.getElementById("DivIdProgressBar");
    progressBar.style.width=percentComplete+"%";

    var select = document.getElementById('mode');
    var optionArray = select.options;
    var modeName = optionArray[select.selectedIndex].value;

    var data = [filename, file, true, modeName, facebookID];

    var newData=JSON.stringify(data);//data.serializeArray();

    //alert(newData);

    $.ajax({
        url: 'Core/PHP/Persistence/FileSystem/FileUpload.php',
        type: 'POST',
        data: {data : newData},
        dataType: 'json',
        success: function(data) {
            //if(typeof data.error === 'undefined') {
                var progressBarHolder = document.getElementById("DivIdProgressBarHolder");
                progressBarHolder.style.height="0px";
                console.log('HA!!!: ' + data.success);
            //} else {
                // Handle errors here

                //console.log('ERRORS: ' + data.error);
            //}
        },
        error: function(jqXHR, textStatus) {
            // Handle errors here
            console.log('ERRORS: ' + jqXHR + ' + ' + textStatus);
            // STOP LOADING SPINNER
        }
    });
}

function setEditorMode(documentTitle) {
    var modelist = require("ace/ext/modelist");
    var modeName;
    var fileExtension=documentTitle.split(".")[1];
    switch(fileExtension) {
        case "asm":
            modeName = "assembly_8051";
        break;
        case "z80":
            modeName = "assembly_z80";
        break;
        default:
            modeName = "easembly";
    }
    var modesByName = modelist.modesByName;
    env.editor.session.setMode(modesByName[modeName].mode);
    env.editor.session.modeName = modesByName[modeName].mode;
    var select = document.getElementById('mode');
    var optionArray = select.options;
    for(var option, j = 0; option = optionArray[j]; j++) {
        if(option.value == modeName) {
            select.selectedIndex = j;
            break;
        }
    }
}

function newCodeTitleReceived(newTitle) {
    setEditorMode(newTitle);
    document.getElementById("DivIdCodeTitleText").innerHTML = newTitle;
}

function checkCodeEditorLoad(){
    return (document.getElementById("ScriptId"+"RequireJS")!=null);
}

function getCodeEditorCount(){
    var count=getFromCache('codeEditorCount');
    if(count==null){
        saveInCache('codeEditorCount',0);
    }
    return count;
}

function codeEditorLoad(){
    //alert("asfasdf");
    if(!checkCodeEditorLoad()) {
        require = {
            baseUrl: window.location.protocol + "//" + window.location.host + window.location.pathname.split("/").slice(0, -1).join("/"),
            paths: {
                ace: "View/JavaScript/Common/Common/Common/Editor/ace-1.1.9/lib/ace"
            },
            waitSeconds: 30
        };

        loadFile("View/JavaScript/Common/Common/Common/Editor/ace-1.1.9/demo/kitchen-sink/require.js", "js", "View/JavaScript/Common/Common/Common/Editor/ace-1.1.9/demo/kitchen-sink/demo", "ScriptIdRequireJS");

        //requireBase("View/JavaScript/Common/Common/Common/Editor/require.js");

        //$.getScript("View/JavaScript/Common/Common/Common/Editor/require.js", function(){
        //
        //    alert("Script loaded but not necessarily executed.");
        //
        //});

        //$(".TextAreaClassLined").linedtextarea(
        //    {selectedLine: 1}
        //);
        //$("#mytextarea").linedtextarea();

        //env.editor.setTheme("Clean");
    }
}
