@extends('theme-views.layouts.app')

@section('title', translate('My_Support_Tickets').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))

@section('content')
    <!-- Main Content -->
    <main class="main-content d-flex flex-column gap-3 py-3 mb-4">
        <div class="container">
            <div class="row g-3">
                <!-- Sidebar-->
                @include('theme-views.partials._profile-aside')
                <div class="col-lg-9">
                    <div class="card h-100 profilecard">
                        <div class="card-body d-flex flex-column gap-3 p-2 p-sm-4">
                            <div class="d-flex left gap-2 justify-content-between">
                                <div class="media gap-3"><h4>{{translate('support_&_tickets')}}</h4></div>
                                <button class="btn btn-primary rounded-pill px-2 py-0 px-sm-4 py-sm-2" data-bs-toggle="modal" data-bs-target="#reviewModal">{{translate('create_support_tickets')}}</button>
                            </div>
                            @foreach($supportTickets as $key=>$supportTicket)
                            <div class="bg__ticketcolor br-12">
                                <div class="border-bottom support-ticket-row border-grey p-3 d-flex justify-content-between">
                                    <div class="media gap-2 gap-sm-3 d-flex">
                                        <div class="avatar wp-30 rounded-circle">
                                            <img onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                 src="{{asset('storage/app/public/profile')}}/{{\App\CPU\customer_info()->image}}"
                                                 loading="lazy" class="img-fit dark-support rounded-circle" alt="" style="width:100%">
                                        </div>
                                        <div class="media-body">
                                            <div class="d-flex flex-column gap-1">
                                                <div class="media align-items-start justify-content-between">
                                                    <div class="media-body">
                                                        <a href="{{route('support-ticket.index',$supportTicket['id'])}}">
                                                            <h6 class="mb-0">{{ \App\CPU\customer_info()->f_name }}&nbsp{{ \App\CPU\customer_info()->l_name }}</h6>
                                                        </a>
                                                        <div class="fs-12 text-muted mb-3">{{ \App\CPU\customer_info()->email }}</div>
                                                        <div class="d-flex flex-wrap align-items-center gap-2 gap-sm-3 font__set__badge">
                                                    <span
                                                    @if($supportTicket->priority == 'Urgent')
                                                        class="badge rounded-pill bg-danger"
                                                    @elseif($supportTicket->priority == 'High')
                                                        class="badge rounded-pill bg-warning"
                                                    @elseif($supportTicket->priority == 'Medium')
                                                        class="badge rounded-pill bg-info"
                                                    @else
                                                        class="badge rounded-pill bg-success"
                                                        @endif
                                                    >
                                                        {{ $supportTicket->priority }}</span>
                                                    <span class="{{$supportTicket->status ==  'open' ? 'badge bg-info' : 'badge bg-danger'}} rounded-pill">{{ $supportTicket->status }}</span>
                                                    <span class="badge bg-white text-dark">{{ $supportTicket->type }}</span>
                                                </div>
                                                    </div>
                                            
                                                </div>

                                              
                                            </div>
                                        </div>
                                    </div>
                                    <!-- @if($supportTicket->status != 'close')
                                    <a href="{{route('support-ticket.close',[$supportTicket['id']])}}" class="btn btn-outline-danger fw-semibold">{{ translate('Close_ticket') }}</a>


                                    @endif -->


                                     <div class="">
                                     @if($supportTicket->status != 'close')
                                                        <a href="{{route('support-ticket.close',[$supportTicket['id']])}}" class="btn btn-outline-danger fw-semibold text-nowrap">{{ translate('Close_ticket') }}</a>
                                                    @endif
                                     </div>
                                </div>

                                <div class="d-flex flex-wrap justify-content-between gap-2 p-3">
                                    <h6 class="text-truncate " style="--width: 60ch">{{ $supportTicket->subject }}</h6>
                                    <div class="fs-12">{{date('d M, Y H:i A',strtotime($supportTicket->created_at))}}</div>
                                </div>
                            </div>
                            @endforeach
                            @if($supportTickets->count()==0)

                                        <div class="col-lg-12">
                                            <div class="empty-content">
                                                <div>                                    
                                                    <img src="{{ theme_asset('assets/images/headphone1.png')}}" alt="">
                                                    <!-- <svg width="211" height="180" viewBox="0 0 211 180" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <g id="Group 2993">
                                                    <g id="empty address">
                                                    <g id="Group 2956">
                                                    <g id="Group">
                                                    <g id="Group_2">
                                                    <path id="Vector" d="M183.985 35.2754H123.765C122.521 35.2754 121.505 36.2835 121.505 37.5346C121.505 38.7787 122.513 39.7939 123.765 39.7939H183.985C185.229 39.7939 186.244 38.7858 186.244 37.5346C186.237 36.2906 185.229 35.2754 183.985 35.2754Z" fill="#E3E1EC"/>
                                                    <path id="Vector_2" d="M113.712 35.2754H98.5127C97.2687 35.2754 96.2534 36.2835 96.2534 37.5346C96.2534 38.7787 97.2615 39.7939 98.5127 39.7939H113.712C114.957 39.7939 115.972 38.7858 115.972 37.5346C115.972 36.2906 114.957 35.2754 113.712 35.2754Z" fill="#E3E1EC"/>
                                                    </g>
                                                    <g id="Group_3">
                                                    <path id="Vector_3" d="M186.38 175.191H126.16C124.916 175.191 123.901 176.199 123.901 177.451C123.901 178.695 124.909 179.71 126.16 179.71H186.38C187.624 179.71 188.64 178.702 188.64 177.451C188.633 176.199 187.624 175.191 186.38 175.191Z" fill="#E3E1EC"/>
                                                    <path id="Vector_4" d="M116.108 175.191H100.908C99.6641 175.191 98.6489 176.199 98.6489 177.451C98.6489 178.695 99.657 179.71 100.908 179.71H116.108C117.352 179.71 118.367 178.702 118.367 177.451C118.367 176.199 117.352 175.191 116.108 175.191Z" fill="#E3E1EC"/>
                                                    </g>
                                                    <g id="Group_4">
                                                    <path id="Vector_5" d="M1.60864 161.514H44.4699C45.3564 161.514 46.0786 160.792 46.0786 159.906C46.0786 159.019 45.3564 158.297 44.4699 158.297H1.60864C0.7221 158.297 0 159.019 0 159.906C0 160.799 0.7221 161.514 1.60864 161.514Z" fill="#E3E1EC"/>
                                                    <path id="Vector_6" d="M51.6267 161.514H62.4439C63.3305 161.514 64.0526 160.792 64.0526 159.906C64.0526 159.019 63.3305 158.297 62.4439 158.297H51.6267C50.7402 158.297 50.0181 159.019 50.0181 159.906C50.0181 160.799 50.7402 161.514 51.6267 161.514Z" fill="#E3E1EC"/>
                                                    </g>
                                                    <g id="Group_5">
                                                    <path id="Vector_7" d="M148.031 169.694H190.892C191.778 169.694 192.5 168.972 192.5 168.085C192.5 167.199 191.778 166.477 190.892 166.477H148.031C147.144 166.477 146.422 167.199 146.422 168.085C146.422 168.972 147.137 169.694 148.031 169.694Z" fill="#E3E1EC"/>
                                                    <path id="Vector_8" d="M198.048 169.694H208.865C209.752 169.694 210.474 168.972 210.474 168.085C210.474 167.199 209.752 166.477 208.865 166.477H198.048C197.162 166.477 196.439 167.199 196.439 168.085C196.439 168.972 197.154 169.694 198.048 169.694Z" fill="#E3E1EC"/>
                                                    </g>
                                                    <g id="Group_6">
                                                    <g id="Group_7">
                                                    <path id="Vector_9" d="M102.352 0H72.2453C71.6233 0 71.1157 0.507616 71.1157 1.12962C71.1157 1.75163 71.6233 2.25925 72.2453 2.25925H102.352C102.974 2.25925 103.482 1.75163 103.482 1.12962C103.482 0.507616 102.981 0 102.352 0Z" fill="#E3E1EC"/>
                                                    <path id="Vector_10" d="M67.2193 0H59.6194C58.9974 0 58.4897 0.507616 58.4897 1.12962C58.4897 1.75163 58.9974 2.25925 59.6194 2.25925H67.2193C67.8413 2.25925 68.3489 1.75163 68.3489 1.12962C68.3489 0.507616 67.8413 0 67.2193 0Z" fill="#E3E1EC"/>
                                                    </g>
                                                    </g>
                                                    <g id="Group_8">
                                                    <path id="Vector_11" d="M81.8402 165.354H51.7336C51.1116 165.354 50.604 165.861 50.604 166.483C50.604 167.105 51.1116 167.613 51.7336 167.613H81.8402C82.4622 167.613 82.9698 167.105 82.9698 166.483C82.9698 165.861 82.4622 165.354 81.8402 165.354Z" fill="#E3E1EC"/>
                                                    <path id="Vector_12" d="M46.7076 165.354H39.1076C38.4856 165.354 37.978 165.861 37.978 166.483C37.978 167.105 38.4856 167.613 39.1076 167.613H46.7076C47.3296 167.613 47.8372 167.105 47.8372 166.483C47.8372 165.861 47.3296 165.354 46.7076 165.354Z" fill="#E3E1EC"/>
                                                    </g>
                                                    </g>
                                                    </g>
                                                    </g>
                                                    <g id="Group 2992">
                                                    <path id="Vector_13" d="M143.126 111.362C143.144 109.092 142.424 107.076 141.172 105.241C139.913 103.393 138.249 102.076 135.961 101.804C135.28 101.722 135.154 101.434 135.149 100.835C135.131 98.7653 135.188 96.6984 135.023 94.6264C134.727 90.8886 133.73 87.3616 132.08 84.0222C130.56 80.9502 128.517 78.2587 126.02 75.891C122.252 72.3177 117.856 69.9552 112.827 68.7161C111.403 68.3665 109.953 68.3537 108.519 68.166C106.82 68.166 105.123 68.166 103.424 68.166C103.334 68.4077 103.113 68.3203 102.946 68.3408C99.3621 68.7676 95.9456 69.7573 92.7759 71.4951C84.7449 75.8936 79.7834 82.5852 77.9248 91.5801C77.2847 94.683 77.4184 97.8218 77.4389 100.956C77.4441 101.742 77.2847 101.997 76.4518 101.961C72.0687 101.768 68.6548 105.426 68.4337 109.416C68.1638 114.269 68.2229 119.146 68.408 124.005C68.5545 127.807 71.8579 131.002 75.6626 131.169C78.205 131.282 80.7552 131.221 83.3028 131.221C84.7912 131.221 85.4647 130.516 85.4647 129.002C85.4647 120.92 85.4647 112.84 85.4622 104.758C85.4622 104.439 85.4673 104.115 85.421 103.804C85.2385 102.57 84.511 101.948 83.2693 101.943C82.8195 101.94 82.367 101.927 81.9171 101.948C81.5907 101.963 81.4364 101.884 81.4416 101.513C81.4827 98.2743 81.2642 95.0249 81.9686 91.8218C84.8658 78.6597 97.2849 70.1892 110.524 72.6596C120.37 74.4977 126.769 80.5055 129.866 90.0377C130.91 93.2511 130.905 96.5751 130.853 99.899C130.843 100.555 131.139 101.39 130.72 101.817C130.334 102.213 129.509 101.914 128.879 101.935C127.946 101.966 127.419 102.315 127.062 103.19C126.856 103.691 126.825 104.215 126.825 104.753C126.83 112.835 126.828 120.915 126.828 128.997C126.828 130.509 127.542 131.2 129.08 131.226C129.663 131.236 130.26 131.136 130.866 131.295C128.65 134.922 123.388 137.75 118.756 137.892C118.141 137.91 117.91 137.75 117.745 137.154C116.612 133.072 112.905 130.105 108.755 129.904C103.406 129.647 99.051 133.339 98.2515 138.812C97.5651 143.498 101.028 148.334 105.917 149.552C106.149 149.609 106.462 149.537 106.606 149.833C107.509 149.833 108.411 149.833 109.313 149.833C110.002 149.663 110.725 149.73 111.411 149.501C114.38 148.511 116.619 146.642 117.624 143.676C118.09 142.306 118.817 142.051 120.031 141.917C126.452 141.208 131.483 138.154 135.01 132.709C135.625 131.761 136.329 131.213 137.488 130.989C141.134 130.28 143.247 127.318 143.147 123.879C143.023 119.709 143.093 115.534 143.126 111.362ZM81.4596 106.58C81.439 109.925 81.4493 113.272 81.4493 116.616C81.4493 119.909 81.4364 123.2 81.4621 126.493C81.4673 127.033 81.3619 127.221 80.7835 127.203C79.2487 127.156 77.7089 127.208 76.1742 127.177C74.0842 127.133 72.3926 125.444 72.3823 123.352C72.3618 118.997 72.3566 114.642 72.3875 110.29C72.4055 107.637 74.1536 105.968 76.8091 105.968C77.5238 105.968 78.241 105.968 78.9557 105.968C79.5906 105.968 80.2308 106.007 80.8631 105.956C81.3747 105.917 81.4621 106.11 81.4596 106.58ZM108.331 145.794C105.102 145.833 102.288 143.072 102.228 139.807C102.172 136.609 104.871 133.892 108.126 133.874C111.501 133.853 114.082 136.475 114.11 139.953C114.139 143.123 111.534 145.756 108.331 145.794ZM138.92 124.141C138.913 125.892 137.72 127.02 135.684 127.149C134.231 127.241 132.769 127.161 131.314 127.195C130.715 127.208 130.866 126.809 130.866 126.498C130.861 123.179 130.864 119.861 130.864 116.542C130.864 113.249 130.877 109.958 130.851 106.665C130.846 106.125 130.961 105.925 131.537 105.953C132.622 106.002 133.709 105.994 134.797 105.958C135.835 105.925 136.648 106.341 137.303 107.1C138.306 108.262 138.897 109.627 138.91 111.156C138.951 115.485 138.941 119.814 138.92 124.141Z" fill="#0A9494"/>
                                                    </g>
                                                    </g>
                                                    </svg> -->

                                                    <h3>{{translate('Not_Found_Anything')}}</h3>
                                                    <!-- <a href="{{route('account-address-add')}}" class="btn-login for-empty">{{translate('Add_New_Address')}}</a> -->
                                                </div>
                                           </div>
                                        </div>








                                <!-- <h5 class="text-center">{{translate('not_found_anything')}}</h5> -->
                            @endif

                            <div class="border-0">
                                {{$supportTickets->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- End Main Content -->

    <!-- Review Modal -->
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header px-sm-5">
                    <h1 class="modal-title fs-5" id="reviewModalLabel">{{translate('submit_new_ticket')}}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="p-3 px-sm-5">
                    <span>{{translate('you_will_get_response')}}.</span>
                </div>
                <div class="modal-body px-sm-5">
                    <form action="{{route('ticket-submit')}}" id="open-ticket" method="post">
                        @csrf
                        <div class="form-group mb-4">
                            <label for="rating">{{ translate('Subject') }}</label>
                            <input type="text" class="form-control" id="ticket-subject" name="ticket_subject" required>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6 mb-4">
                                <label for="rating">{{ translate('Type') }}</label>
                                <select id="ticket-type" name="ticket_type" class="form-select" required>
                                    <option value="Website problem">{{translate('Website')}} {{translate('problem')}}</option>
                                    <option value="Partner request">{{translate('partner_request')}}</option>
                                    <option value="Complaint">{{translate('Complaint')}}</option>
                                    <option value="Info inquiry">{{translate('Info')}} {{translate('inquiry')}} </option>
                                </select>
                            </div>
                            <div class="form-group col-md-6 mb-4">
                                <label for="rating">{{ translate('Priority') }}</label>
                                <select id="ticket-priority" name="ticket_priority" class="form-select" required>
                                    <option value>{{translate('choose_priority')}}</option>
                                    <option value="Urgent">{{translate('Urgent')}}</option>
                                    <option value="High">{{translate('High')}}</option>
                                    <option value="Medium">{{translate('Medium')}}</option>
                                    <option value="Low">{{translate('Low')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <label for="comment">{{translate('describe_your_issue')}}</label>
                            <textarea class="form-control" rows="6" id="ticket-description" name="ticket_description" placeholder="{{translate('Leave_your_issue')}}"></textarea>
                        </div>
                        <div class="modal-footer gap-3 pb-4 px-sm-5">
                            <button type="button" class="btn btn-secondary m-0" data-bs-dismiss="modal">{{translate('Back')}}</button>
                            <button type="submit" class="btn btn-primary m-0">{{ translate('Submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

