
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Individual Mobile Token Settings') }}
        </h2>
    </x-slot>
    <script type="text/javascript" src="/js/qrcode.js"></script>
    
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            </div>
          </div>
      </div>
      
      <div class="container">
        <div class="mb-3">
          <label for="device_id" class="col-form-label">Connection Status:</label>
          @if ($basic_info->phone_token != "" && $basic_info->deleted_from_phone != "yes")
              <label style="color:green;">Connected</label>
          @elseif($basic_info->phone_token != "" && $basic_info->deleted_from_phone == "yes")
              <label style="color:red;">Disconnected and Invalid</label>
          @elseif($basic_info->phone_token == "" && $basic_info->deleted_from_phone == "")
              <label style="color:red;">Not Connected</label>
          @else
              <label style="color:red;">Unknown</label>
          @endif             
        </div> 
        @if ($basic_info->phone_token != "")
        <div class="mb-3">         
          <label >Last Accessed At: {{$basic_info->last_access}}</label>
        </div>
        @endif
        <div class="alert alert-primary d-flex align-items-center" role="alert">
          <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
          <div>
            <strong>Key Protection Policy</strong>
            <p>This key can only be added to one device. After adding the key, the system will 
              bar any new connection attempt from the app. 
              It is a measure to protect your data. 
            </p>
          </div>
        </div>
        <div class="mb-3">
            <label for="device_id" class="col-form-label">Access Token:</label>
            <input type="text" class="form-control" value="{{$basic_info->access_token}}" readonly>
        </div>
        <div id="qrCode" class=" border" style=""></div>
        <script>
          var typeNumber = 0;
          var errorCorrectionLevel = 'L';
          var qr = qrcode(typeNumber, errorCorrectionLevel);
          qr.addData('{{$basic_info->access_token}}');
          qr.make();
          document.getElementById('qrCode').innerHTML = qr.createSvgTag(8);
        </script>
            <form method="post" action="/mobile_access_amend/{{$basic_info->case_id}}">
              @csrf
              

              <div class="mb-3">
                <input type="text" class="form-control" id="case_id" name="case_id" value="{{$basic_info->case_id}}"hidden required>
              </div>
              <div class="mb-3">
                <label for="device_id" class="col-form-label">Nickname:</label>
                <input type="text" class="form-control" id="nickname" name="nickname" value="{{$basic_info->nickname}}" >
              </div>

             
             

              <div class="modal-footer">
                <a type="button" class="btn btn-secondary" href="{{ url()->previous() }}">Close</a>
                <button type="submit" class="btn btn-primary">Amend</button>
              </div>
            </form>
            <form method="post" action="/mobile_access_destroy/{{$basic_info->case_id}}">
              @csrf
              <button type="submit" class="btn btn-danger">Destroy</button>

            </form>
      </div>
      
          
    
</x-app-layout>

       