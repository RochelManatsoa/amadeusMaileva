var mustObligatory = function(el){
    id = el.id;
    var labelError = document.createElement('label');
    labelError.className = "error";
    labelError.setAttribute("for", id);
    labelError.setAttribute("generated", "true");
    labelError.innerText="Champs obligatoire";
    el.parentElement.insertBefore(labelError, el);
    $(el).addClass("error");
    el.onchange = function (){
        $(this).removeClass("error");
        this.parentElement.removeChild(labelError);
    }
}

var RequiredField = function(el){
    fields = ['INPUT', 'TEXTAREA', 'SELECT'];
    test = 0;
    for (field of fields){
        if (el.nodeName === field && el.getAttribute('required') === 'required'){
            tableau.push(el);
        }
    }
}

var getField = function(parent) {
    childs = parent.children;
    if (childs.length > 0){
        for (child of childs){
            RequiredField(child);
            if (child.children.length > 0){
                getField(child);
            }
        }
    }
}

var validStep = function(el){
    tableau = [];
    getField(el);
    required = 0;
    link = document.querySelector('[href = "#'+el.id+'"]');
    for (item of tableau){
        if (item.value !== ''){
            required +=1;
        } else {
            mustObligatory(item);
        }
    }
    if (required===tableau.length){
        if (!el.classList.contains("done")){
            $(el).addClass("done");
            $(link).addClass("done");
            if ($.inArray(link,done) === -1){
                done.push(link);
                if ($(link).parent().next().hasClass("not-yet")){
                    $(link).parent().next().removeClass("not-yet");
                }
            }
            voirStep(done);
        }
        return true;
    } else {
        if (el.classList.contains("done")){
            $(el).removeClass("done");
            $(link).removeClass("done");
        }
        return false;
    }
}

var nextStep = function(el){
    if (validStep(el)){
        el.classList.replace("current", "d-none");
        next = el.nextElementSibling;
        next.classList.replace("d-none", "current");
        doneRecap(next);
        if (parseInt(next.getAttribute("index"))<4){
            if($('#previous-button').parent().hasClass("d-none")){
                $('#previous-button').parent().removeClass("d-none");
            }
        } else if (parseInt(next.getAttribute("index"))==4){
            if($('#finish-button').parent().hasClass("d-none")){
                $('#finish-button').parent().removeClass("d-none");
                $('#next-button').parent().addClass("d-none");
            }
        }
    }
}

var previousStep = function(el){
    el.classList.replace("current", "d-none");
    previous = el.previousElementSibling;
    previous.classList.replace("d-none", "current");
    if (parseInt(previous.getAttribute("index"))>1){
        if($('#next-button').parent().hasClass("d-none")){
            $('#next-button').parent().removeClass("d-none");
        }
        if(!$('#finish-button').parent().hasClass("d-none")){
            $('#finish-button').parent().addClass("d-none");
        }
    } else if (parseInt(previous.getAttribute("index"))===1){
        if(!$('#previous-button').parent().hasClass("d-none")){
            $('#previous-button').parent().addClass("d-none");
        }
    }
}

var voirStep = function (done){
    for (link of done){
        link.parentElement.onclick = function(){
            href = this.firstElementChild.href;
            cible = href.substring(href.indexOf('#')+1, href.length);
            document.querySelector('[data-element].current').classList.replace("current", "d-none");
            document.getElementById(cible).classList.replace("d-none", "current");
        }
    }
}
var doneRecap = function(el){
    if (el.hasAttribute("recap")){
        link = document.querySelector('[href = "#'+el.id+'"]');
        $(el).addClass("done");
        $(link).addClass("done");
        if ($.inArray(link,done) === -1){
            done.push(link);
        }
        voirStep(done);
        recap();
    }
}/*
var gererButton = function(dataElement){
    if (dataElement === "step-form1")
}*/

var recap = function () {
    let data = $('#example-basic').serializeArray();
    let resum = $('#resum');
    let html = '';
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
                '<tr>'+
                    '<td class="d-flex justify-content-between">'+
                        label[name] +
                        '<span class="ms-2">:</span>'+
                    '</td>'+   
                    '<td>'+
                        value+
                    '</td>'+
                '</tr>'
            )
        }
    })
    resum.html(
        html.concat(
        "<div></div>",
        ),
    )
}



function uncheck(e){
    if (e.checked) {
        e.checked = false;
    }
}

function uncheckOther(e){
    let identifiant = e.id;
    let className = e.className;
    let parentElement = e.parentElement.parentElement;
    
    let radios = parentElement.querySelectorAll('.'+className);
    for (radio of radios) {
        if(radio.id !== identifiant){
            uncheck(radio);
        }
    }
}