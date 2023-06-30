
@extends('clientLayout')
@section('content')
@php
    $totalAmount = 0;
    foreach ($transformedCartProducts as $cartProduct) {
        $totalAmount += $cartProduct['subTotal'];
    }
@endphp


    @error('quantity')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    <div class="container">
        <h1>Cart</h1>
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Product Name</th>
                    <th scope="col">Image</th>
                    <th scope="col">Storage</th>
                    <th scope="col">Color</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Unit Price</th>
                    <th scope="col">Subtotal</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transformedCartProducts as $cartProduct)
                    <tr>
                        <td>{{ $cartProduct['productName'] }}</td>
                        <td><img src="data:image/jpeg;base64,{{ $cartProduct['productImage'] }}" width="200" height="200" /></td>
                        <td>{{ $cartProduct['productStorage'] }}</td>
                        <td>{{ $cartProduct['productColor'] }}</td>
                        <td>
                            <input type="number" min="1" max="99" value="{{ $cartProduct['cartProductQty'] }}" style="width:40px" oninput="updateSubtotal(this, {{ $cartProduct['variationPrice'] }}, {{ $cartProduct['variationId'] }})" name="quantity" data-variation-id="{{ $cartProduct['variationId'] }}" onchange="updateQuantity(this)">
                            
                        </td>
                        <td>{{ $cartProduct['variationPrice'] }}</td>
                        <td id="subtotal-{{ $loop->index }}">{{ $cartProduct['subTotal'] }}</td>
                        <td>
                            <a href="#" class="btn btn-danger" onclick="deleteProduct({{ $cartProduct['variationId'] }})">Delete</a>


                        </td>
                    </tr>
                @endforeach
                <tr>
                    <th colspan="6">Total Amount:</th>
                    <td id="totalAmount">{{ $totalAmount }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <div class="d-flex justify-content-between">
            <div>
                
                <button type="button" class="btn btn-secondary" onclick="window.location.href = '{{ route('export.cart.products') }}'">Back</button>

            </div>
        </div>
    </div>


    <script>
        
        function updateQuantity(input) {
            const variationId = input.getAttribute("data-variation-id");
            const quantity = input.value;
            confirmEdit(variationId, quantity);
        }

        function confirmEdit(variationId, quantity) {
            // Redirect to the /edit-cart-qty route with the updated variationId and quantity
            window.location.href = "/edit-cart-qty?variationId=" + variationId + "&quantity=" + quantity;
        }


        function goBack() {
            // Handle the logic for going back to the previous page or route
            window.history.back();
        }

        function deleteProduct(variationId) {
            // Display a confirmation dialog to the user
            var confirmed = confirm('Are you sure you want to remove this product from the cart?');
            
            // Proceed with deletion if user confirmed
            if (confirmed) {
                // Send a DELETE request using AJAX
                fetch('/delete-cart-product/' + variationId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        // Redirect to the cart view or perform other actions as needed
                        window.location.href = '{{ route("export.cart.products") }}';
                    } else {
                        // Handle the case where the deletion fails
                        // Display an error message or perform other actions as needed
                        console.error('Failed to delete cart product.');
                    }
                })
                .catch(error => {
                    // Handle any network errors
                    console.error(error);
                });
            }
        }




        function updateSubtotal(input, variationPrice, productId) {
        // Get the updated quantity from the input element
        var quantity = input.value;

        // Calculate the new subtotal based on the variation price and quantity
        var subtotal = variationPrice * quantity;

        // Update the subtotal in the table
        var rowId = input.parentNode.parentNode.rowIndex;
        document.getElementById('subtotal-' + (rowId - 1)).textContent = subtotal;

        // Update the total amount by summing up all the subtotals
        var subtotals = document.querySelectorAll('td[id^="subtotal-"]');
        var totalAmount = 0;
        subtotals.forEach(function(subtotal) {
            totalAmount += parseFloat(subtotal.textContent);
        });
        document.getElementById('totalAmount').textContent = totalAmount;
    }


    </script>

@endsection