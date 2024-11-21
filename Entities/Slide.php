<?php

namespace Modules\Slider\Entities;

use Astrotomic\Translatable\Translatable;
use Modules\Core\Icrud\Entities\CrudModel;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Modules\Media\Support\Traits\MediaRelation;
use Modules\Page\Entities\Page;

class Slide extends CrudModel
{
  use Translatable, MediaRelation, BelongsToTenant;

  protected $table = 'slider__slides';
  public $transformer = 'Modules\Slider\Transformers\SlideTransformer';
  public $repository = 'Modules\Slider\Repositories\SlideRepository';
  public $requestValidation = [
      'create' => 'Modules\Slider\Http\Requests\CreateSlideRequest',
      'update' => 'Modules\Slider\Http\Requests\UpdateSlideRequest',
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
  public $with = ['files','translations'];
  public $translatedAttributes = [
    'title',
    'caption',
    'uri',
    'url',
    'active',
    'custom_html',
    'summary',
    'code_ads'
  ];
  protected $fillable = [
    'slider_id',
    'page_id',
    'position',
    'target',
    'title',
    'caption',
    'uri',
    'url',
    'type',
    'active',
    'external_image_url',
    'custom_html',
    'responsive',
    'options'
  ];

  /**
   * @var string
   */
  private $linkUrl;

  /**
   * @var string
   */
  private $imageUrl;

  public function slider()
  {
    return $this->belongsTo(Slider::class);
  }

  /**
   * Check if page_id is empty and returning null instead empty string
   * @return number
   */
  public function setPageIdAttribute($value)
  {
    $this->attributes['page_id'] = !empty($value) ? $value : null;
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function page()
  {
    return $this->belongsTo(Page::class);
  }

  /**
   * returns slider image src
   * @return string|null full image path if image exists or null if no image is set
   */
  public function getImageUrl()
  {
    if ($this->imageUrl === null) {
      if (!empty($this->external_image_url)) {
        $this->imageUrl = $this->external_image_url;
      }
      $slideImage = $this->filesByZone('slideimage')->first();
      if ($slideImage) $this->imageUrl = $slideImage->path;
    }

    return $this->imageUrl;
  }


  /**
   * returns slider link URL
   * @return string|null
   */
  public function getLinkUrl()
  {
    if ($this->linkUrl === null) {
      if (!empty($this->url)) {
        $this->linkUrl = $this->url;
      } elseif (!empty($this->uri)) {
        $this->linkUrl = '/' . locale() . '/' . $this->uri;
      } elseif (!empty($this->page)) {
        $this->linkUrl = route('page', ['uri' => $this->page->slug]);
      }
    }

    return $this->linkUrl;
  }

  /**
   * returns slider link URL
   * @return string|null
   */
  public function getUrlAttribute()
  {
    $url = "";
      if (!empty($this->attributes["url"])) {
        $url = $this->attributes["url"];
      } elseif (!empty($this->uri)) {
        $url = \LaravelLocalization::localizeUrl('/'. $this->uri);
      } elseif (!empty($this->page)) {
        $url = route('page', ['uri' => $this->page->slug]);
      }


    return $url;
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
    if (!$this->wasRecentlyCreated) {
      $baseUrls[] = $this->url;
    }
    $urls = ['urls' => $baseUrls];
    return $urls;
  }
}
