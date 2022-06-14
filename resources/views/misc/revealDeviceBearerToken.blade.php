

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>







<div class="container-fluid" style="padding-top:50px;">
    
              
     
    <div class="row">
        <div  class="badge-item col-12	col-sm-12	col-md-6	col-lg-4	col-xl-3 " ></div>
        <div  class="badge-item col-12	col-sm-12	col-md-6	col-lg-4	col-xl-3 ">
            @if ($result=="success")
                <h3 hidden>The link is only valid for 5 minutes</h3>
                <div class="mb-3">
                    <label for="deviceId" class="form-label">Device ID</label>
                    <input type="name" class="form-control" id="deviceId" name="deviceId" ng-model="device_id" value="{{$data['device_id']}}"readonly  >
                </div>
                
                <div class="mb-3">
                </div>
                
                <label for="bearerToken" class="form-label">Bearer Token</label>
                <div class="input-group mb-3">
                  <script>
                    new ClipboardJS('#button-copy');
                  </script>
                  
                  <input type="text" class="form-control" aria-label="Recipient's username" id="bearerToken" name="bearerToken" ng-model="bearer_token" value="{{$data['bearer_token']}}" aria-describedby="button-addon2" readonly> 
                  <button class="btn btn-outline-secondary" data-clipboard-target="#bearerToken"type="button" id="button-copy" data-clipboard-action="copy" data-clipboard-target="#bearerToken">Copy</button>
                </div>
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">Device API</h4>
                    <p>You may send the request using the link below. </p>
                    <hr>
                    <div style="display:flex; max-width:100%;">
                      <p class="mb-0 " style="max-width: 100%;">Link:&nbsp;  </p>
                      <span style="max-width: 100%;">{{route('deviceAPI.v1', ['device_id'=>$data['device_id']])}}</span>
                    </div>
                    <p class="mb-0">Bearer Token Required</p>
                    <p class="mb-0">Query Parameters: button_id</p>
                    
                </div>
                
            @else
                <h1>No Privilege</h1>
            @endif
            
        </div>
        <div  class="badge-item col-12	col-sm-12	col-md-6	col-lg-4	col-xl-3 " ></div>
    </div>

</div>


    


