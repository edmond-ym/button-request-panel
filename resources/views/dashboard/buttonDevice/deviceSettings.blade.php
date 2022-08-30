<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Individual Device Data') }}
        </h2>
    </x-slot>
    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            </div>
        </div>
    </div>
    
      
      <div class="container">
        <div class="accordion" id="accordionExample" hidden>
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Device API
              </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse " aria-labelledby="headingOne" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <h4 class="alert-heading">Device API</h4>
                  <p>You may send the request using the link below. </p>
                <div class="alert alert-success" role="alert">

                  <div style="display:flex; align-items:baseline;">
                    <p class="mb-0">Link:&nbsp;  </p><span>{{route('deviceAPI.v1',['device_id'=>$data->device_id])}}</span>
                  </div>
                  <p class="mb-0">Bearer Token Required</p>
                  <p class="mb-0">Query Parameters: button_id</p>
                  
                </div>
              </div>
            </div>
          </div>
          
        </div>
        
                @if ($result=='success')
                <h3 class="text-dark mb-1" >Device Id: {{$data->device_id}}</h3>

                <form method="post" action="{{route('device_amend')}}">
                  @csrf
                  <div class="mb-3">
                    <input type="text" class="form-control" id="case_id" name="case_id" value="{{$data->case_id}}"hidden required>
                  </div>
                  <div class="mb-3">
                    <label for="device_id" class="col-form-label">Device Id:</label>
                    <input type="text" class="form-control" id="device_id" name="device_id" value="{{$data->device_id}}" readonly>
                  </div>
        
                  <div class="mb-3">
                    <label for="nickname" class="col-form-label">Device Nickname:</label>
                    <input type="text" class="form-control" id="nickname" name="nickname" value="{{$data->nickname}}" >
                  </div>
        
                  <!---------->
                    <div ng-app="FormApp">
                        <div ng-controller="FormController as formCon">
                          <div class="mb-3">
                              <label class="col-form-label">Message For Different Button:</label>
                              <button type="button" class="btn btn-primary"ng-click="formCon.addNewButton();">New</button></br>
                              </br>
                              <div class="card" ng-repeat="item in formCon.infoList">
                               
                                <div class="card-body">
                                  <!--<h5 class="card-title">Button</h5>-->
                                  <div class="text-right">
                                    <button type="button" ng-click="formCon.deleteButton($index);" class="btn-close float-right" aria-label="Close"></button>
                                  </div>
                                  <div class="input-group  input-group-sm">
                                    <span class="input-group-text">ID and Nickname</span>
                                    <input type="text" aria-label="Button ID" ng-model="item.buttonNo" placeholder="Button ID" class="form-control">
                                   
                                    <input type="text" aria-label="Nickname" ng-model="item.nickname" placeholder="Nickname" class="form-control">
                                  
                                  </div>
                                  </br> 
                                  <div class="input-group input-group-sm mb-3">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">Message</span>
                                    <input type="text" class="form-control" ng-model="item.message" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                                  </div>
                                  
                                </div>
                              </div>

                              <!--<div class="input-group mb-3"ng-repeat="item in formCon.infoList">
                                <input type="text" class="form-control" ng-model="item.buttonNo"placeholder="Button ID" required>
                                <span class="input-group-text">:</span>
                                <input type="text" class="form-control" ng-model="item.message" placeholder="Message" required>
                                <input type="text" class="form-control" ng-model="item.nickname" placeholder="Nickname" required>

                                <button type="button" class="btn btn-primary" ng-click="formCon.deleteButton($index);">Delete</button>
                              </div>-->
                              <textarea class="form-control" id="info" rows="3" name="info" value="{{ old('info') }}" hidden readonly>@{{formCon.infoList}}</textarea>
                              @error('info')<div class="alert alert-danger">{{ $message }}</div>@enderror

                              <div class="form-check">
                                <input class="form-check-input" type="radio" name="repeated_msg" id="repeated_msg" value="" 
                                @if($data->repeated_message=='')
                                    checked                           
                                @endif  
                                >
                                <label class="form-check-label" for="repeated_msg">
                                  Allow Repeated Messages
                                </label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="radio" name="repeated_msg" id="repeated_msg" value="no"
                                @if($data->repeated_message=='no')
                                    checked                           
                                @endif  
                                >
                                <label class="form-check-label" for="repeated_msg">
                                  Disallowed Repeated Messages
                                </label>
                              </div>
                              
                          </div>
                          
                        </div>       
                    </div>                        

        
                    <script>
                        angular.module('FormApp', [])
                        .controller('FormController', FormController)
                        .service('EmailService', EmailService)
                        .constant("EmailAPI", {Link:"http://localhost/device_data_email_check"});        
                        EmailService.$inject=['$http', 'EmailAPI'];
                        function EmailService($http, EmailAPI){
                          this.emailExistCheck=function(email){
                            var a=$http({                    
                              method: 'POST',                    
                              url : EmailAPI.Link+ "/" + email,
                              headers: {                    
                                  'Content-Type': 'application/json'                    
                              }                    
                            }).then(function (result) {                 
                              return result.data.result;
                            });  
                            return a.$$state;
                          }
                        }
                        FormController.$inject=['EmailService'];
                        function FormController(EmailService) {
                            @if($info_len==0)
                              this.infoList=[]
                            @else
                              this.infoList={!! $info !!} 
                            @endif
                            
                            this.infoList.forEach(element => {
                              if (!('nickname' in element)) {
                               element['nickname']="";
                              }
                            });
                            this.addNewButton=function(){
                              this.infoList.push({buttonNo:"", message:""});
                            }
                            this.deleteButton=function(index){
                              this.infoList.splice(index,1);
                            }              
                        }
                    </script>
                  <!---------->
        
                  <div class="mb-3" hidden>
                    <label for="newBearerToken" class="col-form-label">New Bearer Token:</label>
                    <input type="text" class="form-control" id="newBearerToken" name="newBearerToken" disabled/>
                  </div>
                  <div class="modal-footer">
                    @if ($backRouteName=="deviceList")
                        <a type="button" class="btn btn-secondary" href="{{ route('deviceList') }}">Close</a>
                    @elseif($backRouteName=="deviceSharedToMe")
                        <a type="button" class="btn btn-secondary" href="{{ route('deviceSharedToMe') }}">Close</a>

                    @else
                        
                    @endif
                    
                    <button type="submit" class="btn btn-primary">Amend</button>
                  </div>
                </form>            
              @else
              <center><p style="position: sticky;top: 50%;left:20%;right:20%;transform:translateY(400%);"class="text-2xl">No Privilege</p></center>
            
              @endif
      </div>
        

          
    
</x-app-layout>

       
                        
                           
           
