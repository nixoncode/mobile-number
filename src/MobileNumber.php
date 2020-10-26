<?php
/**
 * Created by PhpStorm.
 * User: nixon
 * Date: 26/10/2020
 * Time: 06:10
 */

namespace Nixon\MobileNumber;



use function array_push;
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
            array_push($safaricomPrefixes, (int)("70" . $i));
        }
        for ($i = 0; $i < 10; $i++) { //0710 -> 0719
            array_push($safaricomPrefixes, (int)("71" . $i));
        }
        for ($i = 0; $i < 10; $i++) { //0720 -> 0729
            array_push($safaricomPrefixes, (int)("72" . $i));
        }
        
        for ($i = 0; $i < 10; $i++) { //0790 -> 0799
            array_push($safaricomPrefixes, (int)("79" . $i));
        }
        
        for ($i = 0; $i < 10; $i++) { //0740 -> 0749
            if ($i == 4 || $i == 7 || $i == 9) { // skip 0744, 0747, 0749
                continue;
            }
            array_push($safaricomPrefixes, (int)("74" . $i));
        }
        
        // for 075*
        array_push($safaricomPrefixes, 757);
        array_push($safaricomPrefixes, 758);
        array_push($safaricomPrefixes, 759);
        
        // 076*
        array_push($safaricomPrefixes, 768);
        array_push($safaricomPrefixes, 769);
        
        
        // recent 01 prefixes
        array_push($safaricomPrefixes, 110);
        array_push($safaricomPrefixes, 111);
        return $safaricomPrefixes;
    }
    
    private static function airtelPrefixes()
    {
        $airtelPrefixes = [];
        
        for ($i = 0; $i < 10; $i++) { //0730 -> 0739
            array_push($airtelPrefixes, (int)("73" . $i));
        }
        
        for ($i = 0; $i < 7; $i++) { //0750 -> 0756
            array_push($airtelPrefixes, (int)("75" . $i));
        }
        
        
        for ($i = 0; $i < 10; $i++) { //0780 -> 0789
            array_push($airtelPrefixes, (int)("78" . $i));
        }
        
        array_push($airtelPrefixes, 762);
        
        // new prefixes
        array_push($airtelPrefixes, 100);
        array_push($airtelPrefixes, 101);
        array_push($airtelPrefixes, 102);
        return $airtelPrefixes;
    }
    
    private static function telkomPrefixes()
    {
        $telkomPrefixes = [];
        for ($i = 0; $i < 10; $i++) { //0770 -> 0779
            array_push($telkomPrefixes, (int)("77" . $i));
        }
        
        return $telkomPrefixes;
    }
    
    private static function equitelPrefix()
    {
        $equitelPrefix = [];
        
        array_push($equitelPrefix, 763);
        array_push($equitelPrefix, 764);
        array_push($equitelPrefix, 765);
        
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
                break;
            case 10:
                if (substr($phoneNumber, 0, 2) == "07" || substr($phoneNumber, 0, 2) == "01") {
                    $sanitized = (float)$phoneNumber; // drops the leading 0
                    $sanitized = "254" . $sanitized; // drops the leading 0 and appends 254 then cast to int
                    return $sanitized;
                }
                return null;
                break;
            case 9:
                if (substr($phoneNumber, 0, 1) == "7" || substr($phoneNumber, 0, 1) == "1") {
                    return ("254" . $phoneNumber);
                }
                return null;
                break;
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