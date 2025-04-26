<?php

namespace App\Services\Switching;

use App\Enum\Vendor;
use App\Services\AlrahuzData\AirtimeService;
use App\Services\SMEPlug\AirtimeService as SMEPlugAirtimeService;

class AirtimeSwitchingService
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
                $response = (new AirtimeService($this->data))->run();
                break;
            case Vendor::SMEPLUG :
                    $response = (new SMEPlugAirtimeService($this->data))->run();
                    break;
            case Vendor::EASYACCESS :
                $response = '';
                break;
            default :
                $response = (new AirtimeService($this->data))->run();
                break;
        }
        $response['provider'] = $this->provider;
        return $response;
    }
}
