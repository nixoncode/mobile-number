<?php
/**
 * Created by PhpStorm.
 * User: nixon
 * Date: 26/10/2020
 * Time: 06:11
 */

namespace Nixon\MobileNumber;


use function array_key_exists;
use function array_search;

class NetworkUtils
{
    const NETWORK_SAF = "Safaricom";
    const NETWORK_AIRTEL = "Airtel";
    const NETWORK_TELKOM = "Telkom";
    const NETWORK_EQUITEL = "Equitel";
    const NETWORK_UNKNOWN = "Unknown";
    
    
    private static $networks = [
        1  => 'Safaricom',
        2  => 'Airtel',
        3  => 'Telkom',
        4  => 'Equitel',
        5  => 'MTN',
        6  => 'Airtel',
        7  => 'Etisalat',
        8  => 'Du',
        9  => 'Intnl',
        10 => 'Unknown',
    ];
    
    
    public static function getNetworkName($netId)
    {
        if (array_key_exists($netId, self::$networks)) {
            return NetworkUtils::$networks[$netId];
        }
        return 'Unknown';
    }
    
    public static function getNetworkId($netName)
    {
        return array_search($netName, self::$networks);
    }
    
    
}