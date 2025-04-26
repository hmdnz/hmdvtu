<?php

namespace App\Http\Controllers;

use App\Enum\PaymentStatus;
use App\Enum\TransactionStatus;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\VirtualAccount;

class WalletWebhookController extends Controller
{
    //
    public function handleWebhook(Request $request)
    {
        $json = json_decode($request->getContent(), true);

        if ($json['eventType'] == 'SUCCESSFUL_TRANSACTION') {
            if ($json['eventData']['product']['type'] == 'RESERVED_ACCOUNT') {
                $reference = $json['eventData']['paymentReference'];
                $provider_reference = $json['eventData']['transactionReference'];
                $accountNumber = $json['eventData']['destinationAccountInformation']['accountNumber'];
                $bankName = $json['eventData']['destinationAccountInformation']['bankName'];
                $total = $json['eventData']['amountPaid'];
                $fees = 50;
                $amount = $total - $fees;
                $gateway = 'Monnify';
                $channel = 'Virtual Account';
                $status = TransactionStatus::SUCCESSFUL;

                // Retrieve virtual account and user information
                $RVA = VirtualAccount::where('accountNumber', $accountNumber)->first();
                if($RVA)
                {
                    $user = User::where('id', $RVA->userID)->first();
                    if($user)
                    {
                        // Retrieve wallet information 
                        // $user = User::where('accountNumber', $accountNumber)->where('status', 1)->first();
                        $wallet = Wallet::where('userID', $user->id)->first();

                        if ($wallet) {
                            $balanceBefore = $wallet->mainBalance;
                            $balanceAfter = $wallet->mainBalance + floatval($amount);
                            $wallet->update(['mainBalance' => $balanceAfter]);

                            $payment = Payment::create([ 
                                'userID' => $user->id,
                                'walletID' => $wallet->id,
                                'reference' => $reference,
                                'provider_reference' => $provider_reference,
                                'gateway' => $gateway,
                                'channel' => $channel,
                                'balanceBefore' => $balanceBefore,
                                'amount' => $amount,
                                'balanceAfter' => $balanceAfter,
                                'fees' => $fees,
                                'total' => $total,
                                'status' => $status,
                            ]);

                            if($payment)
                            {
                                $transaction = Transaction::create([
                                    'userID' => $user->id,
                                    'walletID' => $wallet->id,
                                    'type' => 'Credit',
                                    'category' => 'Funding',
                                    'reference' => generateTransactionReferenceCode(),
                                    'provider_reference' => $reference,
                                    'balanceBefore' => $balanceBefore,
                                    'amount' => $amount,
                                    'balanceAfter' => $balanceAfter,
                                    'note' => 'Wallet Funding',
                                    'status' => TransactionStatus::SUCCESSFUL,
                                ]);
                                // return 'payment and transaction successful';
                                return response()->json(['status' => 'Successfull Transaction'], 200);
                            }else
                            {
                                // return 'Payment failed';
                                return response()->json(['status' => 'failed to record payment'], 500);
                            }
                        }else{
                            // return 'we cant get wallet';
                            return response()->json(['status' => 'Wallet not found'], 500);
                        }
                    }
                    // return 'we cant get user';
                    return response()->json(['status' => 'User not found'], 500);
                }
                // return 'we cant get rva';
                return response()->json(['status' => 'Account number not found'], 500);
                
            }
        }
    }
}
