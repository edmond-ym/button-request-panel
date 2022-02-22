@component('mail::message')

Hi {{$fullName}},


@if ($newCredential=='true')
  
  <p>A new credential has been created! Please well keep the following credential.</p>
  @component('mail::table')
  |                   |                                      | 
  | :-----------------|:------------------------------------ |
  | Nickname:         | {{$deviceCredential->nickname}}      | 
  | Device ID:        | {{$deviceCredential->deviceId}}      |
  | Bearer Token:     | {{$deviceCredential->bearerToken}}   | 
  @endcomponent
  <p>Time Created: {{$currentTime}}</p>

@else
  <p>An Existing Credential has just been added to your account! </p>
  @component('mail::table')
  |                   |                                      | 
  | :-----------------|:------------------------------------ |
  | Nickname:         | {{$deviceCredential->nickname}}      | 
  | Device ID:        | {{$deviceCredential->deviceId}}      |
  | Bearer Token:     | {{$deviceCredential->bearerToken}}   | 
  @endcomponent
  <p>Time Added: {{$currentTime}}</p>
    
@endif

Thanks,<br>
{{config('app.name') }}

@endcomponent