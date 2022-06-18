@extends('template')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Order From Supplier</h1>
    </section>

    <section class="content">
    	<div class="row">
    		<div class="col-md-12">
    			@include('alert')
    		</div>
    	</div>
    	<div class="row">
    		<div class="col-md-12">
                <a href="{{ action('SVIBookingController@create') }}" class="btn btn-success btn-sm" style="margin-bottom: 10px;"><i class="glyphicon glyphicon-plus"></i>Create Order</a>
    			<div class="box">
    			    <div class="box-header with-border">
    			        <h3 class="box-title">Order List</h3>
    			    </div>
    			    <div class="box-body">
                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Client</th>
                                    <th class="text-center">Terms</th>
                                    <th class="text-center">Total Heads</th>
                                    <th class="text-center">Total Weight</th>
                                    <th class="text-center">Total Amount</th>
                                    <th class="text-center">Cash Bond</th>
                                    <th class="text-center" width="80">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $data)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($data->date)->toFormattedDateString() }}</td>
                                    <td>{{ $data->client->name }}</td>
                                    <td>{{ $data->term->name }}</td>
                                    <td>{{ number_format($data->details->sum('heads'),2) }}</td>
                                    <td>{{ number_format($data->details->sum('weight'),2) }}</td>
                                    <td>{{ number_format($data->details->sum('total_price'),2) }}</td>
                                    <td>{{ number_format($data->cashbond,2) }}</td>
                                    <td class="text-center">
                                        <a href="{{ action('SVIBookingController@edit',$data->id) }}" class="btn btn-success btn-sm"><i class="fa fa-edit"></i></a>
                                        <a href="{{ action('OrderController@delete',$data->id) }}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
    			    </div>
    			</div>
    		</div>
    	</div>
    	
    </section>
</div>
@endsection