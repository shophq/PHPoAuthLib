<?php
/**
 * OAuth2 service implementation for Google.
 *
 * PHP version 5.4
 *
 * @category   OAuth
 * @package    OAuth2
 * @subpackage Service
 * @author     Lusitanian <alusitanian@gmail.com>
 * @copyright  Copyright (c) 2012 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */
namespace OAuth\OAuth2\Service;

use OAuth\OAuth2\Token\StdOAuth2Token;
use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\Common\Http\Uri;

/**
 * OAuth2 service implementation for Microsoft.
 *
 * @category   OAuth
 * @package    OAuth2
 * @subpackage Service
 * @author     Lusitanian <alusitanian@gmail.com>
 */
class Microsoft extends AbstractService
{
    const SCOPE_BASIC = 'wl.basic';
    const SCOPE_OFFLINE = 'wl.offline_access';
    const SCOPE_SIGNIN = 'wl.signin';
    const SCOPE_BIRTHDAY = 'wl.birthday';
    const SCOPE_CALENDARS = 'wl.calendars';
    const SCOPE_CALENDARS_UPDATE = 'wl.calendars_update';
    const SCOPE_CONTACTS_BIRTHDAY = 'wl.contacts_birthday';
    const SCOPE_CONTACTS_CREATE = 'wl.contacts_create';
    const SCOPE_CONTACTS_CALENDARS = 'wl.contacts_calendars';
    const SCOPE_CONTACTS_PHOTOS = 'wl.contacts_photos';
    const SCOPE_CONTACTS_SKYDRIVE = 'wl.contacts_skydrive';
    const SCOPE_EMAILS = 'wl.emails';
    const sCOPE_EVENTS_CREATE = 'wl.events_create';
    const SCOPE_MESSENGER = 'wl.messenger';
    const SCOPE_PHONE_NUMBERS = 'wl.phone_numbers';
    const SCOPE_PHOTOS = 'wl.photos';
    const SCOPE_POSTAL_ADDRESSES = 'wl.postal_addresses';
    const SCOPE_SHARE = 'wl.share';
    const SCOPE_SKYDRIVE = 'wl.skydrive';
    const SCOPE_SKYDRIVE_UPDATE = 'wl.skydrive_update';
    const SCOPE_WORK_PROFILE = 'wl.work_profile';
    const SCOPE_APPLICATIONS = 'wl.applications';
    const SCOPE_APPLICATIONS_CREATE = 'wl.applications_create';

    /**
     * @return \OAuth\Common\Http\Uri|\OAuth\Common\Http\UriInterface
     */
    public function getAuthorizationEndpoint()
    {
        return new Uri('https://login.live.com/oauth20_authorize.srf');
    }

    /**
     * @return \OAuth\Common\Http\Uri|\OAuth\Common\Http\UriInterface
     */
    public function getAccessTokenEndpoint()
    {
        return new Uri('https://login.live.com/oauth20_token.srf');
    }

    /**
     * @param string $responseBody
     * @return \OAuth\Common\Token\TokenInterface|\OAuth\OAuth2\Token\StdOAuth2Token
     * @throws \OAuth\Common\Http\Exception\TokenResponseException
     */
    protected function parseAccessTokenResponse($responseBody)
    {
        $data = json_decode( $responseBody, true );

        if( null === $data || !is_array($data) ) {
            throw new TokenResponseException('Unable to parse response.');
        } elseif( isset($data['error'] ) ) {
            throw new TokenResponseException('Error in retrieving token: "' . $data['error'] . '"');
        }

        $token = new StdOAuth2Token();
        $token->setAccessToken( $data['access_token'] );
        $token->setLifetime( $data['expires_in'] );

        if( isset($data['refresh_token'] ) ) {
            $token->setRefreshToken( $data['refresh_token'] );
            unset($data['refresh_token']);
        }

        unset( $data['access_token'] );
        unset( $data['expires_in'] );

        $token->setExtraParams( $data );

        return $token;
    }

    /**
     * @return int
     */
    public function getAuthorizationMethod()
    {
        return static::AUTHORIZATION_METHOD_QUERY_STRING;
    }
}
