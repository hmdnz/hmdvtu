<?php

namespace App\Services\Switching;

use App\Enum\Vendor;
use App\Services\AlrahuzData\DataService;
use App\Services\SMEPlug\DataService as SMEPlugDataService;

class DataSwitchingService
{
    protected $data;
    protected $provider;

    public function __construct(
        array $data,
        $provider
    ) {
        $this->data = $data;
        $this->provider = $provider;
    }

    public function run(): array
    {
        switch ($this->provider) {
            case Vendor::ALRAHUZDATA :
                $response = (new DataService($this->data))->run();
                break;
            case Vendor::SMEPLUG :
                    $response = (new SMEPlugDataService($this->data))->run();
                    break;
            case Vendor::EASYACCESSAPI :
                $response = '';
                break;
            default :
                $response = (new DataService($this->data))->run();
                break;
        }
        $response['provider'] = $this->provider;
        return $response;
    }
}
