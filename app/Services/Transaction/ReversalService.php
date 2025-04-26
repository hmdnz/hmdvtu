<?php

namespace App\Services\Transaction;

use App\Enum\TransactionStatus;
use App\Enum\Vendor;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;

class ReversalService
{
    protected $transaction;
    protected $user;
    protected $data;

    public function __construct(Transaction $transaction, User $user, array $data = [])
    {
        $this->transaction = $transaction;
        $this->user = $user;
        $this->data = $data;
    }

    public function run()
    {
        // if()
        $wallet = Wallet::where('userID', $this->user->id)->first();
        if($wallet)
        {
            $balanceBefore = $wallet->mainBalance;
            $balanceAfter = $wallet->mainBalance + floatval($this->transaction->amount);
            $wallet->update([
                'mainBalance' => $balanceAfter,
            ]);
            // mark transaction as failed
            $this->transaction->update([
                'status' => TransactionStatus::FAILED
            ]);

            // reversal transaction 
            $newTransaction = Transaction::create([
                'userID' => $this->user->id,
                'orderID' => $this->transaction->orderID,
                'walletID' => $wallet->id,
                'reference' => generateTransactionReferenceCode(),
                'provider_reference' => $this->transaction->reference,
                'type' => 'Credit',
                'category' => 'Reversal',
                'balanceBefore' => $balanceBefore,
                'amount' => $this->transaction->amount,
                'balanceAfter' => $balanceAfter,
                'note' => 'Reversal for order: ' . $this->transaction->orderID,
                'status' => TransactionStatus::SUCCESSFUL
            ]);


            return true;
        }
        return false;
    }

}