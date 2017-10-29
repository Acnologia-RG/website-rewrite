<?php
/**
 * Retrieves the best guess of the client's actual IP address.
 * Takes into account numerous HTTP proxy headers due to variations
 * in how different ISPs handle IP addresses in headers between hops.
 *
 * @since 1.1.3
 *
 * @return object {
 *         IP Address details
 *
 *         string $ip The users IP address (might be spoofed, if $proxy is true)
 *         bool $proxy True, if a proxy was detected
 *         string $proxy_id The proxy-server IP address
 * }
 */
function ip_info() {
    $result = (object) array(
        'ip'        => $_SERVER['REMOTE_ADDR'],
        'proxy'     => false,
        'proxy_ip'  => '',
    );

    /*
    * This code tries to bypass a proxy and get the actual IP address of
    * the visitor behind the proxy.
    * Warning: These values might be spoofed!
    */
    $ip_fields = array(
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR',
    );
    foreach ( $ip_fields as $key ) {
        if ( array_key_exists( $key, $_SERVER ) === true ) {
            foreach ( explode( ',', $_SERVER[$key] ) as $ip ) {
                $ip = trim( $ip );

                if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) !== false ) {
                    $forwarded = $ip;
                    break 2;
                }
            }
        }
    }

    // If we found a different IP address then REMOTE_ADDR then it's a proxy!
    if ( @$forwarded != $result->ip ) {
        $result->proxy = true;
        $result->proxy_ip = $result->ip;
        $result->ip = @$forwarded;
    }

    return $result;
}