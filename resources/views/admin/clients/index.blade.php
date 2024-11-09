@extends('layouts.admin')

@section('content')
<style>
    @media print {
        /* Hide everything except the table */
        body * {
            visibility: hidden;
        }

        /* Only show the table */
        #clientTable, #clientTable * {
            visibility: visible;
        }

        /* Center the table and add margins */
        #clientTable {
            margin: 0 auto; /* Center the table horizontally */
            width: 90%; /* Set the width to 90% of the page for a centered look */
            position: relative;
            top: 0;
            left: 0;
        }

        /* Hide the action column in the table */
        #clientTable th:nth-child(8), #clientTable td:nth-child(8) {
            display: none;
        }

        /* Hide the files column in the table */
        #clientTable th:nth-child(7), #clientTable td:nth-child(7) {
            display: none;
        }

        /* Hide pagination */
        #paginationLinks {
            display: none !important;
        }

        /* Remove unnecessary elements in print view */
        .card-header, .btn, .alert {
            display: none !important;
        }

        /* Remove page breaks inside the table */
        table, tr, td, th {
            page-break-inside: avoid !important;
        }
    }
    div#paginationLinks{
        display: flex !important;
    }
    .d-none.flex-sm-fill.d-sm-flex.align-items-sm-center.justify-content-sm-between{
        display: flex !important;
        flex-direction: column;
    }
</style>    
    
<div class="container-fluid p-4">
    <div class="card p-3">
        <div class="d-flex justify-content-between mb-3">
            <h1>العملاء</h1>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createClientModal">
                إضافة عميل جديد
            </button>
        </div>      

        <!-- Search and Filter Form -->
        <div class="row mb-4 d-flex justify-content-between">
            <div class="col-md-3">
                <input type="text" id="search" class="form-control" placeholder="ابحث عن عميل">
            </div>
            <div class="col-md-3">
                <select id="villageFilter" class="form-control">
                    <option value="">اختر قرية</option>
                    @foreach($villages as $village)
                        <option value="{{ $village->id }}">{{ $village->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="checkbox" id="noInvoices" onchange="searchClients()"> عملاء بدون فواتير
            </div>
            <div class="col-md-3 d-flex justify-content-end">
                <a href="{{ route('clients.printTable') }}" target="_blank" class="btn btn-info">طباعة القائمة</a>
            </div>
        </div>       

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Clients Table -->
        <div class="table-responsive">

            <table class="table table-striped mt-4" id="clientTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الهاتف</th>
                        <th>العنوان</th>
                        <th>الكود</th>
                        <th>القرية</th>
                        <th>الملفات</th> <!-- Add the column for files -->
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $client)
                        <tr>
                            <td>{{ $client->id }}</td>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->phone }}</td>
                            <td>{{ $client->address }}</td>
                            <td>{{ $client->code }}</td>
                            <td>{{ optional($client->village)->name ?? 'غير محدد' }}</td>
                            <td>
                                @if($client->files->count() > 0)
                                    <ul>
                                        @foreach($client->files as $file)
                                            <li><a href="{{ asset('storage/' . $file->path) }}" target="_blank">{{ basename($file->path) }}</a></li>
                                        @endforeach
                                    </ul>
                                @else
                                    لا توجد ملفات
                                @endif
                            </td>
                            <td class="d-flex justify-content-between">
                                <a class="btn btn-info" href="{{ route('clients.show', $client->id) }}">عرض</a>
                                <button class="btn btn-warning mr-2 ml-2" onclick="editClient({{ $client->id }})" data-toggle="modal" data-target="#editClientModal">تعديل</button>
                                @if(auth()->user()->hasPermission('حذف عميل'))
                                    <form action="{{ route('clients.destroy', $client->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>   
        </div>
     

        <!-- Pagination Links -->
        <div id="paginationLinks" class="d-flex justify-content-center">
            {{ $clients->links('pagination::bootstrap-5') }}
        </div>        
    </div>
</div>


<!-- Create Client Modal -->
<div class="modal fade" id="createClientModal" tabindex="-1" role="dialog" aria-labelledby="createClientModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createClientModalLabel">إضافة عميل جديد</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createClientForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">الاسم</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">الهاتف</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="address">العنوان</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                    <div class="form-group">
                        <label for="code">الكود</label>
                        <input type="text" class="form-control" id="code" name="code" required>
                    </div>
                    <div class="form-group">
                        <label for="village_id">القرية</label>
                        <select class="form-control" id="village_id" name="village_id">
                            <option value="">اختر قرية</option>
                            @foreach($villages as $village)
                                <option value="{{ $village->id }}">{{ $village->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="files">الملفات</label>
                        <input type="file" class="form-control" id="files" name="files[]" multiple>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    <button type="submit" class="btn btn-primary">إضافة العميل</button>
                </div>
            </form>
            
        </div>
    </div>
</div>

<!-- Edit Client Modal -->
<div class="modal fade" id="editClientModal" tabindex="-1" role="dialog" aria-labelledby="editClientModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editClientModalLabel">تعديل العميل</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editClientForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_client_id" name="client_id">
                    <div class="form-group">
                        <label for="edit_name">الاسم</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_phone">الهاتف</label>
                        <input type="text" class="form-control" id="edit_phone" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_address">العنوان</label>
                        <input type="text" class="form-control" id="edit_address" name="address" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_code">الكود</label>
                        <input type="text" class="form-control" id="edit_code" name="code" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_village_id">القرية</label>
                        <select class="form-control" id="edit_village_id" name="village_id">
                            <option value="">اختر قرية</option>
                            @foreach($villages as $village)
                                <option value="{{ $village->id }}">{{ $village->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_files">الملفات</label>
                        <input type="file" class="form-control" id="edit_files" name="files[]" multiple>
                    </div>
                    <!-- Existing files display (optional) -->
                    <div class="form-group">
                        <label>الملفات الحالية</label>
                        <ul id="existing_files_list">
                            <!-- Files will be appended here via JS -->
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    <button type="submit" class="btn btn-primary">تحديث العميل</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script>
let searchTimeout;
$('#search, #villageFilter, #noInvoices').on('change keyup', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(function() {
        let searchQuery = $('#search').val();
        let villageId = $('#villageFilter').val();
        let noInvoices = $('#noInvoices').is(':checked') ? 1 : 0;

        $.ajax({
            url: "{{ route('clients.index') }}",
            type: 'GET',
            data: { search: searchQuery, village_id: villageId, no_invoices: noInvoices },
            success: function(data) {
                $('#clientTable').html($(data).find('#clientTable').html());
                $('#paginationLinks').html($(data).find('#paginationLinks').html());
            },
            error: function(xhr) {
                console.error('Error during search/filter: ', xhr);
            }
        });
    }, 300);
});



$(document).on('click', '#paginationLinks a', function(event) {
    event.preventDefault();
    let page = $(this).attr('href').split('page=')[1]; // Extract the page number from the link
    let searchQuery = $('#search').val();
    let villageId = $('#villageFilter').val(); // Get the selected village

    // Send AJAX request with the current search and village filter
    $.ajax({
        url: "{{ route('clients.index') }}",
        type: 'GET',
        data: { search: searchQuery, village_id: villageId, page: page }, // Pass search, village, and page
        success: function(data) {
            $('#clientTable').html($(data).find('#clientTable').html());
            $('#paginationLinks').html($(data).find('#paginationLinks').html());
        },
        error: function(xhr) {
            console.error('Error during pagination: ', xhr);
        }
    });
});


// Create Client
$('#createClientForm').on('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this); // Use FormData to handle file uploads

    $.ajax({
        url: "{{ route('clients.store') }}",
        type: "POST",
        data: formData,
        contentType: false, // Prevent jQuery from setting content type
        processData: false, // Prevent jQuery from processing the data
        success: function(response) {
            $('#createClientModal').modal('hide');
            location.reload();
        },
        error: function(xhr) {
            alert('فشل في إضافة العميل.');
        }
    });
});

// Edit Client - Show existing client data in the modal
function editClient(id) {
    $.ajax({
        url: "/clients/" + id + "/edit",
        type: "GET",
        success: function(response) {
            $('#edit_client_id').val(response.id);
            $('#edit_name').val(response.name);
            $('#edit_phone').val(response.phone);
            $('#edit_address').val(response.address);
            $('#edit_code').val(response.code);
            $('#edit_village_id').val(response.village_id);

            // Clear existing files list
            $('#existing_files_list').empty();

            // Populate existing files list
            if (response.files.length > 0) {
                response.files.forEach(function(file) {
                    $('#existing_files_list').append('<li><a href="' + file.url + '" target="_blank">' + file.path.split('/').pop() + '</a></li>');
                });
            } else {
                $('#existing_files_list').append('<li>لا توجد ملفات</li>');
            }
        },
        error: function(xhr) {
            alert('فشل في جلب بيانات العميل.');
        }
    });
}

// Update Client
$('#editClientForm').on('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this); // Use FormData for file uploads

    $.ajax({
        url: "/clients/" + $('#edit_client_id').val(),
        type: "POST",
        data: formData,
        contentType: false, // Prevent jQuery from setting content type
        processData: false, // Prevent jQuery from processing the data
        success: function(response) {
            $('#editClientModal').modal('hide');
            location.reload();
        },
        error: function(xhr) {
            alert('فشل في تحديث بيانات العميل.');
        }
    });
});
</script>
@endpush
