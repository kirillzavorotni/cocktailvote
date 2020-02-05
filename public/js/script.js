const product = document.querySelector('.product');
const email_input = document.querySelector('#email_id');
const send_email_btn = document.querySelector('#send_btn_id');
const vote_btn = document.querySelector('#vote_btn_id');
const send_again_btn = document.querySelector('#send_again_btn_id');

const codeAction = {
    /**
     * Confirm email message
     */
    "cem": {
        status: true,
        action: (msg, status) => {
            if (status) {
                email_input.style.display = "none";
                send_email_btn.style.display = "none";
                send_again_btn.style.display = "block";
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
    }
};


function setEventHandlers() {
    send_email_btn.addEventListener('click', sendEmail);
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
}


function start() {
    setEventHandlers();

    if (checkCookie()) {
        makeValidateRequest();
    }
}

// --------------------------------------------------------------
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
            // console.log("data = ", data);
            if (codeAction[data["errorCode"]]) {
                codeAction[data["errorCode"]]["action"](data["msg"], data["status"]);
            }
        })
        .catch(error => {
            console.error(error);
        })

}

start();
