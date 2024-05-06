@extends($theme.'layouts.app')
@section('title', trans($title))

@section('content')
    <!-- blog section  -->
    @if(isset($templates['blog'][0]) && $blog = $templates['blog'][0])
        @if(0 < count($contentDetails['blog']))
            <section class="blog-section blog-details">
                <div class="container">
                    <div class="row g-lg-5 gy-5">
                        <div class="col-lg-8">
                            <div class="row g-4">
                                @foreach($contentDetails['blog']->sortDesc() as $k => $data)
                                    <div class="col-12">
                                        <div class="blog-box">
                                            <div class="img-box">
                                                <img
                                                    src="{{getFile(config('location.content.path').'thumb_'.@$data->content->contentMedia->description->image)}}"
                                                    class="img-fluid" alt="">
                                                <div class="date">
                                                    <span>{{dateTime(@$data->created_at,'d M, Y')}}</span>
                                                </div>
                                            </div>
                                            <div class="text-box">
                                                <div class="date-author">
                                                    <span><i class="fal fa-user-circle text-secondary"></i> @lang('Admin') </span>
                                                </div>
                                                <h4>
                                                    <a href="{{route('blogDetails',[slug(@$data->description->title), $data->content_id])}}"
                                                       class="blog-title"
                                                    >{{\Illuminate\Support\Str::limit(@$data->description->title,60)}}</a
                                                    >
                                                </h4>
                                                <p>
                                                    {{Illuminate\Support\Str::limit(strip_tags(@$data->description->description),120)}}
                                                </p>
                                                <a href="{{route('blogDetails',[slug(@$data->description->title), $data->content_id])}}"
                                                   class="btn-custom read-more"
                                                >@lang('read more')
                                                    <i class="fal fa-long-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="side-bar">
                                <div class="side-box">
                                    <h4>@lang('Recent Blogs')</h4>
                                    @foreach($contentDetails['blog']->shuffle()->take(3) as $key => $data)
                                        <div class="side-blog-box">
                                            <div class="img-box">
                                                <img class="img-fluid" src="{{getFile(config('location.content.path').'thumb_'.@$data->content->contentMedia->description->image)}}" alt="">
                                            </div>
                                            <div class="text-box">
                                                <a href="{{route('blogDetails',[slug(@$data->description->title), $data->content_id])}}" class="title">{{\Illuminate\Support\Str::limit(@$data->description->title,20)}} </a>
                                                <span class="date">{{dateTime(@$data->created_at,'d M, Y')}}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    @endif

@endsection
