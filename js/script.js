var oTable;

function dataTableInit(config) {
    oTable = $(config.id).DataTable({
        processing: true,
        serverSide: true,
        order: [[config.order.column, config.order.direction]],
        oLanguage: {
            sProcessing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
        },
        ajax: {
            url: config.url,
            dataType: "json",
            type: "GET",
            data: function (d, settings) {
                var api = new $.fn.dataTable.Api(settings);
                d.page = api.page() + 1;
                d.filters = {};
                if (config.filters) {
                    for (var filter in config.filters) {
                        var field = config.filters[filter];
                        d.filters[field] = $(`#${field}`).val();
                    }
                }
            },
            dataSrc: function (json) {
                json.draw = json.data.draw;
                json.recordsTotal = json.data.recordsTotal;
                json.recordsFiltered = json.data.recordsFiltered;
                return json.data.data;
            }
        },
        columns: config.columns,
        rowReorder: config.rowReorder ? config.rowReorder : false,
    });
    
}

function serialNumber(id, row) {
    var pageInfo = $(id).DataTable().page.info();
    return pageInfo.start + 1 + row;
}

function buttonEdit(endPoint, id) {
    return `<a 
      href="${endPoint}/${id}/edit" 
      class="btn btn-sm btn-warning btn-circle"
      ><i class="fas fa-edit"></i>
    </a>`;
}

function buttonDownload(url) {
    return `<a 
      href="${url}" 
      class="btn btn-sm btn-success btn-circle"
      download
      ><i class="fas fa-download"></i>
    </a>`;
}

function buttonDelete(endPoint, id) {
    return `<a 
      href="javascript:void(0)" 
      data-url="${endPoint}/${id}" 
      class="btn btn-sm btn-danger btn-circle btn-delete" 
      data-toggle="modal" 
      data-target="#deleteModal">
        <i class="fas fa-trash"></i>
    </a>`;
}

function buttonPermission(props) {
    return `<a 
      href="javascript:void(0)" 
      data-url="${props.url}" 
      class="btn btn-sm btn-primary btn-circle ${props.className}" 
      data-id="${props.id}"
      data-toggle="modal" 
      data-target="${props.target}">
        <i class="fas fa-user-plus"></i>
    </a>`;
}

function buttonActivate(url) {
    return `<button type="button" data-url="${url}" class="btn btn-success btn-sm btn-activation">Activate</a>`;
}

function buttonDeactivate(url) {
    return `<button type="button" data-url="${url}" class="btn btn-danger btn-sm btn-activation">Deactivate</a>`;
}

$('#state,#role').on('change', function () {
    serial = 0;
    oTable.ajax.reload();
});

$('#reset_filter').on('click', function () {
    $('.filter').val('');
    oTable.ajax.reload();
});

$(document).on("click", ".btn-delete", function () {
    var url = $(this).data("url");
    $("#modal-delete-url").val(url);
});

$(document).on("click", "#confirm-delete", function () {
    var url = $("#modal-delete-url").val();
    var csrfToken = $("#csrf-token").val();
    $.ajax({
        url: url,
        method: "DELETE",
        data: {
            _token: csrfToken,
        },
        success: function (response) {
            success(response);
            $("#deleteModal").modal("hide");
            oTable.ajax.reload();
        },
        error: function (response) {
            if (response.responseJSON.exception && response.responseJSON.exception === 'Illuminate\\Database\\QueryException') {
                response.responseJSON.message = 'Could not delete because this record has mapped to another record(s)';
                failure(response.responseJSON);
                $("#deleteModal").modal("hide");
            }
        }
    });
});


function sendRequest(requestData, redirectTo, isLogout = false) {
    disableSubmit();
    $.ajax({
        url: requestData.url,
        method: requestData.method,
        data: JSON.stringify(requestData.body),
        headers: requestData.headers ? requestData.headers : { 'Content-Type': 'application/json' },
        success: function (responseData) {
            if (responseData.status) {
                success(responseData);
                if (isLogout) {
                    $(document).find("#logout-form").submit();
                    return;
                }
                redirect(redirectTo);
            }
            else if (!responseData.status && responseData.errors) {
                applyValidationErrors(responseData);
                enableSubmit();
                return;
            }
            else {
                failure(responseData);
                enableSubmit();
            }
        }
    })
}

function applyValidationErrors(responseData) {
    for (var error in responseData.errors) {
        var errorMessage = responseData.errors[error][0];
        $(`#${error}_error`).text(errorMessage);
    }
}

function success(responseData) {
    $("#alert-success-message").text(responseData.message);
    $(".alert-success").show();
    setTimeout(function () {
        $(".alert-success").hide();
    }, 1000);
}

function failure(responseData) {
    $("#alert-error-message").text(responseData.message);
    $(".alert-danger").show();
}

function disableSubmit() {
    $('button[type="submit"]').attr("disabled", "disabled");
}

function enableSubmit() {
    $('button[type="submit"]').removeAttr("disabled");
}

function redirect(redirectTo) {
    setTimeout(function () {
        location.href = redirectTo;
    }, 800);
}

$(".form-control").focus(function () {
    $(this).parent('div').find('.form-error').text('');
});

$(document).on("click", ".btn-activation", function() {
    var url = $(this).data("url");
    var csrfToken = $("#csrf-token").val();
    $.ajax({
        url: url,
        method: "POST",
        data: {
            _token: csrfToken
        },
        success: function(response) {
            success(response);
            oTable.ajax.reload();
        }
    });
});

