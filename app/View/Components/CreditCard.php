<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CreditCard extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $paymentId, $brand, $fundingType, $last4, $expMonth, $expYear, $withDeleteButton;
    


    public function __construct($paymentId, $brand, $fundingType, $last4, $expMonth, $expYear, $withDeleteButton="true")
    {
        $this->paymentId=$paymentId;
        $this->brand=$brand;
        $this->fundingType=$fundingType;
        $this->last4=$last4;
        $this->expMonth=$expMonth;
        $this->expYear=$expYear;
        $this->withDeleteButton=$withDeleteButton;

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.credit-card');
    }
}
