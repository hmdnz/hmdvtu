<?php

namespace App\Services\Switching;

use App\Enum\Vendor;
use App\Services\AlrahuzData\RequeryService;
use App\Services\SMEPlug\RequeryService as SMEPlugRequeryService;

class RequerySwitchingService
{
    protected $reference;
    protected $provider;
    protected $service;

    public function __construct(
        $reference,
        $provider,
        $service
    ) {
        $this->reference = $reference;
        $this->provider = $provider;
        $this->service = $service;
    }

    public function run(): array
    {
        switch ($this->provider) {
            case Vendor::ALRAHUZDATA :
                $response = (new RequeryService())->run($this->reference, $this->service);
                break;
            case Vendor::SMEPLUG :
                    $response = (new SMEPlugRequeryService())->run($this->reference);
                    break;
            case Vendor::EASYACCESSAPI :
                $response = '';
                break;
            default :
                $response = (new RequeryService())->run($this->reference, $this->service);
                break;
        }

        return $response;
    }
}
