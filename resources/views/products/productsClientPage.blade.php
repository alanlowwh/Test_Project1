@extends('clientLayout')
@section('content')

<head>
    <title>Display Product</title>
    
    <style>
        body {
            font-family: "Poppins", sans-serif;
        }
        
        .card {
            margin: 20px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2);
            border: none;
        }
        
        .card img {
            height: 300px;
            object-fit: contain;
        }
        
        .card-title {
            font-weight: 500;
            margin-top: 10px;
        }
        
        .card-text {
            margin-top: 10px;
        }
        
        .card-footer {
            background-color: #f2f2f2;
            border: none;
        }
        
        .price {
            font-size: 1.5rem;
            font-weight: 500;
            color: #fe302f;
        }
        
        .btn{
            background-color: #fe302f;
            color: #fff;
            border: none;
            margin-top: 10px;
        }
        
        .btn-add-to-cart:hover {
            background-color: #ff4f4f;
            color: #fff;
            border: none;
        }
    </style>

 @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
    <div class="container">
        <div class="row">
            @foreach ($transformedVariations as $variation)
                <div class="col-md-6 col-lg-4">
                    <div class="card">
                        <img src="data:image/png;base64,{{ $variation['productImage'] }}" alt="Product Image" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title">{{ $variation['productName'] }}</h5>
                            <h5 class="card-title">Storage: {{ $variation['productStorage'] }}</h5>
                            <h5 class="card-title">Color: {{ $variation['productColor'] }}</h5>
                            <p class="card-text">RM{{ $variation['variationPrice'] }}</p>
                        </div>
                        <div class="card-footer">
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="productId" value="{{ $variation['productId'] }}">
                                <input type="hidden" name="variationId" value="{{ $variation['variationId'] }}">
                                <button type="submit" class="btn btn-primary">Add to Cart</button>
                            </form>
                            
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection
