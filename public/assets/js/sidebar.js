var addDNone = function(el) {
    if (!el.classList.contains('d-none')) {
        el.classList.add('d-none');
    } 
}

var removeDNone = function(el) {
    if (el.classList.contains('d-none')) {
        el.classList.remove('d-none');
    } 
}

var showCurrent = function(){
    let postLink = document.querySelector('.post-link.active');
    let postLinkContainer = postLink.parentElement.parentElement.parentElement;
    removeDNone(postLinkContainer);
}

var togglePostContainer = function(el) {
    currentPostLinkContainer = el.nextElementSibling;
    postLinkContainers = document.querySelectorAll('.container-post-links');
    removeDNone(currentPostLinkContainer);
    for (postLinkContainer of postLinkContainers){
        if (postLinkContainer != currentPostLinkContainer){
            addDNone(postLinkContainer);
        }
    }
}