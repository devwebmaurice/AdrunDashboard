@extends('layouts.adrun-master-one')

@section('content')

<h3 class="page-title">@lang('global.ciblage.title')</h3>

    {!! Form::model($ciblage, ['method' => 'PUT', 'route' => ['admin.ciblage.update', $ciblage->id]]) !!}

        <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_create')
        </div>
        
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('name', 'Name*', ['class' => 'control-label']) !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('name'))
                        <p class="help-block">
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                </div>
            </div>
            
            <div class="row">
                <div class="col-xs-4 form-group">
                    {!! Form::label('id', 'ADRUN Intelligence ID', ['class' => 'control-label']) !!}
                    {!! Form::text('id', old('id'), ['class' => 'form-control', 'placeholder' => '', 'readonly']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('id'))
                        <p class="help-block">
                            {{ $errors->first('id') }}
                        </p>
                    @endif
                </div>
                
                <div class="col-xs-4 form-group">
                    {!! Form::label('id_adtech', 'ADTECH ID', ['class' => 'control-label']) !!}
                    {!! Form::text('id_adtech', '', ['class' => 'form-control', 'placeholder' => '', 'readonly']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('id_adtech'))
                        <p class="help-block">
                            {{ $errors->first('id_adtech') }}
                        </p>
                    @endif
                </div>
                
                <div class="col-xs-4 form-group">
                    {!! Form::label('id_sage', 'SAGE ID', ['class' => 'control-label']) !!}
                    {!! Form::text('id_sage', '', ['class' => 'form-control', 'placeholder' => '', 'readonly']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('id_sage'))
                        <p class="help-block">
                            {{ $errors->first('id_sage') }}
                        </p>
                    @endif
                </div>
                
            </div>
            
            <div class="row">
          
                <div class="col-xs-12 form-group">
                    {!! Form::label('combination', 'Combination', ['class' => 'control-label']) !!}
                    {!! Form::select('ciblage[]', $ciblages, old('ciblage') ? old('ciblage') : $combinations, ['class' => 'form-control select2', 'multiple' => 'multiple']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('combination'))
                        <p class="help-block">
                            {{ $errors->first('combination') }}
                        </p>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {!! Form::submit(trans('global.app_save'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}

@stop

