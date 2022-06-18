@extends('template')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Customers Page - {{ $customer->name }}</h1>
    </section>

    <section class="content">
    	<div class="row">
    		<div class="col-md-12">
    			@include('alert')
    		</div>
    	</div>
    	<div class="row">
    		<div class="col-md-12">
                <a href="{{ action('CustomerController@index') }}" class="btn btn-success btn-sm" style="margin-bottom: 10px;"><i class="glyphicon glyphicon-home"></i> Back to Customer Menu</a>
    			{!! Form::open(['method'=>'POST','action'=>['CustomerController@post_pricing',$customer->id]]) !!}
		        <div class="box">
		            <div class="box-header with-border">
		                <h3 class="box-title">Create Customer Pricing</h3>
		            </div>
		            <div class="box-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    {!! Form::label('Product Type') !!}
                                </div>
                            </div>  
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('Pricing') !!}
                                </div>
                            </div>
                            @foreach($products as $data)
                            <div class="col-md-8">
                                <div class="form-group">
                                    {!! Form::text('',$data->name,['class'=>'form-control','readonly']) !!}
                                    {!! Form::hidden('product_id[]',$data->id,['class'=>'form-control','readonly']) !!}
                                </div>
                            </div>  
                            <div class="col-md-4">
                                <div class="form-group">
                                    @if($customer->pricings->count() == 0)
                                    {!! Form::text('pricing[]','0.00',['class'=>'form-control']) !!}
                                    @else
                                    <?php 
                                        $defPrice = \App\Customer_pricing::where('customer_id',$customer->id)
                                            ->where('product_id',$data->id)
                                            ->first();
                                    ?>
                                    {!! Form::text('pricing[]',$defPrice->pricing,['class'=>'form-control']) !!}
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
		            <div class="box-footer">
                    	<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Entry</button>
                  	</div>
		        </div>
		        {!! Form::close() !!}
    		</div>
    	</div>
    </section>
</div>
@endsection