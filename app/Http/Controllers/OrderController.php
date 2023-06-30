<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use SimpleXMLElement;
use XSLTProcessor;
use DOMDocument;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::where('status','0')->get();
        return view('order.index',compact('orders'));
    }

    public function view($id)
    {
        $orders = Order::where('id', $id)->first();
        return view('order.view', compact('orders'));
    }
    
    public function update(Request $request, $id)
    {
        $orders = Order::find($id);
        $orders->status = $request->input('order_status');
        $orders->update();
        return redirect('orders')->with('status', "Order Updated Succesfully");
    }

    public function orderHistory()
    {
        $orders = Order::where('status','1')->get();
        return view('order.history',compact('orders'));
    }

    public function generateReport()
    {
        // Step 1: Query the database and fetch completed orders with their order items
        $completedOrders = Order::with(['orderItems', 'orderItems.products', 'orderItems.variation'])
            ->where('status', '1')
            ->get();

        $xmlFilePath = resource_path('views/order/report.xml');

        // Create the root <report> element
        $xmlData = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><report></report>');

        // Iterate over completed orders and add them to the XML structure
        foreach ($completedOrders as $order) {
            $orderNode = $xmlData->addChild('order');
            $orderNode->addChild('id', $order->id);
            $orderNode->addChild('price', $order->total_price);
            $orderNode->addChild('date', $order->created_at);

            foreach ($order->orderItems as $orderItem) {
                $orderItemNode = $orderNode->addChild('orderItem');
                $orderItemNode->addChild('productName', $orderItem->products->productName);
                $orderItemNode->addChild('productDesc', $orderItem->products->productDesc);
                $orderItemNode->addChild('productColor', $orderItem->variation->productColor);
                $orderItemNode->addChild('productStorage', $orderItem->variation->productStorage);
                $orderItemNode->addChild('productPrice', $orderItem->variation->productPrice);
                $orderItemNode->addChild('quantity', $orderItem->quantity);
            }
        }

        // Save the XML to the file
        $xmlData->asXML($xmlFilePath);

        // Step 3: Load and apply the XSLT transformation
        $xsltStylesheet = resource_path('views/order/report_template.xslt'); // Path to your XSLT stylesheet file

        $dom = new DOMDocument();
        $dom->load($xsltStylesheet);

        $xslt = new XSLTProcessor();
        $xslt->importStylesheet($dom);

        $transformedOutput = $xslt->transformToXML($xmlData);

        // Step 5: Pass the transformed output to the view
        return view('order.report')->with('report', $transformedOutput);
    }
}
