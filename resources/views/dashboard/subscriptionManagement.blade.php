<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Subscription') }}
        </h2>
    </x-slot>
    <script src="https://js.stripe.com/v3/"></script>
    
    <div class="accordion" id="accordionPanelsStayOpenExample">
        <div class="accordion-item">
          <h2 class="accordion-header" id="panelsStayOpen-headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
              Subscription 
            </button>
          </h2>
          <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
            <div class="accordion-body">
             
                <div class="container" style=" background-color: #ffffff;">
                    @if ($hasPaymentMethod)
                      @if ($currentSubscriptionType == "none")
                        <h3>Not Subscribed</h3>
                        <form method="post" action="{{route('subscribe_service')}}">
                            @csrf
                            <label for="plan_selector">Plan</label>
                            <select class="form-select" aria-label="Default select example" name="subscribe_item" id="plan_selector" required>
                                <option selected disabled>Select Plan</option>
                                <option value="basic">Basic</option>
                            </select>
                            <div class="container-fluid py-5"><h3>Select Payment Method</h3>
                                <div class="row">
                                @for ($i = 0; $i < count($currentPaymentMethod); $i++)
                                    <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method_select" id="payment_method_select" value="{{$currentPaymentMethod[$i]->id}}" required>
                                            
                                            <x-credit-card  ::for="payment_method_select"
                                              payment-id="{{$currentPaymentMethod[$i]->id}}" 
                                              brand="{{$currentPaymentMethod[$i]->card->brand}}"
                                              funding-type="{{$currentPaymentMethod[$i]->card->funding}}" 
                                              last4="{{$currentPaymentMethod[$i]->card->last4}}"
                                              exp-month="{{$currentPaymentMethod[$i]->card->exp_month}}"
                                              exp-year="{{$currentPaymentMethod[$i]->card->exp_year}}"
                                              with-delete-button="false"
                                            />
                                            
                                        </div>
                                        
                                    </div>
                                @endfor
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary"   >Subscribe</button>
                        </form>
                      @else
                        
                        @for ($i = 0; $i < count($subscriptionItemList); $i++)
                        
                            @inject('smc', 'App\Http\Controllers\SubscriptionManagementController')
                            @inject('sms', 'App\Library\Services\SubscriptionManagementService')
                            <p>Plan: {{$subscriptionItemList[$i]->plan->nickname}}</p>
                            <p>Interval: {{$subscriptionItemList[$i]->plan->interval}}</p>

                           
                            @if ($smc::subscriptionRetrieve($subscriptionItemList[$i]->subscription)->default_payment_method != null)
                                <div class="row">
                                    <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                                      <x-credit-card  ::for="payment_method_select"
                                        payment-id="{{$smc::paymentMethodRetrieve($smc::subscriptionRetrieve($subscriptionItemList[$i]->subscription)->default_payment_method)->id}}" 
                                        brand="{{$smc::paymentMethodRetrieve($smc::subscriptionRetrieve($subscriptionItemList[$i]->subscription)->default_payment_method)->card->brand}}"
                                        funding-type="{{$smc::paymentMethodRetrieve($smc::subscriptionRetrieve($subscriptionItemList[$i]->subscription)->default_payment_method)->card->funding}}" 
                                        last4="{{$smc::paymentMethodRetrieve($smc::subscriptionRetrieve($subscriptionItemList[$i]->subscription)->default_payment_method)->card->last4}}"
                                        exp-month="{{$smc::paymentMethodRetrieve($smc::subscriptionRetrieve($subscriptionItemList[$i]->subscription)->default_payment_method)->card->exp_month}}"
                                        exp-year="{{$smc::paymentMethodRetrieve($smc::subscriptionRetrieve($subscriptionItemList[$i]->subscription)->default_payment_method)->card->exp_year}}"
                                        with-delete-button="false"
                                      />
                                    </div>
                                </div>
                                
                                
                            @else
                                <p>No default payment method attached</p>
                            @endif
                            <br>
                            <form method="post" action="{{route('cancelSubscriptionItem')}}/{{$subscriptionItemList[$i]->subscription}}">
                              @csrf
                              <button class="btn btn-primary" type="submit" name="cancel_subscription" >Cancel</button>
                              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paymentMethodAmendModal" data-PaymentID="{{$subscriptionItemList[$i]->subscription}}">Update Payment Method</button>
                              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#planUpdateModal" data-SubItemID="{{$subscriptionItemList[$i]->id}} ">Change Plan</button>
                            </form>
                           

                        @endfor
                      @endif
                    @else
                        <strong>No Payment Method attached to your account</strong>
                    @endif
                </div>
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
              Payment Method
            </button>
          </h2>
          <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTwo">
            <div class="accordion-body">
                <div class="container" style=" background-color: white;">
                    <div class="container-fluid py-5"><h3>Current Payment Method</h3>
                        <div class="row">
                        @for ($i = 0; $i < count($currentPaymentMethod); $i++)
                            <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                                <x-credit-card 
                                  payment-id="{{$currentPaymentMethod[$i]->id}}" 
                                  brand="{{$currentPaymentMethod[$i]->card->brand}}"
                                  funding-type="{{$currentPaymentMethod[$i]->card->funding}}" 
                                  last4="{{$currentPaymentMethod[$i]->card->last4}}"
                                  exp-month="{{$currentPaymentMethod[$i]->card->exp_month}}"
                                  exp-year="{{$currentPaymentMethod[$i]->card->exp_year}}"
                                />
                            </div>
                        @endfor
                        </div>
                    </div>
                    <div class="container-fluid py-5" >
                        <div id="card-element" ></div>
                        <button id="card-button" class="btn btn-primary"data-secret="{{ $intent->client_secret }}">
                            Add my credit card
                        </button>
                        <script>
                            const stripe = Stripe('pk_test_51H3hn1GYbHDUMvIfB4P46h4in72AKwajk0ddXPt5XQfbt7T5wnUhmX6detSX3mvvC53g3AUc5ByZQ7jtEgjuLR0Q00LcvXDBCq');
                            const appearance = {
                                theme: 'flat',
                                variables:{ 
                                }
                            };
                            const cardButton = document.getElementById('card-button');
                            const clientSecret = cardButton.dataset.secret;
                            const elements = stripe.elements({clientSecret, appearance});
                            const cardElement = elements.create('payment');
                            cardElement.focus();
                            cardElement.mount('#card-element');
                            cardButton.addEventListener('click', async (e) => {
                                stripe.confirmSetup({
                                    elements,
                                  confirmParams: {
                                    return_url: "{{route('add_new_setup_intent')}}",
                                  },
                                })
                                .then(function(result) {
                                  if (result.error){
                                      console.log(result)
                                  }
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
       
   
   
       
    
   
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
           
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                

               
                
                    
                
                
                
                

                
                
                    
                
            </div>
        </div>
    </div>
    
</x-app-layout>



<div class="modal fade" id="paymentMethodAmendModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Payment Method</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <form method="post" action="{{route('update_payment_method')}}">
        @csrf
        <div class="modal-body">
            <div class="mb-3" hidden>
              <input type="text" name="SubId" id="SubId" class="form-control" hidden>
            </div>
            
            <div class="container-fluid py-5">
              <h3>Select Payment Method</h3>
              <div class="row">
              @for ($i = 0; $i < count($currentPaymentMethod); $i++)
                  <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                      <div class="form-check">
                          <input class="form-check-input" type="radio" name="payment_method_select" id="payment_method_select" value="{{$currentPaymentMethod[$i]->id}}" >
                          
                          <x-credit-card  ::for="payment_method_select"
                            payment-id="{{$currentPaymentMethod[$i]->id}}" 
                            brand="{{$currentPaymentMethod[$i]->card->brand}}"
                            funding-type="{{$currentPaymentMethod[$i]->card->funding}}" 
                            last4="{{$currentPaymentMethod[$i]->card->last4}}"
                            exp-month="{{$currentPaymentMethod[$i]->card->exp_month}}"
                            exp-year="{{$currentPaymentMethod[$i]->card->exp_year}}"
                            with-delete-button="false"
                          />
                          
                      </div>
                      
                  </div>
              @endfor
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
        
        
      </form>
    </div>
  </div>
</div>
<script>
var paymentMethodAmendModal = document.getElementById('paymentMethodAmendModal')
paymentMethodAmendModal.addEventListener('show.bs.modal', function (event) {

  var button = event.relatedTarget
 
  paymentMethodAmendModal.querySelector('#SubId').value=button.getAttribute('data-PaymentID')

})
</script>








<div class="modal fade" id="planUpdateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Change Plan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <form method="post" action="{{route('change_plan')}}">
        @csrf
        <div class="modal-body">
            <div class="mb-3">
              <input type="text" name="SubItemId" id="SubItemId" class="form-control" hidden >
            </div>
            @inject('smc', 'App\Http\Controllers\SubscriptionManagementController')
            @inject('sms', 'App\Library\Services\SubscriptionManagementService')
            <select class="form-select" aria-label="Default select example" name="newPlan" id="newPlan">
              <option disabled
                @if ($sms::subscriptionType(Auth::id(), $retrieveCurrentSubscriptions)=="none")
                   selected
                @endif
              >Select Plan</option>
              <option value="basic"
                @if ($sms::subscriptionType(Auth::id(), $retrieveCurrentSubscriptions)=="basic")
                   selected
                @endif
              >Basic</option>
              

             
            
            </select>
        </div>
        
        
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>

        
        
      </form>
    </div>
  </div>
</div>
<script>
var planUpdateModal = document.getElementById('planUpdateModal')
planUpdateModal.addEventListener('show.bs.modal', function (event) {

  var button = event.relatedTarget
  
  planUpdateModal.querySelector('#SubItemId').value=button.getAttribute('data-SubItemID')

})
</script>

