<?php
namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Biller;
use App\Models\Category;
use App\Models\Package;
use Illuminate\Support\Facades\Auth;

class AirtimeTopUp extends Component
{
    public $billers, $categories = [], $packages = [];
    public $biller, $billerName, $category, $package, $packageName;
    public $amount, $number, $pin, $total;

    protected $rules = [
        'biller' => 'required',
        'amount' => 'required|numeric|min:50',
        'category' => 'required',
        'package' => 'required',
        'number' => 'required|digits:11',
        'pin' => 'required|digits:4',
    ];

    public function updatedBiller($value)
    {
        $selectedBiller = Biller::find($value);
        $this->billerName = $selectedBiller ? $selectedBiller->title : '';

        // Fetch categories related to the selected biller
        $this->categories = Category::where('biller_id', $value)->get();
        $this->packages = []; // Reset packages
    }

    public function updatedCategory($value)
    {
        // Fetch packages related to the selected category
        $this->packages = Package::where('category_id', $value)->get();
    }

    public function updatedPackage($value)
    {
        $selectedPackage = Package::find($value);
        $this->packageName = $selectedPackage ? $selectedPackage->name : '';
    }

    public function submit()
    {
        $this->validate();

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
        return view('livewire.airtime-top-up');
    }
}
