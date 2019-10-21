<?

/*
Copyright [2014] [Diagramo]

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

// $WEBADDRESS = $delegate->settingsGetByKeyNative('WEBADDRESS');
?>
<div id="header">    
    <!--Logo menu-->
    <div class="dropdown_menu">
        <a style="padding: 0;" href="#" target="blank"><img src="{{url('diagramo_assets')}}/assets/images/logo-36x36.png"/></a>
    </div>
    <!-- /File menu-->
        
    <div id="dropdown">
        
        <!--File menu-->
        <div class="dropdown_menu"><a href="#" onmouseover="dropdownSpace.menuOpen('file')" onmouseout="dropdownSpace.menuCloseTime()">File</a>
            <div class="dropdown_menu_panel" id="file" onmouseover="dropdownSpace.menuCancelCloseTime()" onmouseout="dropdownSpace.menuCloseTime()">
                <a style="text-decoration: none;" href="{{url('diagramo_assets')}}/common/controller.php?action=newDiagramExe" title="New diagram"><img style="vertical-align:middle; margin-right: 3px;" src="{{url('diagramo_assets')}}/assets/images/icon_new.jpg" border="0" width="20" height="21"/><span class="menuText">New</span></a>
                <a style="text-decoration: none; border-bottom: 1px solid #666;" href="{{url('diagramo_assets')}}/myDiagrams.php" title="Open diagram"><img style="vertical-align:middle; margin-right: 3px;" src="{{url('diagramo_assets')}}/assets/images/icon_open.jpg" border="0" width="20" height="21"/><span class="menuText">Open...</span></a>
                <?if($page=='editor'){?>
                    <a style="text-decoration: none;" href="javascript:save();"  title="Save diagram (Ctrl-S)"><img style="vertical-align:middle; margin-right: 3px;" src="{{url('diagramo_assets')}}/assets/images/icon_save.jpg" border="0" width="22" height="22"/><span class="menuText">Save</span></a>
                    <a style="text-decoration: none; border-bottom: 1px solid #666;" href="javascript:saveAs();"  title="Save diagram as..."><img style="vertical-align:middle; margin-right: 3px;" src="{{url('diagramo_assets')}}/assets/images/icon_save_as.jpg" border="0" width="22" height="22"/><span class="menuText">Save as...</span></a>
                    <a style="text-decoration: none; border-bottom: 1px solid #666;" href="javascript:void(0)" onclick="showImport();"  title="Import diagram"><img style="vertical-align:middle; margin-right: 3px;" src="{{url('diagramo_assets')}}/assets/images/icon_import.gif" border="0" width="25" height="21"/><span class="menuText">Import</span></a>
                    <?if(isset($_REQUEST['diagramId']) &&  is_numeric($_REQUEST['diagramId']) ){//option available ony when the diagram was saved?>
                        <a style="text-decoration: none; border-bottom: 1px solid #666;" href="{{url('diagramo_assets')}}/exportDiagram.php?diagramId=<?=$_REQUEST['diagramId']?>"  title="Export diagram"><img style="vertical-align:middle; margin-right: 3px;" src="{{url('diagramo_assets')}}/assets/images/icon_export.jpg" border="0" width="22" height="22"/><span class="menuText">Export</span></a>
                        <a style="text-decoration: none; border-bottom: 1px solid #666;" href="javascript:print_diagram();" title="Print diagram"><img style="vertical-align:middle; margin-right: 3px;" src="{{url('diagramo_assets')}}/assets/images/icon_print.png" border="0" width="20" height="21"/><span class="menuText">Print</span></a>
                    <?}?>
                <?}?>
            </div>
        </div>
        <!-- /File menu-->

        <!--Settings menu-->
        <div class="dropdown_menu"><a href="#" onmouseover="dropdownSpace.menuOpen('settings')" onmouseout="dropdownSpace.menuCloseTime()">Settings</a>
            <div class="dropdown_menu_panel"  id="settings" onmouseover="dropdownSpace.menuCancelCloseTime()" onmouseout="dropdownSpace.menuCloseTime()">
                <a href="{{url('diagramo_assets')}}/settings.php">My settings</a>
            </div>
        </div>
        <!--Settings menu-->
        
        <!--Users menu-->
        <div class="dropdown_menu"><a href="#" onmouseover="dropdownSpace.menuOpen('users')" onmouseout="dropdownSpace.menuCloseTime()">Users</a>
            <div class="dropdown_menu_panel"  id="users" onmouseover="dropdownSpace.menuCancelCloseTime()" onmouseout="dropdownSpace.menuCloseTime()">
                <a style="text-decoration: none;" href="{{url('diagramo_assets')}}/users.php"  title="Invite/Manage collaborators"><img style="vertical-align:middle; margin-right: 3px;" src="{{url('diagramo_assets')}}/assets/images/collaborators.gif" border="0" width="22" height="22"/><span class="menuText">Users</span></a>
            </div>
        </div>
        <!--Help menu-->
        
        <!--Help menu-->
        <div class="dropdown_menu"><a href="#" onmouseover="dropdownSpace.menuOpen('help')" onmouseout="dropdownSpace.menuCloseTime()">Help</a>            
            <div class="dropdown_menu_panel" id="help"    onmouseover="dropdownSpace.menuCancelCloseTime()" onmouseout="dropdownSpace.menuCloseTime()" >
                <a href="https://bitbucket.org/scriptoid/diagramo/issues/new" target="new">Report bug</a>
                <a href="javascript:void(0);" onclick="javascript:DIAGRAMO.switchDebug()"><img id="iconDebug"  src="{{url('diagramo_assets')}}/assets/images/icon_debug_false.gif" style="vertical-align: middle;"/> Debug</a>
                <!--<a href="{{url('diagramo_assets')}}/license.php">License</a>-->
                <a href="javascript:void(0);" onclick="buildNo()">About</a>
                               
            </div>                        
        </div>
        <!--Help menu-->
        
        
        <!--Direct link-->
        
        <!-- //Direct link-->
        
        
        
        <!-- alfa
            <td valign="middle">
                <a style="text-decoration: none;" href="javascript:exportCanvas();"><img style="vertical-align:middle; margin-right: 3px;" src="{{url('diagramo_assets')}}/assets/images/icon_export.jpg" border="0" width="22" height="22"/><span class="menuText">Debug Export</span></a>
            </td>
            <td width="20" align="center">
                <img style="vertical-align:middle;" src="{{url('diagramo_assets')}}/assets/images/upper_bar_separator.jpg" border="0" width="2" height="16"/>
            </td>
        -->    

        <script type="text/javascript">
            //check if browser is ready
            if(!isBrowserReady()){                
                document.write('<span style="background-color: red;" >');
                document.write("No support for HTML5. More <a href=\"http://<?=$WEBADDRESS?>/htm5-support.php\">here</a></a>");
                document.write("</span>");                
            }
        </script>   
        
    </div>
</div>