const email_input = document.querySelector('#email_id');
const send_email_btn = document.querySelector('#send_btn_id');
const vote_btn = document.querySelector('#vote_btn_id');
const send_again_btn = document.querySelector('#send_again_btn_id');


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
        'validate'
    ).then(response => {
        return response.json();
    }).then(json => {
        console.log(json);
    })
}


function start() {
    //-----------------------
    setEventHandlers();
    //-----------------------

    if (checkCookie()) {
        makeValidateRequest();
    }
}

// --------------------------------------------------------------
function sendEmail() {
    // fetch(
    //     'auth',
    //     {
    //         method: 'POST',
    //         headers: {
    //             'Content-Type': 'application/json;charset=utf-8'
    //         },
    //         body: JSON.stringify({
    //             email: email_input.value
    //         })
    //     }
    // ).then(response => {
    //     return response.json();
    // }).then(json => {
    //     console.log(json);
    // }).catch(error => {
    //     console.error('Ошибка:', error);
    // });

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
    ).catch(error => {
        console.error('Ошибка:', error);
    });
}

start();
