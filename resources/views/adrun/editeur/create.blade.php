@extends('layouts.adrun-master-one')

@section('content')

<h3 class="page-title">@lang('global.editeur.title')</h3>

    {!! Form::open(['method' => 'POST', 'route' => ['admin.editeur.store']]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_create')
        </div>
        
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-4 form-group">
                    {!! Form::label('name', 'Name*', ['class' => 'control-label']) !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('name'))
                        <p class="help-block">
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                </div>
                
                <div class="col-xs-4 form-group">
                    {!! Form::label('company_name', 'Company Name*', ['class' => 'control-label']) !!}
                    {!! Form::text('company_name', old('company_name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('company_name'))
                        <p class="help-block">
                            {{ $errors->first('company_name') }}
                        </p>
                    @endif
                </div>
                
                <div class="col-xs-4 form-group">
                    {!! Form::label('url', 'Website*', ['class' => 'control-label']) !!}
                    {!! Form::text('url', old('url'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('url'))
                        <p class="help-block">
                            {{ $errors->first('url') }}
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
                    {!! Form::label('address1', 'Address 1', ['class' => 'control-label']) !!}
                    {!! Form::text('address1', old('v_address1'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('address1'))
                        <p class="help-block">
                            {{ $errors->first('address1') }}
                        </p>
                    @endif
                </div>
                
                <div class="col-xs-6 form-group">
                    {!! Form::label('address2', 'Address 2', ['class' => 'control-label']) !!}
                    {!! Form::text('address2', old('address2'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('address2'))
                        <p class="help-block">
                            {{ $errors->first('address2') }}
                        </p>
                    @endif
                </div>
                
            </div>
            
            <div class="row">
                
                <div class="col-xs-4 form-group">
                    {!! Form::label('town', 'Town', ['class' => 'control-label']) !!}
                    {!! Form::text('town', old('town'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('town'))
                        <p class="help-block">
                            {{ $errors->first('town') }}
                        </p>
                    @endif
                </div>
                
                <div class="col-xs-4 form-group">
                    {!! Form::label('post_code', 'Post Code', ['class' => 'control-label']) !!}
                    {!! Form::text('post_code', old('post_code'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('post_code'))
                        <p class="help-block">
                            {{ $errors->first('post_code') }}
                        </p>
                    @endif
                </div>
                
                <div class="col-xs-4 form-group">
                    {!! Form::label('country', 'Country', ['class' => 'control-label']) !!}
                    {!! Form::text('country', 'Reunion', ['class' => 'form-control', 'placeholder' => '', 'readonly']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('country'))
                        <p class="help-block">
                            {{ $errors->first('country') }}
                        </p>
                    @endif
                </div>
                
            </div>
            
            <div class="row">
                <div class="col-xs-4 form-group">
                    {!! Form::label('phone', 'Phone', ['class' => 'control-label']) !!}
                    {!! Form::text('phone', old('phone'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('phone'))
                        <p class="help-block">
                            {{ $errors->first('phone') }}
                        </p>
                    @endif
                </div>
                
                <div class="col-xs-4 form-group">
                    {!! Form::label('fax', 'FAX', ['class' => 'control-label']) !!}
                    {!! Form::text('fax', old('d_fax'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('fax'))
                        <p class="help-block">
                            {{ $errors->first('fax') }}
                        </p>
                    @endif
                </div>
                
                <div class="col-xs-4 form-group">
                    {!! Form::label('email', 'Email', ['class' => 'control-label']) !!}
                    {!! Form::text('email', old('email'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('email'))
                        <p class="help-block">
                            {{ $errors->first('email') }}
                        </p>
                    @endif
                </div>
                
            </div>
            
            <div class="row">
                <div class="col-xs-4 form-group">
                    {!! Form::label('customer_ref_id', 'Customer Ref ID', ['class' => 'control-label']) !!}
                    {!! Form::text('customer_ref_id', old('customer_ref_id'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('customer_ref_id'))
                        <p class="help-block">
                            {{ $errors->first('customer_ref_id') }}
                        </p>
                    @endif
                </div>
                
                <div class="col-xs-4 form-group">
                    {!! Form::label('target', 'Target', ['class' => 'control-label']) !!}
                    {!! Form::text('target', 'Local', ['class' => 'form-control', 'placeholder' => '', 'readonly']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('target'))
                        <p class="help-block">
                            {{ $errors->first('target') }}
                        </p>
                    @endif
                </div>
                
                <div class="col-xs-4 form-group">
                    {!! Form::label('status', 'Status', ['class' => 'control-label']) !!}
                    {!! Form::text('status', 'Active', ['class' => 'form-control', 'placeholder' => '', 'readonly']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('status'))
                        <p class="help-block">
                            {{ $errors->first('status') }}
                        </p>
                    @endif
                </div>
                
            </div>
            
            <div class="row">
                <div class="col-xs-4 form-group">
                    {!! Form::label('siret', 'Siret', ['class' => 'control-label']) !!}
                    {!! Form::text('siret', old('siret'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('siret'))
                        <p class="help-block">
                            {{ $errors->first('siret') }}
                        </p>
                    @endif
                </div>
                
                <div class="col-xs-4 form-group">
                    {!! Form::label('code_naf', 'Code NAF', ['class' => 'control-label']) !!}
                    {!! Form::text('code_naf', '', ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('code_naf'))
                        <p class="help-block">
                            {{ $errors->first('code_naf') }}
                        </p>
                    @endif
                </div>
                
                <div class="col-xs-4 form-group">
                    {!! Form::label('code_tva', 'TVA Ref', ['class' => 'control-label']) !!}
                    {!! Form::text('code_tva', '', ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('code_tva'))
                        <p class="help-block">
                            {{ $errors->first('code_tva') }}
                        </p>
                    @endif
                </div>
                
            </div>
            
            <div class="row">
          
                <div class="col-xs-12 form-group">
                    {!! Form::label('ciblage', 'Ciblages*', ['class' => 'control-label']) !!}
                    {!! Form::select('ciblage[]', $ciblages, old('ciblage'), ['class' => 'form-control select2', 'multiple' => 'multiple']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('ciblage'))
                        <p class="help-block">
                            {{ $errors->first('ciblage') }}
                        </p>
                    @endif
                </div>
            </div>
            
            <div class="row">
                <div class="col-xs-6 form-group">
                    {!! Form::label('regie_percentage', 'Regie[%]', ['class' => 'control-label']) !!}
                    {!! Form::text('regie_percentage', old('regie_percentage'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('regie_percentage'))
                        <p class="help-block">
                            {{ $errors->first('regie_percentage') }}
                        </p>
                    @endif
                </div>
                
                <div class="col-xs-6 form-group">
                    {!! Form::label('editeur_percentage', 'Editeur[%]', ['class' => 'control-label']) !!}
                    {!! Form::text('editeur_percentage', old('editeur_percentage'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('editeur_percentage'))
                        <p class="help-block">
                            {{ $errors->first('editeur_percentage') }}
                        </p>
                    @endif
                </div>
                
            </div>
            
        <div class="alert alert-success">
            <strong>Contact</strong> Person
        </div> 
            
        <div class="row">
            <div class="col-xs-6 form-group">
                {!! Form::label('contact_first_name', 'First Name', ['class' => 'control-label']) !!}
                {!! Form::text('contact_first_name', old('contact_first_name'), ['class' => 'form-control', 'placeholder' => '']) !!}
                <p class="help-block"></p>
                @if($errors->has('contact_first_name'))
                    <p class="help-block">
                        {{ $errors->first('contact_first_name') }}
                    </p>
                @endif
            </div>

            <div class="col-xs-6 form-group">
                    {!! Form::label('contact_last_name', 'Last Name', ['class' => 'control-label']) !!}
                    {!! Form::text('contact_last_name', old('contact_last_name'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('contact_last_name'))
                        <p class="help-block">
                            {{ $errors->first('contact_last_name') }}
                        </p>
                    @endif
            </div>
        </div>
            
        <div class="row">
            <div class="col-xs-6 form-group">
                {!! Form::label('contact_phone', 'Phone', ['class' => 'control-label']) !!}
                {!! Form::text('contact_phone', old('id_siret'), ['class' => 'form-control', 'placeholder' => '']) !!}
                <p class="help-block"></p>
                @if($errors->has('contact_phone'))
                    <p class="help-block">
                        {{ $errors->first('contact_phone') }}
                    </p>
                @endif
            </div>

            <div class="col-xs-6 form-group">
                    {!! Form::label('contact_email', 'Email', ['class' => 'control-label']) !!}
                    {!! Form::text('contact_email', old('id_siret'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('contact_email'))
                        <p class="help-block">
                            {{ $errors->first('contact_email') }}
                        </p>
                    @endif
            </div>
        </div>    
            
        </div>
    </div>

    {!! Form::submit(trans('global.app_save'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}

@stop