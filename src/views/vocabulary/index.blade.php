@extends($layout->extends)

@section($layout->content)
  <div class="content">

    <h1>@lang('taxonomy::general.title')</h1>
    <div class="row">
      <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">
                @lang('taxonomy::vocabulary.table.title')
              </h3>
              <div class="btn-group pull-right">
                {!! Form::open(array('method'=>'GET', 'url' => action('\Trexology\Taxonomy\Controllers\TaxonomyController@create'))) !!}
                {!! Form::submit(Lang::get('taxonomy::vocabulary.create.button.add'), array('class' => 'btn btn-success btn-flat margin-bottom')) !!}
                {!! Form::close() !!}
              </div>
            </div>

          <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th class="">@lang('taxonomy::vocabulary.table.name')</th>
                  <th class="text-right">@lang('taxonomy::vocabulary.table.actions')</th>
                </tr>
              </thead>

              <tbody>
                @foreach ($vocabularies as $vocabulary)
                  <tr>
                    <td>
                      <a href="{!! action('\Trexology\Taxonomy\Controllers\TermsController@index', ['id' => $vocabulary->id]) !!}">
                        {!! $vocabulary->name !!}
                      </a>
                    </td>
                    <td class="text-right">
                      <div class="btn-group">
                        <a href="{!! action('\Trexology\Taxonomy\Controllers\TermsController@edit', ['id' => $vocabulary->id]) !!}" class = 'btn btn-xs btn-primary btn-flat'>
                          {!! Lang::get('taxonomy::general.button.edit') !!}
                        </a>
                      </div>

                      <div class="btn-group">
                          {!! Form::open(array('method'=>'DELETE', 'url' => action('\Trexology\Taxonomy\Controllers\TaxonomyController@destroy', $vocabulary->id))) !!}
                          {!! Form::submit(Lang::get('taxonomy::general.button.delete'), array('class' => 'delete-confirm-dialog btn btn-xs btn-danger btn-flat')) !!}
                          {!! Form::close() !!}
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="box-footer clearfix">
            {!! $vocabularies->render() !!}
          </div>

        </div>
      </div>
    </div>


  </div>

@stop
