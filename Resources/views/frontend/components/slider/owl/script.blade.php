@section('scripts-owl')
  @parent
  <script>
    $(document).ready(function () {
      let vmslider = $('#{{ $slider->system_name }}');//Get the slider by ID
      let isMobile = window.innerWidth <= 767 ? true : false; // Define if current window size is mobile or not
      //Filter items by responsive before start the owl
      vmslider.find(`.owl-d-${isMobile ? 'desktop' : 'mobile'}`).each(function () {
        $(this).remove();
      })
      //start owl
      vmslider.owlCarousel({
        stagePadding: {!!$stagePadding!!},
        items: 1,
        dots: {!! $dots ? 'true' : 'false' !!},
        loop: {!! $loopOwl ? 'true' : 'false' !!},
        lazyLoad: true,
        margin: {!! $margin !!},
        nav: {!! $nav ? 'true' : 'false' !!},
        autoplay: {!! $autoplay ? 'true' : 'false' !!},
        autoplayHoverPause: {!! $autoplayHoverPause ? 'true' : 'false' !!},
        responsiveClass: {!! $responsiveClass ? 'true' : 'false' !!},
        responsive: {!! $responsive!!},
        autoplayTimeout: {{$autoplayTimeout}},
        mouseDrag: {!! $mouseDrag ? 'true' : 'false' !!},
        touchDrag: {!! $touchDrag ? 'true' : 'false' !!},
        {!! !empty($navText) ? 'navText: '.$navText."," : "" !!}
      });
      vmslider.find('.owl-dot').each(function(index) {
        $(this).attr('aria-label', index + 1);
      });
      vmslider.find('.owl-next').attr('aria-label','{{trans('slider::frontend.next')}}');
      vmslider.find('.owl-prev').attr('aria-label','{{trans('slider::frontend.previous')}}');
    });
  </script>
@stop