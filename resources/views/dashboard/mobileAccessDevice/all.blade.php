
    <x-app-layout >
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Device List') }}
            </h2>
        </x-slot>
 
        <!----------------------------->
          <div class="container">

              <div class="flex flex-col">
               <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                 <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                   <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                   <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                       New Mobile Token
                   </button>

                   <table class="min-w-full divide-y divide-gray-200 table-auto" >
                    
                       <thead class="bg-gray-50">
                         <tr>
                           <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                           <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nickname</th>
                           <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Settings</th>
                           <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"> </th>

                         </tr>
                       </thead>
                         <div>
                           <tbody class="bg-white divide-y divide-gray-200"> 
                             @foreach ($data as $item)
                             <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">{{$loop->index+1}}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">{{$item->nickname}}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                                    
                                    <a  class="btn btn-primary"  href="{{route('mobile_access_individual', ['case_id'=>$item->case_id])}}">Edit</a> 

                                </td>
                                <td>
                                  @if ($item->phone_token != "" && $item->deleted_from_phone != "yes")
                                      <label style="color:green;">Connected</label>
                                  @elseif($item->phone_token != "" && $item->deleted_from_phone == "yes")
                                      <label style="color:red;">Disconnected and Invalid</label>
                                  @elseif($item->phone_token == "" && $item->deleted_from_phone == "")
                                      <label style="color:red;">Not Connected</label>
                                  @else
                                      <label style="color:red;">Unknown</label>
                                  @endif 
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
    
    <!------------------------->

    </x-app-layout>
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
    




  
  
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">New Mobile Token</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="post" action="/mobile_access_new">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label for="nickname" class="form-label">Nickname</label>
                    <input type="text" class="form-control" id="nickname" name="nickname" aria-describedby="emailHelp">
                    
                    @error('nickname')<div class="alert alert-danger">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Add</button>
            </div>
        </form>
      </div>
    </div>
  </div>
    
  
    