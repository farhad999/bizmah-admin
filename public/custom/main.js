$(document).ready(function () {

  //toaster
  toastr.options.preventDuplicates = true;
  toastr.options.timeOut = "3000"

  jQuery.validator.setDefaults({

    invalidHandler: function () {
      toastr.error('Incomplete Form');
    },

    highlight: function (element) {
      $(element).addClass("is-invalid").removeClass("is-valid");
    },
    unhighlight: function (element) {
      $(element).addClass("is-valid").removeClass("is-invalid");
    },

    //add
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      if (element.closest('.form-group').length) {
        element.closest('.form-group').append(error);
        //error.insertAfter(element.parent());
      } else {
        error.insertAfter(element);
      }
    }

  });
  //custom rules added
  jQuery.validator.addMethod("alpha", function (value, element) {
    return this.optional(element) || /^[a-zA-Z ]+$/u.test(value);
  }, "Only alphabets and whitespace are allowed");

  jQuery.validator.addMethod("alpha_num", function (value, element) {
    return this.optional(element) || /^[a-zA-Z \d]+$/u.test(value);
  }, "Only alphabets and numbers are allowed");

  jQuery.validator.addMethod("number", function (value, element) {
    return this.optional(element) || /^\d+\.?\d*$/.test(value);
  }, "Only numbers are allowed");

  //extract rules from form

  let selector = "form#validate_form";

  if ($(selector).length) {
    $(selector).validate();

    $(selector).on('submit', function (e) {
      e.preventDefault();
      addRules(selector);

      if ($(selector).valid()) {
        $(selector)[0].submit();
      }

    })

  }

  //data tables

  jQuery.extend($.fn.dataTable.defaults, {
    //Uncomment below line to enable save state of datatable.
    //stateSave: true,
    fixedHeader: true,
    aaSorting: [],
    responsive: true,
    serverSide: true,
    processing: true,
    columnDefs: [
      {targets: "_all", orderable: false},
    ],
  });

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  })


})


function addRules(form) {
  let rules = {};
  $(form).find('input:not(:hidden), select').each(function () {

    let ruleObj = {};
    let inputRules = $(this).data('rules');
    if (inputRules && inputRules.length) {
      let ruleArray = inputRules.split('|');
      ruleArray.forEach(function (item) {
        let splitRules = item.split(':');
        ruleObj[splitRules[0]] = splitRules[1] ? splitRules[1] : true
      })
    }
    $(this).rules('add', ruleObj)
  });

  return rules;
}

//File Upload

function imagePreview(input) {

  let box = $(input).closest('.image-box');
  let gallery = $(box).find('.image-preview-gallery');

  if (input.files) {
    let filesAmount = input.files.length;
    let html = "";
    console.log({"files": input.files})
    for (let i = 0; i < filesAmount; i++) {
      let reader = new FileReader()

      reader.onload = function (event) {

        console.log({'file': event.target})

        html += '<div><img src="' + event.target.result + '"/></div>';
        $(gallery).html(html);
      }
      reader.readAsDataURL(input.files[i]);
    }

  }

}

$(document).on('click', '.delete-item-btn', function () {

  let url = $(this).data('href');

  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#4e90bd',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: url,
        method: 'delete',
        dataType: 'json',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {
          let {status} = res;
          if(status === 'error'){
            toastr.error(res.message ?? 'Something went wrong');
            return;
          }
          toastr.success(res.message);
          window.location.reload();
        },
        error: function (er) {
          console.log(er)
        }
      });

    }
  })
});

//image
$(document).on('change', '.image-input', function () {
  imagePreview(this);
});
