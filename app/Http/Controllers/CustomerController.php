<?php

namespace App\Http\Controllers;

use App\Client\Shopify;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    private $shopifyClient;
    
    public function __construct(Shopify $shopifyClient)
    {
        $this->shopifyClient = $shopifyClient;
    }
    
    /**
     * Display a index view with data.
     *
     * @return \Illuminate\Http\Response
     */
    public function appDashboard() {
        $allCustomers = $this->index();
        return view('welcome', compact('allCustomers'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Customer::all()->sortByDesc("created_at");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('from store method..');
        Log::info('Request Array:', $request->all());

        $rawRequestData = json_decode($request->get('rawRequest'), true);
        Log::debug('rawRequestData Array:', [$rawRequestData]);
        
        $dataPayLoad = [
            "first_name" => $rawRequestData['q3_name']['first'],
            "last_name" => $rawRequestData['q3_name']['last'],
            "company" => $rawRequestData['q8_company'],
            "email" => $rawRequestData['q4_email'],
            "phone_number" => $rawRequestData['q6_phoneNumber']['full'],
            "street_address" => $rawRequestData['q5_address']['addr_line1'],
            "street_address_2" => $rawRequestData['q5_address']['addr_line2'],
            "city" => $rawRequestData['q5_address']['city'],
            "state" => $rawRequestData['q5_address']['state'],
            "zip_code" => $rawRequestData['q5_address']['postal'],
            "country" => $rawRequestData['q9_country']
        ];
        Log::debug('DataPayLoad: Array:', [$dataPayLoad]);

        Customer::create($dataPayLoad);

        $this->shopifyClient->saveCustomer($dataPayLoad);

        return response()->json([
            'Data' => [
                'success' => true,
                'message' => 'Data Successfully Added.'
                ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
    }

}
