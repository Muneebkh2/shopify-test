<?php

class Shopify {
    public function saveCustomer($customerDataPayload) {
        $accessToken = config('shopify.api.access_token');
        $storeUrl = config('shopify.store_url');
        
        $formattedData = ["customer" => [
            "first_name" => $customerDataPayload['first_name'],
            "last_name" => $customerDataPayload['last_name'],
            "email" => $customerDataPayload['email'],
            "phone" => "+1". preg_replace( '/[^0-9]/', '', $customerDataPayload['phone_number']),
            "verified_email" => false,
            "company" => $customerDataPayload['company'],
            "address" => [
                "address1" => $customerDataPayload['street_address'],
                "city" => $customerDataPayload['city'],
                "province" => $customerDataPayload['state'],
                "zip" => $customerDataPayload['zip_code'],
                "last_name" => $customerDataPayload['last_name'],
                "first_name" => $customerDataPayload['first_name'],
                "country" => isset($customerDataPayload['country']) ?? ''
            ]
        ]]; 

        return $formattedData;
    }
}