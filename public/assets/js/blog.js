var posts = [],
    categories = [],
    numAjaxRequests = 0;

function formatImgUrl(imgUrl, width, height) {
    fileUrl = imgUrl.slice(0, -4);
    fileType = imgUrl.slice(-4);
    return fileUrl+'-'+width.toString()+'x'+height.toString()+fileType;
}

function getCategories(){
    let restUrl = "https://comment-resilier.info/wp-json/wp/v2/categories",
        href = location.origin;
    numAjaxRequests ++;
    $.getJSON(restUrl, function(restCategories){
        restCategories.forEach(element => {
            let object = {};
            object.id = element.id;
            object.name = element.name;
            object.slug = element.slug;
            object.href = href+'/category/'+element.slug;
            categories.push(object);
        })
        numAjaxRequests --;
        checkIfAllRequestsComplete();
    })
}

function getPosts(){
    let restUrl = "https://comment-resilier.info/wp-json/wp/v2/posts",
        href = location.origin;
    numAjaxRequests ++;


    $.getJSON(restUrl, function(restPosts){
        restPosts.forEach(element => {
            let object = {};
            object.id = element.id;
            object.title = element.title.rendered;
            object.slug = element.slug;
            object.href = href+'/comment-résilier/'+element.slug;
            object.description = element.excerpt.rendered;
            object.content = element.content.rendered;
            object.featured_image = element.featured_media ? 
                                formatImgUrl(element.yoast_head_json.og_image[0].url, 300, 150) :
                                "https://comment-resilier.info/wp-content/themes/onepress/assets/images/placholder2.png"
            postCategories = [];
            categoriesId = element.categories;
            categoriesId.forEach(el => {
                categories.forEach(category => {
                    if (category.id === el){
                        postCategories.push(category)
                    }
                })
            })
            object.categories = postCategories;
            posts.push(object);
            
        });
        numAjaxRequests --;
        checkIfAllRequestsComplete();
    })
}

function showThreeLatestPosts(allPosts){
    let count = 0;
    allPosts.forEach(element => {
        if (count === 0){
            showPost(element, true);
        } else if (count > 0 && count < 3){
            showPost(element);
        }
        count ++;
    })
}

function showPostsByCategory(allPosts, category){
    let count = 0;
    allPosts.forEach(element => {
        if (count === 0 && element.categories.includes(category)){
            showPost(element, true);
        } else if (count > 0 && element.categories.includes(category)){
            showPost(element);
        }
        count ++;
    })
}

function showPost(post, active){
    category = '<span class="text-info h6">';
    post.categories.forEach(cat => {
        category = category + '<a href="'+cat.href+'" class="text-uppercase text-info">'+cat.name+'</a>/';
    })
    category = category.slice(0, -1)+'</span>';
    if (active) {
        active = ' active';
    } else {
        active = '';
    }
    postElement =   '<div class="container d-flex post-content'+active+'" post-Id="'+post.id+'">'+
                        '<img src="'+post.featured_image+'" width="300" height="150" class="m-3"/>'+
                        '<div class="p-4">'+
                            category+
                            '<a href="'+post.href+'" class="text-decoration-none text-secondary">'+
                                '<h4>'+post.title+'</h4>'+
                            '</a>'+
                            post.description+
                        '</div>'+
                    '</div>'
    $( postElement ).appendTo( $( '#blog-posts' ) );
}

function createSidebar(allPosts, allCategories){
    let listPost = [];
    allCategories.forEach(category => {
        let object = {};
        for (key in category) {
            object[key] = category[key];
        }
        object.posts = []
        allPosts.forEach(post => {
            if (post.categories.includes(category)){
                postInCategory = {}
                postInCategory.id = post.id;
                postInCategory.title = post.title;
                postInCategory.href = post.href;

                object.posts.push(postInCategory);
            }
        })
        if (object.posts.length > 0) {
            listPost.push(object);
        }
    })
    theSidebar(listPost);
}

function theSidebar(listPost) {
    sidebar = '<div class="border ms-5 p-3 blog-sidebar" style="width: 300px">';
    listPost.forEach(category => {
        sidebar += '<a href="'+category.href+'" class="text-decoration-none text-muted h6">'+
                       category.name+
                   '</a>'+
                   '<ul class="list-unstyled ms-4 d-none">';
        category.posts.forEach(post => {
            sidebar += '<li class="mb-2 post-link" post-Id="'+post.id+'">'+
                           '<a href="'+post.href+'" class="text-decoration-none text-secondary small">'+
                               post.title+
                           '</a>'+
                       '</li>';
        })
        sidebar += '</ul>';
    })
    sidebar += '</div>';
    $( sidebar ).appendTo( $( '#blog-sidebar' ) );
}

function setActiveLink(){
    postActive = document.querySelector('.post-content.active');
    postActiveId = postActive.getAttribute('post-Id');
    postActiveLink = document.querySelector('li[post-id="'+postActiveId+'"]');
    $(postActiveLink).addClass('active');
    parentActive = postActiveLink.parentElement;
    $(parentActive).removeClass('d-none');
}

function checkIfAllRequestsComplete() {
    if (numAjaxRequests === 0) {
        // Toutes les requêtes sont terminées
        createSidebar(posts, categories);
        if (location.pathname === '/blog'){
            showThreeLatestPosts(posts);
        } else if (location.pathname.includes('/comment')){
            slug = location.pathname.slice(location.pathname.slice(1).indexOf('/')+2);
            content = thePost(getPostBySlug(posts, slug));
            $(content).appendTo($('#blog-posts'))
        } else if (location.pathname.includes('/category/')){
            slug = location.pathname.slice(location.pathname.slice(1).indexOf('/')+2);
            category = getCategoryBySlug(categories, slug);
            $('#category-name').html(category.name);
            showPostsByCategory(posts, category);
        }
        setActiveLink();
    }
}

function getPostBySlug(allPosts, slug){
    let result = {};
    allPosts.forEach(post => {
        if (post.slug === slug){
            result.id = post.id;
            result.content = post.content;
        }
    })
    return result;
}

function getCategoryBySlug(allCategories, slug){
    let result;
    allCategories.forEach(el => {
        if (el.slug === slug){
            result = el;
        }
    })
    return result;
}

function thePost(currentPost){
    result = '<div class="me-5 post-content active" post-Id="'+currentPost.id+'">';
    result += theFeatureImage(currentPost.content);
    result += theContent(currentPost.content);
    result += theIframe(currentPost.content);
    result += '</div>';
    return result;
}

function theFeatureImage(content){
    startImageSrc = content.indexOf("src")+5;
    endImageSrc = content.slice(startImageSrc).indexOf('"')+startImageSrc;
    imageSrc = content.slice(startImageSrc, endImageSrc);
    //return imageSrc;
    return '<img src="'+imageSrc+'" class="post-image">';
}

function theContent(content){
    startContent = content.indexOf('<h2');
    endContent = content.slice(startContent).indexOf('<figure')+startContent-4;
    newContent = content.slice(startContent, endContent);
    startlink = newContent.indexOf('<a');
    endlink = newContent.indexOf('</a>')+4;
    newContent = newContent.replace(newContent.slice(startlink, endlink), 'Comment résilier');
    return newContent;
}

function theIframe(content){
    startIframe = content.indexOf('<iframe');
    endIframe = content.indexOf('</iframe>')+9;
    iframe = content.slice(startIframe, endIframe);
    return iframe;
}