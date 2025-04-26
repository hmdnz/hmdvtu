<?php

namespace App\Livewire\User;

use Livewire\Component;

class BuyData extends Component
{

    public $billers;
    public $user_id, $service ='Data', $biller, $billerName, $category, $package, $packageName, $dataPlan;
    public $amount, $number, $pin, $total;
    public $loading = false; 

    protected $rules = [
        'biller' => 'required',
        // 'billerName' => 'required',
        'package' => 'required',
        // 'packageName' => 'required',
        'category' => 'required',
        'amount' => 'required|numeric|min:100',
        'total' => 'required|numeric',
        'number' => 'required|digits:11',
        'pin' => 'required|digits:4',
    ];


    public function render()
    {
        return view('livewire.user.buy-data');
    }
}
