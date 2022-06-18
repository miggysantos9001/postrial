@extends('template')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Customers Page</h1>
    </section>

    <section class="content">
    	<div class="row">
    		<div class="col-md-12">
    			@include('alert')
    		</div>
    	</div>
    	<div class="row">
    		<div class="col-md-4">
    			{!! Form::open(['method'=>'POST','action'=>'CustomerController@store']) !!}
		        <div class="box">
		            <div class="box-header with-border">
		                <h3 class="box-title">Create Customer</h3>
		            </div>
		            <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('Select Group') !!}
                                    {!! Form::select('group_id',$groups,null,['class'=>'form-control select2','placeholder'=>'PLEASE SELECT']) !!}
                                </div>
                            </div>  
                        </div>
		            	<div class="row">
		            		<div class="col-md-12">
		            			<div class="form-group">
				                	{!! Form::label('Enter Name') !!}
				                	{!! Form::text('name',null,['class'=>'form-control']) !!}
				                </div>
		            		</div>	
		            	</div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('Enter Code') !!}
                                    {!! Form::text('code',null,['class'=>'form-control']) !!}
                                </div>
                            </div>  
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('Balance Forwarded') !!}
                                    {!! Form::text('balance','0.00',['class'=>'form-control']) !!}
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
    			        <h3 class="box-title">Customer List</h3>
    			    </div>
    			    <div class="box-body">
                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Group</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Code</th>
                                    <th class="text-center">Balance Forwarded</th>
                                    <th class="text-center" width="50">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customers as $data)
                                <tr>
                                    <td>{{ ($data->group != NULL) ? $data->group->name : 'INDIVIDUAL' }}</td>
                                    <td>{{ $data->name }}</td>
                                    <td>{{ $data->code }}</td>
                                    <td class="text-right">{{ number_format($data->balance,2) }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li><a href="#edit{{ $data->id }}" data-toggle="modal">Edit</a></li>
                                                <li><a href="{{ action('CustomerController@view_account',$data->id) }}">View Account</a></li>
                                                <li><a href="{{ action('CustomerController@view_pricing',$data->id) }}">View Pricing</a></li>
                                            </ul>
                                        </div>
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
                                            {!! Form::open(['method'=>'PATCH','action'=>['CustomerController@update',$data->id]]) !!}
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            {!! Form::label('Select Group') !!}
                                                            {!! Form::select('group_id',$groups,$data->group_id,['class'=>'form-control select2','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                                                        </div>
                                                    </div>  
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            {!! Form::label('Enter Name') !!}
                                                            {!! Form::text('name',$data->name,['class'=>'form-control']) !!}
                                                        </div>
                                                    </div>  
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            {!! Form::label('Enter Code') !!}
                                                            {!! Form::text('code',null,['class'=>'form-control']) !!}
                                                        </div>
                                                    </div>  
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            {!! Form::label('Balance Forwarded') !!}
                                                            {!! Form::text('balance',$data->balance,['class'=>'form-control']) !!}
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