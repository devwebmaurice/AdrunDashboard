@extends('layouts.adrun-master-one')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                
                <div class="panel-heading"> CAMPAGNE TERMINÉE </div>
        
                <div class="panel-body  table-responsive">
                    
                    <table class="table table-bordered table-striped {{ count($campaigns) > 0 ? 'datatable' : '' }} dt-select">
                        <thead>
                            <tr>
                                <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>

                                <th>@lang('global.campaign.fields.name')</th>
                                <th>@lang('global.campaign.fields.advertiser')</th>
                                <th>@lang('global.campaign.fields.statut')</th>
                                <th>@lang('global.campaign.fields.impression')</th>
                                <th>@lang('global.campaign.fields.start')</th>
                                <th>@lang('global.campaign.fields.end')</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if (count($campaigns) > 0)

                                @foreach ($campaigns as $campaign)
                                
                                    @if ( $campaign->start > Carbon\Carbon::now() )

                                        <tr data-entry-id="{{ $campaign->id }}"  class="adrun-en-attente">

                                    @elseif ( $campaign->end > Carbon\Carbon::now() )

                                        <tr data-entry-id="{{ $campaign->id }}"  class="adrun-en-cour">

                                    @else

                                        <tr data-entry-id="{{ $campaign->id }}"  class="adrun-termine">

                                    @endif
                                    
                                        <td></td>

                                        <td>{{ mb_strtoupper(trans($campaign->cname)) }}</td>
                                        <td>{{ mb_strtoupper(html_entity_decode(trans($campaign->aname))) }}</td>
                                        
                                        @if ( $campaign->start > Carbon\Carbon::now() )

                                         <td>EN ATTENTE</td>

                                    @elseif ( $campaign->end > Carbon\Carbon::now() )

                                         <td>EN COUR</td>

                                    @else

                                         <td>TERMINE</td>

                                    @endif
                                        
                                       
<!--                                        <td>{{ App\Models\Adrun\AdrunReportModel::getInstance()->getImpression($campaign->id_adtech) }}</td>-->
                                    <td>MASTER CAMPAIGN</td>
                                        <td>{{ Carbon\Carbon::parse($campaign->start)->format('d-m-Y')  }}</td>
                                        <td>{{ Carbon\Carbon::parse($campaign->end)->format('d-m-Y')}}</td>
                                        <td>
                                            {!! Form::open(array(
                                                'style' => 'display: inline-block;',
                                                'method' => 'DELETE',
                                                'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                                'route' => ['admin.editeur.destroy', $campaign->id])) !!}
                                            {!! Form::submit(trans('Report Created'), array('class' => 'btn btn-xs btn-success')) !!}
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
        </div>
        
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">EDITEUR</div>

                <div class="panel-body">
                    <table class="table table-bordered table-striped {{ count($editeurs) > 0 ? 'datatable' : '' }} dt-select">
                        <thead>
                            <tr>
                                <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>

                                <th>@lang('global.campaign.fields.name')</th>
                                <th>REGIE %</th>
                                <th>EDITEUR %</th>
                                <th>IMPRESSION</th>
                                <th>CLICK</th>
                                <th>MONTH</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @if (count($editeurs) > 0)

                                @foreach ($editeurs as $editeur)
                                

                                        <tr data-entry-id="{{ $editeur->id }}"  class="adrun-en-attente">
                                        <td></td>
                                        <td>{{ mb_strtoupper(trans($editeur->name)) }}</td>
                                        <td>{{ $editeur->regie_percentage }}</td>
                                        <td>{{ $editeur->editeur_percentage }}</td>
<!--                                        <td>{{ App\Models\Adrun\AdrunReportModel::getInstance()->getImpression($campaign->id_adtech) }}</td>-->
                                        <td>{{ App\Models\Adrun\AdrunWebsiteModel::getInstance()->getImpression( $editeur->id_adtech )   }}</td>
                                        <td>{{ App\Models\Adrun\AdrunWebsiteModel::getInstance()->getClick( $editeur->id_adtech )  }}</td>
                                        <td>{{ mb_strtoupper(trans($month->format('F'))) }}</td>
                                        <td>
                                            {!! Form::open(array(
                                                'style' => 'display: inline-block;',
                                                'method' => 'DELETE',
                                                'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                                'route' => ['admin.editeur.destroy', $campaign->id])) !!}
                                            {!! Form::submit(trans('Report Created'), array('class' => 'btn btn-xs btn-success')) !!}
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
        </div>
        
        
        
    </div>
@endsection





