

@if ($receiverType=="sharer")
    Hi {{$sharerFullName}}, 
        <p>You have shared your device to {{$shareeEmail}}.</p>
        
        <p>Nickname:          {{$deviceInfo->nickname}}            </p> 
        <p>Device ID:         {{$deviceInfo->deviceId}}            </p>
@endif

@if ($receiverType=="sharee")
    Hi {{$shareeFullName}}, 
        <p>User {{$sharerEmail}} has shared his/her device to you.</p>
        
        <p> Nickname:         | {{$deviceInfo->nickname}}            </p> 
        <p> Device ID:        | {{$deviceInfo->deviceId}}            </p>        
       
@endif


Thanks,<br>
Inchoatae Limited

