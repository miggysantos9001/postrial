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
                <a href="{{ action('ReportController@view_lost_kilos') }}" class="btn btn-success btn-sm" style="margin-bottom: 10px;"><i class="glyphicon glyphicon-home"></i> Back to Lost Kilos Page</a>
		        <div class="box">
		            <div class="box-header with-border">
		                <h3 class="box-title">Booking List of {{ $custName->name }} on {{ \Carbon\Carbon::parse($date)->toFormattedDateString() }}</h3>
		            </div>
		            <div class="box-body">
	            		<table id="myTable" class="table table-bordered table-striped">
	            			<thead>
	            				<tr>
	            					<th class="text-center">Product</th>
	            					<th class="text-center">Weight / Kilos</th>
	            					<th class="text-center">Unit Price</th>
	            					<th class="text-center">Total Price</th>
	            					<th class="text-center" width="50">Action</th>
	            				</tr>
	            			</thead>
	            			<tbody>
	            				@foreach($list as $data)
	            				<tr>
	            					<td>{{ $data->product->name }}</td>
	            					<td>{{ $data->weight }}</td>
	            					<td>{{ $data->unit_price }}</td>
	            					<td>{{ $data->total_price }}</td>
	            					<td class="text-center">
	            						<a href="#edit{{ $data->id }}" class="btn btn-info btn-sm" data-toggle="modal"><i class="fa fa-edit"></i></a>
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
@foreach($list as $data)
{!! Form::open(['method'=>'POST','action'=>['ReportController@post_lost_kilos_values',$data->id]]) !!}
<div id="edit{{ $data->id }}" class="modal fade" role="dialog">
  	<div class="modal-dialog">
    	<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal">&times;</button>
        		<h4 class="modal-title">Update Kilo/Weight</h4>
      		</div>
      		<div class="modal-body">
        		<div class="row">
        			<div class="col-md-12">
        				<div class="form-group">
        					{!! Form::label('Actual Weight / Kilos Received') !!}
        					{!! Form::text('lostkilo','0.00',['class'=>'form-control']) !!}
        				</div>
        			</div>
        		</div>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        		<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update Entry</button>
      		</div>
    	</div>
  	</div>
</div>
{!! Form::close() !!}
@endforeach
@endsection