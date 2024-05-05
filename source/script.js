// Selecting elements from the DOM
const form = document.querySelector(".wrapper form");
const fullURL = form.querySelector("input");
const shortenBtn = form.querySelector("form button");
const blurEffect = document.querySelector(".blur-effect");
const popupBox = document.querySelector(".popup-box");
const infoBox = popupBox.querySelector(".info-box");
const form2 = popupBox.querySelector("form");
const shortenURL = popupBox.querySelector("form .shorten-url");
const copyIcon = popupBox.querySelector("form .copy-icon");
const saveBtn = popupBox.querySelector("button");

// Prevent the default form submission behavior
form.onsubmit = (e) => {
    e.preventDefault();
};

// Handle 'Shorten' button click
shortenBtn.onclick = () => {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/url-controll.php", true);

    xhr.onload = () => {
        if (xhr.readyState == 4 && xhr.status == 200) {
            let data = xhr.response;

            if (data.length <= 5) {
                // Display popup box
                blurEffect.style.display = "block";
                popupBox.classList.add("show");
                
                let domain = 'http://localhost/TinyURL/';
                shortenURL.value = domain + data;

                // Handle copying of shortened URL
                copyIcon.onclick = () => {
                    shortenURL.select();
                    document.execCommand("copy");
                    alert("Link Copied");
                };

                // Handle 'Save' button click
                saveBtn.onclick = () => {
                    form2.onsubmit = (e) => {
                        e.preventDefault();
                    };

                    let xhr2 = new XMLHttpRequest();
                    xhr2.open("POST", "php/save-url.php", true);

                    xhr2.onload = () => {
                        if (xhr2.readyState == 4 && xhr2.status == 200) {
                            let data = xhr2.response;

                            if (data == "success") {
                                location.reload();
                            } else {
                                infoBox.classList.add("error");
                                infoBox.innerText = data;
                            }
                        }
                    };

                    let shorten_url1 = shortenURL.value;
                    let hidden_url = data;

                    xhr2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhr2.send("shorten_url=" + shorten_url1 + "&hidden_url=" + hidden_url);
                };
            }else{
                alert(data);
            }
        }
    };

    // Send form data via XMLHttpRequest
    let formData = new FormData(form);
    xhr.send(formData);
};
