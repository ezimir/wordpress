<?php

class Pipedrive {
    var $api_url = 'https://api.pipedrive.com/v1/';
    var $api_token;

    public function __construct( $api_token ) {
        $this->api_token = $api_token;
    }

    public function getOrganization( $search_term ) {
        $response = $this->makeRequest('organizations/find', array(
            'term' => $search_term
        ));

        if (count($response->data) === 1) {
            return $response->data[0];
        }
    }

    public function getList( $object ) {
        return $this->makeRequest( $object )->data;
    }

    private function getRequestUrl( $endpoint, $params ) {
        $params['api_token'] = $this->api_token;

        $url = $this->api_url . $endpoint . '?' . http_build_query($params);

        return $url;
    }

    private function makeRequest( $endpoint, $params = array() ) {
        $url = $this->getRequestUrl( $endpoint, $params );
        $request = file_get_contents( $url );
        $response = json_decode( $request );

        return $response;
    }
}

?>
