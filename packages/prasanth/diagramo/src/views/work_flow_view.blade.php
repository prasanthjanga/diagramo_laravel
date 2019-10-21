<?php


/*
Copyright [2014] [Scriptoid s.r.l]

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/





//check if diagram is public
if(!is_object($diagram)){
    print "No diagram found";
    exit();
}

if(!$diagram->public){
    print "Diagram is not public. A Diagram must be public to be publicly visible.";
    exit();
}
//end check
// $WEBADDRESS = $delegate->settingsGetByKeyNative('WEBADDRESS');

//exit("here");
?>

<!DOCTYPE html>
<html>
    <!--Copyright 2010 Scriptoid s.r.l-->
    <head>
        <title><?=$diagram->title?></title>
        <meta charset="UTF-8">
        <meta name="description" content="<?=$diagram->description?>" />
        <meta http-equiv="X-UA-Compatible" content="IE=9" />
        <link rel="stylesheet" media="screen" type="text/css" href="{{url('/diagramo_assets')}}/assets/css/style.css" />
    </head>
    <body>
        <div id="content" style="margin-left: 30px;">
            <h1><?=$diagram->title?></h1>
            <div><?=$diagram->description?></div>
            <div>Public </div>
            <p/>

            <div id="container">
                <img src="{{url('diagramo/png/'.$diagramId)}}">
            </div>
            
        </div>
    </body>
</html>