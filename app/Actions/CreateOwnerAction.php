<?php
namespace App\Actions;

use App\Http\Requests\SchoolSetUpFormRequest;
use App\Models\SchoolOwner;

class CreateOwnerAction {
    /**
     * @param $request
     * @param $school_id
     * @return array
     */
    public function execute(SchoolSetUpFormRequest $request, $school_id)
    {
        $owner = [];
        foreach ($request->owners as $key => $owner){
            $decodedValue =json_decode($owner, true);
            $newOwner = [
                'user_id' => auth()->id(),
                'school_id' =>  $school_id,
                'first_name' => $decodedValue['first_name'],
                'last_name' => $decodedValue['last_name'],
                'email' => $decodedValue['email'],
                'telephone' => $decodedValue['telephone'],
                'bvn' => $decodedValue['bvn'],
                'id_card_type' => $decodedValue['id_card_type'],
                'date_of_birth' => $decodedValue['date_of_birth'],
                'id_card_photo' => $decodedValue['id_card_photo'],
            ];

            $owner = SchoolOwner::create($newOwner);
        }

        return [$owner];
    }
}
