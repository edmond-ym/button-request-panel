<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Message') }}
        </h2>
    </x-slot>

  
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            </div>
        </div>      
        <div ng-app="MessaageApp" >      
            <div ng-controller="MessageController as mcon" >      
                <div  class="container">
                        <button type="button" class="btn btn-success" ng-click="refresh();">Refresh</button></br></br>
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <div class="btn-group" role="group">
                                <button class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                  Filter By Name
                                </button>
                                
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                  <li>
                                    <div ng-repeat="i in data[0] | nicknameList" style="padding-left: 10px;padding-right:10px;">
                                        <div class="form-check">    
                                            <input class="form-check-input" type="checkbox"   ng-click="NicknameCheckboxPress(i,$event.target.checked);" id="NickNameItem"  >
                                            <label class="form-check-label" for="flexCheckChecked">@{{i}}</label> 
                                        </div> 
                                    </div>   
                                  </li>
                                </ul>
                            </div>
                            <div class="btn-group" role="group" >
                                <button class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                  Filter By Pin
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                  <li>
                                    <div  style="padding-left: 10px;padding-right:10px;">
                                        <div class="form-check">    
                                            <input class="form-check-input" type="checkbox"   ng-click="pinFilterCheckboxPress('true', $event.target.checked);" id="pinned"  >
                                            <label class="form-check-label" for="">Pinned</label> 
                                        </div>  
                                        <div class="form-check">    
                                            <input class="form-check-input" type="checkbox"   ng-click="pinFilterCheckboxPress('false', $event.target.checked);" id="not-pinned"  >
                                            <label class="form-check-label" for="">Not Pinned</label> 
                                        </div> 
                                    </div>   
                                  </li>
                                </ul>
                            </div>
                            <div class="btn-group" role="group" >
                                <button class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                  Origin
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                  <li>
                                    <div  style="padding-left: 10px;padding-right:10px;">
                                        <div class="form-check">    
                                            <input class="form-check-input" type="checkbox"   ng-click="shareFilterCheckboxPress('false', $event.target.checked);" id="pinned"  checked>
                                            <label class="form-check-label" for="">Message From My Device</label> 
                                        </div> 
                                        <div class="form-check">   
                                            <input class="form-check-input" type="checkbox"   ng-click="shareFilterCheckboxPress('true', $event.target.checked);" id="not-pinned"  >
                                            <label class="form-check-label" for="">Message Shared To Me</label> 
                                        </div> 
                                    </div>   
                                  </li>
                                </ul>
                            </div>
                        </div></br></br>
                           


                           
                           
                        <div ng-if="(data[0] | InfoFilter:{'pin':DiscreteFilterDict.pin}:'or':{'nickname':IncludeFilterDict.nickname, 'shared_to_me': DiscreteFilterDict.shared_to_me}:'or').length==0" >

                            <center><p style="position: sticky;top: 50%;left:20%;right:20%;transform:translateY(400%);"class="text-2xl">No Message here!</p></center>
                        </div>
                        <div ng-else> 
                            
                                    
                                    <div ng-repeat="i in data[0] | InfoFilter:{'pin':DiscreteFilterDict.pin}:'or':{'nickname':IncludeFilterDict.nickname, 'shared_to_me': DiscreteFilterDict.shared_to_me}:'or'">          
                                        <div >
                                            
                                            <div class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true" style="width:100%;"  > 
                                                <div class="toast-header" > 
                                                    <strong class="me-auto" >
                                                      <i ng-if="i['shared_to_me']=='true'" class="fas fa-share"></i>
                                                        @{{i['nickname']}}
                                                    </strong>
                                                    <small>@{{i['datetime'] | timeDifferenceFilter }}</small>
                                                    <div ng-if="i['pin']=='true'">
                                                      <div ng-if="i['right']!='basic'">
                                                          <button type="button" style="padding-right:10px;padding-left:10px;color:blue;" ng-click="pinMessage(i['msg_id'], 'false');"><i class="fas fa-thumbtack"></i></button>
                                                      </div>
                                                    </div>
                                                    <div ng-if="i['pin']!='true'">
                                                      <div ng-if="i['right']!='basic'">
                                                          <button type="button" style="padding-right:10px;padding-left:10px;" ng-click="pinMessage(i['msg_id'], 'true');"><i class="fas fa-thumbtack"></i></button>
                                                      </div>
                                                    </div>
                                                    <div ng-if="i['right']!='basic'">
                                                      <button type="button" class="btn-close" aria-label="Close"ng-click="deleteMessage(i['msg_id']);"></button>
                                                    </div>              
                                                </div>
                                                <div class="toast-body">@{{i['message']}}</div>
                                            </div>  
                                        </div>
                                    </div>
                                                                             
                                           
                           
                        </div>     
                    </div>
             
            </div>                     
        </div>                    
                                          
                      <script type="module">   
                        import InfoFilter from '/js/info-filter.js'; 
                        import TimeDifferenceFilter from '/js/time-filter.js'; 
                        import NicknameList from '/js/nickname-list.js'; 

                        (                    
                            function(){                    
                                angular.module('MessaageApp', [])                    
                                .controller('MessageController', MessageController)                    
                                .service('MessageRetrievingService', MessageRetrievingService) 
                                .filter("timeDifferenceFilter", TimeDifferenceFilter)      
                                .filter("InfoFilter", InfoFilter)                                  
                                .filter("nicknameList", NicknameList) 
                                .filter("NicknameFilterData", NicknameFilterData)                                       
                                .constant("MessageAPI", {Link:"{{route('msg_enquiry_api')}}", LoginSession:"{{$login_session}}"})          
                                .constant("MessageDeleteAPI", {Link:"{{route('msg_delete_api')}}", LoginSession:"{{$login_session}}" })       
                                .constant("MessagePinAPI", {Link:"{{route('msg_pin_api')}}", LoginSession:"{{$login_session}}" });          

                                MessageRetrievingService.$inject=['MessageAPI', '$http', '$interval', 'MessageDeleteAPI', 'MessagePinAPI'];
                                function MessageRetrievingService(MessageAPI, $http, $interval, MessageDeleteAPI, MessagePinAPI){
                                    this.retrievingMsg=function(){                    
                                        var interimData=[];                    
                                        $http({                    
                                            method: 'POST',                    
                                            url : MessageAPI.Link+ "/" + MessageAPI.LoginSession,
                                            headers: {                    
                                                'Content-Type': 'application/json'                    
                                            }                    
                                        }).then(function (result) {   
                                                            
                                            interimData.push(result.data);                    
                                        });                    
                                        return interimData;                    
                                    }      
                                             
                                    this.deleteMsg=function(msg_id){                    
                                        $http({                    
                                            method: 'POST',                    
                                            url : MessageDeleteAPI.Link+ "/" + MessageDeleteAPI.LoginSession + "/" + msg_id,
                                            headers: {                    
                                                'Content-Type': 'application/json'                    
                                            }                    
                                        }).then(function (result) {                    
                                                                
                                        });                    
                                    }        
                                    this.pinMsg=function(msg_id, true_false){
                                        //console.log(msg_id);
                                        $http({                    
                                            method: 'POST',                    
                                            url : MessagePinAPI.Link+ "/" + MessagePinAPI.LoginSession + "/" + msg_id+"/"+true_false,
                                            headers: {                    
                                                'Content-Type': 'application/json'                    
                                            }                    
                                        }).then(function (result) {                    
                                           //console.log(result);  
                                        });
                                    }       
                                }                    
                                                
                                MessageController.$inject=['MessageRetrievingService', '$interval', '$scope', '$http', 'MessageAPI' , '$filter'];
                                function MessageController(MessageRetrievingService, $interval, $scope, $http, $filter){
                                  
                                    $scope.data=MessageRetrievingService.retrievingMsg();                    
                                    $scope.deleteMessage=function($Message_ID){                    
                                      MessageRetrievingService.deleteMsg($Message_ID);                    
                                      $scope.data=MessageRetrievingService.retrievingMsg();                    
                                    }                   
                                    $scope.refresh=function(){                    
                                        $scope.data=MessageRetrievingService.retrievingMsg();                    
                                    }      
                                    //$scope.filter=[];            
                                    $scope.IncludeFilterDict={
                                        'nickname':[]
                                    }
                                    $scope.DiscreteFilterDict={
                                        'pin':[],
                                        'shared_to_me':['false']//True or False
                                    }

                                    $scope.NicknameCheckboxPress=function(nickname, status){
                                        if (String(status)=="true") {
                                            $scope.IncludeFilterDict.nickname.push(nickname);
                                        }else{
                                            $scope.IncludeFilterDict.nickname=$scope.IncludeFilterDict.nickname.filter(function(a) { return a !== nickname })
                                        }
                                    }  
                                    $scope.pinFilterCheckboxPress=function(type, status){    
                                        
                                        if (String(status)=="true") {
                                            $scope.DiscreteFilterDict.pin.push(type);
                                        }else{
                                            $scope.DiscreteFilterDict.pin=$scope.DiscreteFilterDict.pin.filter(function(a) { return a !== type })
                                        }
                                    }
                                    $scope.shareFilterCheckboxPress=function(type, status){
                                        // Shared To Me: True or False
                                        if (String(status)=="true") {
                                            $scope.DiscreteFilterDict.shared_to_me.push(type);
                                        }else{
                                            $scope.DiscreteFilterDict.shared_to_me=$scope.DiscreteFilterDict.shared_to_me.filter(function(a) { return a !== type })
                                        }
                                    }

                                    $scope.pinMessage=function(msg_id, true_false){
                                        MessageRetrievingService.pinMsg(msg_id, true_false);                    
                                    } 

                                                        
                                    $interval(function(){                    
                                                            
                                        $scope.data=MessageRetrievingService.retrievingMsg();                    
                    
                                    }, 500);
                                    
                                }
                                
                                function NicknameFilterData(){
                                    
                                    return function(NicknameArray, CurrentDataArray){
                                        FilteredArray=[];
                                        for (let index = 0; index < CurrentDataArray.length; index++) {
                                            
                                            if(NicknameArray.includes(CurrentDataArray[index].nickname)){
                                                FilteredArray.push(CurrentDataArray[index]);
                                            }
                                        }
                                        return FilteredArray;
                                    }
                                }

                                
                            }
                        )();
                        
                        
                      </script>
           
        </div>
    
  
</x-app-layout>
