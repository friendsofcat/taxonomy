<li class="dd-item" data-id="{!! $child->id !!}">
  <!-- drag handle -->
  <div class="handle dd-handle">
    <i class="fa fa-ellipsis-v"></i>
    <i class="fa fa-ellipsis-v"></i>
    &nbsp;
    <!-- checkbox -->
    <!-- todo text -->
    <span class="text">{!! $child->id !!} | {!! $child->name !!}</span>
    <!-- Emphasis label -->
    <!-- General tools such as edit or delete-->
    <div class="pull-right">
      <div class="btn-group">
        {!! Form::open(array('method' => 'GET', 'url' => action('\Trexology\Taxonomy\Controllers\TermsController@edit', $child->id))) !!}
        {!! Form::button(Lang::get('taxonomy::general.button.edit'), array('class'=>'btn btn-xs btn-primary btn-flat', 'type' => 'submit')) !!}
        {!! Form::close() !!}
      </div>

      <div class="btn-group">
        {!! Form::open(array('method' => 'DELETE', 'url' => action('\Trexology\Taxonomy\Controllers\TermsController@destroy', $child->id))) !!}
        {!! Form::button(Lang::get('taxonomy::general.button.delete'), array('class'=>'delete-confirm-dialog btn btn-xs btn-danger btn-flat', 'type' => 'submit')) !!}
        {!! Form::close() !!}
      </div>
    </div>
  </div>
  <ul class="dd-list">
    @each('taxonomy::terms.term', $child->child, 'child')
  </ul>
</li>
