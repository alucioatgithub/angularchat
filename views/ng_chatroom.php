<style type="text/css">
.txtarea-sec .qq-upload-button-selector.qq-upload-button {
    background: none repeat scroll 0 0 #428bca;
    border-bottom-left-radius: 10px;
    border-top-left-radius: 10px;
    height: 40px;
    position: absolute !important;
    top: 0;
    width: 40px;
}
.txtarea-sec .qq-upload-button-selector.qq-upload-button > a {
    color: #fff;
    display: block;
    font-size: 18px;
    line-height: 40px;
    text-align: center;
}

.model-body{
    text-align: center;
}

.online-list li {
    cursor: pointer;
}

.fullscreenWindow {position: fixed; top: 0px; left: 0px; bottom: 0px; z-index: 99999; width: 100% !important}
.fullscreenWindow .right-block-npad,.fullscreenWindow .left-item-section{height: 100%;}
.fullscreenWindow .right-item-section,.fullscreenWindow .messages{height: 100%;}
.fullscreenWindow .messages ul#chatroomscroll {height: 80%;}
.fullscreenWindow .textarea_panel{bottom: 7%;}
.normal,.fullscreen{
    background: none repeat scroll 0 0 #f4f4f4;
    border: 1px solid #ddd;
    border-radius: 50%;
    color: #aaa;
    font-size: 16px;
    height: 35px;
    padding: 5px;
    position: absolute;
    right: 20px;
    top: 7px;
    text-align: center;
    width: 35px;
    outline: none!important;
}

.fc_btn_holder2 {
border-radius: 50% !important;
height: 35px !important;
margin: 0 10px;
overflow: hidden !important;
width: 35px !important;
position: relative;
top: 3px;
left: 10px;
}

#send_chat button {
background: none repeat scroll 0 0 #2f8fc9!important;
height: 35px !important;
padding: 0 !important;
position: absolute;
width: 35px !important;
margin: 0;
border-radius: 50%;
}


</style>
<!-- Krita - CSS for the overlay effect on hover -->
<link rel="stylesheet" href="http://manish/campaign/portal/assets/css/hover.css" />



<div class="right-block-npad" ng-app="chat_system" ng-controller="ChatController as chat">

    <div class="left-item-section">
        <div>
            <div id="tabs">

                <ul class="tab-sec">
                    <li ng-click="set_active_tab('online')" ng-class="{'ui-state-active':is_tab_set('online')}">
                        <a href="javascript:;">Online</a>
                    </li>
                    <li ng-click="set_active_tab('offline')" ng-class="{'ui-state-active':is_tab_set('offline')}">
                        <a href="javascript:;">History</a>
                    </li>
                    <div class="clear-fix"></div>
                </ul>

                <div class="form-sch">
                    <!-- <a href="#" id="toogleScreen"  class="normal">fullscreen</a> --> 
                    <!-- <button ng-click="playSound('notification');">Play</button>  -->
                    <form >
                        <input type="text" placeholder="Search.." ng-model="search_user">
                    </form>
                </div>

                <div id="tabs-1" ng-show="is_tab_set('online')">

                    <ul class="online-list">

                        <li id="{{online_user.user_id}}" class="chat_user" ng-repeat="online_user in online_users | filter:search_user" ng-class="{'current_active_user': active_user == online_user.user_id}" ng-click="get_chat_logs(online_user.user_id)">
                            <div>
                                <div class="left-details">
                                    <p><strong>{{online_user.username}}</strong></p>
                                    <small>{{online_user.message}}</small>
                                </div>
                                <div class="right-details">
                                    <span class="smallest-txt block-center"> {{online_user.created_at}} / <strong><i>{{online_user.user_id ? 'Member' : 'Guest'}}</i></strong></span>
                                    <span class="newmsg-notif" ng-show="online_user.unread_msg">{{online_user.unread_msg}}</span>
                                </div>
                                <div class="clear-fix"></div>
                            </div>
                        </li>                        

                    </ul>
                    
                </div>

                <div id="tabs-2" ng-show="is_tab_set('offline')">

                    <ul class="online-list">

                        <li id="{{offline_user.user_id}}" class="chat_user" ng-repeat="offline_user in offline_users | filter:search_user" ng-class="{'current_active_user': active_user == offline_user.user_id}" ng-click="get_chat_logs(offline_user.user_id)">
                            <div>
                                <div class="left-details">
                                    <p><strong>{{offline_user.username}}</strong></p>
                                    <small>{{offline_user.message}}</small>
                                </div>
                                <div class="right-details">
                                    <span class="smallest-txt block-center"> {{offline_user.created_at}} / <strong><i>{{offline_user.user_id ? 'Member' : 'Guest'}}</i></strong></span>
                                    <span class="newmsg-notif" ng-show="offline_user.unread_msg">{{offline_user.unread_msg}}</span>
                                </div>
                                <div class="clear-fix"></div>
                            </div>
                        </li>                        

                    </ul>

                </div>

            </div>
        </div>
    </div>

    <div class="right-item-section">
        <div class="header-message">
            <a class="normal" id="toogleScreen" href="#"><i class="fa fa-expand"></i>
</a>
            <div class="header-title-sec">
            </div>
            <!-- <a href="#"><span class="action-btn"><i class="fa fa-cog"></i></span></a> -->
            <div style="height:32px;"><p class="no-mg-p">{{active_username}}</p> <span class="small-texts">{{last_online}}</span></div>
            <div class="clear-fix"></div>
        </div>

        <div class="messages">


            <!-- chat logs start -->

            <ul id="chatroomscroll">
               

                <li ng-repeat="chat_log in chat_logs">

                    <div class="{{get_message_class(chat_log)}}">
                        
                        <div ng-class="{'arrow-right': chat_log.is_reply == 1, 'arrow-left': chat_log.is_reply == 0}"></div>
                        <div ng-class="{'arrow-right1': chat_log.is_reply == 1, 'arrow-left1': chat_log.is_reply == 0}"></div>


                        <span ng-class="{'chat-img admin pull-right' : chat_log.is_reply == 1, 'chat-img user pull-left' : chat_log.is_reply == 0}" > &nbsp; </span>
                        

                        <span ng-class="{'mess-admin': chat_log.is_reply == 1, 'mess-user': chat_log.is_reply == 1}" class="pull-left" style="width:100%;padding:5px;" >            
                            <p class="md-txt">
                                
                                <span ng-if="!chat_log.file">{{chat_log.message}}</span>

                                    <div id="hov" class="effects clearfix" ng-if="chat_log.file && chat_log.mime_type == <?php echo CHAT_IMAGE ?>">
                                        <div class="img">
                                            <img ng-src="{{site_url+'uploads/chat/'+chat_log.file_name}}" alt="">
                                            <div class="overlay">
                                                <a href="#" class="expand" data-toggle="modal" data-target="#file{{chat_log.file}}" title="View" style="left:-50px;">+</a>
                                                <a href="{{site_url+'chatroom/download_file/'+chat_log.file_name}}" class="expand" title="Download" style="left:50px;"><i class="fa fa-cloud-download"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                            


                                <div ng-if="chat_log.file && chat_log.mime_type != <?php echo CHAT_IMAGE ?>">
                                    <span class="img-chat">{{chat_log.file_name}}</span>
                                    <a href="{{site_url+'chatroom/download_file/'+chat_log.file_name}}" class="btn">Download</a>
                                </div>


                            </p>
                        </span>

                        <div class="clear-fix"></div>
                        <span class="smallest-txt" ng-class="{'left-position':chat_log.is_reply == 1, 'right-position':chat_log.is_reply == 0}">
                            {{chat_log.created_at}}
                        </span>

                    </div>
                    <div class="clear-fix"></div>
                        
                        <div ng-if="chat_log.file" class="modal fade" id="file{{chat_log.file}}" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                                    <h4 class="modal-title">Image Preview</h4>
                                    </div>
                                    <div class="modal-body model-content-inner">
                                        <img ng-src="{{site_url+'uploads/chat/'+chat_log.file_name}}" /></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                          </div>
                        </div>                        
                    
                </li>




            </ul>




            <!-- chat logs end -->

            <div class="textarea_panel">
                
                <div fine-uploader
                        chat-logs = "{{chat_logs}}"
                        upload-server="chatroom/ng_upload_files/"
                        max-file-size="10000000"
                        large-preview-size="500"
                        allowed-mimes="image/jpeg, image/png, image/gif, image/tiff, image/bmp"
                        allowed-extensions="*"></div>

            </div>

        </div>
        <div class="clear-fix">&nbsp;</div>

    </div>

    <div class="clearfix"></div>

    <!-- JS file -->
    <script type="text/javascript" src="<?php echo js_url() ?>hover.js"></script>
    <script type="text/javascript" src="<?php echo js_url() ?>angular.min.js"></script>
    
    <script type="text/javascript" src="<?php echo $admin_path ?>/js/bootstrap.min.js"></script>

    <!-- <div class="play"></div> -->

     
    <div id="sound"></div>


<script type="text/javascript">
    // @param filename The name of the file WITHOUT ending
    // function playSound(filename){
    //     filename = base_path + 'assets/sound/' + filename;
    //     document.getElementById("sound").innerHTML='<audio autoplay="autoplay"><source src="' + filename + '.mp3" type="audio/mpeg" /><source src="' + filename + '.ogg" type="audio/ogg" /><embed hidden="true" autostart="true" loop="false" src="' + filename +'.mp3" /></audio>';
    // }

(function($) {    

// playSound('notification');

    // $.extend({
    //   playSound: function(){
    //     console.log(arguments[0]);
    //     return $("<embed src='"+arguments[0]+"' hidden='true' autostart='true' loop='false' class='playSound'>").appendTo('.play');
    //   }
    // });

    // // $.playSound(base_path + 'assets/sound/' + $(this).val() + ".mp3");
    // console.log(base_path + 'assets/sound/' + "notification.mp3");
    // $.playSound(base_path + 'assets/sound/' + "notification.mp3");

    
    var app = angular.module('chat_system', []);

    app.controller('ChatController', function($scope, $http) {
        // var $scope = $scope;
        $scope.can_send_message = true;

        $scope.unread_chat_history = [];

        $scope.active_username = '';

        $scope.last_online = '';

        $scope.active_user = 0;

        $scope.active_tab = '';

        $scope.message = '';

        $scope.chat_logs = [];

        $scope.online_users = [];

        $scope.offline_users = [];

        $scope.foo = 0;

        $scope.fetched_chat_ids = [];

        

        $scope.fetch_all_chat_ids = function() {
            $http.post(site_url+'chatroom/userchat/fetch_all_chat_ids').
            success(function(data) {  
                $scope.fetched_chat_ids = data.all_chat_ids;
                $scope.get_active_users();
            });

        }
        

        $scope.playSound = function(filename) {
            filename = base_path + 'assets/sound/' + filename;
            document.getElementById("sound").innerHTML='<audio autoplay="autoplay"><source src="' + filename + '.mp3" type="audio/mpeg" /><source src="' + filename + '.ogg" type="audio/ogg" /><embed hidden="true" autostart="true" loop="false" src="' + filename +'.mp3" /></audio>';            
        }

        $scope.get_message_class = function(chat_log) {
            var message_class = '';
            
            if(chat_log.is_reply == 1) {
                message_class = 'admin-mesg';
                // if(!chat_log.file)
                //     message_class += ' admin-mesg-txt';
            } else {
                message_class = 'user-mesg';
                // if(!chat_log.file)
                //     message_class += ' user-mesg-txt';
            }
            return message_class;
        }

        $scope.set_active_tab = function(tab) {
            $scope.active_tab = tab;
        }

        $scope.is_tab_set = function(tab) {
            return $scope.active_tab == tab;
        }

        $scope.set_active_user = function(user_id) {
            $scope.active_user = user_id;
            $scope.unread_chat_history[user_id] = 0;
            if($scope.online_users.length > 0) {
                angular.forEach($scope.online_users, function(online_user, i) {
                    if(online_user.user_id == user_id) {
                        $scope.active_username = online_user.username;
                        $scope.last_online = '';
                        return;
                    }
                });
            }
            if($scope.offline_users.length > 0) {
                angular.forEach($scope.offline_users, function(offline_user, i) {
                    if(offline_user.user_id == user_id) {
                        $scope.active_username = offline_user.username;
                        $scope.last_online = offline_user.last_online=='Never' ? 'Never Online' : 'Last online at '+offline_user.last_online;
                        return;
                    }
                });
            }
        }

        $scope.get_active_users = function() {
            // console.log($scope.unread_chat_history);
            // console.log($scope.fetched_chat_ids);
            $http.post(site_url+'chatroom/userchat/get_users', {fetched_chat_ids: $scope.fetched_chat_ids, active_user : $scope.active_user}).
            success(function(data) {
                $scope.online_users = data.online_users;
                $scope.offline_users = data.offline_users;

                var current_user = 0;
                if( data.online_users.length > 0 ) {
                    current_user = $scope.active_user ? $scope.active_user : data.online_users[0].user_id;
                    if($scope.active_user == 0)
                        $scope.set_active_tab('online');

                    $scope.notify_new_message(data.online_users);

                } else if( data.offline_users.length > 0 ) {
                    current_user = $scope.active_user ? $scope.active_user : data.offline_users[0].user_id;
                    if($scope.active_user == 0)
                        $scope.set_active_tab('offline');

                    // $scope.notify_new_message(data.offline_users);
                } else {
                    $scope.chat_logs = [];
                    $scope.foo = setTimeout($scope.get_active_users, 5000);
                    return false;
                }

                $scope.get_chat_logs(current_user);

            });
        }

        $scope.notify_new_message = function(online_users) {
            angular.forEach(online_users, function (online_user, i) {
                // console.log(online_user.username+' is online.');
                if(online_user.unread_msg > 0) {
                    var old_unread = $scope.unread_chat_history[online_user.user_id];
                    // console.log(old_unread);
                    if(old_unread == undefined || online_user.unread_msg > old_unread) {
                        $scope.playSound('notification');
                        // console.log('buzz');
                    }
                    $scope.unread_chat_history[online_user.user_id] = online_user.unread_msg;
                }                        
                // notifyMe(online_user.username+' is online.');
                // var notification = new Notification(' is online.');
            });            
        }

        $scope.get_chat_logs = function (user_id) {

            clearTimeout($scope.foo);

            $http.post(site_url+'chatroom/userchat/get_chat_logs/'+user_id, {first_fetch: $scope.active_user != user_id ? 'true' : 'false', fetched_chat_ids : $scope.fetched_chat_ids }).
            success(function(data) {
                // $scope.chat_logs = data.chat_logs;
                $scope.fetched_chat_ids = $scope.fetched_chat_ids.concat(data.fetched_chat_ids);
                // console.log($scope.fetched_chat_ids);

                if($scope.active_user != user_id) //to clear chat logs before fetching chat logs of new user
                    $scope.chat_logs = [];

                if(data.chat_logs.length > 0) {
                    angular.forEach(data.chat_logs, function(chat_log, i) {
                        $scope.chat_logs.push(chat_log);
                    });
                }
                
                if(user_id != $scope.active_user || data.chat_logs.length > 0)
                    $("#chatroomscroll").scrollTop = $("#chatroomscroll").animate({scrollTop: 1000000000});

                $scope.set_active_user(user_id);
                $scope.foo = setTimeout($scope.get_active_users, 5000);
            });

        };

        $scope.send_message = function () {

            if($scope.message == '' || !$scope.can_send_message) {
                return false;
            }
            $scope.can_send_message = false;

            var user_id = $scope.active_user;

            $http.post(site_url+'chatroom/userchat/ng_save_message', {user_id: user_id, message: $scope.message}).
            success(function(data) {
                
                if(data.status == 'true') {
                    $scope.fetched_chat_ids.push(data.chat_message.chat_id);
                    $scope.chat_logs.push(data.chat_message);
                    $scope.message = '';
                    $("#chatroomscroll").scrollTop = $("#chatroomscroll").animate({scrollTop: 100000000});
                }
            }).then(function() {
                $scope.can_send_message = true;
            });

        }

        $scope.fetch_all_chat_ids();
        // $scope.foo = setInterval($scope.get_active_users, 3000);



        
    });

        app.directive('ngEnter', function () {
            return function (scope, element, attrs) {
                element.bind("keydown keypress", function (event) {
                    if(event.which === 13) {
                        scope.$apply(function (){
                            scope.$eval(attrs.ngEnter);
                        });

                        event.preventDefault();
                    }
                });
            };
        });

        app.directive("fineUploader", function($compile, $interpolate) {
            return {
                restrict: "A",
                replace: true,

                link: function(scope, element, attrs) {
                    console.log(scope.chat_logs);
                    var endpoint = attrs.uploadServer,
                        notAvailablePlaceholderPath = attrs.notAvailablePlaceholder,
                        waitingPlaceholderPath = attrs.waitingPlaceholder,
                        acceptFiles = attrs.allowedMimes,
                        sizeLimit = attrs.maxFileSize,
                        largePreviewSize = attrs.largePreviewSize,
                        allowedExtensions = $.map(attrs.allowedExtensions.split(","), function(extension) {
                            return $.trim(extension);
                        });

                    $(element).fineUploader({
                      // debug: true,
                      request: {
                        endpoint: endpoint
                      },
                      template: 'qq-chat-template',
                      multiple: false,
                      validation: {
                        acceptFiles: acceptFiles,
                        allowedExtensions: allowedExtensions,
                        // allowedExtensions: ['jpeg', 'jpg', 'gif', 'png', 'txt'],
                        sizeLimit: sizeLimit  // 50 kB = 50 * 1024 bytes
                      },
                      display: {
                          prependFiles: true
                      },
                      failedUploadTextDisplay: {
                          mode: "custom"
                      },
                      // showMessage: function(message) {
                      //     applyNewText("errorMessage", $scope, message);
                      //     $("#errorDialog").modal("show");
                      // },

                      callbacks: {
                        // onSubmitted: function(id, name) {
                        //     // var $file = $(this.getItemByFileId(id)),
                        //     //     $thumbnail = $file.find(".qq-thumbnail-selector");
                        //     // $thumbnail.click(function() {
                        //     //     openLargerPreview($scope, $(element), largePreviewSize, id, name);
                        //     // });
                        //     console.log('file submitting');
                        //     bindToRenderedTemplate($compile, scope, $interpolate, element);
                        // },
                        onComplete: function(id, fileName, responseJSON) {
                            
                            if (responseJSON.status == 'success') {
                                // $('.error').hide();
                                //$(".qq-upload-status-text").text("File Uploaded Successfully.");
                                $(".qq-upload-fail").hide();
                                // attrs.chatLogs = [];
                                //append li 
                                // $('#chatroomscroll').append(responseJSON.page);

                                //scroll to the bottom
                              
                            }
                            else if(responseJSON.status == 'false')
                            {
                                $('.qq-upload-status-text').html(responseJSON.error);
                                $(".qq-upload-fail").show();
                            }
                        }
                      }
                    }).on('submit', function (event, id, filename) {
                            $(this).fineUploader('setParams', { 'user_id': $('.current_active_user').attr('id')});
                        });

                    bindToRenderedTemplate($compile, scope, $interpolate, element);
                }
            }
        });


    function bindToRenderedTemplate($compile, scope, $interpolate, element) {
        $compile(element.contents())(scope);

    }

    function notifyMe(message) {
      // Let's check if the browser supports notifications
      if (!("Notification" in window)) {
        alert("This browser does not support desktop notification");
      }

      // Let's check if the user is okay to get some notification
      else if (Notification.permission === "granted") 
      {
        // If it's okay let's create a notification
        var notification = new Notification(message);
      }

      // Otherwise, we need to ask the user for permission
      // Note, Chrome does not implement the permission static property
      // So we have to check for NOT 'denied' instead of 'default'
      else if (Notification.permission !== 'denied') {
        Notification.requestPermission(function (permission) {

          // Whatever the user answers, we make sure we store the information
          if(!('permission' in Notification)) {
            Notification.permission = permission;
          }

          // If the user is okay, let's create a notification
          if (permission === "granted") {
            var notification = new Notification(message);
          }
        });
      }

      // At last, if the user already denied any notification, and you 
      // want to be respectful there is no need to bother him any more.
    }


    $('#toogleScreen').on('click', function(e){

        e.preventDefault();
       
       if($(this).hasClass('normal') )
       {
            $(this).removeClass('normal');
            $(this).addClass('fullscreen');
            $(this).html('<i class="fa fa-compress"></i>');
            $('#full-width-box').addClass('fullscreenWindow');
             var docElement, request;

            docElement = document.documentElement;
            request = docElement.requestFullScreen || docElement.webkitRequestFullScreen || docElement.mozRequestFullScreen || docElement.msRequestFullScreen;

            if(typeof request!="undefined" && request){
                request.call(docElement);
            }
       }
       else
       {
            $(this).removeClass('fullscreen');
            $(this).addClass('normal');
            $(this).html('<i class="fa fa-expand"></i>');
            $('#full-width-box').removeClass('fullscreenWindow');
             var docElement, request;

        docElement = document;
        request = docElement.cancelFullScreen|| docElement.webkitCancelFullScreen || docElement.mozCancelFullScreen || docElement.msCancelFullScreen || docElement.exitFullscreen;
        if(typeof request!="undefined" && request){
            request.call(docElement);
        }

       }

    });

})(jQuery);



</script>






    <!-- Template for messages -->
    <script id="chat_header" type="text/template">
    <h5>{{name}}</h5>
    <small>
    {{#online}} Online  {{/online}} 
    {{^online}} Offline  {{/online}}
    </small>
    </script>


<!-- Fine Uploader template
    ====================================================================== -->
    <script type="text/template" id="qq-chat-template">
    <div class="qq-uploader-selector qq-uploader">
    
        <ul class="qq-upload-list-selector qq-upload-list" style="background-color: #f6f6f6!important;color: #444 !important;font-size: 11px !important; list-style: none">
            <li style="padding: 10px; ">
                <div class="qq-progress-bar-container-selector">
                    <div class="qq-progress-bar-selector qq-progress-bar"></div>
                </div>

                <span class="qq-upload-spinner-selector qq-upload-spinner fa fa-spin fa-refresh"></span>&nbsp;&nbsp;
                <span class="qq-upload-file-selector qq-upload-file"></span>
                <span class="qq-upload-size-selector qq-upload-size"></span>
                <a class="qq-upload-cancel-selector qq-upload-cancel" href="#">X</a>
                <span class="qq-upload-status-text-selector qq-upload-status-text" style="font-style:italic;margin-left:20px;"></span>
            </li>
        </ul>

    
        <div class="textarea-inner">
            <form>
                <div class="txtarea-sec">
                    <div class="qq-upload-button-selector qq-upload-button">
                        <a href="javascript:;"><span><i class="fa fa-paperclip"></i></span></a>
                    </div>

                    <textarea ng-enter="send_message()" placeholder="Write your message here.." name="chat_message" id="fc_chat_mail" ng-model="message"></textarea>
                </div>

                <div id="send_chat" class="fc_btn_holder2" ng-click="send_message()" style="cursor: pointer">
                    <button type="button" class="chat-btn"><i class="fa fa-arrow-right"></i>
</button>
                </div>

                <div class="clear-fix"></div>
            </form>
        </div>

    </div>
    </div>
    </script>