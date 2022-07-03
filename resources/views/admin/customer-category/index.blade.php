@extends('admin.master')
@section('title', 'Customer Category')

@section('css')
@endsection

@section('content')
    <main class="app-content">


        @include('partials._alert_message')

        <!-- Add New Modal -->
        @include('admin.customer-category.create-modal')

        @include('admin.customer-category.edit-modal')

        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    @if(hasPermission('customer-category.create'))
                        <button class="btn btn-primary create-category-btn" style="float: right;" data-toggle="modal" data-target="#create-modal">
                            <i class="fa fa-plus"></i> Create New
                        </button>
                    @endif
                    <h3 class="tile-title"><i class="fa fa-list"></i> Customer Category</h3>

                    <br>

                    <div class="tile-body table-responsive" style="height: 740px !important;">
                        <table class="table table-hover table-bordered table-sm" id="sampleTable">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Category Name</th>
                                    <th>Description</th>
                                    <th class="text-right">Amount Of</th>
                                    <th class="text-right">Amount</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($categories as $key => $category)
                                    <tr>
                                        <td>{{ $key + $categories->firstItem() }}</td>
                                        <td class="name">{{ $category->name }}</td>
                                        <td class="description">{{ $category->description }}</td>
                                        <td class="text-right amount-of">{{ $category->amount_of }}</td>
                                        <td class="text-right amount">{{ $category->amount }}</td>
                                        <td class="type">{{ ucwords($category->type) }}</td>
                                        <td>
                                            @if ($category->status)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">De Active</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-corner">
                                                @include('admin.includes.user-log', ['data' => $category])

                                                @if(hasPermission('customer-category.edit'))
                                                    <button class="btn btn-success btn-sm" onclick="loadEditData(this, {{ $category->id }}, {{ $category->status }})" title="Edit" data-toggle="modal" data-target="#edit-modal">
                                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                    </button>
                                                @endif


                                                @if(hasPermission('customer-category.destroy'))
                                                    <button type="button" class="btn btn-danger btn-sm" title="Delete" onclick="delete_item('{{ route('customer-category.destroy', $category->id) }}')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @include('admin.includes.pagination', ['data' => $categories])
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- delete form -->
    @include('partials._delete_form')

@endsection

@section('js')
    <script>

        @if($errors->any() && old('modal-type') == 'create')
            $('.create-category-btn').trigger("click")
        @endif

        function loadEditData(object, id, status) {
            let action_url = $('#edit-form').attr('action')
            $('#edit-form').attr('action', action_url.replace(":id", id))

            $('.edit-name').val($(object).closest('tr').find('.name').text())
            $('.edit-description').val($(object).closest('tr').find('.description').text())
            $('.edit-amount-of').val($(object).closest('tr').find('.amount-of').text())
            $('.edit-amount').val($(object).closest('tr').find('.amount').text())

            if ($(object).closest('tr').find('.type').text() == 'Percent') {
                $('.edit-type-percent').attr('checked', true)
            }

            if (status == 0) {
                $('.edit-status-inactive').attr('checked', true)
            }
        }
    </script>
@endsection
