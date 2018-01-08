@extends('layouts.adrun-master-one')

@section('content')

<h3 class="page-title">@lang('global.annonceur.title')</h3>

    {!! Form::open(['method' => 'POST', 'route' => ['admin.annonceur.store']]) !!}

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
                <div class="col-xs-6 form-group">
                    {!! Form::label('v_address1', 'Address 1', ['class' => 'control-label']) !!}
                    {!! Form::text('v_address1', old('v_address1'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('v_address1'))
                        <p class="help-block">
                            {{ $errors->first('v_address1') }}
                        </p>
                    @endif
                </div>
                
                <div class="col-xs-6 form-group">
                    {!! Form::label('v_address2', 'Address 2', ['class' => 'control-label']) !!}
                    {!! Form::text('v_address2', old('v_address2'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('v_address2'))
                        <p class="help-block">
                            {{ $errors->first('v_address2') }}
                        </p>
                    @endif
                </div>
                
            </div>
            
            <div class="row">
                
                <div class="col-xs-4 form-group">
                    {!! Form::label('v_town', 'Town', ['class' => 'control-label']) !!}
                    {!! Form::text('v_town', old('v_town'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('v_town'))
                        <p class="help-block">
                            {{ $errors->first('v_town') }}
                        </p>
                    @endif
                </div>
                
                <div class="col-xs-4 form-group">
                    {!! Form::label('v_post_code', 'Post Code', ['class' => 'control-label']) !!}
                    {!! Form::text('v_post_code', old('v_post_code'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('v_post_code'))
                        <p class="help-block">
                            {{ $errors->first('v_post_code') }}
                        </p>
                    @endif
                </div>
                
                <div class="col-xs-4 form-group">
                    {!! Form::label('v_country', 'Country', ['class' => 'control-label']) !!}
                    {!! Form::text('v_country', 'Reunion', ['class' => 'form-control', 'placeholder' => '', 'readonly']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('v_country'))
                        <p class="help-block">
                            {{ $errors->first('v_country') }}
                        </p>
                    @endif
                </div>
                
            </div>
            
            <div class="row">
                <div class="col-xs-4 form-group">
                    {!! Form::label('d_phone', 'Phone', ['class' => 'control-label']) !!}
                    {!! Form::text('d_phone', old('d_phone'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('d_phone'))
                        <p class="help-block">
                            {{ $errors->first('d_phone') }}
                        </p>
                    @endif
                </div>
                
                <div class="col-xs-4 form-group">
                    {!! Form::label('d_fax', 'FAX', ['class' => 'control-label']) !!}
                    {!! Form::text('d_fax', old('d_fax'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('d_fax'))
                        <p class="help-block">
                            {{ $errors->first('d_fax') }}
                        </p>
                    @endif
                </div>
                
                <div class="col-xs-4 form-group">
                    {!! Form::label('v_email', 'Email', ['class' => 'control-label']) !!}
                    {!! Form::text('v_email', old('v_email'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('v_email'))
                        <p class="help-block">
                            {{ $errors->first('v_email') }}
                        </p>
                    @endif
                </div>
                
            </div>
            
            <div class="row">
                <div class="col-xs-4 form-group">
                    {!! Form::label('v_customer_ref_id', 'Customer Ref ID', ['class' => 'control-label']) !!}
                    {!! Form::text('v_customer_ref_id', old('v_customer_ref_id'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('v_customer_ref_id'))
                        <p class="help-block">
                            {{ $errors->first('v_customer_ref_id') }}
                        </p>
                    @endif
                </div>
                
                <div class="col-xs-4 form-group">
                    {!! Form::label('v_type', 'Type', ['class' => 'control-label']) !!}
                    {!! Form::text('v_type', 'Direct Customer', ['class' => 'form-control', 'placeholder' => '', 'readonly']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('v_type'))
                        <p class="help-block">
                            {{ $errors->first('v_type') }}
                        </p>
                    @endif
                </div>
                
                <div class="col-xs-4 form-group">
                    {!! Form::label('id_status', 'Status', ['class' => 'control-label']) !!}
                    {!! Form::text('id_status', 'Active', ['class' => 'form-control', 'placeholder' => '', 'readonly']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('id_status'))
                        <p class="help-block">
                            {{ $errors->first('id_status') }}
                        </p>
                    @endif
                </div>
                
            </div>
            
            <div class="row">
                <div class="col-xs-4 form-group">
                    {!! Form::label('id_siret', 'Siret', ['class' => 'control-label']) !!}
                    {!! Form::text('id_siret', old('id_siret'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('id_siret'))
                        <p class="help-block">
                            {{ $errors->first('id_siret') }}
                        </p>
                    @endif
                </div>
                
                <div class="col-xs-4 form-group">
                    {!! Form::label('id_naf', 'Code NAF', ['class' => 'control-label']) !!}
                    {!! Form::text('id_naf', '', ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('id_naf'))
                        <p class="help-block">
                            {{ $errors->first('id_naf') }}
                        </p>
                    @endif
                </div>
                
                <div class="col-xs-4 form-group">
                    {!! Form::label('id_tva', 'TVA Ref', ['class' => 'control-label']) !!}
                    {!! Form::text('id_tva', '', ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('id_tva'))
                        <p class="help-block">
                            {{ $errors->first('id_tva') }}
                        </p>
                    @endif
                </div>
                
            </div>
            
           
            
        </div>
    </div>

    {!! Form::submit(trans('global.app_save'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}

@stop