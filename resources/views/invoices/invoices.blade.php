@extends('layouts.master')
@section('title',$title)
@section('css')
<!-- Internal Data table css -->
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
<!--Internal   Notify -->
<link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
<!--Internal  treeview -->
<link href="{{ URL::asset('assets/plugins/treeview/treeview.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ قائمة
                الفواتير</span>
        </div>
    </div>

</div>
<!-- breadcrumb -->
@endsection
@section('content')
<!-- row -->
<div class="row">
    <!--div-->
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0">
                {{-- <div class="d-flex justify-content-between">
                    <a href="{{ route('invoices.create') }}" class="modal-effect btn btn-sm btn-primary" style="color:white"><i class="fas fa-plus"></i>&nbsp; اضافة فاتورة</a>
            </div> --}}
            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    {{-- @can('اضافة فاتورة') --}}
                    <a href="{{ route('invoices.create') }}" class="modal-effect btn btn-sm btn-primary" style="color:white"><i class="fas fa-plus"></i>&nbsp; اضافة فاتورة</a>
                    {{-- @endcan --}}

                    {{-- @can('تصدير EXCEL') --}}
                    <a class="modal-effect btn btn-sm btn-primary" href="{{ route('invoices.export') }}" style="color:white"><i class="fas fa-file-download"></i>&nbsp;تصدير اكسيل</a>
                    {{-- @endcan --}}

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example1" class="table key-buttons text-md-nowrap" data-page-length='50' style="text-align: center">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0">رقم الفاتورة</th>
                                    <th class="border-bottom-0">تاريخ القاتورة</th>
                                    <th class="border-bottom-0">تاريخ الاستحقاق</th>
                                    <th class="border-bottom-0">المنتج</th>
                                    <th class="border-bottom-0">القسم</th>
                                    <th class="border-bottom-0">الخصم</th>
                                    <th class="border-bottom-0">نسبة الضريبة</th>
                                    <th class="border-bottom-0">قيمة الضريبة</th>
                                    <th class="border-bottom-0">الاجمالي</th>
                                    <th class="border-bottom-0">الحالة</th>
                                    <th class="border-bottom-0">ملاحظات</th>
                                    <th class="border-bottom-0">العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 0; @endphp
                                @foreach ($invoices as $invoice)
                                @php $i++; @endphp
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $invoice->invoice_number }} </td>
                                    <td>{{ date('d-m-Y H:i:s',strtotime($invoice->invoice_data))}}</td>
                                    <td>{{ $invoice->due_date }}</td>
                                    <td>{{ $invoice->product }}</td>
                                    <td><a href="{{ route('invoices.show',$invoice->id) }}">{{ $invoice->section->name }}</a>
                                    </td>
                                    <td>{{ $invoice->discount }}</td>
                                    <td>{{ $invoice->rate_vat }}</td>
                                    <td>{{ $invoice->value_vat }}</td>
                                    <td>{{ $invoice->total }}</td>
                                    <td>
                                        @if ($invoice->status == 1)
                                        <span class="text-success">مدفوعة</span>
                                        @elseif($invoice->status == 2)
                                        <span class="text-warning">مدفوعة جزئيا</span>
                                        @else
                                        <span class="text-danger">غير مدفوعة</span>
                                        @endif

                                    </td>
                                    <td>{{ $invoice->note }}</td>
                                    @if (!request()->has('archive'))
                                        <td>
                                            <div class="dropdown">
                                                <button aria-expanded="false" aria-haspopup="true" class="btn ripple btn-primary btn-sm" data-toggle="dropdown" type="button">العمليات<i class="fas fa-caret-down ml-1"></i></button>
                                                <div class="dropdown-menu tx-13">
                                                    {{-- @can('تعديل الفاتورة') --}}
                                                    <a class="dropdown-item" href=" {{ route('invoices.edit',$invoice->id ) }}"><i class="text-success fas fa-edit"></i>&nbsp;&nbsp;تعديل
                                                        الفاتورة</a>
                                                    {{-- @endcan --}}

                                                    {{-- @can('حذف الفاتورة') --}}
                                                    <a class="dropdown-item" href="#" data-invoice_id="{{ $invoice->id }}" data-toggle="modal" data-target="#delete_invoice"><i class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;حذف
                                                        الفاتورة
                                                    </a>

                                                    {{-- @endcan --}}

                                                    {{-- @can('تغير حالة الدفع') --}}
                                                    <a class="dropdown-item" href="{{ route('status.show',$invoice->id )}}"><i class=" text-success fas fa-money-bill"></i>&nbsp;&nbsp;تغيير
                                                        حالة الدفع</a>
                                                    {{-- @endcan --}}

                                                    {{-- @can('ارشفة الفاتورة') --}}
                                                    <a class="dropdown-item" href="#" data-invoice_id="{{ $invoice->id }}" data-toggle="modal" data-target="#Transfer_invoice"><i class="text-warning fas fa-exchange-alt"></i>&nbsp;&nbsp;نقل
                                                        الي
                                                        الارشيف</a>
                                                    {{-- @endcan --}}

                                                    {{-- @can('طباعةالفاتورة') --}}
                                                    <a class="dropdown-item" href="{{ route('invoices.print',$invoice->id )}}"><i class="text-success fas fa-print"></i>&nbsp;&nbsp;طباعة
                                                        الفاتورة
                                                    </a>
                                                    {{-- @endcan --}}
                                                </div>
                                            </div>

                                        </td>
                                        <!-- حذف الفاتورة -->
                                        <div class="modal fade" id="delete_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">حذف الفاتورة</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        <form action="{{ route('invoices.destroy',$invoice->id) }}" method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                    </div>
                                                    <div class="modal-body">
                                                        هل انت متأكد من عملية الحذف ؟
                                                        <input type="hidden" name="invoice_id" id="invoice_id" value="">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                                                        <button type="submit" class="btn btn-danger">تأكيد</button>
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- ارشيف الفاتورة -->
                                        <div class="modal fade" id="Transfer_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">ارشفة الفاتورة</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        <form action="{{ route('invoices.archive',$invoice->id) }}" method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                    </div>
                                                    <div class="modal-body">
                                                        هل انت متأكد من عملية الارشفة ؟
                                                        <input type="hidden" name="invoice_id" id="invoice_id" value="">
                                                        <input type="hidden" name="id_page" id="id_page" value="2">

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                                                        <button type="submit" class="btn btn-success">تأكيد</button>
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <td>
                                            <div class="dropdown">
                                                <button aria-expanded="false" aria-haspopup="true" class="btn ripple btn-primary btn-sm" data-toggle="dropdown" type="button">العمليات<i class="fas fa-caret-down ml-1"></i></button>
                                                <div class="dropdown-menu tx-13">
                                                    <a class="dropdown-item" href="#" data-invoice_id2="{{ $invoice->id }}" data-toggle="modal" data-target="#cancel_transfer_invoice"><i class="text-warning fas fa-exchange-alt"></i>&nbsp;&nbsp;نقل الي
                                                        الفواتير</a>
                                                    <a class="dropdown-item" href="#" data-invoice_id2="{{ $invoice->id }}" data-toggle="modal" data-target="#delete_permanently"><i class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;حذف
                                                        الفاتورة</a>
                                                </div>
                                            </div>

                                        </td>
                                        <!-- حذف الفاتورة نهائيا-->
                                        <div class="modal fade" id="delete_permanently" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabe2">حذف الفاتورة</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        <form action="{{ route('invoices.deletePermanently',$invoice->id) }}" method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                    </div>
                                                    <div class="modal-body">
                                                        هل انت متاكد من عملية الحذف ؟
                                                        <input type="hidden" name="invoice_id" id="invoice_id2" value="">

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                                                        <button type="submit" class="btn btn-danger">تاكيد</button>
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!--الغاء الارشفة-->
                                        <div class="modal fade" id="cancel_transfer_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel2">الغاء ارشفة الفاتورة</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        <form action="{{ route('invoices.restore',$invoice->id) }}" method="get">
                                                            
                                                           {{--  {{ csrf_field() }} --}}
                                                    </div>
                                                    <div class="modal-body">
                                                        هل انت متاكد من عملية الغاء الارشفة ؟
                                                        <input type="hidden" name="invoice_id" id="invoice_id2" value="">

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                                                        <button type="submit" class="btn btn-success">تاكيد</button>
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </tr>

                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/div-->
</div>

@endsection
@section('js')
<!-- Internal Data tables -->
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
<!--Internal  Datatable js -->
<script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
<!--Internal  Notify js -->
<script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/notify/js/notifit-custom.js') }}"></script>

<script>
    $('#delete_invoice').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var invoice_id = button.data('invoice_id')
        var modal = $(this)
        modal.find('.modal-body #invoice_id').val(invoice_id);
    })

</script>

<script>
    $('#Transfer_invoice').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var invoice_id = button.data('invoice_id')
        var modal = $(this)
        modal.find('.modal-body #invoice_id').val(invoice_id);
    })

</script>

<script>
    $('#delete_permanently').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var invoice_id = button.data('invoice_id2')
        var modal = $(this)
        modal.find('.modal-body #invoice_id2').val(invoice_id);
    })

</script>

<script>
    $('#cancel_transfer_invoice').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var invoice_id = button.data('invoice_id2')
        var modal = $(this)
        modal.find('.modal-body #invoice_id2').val(invoice_id);
    })

</script>







@endsection
