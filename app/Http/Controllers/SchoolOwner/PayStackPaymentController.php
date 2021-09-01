<?php

namespace App\Http\Controllers\SchoolOwner;

use App\Actions\CreatePayStackTransactionAction;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RespondsWithHttpStatusController;
use App\Http\Requests\SchoolSetUpFormRequest;
use Illuminate\Http\Request;

class PayStackPaymentController extends RespondsWithHttpStatusController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param SchoolSetUpFormRequest $request
     * @param CreatePayStackTransactionAction $createPayStackTransactionAction
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function pay(SchoolSetUpFormRequest $request, CreatePayStackTransactionAction $createPayStackTransactionAction){

        $this->authorize('create', auth()->user());

        list($decodedValue) =  $createPayStackTransactionAction->execute($request);

        return $this->responseCreated([
            'access_code' => $decodedValue['data']['access_code'],
            'reference' => $decodedValue['data']['reference'],
            'url' => $decodedValue['data']['authorization_url']
        ], 'Authorization URL created');
    }


    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
        public function verify(Request $request){

            $request->validate([
                'reference'  => ['required', 'string']
            ]);

            $url = config('auth.paystack.url.verify').$request->reference;
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Authorization: Bearer sk_test_46c6dfddcf73f9d98552d8fbddb20bf9d269f449",
                "Cache-Control: no-cache",
            ));

            curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

            $result = curl_exec($ch);

            $decodedValue =json_decode($result, true);

            return response()->json([
                'status' =>   $decodedValue['data']['status'] === 'success' ? 'success' : 'fail',
               'data' => [
                   'reference' => $decodedValue['data']['reference'],
                   'amount' => $decodedValue ['data']['amount'],
                   'metadata' => $decodedValue['data']['metadata'],
                   'paid_at' => $decodedValue['data']['paid_at'],
                   'created_at' => $decodedValue['data']['created_at']
               ]
            ]);
        }
}
