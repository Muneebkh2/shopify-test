@extends('shopify-app::layouts.default')

@section('content')
    <!-- You are: (shop domain name) -->
    <p>You are: {{ $shopDomain ?? Auth::user()->name }}</p>
    <h6>All Customers</h6>
    <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Full Name</th>
            <th scope="col">Comapny</th>
            <th scope="col">Email</th>
            <th scope="col">Phone</th>
            <th scope="col">Address</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($allCustomers as $customer)   
            <tr>
              <th scope="row">{{$customer->id}}</th>
              <td>{{$customer->first_name . " " . $customer->last_name}}</td>
              <td>{{$customer->Comapny}}</td>
              <td>{{$customer->email}}</td>
              <td>{{$customer->phone}}</td>
              <td>{{$customer->street_address . $customer->street_address_2 . ', \n' . $customer->city . ', ' . $customer->state . ', ' . $customer->zip_code}}</td>
            </tr>
            @endforeach
        </tbody>
      </table>
@endsection

@section('scripts')
    @parent

    <script>
        actions.TitleBar.create(app, { title: 'Welcome' });
    </script>
@endsection