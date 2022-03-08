<div class="card" >
    <div class="card-body">
        <div class="text-right">
            @if ($withDeleteButton=="true")
                <form method="post" action="{{route('set_default_payment_method')}}" hidden>
                    @csrf
                    <button type="submit" class="btn text-right" value="{{$paymentId}}" name="set_as_default" aria-label="Close" hidden>Default</button>
                </form>
                <form method="post" action="{{route('delete_payment_method')}}">
                    @csrf
                    <button type="submit" class="btn-close text-right" value="{{$paymentId}}" name="delete_payment_method" aria-label="Close"></button>
                </form>
            @endif
            
        </div>
      <h5 class="card-title">
        @if ($brand=='amex')
            <i class="fab fa-cc-amex"></i>
        @elseif($brand=='diners')
            <i class="fab fa-cc-diners-club"></i>
        @elseif($brand=='discover')
            <i class="fab fa-cc-discover"></i>
        @elseif($brand=='jcb')
            <i class="fab fa-cc-jcb"></i>
        @elseif($brand=='mastercard')
            <i class="fab fa-cc-mastercard"></i>
        @elseif($brand=='unionpay')
            UnionPay
        @elseif($brand=='visa')
           <i class="fab fa-cc-visa"></i>  
        @else
           <i class="fas fa-question-square"></i>
        @endif
        
        &nbsp;
        @if ($fundingType=='credit')
            Credit Card
        @elseif($fundingType=='debit')
            Debit Card
        @elseif($fundingType=='prepaid')
            Prepaid Card
        @else
            Unknown
        @endif
      </h5>
      
      <p class="card-text">Last 4 digits: {{$last4}}</p>
      <p class="card-text"> Exp date: {{$expMonth}}/{{$expYear}}</p>
    </div>
</div>