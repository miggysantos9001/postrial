@extends('template')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Lost Kilo Page</h1>
    </section>

    <section class="content">
    	<div class="row">
    		<div class="col-md-12">
    			@include('alert')
    		</div>
    	</div>
    	<div class="row">
    		<div class="col-md-12">
    			{!! Form::open(['method'=>'POST','action'=>'ReportController@post_lost_kilos']) !!}
		        <div class="box">
		            <div class="box-header with-border">
		                <h3 class="box-title">Lost Kilos</h3>
		            </div>
		            <div class="box-body">
		            	<div class="row">
		            		<div class="col-md-6">
		            			<div class="form-group">
				                	{!! Form::label('Select Customer') !!}
				                	{!! Form::select('customer_id',$customers,null,['class'=>'form-control select2','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
				                </div>
		            		</div>	
		            		<div class="col-md-6">
		            			<div class="form-group">
				                	{!! Form::label('Select Booking Date') !!}
				                	{!! Form::text('date',\Carbon\Carbon::now()->toDateString(),['class'=>'form-control dp']) !!}
				                </div>
		            		</div>
		            	</div>
		            </div>
		            <div class="box-footer">
                    	<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search Bookings</button>
                  	</div>
		        </div>
		        {!! Form::close() !!}
    		</div>
    	</div>
    </section>
</div>
@endsection