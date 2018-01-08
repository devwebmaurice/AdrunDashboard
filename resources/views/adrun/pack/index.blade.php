@extends('layouts.adrun-master-one')

@section('content')

    <h3 class="page-title">@lang('global.pack.title')</h3>
    <p>
        <a href="{{ route('admin.users.create') }}" class="btn btn-success">@lang('global.app_add_new')</a>
    </p>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_list')
        </div>
        
        <div class="panel-body table-responsive">
            
            <table class="table table-bordered table-striped {{ count($packs) > 0 ? 'datatable' : '' }} dt-select">
                <thead>
                    <tr>
                        <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        <th style="text-align:center;"></th>
                        <th>@lang('global.annonceur.fields.name')</th>
                        <th>@lang('global.annonceur.fields.websites')</th>
                        <th>@lang('global.annonceur.fields.impression')</th>
                        <th>@lang('global.annonceur.fields.status')</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                
                <tbody>
                    @if (count($packs) > 0)
                    
                        @foreach ($packs as $key => $pack)
                            <tr data-entry-id="{{ $pack['id'] }}">
                                <td></td>
                                <td>
                                 <button  class="btn btn-xs btn-primary open-modal"   value="{{ $pack['id'] }}">
                                     <i class="fa fa-list" aria-hidden="true"></i>
                                 </button >
                                </td>
                                <td>{{ $pack['name'] }}</td>
                                <td>{{ $pack['count'] }}</td>
                                <td>0</td>
                                <td>{{ $pack['status'] }}</td>
                                <td>
                                    <button class="btn btn-xs btn-primary open-modal-list-website" value="{{ $pack['id'] }}">
                                        <i class="fa fa-plus-square" aria-hidden="true"></i>
                                    </button>
                                    <a href="{{ route('admin.annonceur.edit',[$pack['id']]) }}" class="btn btn-xs btn-info"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['admin.annonceur.destroy', $pack['id']])) !!}
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
            <h4 class="modal-title" id='pack_name'>PACK:: </h4>
          </div>
          <div class="modal-body">
            <div id='pack_content'>
                
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>
    
    <!-- Modal -->
    <div id="modal-list-website" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id='pack_name2'>PACK:: </h4>
          </div>
          <div class="modal-body">
            <div id='pack_content2'>
                
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>
    
    
    
@endsection

@section('js-script')
<script src="{{asset('js/ajax-crud.js')}}"></script>

@endsection