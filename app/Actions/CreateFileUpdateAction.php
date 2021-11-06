<?php
namespace App\Actions;

use App\Http\Requests\CreateTeacherRequest;
use App\Models\User;


class CreateFileUpdateAction {
    /**
     * @param CreateTeacherRequest $request
     * @param $teacher
     * @return void
     */
    public function execute(CreateTeacherRequest $request, $teacher): void
    {

        $photo = $request->file('photo');
        $primary_certificate = $request->file('primary_certificate');
        $tertiary_certificate = $request->file('tertiary_certificate');
        $secondary_certificate = $request->file('secondary_certificate');
        $others_certificate = $request->file('others_certificate');
        $identity_card_photo = $request->file('identity_card_photo');

        $photoImagePath = $request->hasFile('photo') ? $photo->store('public/profileImages') : '';
        $primaryCertificateImagePath = $request->hasFile('primary_certificate') ? $primary_certificate->store('public/primaryCertificate') : '';
        $tertiaryCertificateImagePath = $request->hasFile('tertiary_certificate') ? $tertiary_certificate->store('public/tertiaryCertificate') : '';
        $secondaryCertificate = $request->hasFile('secondary_certificate') ? $secondary_certificate->store('public/secondaryCertificate') : '';
        $othersCertificateImagePath = $request->hasFile('others_certificate') ? $others_certificate->store('public/othersCertificate') : '';
        $identityCardPhotoImagePath = $request->hasFile('identity_card_photo') ? $identity_card_photo->store('public/identityCardPhoto') : '';


        $teacher->update([
            'identity_card_photo'       => $identityCardPhotoImagePath,
            'tertiary_certificate'      => $tertiaryCertificateImagePath,
            'primary_certificate'       => $primaryCertificateImagePath,
            'secondary_certificate'     => $secondaryCertificate,
            'others_certificate'        => $othersCertificateImagePath,
        ]);

        User::where('id', '=', $teacher->user_id)->update(['photo'=> $photoImagePath]);
    }
}
