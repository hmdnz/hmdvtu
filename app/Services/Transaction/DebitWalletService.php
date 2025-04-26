<?php 

namespace App\Services\Transaction;

use App\Models\Wallet;
use Exception;
use Illuminate\Support\Facades\Log;

class DebitWalletService
{
    protected $wallet;

    public function __construct(Wallet $wallet)
    {
        $this->wallet = $wallet;
    }

    /**
     * Debit the wallet balance.
     */
    public function debitWallet(float $amount)
    {
        try {
            if (!$this->wallet) {
                return false;
            }

            $balanceBefore = $this->wallet->mainBalance;
            $balanceAfter = $balanceBefore - $amount;
            $this->wallet->mainBalance = $balanceAfter;
            $this->wallet->save();

            return [
                "balanceBefore" => $balanceBefore,
                "balanceAfter" => $balanceAfter,
                "amount" => $amount,
            ];
        } catch (Exception $exception) {
            Log::error("Error debiting wallet: " . $exception->getMessage());
            return false;
        }
    }
}
