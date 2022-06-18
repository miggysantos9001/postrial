@extends('template')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Customer Payment Page</h1>
        <h3 style="color:blue;font-weight: bolder;">{{ $payment->customer->name }}</h3>
    </section>

    <section class="content">
    	<div class="row">
    		<div class="col-md-12">
    			@include('alert')
    		</div>
    	</div>
        {!! Form::model($payment,['method'=>'PATCH','action'=>['PaymentController@update',$payment->id]]) !!}
    	<div class="row">
    		<div class="col-md-12">
    			<div class="box">
    			    <div class="box-header with-border">
    			        <h3 class="box-title">Payment Details</h3>
    			    </div>
    			    <div class="box-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    {!! Form::label('Date') !!}
                                    {!! Form::text('date',null,['class'=>'form-control dp']) !!}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    {!! Form::label('OR Number') !!}
                                    {!! Form::text('or_number',null,['class'=>'form-control']) !!}
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
                                    {!! Form::text('amount',null,['class'=>'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="checkbox" style="margin-top: 25px;">
                                        <label>
                                            @if($payment->tax > 0)
                                            <input type="checkbox" name="withTax" value="1" checked="checked">
                                            @else
                                            <input type="checkbox" name="withTax" value="1">
                                            @endif
                                            With Tax?
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <p>Booking Details under Payment</p>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Booking Date</th>
                                            <th class="text-center">Total Weight</th>
                                            <th class="text-center">Total Price</th>
                                            <th class="text-center">Amount Paid</th>
                                            <th class="text-center" width="100">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($payment->lists as $row)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($row->booking->date)->toFormattedDateString() }}</td>
                                            <td>{{ number_format($row->total_weights,2) }}</td>
                                            <td>{{ number_format($row->total_price,2) }}</td>
                                            <td>{{ number_format($row->paymentMade,2) }}</td>
                                            <td>
                                                <a href="#edit{{ $row->id }}" class="btn btn-sm btn-success" data-toggle="modal"><i class="fa fa-edit"></i></a>
                                                <a href="#" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <hr> -->
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
@section('modal')
@foreach($payment->lists as $row)
<div class="modal fade" id="edit{{ $row->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            {!! Form::open(['method'=>'PATCH','action'=>['PaymentController@update_payments',$row->id]]) !!}
            <div class="modal-body">
                <div class="row">  
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('Amount') !!}
                            {!! Form::text('paymentMade',$row->paymentMade,['class'=>'form-control']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('Remarks') !!}
                            {!! Form::textarea('remarks',$row->remarks,['class'=>'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endforeach
@endsection