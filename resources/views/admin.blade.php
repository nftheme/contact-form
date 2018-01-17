<div id="nf-theme-contact">
    @if($should_flash)
        <div class="alert alert-success" role="alert">
          <strong>Well done!</strong> Options are saved successfully.
        </div>
    @endif
    <div class="nto-header">
        <h4 class="nto-title bd-title">Contact Manager</h4>
        <ul class="nto-tabs nav nav-tabs">
            @foreach($pages as $page)
            <li class="nto-item nav-item">
                <a class="{{ $manager->isPage($page->name) ? 'nto-menu-link-link nav-link active' : 'nto-menu-link-link nav-link' }}" href="{{$manager->getTabUrl($page->name)}}">{{$page->name}}</a>
            </li>
            @endforeach
        </ul>
    </div>
    <div class="nto-content">
        <table class="table table-bordered table-striped contact-module-table">
            <thead>
                <tr>
                    <th>#</th>
                    @foreach($current_page->fields as $field)
                    <th>{!! $field->name !!}</th>
                    @endforeach
                    <th>{!! __('Contact Status', 'contactmodule') !!}</th>
                    <th>{!! __('Created at', 'contactmodule') !!}</th>
                    <th>{!! __('Updated at', 'contactmodule') !!}</th>
                </tr>
            </thead>
            <tbody>
                @if($contact_data)
                    @foreach($contact_data as $key => $row)
                    @php
                        $records = json_decode($row->data);
                    @endphp
                        <tr>
                            <th scope="row">{!! $row->id !!}</th>
                            @foreach($current_page->fields as $field)
                                <td>
                                @foreach($records as $key_record => $record)
                                    {!! ($field->name == $key_record) ? $record : '' !!}
                                @endforeach
                                </td>
                            @endforeach
                            <td>
                                @if($row->status == $status_active) 
                                   <p class="text-success">{!! __('Contacted', 'contactmodule') !!}</p>
                                @elseif ($row->status == $status_deactive)
                                   <p class="text-warning">{!! __('Not contacted', 'contactmodule') !!}</p>
                                @else 
                                   <p class="text-danger">{!! __('Cancel', 'contactmodule') !!}</p>
                                @endif
                            </td>
                            <td>{{ $row->created_at }}</td>
                            <td>{{ $row->updated_at }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <div class="paginate">
            @php
                $data = [
                    'paginator'   => $contact_data,
                    'next_page_url' => $next_page_url,
                    'prev_page_url' => $prev_page_url,
                    'page_query_param' => $page_query_param,
                    'total' => $total,
                ];
            @endphp
            {{ view('vendor.option.pagination.default', $data) }}
        </div>
    </div>
</div>
