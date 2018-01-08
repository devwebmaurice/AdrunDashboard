@extends('layouts.adrun-master-one')

@section('content')

    <h3 class="page-title">@lang('global.editeur.title')</h3>
    <p>
        <a href="{{ route('admin.editeur.create') }}" class="btn btn-success">@lang('global.app_add_new')</a>
    </p>


    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_list')
        </div>
        
        <div class="panel-body table-responsive">
            
            <table class="table table-bordered table-striped {{ count($editeurs) > 0 ? 'datatable' : '' }} dt-select">
                <thead>
                    <tr>
                        <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>

                        <th>@lang('global.editeur.fields.name')</th>
                        <th>@lang('global.editeur.fields.company_name')</th>
                        <th>@lang('global.editeur.fields.target')</th>
                        <th>@lang('global.editeur.fields.percentage_regie')</th>
                        <th>@lang('global.editeur.fields.percentage_editeur')</th>
                        <th>@lang('global.editeur.fields.impression')</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                
                <tbody>
                    @if (count($editeurs) > 0)
                    
                        @foreach ($editeurs as $editeur)
                            <tr data-entry-id="{{ $editeur->id }}">
                                <td></td>

                                <td>{{ $editeur->name }}</td>
                                <td>{{ $editeur->company }}</td>
                                <td>{{ $editeur->target }}</td>
                                <td>{{ $editeur->regie }}</td>
                                <td>{{ $editeur->editeur }}</td>
                                <td>{{ $editeur->impression }}</td>
                                <td>
                                    <a href="{{ route('admin.editeur.edit',[$editeur->id]) }}" class="btn btn-xs btn-info">@lang('global.app_edit')</a>
                                    <button class="btn btn-xs btn-primary"  data-toggle="modal" data-target="#myModal"><i class="fa fa-area-chart" aria-hidden="true"></i></button>
                                    <button class="btn btn-xs btn-warning"  data-toggle="modal" data-target="#myModal"><i class="fa fa-list-alt" aria-hidden="true"></i></button>
                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['admin.editeur.destroy', $editeur->id])) !!}
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
    
    
      <!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Extended Module</h4>
      </div>
      <div class="modal-body">
        <p>Under construction</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>  
    
    
@endsection
