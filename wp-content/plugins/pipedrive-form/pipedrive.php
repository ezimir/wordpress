<?php

class Pipedrive {
    var $api_url = 'https://api.pipedrive.com/v1/';
    var $api_token;

    public function __construct( $api_token ) {
        $this->api_token = $api_token;
    }

    public function get( $object, $id ) {
        return $this->makeRequest( $object . '/' . $id )->data;
    }

    public function getList( $object ) {
        return $this->makeRequest( $object )->data;
    }

    public function find( $object, $name ) {
        $response = $this->makeRequest( $object . '/find', array(
            'term' => $name
        ));

        if ( count( $response->data ) === 0 ) {
            return false;
        }

        return $this->get( $object, $response->data[0]->id );
    }

    public function getOrCreate( $object, $name, $defaults ) {
        $data = $this->find( $object, $name );

        if ( $data ) {
            return $data;
        }

        return $this->create( $object, $defaults );
    }

    public function create( $object, $defaults ) {
        return $this->makeRequest( $object, $defaults, 'post' )->data;
    }

    public function update( $object, $id, $data ) {
        $response = $this->makeRequest( $object . '/' . $id, $data, 'put' );
    }

    private function makeRequest( $endpoint, $params = array(), $method = 'get' ) {
        $url = $this->api_url . $endpoint . '?api_token=' . $this->api_token;
        $params = http_build_query( $params );
        $options = array();

        if ( $method !== 'get' ) {
            $options['method'] = strtoupper($method);
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
