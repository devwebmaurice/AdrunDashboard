@extends('layouts.adrun-master-one')

@section('content')

    <h3 class="page-title">@lang('global.format.title')</h3>
    <p>
        <a href="{{ route('admin.format.create') }}" class="btn btn-success">@lang('global.app_add_new')</a>
    </p>


    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_list')
        </div>
        
        <div class="panel-body table-responsive">
            
            <table class="table table-bordered table-striped {{ count($formats) > 0 ? 'datatable' : '' }} dt-select">
                <thead>
                    <tr>
                        <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>

                        <th>@lang('global.annonceur.fields.name')</th>
                        <th>@lang('global.annonceur.fields.type')</th>
                        <th>@lang('global.annonceur.fields.target')</th>
                        <th>@lang('global.annonceur.fields.methode')</th>
                        <th>@lang('global.annonceur.fields.status')</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                
                <tbody>
                    @if (count($formats) > 0)
                    
                        @foreach ($formats as $format)
                            <tr data-entry-id="{{ $format->id }}">
                                <td></td>

                                <td>{{ $format->name }}</td>
                                <td>{{ $format->type }}</td>
                                <td>{{ $format->target }}</td>
                                <td>{{ $format->methode }}</td>
                                <td>{{ $format->status }}</td>
                                <td>
                                    <a href="{{ route('admin.format.edit',[$format->id]) }}" class="btn btn-xs btn-info">@lang('global.app_edit')</a>
                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['admin.format.destroy', $format->id])) !!}
                                    {!! Form::submit(trans('global.app_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                </td>

                            </tr>
                        @endforeach
                    
                    @else
                        <tr>
                            <td colspan="9">@lang('global.app_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>
                
            </table>
            
            
        </div>
        
        
        
    </div>
@endsection
