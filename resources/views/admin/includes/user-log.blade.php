
@php
    if (!isset($data)) {
        $data = collect([]);
    }
@endphp

<span class="btn btn-info btn-{{ isset($icon_size) ? $icon_size : 'sm' }} popover-success"
      data-toggle="popover"
      {{-- data-trigger="hover" --}}
      data-placement="left"
      title="<i class='ace-icon fa fa-info-circle text-success'></i> Log Information"
      data-content="<p>Created By: {{ ucwords(optional($data->created_user)->name) ?? 'System Admin' }}.</p> <p> Created At : {{ fdate($data->created_at, 'Y-m-d  h:i A') }} </p>
       <hr/>
       <p>Updated By: {{ ucwords(optional($data->updated_user)->name) ?? 'System Admin' }}.</p> <p> Updated At : {{ fdate($data->updated_at, 'Y-m-d  h:i A') }} </p>">
    <i class="fa fa-info-circle"></i>
</span>
