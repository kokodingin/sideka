@extends('layouts.app')

@section('title', 'Rapat')

@push('links')
    <link rel="stylesheet" href="{{ asset('dist/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('dist/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('dist/libs/quill/dist/quill.snow.css') }}" />

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <!-- Or for RTL support -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />
    <link rel="stylesheet" href="{{ asset('dist/libs/select2/dist/css/select2.min.css') }}" />

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus@6.9.4/dist/css/tempus-dominus.min.css"
        crossorigin="anonymous" />

    <!-- FancyBox -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css"
        integrity="sha512-H9jrZiiopUdsLpg94A333EfumgUBpO9MdbxStdeITo+KEIMaNfHNvwyjjDJb+ERPaRS6DpyRlKbvPUasNItRyw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('content')
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Pertemuan dan Rapat</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('home') }}">Beranda Dasbor</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Pertemuan dan Rapat</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="{{ asset('dist/images/breadcrumb/ChatBc.png') }}" alt="modernize-img"
                            class="img-fluid mb-n4">
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.show-errors')
    @include('page.panel.meeting.create')
    @include('page.panel.meeting.show')
    @include('page.panel.meeting.edit')

    <div class="row my-3 justify-content-between">
        <div class="left-side col-md-4">
            <form action="{{ request()->fullUrl() }}" class="d-flex gap-2" id="auto-submit-form">
                <select name="council_level" id="council_level" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua</option>
                    @foreach (RoleUserEnum::cases() as $roles)
                        <option value="{{ $roles->value }}"
                            {{ request('council_level') == $roles->value ? 'selected' : '' }}>{{ $roles->label() }}
                        </option>
                    @endforeach
                </select>

                <select name="category" id="category" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua</option>
                    @forelse ($councilCategories as $council)
                        <option value="{{ $council->id }}"
                            {{ request('category') == $council->id ? 'selected' : '' }}>
                            {{ $council->name }}
                        </option>
                    @empty
                        <option value="">Tidak ada kategori</option>
                    @endforelse
                </select>
            </form>
        </div>
        <div class="right-side d-flex justify-content-end col-md-4 d-flex gap-2 align-items-center">
            <a href="#" class="btn btn-success d-flex gap-2 align-items-center" onclick="refreshTable()">
                <i class="ti ti-reload"></i><span>Segarkan</span>
            </a>
            <a href="#" class="btn btn-primary d-flex gap-2 align-items-center" data-bs-toggle="modal"
                data-bs-target="#modalCreation">
                <i class="ti ti-plus"></i><span>Tambah</span>
            </a>
        </div>
    </div>

    <div class="card shadow-none border card-body">
        <div class="table-responsive">
            <table class="table w-100 table-striped table-bordered table-hover" id="table-ajax">
                <thead>
                    <tr>
                        <th>Nama Agenda</th>
                        <th>Diselenggarakan Oleh</th>
                        <th>Tanggal</th>
                        <th>Jumlah Peserta</th>
                        <th class="text-center no-sort">#</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('dist/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('dist/libs/moment/locale/id.js') }}"></script>
    <script src="{{ asset('dist/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('dist/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.min.js"></script>

    <script src="{{ asset('dist/libs/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('dist/libs/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('dist/libs/quill/dist/quill.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus@6.9.4/dist/js/tempus-dominus.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"
        integrity="sha512-uURl+ZXMBrF4AwGaWmEetzrd+J5/8NRkWAvJx5sbPSSuOb0bZLqf+tOzniObO00BjHa/dD7gub9oCGMLPQHtQA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endpush

@push('script')
    <script>
        initializeDataTableWithAjax(
            'table-ajax',
            generateAjaxUrl(
                `{{ route('api.meeting.index') }}`, {
                    council_level: `{{ request('council_level') }}`,
                    category: `{{ request('category') }}`
                }
            ),
            [{
                    data: 'title'
                },
                {
                    data: 'user',
                    render(a) {
                        return `<a href="{{ url('/user') }}/${a.id}">${a.fullname}</a>`;
                    },
                },
                {
                    data: 'id',
                    render(a, b, c) {
                        let $dateFormat = moment.utc(c.date).locale('id').format('D MMMM YYYY');
                        return `${$dateFormat}`;
                    },
                },
                {
                    data: 'participant',
                    render: (a) => `${a} peserta`,
                },
                {
                    data: 'id',
                    render(a) {
                        return `
                        <form data-target="#table-ajax" data-reload-table="true" action="{{ url('api/meeting') }}/${a}" data-success-message="Data berhasil dihapus dari sistem" id="deletedata-${a}" class="form-ajax" method="POST">
                            @csrf
                            @method('DELETE')
                        </form>

                        <div class="d-flex gap-2 align-items-center">
                            <a href="javascript:handleButton('show', '${a}')" class="btn btn-sm btn-primary"><i class="ti ti-eye"></i></a>
                            <a href="javascript:handleButton('edit', '${a}')" class="btn btn-sm btn-light"><i class="ti ti-pencil"></i></a>
                            <a href="javascript:handleButton('delete', '${a}')" class="btn btn-sm btn-danger"><i class="ti ti-trash"></i></a>
                        </div>
                    `;
                    },
                }
            ]
        );

        function refreshTable() {
            reloadDataTable('#table-ajax')
        }

        function handleButton(action, dataId) {
            if (action == 'show') {
                viewData(dataId);
            }

            if (action == 'edit') {
                editData(dataId);
            }

            if (action === 'delete') {
                runModalConfirmWithSubmit('Data yang akan dihapus, tidak akan kembali lagi.', `#deletedata-${dataId}`)
            }
        }
    </script>
@endpush
