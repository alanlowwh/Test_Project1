@extends('products.layout')

@section('content')
    <h1>All Products</h1>
    <div class="pull-right">
        <a class="btn btn-success" href="{{ route('products.create') }}"> Create New Product</a>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Image</th>
                <th>Storage</th>
                <th>Color</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Status</th>
                <th>Action</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->productName }}</td>
                    <td>{{ $product->productDesc }}</td>
                    <td>
                        @if ($product->productImage)
                            <img src="data:image/png;base64,{{ base64_encode($product->productImage) }}" alt="Product Image"
                                style="width:50px;height:50px">
                        @else
                            No image
                        @endif
                    </td>
                    @foreach ($product->variations as $variation)
                        <td>{{ $variation->productStorage }}</td>
                        <td>{{ $variation->productColor }}</td>
                        <td>{{ $variation->qty }}</td>
                        <td>{{ $variation->productPrice }}</td>
                        <td>{{ $variation->productStatus }}</td>
                    
                    <td>
                      <form action="{{ route('products.destroy',$product->id) }}" method="POST">
                          <a class="btn btn-primary" href="{{ route('products.edit',$product->id) }}">Edit</a>
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-danger">Delete</button>
                      </form>
                  </td>
                  @endforeach
                </tr>
            @endforeach            
        </tbody>        
    </table>
    {{ $products->appends(request()->except('page'))->links() }}
@endsection
