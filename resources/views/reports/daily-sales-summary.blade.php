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
    			{!! Form::open(['method'=>'POST','action'=>'ReportController@post_daily_sales_summary']) !!}
		        <div class="box">
		            <div class="box-header with-border">
		                <h3 class="box-title">Daily Sales Summary Report</h3>
		            </div>
		            <div class="box-body">
		            	<div class="row">
		            		<div class="col-md-6">
		            			<div class="form-group">
				                	{!! Form::label('Select Date From') !!}
				                	{!! Form::text('date_from',\Carbon\Carbon::now()->toDateString(),['class'=>'form-control dp']) !!}
				                </div>
		            		</div>	
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('Select Date To') !!}
                                    {!! Form::text('date_to',\Carbon\Carbon::now()->toDateString(),['class'=>'form-control dp']) !!}
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