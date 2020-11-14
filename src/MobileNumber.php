<?php
/**
 * Created by PhpStorm.
 * User: nixon
 * Date: 26/10/2020
 * Time: 06:10
 */

namespace Nixon\MobileNumber;


use function in_array;
use function preg_replace;
use function strlen;
use function substr;

class MobileNumber
{
    
    public static function getNetwork($phoneNumber)
    { //
        
        
        $prefix = substr($phoneNumber, 3, 3);
        
        if (in_array($prefix, self::safaricomPrefixes())) {
            return NetworkUtils::NETWORK_SAF;
        }
        
        if (in_array($prefix, self::airtelPrefixes())) {
            return NetworkUtils::NETWORK_AIRTEL;
        }
        
        if (in_array($prefix, self::telkomPrefixes())) {
            return NetworkUtils::NETWORK_TELKOM;
        }
        if (in_array($prefix, self::equitelPrefix())) {
            return NetworkUtils::NETWORK_EQUITEL;
        }
        
        return NetworkUtils::NETWORK_UNKNOWN;
    }
    
    private static function safaricomPrefixes()
    {
        $safaricomPrefixes = [];
        
        for ($i = 0; $i < 10; $i++) { //0700 -> 0709
            $safaricomPrefixes[]= (int)("70" . $i);
        }
        for ($i = 0; $i < 10; $i++) { //0710 -> 0719
            $safaricomPrefixes[]= (int)("71" . $i);
        }
        for ($i = 0; $i < 10; $i++) { //0720 -> 0729
            $safaricomPrefixes[]= (int)("72" . $i);
        }
        
        for ($i = 0; $i < 10; $i++) { //0790 -> 0799
            $safaricomPrefixes[]= (int)("79" . $i);
        }
        
        for ($i = 0; $i < 10; $i++) { //0740 -> 0749
            if ($i == 4 || $i == 7 || $i == 9) { // skip 0744, 0747, 0749
                continue;
            }
            $safaricomPrefixes[]= (int)("74" . $i);
        }
        
        // for 075*
        $safaricomPrefixes[]= 757;
        $safaricomPrefixes[]= 758;
        $safaricomPrefixes[]= 759;
        
        // 076*
        $safaricomPrefixes[] =768;
        $safaricomPrefixes[]= 769;
        
        
        // recent 01 prefixes
        for ($i = 0; $i < 6; $i++) {
            $safaricomPrefixes[] = (int)"11" . $i;
        }
        
        return $safaricomPrefixes;
    }
    
    private static function airtelPrefixes()
    {
        $airtelPrefixes = [];
        
        for ($i = 0; $i < 10; $i++) { //0730 -> 0739
            $airtelPrefixes[] = (int)("73" . $i);
        }
        
        for ($i = 0; $i < 7; $i++) { //0750 -> 0756
            $airtelPrefixes[] = (int)("75" . $i);
        }
        
        
        for ($i = 0; $i < 10; $i++) { //0780 -> 0789
            $airtelPrefixes[] = (int)("78" . $i);
        }
        
        $airtelPrefixes[]= 762;
        
        // new prefixes
        for ($i = 0; $i < 7; $i++) { // 100 -> 106
            $airtelPrefixes[] = (int)("10" . $i);
        }
        return $airtelPrefixes;
    }
    
    private static function telkomPrefixes()
    {
        $telkomPrefixes = [];
        for ($i = 0; $i < 10; $i++) { //0770 -> 0779
            $telkomPrefixes[] = (int)("77" . $i);
        }
        
        return $telkomPrefixes;
    }
    
    private static function equitelPrefix()
    {
        $equitelPrefix = [];
        
        $equitelPrefix[] = 763;
        $equitelPrefix[] = 764;
        $equitelPrefix[] = 765;
        
        return $equitelPrefix;
    }
    
    public static function validateAndSanitizeNumber($phoneNumber)
    {
        //remove any non digit characters
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        
        $length = strlen($phoneNumber);
        
        
        switch ($length) {
            case 12:
                if (substr($phoneNumber, 0, 3) == 254) {
                    return $phoneNumber;
                }
                return null;
            case 10:
                if (substr($phoneNumber, 0, 2) == "07" || substr($phoneNumber, 0, 2) == "01") {
                    $sanitized = (float)$phoneNumber; // drops the leading 0
                    $sanitized = "254" . $sanitized; // drops the leading 0 and appends 254 then cast to int
                    return $sanitized;
                }
                return null;
            case 9:
                if (substr($phoneNumber, 0, 1) == "7" || substr($phoneNumber, 0, 1) == "1") {
                    return ("254" . $phoneNumber);
                }
                return null;
            default:
                return null;
        }
    }
    
    /**
     * Hides some characters of the phone number
     * @param $msisdn int|float|string phone number
     * @return string with hidden characters
     * @example 254719133270 becomes 0719XXX270
     */
    public static function maskMsisdn($msisdn)
    {
        $len = strlen($msisdn);
        $r = '0';
        $r .= substr($msisdn, 3, 3);
        $r .= 'XXX';
        $r .= substr($msisdn, $len - 3, $len);
        
        return $r;
    }
}