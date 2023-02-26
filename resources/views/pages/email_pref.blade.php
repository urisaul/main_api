<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Prefrences</title>
</head>

<body>
    Subscibe to Parsha weekly qustion sheet:
    <br>
    <form action="" id="main-form">
        <label for="email-inp">Email:</label>
        <input type="email" required name="email" id="email-inp">
        <button id="sub-btn">Subscibe!</button>
        <button id="unsub-btn" style="display: none;">Unsubscibe</button>
    </form>
    <div id="messages"></div>

    <script>
        const params = new Proxy(new URLSearchParams(window.location.search), {
            get: (searchParams, prop) => searchParams.get(prop),
        });
        let action = params.action;

        
        let main_form = document.querySelector("form#main-form");
        let email_inp = document.querySelector("input#email-inp");
        let sub_btn = document.querySelector("button#sub-btn");
        let unsub_btn = document.querySelector("button#unsub-btn");
        
        if (action === "unsub") {
            email_inp.value = params.user_e;
            sub_btn.style.display = "none";
            unsub_btn.style.display = "inline";
        }

        main_form.addEventListener("submit", (e) => {
            e.preventDefault();
            let email = email_inp.value;

            let body = {
                "user_e": email,
                action
            }

            body = JSON.stringify(body);

            fetch("{{ url('') }}/api/parsha/email_pref", {
                method: "post",
                body,
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                }
            })
                .then(res => res.json())
                .then(res => {
                    document.querySelector("div#messages").innerHTML = res.message || "";

                    // toggle button display (for both buttons)
                    sub_btn.style.display   = sub_btn.style.display   === "none" ? "inline" : "none";
                    unsub_btn.style.display = unsub_btn.style.display === "none" ? "inline" : "none";

                    // toggle action
                    action = action === "sub" ? "unsub" : "sub";
                })
                .catch(err => {
                    document.querySelector("div#messages").innerHTML = err.message || "an error has occurred please contact us at support@urisaul.com";
                })
        });
        sub_btn.addEventListener("click", (e) => {
            let email = document.querySelector("input#email-inp").value;
            console.log(email)
        })
    </script>
</body>

</html>