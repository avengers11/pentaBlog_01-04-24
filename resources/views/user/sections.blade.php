@extends('user.layout')
@php
    $userDefaultLang = \App\Models\User\Language::where([
    ['user_id',\Illuminate\Support\Facades\Auth::id()],
    ['is_default',1]
    ])->first();
    $userLanguages = \App\Models\User\Language::where('user_id',\Illuminate\Support\Facades\Auth::id())->get();
    $permissions = \App\Http\Helpers\UserPermissionHelper::packagePermission(Auth::id());
    $permissions = json_decode($permissions, true);
@endphp

@includeIf('user.partials.rtl-style')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{__('Section Customization')}}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{route('admin.dashboard')}}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{__('Home Page')}}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{__('Section Customization')}}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form class="" action="{{route('user.sections.update')}}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-title">{{__('Customize Sections')}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-5 pb-5">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3">
                                @csrf
                                <div class="form-group">
                                    <label>{{__('Introduction Section')}} **</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="intro_section" value="1"
                                                   class="selectgroup-input" {{ isset($sections->intro_section) && $sections->intro_section == 1 ? 'checked' : ''}}>
                                            <span class="selectgroup-button">{{__('Active')}}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="intro_section" value="0"
                                                   class="selectgroup-input" {{ !isset($sections->intro_section) || $sections->intro_section == 0 ? 'checked' : ''}}>
                                            <span class="selectgroup-button">{{__('Deactive')}}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{__('Portfolio Section')}} **</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="portfolio_section" value="1"
                                                   class="selectgroup-input" {{isset($sections->portfolio_section) && $sections->portfolio_section == 1 ? 'checked' : ''}}>
                                            <span class="selectgroup-button">{{__('Active')}}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="portfolio_section" value="0"
                                                   class="selectgroup-input" {{!isset($sections->portfolio_section) || $sections->portfolio_section == 0 ? 'checked' : ''}}>
                                            <span class="selectgroup-button">{{__('Deactive')}}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{__('Featured Services Section')}} **</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="featured_services_section" value="1"
                                                   class="selectgroup-input" {{isset($sections->featured_services_section) && $sections->featured_services_section == 1 ? 'checked' : ''}}>
                                            <span class="selectgroup-button">{{__('Active')}}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="featured_services_section" value="0"
                                                   class="selectgroup-input" {{!isset($sections->featured_services_section) || $sections->featured_services_section == 0 ? 'checked' : ''}}>
                                            <span class="selectgroup-button">{{__('Deactive')}}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{__('Why Choose Us Section')}} **</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="why_choose_us_section" value="1"
                                                   class="selectgroup-input" {{isset($sections->why_choose_us_section) && $sections->why_choose_us_section == 1 ? 'checked' : ''}}>
                                            <span class="selectgroup-button">{{__('Active')}}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="why_choose_us_section" value="0"
                                                   class="selectgroup-input" {{!isset($sections->why_choose_us_section) || $sections->why_choose_us_section == 0 ? 'checked' : ''}}>
                                            <span class="selectgroup-button">{{__('Deactive')}}</span>
                                        </label>
                                    </div>
                                </div>

                                @if (!empty($permissions) && in_array('Counter Information', $permissions))
                                <div class="form-group">
                                    <label>{{__('Counter Info Section')}} **</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="counter_info_section" value="1"
                                                   class="selectgroup-input" {{isset($sections->counter_info_section) && $sections->counter_info_section == 1 ? 'checked' : ''}}>
                                            <span class="selectgroup-button">{{__('Active')}}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="counter_info_section" value="0"
                                                   class="selectgroup-input" {{!isset($sections->counter_info_section) || $sections->counter_info_section == 0 ? 'checked' : ''}}>
                                            <span class="selectgroup-button">{{__('Deactive')}}</span>
                                        </label>
                                    </div>
                                </div>
                                @endif

                                <div class="form-group">
                                    <label>{{__('Video Section')}} **</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="video_section" value="1"
                                                   class="selectgroup-input" {{isset($sections->video_section) && $sections->video_section == 1 ? 'checked' : ''}}>
                                            <span class="selectgroup-button">{{__('Active')}}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="video_section" value="0"
                                                   class="selectgroup-input" {{!isset($sections->video_section) || $sections->video_section == 0 ? 'checked' : ''}}>
                                            <span class="selectgroup-button">{{__('Deactive')}}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{__('Team Members Section')}} **</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="team_members_section" value="1"
                                                   class="selectgroup-input" {{isset($sections->team_members_section) && $sections->team_members_section == 1 ? 'checked' : ''}}>
                                            <span class="selectgroup-button">{{__('Active')}}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="team_members_section" value="0"
                                                   class="selectgroup-input" {{!isset($sections->team_members_section) || $sections->team_members_section == 0 ? 'checked' : ''}}>
                                            <span class="selectgroup-button">{{__('Deactive')}}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{__('Skills Section')}} **</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="skills_section" value="1"
                                                   class="selectgroup-input" {{isset($sections->skills_section) && $sections->skills_section == 1 ? 'checked' : ''}}>
                                            <span class="selectgroup-button">{{__('Active')}}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="skills_section" value="0"
                                                   class="selectgroup-input" {{!isset($sections->skills_section) || $sections->skills_section == 0 ? 'checked' : ''}}>
                                            <span class="selectgroup-button">{{__('Deactive')}}</span>
                                        </label>
                                    </div>
                                </div>
                                @if (!empty($permissions) && in_array('Testimonial', $permissions))
                                <div class="form-group">
                                    <label>{{__('Testimonial Section')}} **</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="testimonials_section" value="1"
                                                   class="selectgroup-input" {{isset($sections->testimonials_section) && $sections->testimonials_section == 1 ? 'checked' : ''}}>
                                            <span class="selectgroup-button">{{__('Active')}}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="testimonials_section" value="0"
                                                   class="selectgroup-input" {{!isset($sections->testimonials_section) || $sections->testimonials_section == 0 ? 'checked' : ''}}>
                                            <span class="selectgroup-button">{{__('Deactive')}}</span>
                                        </label>
                                    </div>
                                </div>
                                @endif
                                <div class="form-group">
                                    <label>{{__('Blogs Section')}} **</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="blogs_section" value="1"
                                                   class="selectgroup-input" {{isset($sections->blogs_section) && $sections->blogs_section == 1 ? 'checked' : ''}}>
                                            <span class="selectgroup-button">{{__('Active')}}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="blogs_section" value="0"
                                                   class="selectgroup-input" {{!isset($sections->blogs_section) || $sections->blogs_section == 0 ? 'checked' : ''}}>
                                            <span class="selectgroup-button">{{__('Deactive')}}</span>
                                        </label>
                                    </div>
                                </div>
                                @if(isset($userBs->theme) && $userBs->theme === "home_three")
                                    <div class="form-group">
                                        <label>{{__('FAQ Section')}} **</label>
                                        <div class="selectgroup w-100">
                                            <label class="selectgroup-item">
                                                <input type="radio" name="faq_section" value="1"
                                                       class="selectgroup-input" {{isset($sections->faq_section) && $sections->faq_section == 1 ? 'checked' : ''}}>
                                                <span class="selectgroup-button">{{__('Active')}}</span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="radio" name="faq_section" value="0"
                                                       class="selectgroup-input" {{!isset($sections->faq_section) || $sections->faq_section == 0 ? 'checked' : ''}}>
                                                <span class="selectgroup-button">{{__('Deactive')}}</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>{{__('Contact Section')}} **</label>
                                        <div class="selectgroup w-100">
                                            <label class="selectgroup-item">
                                                <input type="radio" name="contact_section" value="1"
                                                       class="selectgroup-input" {{isset($sections->contact_section) && $sections->contact_section == 1 ? 'checked' : ''}}>
                                                <span class="selectgroup-button">{{__('Active')}}</span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="radio" name="contact_section" value="0"
                                                       class="selectgroup-input" {{!isset($sections->contact_section) || $sections->contact_section == 0 ? 'checked' : ''}}>
                                                <span class="selectgroup-button">{{__('Deactive')}}</span>
                                            </label>
                                        </div>
                                    </div>
                                @endif
                                @if(isset($userBs->theme) &&($userBs->theme === "home_three" || $userBs->theme === "home_two"))
                                    <div class="form-group">
                                        <label>{{__('Work Process Section')}} **</label>
                                        <div class="selectgroup w-100">
                                            <label class="selectgroup-item">
                                                <input type="radio" name="work_process_section" value="1"
                                                       class="selectgroup-input" {{isset($sections->work_process_section) && $sections->work_process_section == 1 ? 'checked' : ''}}>
                                                <span class="selectgroup-button">{{__('Active')}}</span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="radio" name="work_process_section" value="0"
                                                       class="selectgroup-input" {{!isset($sections->work_process_section) || $sections->work_process_section == 0 ? 'checked' : ''}}>
                                                <span class="selectgroup-button">{{__('Deactive')}}</span>
                                            </label>
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label>{{__('Brands Section')}} **</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="brand_section" value="1"
                                                   class="selectgroup-input" {{isset($sections->brand_section) && $sections->brand_section == 1 ? 'checked' : ''}}>
                                            <span class="selectgroup-button">{{__('Active')}}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="brand_section" value="0"
                                                   class="selectgroup-input" {{!isset($sections->brand_section) || $sections->brand_section == 0 ? 'checked' : ''}}>
                                            <span class="selectgroup-button">{{__('Deactive')}}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{__('Top Footer Section')}} **</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="top_footer_section" value="1"
                                                   class="selectgroup-input" {{isset($sections->top_footer_section) && $sections->top_footer_section == 1 ? 'checked' : ''}}>
                                            <span class="selectgroup-button">{{__('Active')}}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="top_footer_section" value="0"
                                                   class="selectgroup-input" {{!isset($sections->top_footer_section) || $sections->top_footer_section == 0 ? 'checked' : ''}}>
                                            <span class="selectgroup-button">{{__('Deactive')}}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{__('Copyright Section')}} **</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="copyright_section" value="1"
                                                   class="selectgroup-input" {{isset($sections->copyright_section) && $sections->copyright_section == 1 ? 'checked' : ''}}>
                                            <span class="selectgroup-button">{{__('Active')}}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="copyright_section" value="0"
                                                   class="selectgroup-input" {{!isset($sections->copyright_section) || $sections->copyright_section == 0 ? 'checked' : ''}}>
                                            <span class="selectgroup-button">{{__('Deactive')}}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form">
                            <div class="form-group from-show-notify row">
                                <div class="col-12 text-center">
                                    <button type="submit" id="displayNotif" class="btn btn-success">{{__('Update')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
