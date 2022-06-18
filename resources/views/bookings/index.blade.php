@extends('template')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Booking Page</h1>
    </section>

    <section class="content">
    	<div class="row">
    		<div class="col-md-12">
    			@include('alert')
    		</div>
    	</div>
        {!! Form::open(['method'=>'POST','action'=>'BookingController@create_booking_form']) !!}
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('','Select Date') !!}
                    {!! Form::text('date',\Carbon\Carbon::now()->toDateString(),['class'=>'form-control dp']) !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('','Select Client') !!}
                    {!! Form::select('client_id',$clients,null,['class'=>'form-control select2','style'=>'width:100%;','placeholder'=>'PLEASE SELECT']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('Select Customer') !!}
                    {!! Form::select('customer_id[]',$customers,null,['class'=>'form-control select2','multiple','style'=>'width:100%;']) !!}
                </div>
            </div> 
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Create Booking</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
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
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Client</th>
                                    <th class="text-center" width="160">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $data)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($data->date)->toFormattedDateString() }}</td>
                                    <td>{{ $data->client->name }}</td>
                                    <td class="text-center">
                                        <a href="{{ action('BookingController@edit',$data->id) }}" class="btn btn-success btn-sm"><i class="fa fa-edit"></i></a>
                                        <a href="#additional{{ $data->id }}" data-toggle="modal" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></a>
                                        <a href="{{ action('BookingController@show',$data->id) }}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>
                                        <a href="{{ action('BookingController@delete',$data->id) }}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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
@section('modal')
@foreach($bookings as $data)
<div class="modal fade" id="additional{{ $data->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Customer to Booking Date {{ \Carbon\Carbon::parse($data->date)->toFormattedDateString() }}</h4>
            </div>
            {!! Form::open(['method'=>'POST','action'=>['BookingController@additional',$data->id]]) !!}
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('Select Customer') !!}
                            {!! Form::select('addcust[]',$customers,null,['class'=>'form-control select2','multiple','style'=>'width:100%;']) !!}
                        </div>
                    </div>  
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Create</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endforeach
@endsection