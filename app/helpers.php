<?php
use App\Models\Token;
if (! function_exists('generateToken')) {
    /**
     * Generate a unique token for a user.
     *
     * @return string
     */
    function generateToken()
    {
        do {
            $token = mt_rand(100000, 999999);
        } while (Token::where('token', $token)->exists());

        return (string) $token;
    }
}

if (! function_exists('generateReferenceCode')) {
    /**
     * Generate a user's referral code.
     *
     * @return string
     * @throws Exception
     */
    function generateReferenceCode()
    {
        return bin2hex(random_bytes(3));

    }
}
