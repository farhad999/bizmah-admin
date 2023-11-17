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
    columnDefs: [
      {targets: "_all", orderable: false},
    ],
  });

  //image
  $('.image-input').on('change', function () {
    imagePreview(this, 'div.image-preview-gallery');
  });

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

function imagePreview(input, placeToInsertImagePreview) {

  if (input.files) {
    let filesAmount = input.files.length;
    let html = "";
    console.log({"files": input.files})
    for (let i = 0; i < filesAmount; i++) {
      let reader = new FileReader()

      reader.onload = function (event) {

        console.log({'file': event.target})

        html += '<div><img src="' + event.target.result + '"/></div>';
        $(placeToInsertImagePreview).html(html);
      }
      reader.readAsDataURL(input.files[i]);
    }

  }

}
