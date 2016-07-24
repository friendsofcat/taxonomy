@extends($layout->extends)

@section($layout->content)
  <h1>@lang('taxonomy::general.title')</h1>
  <div class="row">
    <div class="col-sm-12">
        {!! Form::open(array('method'=>'GET', 'url' => action('\Trexology\Taxonomy\Controllers\TaxonomyController@getCreate'))) !!}
        {!! Form::submit(Lang::get('taxonomy::vocabulary.create.button.add'), array('class' => 'btn btn-primary btn-flat margin-bottom')) !!}
        {!! Form::close() !!}
   </div>
  </div>

  <div class="row">

    <div class="col-xs-12">

      <div class="box">

        <div class="box-header">
          <h3 class="box-title">
            @lang('taxonomy::vocabulary.table.title')
          </h3>
        </div>

        <div class="box-body table-responsive no-padding">

          <table class="table table-hover">

            <tbody>
              <tr>
                <th class="span2">
                  @lang('taxonomy::vocabulary.table.name')
                </th>
                <th class="span2">
                  @lang('taxonomy::vocabulary.table.actions')
                </th>
              </tr>
            </tbody>

            <tbody>
              @foreach ($vocabularies as $vocabulary)
                <tr>
                  <td>
                    <a href="{!! action('\Trexology\Taxonomy\Controllers\TermsController@getIndex', ['id' => $vocabulary->id]) !!}">
                      {!! $vocabulary->name !!}
                    </a>
                  </td>
                  <td class="text-right">
                    <div class="btn-group">
                          {!! Form::open(array('method'=>'GET', 'url' => action('\Trexology\Taxonomy\Controllers\TaxonomyController@getEdit', $vocabulary->id))) !!}
                          {!! Form::submit(Lang::get('taxonomy::general.button.edit'), array('class' => 'btn btn-xs btn-primary btn-flat')) !!}
                          {!! Form::close() !!}
                      </div>

                      <div class="btn-group">
                          {!! Form::open(array('method'=>'DELETE', 'url' => action('\Trexology\Taxonomy\Controllers\TaxonomyController@deleteDestroy', $vocabulary->id))) !!}
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

@stop
