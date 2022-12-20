<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class TransactionController extends Controller
{

    public function index()
    {
        return Transaction::all();
    }


    public function store(Request $request)
    {
        $tran = new Transaction();
        $tran->user_id = $request->user_id;
        $tran->tran_id = $request->tran_id;
        $tran->payment_option = $request->payment_option;
        $tran->hash = $request->hash;
        $tran->status = $request->status;
        $tran->currency = $request->currency;
        $tran->amount = $request->amount;
        $tran->save();
        return response()->json(['msg' => 'payment success']);
    }


    public function show($id)
    {
        return Transaction::where("user_id", $id)->get();
    }


    public function update(Request $request, Transaction $transaction)
    {
        //
    }


    public function destroy(Transaction $transaction)
    {
        //
    }

    public function getHash($amount)
    {

        $data = 'ec002497' . $this->getTranID() . $amount;
        // return response()->json(['amount' => $data]);
        return base64_encode(
            hash_hmac(
                'sha512',
                $data,
                "a4a12ed92f831906729ac771f8e43136a81209e6",
            true
            )
        );
    }

    public function getTranID()
    {
        $transaction = Transaction::all();
        if ($transaction->isEmpty()) {
            return "tran-id-" . 100;
        }
        $transaction = Transaction::orderBy('id', 'desc')->first();
        $tran_id = "tran-id-" . $transaction->id += 1;
        return $tran_id;

    }

    public function getTimestamp()
    {
        return date("Ymdhis");
    }

    public function getPyamentResponse(Request $request)
    {


        // return $request->tran_id;
        $client = new Client();
        $response = $client->request(
            "POST",
            'https://checkout-sandbox.payway.com.kh/api/payment-gateway/v1/payments/check-transaction',
            [
                'merchant_id' => 'ec002497',
                'tran_id' => $request->tran_id,
                'hash' => $this->getNewHash($this->getTimestamp()),
            ]

        );
        $body = $response->getStatusCode();
        return response()->json($body);
    }

    public function getNewHash($req_time)
    {

        $data = $req_time . 'ec002497' . $this->getTranID();
        // return response()->json(['amount' => $data]);
        return base64_encode(
            hash_hmac(
                'sha512',
                $data,
                "a4a12ed92f831906729ac771f8e43136a81209e6",
            true
            )
        );
    }

    public function QRcode(Request $request)
    {


        $client = new Client();
        $data = 'ec002497' . $this->getTimestamp();
        // return response()->json(['amount' => $data]);
        $hash = base64_encode(
            hash_hmac(
                'sha512',
                $data,
                "a4a12ed92f831906729ac771f8e43136a81209e6",
            true
            )
        );
        // $response = $client->request(
        //     "POST",
        //     'https://checkout-sandbox.payway.com.kh/api/aof/request-qr',
        //     [
        //         "multipart" => [
        //             [
        //                 "name" => "merchant_id",
        //                 "contents" => "ec002497",
        //             ],
        //             [
        //                 "name" => "req_time",
        //                 "contents" => $this->getTimestamp(),
        //             ],
        //             [
        //                 "name" => "hash",
        //                 "contents" => $hash,
        //             ],
        //         ]
        //     ]

        // );
        $response = $client->request(
            'POST',
            'https://checkout-sandbox.payway.com.kh/api/aof/request-qr',
            [
                'merchant_id' => 'ec002497',
                'req_time' => $this->getTimestamp(),
                'hash' => $hash,
            ]
        );
        $body = $response->getBody();
        return response()->json($body);
    }
}
