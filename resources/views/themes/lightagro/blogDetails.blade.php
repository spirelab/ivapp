@extends($theme.'layouts.app')
@section('title',trans('Blog Details'))

@section('content')

    <!-- blog details  -->
    <section class="blog-section blog-details">
        <div class="container">
            <div class="row g-lg-5 g-4">
                <div class="col-lg-8">
                    <div class="blog-box">
                        <div class="img-box">
                            <img src="{{$singleItem['image']}}" class="img-fluid" alt="">
                            <div class="date">
                                <span>{{$singleItem['date']}}</span>
                            </div>
                        </div>
                        <div class="text-box">
                            <div class="date-author">
                                <span><i class="fal fa-user-circle text-secondary"></i> @lang('Admin') </span>
                            </div>
                            <h4 class="blog-title">@lang($singleItem['title'])</h4>
                            <p>
                                @lang($singleItem['description'])
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="side-bar">
                        <div class="side-box">
                            <h4>@lang('Recent Blogs')</h4>
                            @foreach($popularContentDetails['blog']->sortDesc() as $data)
                                <div class="side-blog-box">
                                    <div class="img-box">
                                        <img class="img-fluid" src="{{getFile(config('location.content.path').'thumb_'.@$data->content->contentMedia->description->image)}}" alt="">
                                    </div>
                                    <div class="text-box">
                                        <a href="{{route('blogDetails',[slug($data->description->title), $data->content_id])}}" class="title"> {{\Str::limit($data->description->title,40)}} </a>
                                        <span class="date">{{dateTime($data->created_at)}}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
