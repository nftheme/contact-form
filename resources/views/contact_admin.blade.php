<div id="nf-theme-contact">
    @if($should_flash)
        <div class="alert alert-success" role="alert">
          <strong>Well done!</strong> Options are saved successfully.
        </div>
    @endif
    <div class="nto-header">
        <h4 class="nto-title bd-title">Form Manager</h4>
        <ul class="nto-tabs nav nav-tabs">
            @foreach($pages as $page)
            <li class="nto-item nav-item">
                <a class="{{ $manager->isPage($page->name) ? 'nto-menu-link-link nav-link active' : 'nto-menu-link-link nav-link' }}" href="{{$manager->getTabUrl($page->name)}}">{{$page->name}}</a>
            </li>
            @endforeach
        </ul>
    </div>
    <div class="nto-content">
        <form id="contactfilter" method="get" action="">
            <input type="hidden" name="page" value="{{ $param_page }}">
            <input type="hidden" name="tab" value="{{ $name_tab }}">
            <div class="col-xs-4 actions">
                <input type="button" class="button export-btn" data-page="{{ $param_page }}" data-name="{{ $name_tab }}" value="{!! __('Export', 'contactmodule') !!}">
                <select class="custom-select-filter" name="statusfilter">
                    <option value="-1">All</option>
                    @foreach($list_status as $key => $item)
                        <option value="{{ $item['id'] }}" {!! (!empty($statusfilter) && ($item['id'] == $statusfilter)) ? 'selected' : '' !!}>{!! $item['name'] !!}</option>
                    @endforeach
                </select>
                <input type="submit" class="button" value="{!! __('filter', 'contactmodule') !!}">
            </div>
        </form>
        <?php 
        if($enable):
        ?>
        <div class="col-xs-12 actions">
            <select class="html_template_inp custom-select-filter" name="template_html">
                <option value="">Choose html template</option>
                <?php 
                if(!empty($template_email)) {
                    foreach ($template_email as $key => $item) {
                        echo '<option value="' . $item['path'] . '">' . $item['name'] . '</option>';
                    }
                }
                ?>
            </select>
            <input type="text" class="subject inline-element subject-inp" name="subject" placeholder="subject email">
            <input type="button" data-page="{{ $param_page }}" data-name="{{ $name_tab }}" class="button send_email_single" name="send_email" value="Send to selected email">
            <input type="button" class="button send_email_all" data-page="{{ $param_page }}" data-name="{{ $name_tab }}" name="send_email_all" value="Send all">
        </div>
        <?php  
        endif;
        ?>
        <table class="table table-bordered table-striped contact-module-table">
            <thead>
                <tr>
                    <th><input type="checkbox" name="all" class="all-inp" value="-1"></th>
                    <th>{!! __('Status', 'contactmodule') !!}</th>
                    @foreach($current_page->fields as $field)
                        @if($field->type !== 'submit') 
                            <th>{!! $field->name !!}</th>
                        @endif
                    @endforeach
                    <th>{!! __('Created date', 'contactmodule') !!}</th>
                    <th>{!! __('Updated date', 'contactmodule') !!}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @if($contact_data)
                    @foreach($contact_data as $key => $row)
                    @php
                        $records = json_decode($row->data);
                    @endphp
                        <tr>
                            <th scope="row"><input type="checkbox" name="id_contact_row[]" class="id_contact_row" data-id="{!! $row->id !!}"></th>
                            <td>
                                <select class="custom-select mr-sm-2" id="inlineFormCustomSelect" attr-id="{!! $row->id !!}">
                                    @foreach($list_status as $key => $item)
                                        <option value="{{ $item['id'] }}" {!! ($row->status == $item['id']) ? 'selected' : '' !!}>{!! $item['name'] !!}</option>
                                    @endforeach
                                </select>
                            </td>
                            @foreach($current_page->fields as $field)
                                @if($field->type !== 'submit') 
                                <td>
                                @foreach($records as $key_record => $record)
                                    {!! ($field->name == $key_record) ? $record : '' !!}
                                @endforeach
                                </td>
                                @endif
                            @endforeach
                            <td>{{ $row->created }}</td>
                            <td>{{ $row->updated }}</td>
                            <td>
                                <button class="btn btn-danger delete-item nopadding" id="{{ $row->id }}"><span class="dashicons dashicons-no-alt"></span></button>
                            </td>
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
                    'total_page' => $total_page
                ];
            @endphp
            {!! \Vicoders\ContactForm\Facades\View::render('pagination.default', $data) !!}
        </div>
    </div>
</div>
