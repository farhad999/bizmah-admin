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

