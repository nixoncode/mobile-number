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
    
    /**
     * Get the opt-out for a given sender id
     *
     * @param string $networkName
     * @param string $senderName
     * @return string opt out for the given network with line break included
     */
    public static function getOptOut($networkName, $senderName = null)
    {
        switch ($networkName) {
            case self::NETWORK_SAF:
                return "\nSTOP *456*9*5#";
            case self::NETWORK_AIRTEL:
                //TODO add sender name
                return "\nSTOP TO 20133";
            default:
                return '';
        }
    }
}