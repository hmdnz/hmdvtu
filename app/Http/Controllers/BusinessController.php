<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Biller;
use App\Models\Category;
use App\Models\Package;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\CategorySwitch;
use App\Services\Monnify\GetBanksService;
use App\Services\Switching\ServiceProviderResolver;

class BusinessController extends Controller
{
    //
    public function index()
    {
        $MTNPackages = Package::where('billerID', '1')
                ->where('type', 'Data')
                ->where('status', 'Active')->limit(10)->get();
        
        $AirtelPackages = Package::where('billerID', '3')
            ->where('type', 'Data')
            ->where('status', 'Active')->limit(10)->get();

        $MobilePackages = Package::where('billerID', '4')
            ->where('type', 'Data')
            ->where('status', 'Active')->limit(10)->get();

        $GloPackages = Package::where('billerID', '2')
            ->where('type', 'Data')
            ->where('status', 'Active')->limit(10)->get();
        

        return view('index', compact('MTNPackages', 'AirtelPackages', 'MobilePackages', 'GloPackages'));
    }

    public function getBanks(Request $request)
    {
        $response = (new GetBanksService())->run();
        if($response && $response['status'])
        {
            $filteredBanks = array_map(function ($bank) {
                return [
                    'name' => $bank['name'],
                    'code' => $bank['code']
                ];
            }, $response['data']);
        
            return response()->json(['status' => 'Success', 'message' =>"Fetch banks successfully", 'data' => $filteredBanks]);
        }else{
            $response = ['status' => 'Failed', 'message'=> "Cannot fetch banks"];
            return response()->json($response);
        }
    }

    public function checkService($service)
    {
        // Find the service by title
        $service = Service::where('title', $service)->first();

        // Check if the service doesn't exist or if the status is null
        if (!$service || $service->status === 'Inactive' || $service->status === null) {
            return response()->json(['status' => false]);
        } else {
            return response()->json(['status' => true]);
        }
    }

    public function checkSwitches($service, $biller)
    {
        // $billerID = $biller;
        // $data = [];
        // // fetch biller
        // $selectedBiller = Biller::find($billerID);
        // if($selectedBiller)
        // {
        // $data["biller"] =["id" => $selectedBiller->id,"title" => strtolower($selectedBiller->title)];
        // }
        // // fetch categories
        // $categories = Category::where('service', $service)->where(strtolower($selectedBiller->title), 'Active')->where('status', 'Active')->get();
        // if($categories) {
        //     $data["categories"] =$categories; 
        // }
        // Find the service 
        if($biller == '9MOBILE') {$biller = 'mobile';} else {$biller = strtolower($biller);}
        // $biller = strtolower($biller);
        $activeSwitches = Category::where('service', $service)->where($biller, 'Active')->where('status', 'Active')->get();
        // Return the active switches as JSON
        return response()->json($activeSwitches);
    }

    public function fetchPackages($biller, $category, $type)
    {
        $resolver = new ServiceProviderResolver();
        if($type == 'Airtime')
        {
            $provider = $resolver->resolve($type, $biller, null);
        }elseif($type == 'Bulk SMS')
        {
            $provider = $resolver->resolve($type, null, null);
        }
        else{
            $provider = $resolver->resolve($type, $biller, $category);
        }

        $providerKey = $provider;

        // Build query
        $query = Package::query()
            ->where('status', 'Active')
            ->where('type', $type);
            
        // Category-specific adjustments
        if (in_array($type, ['Cable', 'BulkSMS', 'Electricity'])) {
            $query->where('billerID', $biller)
            ->where('provider', $providerKey);
        }
        if (in_array($type, ['Airtime', 'Data'])) {
            $query->where('billerID', $biller)
                ->where('provider', $providerKey)
                ->where('planType', $category);
        }

        // Get results
        $packages = $query->get();

        return response()->json($packages);
    }

    public function getAnnouncement(){
        $announcement = Announcement::where('status', 'Active')->orderBy('id', 'desc')->first();
        return response()->json($announcement);
    }

    public function verifyIUC($iuc, $biller)
    {
        $biller = Biller::find($biller);
        $data = array(
            "smart_card_number" => $iuc,
            'cablename' => $biller->title,
        );

        $authorizationHeader = 'Authorization: Token ' . env('ALRAHUZ_API_KEY');

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://alrahuzdata.com.ng/api/validateiuc?smart_card_number=". $iuc .'&&cablename='. $biller->title,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                $authorizationHeader,
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $obj = json_decode($response);
        return $response;
    }
}
