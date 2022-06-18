@extends('template')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Customers Page - {{ $customer->name }}</h1>
    </section>

    <section class="content">
    	<div class="row">
    		<div class="col-md-12">
    			@include('alert')
    		</div>
    	</div>
    	<div class="row">
    		<div class="col-md-12">
                <a href="{{ action('CustomerController@index') }}" class="btn btn-success btn-sm" style="margin-bottom: 10px;"><i class="glyphicon glyphicon-home"></i> Back to Customer Menu</a>
		        <div class="box">
		            <div class="box-header with-border">
		                <h3 class="box-title">View Accounts</h3>
		            </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table id="myTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Total Weights</th>
                                            <th class="text-center">Total Amount</th>
                                            <th class="text-center">Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($lists as $data)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($data->booking->date)->toFormattedDateString() }}</td>
                                            <td>{{ number_format($data->total_weights,2) }}</td>
                                            <td>{{ number_format($data->total_price,2) }}</td>
                                            <td>{{ number_format($data->total_price - $data->paymentMade,2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
		        </div>
    		</div>
    	</div>
    </section>
</div>
@endsection