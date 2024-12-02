<?php

namespace Modules\Slider\Entities;

use Astrotomic\Translatable\Translatable;
use Modules\Core\Icrud\Entities\CrudModel;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Slider extends CrudModel
{

  use BelongsToTenant;

  protected $table = 'slider__sliders';
  public $transformer = 'Modules\Slider\Transformers\SliderTransformer';
  public $repository = 'Modules\Slider\Repositories\SliderRepository';
  public $requestValidation = [
      'create' => 'Modules\Slider\Http\Requests\CreateSliderRequest',
      'update' => 'Modules\Slider\Http\Requests\UpdateSliderRequest',
    ];
  //Instance external/internal events to dispatch with extraData
  public $dispatchesEventsWithBindings = [
    //eg. ['path' => 'path/module/event', 'extraData' => [/*...optional*/]]
    'created' => [],
    'creating' => [],
    'updated' => [],
    'updating' => [],
    'deleting' => [],
    'deleted' => []
  ];
  public $translatedAttributes = [];
  protected $fillable = [
    'name',
    'system_name',
    'options',
    'active',
    'type',
    'organization_id'
  ];
  protected $fakeColumns = ['options'];

  public function slides()
  {
    return $this->hasMany(Slide::class)->with('translations')->orderBy('position', 'asc');
  }

  protected function setOptionsAttribute($value)
  {
    $this->attributes['options'] = json_encode($value);
  }

  public function getOptionsAttribute($value)
  {
    return json_decode($value);
  }

  public function getCacheClearableData()
  {
    $baseUrls = [config("app.url")];
//    falta la funcicion getUrlAttribute
//    if (!$this->wasRecentlyCreated) {
//      $baseUrls[] = $this->url;
//    }
    $urls = ['urls' => $baseUrls];
    return $urls;
  }
}
