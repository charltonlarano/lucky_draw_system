@extends('layouts.app')
@section('content')

    <div class="container">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ Session::get('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ Session::get('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Lucky Draw System</div>

                            <div class="card-body">

                                <div class="row justify-content-center">
                                    <div class="col-md-10">
                                        {!! Form::open(['route' => 'admin.lucky_winners.draw','method'=>'POST']) !!}
                                        <div class="row justify-content-center">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    {{Form::label('prize', 'Prize Types', ['class' => ''])}}
                                                    {{Form::select('prize',$prizes,null, ['class' => 'form-control','required'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    {{Form::label('generate_randomly', 'Generate Randomly', ['class' => ''])}}
                                                    {{Form::select('generate_randomly',$generate_randomly,null, ['id'=>'generate_randomly','class' => 'form-control','required'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    {{Form::label('winning_number', 'Winning Number', ['class' => ''])}}
                                                    {{Form::text('winning_number',null, ['id'=>'winning_number_draw','class' => 'number-input form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    {{ Form::submit('Draw',['class'=>'btn btn-block btn-success '])}}
                                                </div>
                                            </div>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">Users</div>
                            <div class="card-body">
                                <div class="row justify-content-center">
                                    <div class="col-md-12">
                                        {!! Form::open(['route' => 'admin.generate_users','id'=>'gen_users_form','method'=>'POST']) !!}
                                        <div id="generate_users_row" class="row justify-content-center">
                                            <div id="winning_number_container_orig" class="col-md-12 winning-number-container">
                                                <div class="form-group">
                                                    {{Form::label('winning_number', 'Winning Number', ['class' => ''])}}
                                                    <div class="input-group ">
                                                        {{Form::text('winning_number[]',null, ['class' => 'form-control number-input winning-number','maxlength'=>6,'required'=>true])}}
                                                        <div class="input-group-append">
                                                            <span id="add_winning_number" class="input-group-text">+</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="generate_btn_container" class="col-md-8 generate-btn-container">
                                                <div class="form-group">
                                                    <button id="generate_user" type="button" class="btn btn-block btn-success ">Generate User</button>
                                                </div>
                                            </div>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="padding: 10px 0px;">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">Lucky Draw Winners</div>
                            <div class="card-body">
                                <div class="row justify-content-center">
                                    <div class="col-md-12">
                                        <table class="table table-bordered">
                                            <thead>
                                            <th>Prize</th>
                                            <th>Lucky Draw Winner</th>
                                            <th>Draw Method</th>
                                            <th>Date & Time drawn</th>
                                            </thead>
                                            <tbody>
                                            @foreach($lucky_draw_winners as $lucky_draw_winner)
                                                <tr>
                                                    <td>{{$lucky_draw_winner->prize_name}}</td>
                                                    <td>{{$lucky_draw_winner->user_name}}</td>
                                                    <td>{{$lucky_draw_winner->draw_method}}</td>
                                                    <td>{{($lucky_draw_winner->date_time_drawn != null?\Carbon\Carbon::parse($lucky_draw_winner->date_time_drawn)->format('M j, Y h:i:s a'):'') }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="padding: 10px 0px;">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">Lucky Draw Users</div>
                            <div class="card-body">
                                <div class="row justify-content-center">
                                    <div class="col-md-12">
                                        <table class="table table-bordered">
                                            <thead>
                                            <th>Name</th>
                                            <th>Winning Numbers</th>
                                            </thead>
                                            <tbody>
                                            @foreach($members as $member)
                                                <tr>
                                                    <td>{{$member->name}}</td>

                                                    <td>
                                                        @foreach($member->winning_numbers as $key => $winning_number)
                                                            <ul>
                                                                <li>{{$key+1 .'. '.$winning_number->winning_number}}</li>
                                                            </ul>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('styles')
    <style>
        #add_winning_number{
            cursor: pointer;
            background: #32CD32;
            color: #fff;
        }
        #add_winning_number:hover{
            background: #32cd59;
            color: #fff;
        }
        .remove-winning-number{
            cursor: pointer;
            background: #ff0000;
            color: #fff;
        }
        .remove-winning-number:hover{
            background: #e50000;
            color: #fff;
        }
        ul {
            list-style-type: none;
        }
    </style>
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $(document).ready(function(){
            numberInputChecker();
        });
        function numberInputChecker(){
            $(".number-input").keypress(function (e) {
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
            });
        }
        $(document).on('change','#generate_randomly',function(){
            if($(this).val() == 1){
                $('#winning_number_draw').prop('required',false);
            } else{
                $('#winning_number_draw').prop('required',true);
            }
        });
        $(document).on('click','#add_winning_number',function(){
            var orig_container = $('#winning_number_container_orig').clone();
            orig_container.find('.winning-number').val('');
            orig_container.insertBefore('#generate_btn_container').find('#add_winning_number').attr('id','').addClass('remove-winning-number').html('-');
            numberInputChecker();
        })
        $(document).on('click','.remove-winning-number',function(){
            $(this).closest('.winning-number-container').remove();
        });
        $(document).on('click','#generate_user',function(){
            const $inputs = $('.winning-number');
            const uniques = new Set($inputs.map((i, el) => el.value).get());

            if (uniques.size < $inputs.length) {
                swal({
                    title: "Oops",
                    text: "Numbers Must Be Unique",
                    icon: "error",
                });
            }else{
                $('#gen_users_form').submit();
            }
        });

    </script>
@endsection
