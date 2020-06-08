@extends('layouts.main')
@section('title', 'Clients')
@section('css')
    <!-- BEGIN PAGE VENDOR CSS-->
    <!-- END PAGE VENDOR CSS-->
    
    <!-- BEGIN PAGE LEVEL CSS-->
    <!-- END PAGE LEVEL CSS-->
@endsection
    
@section('js')
    <!-- BEGIN PAGE VENDOR JS-->
    {{ Html::script('app-assets/vendor/jquery-validation/jquery.validate.js') }}
    <!-- END PAGE VENDOR JS-->
    
    <!-- BEGIN PAGE LEVEL JS-->
    <script type="text/javascript">
    $(document).ready(function() {
    });

    
    $("#FrmAddUpdateClient").on("submit",function(e){
        e.preventDefault();
        var isvalidate=$("#FrmAddUpdateClient").valid();
        if(isvalidate){
            saveRow();
        }
        return false;
    });
    
    $("#FrmAddUpdateClient #ResetFormBtn").on("click",function(e){
        e.preventDefault();
        resetValidation("#FrmAddUpdateClient");
        // $("#SubmitFormBtn").button('reset');
    });

    // $(document).on('click', '#SubmitFormBtn1', function() {
    function saveRow(){
        var formData = new FormData($('#FrmAddUpdateClient')[0]);
        $.ajax({
            type: 'POST',
            url: '{{ route("clients.store") }}',
            processData: false,
            contentType: false,
            data: formData,
            beforeSend: function() {
                $('.loadingoverlay').css('display', 'block');
            },
            success: function(data) {
                if ((data.errors)) {
                    toastr.error("Ooopps! Something went wrong.");
                } else {
                    clearData();
                    $('#RolesTable').DataTable().ajax.reload();
                    toastr.info("Good job! Role is successfully added.");
                }
            },
            complete: function() {
                $('.loadingoverlay').css('display', 'none');
            },
        });
    }
    </script>
    <!-- END PAGE LEVEL JS-->
    
@endsection
@section('content')
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    <section role="main" class="content-body card-margin">
        <header class="page-header">
            <h2>Default Layout</h2>

            <div class="right-wrapper text-right">
                <ol class="breadcrumbs">
                    <li>
                        <a href="index-2.html">
                            <i class="fas fa-home"></i>
                        </a>
                    </li>
                    <li><span>Layouts</span></li>
                    <li><span>Default</span></li>
                </ol>

                <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fas fa-chevron-left"></i></a>
            </div>
        </header>
        <!-- start: page -->
        <div class="row">
            <div class="col-lg-12">
                <form id="FrmAddUpdateClient" class="Validateform form-horizontal" method="post">
                    {{ csrf_field() }}
                    <div class="validation-message">
                        <ul></ul>
                    </div>
                    <section class="card">
                        <header class="card-header">
                            <div class="card-actions">
                                <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                                <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                            </div>

                            <h2 class="card-title">Basic Form Validation</h2>
                            <p class="card-subtitle">
                                Basic validation will display a label with the error after the form control.
                            </p>
                        </header>
                        <div class="card-body">
                            <div class="col-md-6 pull-left">
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label text-sm-right pt-2" for="ClientName">Client Name <span class="required">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" id="ClientName" name="ClientName" class="form-control ClientName" placeholder="Enter client name " required/>
                                        <small>Company Name Ex.Shivdahra Reyon</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label text-sm-right pt-2" for="ClientDetails">Client Details <span class="required">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" id="ClientDetails" name="ClientDetails" class="form-control ClientDetails" placeholder="Enter client details " required/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label text-sm-right pt-2">Client Email <span class="required">*</span></label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-envelope"></i>
                                                </span>
                                            </span>
                                            <input type="email" id="ClientEmail" name="ClientEmail" class="form-control ClientEmail" placeholder="Enter client email" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
											<label class="col-sm-4 control-label text-sm-right pt-2">Client Address <span class="required">*</span></label>
											<div class="col-sm-8">
												<textarea id="ClientAddress" name="ClientAddress" rows="2" class="form-control ClientAddress" placeholder="Enter client address" required></textarea>
											</div>
								</div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label text-sm-right pt-2" for="PanNumber">Pan Number <span class="required">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" id="PanNumber" name="PanNumber" class="form-control PanNumber" placeholder="Enter pan number " required/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label text-sm-right pt-2" for="GSTNumber">GST Number <span class="required">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" id="GSTNumber" name="GSTNumber" class="form-control GSTNumber" placeholder="Enter gst number " required/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label text-sm-right pt-2" for="ClientWebsite">Client Website </label>
                                    <div class="col-sm-8">
                                        <input type="text" id="ClientWebsite" name="ClientWebsite" class="form-control ClientWebsite" placeholder="Enter client website "/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label text-sm-right pt-2" for="ClientProfileLink">Client Profile Link </label>
                                    <div class="col-sm-8">
                                        <input type="link" id="ClientProfileLink" name="ClientProfileLink" class="form-control ClientProfileLink" placeholder="Enter Client profile link "/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label text-sm-right pt-2" for="ContectPerson1">Owner Name <span class="required">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" id="ContectPerson1" name="ContectPerson1" class="form-control ContectPerson1" placeholder="Enter contect person1 " required/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label text-sm-right pt-2" for="ContectNumber1">Owner Number <span class="required">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" id="ContectNumber1" name="ContectNumber1" class="form-control ContectNumber1" placeholder="Enter contect number1 " required/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label text-sm-right pt-2">Owner Email <span class="required">*</span></label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-envelope"></i>
                                                </span>
                                            </span>
                                            <input type="email" id="ContectEmail1" name="ContectEmail1" class="form-control ClientEmail" placeholder="Enter contect email1" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label text-sm-right pt-2" for="ClientStateName">Client State Name <span class="required">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" id="ClientStateName" name="ClientStateName" class="form-control ClientStateName" placeholder="Enter Client state name " required/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label text-sm-right pt-2" for="ClientStateCode">Client State Code <span class="required">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" id="ClientStateCode" name="ClientStateCode" class="form-control ClientStateCode" placeholder="Enter Client state code " required/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 pull-right">
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label text-sm-right pt-2" for="ContectPerson2">Contect Person2 </label>
                                    <div class="col-sm-8">
                                        <input type="text" id="ContectPerson2" name="ContectPerson2" class="form-control ContectPerson2" placeholder="Enter contect person2 "/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label text-sm-right pt-2" for="ContectNumber2">Contect Number2 </label>
                                    <div class="col-sm-8">
                                        <input type="text" id="ContectNumber2" name="ContectNumber2" class="form-control ContectNumber2" placeholder="Enter contect number2 "/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label text-sm-right pt-2">Contect Email2 </label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-envelope"></i>
                                                </span>
                                            </span>
                                            <input type="email" id="ContectEmail2" name="ContectEmail2" class="form-control ClientEmail" placeholder="Enter contect email2"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label text-sm-right pt-2">Delivery Address-1 <span class="required">*</span></label>
                                    <div class="col-sm-8">
                                        <textarea id="DeliveryAddress1" name="DeliveryAddress1" rows="2" class="form-control DeliveryAddress1" placeholder="Enter Delivery Address-1" required></textarea>
                                    </div>
								</div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label text-sm-right pt-2">Delivery Address-2 </label>
                                    <div class="col-sm-8">
                                        <textarea id="DeliveryAddress2" name="DeliveryAddress2" rows="2" class="form-control DeliveryAddress2" placeholder="Enter Delivery Address-2" ></textarea>
                                    </div>
								</div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label text-sm-right pt-2">Delivery Address-3 </label>
                                    <div class="col-sm-8">
                                        <textarea id="DeliveryAddress3" name="DeliveryAddress3" rows="2" class="form-control DeliveryAddress3" placeholder="Enter Delivery Address-3" ></textarea>
                                    </div>
								</div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label text-sm-right pt-2" for="ReferenceName">Reference Name <span class="required">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" id="ReferenceName" name="ReferenceName" class="form-control ReferenceName" placeholder="Enter reference name " required/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label text-sm-right pt-2" for="ReferenceContectNumber">Reference Contect Number </label>
                                    <div class="col-sm-8">
                                        <input type="text" id="ReferenceContectNumber" name="ReferenceContectNumber" class="form-control ReferenceContectNumber" placeholder="Enter Reference contect number " />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label text-sm-right pt-2" for="ReferenceAddress">Reference Address </label>
                                    <div class="col-sm-8">
                                        <input type="text" id="ReferenceAddress" name="ReferenceAddress" class="form-control ReferenceAddress" placeholder="Enter reference address " />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label text-sm-right pt-2" for="ReferenceRelationship">Reference Relationship</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="ReferenceRelationship" name="ReferenceRelationship" class="form-control ReferenceRelationship" placeholder="Enter reference relationship" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <footer class="card-footer">
                            <div class="row justify-content-end">
                                <div class="col-sm-9">
                                    <button class="btn btn-primary" id="SubmitFormBtn">Submit</button>
                                    <button type="reset" class="btn btn-default" id="ResetFormBtn">Reset</button>
                                </div>
                            </div>
                        </footer>
                    </section>
                </form>
            </div>
        </div>
        <!-- end: page -->
    </section>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection
