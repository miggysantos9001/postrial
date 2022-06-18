@extends('template')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Customer Payment Page</h1>
    </section>

    <section class="content">
    	<div class="row">
    		<div class="col-md-12">
    			@include('alert')
    		</div>
    	</div>
    	<div class="row">
    		<div class="col-md-12">
    			{!! Form::open(['method'=>'POST','action'=>'PaymentController@store']) !!}
		        <div class="box">
		            <div class="box-header with-border">
		                <h3 class="box-title">Search Customer</h3>
		            </div>
		            <div class="box-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    {!! Form::label('Select Customer') !!}
                                    {!! Form::select('customer_id',$customers,null,['class'=>'form-control select2','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                                </div>
                            </div> 
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" style="margin-top:25px;"><i class="fa fa-search"></i> Search Bookings</button>
                                </div>
                            </div> 
                        </div>
		            </div>
		        </div>
		        {!! Form::close() !!}
    		</div>
    	</div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Payment List</h3>
                    </div>
                    <div class="box-body">
                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Customer</th>
                                    <th class="text-center">OR #</th>
                                    <th class="text-center">Payment Mode</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-center">Tax</th>
                                    <th class="text-center" width="80">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $data)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($data->date)->toFormattedDateString() }}</td>
                                    <td>{{ $data->customer->name }}</td>
                                    <td>{{ $data->or_number }}</td>
                                    <td>{{ $data->payment_mode }}</td>
                                    <td>{{ number_format($data->amount,2) }}</td>
                                    <td>{{ number_format($data->tax,2) }}</td>
                                    <td class="text-center">
                                        <a href="{{ action('PaymentController@show',$data->id) }}" class="btn btn-success btn-sm"><i class="fa fa-edit"></i></a>
                                        <a href="{{ action('PaymentController@delete_payments',$data->id) }}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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