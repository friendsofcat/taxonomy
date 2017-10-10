<?php namespace Trexology\Taxonomy\Controllers;

use Illuminate\Http\Request;

use Config;
use Lang;
use Redirect;
use Response;
use Sentry;
use Session;
use Validator;
use View;
use Helpers;

use Trexology\Taxonomy\Models\Vocabulary;
use Trexology\Taxonomy\Models\Term;
use Trexology\Taxonomy\Models\TermRelation;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

class TaxonomyController extends BaseController {

	use DispatchesJobs, ValidatesRequests;


  protected $vocabulary;
  protected $route_prefix;

  public function __construct(Vocabulary $vocabulary) {
    $this->vocabulary = $vocabulary;
    $this->route_prefix = rtrim(config('taxonomy.route_prefix'), '.') . '.';

    $layout = (object) [
      'extends' => config('taxonomy.config.layout.extends'),
      'header' => config('taxonomy.config.layout.header'),
      'content' => config('taxonomy.config.layout.content'),
			'js' => config('taxonomy.config.layout.js'),
    ];

    View::share('layout', $layout);
  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index() {
    $vocabularies = $this->vocabulary->paginate(10);

    return view('taxonomy::vocabulary.index', [ 'vocabularies' => $vocabularies]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function create() {
    return view('taxonomy::vocabulary.create');
  }

  public function store(Request $request) {
    $this->validate($request, isset($this->vocabulary->rules_create) ? $this->vocabulary->rules_create : $this->vocabulary->rules);

    Vocabulary::create($request->only('name'));

    return Redirect::to(action('\Trexology\Taxonomy\Controllers\TaxonomyController@index'))->with('success', 'Created');

  }

  /**
   * Destory a resource.
   *
   * @return Response
   */
  public function destroy($id) {
    $vocabulary = $this->vocabulary->find($id);

    $terms = $vocabulary->terms->lists('id')->toArray();

    TermRelation::whereIn('term_id',$terms)->delete();
    Term::destroy($terms);
    $this->vocabulary->destroy($id);

    return Redirect::back();
  }

  /**
   * Update a resource.
   *
   * @return Response
   */
  public function update(Request $request, $id) {
    $this->validate($request, isset($this->vocabulary->rules_create) ? $this->vocabulary->rules_create : $this->vocabulary->rules);

    $vocabulary = $this->vocabulary->findOrFail($id);
    $vocabulary->update($request->only('name'));

    return Redirect::to(action('\Trexology\Taxonomy\Controllers\TaxonomyController@index'))->with('success', 'Updated');

  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id) {
    $vocabulary = $this->vocabulary->find($id);

    Session::put('vocabulary_id', $vocabulary->id);

    if (is_null($vocabulary)) {
      return Redirect::route($this->route_prefix . 'taxonomy.index');
    }

    $terms = $vocabulary->terms()->orderBy('parent', 'ASC')->orderBy('weight', 'ASC')->get();

    $ordered_terms = [];
    foreach ($terms as $term) {
      if (!$term->parent) {
        $ordered_terms[$term->id] = [
          'term' => $term,
          'children' => [],
        ];
      }
      else {
        $ordered_terms[$term->parent]['children'][] = $term;
      }
    }

    $terms = $ordered_terms;

    return View::make('taxonomy::vocabulary.edit', compact('vocabulary', 'terms'));
  }

  public function postOrderTerms(Request $request) {
    $this->vocabulary->find($request->id);

    $request = \Request::instance();
    $content = json_decode($request->json);

		$this->saveOrderTerms($content, 0);

  }

	public function saveOrderTerms($content, $parent_term) {
		  foreach ($content as $child_key => $child){
        $child_term = Term::find($child->id);

        $child_term->parent = $parent_term;
        $child_term->weight = $child_key;

        $child_term->save();

				if (!empty($child->children)) {
		    	$this->saveOrderTerms($child->children, $child->id);
		    }

      }
	}

}
