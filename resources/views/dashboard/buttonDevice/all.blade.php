<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>


<div ng-app="deviceListApp">
<x-app-layout >
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Device List') }}
        </h2>
    </x-slot>

    @error('device_case')
    <div class="alert alert-danger" role="alert">
      {{$message}}
    </div>
    @enderror
    
    <script type="module">
      import TimeDifferenceFilter from '/js/time-filter.js'; 
      import NicknameList from '/js/nickname-list.js'; 
      import InfoFilter from '/js/info-filter.js'; 

      angular.module('deviceListApp', [])
      .filter("timeDifferenceFilter", TimeDifferenceFilter)
      .filter("nicknameList", NicknameList) 
      .filter("InfoFilter", InfoFilter)  
      .controller("NewDeviceQRScanController", function ($scope){
        const html5QrCode = new Html5Qrcode( "reader");
        $scope.newDeviceNickname="dd";
        $scope.step=0;
        //$scope.readResult=[{"device_id": "", "bearer_token": ""}];
        $scope.scanQR=function(e){
          if (e.target.files.length == 0) {
            return;
          }
          const imageFile = e.target.files[0];
          html5QrCode.scanFile(imageFile, true)
          .then(decodedText => {
            try{
              var json=JSON.parse(decodedText);
              if("device_id" in json && "bearer_token" in json){
                //$scope.readResult.push({"device_id": json.device_id, "bearer_token": json.bearer_token})
                console.log({"device_id": json.device_id, "bearer_token": json.bearer_token})
              }
              else{
                alert("Non valid Json")
              }
            }catch{
              alert("Non Valid Json");
            }
          })
          .catch(err => {
            console.log(`Error scanning file. Reason: ${err}`)
          });
        }
       

      })                                
      /*.controller('FormController', function() {
          this.infoList=[
              {buttonNo:"", message:""}
          ]
          this.addNewButton=function(){
            this.infoList.push({buttonNo:"", message:""});
          }
      })*/.controller('TableController', function($scope, SearchService) {
        $scope.TableArray=SearchService.TableArray;
        $scope.IncludeFilterDict=SearchService.IncludeFilterDict;
        $scope.DiscreteFilterDict=SearchService.DiscreteFilterDict;

        $scope.openRevealBearerTokenWindow=function($device_id){

          window.open("{{route('openRevealBearerTokenWindow')}}/"+$device_id, "_blank", "toolbar=yes, scrollbars=yes, resizable=no, top=400,left=500,width=400, height=500");
        }
        
      }).controller('SearchController', function(SearchService, $scope){
        $scope.TableArray=SearchService.TableArray;

        $scope.statusFilterCheckboxPress=function(status, checked){
          if ((checked)==true) {
            SearchService.DiscreteFilterDict.status.push(status);
          }else{
            SearchService.DiscreteFilterDict.status=SearchService.DiscreteFilterDict.status.filter(function(a) { return a !== status });
          }
        }
        $scope.IncludeFilterDict=SearchService.IncludeFilterDict;
        $scope.DiscreteFilterDict=SearchService.DiscreteFilterDict;
        
      }).service('SearchService', function(){
        this.TableArray={!! $data !!};

        this.IncludeFilterDict={
            'nickname':[''],
        }
        this.DiscreteFilterDict={
            'status':[],
        }

      });
    </script>     
                                 
    <div class="btn-group" role="group" >
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
          <li>
            <div  style="padding-left: 10px;padding-right:10px;">
                <div class="form-check">    
                    <input class="form-check-input" type="checkbox"   ng-click="statusFilterCheckboxPress('active', $event.target.checked);"  >
                    <label class="form-check-label" for="">Active</label> 
                </div>  
                <div class="form-check">    
                    <input class="form-check-input" type="checkbox"   ng-click="statusFilterCheckboxPress('suspend', $event.target.checked);"   >
                    <label class="form-check-label" for="">Suspended</label> 
                </div> 
            </div>   
          </li>
        </ul>
    </div>


    
<form method="post" action="{{route('device_list_action')}}">
    @csrf
    <!----------------------------->
      <div class="container">
          <div class="flex flex-col">
           <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
             <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
               <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                
                <a class="btn btn-primary" href="{{ route('newDeviceWizard') }}?back={{Route::currentRouteName()}}"> Add Device</a>
              <div class="btn-group" hidden >
                <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                  New Device
                </button>
                <ul class="dropdown-menu">
                  <li>
                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#newDeviceByGenCred" >Generating Credentials Here</button>

                  </li>
                  <li>
                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#newDeviceByScanQR" >Scanning QR Code</button>
                    
                  </li>
                  

                </ul>
              </div><br><br>

              <div class="input-group mb-3" ng-controller="SearchController">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Status</button>
                <ul class="dropdown-menu">
                  <li>
                    <div  style="padding-left: 10px;padding-right:10px;">
                      <div class="form-check">    
                          <input class="form-check-input" type="checkbox"   ng-click="statusFilterCheckboxPress('active', $event.target.checked);"  >
                          <label class="form-check-label" for="">Active</label> 
                      </div>  
                      <div class="form-check">    
                          <input class="form-check-input" type="checkbox"   ng-click="statusFilterCheckboxPress('suspend', $event.target.checked);"   >
                          <label class="form-check-label" for="">Suspended</label> 
                      </div> 
                  </div> 
                  </li>
                </ul>
                <input type="text" class="form-control" placeholder="Search by Nickname" ng-model="IncludeFilterDict.nickname[0]">
                
              </div>
               
               <table class="min-w-full divide-y divide-gray-200 table-auto" ng-controller="TableController">
                
                   <thead class="bg-gray-50">
                     <tr>
                       <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                       <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                       <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                       <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Added</th>

                       <th scope="col" class="relative px-6 py-3" >
                         <span class="sr-only"></span>
                       </th>
                       <th scope="col" class="relative px-6 py-3" hidden>
                         <span class="sr-only">Share To</span>
                       </th>
                       <th scope="col" class="relative px-6 py-3">
                         <div class="dropdown flex justify-end bottom" >
                           <button class="btn btn-secondary dropdown-toggle " type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                             Action
                           </button>
                           <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                 <li><button class="dropdown-item text-success" type="submit"  name="action_submit"value="active">To Active</button></li>
                                 <li><button class="dropdown-item text-warning" type="submit" name="action_submit"value="suspend">Suspend</button></li>
                                 <li><button class="dropdown-item text-danger" type="submit" name="action_submit"value="revoke">Revoke</button></li>
                           </ul>
                         </div>
                       </th>
                       
                     </tr>
                   </thead>
                     <div>
                       <tbody class="bg-white divide-y divide-gray-200"> 
                         
                         <tr ng-repeat="i in TableArray | InfoFilter:{'status':DiscreteFilterDict.status }:'or':{'nickname': IncludeFilterDict.nickname}:'or' ">
                          <td class="px-6 py-4 whitespace-nowrap">@{{$index+1}}</td>
                          <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                              <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">@{{i['nickname']}}</div>
                                <div class="text-sm text-gray-500">@{{i['device_id']}}</div>
                              </div>
                            </div>
                          </td>
                          
                          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div ng-if="i['status']!='active'">
                              <i class="fas fa-stop"></i>
                            </div>
                            <div ng-if="i['status']!='suspend'">
                              <i class="fas fa-play"></i>
                            </div>
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @{{i['datetime'] | timeDifferenceFilter }} 
                            
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                             <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                  <i class="fas fa-sliders-h"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                  <li><a  class="dropdown-item" href="{{route('individual_device')}}/@{{i['device_id']}}?back={{Route::currentRouteName()}}">Edit</a></li> 
                                  <li><a  class="dropdown-item" href="{{route('individual_device_ownership')}}/@{{i['device_id']}}?back={{Route::currentRouteName()}}">Ownership share</a></li>
                                  <li><a  class="dropdown-item" 
                                    
                                    ng-click="openRevealBearerTokenWindow(i['device_id']);"
                                  >Reveal Bearer Token</a></li>

                                </ul>
                             </div>
                             
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <input class=" checkbox-round" type="checkbox" name="device_case[]"value="@{{i['case_id']}}">
                          </td>
                          <style>
                           .checkbox-round{
                             width:20px; 
                             height:20px; 
                             border-radius: 10px;
                             position: relative;
                             left:-40%;
                           }
                           .checkbox-round:checked{
                             border:none;
                           }
    
                          </style>
                          <script>
                            
                          </script>
                        </tr>
                       
                       </tbody>
                     </table>
             
                 
               </div>
             </div>
           </div>
         </div>
        </div>

<!------------------------->




    </form>

    
    
    






<!-- New Item -->
<div class="modal fade" id="newDeviceByGenCred" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New Device</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="/new_device">
            @csrf
            <div class="mb-3">
                <label for="deviceId" class="form-label">Device ID</label>
                <input type="name" class="form-control" id="deviceId" name="deviceId" value="{{ Str::uuid(); }}"readonly >
                @error('deviceId')<div class="alert alert-danger">{{ $message }}</div>@enderror
            </div>
            
            <div class="mb-3">
            </div>
            <label for="bearerToken" class="form-label">Bearer Token</label>
            <div class="input-group mb-3">
              <script>
                new ClipboardJS('#button-copy');
              </script>
              @inject('str', 'Illuminate\Support\Str')
              <input type="text" class="form-control" aria-label="Recipient's username" id="bearerToken" name="bearerToken" value="{{ 'dev_'.Str::random(40) }}" aria-describedby="button-addon2" readonly> 
              <button class="btn btn-outline-secondary" data-clipboard-target="#bearerToken"type="button" id="button-copy" data-clipboard-action="copy" data-clipboard-target="#bearerToken">Copy</button>
              @error('bearerToken')<div class="alert alert-danger">{{ $message }}</div>@enderror
            </div>
            <p class="text-danger">This is the only time you may copy the bearerToken!</p>
            <div class="form-check" hidden disabled>
              <input class="form-check-input" type="checkbox" value="true" id="save_this_key" name="save_this_key">
              <label class="form-check-label" for="save_this_key">
                Save this key For later copy
              </label>
            </div>
            
            <div class="mb-3">
                <label for="nickname" class="form-label">Nickname</label>
                <input type="name" class="form-control" id="nickname" name="nickname" value="{{ old('nickname') }}" >
                @error('nickname')<div class="alert alert-danger">{{ $message }}</div>@enderror
            </div>
            
                <div n3g-3controller="FormController as formCon" disabled hidden>
                    
                    
                    <button type="button" class="btn btn-primary"ng-click="formCon.addNewButton();">New</button>
                    <div class="input-group mb-3"ng-repeat="item in formCon.infoList">
                      <input type="text" class="form-control" ng-model="item.buttonNo"placeholder="Button ID" required>
                      <span class="input-group-text">:</span>
                      <input type="text" class="form-control" ng-model="item.message" placeholder="Message" required>
                    </div>
                    <textarea class="form-control" id="info" rows="3" name="info" value="{{ old('info') }}"hidden >@{{formCon.infoList}}</textarea>
                    @error('info')<div class="alert alert-danger">{{ $message }}</div>@enderror
                </div>    
               
 
              

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Add Device</button>
            </div>
        </form>
      </div>

    </div>
  </div>
</div>





<!-- Modal -->
<div class="modal fade" id="newDeviceByScanQR" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Scan QR Code</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div ng-controller="NewDeviceQRScanController as qrScanner" >
        @{{newDeviceNickname}}
       
        <div class="modal-body">
          
            
          <div id="reader" width="600px" height="600px" hidden></div>
          <input type="file" id="qr-input-file"  accept="image/*" onchange="angular.element(this).scope().scanQR(event)" capture/>
          
          
            <div ng-if="step==0">
              <div class="mb-3">
                <label for="nickname" class="form-label">Nickname</label>
                <input type="name" class="form-control" id="nickname" name="nickname" ng-model="newDeviceNickname" >
              </div>
            </div>
            
         
            
         
        </div>
        <div class="modal-footer" >
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>

        
      </div>
    </div>
  </div>
</div>



</x-app-layout>

</div>



