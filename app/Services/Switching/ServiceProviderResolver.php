<?php
namespace App\Services\Switching;

use App\Enum\GeneralStatus;
use App\Models\Service;
use App\Models\Biller;
use App\Models\Switches;

class ServiceProviderResolver
{
    public function resolve(string $serviceTitle, string $billerInput = null, string $category = null)
    {
        // get service
        $service = Service::where("title", $serviceTitle)->first();

        // Resolve biller: either by ID or title
        $biller = Biller::where("id", $billerInput)->first();

        // // 1. Check category
        if ($category) {
            $categoryEntry = Switches::where([
                'context_type' => 'category',
                'context_id' => $biller->id,
                'category_title' => $category,
                'service_id' => $service?->id,
            ])->first();

            if ($categoryEntry && $categoryEntry->provider && $categoryEntry->provider->status == GeneralStatus::ACTIVE) {
                return $categoryEntry->provider->key;
            }
        }

        // 2. Check biller
        if ($billerInput) {
            $billerEntry = Switches::where([
                'context_type' => 'biller',
                'service_id'=> $service?->id,
                'context_id' => $biller->id,
            ])->first();

            if ($billerEntry && $billerEntry->provider && $billerEntry->provider->status == GeneralStatus::ACTIVE) {
                return $billerEntry->provider->key;
            }
        }

        // 3. Check service
        $serviceEntry = Switches::where([
            'context_type' => 'service',
            'service_id' => $service?->id,
            'context_id' => $service->id,
        ])->first();

        if ($serviceEntry && $serviceEntry->provider && $serviceEntry->provider->status == GeneralStatus::ACTIVE) {
            return $serviceEntry->provider->key;
        }

        // 4. fallback: return a default provider for the service
        return $service->provider->key ?? null;
    }
}
