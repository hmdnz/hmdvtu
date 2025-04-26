<?php

namespace App\Livewire\User;

use App\Enum\OrderStatus;
use Livewire\Component;
use App\Models\Biller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Package;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class BuyAirtime extends Component
{
    public $billers, $categories = [], $packages = [];
    public $service ='Data', $biller, $billerName, $category, $package, $packageName;
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

  
    public function airtimeAPI($operator, $amount, $recipient, $airtimeType, $reference)
    {
        $data = array(
            "network" => $operator,
            'amount' => $amount,
            "mobile_number" => $recipient,
            "Ported_number" => true,
            'airtime_type' => $airtimeType
        );
        $authorizationHeader = 'Authorization: Token ' . env('ALRAHUZ_API_KEY');

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://alrahuzdata.com.ng/api/topup/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                $authorizationHeader,
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        // dd($response);
        $obj = json_decode($response);
        if (property_exists($obj, 'Status')) {
            if ($obj->Status == 'successful') 
            {
                $responseData = [
                    'success' => true, 
                    'apiDeliveryId' => $obj->id, 
                    'apiResponse' => $obj->api_response,
                    'response' => $response
                ];
            } else {
                $responseData = [
                    'success' => 'pending', 
                    'apiDeliveryId' => $obj->id, 
                    'apiResponse' => 'There is an error. Try again later or contact support.',
                    'response' => $response
                ];
            }
            return $responseData;
        } else {
            $response3 = [
                'success' => false, 
                'apiDeliveryId' => null, 
                'apiResponse' => $obj,
                'response' => $response
            ];
            return $response3;
        }
    }

    public function submit()
    {
        $this->loading = true;
        // $this->validate();
        dd($this->biller,$this->package,$this->category,$this->number,$this->amount,$this->pin,$this->total);

         // check and confirm user pin
         $user = User::find(auth('web')->user()->id);
         if (!$user || !Hash::check($this->pin, $user->pin)) {
             return redirect()->route('user.buyAirtime')->with('message', 'Check your pin and try again');
         }
         // other params
         $orderCode = $this->generateOrderCode();
         $service = $this->service; $type = $this->service;
         $today = Carbon::now();
         // get biller and package
         $billerObject = Biller::find($this->biller); $this->billerName = $billerObject->title;
         $packageObject = Package::find($this->package); $this->packageName = $packageObject->title;
        //  note
         $note = $type . " | " . $this->packageName . " | " . $this->amount;
         // Create a new order
         $order = Order::create([
             'userID' => $this->userID,
             'billerID' => $this->biller,
             'packageID' => $this->package,
             'service' => $this->service,
             'reference' => $orderCode,
             'price' => $this->total,
             'quantity' => '1',
             'total' => $this->total,
             'beneficiary' => $this->number,
             'status' => OrderStatus::INITIATED,
         ]);
        // get wallet
        $wallet = Wallet::where('userID', $this->userID)->first();

        if(!$order && !$wallet )
        {
            $this->loading = false;
            return redirect()->route('user.buyAirtime')->with('message', 'There is issue with your wallet. Contact Admin');
        }
        // confirm the available funds and make pending transaction
        if($wallet->mainBalance >= $this->total)
        {
            $balanceBefore = $wallet->mainBalance;
            $balanceAfter = $wallet->mainBalance - floatval($this->total);
            $wallet->mainBalance = $balanceAfter;
            $wallet->save();
            // new transaction
            $transaction = Transaction::create([
                'userID' => $user->id,
                'orderID' => $order->id,
                'walletID' => $wallet->id,
                'type' => 'Debit',
                'balanceBefore' => $balanceBefore,
                'amount' => $this->total,
                'balanceAfter' => $balanceAfter,
                'note' => $note,
                'status' => 'pending',
            ]);
        }
        else
        {
            $this->loading = false;
            return redirect()->route('user.buyAirtime')->with('message', 'Insufficient Funds in Your Wallet');
        }

        try 
        {
            $delivery = $this->airtimeAPI($billerObject->variation, $this->amount,$this->number,$this->category, $orderCode);
            // dd($delivery);
            if($delivery['success'] === true)
            {
                $order->update([
                    'responseAPI' => $delivery['apiDeliveryId'],
                    'responseMessage' => $delivery['apiResponse'],
                    'responseBody' => $delivery['response'],
                    'status' => 'Completed',
                ]);
                dd($order);
                $this->loading = false;
                // return redirect()->route('user.buyAirtime')
                // return redirect()->route('user.showOrder',[$order->id])->with('message', 'You successfully purchase Airtime');
            }
            elseif($delivery['success'] === 'pending')
            {
                $requery = $this->requeryOrder($order);
                $order->update([
                    'responseAPI' => $requery['apiDeliveryId'],
                    'responseMessage' => $requery['apiResponse'],
                    'responseBody' => $requery['response'],
                    'status' => 'Completed',
                ]);
                return redirect()->route('user.showOrder',[$order->id])->with('message', 'You successfully purchase Airtime');
            }
            else
            {
                $order->update([
                    'responseAPI' => $delivery['apiDeliveryId'],
                    'responseMessage' => $delivery['apiResponse'],
                    'responseBody' => $delivery['response'],
                    'status' => 'Failed',
                ]);
                $balanceAfter = $wallet->mainBalance + floatval($this->total);
                $wallet->update([
                    'balance' => $balanceAfter,
                ]);
                $transaction->update([
                    'status' => 'failed'
                ]);
                return redirect()->route('user.buyAirtime')->with('message', 'We cannot process your order at this time');
            }
        } 
        catch (\Throwable $th) 
        {
            return redirect()->route('user.buyAirtime')->with('message', 'There is an error. Try again');
        }

        // Perform the airtime top-up logic (e.g., API call, database update)
        // Example: Save to transactions table
        // \App\Models\AirtimeTransaction::create([
        //     'user_id' => Auth::id(),
        //     'biller_id' => $this->biller,
        //     'category_id' => $this->category,
        //     'package_id' => $this->package,
        //     'amount' => $this->amount,
        //     'phone_number' => $this->number,
        //     'pin' => bcrypt($this->pin),
        //     'status' => 'pending',
        // ]);

        // Reset form fields after submission
        $this->reset();

        session()->flash('success', 'Airtime top-up request submitted successfully!');
    }

    public function mount()
    {
        $this->billers = Biller::all();
    }

    public function render()
    {
        return view('livewire.user.buy-airtime');
    }
}
