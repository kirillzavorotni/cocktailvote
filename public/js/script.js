const product = document.querySelector('.product');
const email_input = document.querySelector('#email_id');
const send_email_btn = document.querySelector('#send_btn_id');
const vote_btn = document.querySelector('#vote_btn_id');
const send_again_btn = document.querySelector('#send_again_btn_id');

const additionalMessages = {
    "emailAgain": "Enter email again"
};

const codeAction = {
    /**
     * Confirm email message
     */
    "cem": {
        status: true,
        action: (msg, status) => {
            if (status) {
                showSendAgainBtn();
                if (msg) {
                    product.innerHTML = msg;
                    return;
                }
                product.innerHTML = "";
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
                    product.innerHTML = msg;
                    return;
                }
                product.innerHTML = "";
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
                    product.innerHTML = msg;
                    return;
                }
                product.innerHTML = "";
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
                if (data["voteCount"]) {
                    product.innerHTML = `You have ${data["voteCount"]} votes`;
                    showVoteBtn();
                } else {
                    product.innerHTML = "You don't have votes";
                }
                hideAllControl();
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
                showSendAgainBtn();
                if (msg) {
                    product.innerHTML = msg;
                    return;
                }
                product.innerHTML = "";
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
                hideAllControl();
                if (msg) {
                    product.innerHTML = msg;
                    return;
                }
                product.innerHTML = "";
            }
        }
    },
};


function setEventHandlers() {
    send_email_btn.addEventListener('click', sendEmail);
    send_again_btn.addEventListener('click', resetEmail)
}

function resetEmail() {
    resetCookie();
    showAllControl();
    hideSendAgainBtn();
}

function resetCookie() {
    document.cookie = "id=; expires=Thu, 01 Jan 1970 00:00:01 GMT";
}

function showAllControl() {
    email_input.style.display = "block";
    send_email_btn.style.display = "block";
    send_again_btn.style.display = "block";
}

function hideAllControl() {
    email_input.style.display = "none";
    send_email_btn.style.display = "none";
    send_again_btn.style.display = "none";
    vote_btn.style.display = "none";
}

function hideSendAgainBtn() {
    send_again_btn.style.display = "none";
    product.innerHTML = additionalMessages.emailAgain;
}

function showSendAgainBtn() {
    email_input.style.display = "none";
    send_email_btn.style.display = "none";
    send_again_btn.style.display = "block";
}

function showVoteBtn() {
    vote_btn.style.display = "block";
    email_input.style.display = "none";
    send_email_btn.style.display = "none";
    send_again_btn.style.display = "none";
}

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

function sendEmail() {
    fetch(
        'auth',
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json;charset=utf-8'
            },
            body: JSON.stringify({
                email: email_input.value
            })
        }
    )
        .then(response => response.json())
        .then(data => {
            if (codeAction[data["errorCode"]]) {
                codeAction[data["errorCode"]]["action"](data["msg"], data["status"], data["data"]);
            }
        })
        .catch(error => {
            console.error(error);
        })

}

function makeValidateRequest() {
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
        .then(data => {
            if (codeAction[data["errorCode"]]) {
                codeAction[data["errorCode"]]["action"](data["msg"], data["status"], data["data"]);
            }
        })
        .catch(error => {
            console.error(error);
        })
}

function start() {
    setEventHandlers();

    if (checkCookie()) {
        makeValidateRequest();
    }
}

start();
