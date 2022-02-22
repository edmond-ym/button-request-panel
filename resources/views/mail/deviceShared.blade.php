@component('mail::message')

@if ($receiverType=="sharer")
    Hi {{$sharerFullName}}, 
        <p>You have shared your device to {{$shareeEmail}}.</p>
        |                   |                                      | 
        | :-----------------|:------------------------------------ |
        | Nickname:         | {{$deviceInfo->nickname}}            | 
        | Device ID:        | {{$deviceInfo->deviceId}}            |
@endif

@if ($receiverType=="sharee")
    Hi {{$shareeFullName}}, 
        <p>User {{$sharerEmail}} has shared his/her device to you.</p>
        |                   |                                      | 
        | :-----------------|:------------------------------------ |
        | Nickname:         | {{$deviceInfo->nickname}}            | 
        | Device ID:        | {{$deviceInfo->deviceId}}            |        
       
@endif


Thanks,<br>
{{ config('app.name') }}
@endcomponent

