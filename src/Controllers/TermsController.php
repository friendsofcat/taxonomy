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

use DB;

use Trexology\Taxonomy\Models\Vocabulary;
use Trexology\Taxonomy\Models\Term;


use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

class TermsController extends BaseController {

	use DispatchesJobs, ValidatesRequests;

  protected $vocabulary;
  protected $route_prefix;

  public function __construct(Vocabulary $vocabulary) {
    $this->vocabulary = $vocabulary;
    $this->route_prefix = rtrim(Config::get('taxonomy::route_prefix'), '.') . '.';


    $layout = (object) [
      'extends' => config('taxonomy.config.layout.extends'),
      'header' => config('taxonomy.config.layout.header'),
      'content' => config('taxonomy.config.layout.content'),
    ];

    View::share('layout', $layout);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function getCreate($vocabulary_id) {
    return View::make('taxonomy::terms.create', compact('vocabulary_id'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function postStore(Request $request) {
    $validation = Validator::make($request->all(), Term::$rules);

    if ($validation->fails()) {
      return Redirect::back()
        ->withInput()
        ->withErrors($validation)
        ->with('error', 'There were validation errors.');
    }

    $vocabulary = Vocabulary::findOrFail($request->vocabulary_id);

    $term = \Taxonomy::createTerm($vocabulary->id, $request->name);

    return Redirect::to(action('\Trexology\Taxonomy\Controllers\TermsController@getIndex', ['id' => $vocabulary->id]))->with('success', 'Created');
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function getEdit($id) {
    $term = Term::find($id);

    if (is_null($term)) {
      return Redirect::back($this->route_prefix . 'taxonomy.index');
    }

     return View::make('taxonomy::terms.edit', compact('term'));
  }

  public function getIndex(Request $request) {
		$vocabulary = Vocabulary::findOrFail($request->id);

		$parents = DB::select("SELECT id, name, EXISTS (SELECT id FROM terms as children WHERE children.parent = terms.id LIMIT 1) as child
		from terms
		where terms.parent = 0 and terms.vocabulary_id = ?
		order by terms.weight ASC", [$request->id]);

		$terms = $this->getChild($parents);

    return view('taxonomy::terms.index', compact('vocabulary', 'terms'));
  }

	private function getChild($parents)
	{

		foreach ($parents as $key => &$term) {
				if ($term->child) {
					$term->child = DB::select("SELECT id, name, EXISTS (SELECT id FROM terms as children WHERE children.parent = terms.id LIMIT 1) as child
					from terms
					where terms.parent = ?
					order by terms.weight ASC", [$term->id]);

					$this->getChild($term->child);
				}
				else{
					$term->child = []; // Cast null to empty array
				}
		}
		return $parents;
	}

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function putUpdate(Request $request, $id) {
    $validation = Validator::make($request->all(), Term::$rules);

    if ($validation->fails()) {
      return Redirect::back()
        ->withInput()
        ->withErrors($validation)
        ->with('error', 'There were validation errors.');
    }

    $term = Term::find($id);
    $term->name = $request->name;
    $term->save();

    return Redirect::to(action('\Trexology\Taxonomy\Controllers\TermsController@getIndex', ['id' => $term->vocabulary_id]))->with('success', 'Updated');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function deleteDestroy($id) {
    // Delete children if any exist
    Term::whereParent($id)->delete();

    // Delete Term
    Term::destroy($id);

    return Redirect::back();
  }

}
