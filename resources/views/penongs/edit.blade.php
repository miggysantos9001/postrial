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
        {!! Form::model($booking,['method'=>'PATCH','action'=>['PenongsController@update',$booking->id]]) !!}
    	<div class="row">
    		<div class="col-md-12">
                <a href="{{ action('PenongsController@index') }}" class="btn btn-success btn-sm" style="margin-bottom: 10px;"><i class="glyphicon glyphicon-home"></i> Back to Booking Menu</a>
		        <div class="box">
		            <div class="box-header with-border">
		                <h3 class="box-title">Update Booking</h3>
		            </div>
		            <div class="box-body">
		            	<div class="row">
		            		<div class="col-md-4">
		            			<div class="form-group">
				                	{!! Form::label('Enter Date') !!}
				                	{!! Form::text('date',null,['class'=>'form-control dp']) !!}
				                </div>
		            		</div>	
		            	</div>
                        <hr>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table" style="font-size: 10px;">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="80">Product</th>
                                            <th class="text-center" width="80">Total</th>
                                            @foreach($customers as $row)
                                            <th class="text-center" width="100">{{ $row->code }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($products as $data)
                                        <?php 
                                            $count = \App\Order_detail::where('date',$booking->date)
                                                ->where('product_id',$data->id)
                                                ->sum('heads');

                                            $count2 = \App\Order_detail::where('date',$booking->date)
                                                ->where('product_id',$data->id)
                                                ->sum('weight');
                                        ?>
                                        <tr>
                                            <td>{{ $data->name }}</td>
                                            <td style="font-size:14px;font-weight: bolder;margin-top: -5px;" class="text-center">{{ ($data->bags == 1) ? number_format($count2,0) : number_format($count,0) }}</td>
                                            @foreach($customers as $row)
                                            <?php 
                                                $headCount = \App\Booking_detail::where('product_id',$data->id)
                                                    ->where('customer_id',$row->id)
                                                    ->where('date',$booking->date)
                                                    ->first();
                                            ?>
                                            @if($headCount->heads == 0)
                                            <td width="100">{{ Form::text("qty_{$row->id}_{$data->id}_{$headCount->id}",number_format($headCount->weight,0),['class'=>'form-control','style'=>'font-size:10px;'])}}</td>
                                            @else
                                            <td width="100">{{ Form::text("qty_{$row->id}_{$data->id}_{$headCount->id}",$headCount->heads,['class'=>'form-control','style'=>'font-size:10px;'])}}</td>
                                            @endif
                                            @endforeach
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
		            </div>
                    <div class="box-footer">
                        
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Entry</button>
                    </div>
		        </div>
    		</div>
    	</div>
    	{!! Form::close() !!}
    </section>
</div>
@endsection
@section('js')
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function(){
        

        $('#wrap').on('click','.minus',function(){
            var $a = $(this).parent('div').parent('div').parent('div').attr('class');
            $('.'+$a).remove();
            
            var sum = 0;
            var num = [];
            $('.amount').each(function(){
                var total = $(this).val();
                num.push(total)
            });
            $.each(num,function(){
                sum += parseFloat(this);
            });

            var sum2 = 0;
            var num2 = [];
            $('.weight').each(function(){
                var total2 = $(this).val();
                num2.push(total2)
            });
            $.each(num2,function(){
                sum2 += parseFloat(this);
            });

            //$('#total').val(sum);
            $('#total').val(sum.toFixed(2));
            $('#totweight').val(sum2.toFixed(2));
        });
    });

    $(document).ready(function(){
        var wrapper = '#wrap';
        $(wrapper).on('keyup','.unit_price',function(){

            var a = $(this).parent('div').parent('div').parent('div').attr('class');
            var heads =$("."+a+" .weight").val();

            console.log($(this).parent('div').parent('div').parent('div').attr('class'));
            var total1 = heads * $(this).val();
            $("."+a+' .amount').val(total1.toFixed(2));

            var sum = 0;
            var num = [];
            $('.amount').each(function(){
                var total = $(this).val();
                num.push(total)
            });
            $.each(num,function(){
                sum += parseFloat(this);
            });

            var sum2 = 0;
            var num2 = [];
            $('.weight').each(function(){
                var total2 = $(this).val();
                num2.push(total2)
            });
            $.each(num2,function(){
                sum2 += parseFloat(this);
            });

            $('#total').val(sum.toFixed(2));
            $('#totweight').val(sum2.toFixed(2));
        });
    });

</script>
@endsection