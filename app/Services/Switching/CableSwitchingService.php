<?php

namespace App\Services\Switching;

use App\Enum\Vendor;
use App\Services\BulkSMSNigeria\SendSMSService;
use App\Services\EasyAccessAPI\VendCableService;

class CableSwitchingService
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
            case Vendor::EASYACCESSAPI :
                $response = (new VendCableService())->run($this->data);
                break;
            default :
                $response = (new VendCableService())->run($this->data);
                break;
        }
        $response['provider'] = $this->provider;
        return $response;
    }
}
