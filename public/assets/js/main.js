function initFormStep(form, title, bodyTag, transitionEffect) {
  var form = form.show()
  form
    .steps({
      headerTag: 'h3',
      bodyTag: 'section',
      transitionEffect: 1,
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
          minlength: 4,
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
          minlength: 4,
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
          digits: 'Le code postal doit être à 4 ou 5 chiffres',
          minlength: 'Le code postal doit être au moins 4 chiffres',
          maxlength: 'Le code postal doit être au plus 5 chiffres',
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
          digits: 'Le code postal doit être à 4 ou 5 chiffres',
          minlength: 'Le code postal doit être au moins 4 chiffres',
          maxlength: 'Le code postal doit être au plus 5 chiffres',
        },
        'resiliation_form[client][address][city]': {
          required: 'Champs obligatoire',
        },
      },
    })
}

function updateNumber(number){
  var content = $('#resiliation_form_description').text()
  content = content.replace('[ mobile-identifiant ]', number)
  $('#resiliation_form_description').html(content)
}

var villeParCodePostal, communeParVille;

function getVilleFrance(){
  var xhttp = new XMLHttpRequest()
  
  xhttp.onreadystatechange = function(){
    if (this.readyState == 4 && this.status == 200){
      const list = this.responseText
      villes = JSON.parse(list)
      codePostals = getAllPostalCode(villes)
      listVilles = getAllVilles(villes)
      villeParCodePostal = getVillesPerPostalCode(codePostals, villes)
      communeParVille = getCommunePerVille(listVilles, villes)
    }
  }
  xhttp.open('GET', 'https://raw.githubusercontent.com/high54/Communes-France-JSON/master/france.json')
  xhttp.send();
}

function getAllPostalCode(villes){
  codePostal = []
  villes.forEach(function(el){
    if (!codePostal.includes(el.Code_postal)){
      codePostal.push(el.Code_postal)
    }
  })
  return codePostal;
}

function getAllVilles(villes){
  listVilles = []
  villes.forEach(function(el){
    if(!listVilles.includes(el.Libelle_acheminement)){
      listVilles.push(el.Libelle_acheminement)
    }
  })
  return listVilles;
}

function keyFormat(string){
  string = string.toLowerCase()
  if (string.includes(' ')){
    string = string.replaceAll(' ','_')
  }
  return string
}

function getVillesPerPostalCode(codePostal, villes){
  let listVilles = {}
  codePostal.forEach(function(postalCode){
    key = postalCode.toString()
    villesArray = []
    communes = []
    for (ville of villes) {
      if (ville["Code_postal"] === postalCode && !communes.includes(ville["Code_commune_INSEE"])){
        communes.push(ville["Code_commune_INSEE"])
        villesArray.push(ville)
      }
    }
    listVilles[key] = villesArray
  })
  return listVilles
}

function getCommunePerVille(listVilles, villes){
  let retour = {}
  listVilles.forEach(function(el){
    key = keyFormat(el)
    villesArray = []
    communes = []
    for (ville of villes) {
      if (ville["Libelle_acheminement"] === el && !communes.includes(ville["Code_commune_INSEE"])){
        communes.push(ville["Code_commune_INSEE"])
        villesArray.push(ville)
      }
    }
    retour[key] = villesArray
  })
  return retour
}

function getSuggestPostalCode(postalCode, villeParCodePostal) {
  result = []
  if (postalCode.length > 3){
    for (zipCode in villeParCodePostal) {
      if (zipCode.includes(postalCode)){
        for (ville in villeParCodePostal[zipCode]){
          result.push(villeParCodePostal[zipCode][ville])
        }
      }
    }
  }
  return result;
}

function showSuggestPostalCode(postalCode, villeParCodePostal) {
  suggestPostalCode = getSuggestPostalCode(postalCode, villeParCodePostal)
  list = document.getElementById('suggest-zipCode')
  list.innerHTML = null
  if (suggestPostalCode.length > 0){
    $('#zip-code').removeClass('d-none')
    suggestPostalCode.forEach(function (ville){
      item = document.createElement('li')
      item.className = "text-capitalize"
      item.setAttribute("data-zipCode", ville["Code_postal"].toString())
      item.setAttribute("data-ville", ville["Libelle_acheminement"].toLowerCase())
      item.innerText = ville["Nom_commune"].toLowerCase()
      list.appendChild(item)
      $(item).on( "mouseenter", function() {
        setZipCode(this);
      } )
      $(item).on( "click", function() {
        setZipCode(this)
        $('#zip-code').addClass('d-none')
      } )
    })
  }
}

function setZipCode (el){
  zipCode = el.getAttribute('data-zipCode')
  ville = el.getAttribute('data-ville')
  $('input#resiliation_form_client_address_zipCode').val(zipCode)
  $('input#resiliation_form_client_address_city').val(ville)
}