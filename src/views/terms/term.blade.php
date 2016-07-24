<li class="dd-item" data-id="{!! $child->id !!}">
  <!-- drag handle -->
  <div class="handle dd-handle">
    <i class="fa fa-ellipsis-v"></i>
    <i class="fa fa-ellipsis-v"></i>
    &nbsp;
    <!-- checkbox -->
    <!-- todo text -->
    <span class="text">{!! $child->name !!}</span>
    <!-- Emphasis label -->
    <!-- General tools such as edit or delete-->
    <div class="pull-right">
      <div class="btn-group">
        {!! Form::open(array('method' => 'GET', 'url' => action('\Trexology\Taxonomy\Controllers\TermsController@getEdit', $child->id))) !!}
        {!! Form::button(Lang::get('taxonomy::general.button.edit'), array('class'=>'btn btn-xs btn-primary btn-flat', 'type' => 'submit')) !!}
        {!! Form::close() !!}
      </div>

      <div class="btn-group">
        {!! Form::open(array('method' => 'DELETE', 'url' => action('\Trexology\Taxonomy\Controllers\TermsController@deleteDestroy', $child->id))) !!}
        {!! Form::button(Lang::get('taxonomy::general.button.delete'), array('class'=>'delete-confirm-dialog btn btn-xs btn-danger btn-flat', 'type' => 'submit')) !!}
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</li>

{{--

@foreach ($child['children'] as $term_weight => $child)
   @include('taxonomy::terms.term', $child)
@endforeach

 --}}
