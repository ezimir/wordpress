<?php

class Pipedrive {
    var $api_url = 'https://api.pipedrive.com/v1/';

    var $api_token;

    public function __construct($api_token) {
        $this->api_token = $api_token;
    }

    public function get_organization($search_term) {
        $response = $this->make_request('organizations/find', array(
            'term' => $search_term
        ));

        if (count($response->data) === 1) {
            return $response->data[0];
        }
    }

    private function get_request_url($endpoint, $params) {
        $params['api_token'] = $this->api_token;

        $url = $this->api_url . $endpoint . '?' . http_build_query($params);

        return $url;
    }

    private function make_request($endpoint, $params) {
        $url = $this->get_request_url($endpoint, $params);
        $request = file_get_contents($url);
        $response = json_decode($request);

        return $response;
    }
}

?>
