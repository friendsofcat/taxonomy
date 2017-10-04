@Extends($layout->extends)

@section($layout->content)
  <div class="content">
    <h1>@lang('taxonomy::vocabulary.create.header')</h1>
    <div class="row">

      {!! Form::open(array('method'=>'POST', 'url' => action('\Trexology\Taxonomy\Controllers\TaxonomyController@store'))) !!}

      <div class="col-md-12">
        <div class="box box-primary">

          <div class="box-body">
            <div class="form-group{!! $errors->has('name') ? ' has-error has-feedback' : '' !!}">
              {!! Form::label('name', Lang::get('taxonomy::vocabulary.create.label.name'), ['class' => 'control-label']) !!}
              {!! Form::text('name', NULL, ['class' => 'form-control']) !!}
              {!! $errors->has('name') ? Form::label('error', $errors->first('name'), array('class' => 'control-label')) : '' !!}
              {!! $errors->has('name') ? '<span class="glyphicon glyphicon-remove form-control-feedback"></span>' : '' !!}
            </div>
          </div>

          <div class="box-footer">
            <button type="submit" class="btn btn-flat btn-success">
              @lang('taxonomy::vocabulary.create.button.add')
            </button>
          </div>

        </div>
      </div>
      {!! Form::close() !!}
    </div>

  </div>

@stop
