@extends('template')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Expense Type Page</h1>
    </section>

    <section class="content">
    	<div class="row">
    		<div class="col-md-12">
    			@include('alert')
    		</div>
    	</div>
    	<div class="row">
    		<div class="col-md-4">
    			{!! Form::open(['method'=>'POST','action'=>'ExpensetypeController@store']) !!}
		        <div class="box">
		            <div class="box-header with-border">
		                <h3 class="box-title">Create Expense Type</h3>
		            </div>
		            <div class="box-body">
		            	<div class="row">
		            		<div class="col-md-12">
		            			<div class="form-group">
				                	{!! Form::label('Enter Name') !!}
				                	{!! Form::text('name',null,['class'=>'form-control']) !!}
				                </div>
		            		</div>	
		            	</div>
		            </div>
		            <div class="box-footer">
                    	<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Entry</button>
                  	</div>
		        </div>
		        {!! Form::close() !!}
    		</div>
    		<div class="col-md-8">
    			<div class="box">
    			    <div class="box-header with-border">
    			        <h3 class="box-title">Expense Type List</h3>
    			    </div>
    			    <div class="box-body">
    			    	<table id="myTable" class="table table-bordered table-striped">
    			    		<thead>
    			    			<tr>
    			    				<th class="text-center">Name</th>
    			    				<th class="text-center" width="50">Action</th>
    			    			</tr>
    			    		</thead>
    			    		<tbody>
    			    			@foreach($expensetypes as $data)
    			    			<tr>
    			    				<td>{{ $data->name }}</td>
    			    				<td class="text-center">
    			    					<a href="#edit{{ $data->id }}" class="btn btn-success btn-sm" data-toggle="modal"><i class="fa fa-edit"></i></a>
    			    				</td>
    			    			</tr>
    			    			<div class="modal fade" id="edit{{ $data->id }}">
		    			          	<div class="modal-dialog">
		    			            	<div class="modal-content">
		    			              		<div class="modal-header">
		    			                		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		    			                  		<span aria-hidden="true">&times;</span></button>
		    			                		<h4 class="modal-title">Edit {{ $data->name }}</h4>
		    			              		</div>
		    			              		{!! Form::open(['method'=>'PATCH','action'=>['ExpensetypeController@update',$data->id]]) !!}
		    			              		<div class="modal-body">
                				            	<div class="row">
                				            		<div class="col-md-12">
                				            			<div class="form-group">
                						                	{!! Form::label('Enter Name') !!}
                						                	{!! Form::text('name',$data->name,['class'=>'form-control']) !!}
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
    			    		</tbody>
    			    	</table>
    			    </div>
    			</div>
    		</div>
    	</div>
    	
    </section>
</div>
@endsection