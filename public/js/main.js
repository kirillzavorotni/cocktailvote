// CONFIGS //
const codeAction = {
    /**
     * Confirm email message
     */
    "cem": {
        status: true,
        action: (msg, status) => {
            if (status) {
                hideCheckBoxWrap();
                hideInputEmailWrap();
                hideSendEmailBtnWrap();
                showSendEmailAgainBtnWrap();
                if (msg) {
                    showAjaxMsgWrap(msg);
                    return;
                }
            }
        }
    },
    /**
     * Email is not correct
     */
    "enc": {
        status: false,
        action: (msg, status) => {
            if (!status) {
                if (msg) {
                    showAjaxMsgWrap(msg);
                    return;
                }
            }
        }
    },
    /**
     * Can't to create user
     */
    "cncu": {
        status: false,
        action: (msg, status) => {
            if (!status) {
                if (msg) {
                    // product.innerHTML = msg;
                    return;
                }
                // product.innerHTML = "";
            }
        }
    },
    /**
     * Successful authorization
     */
    "sa": {
        status: true,
        action: (msg, status, data) => {
            if (status) {
                hideCheckBoxWrap();
                hideInputEmailWrap();
                hideSendEmailBtnWrap();
                if (data["voteCount"]) {
                    showAjaxMsgWrap(`Осталось голосов: ${data["voteCount"]}`);
                    showVoteBtnWrap();
                }
                if (!data["voteCount"]) {
                    hideVoteBtnWrap();
                    showAjaxMsgWrap("У вас не осталось голосов");
                }
            }
        }
    },
    /**
     * Make confirm email
     */
    "mc": {
        status: true,
        action: (msg, status) => {
            if (status) {
                hideCheckBoxWrap();
                hideInputEmailWrap();
                hideSendEmailBtnWrap();
                hideVoteBtnWrap();
                showSendEmailAgainBtnWrap();
                if (msg) {
                    showAjaxMsgWrap(msg);
                    return;
                }
            }
        }
    },
    /**
     * Not vote access
     */
    "nva": {
        status: false,
        action: (msg, status) => {
            if (!status) {
                hideSendEmailBtnWrap();
                hideInputEmailWrap();
                hideCheckBoxWrap();
                hideSendEmailAgainBtnWrap();
                hideVoteBtnWrap();
                if (msg) {
                    showAjaxMsgWrap(msg);
                    return;
                }
            }
        }
    },
    /**
     * Vote is taken
     */
    "vit": {
        status: true,
        action: (msg, status, data) => {
            if (status) {
                if (msg && !data["voteCount"]) {
                    showAjaxMsgWrap(`${msg}. У вас не осталось голосов`);
                    hideVoteBtnWrap();
                    hideSendEmailAgainBtnWrap();
                    hideCheckBoxWrap();
                    hideInputEmailWrap();
                    hideSendEmailBtnWrap();
                }

                if (msg && data["voteCount"]) {
                    showAjaxMsgWrap(`${msg}. Осталось голосов: ${data["voteCount"]}`);
                    hideSendEmailAgainBtnWrap();
                    hideCheckBoxWrap();
                    hideInputEmailWrap();
                    hideSendEmailBtnWrap();
                }
            }
        }
    },
    "end": {
        status: false,
        action: (msg, status) => {
            if (!status) {
                if (!status) {
                    resetCookie();
                    showInputEmailWrap();
                    showSendEmailBtnWrap();
                    showCheckBoxWrap();
                    if (msg) {
                        showAjaxMsgWrap(msg);
                        return;
                    }
                }
            }
        }
    }
};
// CONFIGS //

const classes = {
    checkBoxWrapClass: "checkbox",
    emailBtn: "email_btn",
    sendEmailAgain: "send_again_email_btn",
    emailInput: "emailInput",
    serverMsgWrap: "server_msg_wrap",
    popupWithForm: "popup-with-form",
    sendVoteBtn: "vote_btn"
};

const messages = {
    incorrectEmail: "Укажите правильный Email"
};

const body = document.querySelector('body'),
    loaderList = document.querySelectorAll('.loader_wrap'),
    loader = document.querySelectorAll('.loader'),
    questionsWindow = document.querySelector('.questions'),
    okBtn = document.querySelector('.ok'),
    checkBoxWrapList = document.querySelectorAll('.check_box_wrap'),
    buttonSubmitList = document.querySelectorAll('.email_btn_wrap'),
    emailInputWrapList = document.querySelectorAll('.emailInput'),
    sendEmailBtnWrapList = document.querySelectorAll('.email_btn_wrap'),
    ajaxMmsgWrapList = document.querySelectorAll('.ajax_msg_wrap'),
    sendEmailAgainBtnWrapList = document.querySelectorAll('.send_again_email_wrap'),
    sendVoteBtnWrapList = document.querySelectorAll('.vote_btn_wrap');


start();


////////////////////////////// INTERNAL //////////////////////////////
function checkCookie() {
    if (
        !document.cookie ||
        document.cookie.split("=").length !== 2 ||
        document.cookie.split("=")[0] !== 'id' ||
        !document.cookie.split("=")[1]
    ) {
        return false;
    }
    return true;
}

function resetCookie() {
    document.cookie = "id=; expires=Thu, 01 Jan 1970 00:00:01 GMT";
}

function setEventHandlers() {
    body.addEventListener("click", (e) => {
        changeCheckBoxState(e.target);
        sendEmail(e.target);
        sendEmailAgain(e.target);
        sendVote(e.target);
    });

    okBtn.addEventListener("click", (e) => {
        e.preventDefault();
        questionsWindow.style.display = 'none';
    });

    buttonSubmitList.forEach(item => {
        item.addEventListener("click", (e) => {
            e.preventDefault();
        })
    });

    sendEmailAgainBtnWrapList.forEach(item => {
        item.addEventListener("click", (e) => {
            e.preventDefault();
        })
    });

    sendVoteBtnWrapList.forEach(item => {
        item.addEventListener("click", (e) => {
            e.preventDefault();
        })
    });
}

function sendVote(item) {
    if (item.classList.contains(classes.sendVoteBtn)) {
        const product_id = item.closest('form').getAttribute('data-id');
        addVoteRequest(product_id);
    }
}

function sendEmailAgain(item) {
    if (item.classList.contains(classes.sendEmailAgain)) {
        resetCookie();
        hideSendEmailAgainBtnWrap();
        hideAjaxMsgWrap();
        showCheckBoxWrap();
        showInputEmailWrap();
        showSendEmailBtnWrap();
    }
}

function sendEmail(item) {
    if (item.classList.contains(classes.emailBtn)) {
        const email = item.closest('form').getElementsByClassName(classes.emailInput)[0].value;

        if (!validateEmail(email)) {
            showAjaxMsgWrap(messages.incorrectEmail);
            return;
        }

        hideAjaxMsgWrap();
        ajaxMail(email);
    }
}

function validateEmail(email) {
    var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
    if(reg.test(email) == false) {
        return false;
    }
    return true;
}

function changeCheckBoxState(item) {
    if (item.classList.contains(classes.checkBoxWrapClass)) {
        if (item.checked) {
            enableBtn(item.closest('form').getElementsByClassName(classes.emailBtn)[0]);
        } else {
            disableBtn(item.closest('form').getElementsByClassName(classes.emailBtn)[0]);
        }
    }
}

function start() {
    if (!checkCookie()) {
        showQuestions();
    } else {
        makeValidateRequest();
    }
    setEventHandlers();
}

////////////////////////////// INTERNAL //////////////////////////////


/////////////////////////// SHOW/HIDE ELEMENTS ////////////////////////////////////
function showQuestions() {
    questionsWindow.style.display = 'block';
}

function showCheckBoxWrap() {
    checkBoxWrapList.forEach(item => {
        item.style.display = 'block';
    });
}

function hideCheckBoxWrap() {
    checkBoxWrapList.forEach(item => {
        item.style.display = 'none';
    });
}

function showSendEmailBtnWrap() {
    sendEmailBtnWrapList.forEach(item => {
        item.style.display = 'block';
    });
}

function hideSendEmailBtnWrap() {
    sendEmailBtnWrapList.forEach(item => {
        item.style.display = 'none';
    });
}

function showInputEmailWrap() {
    emailInputWrapList.forEach(item => {
        item.style.display = 'block';
    });
}

function hideInputEmailWrap() {
    emailInputWrapList.forEach(item => {
        item.style.display = 'none';
    });
}

function showAjaxMsgWrap(msg) {
    ajaxMmsgWrapList.forEach(item => {
        item.style.display = 'block';
        item.innerHTML = "";
        item.innerHTML = msg;
    });
}

function hideAjaxMsgWrap() {
    ajaxMmsgWrapList.forEach(item => {
        item.innerHTML = "";
        item.style.display = 'none';
    });
}

function showSendEmailAgainBtnWrap(msg) {
    sendEmailAgainBtnWrapList.forEach(item => {
        item.style.display = 'block';
    });
}

function hideSendEmailAgainBtnWrap() {
    sendEmailAgainBtnWrapList.forEach(item => {
        item.style.display = 'none';
    });
}

function showVoteBtnWrap() {
    sendVoteBtnWrapList.forEach(item => {
        item.style.display = 'block';
    });
}

function hideVoteBtnWrap() {
    sendVoteBtnWrapList.forEach(item => {
        item.style.display = 'none';
    });
}

function showLoaderWrap() {
    loaderList.forEach(item => {
        item.style.display = 'block';
    });
}

function hideLoaderWrap() {
    loaderList.forEach(item => {
        item.style.display = 'none';
    });
}

function showLoader() {
    loader.forEach(item => {
        item.style.display = 'block';
    });
}

function hideLoader() {
    loader.forEach(item => {
        item.style.display = 'none';
    });
}


function disableBtn(btn) {
    btn.setAttribute("disabled", "true");
    btn.style.backgroundColor = "";
}

function enableBtn(btn) {
    btn.removeAttribute("disabled");
    btn.style.backgroundColor = "#12b0c9";
}

/////////////////////////// SHOW/HIDE ELEMENTS ////////////////////////////////////


////////////////////////////////////// AJAX //////////////////////////////////////
function ajaxMail(email) {
    showLoaderWrap();
    showLoader();

    hideSendEmailBtnWrap();
    hideCheckBoxWrap();
    hideInputEmailWrap();

    fetch(
        'auth',
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json;charset=utf-8'
            },
            body: JSON.stringify({
                email: email
            })
        }
    )
        .then(response => response.json())
        .then(data => {

            hideLoaderWrap();
            hideLoader();

            showSendEmailBtnWrap();
            showCheckBoxWrap();
            showInputEmailWrap();

            if (codeAction[data["msgCode"]]) {
                codeAction[data["msgCode"]]["action"](data["msg"], data["status"], data["data"]);
            }
        })
        .catch(error => {

            hideLoaderWrap();
            hideLoader();

            showSendEmailBtnWrap();
            showCheckBoxWrap();
            showInputEmailWrap();

            console.error(error);
        })
}

function makeValidateRequest() {
    showLoaderWrap();
    showLoader();

    hideSendEmailBtnWrap();
    hideCheckBoxWrap();
    hideInputEmailWrap();

    fetch(
        'validate',
        {
            method: 'GET',
            headers: {
                'Content-Type': 'Content-Type:text/html'
            },
        }
    )
        .then(response => response.json())
        .then((data) => {

            hideLoaderWrap();
            hideLoader();

            showSendEmailBtnWrap();
            showCheckBoxWrap();
            showInputEmailWrap();

            if (codeAction[data["msgCode"]]) {
                codeAction[data["msgCode"]]["action"](data["msg"], data["status"], data["data"]);
            }
        })
        .catch(error => {

            hideLoaderWrap();
            hideLoader();

            showSendEmailBtnWrap();
            showCheckBoxWrap();
            showInputEmailWrap();

            console.error(error);
        })
}

function addVoteRequest(prodId) {
    showLoaderWrap();
    showLoader();

    hideSendEmailBtnWrap();
    hideCheckBoxWrap();
    hideInputEmailWrap();
    hideVoteBtnWrap();

    fetch(
        'vote',
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json;charset=utf-8'
            },
            body: JSON.stringify({
                product_id: prodId
            })
        }
    )
        .then(response => response.json())
        .then(data => {

            hideLoaderWrap();
            hideLoader();

            showSendEmailBtnWrap();
            showCheckBoxWrap();
            showInputEmailWrap();
            showVoteBtnWrap();

            if (codeAction[data["msgCode"]]) {
                codeAction[data["msgCode"]]["action"](data["msg"], data["status"], data["data"]);
            }
        })
        .catch(error => {

            hideLoaderWrap();
            hideLoader();

            showSendEmailBtnWrap();
            showCheckBoxWrap();
            showInputEmailWrap();
            showVoteBtnWrap();

            console.error(error);
        })
}

////////////////////////////////////// AJAX //////////////////////////////////////
