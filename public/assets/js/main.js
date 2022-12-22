function initFormStep(form, title, bodyTag, transitionEffect) {
  var form = form.show()
  form
    .steps({
      headerTag: 'h3',
      bodyTag: 'section',
      transitionEffect: 'slideLeft',
      titleTemplate: '<span class="number"></span> #title#',
      labels: {
        current: 'current step:',
        pagination: 'Pagination',
        finish: 'Générer',
        next: 'Suivant',
        previous: 'Précédent',
        loading: 'Chargement ...',
      },
      onStepChanging: function (event, currentIndex, newIndex) {
        // second step
        // if (currentIndex == 1 && newIndex == 2) {
        //     return checkSecondStep();
        // }
        // Allways allow previous action even if the current form is not valid!
        if (currentIndex > newIndex) {
          return true
        }

        // Needed in some cases if the user went back (clean up)
        if (currentIndex < newIndex) {
          // To remove error styles
          form.find('.body:eq(' + newIndex + ') label.error').remove()
          form.find('.body:eq(' + newIndex + ') .error').removeClass('error')
        }
        form.validate().settings.ignore = ':disabled,:hidden'
        return form.valid()
      },
      onStepChanged: function (event, currentIndex, priorIndex) {
        // Used to skip the "Warning" step if the user is old enough.
        if (currentIndex === 3) {
          let data = $('#example-basic').serializeArray()
          let resum = $('#resum')
          let html = ''
          data.forEach((element) => {
            let name = element.name
            let value = element.value

            let label = {
              'resiliation_form[service][name]': 'Service à résilier',
              'resiliation_form[service][address]': 'Addresse',
              'resiliation_form[service][complement]': 'Complément',
              'resiliation_form[service][zipCode]': 'Code postale',
              'resiliation_form[service][city]': 'Ville',
              'resiliation_form[number]': 'Numéro contrat/abonné',
              'resiliation_form[type]': 'Motif',
              'resiliation_form[description]': 'Contenu',
              'resiliation_form[client][firstName]': 'Nom',
              'resiliation_form[client][lastName]': 'Prénom',
              'resiliation_form[client][mobile]': 'Téléphone',
              'resiliation_form[client][address][name]': 'Adresse',
              'resiliation_form[client][address][complement]': 'Complément',
              'resiliation_form[client][address][zipCode]': 'Code postal',
              'resiliation_form[client][address][city]': 'Ville',
            }

            let field = [
              'resiliation_form[service][name]',
              'resiliation_form[number]',
              'resiliation_form[client][firstName]',
              'resiliation_form[client][lastName]',
              'resiliation_form[client][mobile]',
              'resiliation_form[client][address][name]',
              'resiliation_form[client][address][complement]',
              'resiliation_form[client][address][zipCode]',
              'resiliation_form[client][address][city]',
            ]

            if (0 <= $.inArray(name, field)) {
              html = html.concat(
                '<div>' +
                  '<strong>' +
                  label[name] +
                  ':</strong>' +
                  ' <span> ' +
                  value +
                  '</span>' +
                  '</div>',
              )
            }
          })
          resum.html(
            html.concat(
              "<div></div>",
            ),
          )
        }
      },
      onFinishing: function (event, currentIndex) {
        form.validate().settings.ignore = ':disabled'
        return form.valid()
      },
      onFinished: function (event, currentIndex) {
        form.submit()
      },
    })
    .validate({
      errorPlacement: function errorPlacement(error, element) {
        element.before(error)
      },
      rules: {
        'resiliation_form[service][name]': {
          required: true,
        },
        'resiliation_form[service][address]': {
          required: true,
        },
        'resiliation_form[service][zipCode]': {
          required: true,
          digits: true,
          minlength: 5,
          maxlength: 5,
        },
        'resiliation_form[service][city]': {
          required: true,
        },
        'resiliation_form[number]': {
          required: true,
        },
        'resiliation_form[client][firstName]': {
          required: true,
        },
        'resiliation_form[client][lastName]': {
          required: true,
        },
        'resiliation_form[client][address][name]': {
          required: true,
        },
        'resiliation_form[client][address][zipCode]': {
          required: true,
          digits: true,
          minlength: 5,
          maxlength: 5,
        },
        'resiliation_form[duplicata][address][city]': {
          required: true,
        },
      },
      messages: {
        'resiliation_form[service][name]': {
          required: 'Champs obligatoire',
        },
        'resiliation_form[service][address]': {
          required: 'Champs obligatoire',
        },
        'resiliation_form[service][zipCode]': {
          required: 'Champs obligatoire',
          digits: 'Le code postal doit être à 5 chiffres',
          minlength: 'Le code postal doit être à 5 chiffres',
          maxlength: 'Le code postal doit être à 5 chiffres',
        },
        'resiliation_form[service][city]': {
          required: 'Champs obligatoire',
        },
        'resiliation_form[number]': {
          required: 'Champs obligatoire',
        },
        'resiliation_form[client][firstName]': {
          required: 'Champs obligatoire',
        },
        'resiliation_form[client][lastName]': {
          required: 'Champs obligatoire',
        },
        'resiliation_form[client][address][name]': {
          required: 'Champs obligatoire',
        },
        'resiliation_form[client][address][zipCode]': {
          required: 'Champs obligatoire',
          digits: 'Le code postal doit être à 5 chiffres',
          minlength: 'Le code postal doit être à 5 chiffres',
          maxlength: 'Le code postal doit être à 5 chiffres',
        },
        'resiliation_form[client][address][city]': {
          required: 'Champs obligatoire',
        },
      },
    })
}
