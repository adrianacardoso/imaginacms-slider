<div id="{{ $slider->system_name }}"
     class="owl-carousel owl-theme owl-slider-layout-4 {{ $dots ? ' owl-with-dots carousel-indicators-position-'.$dotsPosition.' carousel-indicators-style-'. $dotsStyle: '' }} position-relative">
  @foreach($slides as $index => $slide)
    @if($slide->active)
      <div class="card border-0 {{ $slide->responsive == 2 ? 'owl-d-desktop' : '' }}
      {{ $slide->responsive == 3 ? 'owl-d-mobile' : '' }}">
          <x-isite::edit-link link="{{$editLink}}{{$slider->id}}/?edit={{$slide->id}}"
                              :tooltip="$tooltipEditLink"/>
          <div class="row align-items-center">
              <div class="col-12 col-lg-6 {{$orderClasses["photo"] ?? 'order-0'}}">
                  <div class="bg-image">
                      @if(isset($slide->code_ads) && !is_null($slide->code_ads))
                          <div class="banner-{{$slide->id}} py-3">
                              {!! $slide->code_ads !!}
                          </div>
                      @else
                          <x-media::single-image :alt="$slide->title ?? Setting::get('core::site-name')"
                                                 :title="$slide->title ?? Setting::get('core::site-name')"
                                                 :url="$slide->uri ?? $slide->url ?? null" :isMedia="true"
                                                 imgClasses="w-100 slider-img__{{$imgObjectFit}}" fetchPriority="high"
                                                 :mediaFiles="$slide->mediaFiles()" zone="slideimage"/>
                      @endif
                  </div>
              </div>
              <div class="col-12 col-lg-6 {{$orderClasses["content"] ?? 'order-1'}}">
                  <div class="card-body py-5">
                      @if(!empty($slide->title) || !empty($slide->caption) || !empty($slide->custom_html))
                          @if(!empty($slide->title))
                              <a href="{{ $slide->url ?? $slide->uri }}">
                                  <h3 class="title h1">
                                      {{$slide->title}}
                                  </h3>
                              </a>
                          @endif
                          @if(!empty($slide->custom_html))
                              <div class="custom-html">
                                  {!! $slide->custom_html !!}
                              </div>
                          @endif
                          @if(!empty($slide->url)  || !empty($slide->uri))
                              <div class="d-block">
                                  <a class="btn btn-primary"
                                     href="{{ $slide->url ?? $slide->uri }}">{{ $slide->caption ?? trans('isite::common.menu.viewMore') }}</a>
                              </div>
                          @endif
                      @endif
                  </div>
              </div>
          </div>
      </div>
    @endif
  @endforeach
</div>
@include("slider::frontend.components.slider.owl.script")
<style>
@switch($imgObjectFit)
    @case('fill')
      #{{$slider->system_name}} .slider-img__fill {
          -o-object-fit: fill;
          object-fit: fill;
      }
  @break
  @case('cover')
      #{{$slider->system_name}} .slider-img__cover {
          -o-object-fit: cover;
          object-fit: cover;
      }
  @break
  @case('contain')
      #{{$slider->system_name}} .slider-img__contain {
          -o-object-fit: contain;
          object-fit: contain;
      }
  @break
@endswitch
</style>