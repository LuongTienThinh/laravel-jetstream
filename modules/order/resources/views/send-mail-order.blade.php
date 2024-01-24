<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Email Title</title>
    <style>
        /* Reset some default styles for email compatibility */
        body, table, td, a {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        /* Add some spacing and style */
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            background-color: #fff;
            margin: 20px 0;
            overflow: hidden;
        }

        .table-title {
            font-weight: 800;
            text-align: center;
            padding: 0 1.25rem;
            font-size: 1.875rem;
            margin-bottom: 0.25rem
        }

        th, td {
            padding: 10px 0;
            border: 1px solid #e0e0e0;
            text-align: center;
        }

        .td-body {
            text-align: left;
            padding: 10px 40px;
        }

        .total-price {
            font-weight: 800;
            text-align: center;
            border: 1px solid #e0e0e0;
        }

        h1, h2, h3 {
            color: #333;
        }

        a {
            color: #3498db;
            text-decoration: none;
        }

        /* Media query for responsive design */
        @media only screen and (max-width: 600px) {
            table {
                width: 100%;
            }

            td {
                display: block;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <table>
        <tbody>
            <tr>
                <td class="td-body">
                    <h1>Laravel - Payment Invoice</h1>
                    <p>Hello {{ $user->name }},</p>
                    <p>Receive address:
                        {{
                            $user->number_address . ', ' .
                            explode('#', $user->ward)[0] . ', ' .
                            explode('#', $user->district)[0] . ', ' .
                            explode('#', $user->province)[0]
                        }}
                    </p>
                    <p>You have just made a payment for an order, the order details are below.</p>
                    <p class="table-title">Payment Invoice</p>
                    <div>
                        <table style="width:80%; margin:20px auto">
                            <tr>
                                <th>No</th>
                                <th>Product name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                            </tr>
                            <tbody>
                                @php $total = 0 @endphp
                                @foreach($orderData->orderItem as $item)
                                    @php $total += $item->total_price @endphp
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->total_price }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tr>
                                <td class="total-price" colspan="4">Total price: {{ $total }}</td>
                            </tr>
                        </table>
                    </div>
                    <p>Please check your invoice. If there are any mistakes, contact us via email at ltthinh2001120422@gmail.com.</p>
                    <p>Thank you!</p>
                </td>
            </tr>
            </tbody>
    </table>
</body>
</html>
