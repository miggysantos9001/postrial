@extends('template')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Reports Page</h1>
    </section>

    <section class="content">
    	<div class="row">
    		<div class="col-md-12">
    			@include('alert')
    		</div>
    	</div>
    	<div class="row">
    		<div class="col-md-12">
    			{!! Form::open(['method'=>'POST','action'=>'ReportController@post_customer_soa']) !!}
		        <div class="box">
		            <div class="box-header with-border">
		                <h3 class="box-title">SOA Report</h3>
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
				                	{!! Form::label('Select Status') !!}
				                	{!! Form::select('isPaid',['1'=>'PAID','0'=>'UNPAID'],null,['class'=>'form-control select2','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
				                </div>
		            		</div>
		            	</div>
		            </div>
		            <div class="box-footer">
                    	<button type="submit" class="btn btn-primary"><i class="fa fa-print"></i> Print Report</button>
                  	</div>
		        </div>
		        {!! Form::close() !!}
    		</div>
    	</div>
    </section>
</div>
@endsection