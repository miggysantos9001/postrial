@extends('template')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Penongs Bookings Page</h1>
    </section>

    <section class="content">
    	<div class="row">
    		<div class="col-md-12">
    			@include('alert')
    		</div>
    	</div>
        {!! Form::open(['method'=>'POST','action'=>'PenongsController@store']) !!}
    	<div class="row">
    		<div class="col-md-12">
                <a href="{{ action('PenongsController@index') }}" class="btn btn-success btn-sm" style="margin-bottom: 10px;"><i class="glyphicon glyphicon-home"></i> Back to Booking Menu</a>
		        <div class="box">
		            <div class="box-header with-border">
		                <h3 class="box-title">Create Booking</h3>
		            </div>
		            <div class="box-body">
		            	<div class="row">
		            		<div class="col-md-4">
		            			<div class="form-group">
				                	{!! Form::label('Date') !!}
				                	{!! Form::hidden('date',$date,['class'=>'form-control dp']) !!}
                                    <h2>{{ \Carbon\Carbon::parse($date)->toFormattedDateString() }}</h2>
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
                                            $count = \App\Order_detail::where('date',$date)
                                                ->where('product_id',$data->id)
                                                ->sum('heads');

                                            $count2 = \App\Order_detail::where('date',$date)
                                                ->where('product_id',$data->id)
                                                ->sum('weight');
                                        ?>
                                        <tr>
                                            <td>{{ $data->name }}</td>
                                            <td style="font-size:14px;font-weight: bolder;margin-top: -5px;" class="text-center">{{ ($data->bags == 1) ? number_format($count2,0) : number_format($count,0) }}</td>
                                            @foreach($customers as $row)
                                            <td width="100">{{ Form::text("qty_{$row->id}_{$data->id}",'0',['class'=>'form-control','style'=>'font-size:10px;'])}}</td>
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
        var x = 1;
        $('#bot').on('click',function(){
        x++;
        var data = '<div class="clearfix"></div><div class="wrapp'+x+'" style="margin-top:20px;"><div class="clearfix"></div>';
            data += '<div class="col-md-3"><div class="form-group">';
            data += '{!! Form::select("product_id[]",$products,null,["class"=>"form-control select2","placeholder"=>"PLEASE SELECT"]) !!}';
            data += '</div></div>';

            data += '<div class="col-md-2"><div class="form-group">';
            data += '<input type="text" name="heads[]" class="form-control heads" id="heads" value="1"/>';
            data += '</div></div>';

            data += '<div class="col-md-2"><div class="form-group">';
            data += '<input type="text" name="weight[]" class="form-control weight" id="weight" />';
            data += '</div></div>';

            data += '<div class="col-md-2"><div class="form-group">';
            data += '<input type="text" name="unit_price[]" class="form-control unit_price" id="unit_price" />';
            data += '</div></div>';

            data += '<div class="col-md-2"><div class="form-group">';
            data += '<input type="text" name="total_price[]" class="form-control amount" id="amount" />';
            data += '</div></div>';

            data += '<div class="col-md-1"><div class="form-group">';
            data += '<button type="button" class="btn btn-danger minus"><i class="fa fa-minus-circle" aria-hidden="true"></i></button>';
            data += '</div></div></div>';

            $('#wrap').append(data);
            $(".wrapp"+x+" .select2").select2();
            

        });

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