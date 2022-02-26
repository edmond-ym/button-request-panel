<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row">
          <div  class="badge-item col-12	col-sm-12	col-md-6	col-lg-4	col-xl-3 ">
            <div class="card text-white bg-primary mb-3  " style="height:100%;">
                <div class="card-header" hidden></div>
                <div class="card-body">
                  <h5 class="card-title">Device</h5>
                  <table class=" table-sm bg-transparent  table-responsive" style="max-height:200px;overflow:scroll;">
                    <thead>
                      <tr>
                        <th scope="col" style="color:white;"></th>
                        <th scope="col" style="color:white;"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr><td>My Device Total</td><td>{{$data['myDevice']->number}}</td></tr>
                      <tr><td>My Device Active</td><td>{{$data['myDevice']->active_number}}</td></tr>
                      <tr><td>My Device Suspended</td><td>{{$data['myDevice']->suspend_number}}</td></tr>
                      <tr><td>Shared To Me</td><td>{{$data['deviceSharedToMe']->number}}</td></tr>
                    </tbody>
                  </table>
                </div>
            </div>
          </div>
            <div  class="badge-item col-12	col-sm-12	col-md-6	col-lg-4	col-xl-3 " >
              <div class="card text-white bg-primary mb-3 "  style="height:100%;">
                  <div class="card-header" hidden></div>
                  <div class="card-body " >
                    <h5 class="card-title">Outstanding Messages</h5>
                    <table class=" table-sm bg-transparent  table-responsive" style="max-height:200px;overflow:scroll;">
                      <thead>
                        <tr>
                          <th scope="col" style="color:white;"></th>
                          <th scope="col" style="color:white;"></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr><td>My Msg</td><td>{{$data['messages']->myDevice}} </td></tr>
                        <tr><td>Shared To Me</td><td>{{$data['messages']->sharedToMe}} </td></tr>
          
                      </tbody>
                    </table>
                  </div>
              </div>
            </div>

            <div  class="badge-item col-12	col-sm-12	col-md-6	col-lg-4	col-xl-3 " >
              <div class="card text-white bg-primary mb-3 "  style="height:100%;">
                  <div class="card-header" hidden></div>
                  <div class="card-body " >
                    <h5 class="card-title">Mobile App Key</h5>
                    <table class=" table-sm bg-transparent  table-responsive" style="max-height:200px;overflow:scroll;">
                      <thead>
                        <tr>
                          <th scope="col" style="color:white;"></th>
                          <th scope="col" style="color:white;"></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr><td>Total</td><td>{{$data['mobileKey']->totalNumber}} </td></tr>
                        <tr><td>Connected</td><td> {{$data['mobileKey']->connectedNumber}}</td></tr>
                        <tr><td>Disconnected</td><td> {{$data['mobileKey']->disconnectedNumber}}</td></tr>
                        <tr><td>Not Connected</td><td> {{$data['mobileKey']->notConnectedNumber}}</td></tr>

                      </tbody>
                      
                    </table>
                  </div>
              </div>
            </div>
        </div>
    </div>
    

    <div class="card text-white bg-primary mb-3 col-12	col-sm-12	col-md-6	col-lg-4	col-xl-3" hidden>
      <div class="card-header" hidden=""></div>
      <div class="card-body">
        <h5 class="card-title">Latest Activity</h5>
        <p class="card-text">Shortcut Amend</p>
        <table class=" table-sm bg-transparent  table-responsive" style="max-height:200px;overflow:scroll;">
          <thead>
            <tr>
              <th scope="col" style="color:white;"></th>
              <th scope="col" style="color:white;"></th>
            </tr>
          </thead>
          <tbody>
            <tr><td>Time</td><td>31/Oct/2021 16:05:36 (GMT)</td></tr>
            <tr><td>Description</td><td></td></tr>
            <tr><td>Result</td><td>success</td></tr>
            <tr><td>IP</td><td>223.17.134.194</td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                
            </div>
        </div>
    </div>
    <style>
      .badge-item{
        padding:5px;

      }
    </style>
</x-app-layout>
