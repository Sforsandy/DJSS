<!DOCTYPE html>
<html class="loading" lang="{{ app()->getLocale() }}" data-textdirection="ltr">
 
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

    <title>NovidForms - @yield('title')</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ url('/') }}/public/uploads/novidform_fav.png">
    <link href="https://fonts.googleapis.com/css?family=Muli:300,300i,400,400i,600,600i,700,700i%7CComfortaa:300,400,700" rel="stylesheet">
    {{ Html::style('https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css') }}
    
    {{ Html::style('public/app-assets/css/vendors.min.css') }}
    {{ Html::style('public/app-assets/vendors/css/forms/toggle/switchery.min.css') }}
    {{ Html::style('public/app-assets/css/plugins/forms/switch.min.css') }}
    {{ Html::style('public/app-assets/css/core/colors/palette-switch.min.css') }}
    {{ Html::style('public/app-assets/vendors/css/extensions/toastr.css') }}
    {{ Html::style('public/app-assets/vendors/css/forms/selects/select2.min.css')}}
    {{ Html::style('public/app-assets/css/pages/chat-application.css') }}
    
    <!-- END VENDOR CSS-->
    <!-- BEGIN CHAMELEON  CSS-->
    {{ Html::style('public/app-assets/css/app.min.css') }}
    <!-- END CHAMELEON  CSS-->
    <!-- BEGIN Page Level CSS-->
    {{ Html::style('public/app-assets/css/core/menu/menu-types/vertical-menu.min.css') }}
    {{ Html::style('public/app-assets/css/core/colors/palette-gradient.min.css') }}
    {{ Html::style('public/app-assets/css/plugins/extensions/toastr.min.css') }}
    @yield('css')
    <!-- END Page Level CSS-->
    <!-- BEGIN Custom CSS-->
    {{ Html::style('public/assets/css/style.css') }}
    <!-- END Custom CSS-->
    <!-- custom css -->
    <style>
        /**raymond custom css**/
.header-navbar .navbar-container {
    padding: 0 0px !important; /* removing padding on the right of user avatar*/
}
            
                
/*changing the background of header*/
.navbar-container {
    background: #ffffff !important;
}
                
/* add a border in the user avatar*/
.avatar img {
    border: 1px solid #ffffff;
    padding: 2px;
}
                
/*changing the color of the icons in header*/
.header_icon {
    color: #828282 ;
}

.navbar-nav {
    margin-top: -3px;
}
                
/** removing border of search textbox in the header*/
#search {
    border-style: none none solid none;
    border-radius: 0px !important;
}
                
/* removing content header background */
body.horizontal-layout[data-color=bg-gradient-x-purple-blue] .content-wrapper-before, body.horizontal-layout[data-color=bg-gradient-x-purple-blue] .navbar-horizontal, body.vertical-layout[data-color=bg-gradient-x-purple-blue] .content-wrapper-before, body.vertical-layout[data-color=bg-gradient-x-purple-blue] .navbar-container {
    background: transparent;
    background: transaprent !important;
    background: transaprent !important;
    background: transaprent !important;
    background: transaprent !important;
}
                
                
.navbar-semi-light {
    background: #ffffff !important;
}
                
/** removing shadow in the sidebar **/
.main-menu.menu-shadow {
    -webkit-box-shadow: 0px 0 00px rgba(0,0,0,0) !important;
    box-shadow: 0px 0 0px rgba(0,0,0,0) !important;
}

.main-menu.menu-light .navigation>li.active>a {
    border-radius: 0px 50px 50px 0px;
}
                
/** removing border in the sidebar */
.main-menu.menu-light {
    border-right: 0px solid #e4e7ed !important;
}
                
/** changing add a padding in the right of the logo **/
.navbar-header .navbar-brand {
    margin-right: 0;
    padding: 17px 10px !important;
    z-index: 9999 !important;
    position: absolute;
}
                

body.vertical-layout.vertical-menu.menu-expanded .main-menu.menu-light .navigation>li>a>i {
    -webkit-box-shadow: 0 0 0px rgba(0,0,0,0) !important;
    box-shadow: 0 0 0px rgba(0,0,0,0) !important;
    background: transparent !important;
}
                
.main-menu.menu-light .navigation>li { 
    margin-right: 20px;   
}

.menu-collapsed .main-menu.menu-light .navigation>li { 
    margin-right: 0px;   
}

.menu-collapsed .menu-light .navbar-header .brand-text {
    display:none;
}

.card-visitor {
    box-shadow: 3px 3px 16px 1px rgba(241, 104, 61, 0.2);
}

.card-orders {
    box-shadow: 3px 3px 16px 1px rgba(86, 180, 210, 0.2);
}

.card-sales {
    box-shadow: 3px 3px 16px 1px rgba(147, 110, 211, 0.2);
}

.card-lead {
    box-shadow: 3px 3px 16px 1px rgba(51, 160, 148, 0.2);
}

.icon-visitor {
    background: #f1825f; width: max-content; padding: 25px; border-radius: 50px; position:absolute; top: 0; margin-top: -30px; box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2);
}

.icon-orders {
    background: #3ca9cb; width: max-content; padding: 25px; border-radius: 50px; position:absolute; top: 0; margin-top: -30px; box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2);
}

.icon-sales {
    background: #a992d0; width: max-content; padding: 25px; border-radius: 50px; position:absolute; top: 0; margin-top: -30px; box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2);
}

.icon-lead {
    background: #349c92; width: max-content; padding: 25px; border-radius: 50px; position:absolute; top: 0; margin-top: -30px; box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2);
}

.dash-icon .fa {
    color: #ffffff; text-shadow: 3px 3px 16px rgba(0, 0, 0, 0.2);
}

.topblock-text {
    width: max-content; text-align:center; float: right;
}
     
.topblock-visitors {
    color: #f1825f; font-weight:bold;
}

.topblock-orders {
    color: #3ca9cb; font-weight:bold;
}

.topblock-sales {
    color: #a992d0; font-weight:bold;
}

.topblock-lead {
    color: #349c92; font-weight:bold;
}

           
body.vertical-layout.vertical-menu.menu-expanded .main-menu.menu-light .navigation>li:hover {
    border-radius: 0px 50px 50px 0px;
}
                
.menu_actives {
    color: #ffffff;
    background: #5cb3c4;
    border-radius: 0px 50px 50px 0px;
    box-shadow: 0 7px 12px 0 rgba(62, 57, 107, .16) !important;
}
                
.bg-gradient-x-violet {
    background: #9c7cd2;
}
       
.percircle:after {
    background-color: #ffffff !important;
}

.chatButton {
    position: fixed; 
    bottom: 0px; 
    right: 0px; 
    width: auto; 
    height: 70px; 
    padding-bottom: 10px; 
    padding-right: 10px;
    cursor: pointer;
}

.closeChatButton {
    position: fixed; 
    bottom: 0px; 
    right: 0px; 
    width: auto; 
    height: 70px; 
    padding-bottom: 10px; 
    padding-right: 10px;
    cursor: pointer;
    display: none;
}


.coversationHeader {
    padding: 30px; display:none; background: #ffffff;
}

.chat-application .chats .chat-left .chat-content {
    float: left !important;
    margin: 0 0 15px 20px !important;
    text-align: left !important;
    color: #6b6f80 !important;
    background-color: #fff !important;
    -webkit-box-shadow: 0 7px 12px 0 rgba(62, 57, 107, .16) !important;
    box-shadow: 0 7px 12px 0 rgba(62, 57, 107, .16) !important;
}

.chat-application .chats .chat-body .chat-content {
    position: relative;
    display: block;
    float: right;
    clear: both;
    margin: 0 20px 10px 0;
    padding: 8px 15px;
    text-align: right;
    color: #fff;
    border-radius: 7px;
    background-color: #45a8bc;
    -webkit-box-shadow: 0 5px 12px 0 rgba(62, 57, 107, .36);
    box-shadow: 0 5px 12px 0 rgba(62, 57, 107, .36);
}

.chat-application .chats .chat-body .chat-content:before {
    position: absolute;
    top: 10px;
    right: -10px;
    width: 0;
    height: 0;
    content: '';
    border: 5px solid transparent;
    border-left-color: #45a8bc;
}


.btnQuote {
        background: #ffffff;
        padding: 20px 60px 20px 60px;
        margin: 50px 50px 20px 50px;
        box-shadow: 0px 0px 10px 0px #f4abbc;
        border: none;
        border-radius: 10px;
        color: #f45379;
        font-weight: bold;
    }
    
    .btn-save {
        background: #f45379;
        padding: 20px 60px 20px 60px;
        margin: 50px 50px 20px 50px;
        box-shadow: 0px 0px 10px 0px #f4abbc;
        border: none;
        border-radius: 10px;
        color: #ffffff;
        font-weight: bold;
    }
    
    .btn-cancel {
        background: #ffffff;
        padding: 20px 60px 20px 60px;
        margin: 50px 50px 20px 50px;
        box-shadow: 0px 0px 10px 0px #d3d3d3;
        border: none;
        border-radius: 10px;
        color: #000000;
        font-weight: bold;
    }
    
    .setDataWrap {
        width: 100%;
    }
    
    .clear-all {
        padding: 20px 20px 20px 20px !important;
        color: #ffffff;
        border-radius: 10px;
    }
    
    .save-template {
        padding: 20px 20px 20px 20px !important;
        color: #ffffff;
        border-radius: 10px;
    }
    
    .get-data {
        background: #f45379 !important;
        padding: 20px 20px 20px 20px !important;
        color: #ffffff;
        border-radius: 10px;
    }
    
    .stage-wrap {
        box-shadow: 0px 0px 10px 0px #d3d3d3 !important;
        padding: 50px;
    }
    
    .frmb .form-control {
        box-shadow: 0px 0px 10px 0px #f4abbc !important;
        border: 0px !important;
    }
    
    .ui-sortable-handle {
        margin: 8px !important;
        border-radius: 5px !important;
    }
    
    .form-wrap.form-builder .frmb-control li {
        cursor: move;
        list-style: none;
        margin: 0 0 -1px;
        padding: 10px;
        text-align: left;
        background: #fff;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
        box-shadow: 0px 0px 5px 1px #c5c5c5 !important;
    }
    
    .dropdown .dropdown-menu {
        min-width: 18rem;
    }
    
    .contentBlock {
        background: #ffffff; height: 455px; border-radius: 0px 0px 0px 25px; width: 500px;
    }
    
    .adminAvatar {
        position: absolute; margin-top: -30px; margin-left: 98px; box-shadow: 0 0 20px rgba(117, 117, 117, 0.3); width: 300px; padding: 20px 20px 20px 20px; background: #fff; border-radius: 10px;
    }
    
    .form-builder  {
        padding: 20px;
    }
    
    
/*    .fb-radio {*/
/*      display: inline-block;*/
/*      position: relative;*/
/*      padding: 0 6px;*/
/*      margin: 10px 0 0;*/
/*    }*/
    
/*    .fb-radio input[type='radio'] {*/
/*      display: none;*/
/*    }*/
    
/*    .fb-radio label {*/
/*      color: #666;*/
/*      font-weight: normal;*/
/*    }*/
    
/*    .fb-radio label:before {*/
/*      content: " ";*/
/*      display: inline-block;*/
/*      position: relative;*/
/*      top: 5px;*/
/*      margin: 0 5px 0 0;*/
/*      width: 20px;*/
/*      height: 20px;*/
/*      border-radius: 11px;*/
/*      border: 2px solid #f45379;*/
/*      background-color: transparent;*/
/*    }*/
    
/*    .fb-radio input[type=radio]:checked + label:after {*/
/*      border-radius: 11px;*/
/*      width: 12px;*/
/*      height: 12px;*/
/*      position: absolute;*/
/*      top: 9px;*/
/*      left: 10px;*/
/*      content: " ";*/
/*      display: block;*/
/*      background: #f45379;*/
/*    }*/
    
    
/*     .form-wrap.form-builder .frmb .sortable-options {*/
/*        background: #ffffff !important;*/
/*    }*/
    
/*    .form-wrap.form-builder .frmb .form-elements {*/
/*        padding: 10px 5px;*/
/*        background: #ffffff !important;*/
/*        border-radius: 3px;*/
/*        margin: 0;*/
/*        border: 0px solid #c5c5c5 !important;*/
/*    }*/
    
/*    .fb-checkbox input[type=checkbox]:before { content:""; display:inline-block; width:16px; height:16px; background:red; }*/
/*input[type=checkbox]:checked:before { background:green; }*/


/* images */
/*.fb-checkbox input[type=checkbox]:before { background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAALVWlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4KPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNC4yLjItYzA2MyA1My4zNTI2MjQsIDIwMDgvMDcvMzAtMTg6MTI6MTggICAgICAgICI+CiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPgogIDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiCiAgICB4bWxuczpwaG90b3Nob3A9Imh0dHA6Ly9ucy5hZG9iZS5jb20vcGhvdG9zaG9wLzEuMC8iCiAgICB4bWxuczpJcHRjNHhtcENvcmU9Imh0dHA6Ly9pcHRjLm9yZy9zdGQvSXB0YzR4bXBDb3JlLzEuMC94bWxucy8iCiAgICB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iCiAgICB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIKICAgIHhtbG5zOnN0RXZ0PSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VFdmVudCMiCiAgICB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iCiAgICB4bWxuczp4bXBSaWdodHM9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9yaWdodHMvIgogICBwaG90b3Nob3A6QXV0aG9yc1Bvc2l0aW9uPSJBcnQgRGlyZWN0b3IiCiAgIHBob3Rvc2hvcDpDcmVkaXQ9Ind3dy5nZW50bGVmYWNlLmNvbSIKICAgcGhvdG9zaG9wOkRhdGVDcmVhdGVkPSIyMDEwLTAxLTAxIgogICBJcHRjNHhtcENvcmU6SW50ZWxsZWN0dWFsR2VucmU9InBpY3RvZ3JhbSIKICAgeG1wOk1ldGFkYXRhRGF0ZT0iMjAxMC0wMS0wM1QyMTozMzoxNCswMTowMCIKICAgeG1wTU06T3JpZ2luYWxEb2N1bWVudElEPSJ4bXAuZGlkOjM4QjcwRDNEODFGN0RFMTE5RUFCOTBENzA3OEFGOTRBIgogICB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjM4QjcwRDNEODFGN0RFMTE5RUFCOTBENzA3OEFGOTRBIgogICB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjgyRjI3NDM3QTdGOERFMTE4MjFDRTRCMkM3RTM2RDcwIj4KICAgPElwdGM0eG1wQ29yZTpDcmVhdG9yQ29udGFjdEluZm8KICAgIElwdGM0eG1wQ29yZTpDaUFkckNpdHk9IlByYWd1ZSIKICAgIElwdGM0eG1wQ29yZTpDaUFkclBjb2RlPSIxNjAwMCIKICAgIElwdGM0eG1wQ29yZTpDaUFkckN0cnk9IkN6ZWNoIFJlcHVibGljIgogICAgSXB0YzR4bXBDb3JlOkNpRW1haWxXb3JrPSJrYUBnZW50bGVmYWNlLmNvbSIKICAgIElwdGM0eG1wQ29yZTpDaVVybFdvcms9Ind3dy5nZW50bGVmYWNlLmNvbSIvPgogICA8eG1wTU06SGlzdG9yeT4KICAgIDxyZGY6U2VxPgogICAgIDxyZGY6bGkKICAgICAgc3RFdnQ6YWN0aW9uPSJzYXZlZCIKICAgICAgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDozOEI3MEQzRDgxRjdERTExOUVBQjkwRDcwNzhBRjk0QSIKICAgICAgc3RFdnQ6d2hlbj0iMjAxMC0wMS0wMlQxMDoyODo1MSswMTowMCIKICAgICAgc3RFdnQ6Y2hhbmdlZD0iL21ldGFkYXRhIi8+CiAgICAgPHJkZjpsaQogICAgICBzdEV2dDphY3Rpb249InNhdmVkIgogICAgICBzdEV2dDppbnN0YW5jZUlEPSJ4bXAuaWlkOjMzM0E0QTAzREJGN0RFMTFBOTAwODNFMEExMjUzQkZEIgogICAgICBzdEV2dDp3aGVuPSIyMDEwLTAxLTAyVDIxOjExOjI5KzAxOjAwIgogICAgICBzdEV2dDpjaGFuZ2VkPSIvbWV0YWRhdGEiLz4KICAgICA8cmRmOmxpCiAgICAgIHN0RXZ0OmFjdGlvbj0ic2F2ZWQiCiAgICAgIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6ODJGMjc0MzdBN0Y4REUxMTgyMUNFNEIyQzdFMzZENzAiCiAgICAgIHN0RXZ0OndoZW49IjIwMTAtMDEtMDNUMjE6MzM6MTQrMDE6MDAiCiAgICAgIHN0RXZ0OmNoYW5nZWQ9Ii9tZXRhZGF0YSIvPgogICAgPC9yZGY6U2VxPgogICA8L3htcE1NOkhpc3Rvcnk+CiAgIDxkYzp0aXRsZT4KICAgIDxyZGY6QWx0PgogICAgIDxyZGY6bGkgeG1sOmxhbmc9IngtZGVmYXVsdCI+Z2VudGxlZmFjZS5jb20gZnJlZSBpY29uIHNldDwvcmRmOmxpPgogICAgPC9yZGY6QWx0PgogICA8L2RjOnRpdGxlPgogICA8ZGM6c3ViamVjdD4KICAgIDxyZGY6QmFnPgogICAgIDxyZGY6bGk+aWNvbjwvcmRmOmxpPgogICAgIDxyZGY6bGk+cGljdG9ncmFtPC9yZGY6bGk+CiAgICA8L3JkZjpCYWc+CiAgIDwvZGM6c3ViamVjdD4KICAgPGRjOmRlc2NyaXB0aW9uPgogICAgPHJkZjpBbHQ+CiAgICAgPHJkZjpsaSB4bWw6bGFuZz0ieC1kZWZhdWx0Ij5UaGlzIGlzIHRoZSBpY29uIGZyb20gR2VudGxlZmFjZS5jb20gZnJlZSBpY29ucyBzZXQuIDwvcmRmOmxpPgogICAgPC9yZGY6QWx0PgogICA8L2RjOmRlc2NyaXB0aW9uPgogICA8ZGM6Y3JlYXRvcj4KICAgIDxyZGY6U2VxPgogICAgIDxyZGY6bGk+QWxleGFuZGVyIEtpc2VsZXY8L3JkZjpsaT4KICAgIDwvcmRmOlNlcT4KICAgPC9kYzpjcmVhdG9yPgogICA8ZGM6cmlnaHRzPgogICAgPHJkZjpBbHQ+CiAgICAgPHJkZjpsaSB4bWw6bGFuZz0ieC1kZWZhdWx0Ij5DcmVhdGl2ZSBDb21tb25zIEF0dHJpYnV0aW9uIE5vbi1Db21tZXJjaWFsIE5vIERlcml2YXRpdmVzPC9yZGY6bGk+CiAgICA8L3JkZjpBbHQ+CiAgIDwvZGM6cmlnaHRzPgogICA8eG1wUmlnaHRzOlVzYWdlVGVybXM+CiAgICA8cmRmOkFsdD4KICAgICA8cmRmOmxpIHhtbDpsYW5nPSJ4LWRlZmF1bHQiPkNyZWF0aXZlIENvbW1vbnMgQXR0cmlidXRpb24gTm9uLUNvbW1lcmNpYWwgTm8gRGVyaXZhdGl2ZXM8L3JkZjpsaT4KICAgIDwvcmRmOkFsdD4KICAgPC94bXBSaWdodHM6VXNhZ2VUZXJtcz4KICA8L3JkZjpEZXNjcmlwdGlvbj4KIDwvcmRmOlJERj4KPC94OnhtcG1ldGE+Cjw/eHBhY2tldCBlbmQ9InIiPz68YWJYAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAMFJREFUeNpiYKAQMIIIAwMDfkZGxg1ApgMJeg/8//8/gAXE+vfvH6maGaDqN4AN+Pv3L0zzAqBLFhDSCbQ5AUiBsAPYgD9//kD8w8g44caNGxcJGaChofEBaggkDFRUVP6D6Dt37jAS636YHhQXkAJgemBhQLIBMD3UMWDgvUCJC5hgpoEwDw+PPjGaQepgelAMAJpawMHBgdcQkDxIHUwPOOGwsrLuJyMvgDMUmGRmZuZnYmLaD8T/ScD7QfoAAgwAwhN2ISN/LUUAAAAASUVORK5CYII=) #fff;}*/
/*.fb-checkbox input[type=checkbox]:checked:before { transform: scale(1.5); background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAALVWlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4KPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNC4yLjItYzA2MyA1My4zNTI2MjQsIDIwMDgvMDcvMzAtMTg6MTI6MTggICAgICAgICI+CiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPgogIDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiCiAgICB4bWxuczpwaG90b3Nob3A9Imh0dHA6Ly9ucy5hZG9iZS5jb20vcGhvdG9zaG9wLzEuMC8iCiAgICB4bWxuczpJcHRjNHhtcENvcmU9Imh0dHA6Ly9pcHRjLm9yZy9zdGQvSXB0YzR4bXBDb3JlLzEuMC94bWxucy8iCiAgICB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iCiAgICB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIKICAgIHhtbG5zOnN0RXZ0PSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VFdmVudCMiCiAgICB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iCiAgICB4bWxuczp4bXBSaWdodHM9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9yaWdodHMvIgogICBwaG90b3Nob3A6QXV0aG9yc1Bvc2l0aW9uPSJBcnQgRGlyZWN0b3IiCiAgIHBob3Rvc2hvcDpDcmVkaXQ9Ind3dy5nZW50bGVmYWNlLmNvbSIKICAgcGhvdG9zaG9wOkRhdGVDcmVhdGVkPSIyMDEwLTAxLTAxIgogICBJcHRjNHhtcENvcmU6SW50ZWxsZWN0dWFsR2VucmU9InBpY3RvZ3JhbSIKICAgeG1wOk1ldGFkYXRhRGF0ZT0iMjAxMC0wMS0wM1QyMTozMzoxNCswMTowMCIKICAgeG1wTU06T3JpZ2luYWxEb2N1bWVudElEPSJ4bXAuZGlkOjM5QjcwRDNEODFGN0RFMTE5RUFCOTBENzA3OEFGOTRBIgogICB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjM5QjcwRDNEODFGN0RFMTE5RUFCOTBENzA3OEFGOTRBIgogICB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjgzRjI3NDM3QTdGOERFMTE4MjFDRTRCMkM3RTM2RDcwIj4KICAgPElwdGM0eG1wQ29yZTpDcmVhdG9yQ29udGFjdEluZm8KICAgIElwdGM0eG1wQ29yZTpDaUFkckNpdHk9IlByYWd1ZSIKICAgIElwdGM0eG1wQ29yZTpDaUFkclBjb2RlPSIxNjAwMCIKICAgIElwdGM0eG1wQ29yZTpDaUFkckN0cnk9IkN6ZWNoIFJlcHVibGljIgogICAgSXB0YzR4bXBDb3JlOkNpRW1haWxXb3JrPSJrYUBnZW50bGVmYWNlLmNvbSIKICAgIElwdGM0eG1wQ29yZTpDaVVybFdvcms9Ind3dy5nZW50bGVmYWNlLmNvbSIvPgogICA8eG1wTU06SGlzdG9yeT4KICAgIDxyZGY6U2VxPgogICAgIDxyZGY6bGkKICAgICAgc3RFdnQ6YWN0aW9uPSJzYXZlZCIKICAgICAgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDozOUI3MEQzRDgxRjdERTExOUVBQjkwRDcwNzhBRjk0QSIKICAgICAgc3RFdnQ6d2hlbj0iMjAxMC0wMS0wMlQxMDoyODo1MSswMTowMCIKICAgICAgc3RFdnQ6Y2hhbmdlZD0iL21ldGFkYXRhIi8+CiAgICAgPHJkZjpsaQogICAgICBzdEV2dDphY3Rpb249InNhdmVkIgogICAgICBzdEV2dDppbnN0YW5jZUlEPSJ4bXAuaWlkOjM0M0E0QTAzREJGN0RFMTFBOTAwODNFMEExMjUzQkZEIgogICAgICBzdEV2dDp3aGVuPSIyMDEwLTAxLTAyVDIxOjExOjI5KzAxOjAwIgogICAgICBzdEV2dDpjaGFuZ2VkPSIvbWV0YWRhdGEiLz4KICAgICA8cmRmOmxpCiAgICAgIHN0RXZ0OmFjdGlvbj0ic2F2ZWQiCiAgICAgIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6ODNGMjc0MzdBN0Y4REUxMTgyMUNFNEIyQzdFMzZENzAiCiAgICAgIHN0RXZ0OndoZW49IjIwMTAtMDEtMDNUMjE6MzM6MTQrMDE6MDAiCiAgICAgIHN0RXZ0OmNoYW5nZWQ9Ii9tZXRhZGF0YSIvPgogICAgPC9yZGY6U2VxPgogICA8L3htcE1NOkhpc3Rvcnk+CiAgIDxkYzp0aXRsZT4KICAgIDxyZGY6QWx0PgogICAgIDxyZGY6bGkgeG1sOmxhbmc9IngtZGVmYXVsdCI+Z2VudGxlZmFjZS5jb20gZnJlZSBpY29uIHNldDwvcmRmOmxpPgogICAgPC9yZGY6QWx0PgogICA8L2RjOnRpdGxlPgogICA8ZGM6c3ViamVjdD4KICAgIDxyZGY6QmFnPgogICAgIDxyZGY6bGk+aWNvbjwvcmRmOmxpPgogICAgIDxyZGY6bGk+cGljdG9ncmFtPC9yZGY6bGk+CiAgICA8L3JkZjpCYWc+CiAgIDwvZGM6c3ViamVjdD4KICAgPGRjOmRlc2NyaXB0aW9uPgogICAgPHJkZjpBbHQ+CiAgICAgPHJkZjpsaSB4bWw6bGFuZz0ieC1kZWZhdWx0Ij5UaGlzIGlzIHRoZSBpY29uIGZyb20gR2VudGxlZmFjZS5jb20gZnJlZSBpY29ucyBzZXQuIDwvcmRmOmxpPgogICAgPC9yZGY6QWx0PgogICA8L2RjOmRlc2NyaXB0aW9uPgogICA8ZGM6Y3JlYXRvcj4KICAgIDxyZGY6U2VxPgogICAgIDxyZGY6bGk+QWxleGFuZGVyIEtpc2VsZXY8L3JkZjpsaT4KICAgIDwvcmRmOlNlcT4KICAgPC9kYzpjcmVhdG9yPgogICA8ZGM6cmlnaHRzPgogICAgPHJkZjpBbHQ+CiAgICAgPHJkZjpsaSB4bWw6bGFuZz0ieC1kZWZhdWx0Ij5DcmVhdGl2ZSBDb21tb25zIEF0dHJpYnV0aW9uIE5vbi1Db21tZXJjaWFsIE5vIERlcml2YXRpdmVzPC9yZGY6bGk+CiAgICA8L3JkZjpBbHQ+CiAgIDwvZGM6cmlnaHRzPgogICA8eG1wUmlnaHRzOlVzYWdlVGVybXM+CiAgICA8cmRmOkFsdD4KICAgICA8cmRmOmxpIHhtbDpsYW5nPSJ4LWRlZmF1bHQiPkNyZWF0aXZlIENvbW1vbnMgQXR0cmlidXRpb24gTm9uLUNvbW1lcmNpYWwgTm8gRGVyaXZhdGl2ZXM8L3JkZjpsaT4KICAgIDwvcmRmOkFsdD4KICAgPC94bXBSaWdodHM6VXNhZ2VUZXJtcz4KICA8L3JkZjpEZXNjcmlwdGlvbj4KIDwvcmRmOlJERj4KPC94OnhtcG1ldGE+Cjw/eHBhY2tldCBlbmQ9InIiPz5lxa2uAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAXFJREFUeNqkU01qwkAUdpIKLgLjKhIQXAhZCMEcwRvUniA5QnqT9gZ6g/QE5gTSCS6yiDC4000ruMxP3xt8ISapUvrgzczL+74372fS6/1TGC6u63LGWAjHRSeIseV2u/3o8j3hUhTFPbIvhKjIjuPgZREcoziOX1WAPM+JvALnqsb/3u12gozZbMYBGwHGRV+VQZZldNtbkiSiKxPbthUZK75iwyoAOBQoTVNFnk6nHgCCsiwX+/3+DDaHMisySAjY91YGKJPJxIOAVEYE9hLssEaWmqb5N02kDK4NrfcASbI5ESnlGc/j8fhZowAUBHaf7A4NDoeDKtOyLA+np96BaZol7qfTSdmj0ciD+leNPobgfyGDOK0MUI7H47qRiYTb/Hq0Gw7nvERtjm44HHrw/Qv2edNHHJWyYRhEdi+Xi3j0/gGPAT9bJcA4g8FgML9HRj/iiKMy6Pf7m9/+hQcSqVXXdQ6PYwNa/kE3yPsRYADJ3N43dnuf2gAAAABJRU5ErkJggg==) #fff; }*/

/*.fb-checkbox label {*/
/*      margin-left: 10px;*/
/*      margin-top: 2px;*/
/*    }*/
    .media-list .media {
        margin-top: 10px;
        padding: 0.7rem;
    }
    
    .submittedCardIcon {
        position: absolute;
        left: 40%;
        top: 50%;
        -webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        font-size: 80px;
        color: #ffffff;
    }
    
    .startConversationButton {
        background: #45a8bc; color: #ffffff; margin-top: 200px;
        
    }
    
    #recent-buyers {
        overflow-y: scroll; height:375px;
    }
    
    .chatOption {
        height: 384px;
    }
    
    @media only screen and (max-width: 1425px)  {
        .percircle.big {
            font-size: 168px !important;
        }
    }
    
    @media only screen and (max-width: 1404px)  {
        .topblock-title {
            font-size: 13px;
        }
    }
    
    @media only screen and (max-width: 1333px)  {
        .topblock-title {
            font-size: 13px;
        }
    }
    
    @media only screen and (max-width: 1238px)  {
        .topblock-title {
            font-size: 10px;
        }
    }
    
    
    @media only screen and (max-width: 1199px)  {
        .dropdown-user {
            width: auto !important;
            height: -webkit-fill-available;
        }
        
        .avatar img {
            border: 1px solid #ffffff;
            padding: 2px;
            margin-top: 6px;
        }
    }
    
    @media only screen and (max-width: 1194px)  {
        .dropdown-user {
            width: auto !important;
            height: -webkit-fill-available;
        }
        
        .avatar img {
            border: 1px solid #ffffff;
            padding: 2px;
            margin-top: 6px;
        }
    }
    
    @media only screen and (max-width: 1160px)  {
        .topblock-text {
            width: 100%;
            text-align: center;
            float: none;
            padding-top: 30px;
        }
        
        .dash-icon {
            margin-left: 30px;
            margin-top: -38px;
        }
    }
    
    @media only screen and (max-width: 1120px)  {
        .topblock-text {
            width: 100%;
            text-align: center;
            float: none;
            padding-top: 30px;
        }
        
        .dash-icon {
            margin-left: 24px;
            margin-top: -38px;
        }
        
        .percircle.big {
            font-size: 110px !important;
        }
    }
    
    @media only screen and (max-width: 1010px)  {
        .topblock-text {
            width: 100%;
            text-align: center;
            float: none;
            padding-top: 30px;
        }
        
        .dash-icon {
            margin-left: 11px;
            margin-top: -38px;
        }
    }
    
    @media only screen and (max-width: 991px)  {
        .topblock-text {
            width: max-content;
            text-align: center;
            float: right;
            padding-top: 0px;
        }
        
        .dash-icon {
            margin-left: -11px;
            margin-top: -38px;
        }
        
        .main-menu.menu-light .navigation>li {
            margin-right: 0px;
        }
        
        .circleprogressbar {
            padding: 0px !important;
        }
    }
    
    @media only screen and (max-width: 990px)  {
        .submittedCardBG {
            background: #43ab62 !important;
        }
    }
    
    @media only screen and (max-width: 910px)  {
        .topblock-text {
            width: 100%;
            text-align: center;
            float: none;
            padding-top: 30px;
        }
        
        .dash-icon {
            margin-left: 24px;
            margin-top: -38px;
        }
    }
    
    @media only screen and (max-width: 830px)  {
        .topblock-text {
            width: 100%;
            text-align: center;
            float: none;
            padding-top: 30px;
        }
        
        .dash-icon {
            margin-left: 15px;
            margin-top: -38px;
        }
    }
    
    @media only screen and (max-width: 768px)  {
        .dash-icon {
            margin-left: 6px !important;
            margin-top: -38px;
        }
    }
    
    @media only screen and (max-width: 768px)  {
        .dash-icon {
            margin-left: 6px !important;
            margin-top: -38px;
        }
    }
    
    
    
    @media only screen and (max-width: 767px)  {
        .topblock-text {
            width: max-content;
            text-align: center;
            float: right;
            padding-top: 0px !important;
        }
        
        .dash-icon {
            margin-left: 15px;
            margin-top: -13px !important;
        }
        
        .topblock-title {
            font-size: 24px !important;
        }
        
        .custom-searchbar {
            display:none;
        }
        
        .navbar-search {
            display: block !important;
        }
        
        .notif {
            display: none;
        }
        
        .circlecenter {
            width: max-content;
            margin: auto;
        }   
        
        .vertical-overlay-menu.menu-open .main-menu.menu-light .navigation>li>a>i {
            color: black !important;
        }
    }
    
    @media only screen and (max-width: 680px)  {
        .topblock-text {
            width: max-content;
            text-align: right;
            float: right;
            padding-top: 0px !important;
        }
        
        .dash-icon {
            margin-left: 15px;
            margin-top: -13px !important;
        }
        
        .topblock-title {
            font-size: 18px !important;
        }
    }
    
    @media only screen and (max-width: 680px)  {
        .circlecenter {
            width: max-content;
            margin: auto;
        }   
    }
    
    @media only screen and (max-width: 575px)  {
        .avatarImageChat {
            width: 94px !important;
        }   
        
        .contentBlock {
            height: 250px;
        }
        
        #recent-buyers {
            height: 237px;
        }
        
        .chatOption {
            height: 174px;
        }
        
        .startConversationButton {
            margin-top: 158px;
        }
    }
    
    @media only screen and (max-width: 531px)  {
        .chatPopup  {
            width: 431px !important;
        }   
        
        .contentBlock {
            width: 431px !important;
        }
        
        .adminAvatar {
            margin-left: 64px;
        }
        
        .tableHeader {
            padding: 0px !important;
        }
        
        .tableHeader td, .tableHeader th {
            border-bottom: 1px solid #e3ebf3;
            padding: 0rem;
        }
        
        .avatarOnChat {
            padding-right: 30px !important;
        }
    }
    
    @media only screen and (max-width: 531px)  {
        .chatPopup  {
            width: 350px !important;
        }   
        
        .contentBlock {
            width: 350px !important;
        }
        
        .adminAvatar {
            margin-left: 26px;
        }
        
        .tableHeader {
            padding: 0px !important;
        }
        
        .tableHeader td, .tableHeader th {
            border-bottom: 1px solid #e3ebf3;
            padding: 0rem;
        }
        
        .avatarOnChat {
            padding-right: 30px !important;
        }
    }
    

        .closeChatButton {
            position: fixed; 
            bottom: 0px; 
            right: 0px; 
            width: auto; 
            height: 70px; 
            padding-bottom: 10px; 
            padding-right: 10px;
            cursor: pointer;
            display: none;
        }
        .chatPopup {
            max-width: 500px;
            box-shadow: 0px 1px 15px 0px #45a8bc;
            border-radius: 25px 25px 0px 25px;
            background: #ffffff;
            position: fixed;
            bottom: 0;
            margin-bottom: 80px;
            right: 15px;
            z-index: 9;
            display: none;
        }
        .chatButton {
            position: fixed;
            bottom: 0px;
            right: 0px;
            width: auto;
            height: 70px;
            padding-bottom: 10px;
            padding-right: 10px;
            cursor: pointer;
        }
        .loadingoverlay {
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            display: none;
            right: 0;
            z-index: 999;
            bottom: 0;
            left: 0;
            background-color: rgba(0,0,0,.5);
        }
        .loading-wheel {
            width: 20px;
            height: 20px;
            margin-top: 0px;
            margin-left: 100px;
            color: #42ac95;
            position: absolute;
            top: 50%;
            left: 50%;
            
            border-width: 30px;
            border-radius: 50%;
            -webkit-animation: spin 1s linear infinite;
        }
        .style-2 .loading-wheel {
            border-style: double;
            border-color: #fff transparent;
        }
        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0);
            }
            100% {
                -webkit-transform: rotate(-360deg);
            }
        }
        
        .dropzone::-webkit-scrollbar { 
            display: none;  // Safari and Chrome
        }
        
        .chatTextBox:focus {
            outline: none;
        }
        
        .chat-application .chats .chat-body .chat-content:before {
            display: none;
        }
    </style>
    <!-- end of custom css -->
  </head>

  <body class="vertical-layout vertical-menu 2-columns   menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu" data-color="bg-gradient-x-purple-blue" data-col="2-columns">
        <div class="loadingoverlay style-2"><div class="loading-wheel"></div></div>
        @include('layouts.includes.top-nav')
        @include('layouts.includes.side-nav')
        @yield('content')
        @include('layouts.includes.footer')
    
        <!-- BEGIN VENDOR JS-->
        {{ Html::script('public/app-assets/vendors/js/vendors.min.js') }}
        {{ Html::script('public/app-assets/vendors/js/forms/toggle/switchery.min.js') }}
        {{ Html::script('public/app-assets/js/scripts/forms/switch.min.js') }}
        <!-- BEGIN VENDOR JS-->
        @yield('js')
        {{ Html::script('public/app-assets/vendors/js/extensions/toastr.min.js') }}
        {{ Html::script('public/app-assets/vendors/js/forms/select/select2.full.min.js') }}
        

        <!-- BEGIN CHAMELEON  JS-->
        {{ Html::script('public/app-assets/js/core/app-menu.min.js') }}
        {{ Html::script('public/app-assets/js/core/app.min.js') }}
        {{ Html::script('public/app-assets/js/scripts/customizer.min.js') }}
        {{ Html::script('public/app-assets/vendors/js/jquery.sharrre.js') }}
        {{ Html::script('public/app-assets/dropzone.js') }}
        <!-- END CHAMELEON  JS-->
        
        <script>
            //on click chat button
            $(document).on('click', '.chatButton', function() {
                $('.chatPopup').fadeIn('slow'); 
                $('.chatButton').fadeOut('slow');
                $('.closeChatButton').fadeIn('slow');
            });
            
            //on click close chat button
            $(document).on('click', '.closeChatButton', function() {
                $('.chatPopup').fadeOut('slow');
                $('.chatButton').fadeIn('slow');
                $('.closeChatButton').fadeOut('slow');
            });
            
            $(document).ready(function () {
                $(".dropzone").dropzone({ url: "/file/post" });
            });
        </script>
        <script>
        $(document).on('click', '#startConversation', function() {
            $('.chat-1').css('display', 'none');
            $('.chat-2').css('display', 'block');
        });
        
        // Replace ./data.json with your JSON feed
        fetch('http://forms.novidtechdevelopment.com/api/v1/users')
          .then(response => {
            return response.json()
          })
          .then(data => {
            //console.log(data)
            var list;
            for(var i = 0; i < data.length; i++) {
                list = '<a href="#" data-receiver="'+data[i].id+'" data-name="'+data[i].name+'" id="chatNames" class="media border-0"><div class="media-left pr-1"><span class="avatar avatar-md avatar-online"><img class="media-object rounded-circle" src="http://forms.novidtechdevelopment.com/public/uploads/user_profile/'+data[i].avatar+'" alt="Generic placeholder image"><i></i></span></div><div class="media-body w-100"><span class="list-group-item-heading">'+data[i].name+'</span><p class="list-group-item-text mb-0"><span class="blue-grey lighten-2 font-small-3"> Online </span></p></div></a>';
                $('.media-list').append(list);
            }
            
          })
          .catch(err => {
            //alert('no data');
          });
          
          $(document).on('click', '#chatNames', function() {
                $('.userss').css('display', 'none');
                $('#receiver_id').val($(this).data('receiver'));
                $('.chatCover').css('display', 'block');
                $('.conversationTitle').text($(this).data('name'));
                $('.backToConversations').css('display', 'block');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: 'http://forms.novidtechdevelopment.com/api/v1/chat',
                    data: {
                        'receiver_id': $(this).data('receiver')
                    },
                    beforeSend: function() {
                            //toastr.info("Sending now");
                            $('.chatBody').append("<span class='chatPleaseWait'>Please wait</span>");
                    },
                    success: function(data) {
                        if ((data.errors)) {
                            //toastr.error("Ooopps! Something went wrong.");
                            console.log(data);
                        } else {
                            if(data.status == true) {
                                var chats = "";
                                var current_user_id = <?php echo Auth::user()->id; ?>;
                                var position = "";
                                for(var i = 0; i < data.data.length; i++) {
                                    if(current_user_id == data.data[i].sender_id) {
                                        position = "chat-right";
                                    } else {
                                        position = "chat-left";
                                    }
                                    chats += '<div class="chat '+position+'">';
                                    chats += '<div class="chat-avatar">'
                                    chats += '<a class="avatar" data-toggle="tooltip" href="#" data-placement="right" title="" data-original-title="">';
        						    chats += '<img src="http://forms.novidtechdevelopment.com/public/uploads/user_profile/avatar.png" alt="avatar" />';
        				  			chats += '</a>';
                                    chats += '</div>';
                                    chats += '<div class="chat-body">';
                                    chats += '<div class="chat-content">';
                                    chats += '<p>'+data.data[i].message+'</p>';
                                    chats += '</div>';
                                    chats += '</div>';
                                    chats += '</div>';
                                }
                                //console.log(data);
                                $('.chatPleaseWait').remove();
                                $('.chatBody').append(chats);
                                $(".chat-application").animate({ scrollTop: $('.chat-application').prop("scrollHeight")}, 1000);
                            }
                            
                           
                        }
                    },
                       
                });
          });
          
          $(document).on('click', '.backToConversations', function() {
                $('.userss').css('display', 'block');
                $('.chatCover').css('display', 'none');
                $('.backToConversations').css('display', 'none');
                $('.conversationTitle').text("Conversations");
                $('.chatBody').empty();
          });
          
          $(document).on('click', '#sendChat', function() {
                var receiver = $('#receiver_id').val();
                var message = $('#chatMessage').val();
                if(message == "") {
                    //dont send 
                    alert('Please enter your message');
                } else {
                    var formData = new FormData($('#chatForm')[0]);
                    $.ajax({
                        type: 'POST',
                        url: 'http://forms.novidtechdevelopment.com/api/v1/send',
                        processData: false,
                        contentType: false,
                        data: formData,
                        beforeSend: function() {
                            //toastr.info("Sending now");
                        },
                        success: function(data) {
                            if ((data.errors)) {
                                //toastr.error("Ooopps! Something went wrong.");
                                console.log(data);
                            } else {
                                //toastr.info("Message Sent");
                                var chats = "";
                                chats += '<div class="chat">';
                                chats += '<div class="chat-avatar">'
                                chats += '<a class="avatar" data-toggle="tooltip" href="#" data-placement="right" title="" data-original-title="">';
        						chats += '<img src="http://forms.novidtechdevelopment.com/public/uploads/user_profile/avatar.png" alt="avatar" />';
        				  		chats += '</a>';
                                chats += '</div>';
                                chats += '<div class="chat-body">';
                                chats += '<div class="chat-content">';
                                chats += '<p>'+data.data.message+'</p>';
                                chats += '</div>';
                                chats += '</div>';
                                chats += '</div>';
                                $('.chatBody').append(chats);
                                //$(".chat-application").animate({ scrollTop: $('.chat-application').prop("scrollHeight")}, 1000);
                                $(".chat-application").animate({
                                  scrollTop: $('.chat-application')[0].scrollHeight - $('.chat-application')[0].clientHeight
                                }, 1000);
                                console.log(data);
                            }
                        },
                        complete: function() {
                            $('#chatForm')[0].reset();
                        },
                    });
                }
          });
          
          $('#chatMessage').keypress(function (e) {
              if (e.which == 13) {
                var receiver = $('#receiver_id').val();
                var message = $('#chatMessage').val();
                if(message == "") {
                    //dont send 
                    alert('Please enter your message');
                } else {
                    var formData = new FormData($('#chatForm')[0]);
                    $.ajax({
                        type: 'POST',
                        url: 'http://forms.novidtechdevelopment.com/api/v1/send',
                        processData: false,
                        contentType: false,
                        data: formData,
                        beforeSend: function() {
                            //toastr.info("Sending now");
                        },
                        success: function(data) {
                            if ((data.errors)) {
                                //toastr.error("Ooopps! Something went wrong.");
                                console.log(data);
                            } else {
                                //toastr.info("Message Sent");
                                var chats = "";
                                chats += '<div class="chat">';
                                chats += '<div class="chat-avatar">'
                                chats += '<a class="avatar" data-toggle="tooltip" href="#" data-placement="right" title="" data-original-title="">';
        						chats += '<img src="http://forms.novidtechdevelopment.com/public/uploads/user_profile/avatar.png" alt="avatar" />';
        				  		chats += '</a>';
                                chats += '</div>';
                                chats += '<div class="chat-body">';
                                chats += '<div class="chat-content">';
                                chats += '<p>'+data.data.message+'</p>';
                                chats += '</div>';
                                chats += '</div>';
                                chats += '</div>';
                                $('.chatBody').append(chats);
                                //$(".chat-application").animate({ scrollTop: $('.chat-application').prop("scrollHeight")}, 1000);
                                $(".chat-application").animate({
                                  scrollTop: $('.chat-application')[0].scrollHeight - $('.chat-application')[0].clientHeight
                                }, 1000);
                                console.log(data);
                            }
                        },
                        complete: function() {
                            $('#chatForm')[0].reset();
                        },
                    });
                }
                return false;    //<---- Add this line
              }
            });
          
    </script>
        
  </body>
  </html>