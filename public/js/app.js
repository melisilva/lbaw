document.addEventListener("DOMContentLoaded", addEventListeners);

function addEventListeners() {
    let postEditors = document.getElementById("update-post");
    if (postEditors != null) {
      postEditors.addEventListener("click", sendEditPostRequest);
    }

    let postEditorsIcons = document.querySelectorAll("a .fa.fa-pencil");
    if (postEditorsIcons.length != 0) {
        [].forEach.call(postEditorsIcons, function (editorIcon) {
            editorIcon.addEventListener("click", addEditedFlag);
        });
    }

    let postDeleters = document.querySelectorAll("a .fa.fa-trash");
    if (postDeleters.length != 0) {
        console.log(postDeleters);
        [].forEach.call(postDeleters, function (deleter) {
            deleter.addEventListener("click", sendDeletePostRequest);
        });
    }

    let postCreator = document.getElementById("add-post");

    if (postCreator != null) {
        postCreator.addEventListener("click", sendNewPostRequest);
    }
}

function addEditedFlag() {
    var temp = document.querySelector("i[edited=true]");
    if (temp != null) {
        temp.removeAttribute("edited");
    }
    this.setAttribute("edited", true);
}

function encodeForAjax(data) {
    if (data == null) return null;

    return Object.keys(data)
        .map(function (k) {
            return encodeURIComponent(k) + "=" + encodeURIComponent(data[k]);
        })
        .join("&");
}

function sendAjaxRequest(method, url, data, handler) {
    let request = new XMLHttpRequest();
    request.open(method, url, true);
    request.setRequestHeader(
        "X-CSRF-TOKEN",
        document.querySelector('meta[name="csrf-token"]').content
    );
    request.setRequestHeader(
        "Content-Type",
        "application/x-www-form-urlencoded"
    );
    request.addEventListener("load", handler);
    request.send(encodeForAjax(data));
}

function sendDeletePostRequest() {
    console.log("qlq coisa");
    let id = this.closest("div").getAttribute("data-id");
    let div = this.closest("div");
    console.log(this.parentElement.parentElement);
    console.log(this);
    console.log(div);

    sendAjaxRequest("delete", "/api/post/" + id, null, postDeletedHandler);
}

function sendEditPostRequest() {
    var temp = document.querySelector("i[edited=true]");
    let id = temp.closest("div.posts").getAttribute("data-id");
    let conteudo = document.getElementById("edit-post-body-input").value;
    document.getElementById("edit-post-body-input").value = "";
    sendAjaxRequest(
        "post",
        "/api/post/" + id,
        { body: conteudo },
        postEditedHandler
    );
}

function sendNewPostRequest(event) {
    let conteudo = document.getElementById("new-post-body-input").value;

    if (conteudo != "") {
        sendAjaxRequest(
            "post",
            "/api/post/create",
            { body: conteudo },
            postCreatedHandler
        );
    }
}

function postCreatedHandler() {
    /*if (this.status != 200) {
      window.location = "/";
    }*/

    let post = JSON.parse(this.responseText),
        new_post = createPost(post);

    // Clean the modal up.
    document.getElementById("new-post-body-input").value = "";
    document.getElementById("new-post-annex-input").value = "";

    document.getElementsByClassName("col post-scroll")[0].prepend(new_post);
    window.location.reload();
}

function postDeletedHandler() {
    console.log(this);
    let post = JSON.parse(this.responseText);
    let element = document.querySelector(
        'div.posts[data-id="' + post.id + '"]'
    );
    element.remove();
}

function postEditedHandler() {
  console.log(this.responseText);
    let post = JSON.parse(this.responseText);
    let element = document.querySelector(
        'div.posts[data-id="' + post.id + '"]'
    );
    element.children[1].innerHTML = post.conteudo;
}

function createPost(post) {
    let new_post = document.createElement("div");
    new_post.classList.add(
        "d-flex",
        "justify-content-between",
        "p-2",
        "px-3",
        "posts"
    );
    new_post.setAttribute("data-id", post.id);
    new_post.style.cssText =
        "height: auto;width: auto;max-width: 100%;flex-direction: column;border: 3px solid rgba(118,118,118,0.16);border-radius: 15px;";

    var src = "../" + post.member.fotoperfil;

    new_post.innerHTML =
        `
      <header class="d-flex flex-row align-items-center" style="margin-bottom: 10px;">
        <img class="post-user" src="` +
        src +
        `"> 
        <h1 class="post-author" style="vertical-align: middle;margin: 0px;margin-top: -2px;">
          ${post.member.nome}
        </h1>
      </header> `;
    if (post.annex != null) {
        new_post.innerHTML =
            new_post.innerHTML +
            `<img class="img-fluid" src="` +
            window.location.hostname +
            "/assets/img/" +
            post.anexo +
            `">`;
    }
    console.log("CONSOLE LOG:" + src);

    new_post.innerHTML =
        new_post.innerHTML +
        `
        <p class="p-1 px-0">${post.conteudo}</p>
        <footer>
        <a href="#like" style="margin-right: 15px;"><i class="fa fa-thumbs-up"></i></a>
        <a href="#comment" style="margin-right: 15px;"><i class="fa fa-comment" style="padding: 0px;margin-top: 0px;margin-right: 0px;"></i></a>
            <a style="margin-right: 15px;"><i class="fa fa-pencil" style="padding: 0px;margin-top: 0px;margin-right: 0px;" data-bs-target="#edit-post" data-bs-toggle="modal"></i>
            </a>
            <a href="#"><i class="fa fa-trash"></i></a>
        </footer>
    `;
    return new_post;
}