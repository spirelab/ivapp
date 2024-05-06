@if(isset($templates['blog'][0]) && $blog = $templates['blog'][0])
    @if(0 < count($contentDetails['blog']))
        <!-- blog section -->
        <section class="blog-section">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="header-text text-center">
                            <h5>@lang(@$blog->description->title)</h5>
                            <h2>@lang(wordSplice($blog->description->sub_title)['withoutLastWord']) <span
                                    class="text-stroke">@lang(wordSplice($blog->description->sub_title)['lastWord'])</span>
                            </h2>
                            <p class="mx-auto">
                                @lang(@$blog->description->short_title)
                            </p>
                        </div>
                    </div>
                </div>

                @if(isset($contentDetails['blog']))
                    <div class="row justify-content-center g-4">
                        @foreach($contentDetails['blog']->take(1)->sortDesc() as $k => $data)
                        <div class="col-lg-5 col-xl-6">
                            <div class="blog-box">
                                <div class="img-box">
                                    <img src="{{getFile(config('location.content.path').'thumb_'.@$data->content->contentMedia->description->image)}}" class="img-fluid" alt="">
                                </div>
                                <div class="text-box">
                                    <div class="date-author">
                                        <span>Admin</span>
                                        <span>{{dateTime(@$data->created_at,'d M, Y')}}</span>
                                    </div>
                                    <h4>
                                        <a href="{{route('blogDetails',[slug(@$data->description->title), $data->content_id])}}" class="blog-title"
                                        >{{\Illuminate\Support\Str::limit(@$data->description->title,60)}}</a
                                        >
                                    </h4>
                                    <p>
                                        {!! \Illuminate\Support\Str::limit(@$data->description->description,140) !!}
                                    </p>
                                    <a href="{{route('blogDetails',[slug(@$data->description->title), $data->content_id])}}" class="read-more"
                                    >@lang('read more')
                                        <i class="fal fa-long-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <div class="col-lg-7 col-xl-6">
                            @foreach($contentDetails['blog']->take(3)->sortDesc() as $k => $data)
                                <div class="blog-box d-flex">
                                    <div class="img-box">
                                        <img src="{{getFile(config('location.content.path').'thumb_'.@$data->content->contentMedia->description->image)}}" class="img-fluid"
                                             alt="">
                                    </div>
                                    <div class="text-box">
                                        <div class="date-author">
                                            <span>@lang('Admin')</span>
                                            <span>{{dateTime(@$data->created_at,'d M, Y')}}</span>
                                        </div>
                                        <h4>
                                            <a href="{{route('blogDetails',[slug(@$data->description->title), $data->content_id])}}"
                                               class="blog-title"
                                            >{{\Illuminate\Support\Str::limit(@$data->description->title,60)}}</a
                                            >
                                        </h4>

                                        <a href="{{route('blogDetails',[slug(@$data->description->title), $data->content_id])}}" class="read-more"
                                        >@lang('read more')
                                            <i class="fal fa-long-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </section>
    @endif
@endif

