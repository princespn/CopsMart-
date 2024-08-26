<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - #{{$order->id}}</title>

    <style type="text/css">
        @page {
            margin: 0px;
        }
        body {
            margin: 0px;
        }
        * {
            font-family: Verdana, Arial, sans-serif;
        }
        a {
            color: #fff;
            text-decoration: none;
        }
        table {
            font-size: small;
        }
        tfoot tr td {
            font-weight: bold;
            font-size: small;
        }
        .invoice table {
            margin: 15px;
        }
        .invoice h3 {
            margin-left: 15px;
        }
        .information {
            background-color: #60A7A6;
            color: #FFF;
        }
        .information .logo {
            margin: 5px;
        }
        .information table {
            padding: 10px;
        }
        pre{
            font-size:16px;
        }
    </style>

</head>
<body>

<div class="information">
    <table width="100%">
        <tr>
            <td align="left" style="width: 40%;">
                <h2> {{ $order->user_name}}</h2>
                <pre>
{{$address->address}},
{{$address->landmark}}
<br />
Date: {{ date('d-m-Y', strtotime($order->created_at)) }}
</pre>


            </td>
            <td align="center">
                <img src="http://oceanebazaar.com/images/logo2.png" alt="Logo" width="128" class="logo"/>
            </td>
            <td align="right" style="width: 40%;">

                <h1>{{ $vendor->name}}</h1>
                <pre>

                {{$vendor->address}}
                Ph:  {{$vendor->contact_no}}
                <h5>Powered By: Ocean</h5>
                </pre>
            </td>
        </tr>

    </table>
</div>


<br/>

<div class="invoice">
    <h3>Invoice #{{$order->id}}</h3>
    <table width="100%">
        <thead>
        <tr>
            <th>Description</th>
            <th>Quantity</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        <?php $total = 0; ?>
        @foreach ($products as $product)
            <?php
                $total+= $product->price * $product->qty;
            ?>

        <tr>
            <td> {{ $product->name  }} - {{ $product->package  }} </td>
            <td>{{ $product->qty  }}</td>
            <td align="left"> {{ $product->price * $product->qty }} /-</td>
        </tr>
        @endforeach
        </tbody>

        <tfoot>
        <tr>
            <td colspan="1"></td>
            <td align="left">Discount</td>
            <td align="left" class="gray"> {{ $total - $order->amount +$order->delivery_charges }} /-</td>
        </tr>
        <tr>
            <td colspan="1"></td>
            <td align="left">Delivery Charges</td>
            <td align="left" class="gray">{{$order->delivery_charges }} /-</td>
        </tr>
        <tr>
            <td colspan="1"></td>
            <td align="left">Total</td>
            <td align="left" class="gray">{{ $order->amount }}</td>
        </tr>
        </tfoot>
    </table>
</div>

<div class="information" style="position: absolute; bottom: 0;">
    <table width="100%">
        <tr>
            <td align="left" style="width: 50%;">
                &copy; {{ date('Y') }} Ocean - All rights reserved.
            </td>
            <!-- <td align="right" style="width: 50%;">
                Company Slogan
            </td> -->
        </tr>

    </table>
</div>
</body>
</html>
