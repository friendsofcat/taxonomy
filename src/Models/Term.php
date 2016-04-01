<?php namespace Trexology\Taxonomy\Models;

class Term extends \Eloquent {

  protected $fillable = [
    'name',
    'vocabulary_id',
    'parent',
    'weight',
  ];

	public static $rules = [
		'name' => 'required'
  ];

  public function termRelation() {
    return $this->morphMany('Trexology\Taxonomy\Models\TermRelation', 'relationable');
  }

	public function vocabulary() {
		return $this->belongsTo('Trexology\Taxonomy\Models\Vocabulary');
	}

  public function childrens() {
    return $this->hasMany('Trexology\Taxonomy\Models\Term', 'parent', 'id')
      ->orderBy('weight', 'ASC');
  }

  public function parentTerm() {
    return $this->hasOne('Trexology\Taxonomy\Models\Term', 'id', 'parent');
  }
}
