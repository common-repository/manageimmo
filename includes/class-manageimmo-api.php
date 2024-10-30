<?php

/**
 * @package ManageImmo
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

class ManageImmo_API {

    /**
	 * @var string
	 */
    private $consumer_key;

    /**
	 * @var string
	 */
    private $consumer_secret;

    /**
	 * @var string
	 */
    private $token;

    /**
	 * @var string
	 */
    private $token_secret;

    /**
	 * @var bool
	 */
    private $is_sandbox = true;

    public function __construct( $key, $secret ) {
        $this->consumer_key    = $key;
        $this->consumer_secret = $secret;

        $this->token           = get_option( 'immoscout24_oauth_token' );
        $this->token_secret    = get_option( 'immoscout24_oauth_token_secret' );
    }

    /**
     * Enable the sandbox API.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function enable_sandbox() {
        $this->is_sandbox = true;
    }

    /**
     * Disable the sandbox API.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function disable_sandbox() {
        $this->is_sandbox = false;
    }

    /**
     * Get domain depending on the sandbox.
     *
     * @ince 1.0.0
     *
     * @return string
     */
    public function get_domain() {
        return $this->is_sandbox ? 'sandbox-immobilienscout24.de' : 'immobilienscout24.de';
    }

    /**
     * Get base url depending on the sandbox.
     *
     * @return string base url
     */
    private function get_base_url() {
        $domain = $this->get_domain();

        return 'https://rest.' . $domain . '/restapi/';
    }

    /**
     * Sign the given URL.
     *
     * @since 1.0.0
     *
     * @return string
     */
    private function sign_url( $url, $token_secret = '', $method = 'GET' ) {
        $parsed_url       = wp_parse_url( $url );
        $scheme           = $parsed_url['scheme'];
        $host             = $parsed_url['host'];
        $path             = $parsed_url['path'];
        $query_string     = $parsed_url['query'] ?? '';

        $signature_base = $method . '&' . rawurlencode( $scheme . '://' . $host . $path ) . '&';

        $nonce            = md5( wp_rand() );
        $timestamp        = time();
        $signature_method = 'HMAC-SHA1';

        $query = array(
            'oauth_consumer_key'     => rawurlencode( $this->consumer_key ),
            'oauth_nonce'            => $nonce,
            'oauth_signature_method' => $signature_method,
            'oauth_timestamp'        => $timestamp,
        );

        parse_str( $query_string, $query_array );

        foreach ( $query_array as $key => $value ) {
            $query[ $key ] = $value;
        }

        if( empty( $query[ 'oauth_token' ] ) && $this->token ) {
            $query[ 'oauth_token' ] = rawurlencode( $this->token );
        }

        ksort( $query );

        $query = http_build_query( $query );

        $signature_base .= rawurlencode( $query );

        $signature_key = $this->consumer_secret . "&";

        $signature_key .= rawurlencode( $token_secret ? $token_secret : $this->token_secret );

        $signature = base64_encode( hash_hmac( 'sha1', $signature_base, $signature_key, true ) );

        $signed_url = $scheme . '://' . $host . $path . '?';

        $signed_url .= $query;
        $signed_url .= "&oauth_signature=" . rawurlencode( $signature );

        return $signed_url;
    }

    /**
     * Request the request token.
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function request_request_token( $callback ) {
        $request_token_url  = $this->get_base_url() . 'security/oauth/request_token';
        $confirm_access_url = $this->get_base_url() . 'security/oauth/confirm_access';

        $url = $this->sign_url( add_query_arg( 'oauth_callback', $callback, $request_token_url ) );

        $response = wp_remote_get( $url );
        $code     = wp_remote_retrieve_response_code( $response );
        $body     = wp_remote_retrieve_body( $response );

        if( 200 !== $code ) {
            return new WP_Error( 'failed_manageimmo_request', $body );
        }

        parse_str( $body, $values );

        set_transient( 'immoscout_request_token_secret', $values['oauth_token_secret'] );

        $redirect_url = $confirm_access_url . '?oauth_token=' . $values['oauth_token'];

        wp_redirect( $redirect_url );
        die;
    }

    /**
     * Request the access token.
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function request_access_token( $callback ) {
        $access_token_url =  $this->get_base_url() . 'security/oauth/access_token';

        $url = $this->sign_url( add_query_arg( array(
            'oauth_verifier'     => sanitize_text_field( $_GET['oauth_verifier'] ),
            'oauth_token'        => sanitize_text_field( $_GET['oauth_token'] ),
        ), $access_token_url ), get_transient( 'immoscout_request_token_secret' ) );

        delete_transient( 'immoscout_request_token_secret' );

        $response = wp_remote_get( $url );
        $code     = wp_remote_retrieve_response_code( $response );
        $body     = wp_remote_retrieve_body( $response );

        if( 200 !== $code ) {
            return new WP_Error( 'failed_manageimmo_request', $body );
        }

        parse_str( $body, $values );

        update_option( 'immoscout24_oauth_token',        sanitize_text_field( $values['oauth_token'] ) );
        update_option( 'immoscout24_oauth_token_secret', sanitize_text_field( $values['oauth_token_secret'] ) );

        wp_redirect( $callback );
        die;
    }

    /**
     * Authorize the application via OAuth1.
     *
     * @since 1.0.0
     *
     * @param  string $callback
     * @return void
     */
    public function authorize( $callback ) {
        if ( isset( $_GET['oauth_verifier'] ) && isset( $_GET['oauth_token'] ) ) {
            $this->request_access_token( $callback );
        } else {
            $this->request_request_token( $callback );
        }
    }

    /**
     * Get endpoint.
     *
     * @since 1.0.0
     *
     * @return object|false
     */
    public function get( $endpoint ) {
        $url = $this->sign_url( $this->get_base_url() . 'api' . $endpoint );

        $response = wp_remote_get( $url, array(
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ) );

        $code = wp_remote_retrieve_response_code( $response );
        $body = wp_remote_retrieve_body( $response );

        if( 200 !== $code ) {
            return false;
        }

        return json_decode( $body );
    }

    /**
     * Post endpoint.
     *
     * @since 1.0.0
     *
     * @param  string $endpoint
     * @param  mixed  $body the JSON body.
     * @return object
     */
    public function post( $endpoint, $body ) {
        $url = $this->sign_url( $this->get_base_url() . 'api' . $endpoint, '', 'POST' );

        $response = wp_remote_post( $url, array(
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => $body,
        ) );

        return $response;
    }

}