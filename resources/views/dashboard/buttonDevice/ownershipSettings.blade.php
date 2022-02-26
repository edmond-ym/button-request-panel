<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Share Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
              


            </div>
        </div>
    </div>
    @if ($result=='success')
    <div class="container">
        <div class="flex flex-col">
           <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
             <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
               <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                <h3>Device Id: {{$device_id}} </h3>
                <h3>Device Nickname: {{$device_nickname}}</h3>
                <form method="post" action="/device_share_add/{{$device_id}}">
                  @csrf
                  <div class="input-group mb-3">
                     <input type="text" class="form-control" placeholder="Enter Email" name="email" value="{{old('email')}}"aria-label="Recipient's username" aria-describedby="button-addon2">
                     <div class="input-group-append">
                       <button class="btn btn-outline-secondary" type="submit" id="button-addon2" >Add</button>
                     </div>
                  </div>
                  @error('email')<div class="alert alert-danger">{{ $message }}</div>@enderror 
                </form>
                <table class="min-w-full divide-y divide-gray-200 table-auto" >
                   <thead class="bg-gray-50">
                     <tr>
                       <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                       <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                       <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Added at</th>
                       <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Right</th>
                       
                       <th scope="col" class="relative px-6 py-3">
                         <span class="sr-only">Edit</span>
                       </th>
                     </tr>
                   </thead>
                   <tbody class="bg-white divide-y divide-gray-200">
                   @foreach ($data  as $item)
                     <tr>
                       <td class="px-6 py-4 whitespace-nowrap">{{$loop->index+1}}</td>
                       
                       <td class="px-6 py-4 whitespace-nowrap">{{$item->share_to_email}}</td>
                       <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{$item->created_time}} </td>
                       <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                           <form method="post" action="/change_right_to/{{$item->case_id}}">
                              @csrf
                              
                              <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                  @if($item->right=='advanced')
                                    Advanced
                                  @elseif($item->right=='middle')
                                    Intermediate
                                  @else
                                    Basic
                                  @endif
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                  <li><button class="dropdown-item btn-success"  name="right_alter" type="submit" value="basic">Basic</button></li>
                                  <li><button class="dropdown-item btn-success"  name="right_alter" type="submit" value="middle">Intermediate</button></li>
                                  <li><button class="dropdown-item btn-success"  name="right_alter" type="submit" value="advanced">Advanced</button></li>
                                </ul>
                              </div>
                              

                           </form>
                          
                       </td>
                       
                       <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                         
                         <form method="post" action="/device_share_revoke">
                             @csrf
                            <button class="btn btn-danger"  name="revoke"type="submit" value="{{$item->case_id}}">Revoke</button>
                         </form>
                         
                       </td>
                     </tr>
                   @endforeach

                   </tbody>
                 </table>
               </div>
             </div>
           </div>
        </div>
    </div>
    @else
    <center><p style="position: sticky;top: 50%;left:20%;right:20%;transform:translateY(400%);"class="text-2xl">No Privilege</p></center>

    @endif
</x-app-layout>

       
                        
                           
           
                