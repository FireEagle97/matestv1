@extends('backend.layouts.app')

@section('title')
    {{ __($module_action) }} {{ __($module_title) }}
@endsection


@push('after-styles')
    <link rel="stylesheet" href="{{ mix('modules/subscriptions/style.css') }}">
@endpush
@section('content')
    <div class="card-main mb-5">
            <x-backend.section-header>
              <div class="d-flex flex-wrap gap-3">

                <x-backend.quick-action url='{{ route("backend.$module_name.bulk_action") }}'>
                  <div class="">
                    <select name="action_type" class="form-control select2 col-12" id="quick-action-type" style="width:100%">
                        <option value="">{{ __('messages.no_action') }}</option>

                        <option value="change-status">{{ __('messages.lbl_status') }}</option>
                        @hasPermission('delete_planlimitation')
                        <option value="delete">{{ __('messages.delete') }}</option>
                        @endhasPermission
                        @hasPermission('restore_planlimitation')
                        <option value="restore">{{ __('messages.restore') }}</option>
                        @endhasPermission
                        @hasPermission('force_delete_planlimitation')
                        <option value="permanently-delete">{{ __('messages.permanent_dlt') }}</option>
                        @endhasPermission

                    </select>
                  </div>
                  <div class="select-status d-none quick-action-field" id="change-status-action">
                      <select name="status" class="form-control select2" id="status" style="width:100%">
                        <option value="" selected>{{ __('messages.select_status') }}</option>
                        <option value="1">{{ __('messages.active') }}</option>
                        <option value="0">{{ __('messages.inactive') }}</option>
                      </select>
                  </div>
                </x-backend.quick-action>

                <div>
                    <button type="button" class="btn btn-dark" data-modal="export">
                    <i class="ph ph-export align-middle"></i> {{ __('messages.export') }}
                    </button>

                </div>
              </div>

                <x-slot name="toolbar">
                <div>
                        <div class="datatable-filter">
                            <select name="column_status" id="column_status" class="select2 form-control"
                                data-filter="select" style="width: 100%">
                                <option value="">{{__('messages.all')}}</option>
                                <option value="0" {{ $filter['status'] == '0' ? 'selected' : '' }}>
                                    {{ __('messages.inactive') }}</option>
                                <option value="1" {{ $filter['status'] == '1' ? 'selected' : '' }}>
                                    {{ __('messages.active') }}</option>
                            </select>
                        </div>
                    </div>
                  <div class="input-group flex-nowrap">
                    <span class="input-group-text pe-0" id="addon-wrapping"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" class="form-control dt-search" placeholder="{{ __('messages.search') }}..." aria-label="Search" aria-describedby="addon-wrapping">
                  </div>
{{--
                  @hasPermission('add_planlimitation')
                  <a href="{{ route('backend.'. $module_name . '.create') }}" class="btn btn-primary d-flex align-items-center gap-1"
                    id="add-post-button"> <i class="ph ph-plus-circle"></i> {{__('messages.new')}}</a>
                    @endhasPermission --}}


                </x-slot>
            </x-backend.section-header>
            <table id="datatable" class="table table-responsive">
            </table>
    </div>
    @if(session('success'))
<div class="snackbar" id="snackbar">
    <div class="d-flex justify-content-around align-items-center">
        <p class="mb-0">{{ session('success') }}</p>
        <a href="#" class="dismiss-link text-decoration-none text-success" onclick="dismissSnackbar(event)">Dismiss</a>
    </div>
</div>
@endif

    <div data-render="app">


    </div>
@endsection

@push('after-styles')
    <!-- DataTables Core and Extensions -->
    <link rel="stylesheet" href="{{ asset('vendor/datatable/datatables.min.css') }}">
@endpush

@push('after-scripts')
<script src="{{ asset('js/form-modal/index.js') }}" defer></script>
<script src="{{ asset('js/form/index.js') }}" defer></script>

    <!-- DataTables Core and Extensions -->
    <script type="text/javascript" src="{{ asset('vendor/datatable/datatables.min.js') }}"></script>

    <script type="text/javascript" defer>

const columns = [
            {
                name: 'check',
                data: 'check',
                title: '<input type="checkbox" class="form-check-input" name="select_all_table" id="select-all-table" data-type="plan-limitation" onclick="selectAllTable(this)">',
                width: '0%',
                exportable: false,
                orderable: false,
                searchable: false,
            },
            {
                data: 'updated_at',
                name: 'updated_at',
                title: "{{ __('users.lbl_update_at') }}",
                orderable: true,
                visible: false,
            },
            { data: 'title', name: 'title', title: "{{ __('plan_limitation.lbl_title') }}" ,
                render: function(data, type, row, meta) {
                    return '<h6 class="mb-0">' + data + '</h6>';
                },
                orderable: true,
                searchable: true,
             },
            { data: 'status', name: 'status', orderable: true, searchable: true,  title: "{{ __('plan_limitation.lbl_status') }}",  width: '5%'  },
        ]

        const actionColumn = [
            { data: 'action', name: 'action', orderable: false, searchable: false, title: '{{ __('messages.action') }}',  width: '5%' }
        ]


        let finalColumns = [
            ...columns,
            ...actionColumn
        ]

        document.addEventListener('DOMContentLoaded', (event) => {
            initDatatable({
                url: '{{ route("backend.$module_name.index_data") }}',
                finalColumns,
                orderColumn: [
                    [3, "desc"]
                ],
            })
        })







    function resetQuickAction () {
        const actionValue = $('#quick-action-type').val();
        if (actionValue != '') {
            $('#quick-action-apply').removeAttr('disabled');

            if (actionValue == 'change-status') {
                $('.quick-action-field').addClass('d-none');
                $('#change-status-action').removeClass('d-none');
            } else {
                $('.quick-action-field').addClass('d-none');
            }
        } else {
            $('#quick-action-apply').attr('disabled', true);
            $('.quick-action-field').addClass('d-none');
        }
      }

      $('#quick-action-type').change(function () {
        resetQuickAction()
      });

      $(document).on('update_quick_action', function() {
        // resetActionButtons()
      })
    </script>
@endpush
