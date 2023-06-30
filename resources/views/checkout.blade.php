@extends('order.layout')
@section('title')

    Checkout
@endsection

@section('content')
    <div class="container mt-5">
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ url('confirm-order') }}" method="POST">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-body">
                            <h6>Basic Details</h6>
                            <hr>
                                <div class="row checkout-form">
                                    <div class="col-md-6">
                                        <label for="firstName">Name</label>
                                        <input type="text" class="form-control" value="{{ old('name',Auth::user()->name) }}" name="name" placeholder="Enter Name">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email">Email</label>
                                        <input type="text" class="form-control" value="{{ old('email',Auth::user()->email) }}" name="email" placeholder="Enter Email">
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label for="phone_number">Phone Number</label>
                                        <input type="text" class="form-control" value="{{ old('phone_number',Auth::user()->phoneNumber) }}" name="phone_number" placeholder="Enter Phone Number">
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label for="address">Address</label>
                                        <input type="text" class="form-control" value="{{ old('address',Auth::user()->address) }}" name="address" placeholder="Enter Address">
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label for="city">City</label>
                                        <input type="text" class="form-control" value="{{ old('city',Auth::user()->city) }}" name="city" placeholder="Enter City">
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label for="postalCode">Postal Code</label>
                                        <input type="text" class="form-control" value="{{ old('postal_code',Auth::user()->postcode) }}" name="postal_code" placeholder="Enter Postal Code">
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>        
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-body">
                            <h6>Order Details</h6>
                            <hr>
                            <table class="table table-stripped">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Variant</th>
                                        <th>Quantity</th>
                                        <th>Total Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cartitems as $item)
                                    <tr>
                                        <td><strong>
                                            @php
                                                $variation = \App\Models\Variation::where('id', $item->variationId)->first();
                                                $product = \App\Models\Product::where('id', $variation->productId)->first();
                                                echo $product->productName;
                                            @endphp 
                                        </strong></td>
                                        <td>
                                            @php
                                                $variation = \App\Models\Variation::where('id', $item->variationId)->first();
                                            @endphp
                                            <p><strong>Color:</strong> {{ $variation->productColor }}</p>
                                            <p><strong>Storage:</strong> {{ $variation->productStorage }}</p>
                                        </td>
                                        <td>{{ $item->cartProductQty }}</td>
                                        <td>RM{{ $item->subTotal }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <hr>
                            <button type="submit" class="btn btn-primary float-right">Place Order</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection