<?php

class Pipedrive {
    var $api_url = 'https://api.pipedrive.com/v1/';
    var $api_token;

    public function __construct( $api_token ) {
        $this->api_token = $api_token;
    }

    public function getOrganization( $name, $defaults ) {
        $response = $this->makeRequest('organizations/find', array(
            'term' => $name
        ));

        if ( count( $response->data ) > 0 ) {
            return $response->data[0];
        }

        $response = $this->makeRequest( 'organizations', $defaults, $post = true );
        return $response->data;
    }

    public function getList( $object ) {
        return $this->makeRequest( $object )->data;
    }

    private function makeRequest( $endpoint, $params = array(), $post = false ) {
        $url = $this->api_url . $endpoint . '?api_token=' . $this->api_token;
        $params = http_build_query( $params );
        $options = array();

        if ( $post ) {
            $options['method'] = 'POST';
            $options['content'] = $params;
        } elseif ( $params ) {
            $url .= '&' . $params;
        }

        $context = stream_context_create( array( 'http' => $options ) );
        $request = file_get_contents( $url, false, $context );
        $response = json_decode( $request );

        return $response;
    }
}

?>
