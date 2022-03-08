<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Shared To Me') }}
        </h2>
    </x-slot>
    

    
    
    @if(count($data)==0)
    <center><p style="position: sticky;top: 50%;left:20%;right:20%;transform:translateY(500%);"class="text-2xl">No Device is shared to you!</p></center>
    @else
    <!---------------------->
      <div class="container">
         <div class="flex flex-col">
           <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
             <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
               <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                <div ng-app="TableApp">        
                  <table class="min-w-full divide-y divide-gray-200 table-auto " ng-controller="TableController" >
                    <thead class="bg-gray-50">
                      <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Info</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Added</th>
                        <th scope="col" class="relative px-6 py-3">
                          <span class="sr-only">Edit</span>
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                          <span class="sr-only"></span>
                        </th>
                      </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                      <tr ng-repeat="i in TableArray">
                        <td class="px-6 py-4 whitespace-nowrap">@{{$index+1}}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <div class="flex items-center">
                            <div class="ml-4">
                              <div class="text-sm font-medium text-gray-900">@{{i['owner_name']}}</div>
                              <div class="text-sm text-gray-500">@{{i['owner_email']}}</div>
                            </div>
                          </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <div class="flex items-center">
                            <div class="ml-4">
                              <div class="text-sm font-medium text-gray-900">@{{i['nickname']}}</div>
                              <div class="text-sm text-gray-500">@{{i['device_id']}}</div>
                            </div>
                          </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <table class="table caption-top table-sm">
                            <thead>
                              <tr>
                                <th scope="col">Button ID</th>
                                <th scope="col">Message</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr ng-repeat="k in i['info']|toObject">
                                <td>@{{k['buttonNo']}}</td>
                                <td>@{{k['message']}}</td></p>
                              </tr>

                            </tbody>
                          </table>
                          
                          
                          
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">@{{i['created_time'] | timeDifferenceFilter }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">                         
                          
                   
                          <div ng-if="i['right']=='advanced'">
                            <a  class="btn btn-primary"  href="{{route('individual_device')}}/@{{i['device_id']}}">Edit</a>
                          </div>
                          <div ng-if="i['right']!='advanced'">
                            No Privilege
                          </div>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                             <form method="post" action="{{route('give_up_shared_right')}}">
                                 @csrf
                                <button  class="btn btn-danger"  name="give_up" value="@{{i['case_id']}}" type="submit">Give Up</button>
                             </form>
                        </td>
                      </tr>
                    
          
                    </tbody>
                  </table>


                  
                </div>
                <script type="module">
                  //import InfoFilter from '/js/info-filter.js'; 
                  import TimeDifferenceFilter from '/js/time-filter.js'; 
                  angular.module('TableApp', [])
                  .filter('toObject', ToObject)
                  .filter("timeDifferenceFilter", TimeDifferenceFilter)      
                  .controller('TableController', function($scope) {
                    $scope.TableArray={!! $data !!}
                    
                  });
                  function ToObject(){
                    return function(jsonString) {
                      return angular.fromJson(jsonString);
                    };
                  }
            
                </script>
               </div>
             </div>
           </div>
         </div>
      </div>
    <!---------------------->
    @endif


    
</x-app-layout>


