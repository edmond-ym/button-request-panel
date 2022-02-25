

Hi {{$fullName}},


@if ($newCredential=='true')
  
  <p>A new credential has been created! Please well keep the following credential.</p>

 
  <p> Nickname:          {{$deviceCredential->nickname}}     </p>
  <p> Device ID:         {{$deviceCredential->deviceId}}    </p>
  <p> Bearer Token:      {{$deviceCredential->bearerToken}}  </p>
  <p>Time Created: {{$currentTime}}</p>

@else
  <p>An Existing Credential has just been added to your account! </p>

  
  <p>Nickname:          {{$deviceCredential->nickname}}      </p> 
  <p>Device ID:         {{$deviceCredential->deviceId}}      </p>
  <p>Bearer Token:      {{$deviceCredential->bearerToken}}   </p> 

  <p>Time Added: {{$currentTime}}</p>
    
@endif

Thanks,<br>
Inchoatae Limited

