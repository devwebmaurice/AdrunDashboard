@extends('layouts.adrun-master-one')

@section('content')

<h3 class="page-title">@lang('global.format.title')</h3>

    {!! Form::open(['method' => 'POST', 'route' => ['admin.format.store']]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_create')
        </div>
        
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('v_name', 'Name*', ['class' => 'control-label']) !!}
                    {!! Form::text('v_name', old('v_name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('v_name'))
                        <p class="help-block">
                            {{ $errors->first('v_name') }}
                        </p>
                    @endif
                </div>
            </div>
            
            <div class="row">
                <div class="col-xs-4 form-group">
                    {!! Form::label('id', 'ADRUN Intelligence ID', ['class' => 'control-label']) !!}
                    {!! Form::text('id', '', ['class' => 'form-control', 'placeholder' => '', 'readonly']) !!}
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
                
                <div class="col-xs-4 form-group">
                    {!! Form::label('type', 'Type', ['class' => 'control-label']) !!}
                    {!! Form::select('type', $types, old('type'), ['class' => 'form-control select2']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('type'))
                        <p class="help-block">
                            {{ $errors->first('type') }}
                        </p>
                    @endif
                </div>
                
                <div class="col-xs-4 form-group">
                    {!! Form::label('target', 'Target', ['class' => 'control-label']) !!}
                    {!! Form::select('target', $target, old('target'), ['class' => 'form-control select2']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('target'))
                        <p class="help-block">
                            {{ $errors->first('target') }}
                        </p>
                    @endif
                </div>
                
                <div class="col-xs-4 form-group">
                    {!! Form::label('methode', 'Method', ['class' => 'control-label']) !!}
                    {!! Form::select('methode', $method, old('methode'), ['class' => 'form-control select2']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('methode'))
                        <p class="help-block">
                            {{ $errors->first('methode') }}
                        </p>
                    @endif
                </div>
                
            </div>
            
        </div>
    </div>

    {!! Form::submit(trans('global.app_save'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}

@stop