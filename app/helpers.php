<?php

use App\Enums\RoleEnum;
use App\Models\Token;
use App\Options\DefaultRole;
use App\Options\TermOption;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Spatie\Sluggable\SlugOptions;

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

if (! function_exists('generateTempPassword')) {
    /**
     * Generate a user's referral code.
     *
     * @return string
     * @throws Exception
     */
    function generateTempPassword()
    {
        return bin2hex(random_bytes(3));

    }
}

if(! function_exists('generateSlugName')){
    function generateSlugName(){
       return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}

if(! function_exists('generateDuration')){
  function generateDuration($start_date, $end_date): int
  {
      $start = Carbon::parse($end_date);
      $end = Carbon::parse($start_date);
      return $end->diffInMonths($start);
  }
}

if (! function_exists('defaultOptionNames')) {
    function defaultOptionNames($optionsClass)
    {
        if ($optionsClass === TermOption::class && Cache::has('defaultTermNames')) {
            return Cache::get('defaultTermNames');
        }

        if ($optionsClass === DefaultRole::class && Cache::has('defaultRoleName')) {
            return Cache::get('defaultRoleName');
        }

        $options = app($optionsClass)->getConstants();

        if (($key = array_search(DefaultRole::SUPERADMIN, $options, true)) !== false) {
            unset($options[$key]);
        }

        $newOptionArray = [];

        foreach($options as $key => $value){
           $newOptionArray[] = $value;
        }

        if ($optionsClass === TermOption::class) {
            Cache::put('defaultTermNames', $newOptionArray, now()->addDay());
            return Cache::get('defaultTermNames');
        }

        if ($optionsClass === DefaultRole::class) {
            Cache::put('defaultRoleName', $newOptionArray, now()->addDay());
            return Cache::get('defaultRoleName');
        }

    }
}
