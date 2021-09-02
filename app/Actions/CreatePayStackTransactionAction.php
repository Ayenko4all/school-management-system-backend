<?php
namespace App\Actions;

use App\Http\Requests\SchoolSetUpFormRequest;
use Exception;

class CreatePayStackTransactionAction {
    /**
     * @param SchoolSetUpFormRequest $request
     * @return array
     * @throws Exception
     */
    public function execute(SchoolSetUpFormRequest $request)
    {
        $fields = [
            'email' => auth()->user()->email,
            'amount' => $request->amount * 100,
            'reference' => generateReferenceCode(),
            'callback_url' => config('auth.paystack.url.callback'),
            'metadata' => [
                'school_name' => $request->school_name,
                'school_address' => $request->school_address,
                'school_email_address' => $request->school_email_address,
                'school_type_id' => $request->school_type_id,
                'cac_document' => $request->cac_document,
                'city' => $request->city,
                'lga' => $request->lga,
                'state' => $request->state,
                'bvn' => $request->bvn,
                'owners' => $request->owners,
                'modules' => $request->modules,
            ]
        ];

        $fields_string = http_build_query($fields);
        //open connection
        $ch = curl_init();

        $key = config('auth.paystack.api_key.sk_test');

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, config('auth.paystack.url.transaction'));
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer {$key}",
            "Cache-Control: no-cache",
        ));

        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

        //execute post
        $result = curl_exec($ch);

        return [json_decode($result, true)];
    }
}
