@extends('template')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Change Password Page</h1>
    </section>

    <section class="content">
    	<div class="row">
    		<div class="col-md-12">
    			@include('alert')
    		</div>
    	</div>
    	<div class="row">
    		<div class="col-md-4">
    			{!! Form::model($user,['method'=>'PATCH','action'=>['UserController@update_changepassword',$user->id]]) !!}
		        <div class="box">
		            <div class="box-header with-border">
		                <h3 class="box-title">Change Password</h3>
		            </div>
		            <div class="box-body">
		            	<div class="row">
		            		<div class="col-md-12">
		            			<div class="form-group">
				                	{!! Form::label('Enter Old Password') !!}
                                    {!! Form::password('oldpassword',['class'=>'form-control']) !!}
				                </div>
		            		</div>	
		            	</div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('Enter New Password') !!}
                                    {!! Form::password('newpassword',['class'=>'form-control']) !!}
                                </div>
                            </div>  
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('Confirm New Password') !!}
                                    {!! Form::password('confirmpassword',['class'=>'form-control']) !!}
                                </div>
                            </div>  
                        </div>
		            </div>
		            <div class="box-footer">
                    	<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Entry</button>
                        <a href="{{ action('CustomerController@index') }}" class="btn btn-success"><i class="fa fa-home"></i> Back to Index</a>
                  	</div>
		        </div>
		        {!! Form::close() !!}
    		</div>
    	</div>
    </section>
</div>
@endsection