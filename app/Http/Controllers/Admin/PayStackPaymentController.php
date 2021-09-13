<?php

namespace App\Http\Controllers\Admin;

use App\Actions\CreatePayStackTransactionAction;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RespondsWithHttpStatusController;
use App\Http\Requests\SchoolSetUpFormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
     * @throws ValidationException
     */
    public function verify(Request $request){
       //dd($request->all());
        $key = config('auth.paystack.api_key.sk_test');
        $url = config('auth.paystack.url.verify').$request->reference;
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer {$key}",
            "Cache-Control: no-cache",
        ));

        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        $decodedResult =json_decode($result, true);

       // dd($decodedResult);

        return response()->json([
            'status' =>   $decodedResult['data']['status'] === 'success' ? 'success' : 'fail',
           'data' => [
               'reference' => $decodedResult['data']['reference'],
               'amount' => $decodedResult ['data']['amount'],
               'metadata' => $decodedResult['data']['metadata'],
               'paid_at' => $decodedResult['data']['paid_at'],
               'created_at' => $decodedResult['data']['created_at']
           ]
        ]);
    }

    public function handleGatewayWebHook(){
        // Retrieve the request's body and parse it as JSON
        $input = @file_get_contents("php://input");
        $event = json_decode($input, true);
        // Do something with $event
        http_response_code(200); // PHP 5.4 or greater
    }
}
