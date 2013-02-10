<?php

class Pipedrive {
    var $api_url = 'https://api.pipedrive.com/v1/';
    var $api_token;

    public function __construct( $api_token ) {
        $this->api_token = $api_token;
    }

    public function find( $object, $name ) {
        $response = $this->makeRequest( $object  . '/find', array(
            'term' => $name
        ));

        return $response->data;
    }

    public function getList( $object ) {
        return $this->makeRequest( $object )->data;
    }

    public function getOrCreate( $object, $name, $defaults ) {
        $data = $this->find( $object, $name );

        if ( count( $data ) > 0 ) {
            return $data[0];
        }

        return $this->create( $object, $defaults );
    }

    public function create( $object, $defaults ) {
        $response = $this->makeRequest( $object, $defaults, $post = true );

        return $response->data;
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
