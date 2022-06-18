@extends('template')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Bookings Page</h1>
    </section>

    <section class="content">
    	<div class="row">
    		<div class="col-md-12">
    			@include('alert')
    		</div>
    	</div>
    	<div class="row">
    		<div class="col-md-12">
                <a href="{{ action('BookingController@index') }}" class="btn btn-success btn-sm" style="margin-bottom: 10px;"><i class="glyphicon glyphicon-home"></i> Back to Bookings</a>
                <table class="table table-bordered" id="myTable">
                    <thead>
                        <tr>
                            <th class="text-center">Customer Name</th>
                            <th class="text-center" width="100">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($booking->details->groupBy('customer_id') as $key => $value)
                        <tr>
                            <td>{{ $value->first()->customer->name }}</td>
                            <td class="text-center">
                                <a href="{{ url('bookings/view-booking-details/'.$booking->id.'/'.$value->first()->customer_id) }}" class="btn btn-primary btn-sm"><i class="fa fa-list"></i></a>
                                <a href="{{ url('bookings/delete-booking-details/'.$booking->id.'/'.$value->first()->customer_id) }}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>      
            </div>
    	</div>
    	{!! Form::close() !!}
    </section>
</div>
@endsection