@extends('order.layout')
@section('title')
    Orders
@endsection

@section('content')

    <div class="container">
        <div class="row"></div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h4 class="text-white">Current Orders
                            <hr>
                            <a href="{{ route('orders.report') }}" class="btn btn-warning btn-primary">Generate Report</a>
                            <a href="{{ 'order-history' }}" class="btn btn-warning float-right"> Order History</a>
                        </h4>
                        
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Order Date</th>
                                    <th>Tracking Number</th>
                                    <th>Total Price</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $items)
                                <tr>
                                    <td>{{ date('d-m-y', strtotime($items->created_at)) }} </td>
                                    <td>{{ $items->tracking_no }}</td>
                                    <td>{{ $items->total_price }}</td>
                                    <td>{{ $items->status == '0' ? 'Pending' : 'Completed' }}</td>
                                    <td>
                                        <a href="{{ url('view-order/'.$items->id) }}" class="btn btn-primary">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a href="{{ route('display.variations') }}" class="btn btn-primary float-right">Go to Product Page</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection