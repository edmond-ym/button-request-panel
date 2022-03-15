<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ __('Add New Device') }}
      </h2>
  </x-slot>
  
<script type="module">
    

    angular.module('newDeviceWizard', [])
    .constant("newDeviceAPI", {Link:"{{route('new_device_two_method')}}"})          
    //.constant("saveDeviceCredentialAPI", {Link:"{{route('save_device_credential')}}"})          
    .controller("NewDeviceController", function ($scope, $http, newDeviceAPI/*, saveDeviceCredentialAPI*/){
        $scope.wizardPage=1;
        $scope.numberOfPage=3;
        $scope.nickname="";
        $scope.method="";
        $scope.device_id="";//for gen method
        $scope.bearer_token="";//for gen method
        $scope.error="";
        $scope.submitError=[];
        $scope.dataFilled=function(currentPage){
            if(currentPage==1){
                if($scope.method != "" && $scope.nickname != ""){
                    return true
                }
            }
            return false
        }
        $scope.nextPage=function(){
            
            if ($scope.dataFilled($scope.wizardPage)) {
                if ($scope.wizardPage<$scope.numberOfPage) {
                  $scope.error="";
                  $scope.wizardPage=$scope.wizardPage+1;
                }
            }else{
                $scope.error="Fill All Data Please"
            }
        }
        $scope.submitErrorClear=function(){
          $scope.submitError.splice(0,$scope.submitError.length);
        }
        $scope.previousPage=function(){
            
            if ($scope.wizardPage>1) {
              $scope.submitErrorClear();
              $scope.wizardPage=$scope.wizardPage-1;
            }
        }
        $scope.postMethod=function(){
            
            if ($scope.method=="genCred") {
                $scope.device_id="{{ Str::uuid(); }}";
                $scope.bearer_token="{{ 'dev_'.Str::random(40) }}";
            }else{
                $scope.device_id="";
                $scope.bearer_token="";
            }
        }
        $scope.submitData=function(deviceId, bearerToken, nickname, newCredential){
            return $http({
              method: 'POST',
              url: newDeviceAPI.Link,
              headers: {                    
                'Content-Type': 'application/json'                    
              },
              params: {deviceId: deviceId, bearerToken: bearerToken, nickname: nickname, newCredential: newCredential}
            }).then(function (response) {
                //console.log(response);
                if(response.data.result=='success'){
                  window.location.href="{{ route('deviceList')}}";
                }else{
                  $scope.submitErrorClear();
                  response.data.errors.forEach(element => {
                    $scope.submitError.push(element);
                  });
                }
                return {
                  'result':response.data.result,
                  'errors':response.data.errors
                }
              }, function (response) {
                return {
                  'result':'fail',
                  'errors':[]
                }
            });
        }
        
        const html5QrCode = new Html5Qrcode( "reader");

        $scope.scanQR=function(e){
          if (e.target.files.length == 0) {
            return;
          }
          const imageFile = e.target.files[0];
          var resArr=[];
          document.getElementById("qr-input-file").value=""
          var qrImageData=html5QrCode.scanFile(imageFile, true)
          .then(decodedText => {
            try{
              var json=JSON.parse(decodedText);
              if("device_id" in json && "bearer_token" in json){
                return {'device_id': json.device_id, 'bearer_token': json.bearer_token};
              }else{
                return { 'device_id': '', 'bearer_token': ''};
              }
            }catch{
              return {'device_id': '', 'bearer_token': ''};
            }
          })
          .catch(err => {
            return { 'device_id': '', 'bearer_token': ''}
          });

          const scanQRSubmit = async () => {
            const json = await qrImageData;
            var res=await $scope.submitData(json.device_id, json.bearer_token, $scope.nickname, 'false');     
          };
          scanQRSubmit();

          //console.log($scope.submitError)
        }

        $scope.createCredential= async() =>{
          var res=await $scope.submitData($scope.device_id, $scope.bearer_token, $scope.nickname, 'true');
        }
        /*$scope.saveCredential=function(deviceId, bearerToken, nickname){
          return $http({
              method: 'POST',
              url: saveDeviceCredentialAPI.Link,
              headers: {                    
                'Content-Type': 'application/json'                    
              },
              params: {deviceId: deviceId, bearerToken: bearerToken, nickname: nickname}
            }).then(function (response) {

               console.log(response)
            });
        }*/

    });
</script>


<div class="py-12">
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
      </div>
    </div>
</div>

<div ng-app="newDeviceWizard">
    <div ng-controller="NewDeviceController">
      <div class="container">
      <x-card>
        <div class="container">
       
           
            
            <div ng-show="wizardPage==1">
                <div class="mb-3">
                  <label for="nickname" class="form-label">Nickname</label>
                  <input type="name" class="form-control" id="nickname" name="nickname" ng-model="nickname" >
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="method" ng-model="method" id="method" value="genCred">
                    <label class="form-check-label" for="method">
                      Generate Credentials
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="method" id="method" ng-model="method" value="scanQR">
                    <label class="form-check-label" for="method">
                      Scan QR Code
                    </label>
                </div>
                @if ($backRouteName=="deviceList")
                  <a class="btn btn-primary"  href="{{route('deviceList')}}">Cancel</a>
                @endif
                
                <button class="btn btn-primary" ng-click="nextPage();postMethod();">Next</button>
            </div>
            
            <div ng-show="wizardPage==2">
                
                <div ng-show="method=='genCred'">
                    <div class="mb-3">
                        <label for="deviceId" class="form-label">Device ID</label>
                        <input type="name" class="form-control" id="deviceId" name="deviceId" ng-model="device_id" value="{{ Str::uuid(); }}"readonly  >
                    </div>
                    
                    <div class="mb-3">
                    </div>
                    <label for="bearerToken" class="form-label">Bearer Token</label>
                    <div class="input-group mb-3">
                      <script>
                        new ClipboardJS('#button-copy');
                      </script>
                      @inject('str', 'Illuminate\Support\Str')
                      <input type="text" class="form-control" aria-label="Recipient's username" id="bearerToken" name="bearerToken" ng-model="bearer_token" value="{{ 'dev_'.Str::random(40) }}" aria-describedby="button-addon2" readonly> 
                      <button class="btn btn-outline-secondary" data-clipboard-target="#bearerToken"type="button" id="button-copy" data-clipboard-action="copy" data-clipboard-target="#bearerToken">Copy</button>
                    </div>
                    <p class="text-danger" hidden>This is the only time you may copy the bearerToken!</p>
                    <div class="form-check" hidden disabled>
                      <input class="form-check-input" type="checkbox" value="true" id="save_this_key" name="save_this_key">
                      <label class="form-check-label" for="save_this_key">
                        Save this key For later copy
                      </label>
                    </div>
                    <button class="btn btn-primary" ng-click="previousPage();">Previous</button>
                    <button class="btn btn-primary" ng-click="createCredential();">Create Credential</button>


                </div>
                <div ng-show="method=='scanQR'">
                    <input type="file" id="qr-input-file"  accept="image/*" onchange="angular.element(this).scope().scanQR(event)" capture hidden/>
                    <div id="reader" width="600px" height="600px" hidden></div>
                    <br>
                    <h2>Scan Your QR Code</h2>
                    <p>To add your existing Device Credential, you need to provide the corresponding QR Code. 
                      If your are using computer, you may attached the upload the corresponding photo into the system. 
                      If your are using mobile phone, you may take the picture with the camera.
                    </p>
                    <button class="btn btn-primary" ng-click="previousPage();">Previous</button>
                    <button class="btn btn-primary" type="button" id="inputGroupFileAddon03" onclick="document.getElementById(&quot;qr-input-file&quot;).click();">
                      Take Photo/Upload
                    </button>
                </div>
            </div>
    
            <h3 style="color:red;">@{{error}}</h3>

            <div ng-repeat="i in submitError">
              <h3 style="color:red;">@{{i}}</h3>
            </div>

        
        
        
      </div>
    </x-card>
      </div>
    </div>

</div>

        
    
</x-app-layout>

       
                  