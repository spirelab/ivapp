@if(isset($templates['blog'][0]) && $blog = $templates['blog'][0])

        <!-- blog section -->
        <section class="blog-section">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="header-text text-center">
                            <h5>@lang(@$blog->description->title)</h5>
                            <h2>@lang(@$blog->description->sub_title)</h2>
                            <p class="mx-auto">
                                @lang(@$blog->description->short_title)
                            </p>
                        </div>
                    </div>
                </div>

                @if(isset($contentDetails['blog']))
                    <div class="row justify-content-center g-4">
                        @foreach($contentDetails['blog']->take(3)->sortDesc() as $k => $data)
                            <div class="col-lg-4 col-md-6">
                                <div class="blog-box"
                                     data-aos-anchor-placement="top-center">
                                    <div class="img-box">
                                        <img src="{{getFile(config('location.content.path').'thumb_'.@$data->content->contentMedia->description->image)}}" class="img-fluid" alt=""/>
                                        <div class="date">
                                            <span>{{dateTime(@$data->created_at,'d M, Y')}}</span>
                                        </div>
                                    </div>
                                    <div class="text-box">
                                        <div class="date-author">
                                            <span><i class="fal fa-user-circle text-secondary"></i> @lang('Admin') </span>
                                        </div>
                                        <h4>
                                            <a href="{{route('blogDetails',[slug(@$data->description->title), $data->content_id])}}" class="blog-title">{{\Illuminate\Support\Str::limit(@$data->description->title,60)}}</a>
                                        </h4>
                                        <p> @lang(\Illuminate\Support\Str::limit(strip_tags(@$data->description->description), 120))</p>
                                        <a href="{{route('blogDetails',[slug(@$data->description->title), $data->content_id])}}" class="read-more"
                                        >@lang('Read More')
                                            <i class="fal fa-long-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

@endif
