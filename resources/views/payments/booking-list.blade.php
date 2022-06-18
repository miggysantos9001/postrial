@extends('template')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Customer Bookings Page</h1>
        <h3 style="color:blue;font-weight: bolder;">{{ $customer->name }}</h3>
    </section>

    <section class="content">
    	<div class="row">
    		<div class="col-md-12">
    			@include('alert')
    		</div>
    	</div>
        {!! Form::open(['method'=>'POST','action'=>['PaymentController@store_payments',$customer->id]]) !!}
    	<div class="row">
    		<div class="col-md-12">
    			<div class="box">
    			    <div class="box-header with-border">
    			        <h3 class="box-title">Booking List</h3>
    			    </div>
    			    <div class="box-body">
                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center"><i class="fa fa-check"></i></th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Total Weight</th>
                                    <th class="text-center">Total Balance</th>
                                    <!-- <th class="text-center" width="100">Action</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customer->lists as $data)
                                <tr>
                                    <td class="text-center">
                                        @if($data->paymentMade == $data->total_price)
                                        PAID
                                        @else
                                        <input type="checkbox" name="list_id[]" value="{{ $data->id }}" class="case"/>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($data->booking->date)->toFormattedDateString() }} {{ $data->booking_id }}</td>
                                    <td>{{ number_format($data->total_weights,2) }}</td>
                                    <td>
                                        {{ number_format($data->total_price - $data->paymentMade,2) }}
                                        {!! Form::hidden('total_price[]',$data->total_price - $data->paymentMade,['class'=>'form-control']) !!}
                                    </td>
                                    <!-- <td class="text-center">
                                        <a href="#edit{{ $data->id }}" class="btn btn-sm btn-success" data-toggle="modal"><i class="fa fa-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                    </td> -->
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
    			    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    {!! Form::label('Date') !!}
                                    {!! Form::text('date',\Carbon\Carbon::now()->toDateString(),['class'=>'form-control dp']) !!}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    {!! Form::label('OR Number') !!}
                                    {!! Form::text('or_number',null,['class'=>'form-control']) !!}
                                    {!! Form::hidden('customer_id',$customer->id,['class'=>'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('','Select Payment Mode') !!}
                                    {!! Form::select('payment_mode',['CASH'=>'CASH','CHECK'=>'CHECK'],null,['class'=>'form-control select2','placeholder'=>'PLEASE SELECT']) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('Amount') !!}
                                    {!! Form::text('amount','0.00',['class'=>'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="checkbox" style="margin-top: 25px;">
                                        <label>
                                            <input type="checkbox" name="withTax" value="1"> With Tax?
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('Remarks') !!}
                                    {!! Form::textarea('remarks',null,['class'=>'form-control']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Entry</button>
                                </div>
                            </div>
                        </div>
                    </div>
    			</div>
    		</div>
    	</div>
        {!! Form::close() !!}
    </section>
</div>
@endsection