<?php namespace Trexology\Taxonomy\Models;

class Vocabulary extends \Eloquent {

  protected $fillable = [
    'name',
  ];

  protected $table = 'vocabularies';

  public $rules = [
    'name' => 'required'
  ];

  public function terms() {
    return $this->HasMany('Trexology\Taxonomy\Models\Term');
  }

  public function relations() {
    return $this->HasMany('Trexology\Taxonomy\Models\TermRelation');
  }

}
