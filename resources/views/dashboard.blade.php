@extends('template')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Dashboard</h1>
    </section>
    <section class="content">
    	<div class="row">
    		<div class="col-md-12">
    			@include('alert')
    		</div>
    	</div>
    	<div class="row">
    		<div class="col-md-12">
    			<h1 class="text-center" style="margin-bottom: 10px;">Today is {{ \Carbon\Carbon::now()->toFormattedDateString() }}</h1>
    		</div>
    	</div>
	    <div class="row">
	        <div class="col-lg-4 col-xs-4">          
	          	<div class="small-box bg-aqua">
	            	<div class="inner">
	              		<h3>{{ number_format($cash,2) }}</h3>
	              		<p>Total Cash Sales</p>
	            	</div>
	          	</div>
	        </div>
	        <div class="col-lg-4 col-xs-4">          
	          	<div class="small-box bg-aqua">
	            	<div class="inner">
	              		<h3>{{ number_format($credit,2) }}</h3>
	              		<p>Total Credits</p>
	            	</div>
	          	</div>
	        </div>
	        <div class="col-lg-4 col-xs-4">          
	    	  	<div class="small-box bg-aqua">
	    	    	<div class="inner">
	    	      		<h3>{{ number_format($onhand,0) }}</h3>
	    	      		<p>Total Borrowed Gallons</p>
	    	    	</div>
	    	  	</div>
	    	</div>
	    </div>
	    <div class="row">
	    	<div class="col-lg-6 col-xs-6">          
	    	  	<div class="small-box bg-red">
	    	    	<div class="inner">
	    	      		<h3>{{ number_format($cp->sum('amount'),2) }}</h3>
	    	      		<p>Total Credit Payments</p>
	    	      		<a href="#credit" class="btn btn-default btn-sm" data-toggle="modal">Pay Now</a>
	    	    	</div>
	    	  	</div>
	    	</div>
	    	<div class="col-lg-6 col-xs-6">          
	    	  	<div class="small-box bg-red">
	    	    	<div class="inner">
	    	      		<h3>{{ number_format($rg->sum('qty'),0) }}</h3>
	    	      		<p>Total Returned Gallons</p>
	    	      		<a href="#return" class="btn btn-default btn-sm" data-toggle="modal">Return Now</a>
	    	    	</div>
	    	  	</div>
	    	</div>
	    </div>
	</section>
</div>
@endsection
@section('modal')
<div class="modal fade" id="credit">
  	<div class="modal-dialog">
    	<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          		<span aria-hidden="true">&times;</span></button>
        		<h4 class="modal-title">Credit Payment</h4>
      		</div>
      		{!! Form::open(['method'=>'POST','action'=>'DashboardController@post_credit_payment']) !!}
      		<div class="modal-body">
            	<div class="row">
            		<div class="col-md-12">
            			<div class="form-group">
		                	{!! Form::label('Enter Date') !!}
		                	{!! Form::text('date',\Carbon\Carbon::now()->toDateString(),['class'=>'form-control dp']) !!}
		                </div>
            		</div>	
            		<div class="col-md-12">
            			<div class="form-group">
		                	{!! Form::label('Select Customer') !!}
		                	{!! Form::select('customer_id',$customers,null,['class'=>'form-control select2','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
		                </div>
            		</div>
            		<div class="col-md-12">
            			<div class="form-group">
		                	{!! Form::label('Enter OR') !!}
		                	{!! Form::text('or_number',null,['class'=>'form-control']) !!}
		                </div>
            		</div>
            		<div class="col-md-12">
            			<div class="form-group">
		                	{!! Form::label('Enter Amount') !!}
		                	{!! Form::text('amount','0.00',['class'=>'form-control']) !!}
		                </div>
            		</div>
            	</div>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
        		<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
      		</div>
      		{!! Form::close() !!}
    	</div>
  	</div>
</div>
<div class="modal fade" id="return">
  	<div class="modal-dialog">
    	<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          		<span aria-hidden="true">&times;</span></button>
        		<h4 class="modal-title">Return Gallon</h4>
      		</div>
      		{!! Form::open(['method'=>'POST','action'=>'DashboardController@post_return_gallon']) !!}
      		<div class="modal-body">
            	<div class="row">
            		<div class="col-md-12">
            			<div class="form-group">
		                	{!! Form::label('Enter Date') !!}
		                	{!! Form::text('date',\Carbon\Carbon::now()->toDateString(),['class'=>'form-control dp']) !!}
		                </div>
            		</div>	
            		<div class="col-md-12">
            			<div class="form-group">
		                	{!! Form::label('Select Customer') !!}
		                	{!! Form::select('customer_id',$customers,null,['class'=>'form-control select2','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
		                </div>
            		</div>
            		<div class="col-md-12">
            			<div class="form-group">
		                	{!! Form::label('Select Gallon') !!}
		                	{!! Form::select('product_id',$p_borrows,null,['class'=>'form-control select2','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
		                </div>
            		</div>
            		<div class="col-md-12">
            			<div class="form-group">
		                	{!! Form::label('Enter Qty') !!}
		                	{!! Form::text('qty',1,['class'=>'form-control']) !!}
		                </div>
            		</div>
            	</div>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
        		<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
      		</div>
      		{!! Form::close() !!}
    	</div>
  	</div>
</div>
@endsection