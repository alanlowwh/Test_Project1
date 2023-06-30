@extends('products.layout')

@section('content')
    <div class="container">
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('products.index') }}"> Back</a>
        </div>
        <h1>Update Product</h1>
        <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="productName" id="name" class="form-control"
                    value="{{ $product->productName }}">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="productDesc" id="description" class="form-control">{{ $product->productDesc }}</textarea>
            </div>

            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" name="productImage" id="image" class="form-control-file">
            </div>
            
            @foreach ($product->variations as $variation)
                <div class="form-group">
                    <div class="form-group">
                        <label for="storage">Storage</label>
                        <input type="text" name="productStorage" id="storage" class="form-control"
                            value="{{ $variation->productStorage }}">
                    </div>


                    <label for="color">Color</label>
                    <input type="text" name="productColor" id="color" class="form-control"
                        value="{{ $variation->productColor }}">
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" name="qty" id="quantity" class="form-control" value="{{ $variation->qty }}">
                </div>

                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" name="productPrice" id="price" class="form-control"
                        value="{{ $variation->productPrice }}">
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="productStatus" id="status" class="form-control">
                        <option value="available" @if ($variation->productStatus == 'available') echo selected @endif>Available</option>
                        <option value="unavailable" @if ($variation->productStatus == 'unavailable') echo selected @endif>Unavailable
                        </option>
                    </select>
                </div>
            @endforeach
            <button type="submit" class="btn btn-primary">Update Product</button>
        </form>
    </div>
@endsection
